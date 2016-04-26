<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelExtensionModification extends Model {
    public $is_vqmod = true;

    public static function handleXMLError($errno, $errstr, $errfile, $errline) {
        if ($errno == E_WARNING && (substr_count($errstr, 'DOMDocument::loadXML()') > 0)) {
            throw new DOMException(str_replace('DOMDocument::loadXML()', '', $errstr));
        } else {
            return false;
        }
    }

    public function applyMod() {
        // To catch XML syntax errors
        set_error_handler(array('ModelExtensionModification', 'handleXMLError'));

        if (class_exists('VQMod')) {
            $files = ($files = glob(DIR_SYSTEM . 'xml/*.xml')) ? $files : array();
        } else {
            // Merge vQmods and OCmods files
            $vqmod_files = ($vqmod_files = glob(DIR_VQMOD . 'xml/*.xml')) ? $vqmod_files : array();
            $system_files = ($system_files = glob(DIR_SYSTEM . 'xml/*.xml')) ? $system_files : array();
            $files = array_merge($vqmod_files, $system_files);
        }

        if (!empty($files) && $files) {
            foreach ($files as $file) {
                $xml_content = file_get_contents($file);

                $xml_content = preg_replace('/\$this->(trigger|event)->(fire|trigger)\((\'|")(.*)(\'|"),[\s]*(.*)\);/', '\$this->trigger->fire($3$4$5, array(&$6));', $xml_content);

                $xmls[$file] = $xml_content;
            }
        }

        $modification = $log = $original = array();

        if (empty($xmls)) {
            $log[] = 'Modification::applyMod - NO XML FILES READABLE IN XML FOLDERS';
            $this->writeLog($log);

            return false;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;

        foreach ($xmls as $file => $xml) {
            try {
                $dom->loadXML($xml);

                $modification_node = $dom->getElementsByTagName('modification')->item(0);

                $version = '2.5.1';
                $vqmver  = $modification_node->getElementsByTagName('vqmver')->item(0);
                if ($vqmver) {
                    $version_check = $vqmver->getAttribute('required');
                    if (strtolower($version_check) == 'true' && version_compare($version, $vqmver->nodeValue, '<')) {
                        $log[] = "Modification::applyMod - VQMOD VERSION '" . $vqmver->nodeValue . "' OR ABOVE REQUIRED, XML FILE HAS BEEN SKIPPED";
                        $log[] = "  vqmver = '$vqmver'";
                        $log[] = '----------------------------------------------------------------';

                        return false;
                    }
                }
            } catch (Exception $e) {
                $log[] = 'Modification::applyMod - INVALID XML FILE(' . $file . '): ' . $e->getMessage();
                $this->writeLog($log);
                continue;
            }

            $this->isVqmod($dom);
            $file_nodes      = $modification_node->getElementsByTagName('file');
            $modification_id = @$modification_node->getElementsByTagName('id')->item(0)->nodeValue;
            if (!$modification_id) {
                $modification_id = $modification_node->getElementsByTagName('code')->item(0)->nodeValue;
            }

            $log[] = "Modification::applyMod - Processing '" . $modification_id . "'";
            $log[] = "";

            foreach ($file_nodes as $file_node) {

                $files = $this->getFiles($file_node, $log);
                if ($files === false) {
                    return false;
                }

                $operation_nodes = $file_node->getElementsByTagName('operation');

                foreach ($files as $file) {
                    $key = $this->getFileKey($file);
                    if ($key == '') {
                        $log[] = "Modification::applyMod - UNABLE TO GENERATE FILE KEY:";
                        $log[] = "  modification id = '$modification_id'";
                        $log[] = "  file name = '$file'";
                        $log[] = "";
                        continue;
                    }
                    if (!isset($modification[$key])) {
                        $modification[$key] = preg_replace('~\r?\n~', "\n", file_get_contents($file));
                        $original[$key]     = $modification[$key];
                        // Log
                        $log[] = 'FILE: ' . $key;
                    }

                    if (!$log = $this->operationNode($operation_nodes, $modification, $modification_id, $file, $key, $log)) {
                        return false;
                    }

                } // $files
            } // $file_nodes

            $log[] = "Modification::applyMod - Done '" . $modification_id . "'";
            $log[] = '----------------------------------------------------------------';
            $log[] = "";
        }

        restore_error_handler();

        $this->writeLog($log);
        $this->writeMods($modification, $original);
    }

    public function writeLog($log) {
        if ((defined('DIR_LOG'))) {
            $modification = new Log('modification.log');
            $modification->write(implode("\n", $log));
        }
    }

    public function isVqmod(DOMDocument $dom) {
        $modification_node = $dom->getElementsByTagName('modification')->item(0);
        if ($modification_node) {
            $vqmver_node = $modification_node->getElementsByTagName('vqmver')->item(0);
            if ($vqmver_node) {
                $this->is_vqmod = true;

                return;
            }
        }

        $this->is_vqmod = false;
    }

    public function getFiles($file_node, &$log) {
        $file_node_path  = $file_node->getAttribute('path');
        $file_node_name  = $file_node->getAttribute('name');
        $file_node_error = $file_node->getAttribute('error');

        $files      = array();
        $file_names = explode(',', $file_node_name);
        if (isset($file_names[0]) && $file_names[0] == '') {
            $file_names = explode(',', $file_node_path);
            $file_node_path = '';
            if ($file_names === false) {
                $file_names = array();
            }
        }

        foreach ($file_names as $file_name) {
            $path = '';

            // Get the full path of the files that are going to be used for modification
            if (substr($file_node_path . $file_name, 0, 7) == 'catalog') {
                $path = DIR_CATALOG . substr($file_node_path . $file_name, 8);
            } elseif (substr($file_node_path . $file_name, 0, 5) == 'admin') {
                $path = DIR_ADMIN . substr($file_node_path . $file_name, 6);
            } elseif (substr($file_node_path . $file_name, 0, 6) == 'system') {
                $path = DIR_SYSTEM . substr($file_node_path . $file_name, 7);
            }

            $paths = glob($path);

            if (($paths === false) || is_array($paths) && (count($paths) == 0)) {
                switch ($file_node_error) {
                    case 'skip':
                        break;
                    case 'abort':
                        $log[] = "Modification::getFiles - UNABLE TO FIND FILE(S), XML PARSING ABORTED:";
                        $log[] = "  file = '$path'";
                        $log[] = '----------------------------------------------------------------';

                        return false;
                    case 'log':
                    default:
                        $log[] = "Modification::getFiles - UNABLE TO FIND FILE(S), IGNORED:";
                        $log[] = "  file = '$path'";
                        break;
                }
            } else {
                foreach ($paths as $file) {
                    if (is_file($file)) {
                        $files[] = $file;
                    } else {
                        switch ($file_node_error) {
                            case 'skip':
                                break;
                            case 'abort':
                                $log[] = "Modification::getFiles - NOT A FILE, XML PARSING ABORTED:";
                                $log[] = "  file = '$file'";
                                $log[] = '----------------------------------------------------------------';

                                return false;
                            case 'log':
                            default:
                                $log[] = "Modification::getFiles - NOT A FILE, IGNORED:";
                                $log[] = "  file = '$file'";
                                break;
                        }
                    }
                }
            }
        }

        return $files;
    }

    public function getFileKey($file) {
        $key = '';
        if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
            $key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
        } elseif (substr($file, 0, strlen(DIR_ADMIN)) == DIR_ADMIN) {
            $key = 'admin/' . substr($file, strlen(DIR_ADMIN));
        } elseif (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
            $key = 'system/' . substr($file, strlen(DIR_SYSTEM));
        }

        return $key;
    }

    public function operationNode($nodes, &$modification, $modification_id, $file, $key, $log) {
        foreach ($nodes as $operation_node) {
            $operation_node_error = $operation_node->getAttribute('error');
            if (($operation_node_error != 'skip') && ($operation_node_error != 'log') && $this->is_vqmod) {
                $operation_node_error = 'abort';
            }

            $ignoreif_node = $operation_node->getElementsByTagName('ignoreif')->item(0);
            if ($ignoreif_node) {
                $ignoreif_node_regex = $ignoreif_node->getAttribute('regex');
                $ignoreif_node_value = trim($ignoreif_node->nodeValue);
                if ($ignoreif_node_regex == 'true' && preg_match($ignoreif_node_value, $modification[$key])) {
                    continue;
                } elseif (strpos($modification[$key], $ignoreif_node_value) !== false) {
                    continue;
                }
            }

            $status = false;

            $search_node = $operation_node->getElementsByTagName('search')->item(0);
            $add_node    = $operation_node->getElementsByTagName('add')->item(0);

            if ($search_node->getAttribute('position')) {
                $position = $search_node->getAttribute('position');
            } elseif ($add_node->getAttribute('position')) {
                $position = $add_node->getAttribute('position');
            } else {
                $position = 'replace';
            }

            $search_node_indexes = $this->getIndexes($search_node->getAttribute('index'));

            if ($search_node->getAttribute('offset')) {
                $_offset = $search_node->getAttribute('offset');
            } elseif ($add_node->getAttribute('offset')) {
                $_offset = $add_node->getAttribute('offset');
            } else {
                $_offset = '0';
            }

            $search_node_regex = ($search_node->getAttribute('regex')) ? $search_node->getAttribute('regex') : 'false';
            $limit             = $search_node->getAttribute('limit');
            if (!$limit) {
                $limit = -1;
            }

            $search_node_trim  = ($search_node->getAttribute('trim') == 'false') ? 'false' : 'true';
            $search_node_value = ($search_node_trim == 'true') ? trim($search_node->nodeValue) : $search_node->nodeValue;
            $add_node_trim     = ($add_node->getAttribute('trim') == 'true') ? 'true' : 'false';
            $add_node_value    = ($add_node_trim == 'true') ? trim($add_node->nodeValue) : $add_node->nodeValue;

            $index_count = 0;
            $tmp         = explode("\n", $modification[$key]);
            $line_max    = count($tmp) - 1;

            // apply the next search and add operation to the file content
            switch ($position) {
                case 'top':
                    $tmp[(int) $_offset] = $add_node_value . $tmp[(int) $_offset];
                    break;
                case 'bottom':
                    $offset = $line_max - (int) $_offset;
                    if ($offset < 0) {
                        $tmp[-1] = $add_node_value;
                    } else {
                        $tmp[$offset] .= $add_node_value;;
                    }
                    break;
                default:
                    $changed = false;
                    foreach ($tmp as $line_num => $line) {
                        if (strlen($search_node_value) == 0 && ($operation_node_error == 'log' || $operation_node_error == 'abort')) {
                            $log[] = "Modification::operationNode - EMPTY SEARCH CONTENT ERROR:";
                            $log[] = "  modification id = '$modification_id'";
                            $log[] = "  file name = '$file'";
                            $log[] = "";
                            break;
                        }

                        if ($search_node_regex == 'true') {
                            $pos = @preg_match($search_node_value, $line);
                            if ($pos === false) {
                                if ($operation_node_error == 'log' || $operation_node_error == 'abort') {
                                    $log[] = "Modification::operationNode - INVALID REGEX ERROR:";
                                    $log[] = "  modification id = '$modification_id'";
                                    $log[] = "  file name = '$file'";
                                    $log[] = "  search = '$search_node_value'";
                                    $log[] = "";
                                }
                                continue 2; // continue with next operation_node
                            } elseif ($pos == 0) {
                                $pos = false;
                            }
                        } else {
                            $pos = strpos($line, $search_node_value);
                        }


                        if ($pos !== false) {
                            $index_count++;
                            $changed = true;
                            if (!$search_node_indexes || ($search_node_indexes && in_array($index_count, $search_node_indexes))) {
                                $this->positionAttr($position, $line_num, $_offset, $tmp, $add_node_value, $line_max, $search_node_value, $line, $search_node_regex, $limit);
                                $status = true;
                            }
                        }
                    }

                    if (!$changed) {
                        $skip_text = ($operation_node_error == 'skip' || $operation_node_error == 'log') ? '(SKIPPED)' : '(ABORTING MOD)';
                        if ($operation_node_error == 'log' || $operation_node_error) {
                            $log[] = "Modification::operationNode - SEARCH NOT FOUND $skip_text:";
                            $log[] = "  modification id = '$modification_id'";
                            $log[] = "  file name = '$file'";
                            $log[] = "  search = '$search_node_value'";
                            $log[] = "";
                        }

                        if ($operation_node_error == 'abort') {
                            $log[] = 'ABORTING!';
                            $log[] = '----------------------------------------------------------------';
                            $this->writeLog($log);

                            return false;
                        }
                    }
                    break;
            }

            ksort($tmp);
            $modification[$key] = implode("\n", $tmp);


            if (!$status && $search_node_regex == 'true') {
                $match = array();

                preg_match_all($search_node_value, $modification[$key], $match, PREG_OFFSET_CAPTURE);

                // Remove part of the the result if a limit is set.
                if ($limit > 0) {
                    $match[0] = array_slice($match[0], 0, $limit);
                }

                if ($match[0]) {
                    $log[]  = 'REGEX: ' . $search_node_value;
                    $status = $changed = true;
                }

                // Make the modification
                $modification[$key] = preg_replace($search_node_value, $add_node_value, $modification[$key], $limit);
            }


            if (!$status && !$this->is_vqmod) {
                // Log
                $log[] = 'NOT FOUND!';
            }

        }

        return $log;
    }

    public function getIndexes($search_node_index) {
        if ($search_node_index !== '') {
            $tmp = explode(',', $search_node_index);
            foreach ($tmp as $k => $v) {
                if (!$this->is_vqmod) {
                    $tmp[$k] += 1;
                }
            }
            $tmp = array_unique($tmp);

            return empty($tmp) ? false : $tmp;
        } else {
            return false;
        }
    }

    public function writeMods($modification, $original) {
        foreach ($modification as $key => $value) {
            // Only create a file if there are changes
            if ($original[$key] != $value) {
                $this->filesystem->dumpFile(DIR_MODIFICATION . $key, $value, 0755);
            }
        }
    }

    protected function positionAttr($position, $line_num, $_offset, &$tmp, $add_node_value, $line_max, $search_node_value, $line, $search_node_regex, $limit) {
        switch ($position) {
            case 'before':
                $offset       = ($line_num - $_offset < 0) ? -1 : $line_num - $_offset;
                $tmp[$offset] = empty($tmp[$offset]) ? $add_node_value : $add_node_value . "\n" . $tmp[$offset];
                break;
            case 'after':
                $offset       = ($line_num + $_offset > $line_max) ? $line_max : $line_num + $_offset;
                $tmp[$offset] = $tmp[$offset] . "\n" . $add_node_value;
                break;
            case 'ibefore':
                $tmp[$line_num] = str_replace($search_node_value, $add_node_value . $search_node_value, $line);
                break;
            case 'iafter':
                $tmp[$line_num] = str_replace($search_node_value, $search_node_value . $add_node_value, $line);
                break;
            default:
                if (!empty($_offset)) {
                    if ($_offset > 0) {
                        for ($i = 1; $i <= $_offset; $i++) {
                            if (isset($tmp[$line_num + $i])) {
                                $tmp[$line_num + $i] = '';
                            }
                        }
                    } elseif ($_offset < 0) {
                        for ($i = -1; $i >= $_offset; $i--) {
                            if (isset($tmp[$line_num + $i])) {
                                $tmp[$line_num + $i] = '';
                            }
                        }
                    }
                }
                if ($search_node_regex == 'true') {
                    $tmp[$line_num] = preg_replace($search_node_value, $add_node_value, $line, $limit);
                } else {
                    $tmp[$line_num] = str_replace($search_node_value, $add_node_value, $line);
                }
                break;
        }
    }
}
