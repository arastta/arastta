<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Finder\Finder;

class ModelSystemLanguageoverride extends Model
{

    public function getLanguages($filter_data = array())
    {
        if (isset($filter_data['filter_client']) and ($filter_data['filter_client'] != 'admin')) {
            $lang_dir = DIR_CATALOG.'language/';
        } else {
            $lang_dir = DIR_LANGUAGE;
        }

        $languages = new Finder();
        $languages->directories()->in($lang_dir)->exclude('override')->depth('== 0');

        $data = array();

        foreach ($languages as $language) {
            $data[] = $language->getRelativePathname();
        }

        return $data;
    }

    public function getStrings($filter_data = array())
    {
        $temp_data = array();

        if (isset($filter_data['filter_client']) and ($filter_data['filter_client'] != 'admin')) {
            $lang_dir = DIR_CATALOG.'language/';
        } else {
            $lang_dir = DIR_ADMIN.'language/';
        }

        $files = new Finder();
        $files->files()->in($lang_dir)->exclude('override');

        foreach ($files as $file) {
            // example: en-GB/catalog/attribute.php
            $path_name = str_replace('\\', '/', $file->getRelativePathname());

            $temp = explode('/', $path_name);

            if (isset($filter_data['filter_language']) and isset($temp[0]) and ($filter_data['filter_language'] != $temp[0])) {
                continue;
            }

            if (isset($filter_data['filter_folder']) and isset($temp[1]) and ($filter_data['filter_folder'] != $temp[1])) {
                continue;
            }

            if (isset($filter_data['filter_path']) and isset($temp[1]) and isset($temp[2]) and ($filter_data['filter_path'] != $temp[1].'/'.$temp[2])) {
                continue;
            }

            require($lang_dir.$path_name);

            $override_file = $lang_dir.'override/'.$path_name;
            if (file_exists($override_file)) {
                require($override_file);
            }

            if (empty($_)) {
                continue;
            }

            // Prepare the array key
            $key = str_replace('/', '_', $path_name);
            $key = str_replace('.php', '', $key);

            if (isset($filter_data['filter_text'])) {
                $_temp = array();

                foreach ($_ as $var => $val) {
                    if (!stristr($val, $filter_data['filter_text'])) {
                        continue;
                    }

                    $_temp[$var] = $val;
                }

                if (empty($_temp)) {
                    continue;
                }

                $temp_data[$key] = $_temp;
            } else {
                $temp_data[$key] = $_;
            }

            unset($_);
        }

        // Substract the first dimension count
        $total = count($temp_data, COUNT_RECURSIVE) - count($temp_data);

        $data = array();

        $counter = 0;
        foreach ($temp_data as $key => $strings) {
            $data[$key] = array();

            foreach ($strings as $var => $val) {
                $counter++;

                if ($counter < $filter_data['start']) {
                    continue;
                }

                if ($counter > ($filter_data['start'] + $filter_data['limit'])) {
                    break 2;
                }

                // Convert special characters to HTML entities
                $val = htmlspecialchars($val);

                // Un-escape quotation
                $val = stripslashes($val);

                $data[$key][$var] = $val;
            }
        }

        return array($data, $total);
    }

    public function saveStrings($files)
    {
        if (empty($files)) {
            return;
        }

        if (isset($this->request->get['filter_client']) and ($this->request->get['filter_client'] != 'admin')) {
            $lang_dir = DIR_CATALOG.'language/';
        } else {
            $lang_dir = DIR_ADMIN.'language/';
        }

        // lstrings[english_catalog_attribute][heading_title]
        foreach ($files as $file => $strings) {
            if (empty($strings)) {
                continue;
            }

            // Lets build the path
            $temp = explode('_', $file);
            $path = $temp[0].'/'.$temp[1];

            if (!empty($temp[2])) {
                $path .= '/'.$temp[2];
            }

            if (!empty($temp[3])) {
                $path .= '_'.$temp[3];
            }

            if (!empty($temp[4])) {
                $path .= '_'.$temp[4];
            }

            $path .= '.php';

            // Make sure the original file exists
            $org_file = $lang_dir.$path;
            if (!file_exists($org_file)) {
                continue;
            }

            require($org_file);

            $org_strings = $_;
            unset($_);

            // Load the override if available, to allow return back to the original
            $ovr_file = $lang_dir.'override/'.$path;
            if (file_exists($ovr_file)) {
                require($ovr_file);

                $ovr_strings = $_;
                unset($_);
            }

            $new_strings = array();

            foreach ($strings as $key => $value) {
                if (!isset($org_strings[$key])) {
                    continue;
                }

                // Convert HTML entities
                $value = html_entity_decode($value);

                if (($org_strings[$key] == $value)) {
                    // Remove from overrides if it's the same as original
                    if (isset($ovr_strings[$key])) {
                        unset($ovr_strings[$key]);
                    }

                    continue;
                }

                // Escape quotation
                $value = addslashes($value);

                $new_strings[$key] = $value;
            }

            if (!empty($ovr_strings)) {
                $new_strings = array_merge($ovr_strings, $new_strings);
            }

            // Do we have new strings?
            if (empty($new_strings)) {
                // Delete the override file if the changes have been reverted to original
                if (file_exists($ovr_file)) {
                    $this->filesystem->remove($ovr_file);
                }

                continue;
            }

            // Prepare the content
            $content = '<?php' . "\n";
            foreach ($new_strings as $key => $value) {
                $content .= '$_[\'' . $key . '\'] = \'' . $value . '\';' . "\n";
            }

            $content = rtrim($content, "\n");

            // Write into the file
            $this->filesystem->dumpFile($ovr_file, $content, 0644);
        }
    }
}
