<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Finder\Finder as SFinder;

class ControllerExtensionInstaller extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('extension/installer');

        $data = $this->language->all();

        $this->document->setTitle($data['heading_title']);

        $data['token'] = $this->session->data['token'];

        $directories = glob(DIR_UPLOAD . 'temp-*', GLOB_ONLYDIR);

        if ($directories) {
            $data['error_warning'] = $this->language->get('error_temporary');
        } else {
            $data['error_warning'] = '';
        }

        if (!extension_loaded('xml')) {
            $this->error['error_warning'] = $this->language->get('error_xml');
        }

        if (!extension_loaded('zip')) {
            $this->error['error_warning'] = $this->language->get('error_zip');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/installer.tpl', $data));
    }

    public function upload()
    {
        $this->load->language('extension/installer');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (!empty($this->request->files['file']['name'])) {
                if ((strrchr($this->request->files['file']['name'], '.') != '.zip' && strrchr($this->request->files['file']['name'], '.') != '.xml') && (substr($this->request->files['file']['name'], -10) != '.ocmod.zip' && substr($this->request->files['file']['name'], -10) != '.ocmod.xml')) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            // If no temp directory exists create it
            $path = 'temp-' . md5(mt_rand());

            if (!is_dir(DIR_UPLOAD . $path)) {
                $this->filesystem->mkdir(DIR_UPLOAD . $path);
            }

            // Set the steps required for installation
            $json['step'] = array();
            $json['overwrite'] = array();

            if (strrchr($this->request->files['file']['name'], '.') == '.xml') {
                $xml = file_get_contents($this->request->files['file']['tmp_name']);

                if ($xml) {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->loadXml($xml);
                    $code = $dom->getElementsByTagName('code')->item(0);
                }

                $file = DIR_UPLOAD . $path . '/install.xml';

                if (empty($code)) {
                    $this->session->data['vqmod_file_name'] = $this->request->files['file']['name'];
                    $file = DIR_UPLOAD . $path . '/' . $this->request->files['file']['name'];

                    if (is_file(DIR_VQMOD . 'xml/' . $this->request->files['file']['name'])) {
                        $json['overwrite'][] = 'vqmod/xml/' . $this->request->files['file']['name'];
                    }
                } else {
                    $code = $code->nodeValue;

                    if (is_file(DIR_SYSTEM . 'xml/' . $code . '.xml')) {
                        $json['overwrite'][] = 'system/xml/' . $code . '.xml';
                    }
                }

                // If xml file copy it to the temporary directory
                move_uploaded_file($this->request->files['file']['tmp_name'], $file);

                if (file_exists($file)) {
                    $json['step'][] = array(
                        'text' => $this->language->get('text_xml'),
                        'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/parseXML', 'token=' . $this->session->data['token'], 'SSL')),
                        'path' => $path
                    );

                    // Refresh Modification
                    $json['step'][] = array(
                        'text' => $this->language->get('text_modification'),
                        'url'  => str_replace('&amp;', '&', $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'] . '&extensionInstaller=1', 'SSL')),
                        'path' => $path
                    );
                    // Clear temporary files
                    $json['step'][] = array(
                        'text' => $this->language->get('text_remove'),
                        'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/remove', 'token=' . $this->session->data['token'], 'SSL')),
                        'path' => $path
                    );
                } else {
                    $json['error'] = $this->language->get('error_file');
                }
            }

            // If zip file copy it to the temp directory
            if (strrchr($this->request->files['file']['name'], '.') == '.zip') {
                $file = DIR_UPLOAD . $path . '/upload.zip';

                move_uploaded_file($this->request->files['file']['tmp_name'], $file);

                $this->prepareSteps($file, $path, $json);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function unzip()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Sanitize the filename
        $file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/upload.zip';

        if (!file_exists($file)) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.unzip', array(&$file));

            $installer = new Installer($this->registry);

            if (!$installer->unzip($file)) {
                $json['error'] = $this->language->get('error_unzip');
            }

            // Remove Zip
            unlink($file);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function parseFiles()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $check_folder = 0;
        if ($folder = opendir(DIR_UPLOAD . $this->request->post['path'].'/')) {
            while (false !== ($sub_folder = readdir($folder))) {
                if ($sub_folder != "." && $sub_folder != "..") {
                    if (is_dir(DIR_UPLOAD . $this->request->post['path'] . '/' . $sub_folder)) {
                        $check_folder = 1 ;
                        break;
                    }
                }
            }
        }

        if (empty($check_folder)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        $directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/upload/';

        if (!is_dir($directory)) {
            $directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/UPLOAD/';
        }

        if (!is_dir($directory)) {
            $directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/Upload/';
        }

        if (!is_dir($directory)) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.parseFiles', array(&$directory));

            $replaceFolderName = array(
                'admin/language/english' => 'admin/language/en-GB',
                'catalog/language/english' => 'catalog/language/en-GB'
            );

            // Replace language folder
            foreach ($replaceFolderName as $folderKey => $folderValue) {
                $this->replaceFolderName($directory . $folderKey, $directory . $folderValue);
            }

            // Get a list of files ready to upload
            $files = array();

            $path = array($directory . '*');

            while (count($path) != 0) {
                $next = array_shift($path);

                foreach (glob($next) as $file) {
                    if (is_dir($file)) {
                        $path[] = $file . '/*';
                    }

                    // Replace directory path for language
                    foreach ($replaceFolderName as $folderKey => $folderValue) {
                        $file = str_replace($folderKey, $folderValue, $file);
                    }

                    // $edit_page_url = explode("upload", strtolower($file));
                    $edit_page_url = explode("upload", $file);
                    if (empty($edit_page_url[2])) {
                        $edit_page_url = explode($sub_folder, $edit_page_url);
                        $edit_page_url = 'upload' . $edit_page_url[1];
                    } else {
                        $edit_page_url = 'upload' . $edit_page_url[2];
                    }

                    $this->permissionControl($edit_page_url);
                    $this->replaceFile($file);

                    $files[] = $file;
                }
            }

            foreach ($files as $file) {
                // Upload everything in the upload directory

                $destination = substr($file, strlen($directory));
                if (is_dir($file)) {
                    if (!is_dir(DIR_ROOT . $destination)) {
                        if (!mkdir(DIR_ROOT . $destination)) {
                            $json['error'] = sprintf($this->language->get('error_ftp_directory'), $destination);
                            exit();
                        }
                    }
                }

                if (is_file($file)) {
                    if (!copy($file, DIR_ROOT . $destination)) {
                        $json['error'] = sprintf($this->language->get('error_ftp_file'), $file);
                    }
                }
            }

            $this->response->setOutput(json_encode($json));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function permissionControl($edit_page_url)
    {
        $this->load->model('extension/installer');

        $search_page = array(
            'appearance'    => 'appearance',
            'catalog'       => 'catalog',
            'product'       => 'product',
            'common'        => 'common',
            'dashboard'     => 'dashboard',
            'design'        => 'design',
            'editor'        => 'editor',
            'error'         => 'error',
            'extension'     => 'extension',
            'feed'          => 'feed',
            'localisation'  => 'localisation',
            'marketing'     => 'marketing',
            'module'        => 'module',
            'payment'       => 'payment',
            'report'        => 'report',
            'sale'          => 'sale',
            'search'        => 'search',
            'setting'       => 'setting',
            'shipping'      => 'shipping',
            'system'        => 'system',
            'tool'          => 'tool',
            'total'         => 'total',
            'twofactorauth' => 'twofactorauth',
            'user'          => 'user'
        );

        foreach ($search_page as $page) {
            if (strpos($edit_page_url, 'upload/admin/controller/'.$page) === false) {
                continue;
            }

            $permissions_page = explode($page, $edit_page_url);
            $permissions_page = $page . str_replace('.php', '', $permissions_page[1]);

            $this->model_extension_installer->addPermission($permissions_page);
        }
    }

    public function replaceFile($file)
    {
        if (!is_file($file)) {
            return;
        }

        $replace_text = array(
            'VQMod::modCheck' => 'modification',
            'new Mail()' => 'new Mail($this->config->get(\'config_mail\'))'
        );

        $content = file_get_contents($file);

        // Just add admin file in content.
        if (strpos($file, 'admin') !== false && strpos($content, 'design/layout') !== false) {
            $replace_text['design/layout'] = 'appearance/layout';
            $replace_text['design_layout'] = 'appearance_layout';
        }

        foreach ($replace_text as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        $content = preg_replace('/\$this->(trigger|event)->(fire|trigg er)\(\'(.*)\',[\s]*(.*)\);/', '\$this->trigger->fire("$3", array(&$4));', $content);

        file_put_contents($file, $content);
    }

    public function replaceFolderName($originalName, $changeName)
    {
        if (is_dir($originalName)) {
            rename($originalName, $changeName);
        }
    }

    public function parseSQL()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.sql';

        if (!file_exists($file)) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            $lines = file($file);

            if ($lines) {
                // Fire event
                $this->trigger->fire('pre.admin.extension.parseSQL', array(&$lines));

                try {
                    $sql = '';

                    foreach ($lines as $line) {
                        if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                            $sql .= $line;

                            if (preg_match('/;\s*$/', $line)) {
                                $sql = str_replace(" `oc_", " `" . DB_PREFIX, $sql);
                                $sql = str_replace(" `ar_", " `" . DB_PREFIX, $sql);

                                $this->db->query($sql);

                                $sql = '';
                            }
                        }
                    }
                } catch (Exception $exception) {
                    $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function parseXML()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $ocmod = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.xml';

        if (file_exists($ocmod)) {
            $file = $ocmod;
        } elseif (!empty($this->session->data['vqmod_file_name'])) {
            $vqmod = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/' . $this->session->data['vqmod_file_name'];

            if (file_exists($vqmod)) {
                $file = $vqmod;
            } else {
                $file = null;
            }
        }

        if (!file_exists($file)) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            $xml = file_get_contents($file);

            if ($xml) {
                // Fire event
                $this->trigger->fire('pre.admin.extension.xml', array(&$xml));

                if (!empty($this->session->data['vqmod_file_name'])) {
                    $arastta_mod = DIR_VQMOD . 'xml/'. $this->session->data['vqmod_file_name'];
                } else {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->loadXml($xml);

                    $code = $dom->getElementsByTagName('code')->item(0);
                    $code = $code->nodeValue;

                    $arastta_mod = DIR_SYSTEM . 'xml/' . $code . '.xml';
                }

                if (!copy($file, $arastta_mod)) {
                    $json['error'] =   $this->language->get('error_copy_xmls_file');
                }
            }
        }

        unset($this->session->data['vqmod_file_name']);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function parsePHP()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.php';

        if (!file_exists($file)) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.parsePHP', array(&$file));

            try {
                include($file);
            } catch (Exception $exception) {
                $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function parseJSON()
    {
        $this->load->language('extension/installer');
        $this->load->model('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.json';

        if (!file_exists($file)) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.parseJSON', array(&$file));

            $addon_params = array();
            $product_id = $product_name = $product_type = $product_version = '';

            $content = file_get_contents($file);
            $install_data = json_decode($content, true);

            if (json_last_error() != JSON_ERROR_NONE) {
                $json['error'] = $this->language->get('error_json_' . json_last_error());
            } elseif (count($install_data) and array_key_exists('extension', $install_data)) {
                $product_type = 'extension';
            } elseif (count($install_data) and array_key_exists('theme', $install_data)) {
                $product_type = 'theme';
            } elseif (count($install_data) and array_key_exists('translation', $install_data)) {
                $this->load->model('localisation/language');

                $product_type = 'translation';

                $translation_data = $install_data['translation'];

                $translation_data['locale'] = null;
                $translation_data['status'] = 1;
                $translation_data['sort_order'] = 0;

                $language_id = $this->model_extension_installer->languageExist($translation_data['directory']);
                if (!$language_id) {
                    $language_id = $this->model_localisation_language->addLanguage($translation_data);
                }
                $addon_params['language_id'] = $language_id;
            }

            // Add to addon table
            if (!$json && $product_type) {
                if (isset($install_data[$product_type]['product_id'])) {
                    $product_id = $install_data[$product_type]['product_id'];
                }

                if ($product_id) {
                    $this->load->model('extension/marketplace');

                    if (isset($install_data[$product_type]['name'])) {
                        $product_name = $install_data[$product_type]['name'];
                    }

                    if (isset($install_data[$product_type]['version'])) {
                        $product_version = $install_data[$product_type]['version'];
                    }

                    $addon_params['theme_ids'] = array();
                    $addon_params['extension_ids'] = array();

                    $addon_data = array(
                        'product_id' => $product_id,
                        'product_name' => $product_name,
                        'product_type' => $product_type,
                        'product_version' => $product_version,
                        'files' => '',
                        'params' => $addon_params
                    );

                    $this->model_extension_marketplace->addAddon($addon_data);

                    $this->cache->remove('addon');
                    $this->cache->remove('update');
                    $this->cache->remove('version');

                    // Set it to use in the next step, addExtensionTheme
                    $this->session->data['product_id'] = $product_id;
                    $this->session->data['installer_info'] = $install_data;
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function remove()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']);

        if (!is_dir($directory)) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.remove', array(&$directory));

            // Add to extension or theme tables
            $this->addExtensionTheme($directory);

            // Remove dir
            $this->filesystem->remove($directory);

            // Clear cache
            $this->cache->remove('addon');
            $this->cache->remove('update');
            $this->cache->remove('version');

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clear()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            $directories = glob(DIR_UPLOAD . 'temp-*', GLOB_ONLYDIR);

            // Remove dirs
            $this->filesystem->remove($directories);

            $json['success'] = $this->language->get('text_clear');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install()
    {
        $this->load->language('extension/installer');
        $json = array();
        $data = $this->utility->getRemoteData(html_entity_decode("http://arastta.io/" . rtrim($this->request->post['store'], 's') . "/1.0/download/" . $this->request->post['product_id'] . "/latest/" . VERSION . "/" . $this->config->get('api_key')), array('referrer' => true));

        $response = json_decode($data, true);
        if (empty($data) and isset($response['error']) or (isset($response['status']) and $response['status'] == 'Error')) {
            if (isset($response['error'])) {
                $json['error'] = $response['error'];
            } else {
                $json['error'] = $this->language->get('error_download');
            }
        }

        if ($data and !isset($json['error'])) {
            $path = 'temp-' . md5(mt_rand());
            $file = DIR_UPLOAD . $path . '/upload.zip';

            if (!is_dir(DIR_UPLOAD . $path)) {
                $this->filesystem->mkdir(DIR_UPLOAD . $path);
            }
            $ret = is_int(file_put_contents($file, $data)) ? true : false;

            if ($ret) {
                $this->request->post['path'] = $path;
                $this->prepareSteps($file, $path, $json);
            } else {
                $json['error'] = $this->language->get('error_file');
            }
        } else {
            $json['error'] = $this->language->get('error_download');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function readZip($zip, $path, &$json)
    {
        while ($entry = zip_read($zip)) {
            $zip_name = zip_entry_name($entry);

            // SQL
            if (substr($zip_name, 0, 11) == 'install.sql') {
                $json['step'][] = array(
                    'text' => $this->language->get('text_sql'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/parseSQL', 'token='.$this->session->data['token'], 'SSL')),
                    'path' => $path
                );
            }

            // XML
            if (substr($zip_name, 0, 11) == 'install.xml') {
                $json['step'][] = array(
                    'text' => $this->language->get('text_xml'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/parseXML', 'token='.$this->session->data['token'], 'SSL')),
                    'path' => $path
                );
            } elseif (substr($zip_name, -4) == '.xml') {
                $check = strpos($zip_name, '/');

                if (!empty($check) && $check > 0) {
                    $_msmod   = explode('/', $zip_name);
                    $zip_name = end($_msmod);
                }

                if (is_file(DIR_VQMOD.'xml/'.$zip_name)) {
                    $json['overwrite'][] = 'vqmod/xml/'.$zip_name;
                }

                if (is_file(DIR_SYSTEM.'xml/'.$zip_name)) {
                    $json['overwrite'][] = 'system/xml/'.$zip_name;
                }
            }

            // PHP
            if (substr($zip_name, 0, 11) == 'install.php') {
                $json['step'][] = array(
                    'text' => $this->language->get('text_php'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/parsePHP', 'token='.$this->session->data['token'], 'SSL')),
                    'path' => $path
                );
            }

            // JSON
            if (substr($zip_name, 0, 12) == 'install.json') {
                $json['step'][] = array(
                    'text' => $this->language->get('text_json'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/parseJSON', 'token='.$this->session->data['token'], 'SSL')),
                    'path' => $path
                );
            }

            // Compare admin files
            $file = DIR_ADMIN.substr($zip_name, 13);
            if (is_file($file) && strtolower(substr($zip_name, 0, 13)) == 'upload/admin/') {
                $json['overwrite'][] = substr($zip_name, 7);
            }

            // Compare catalog files
            $file = DIR_CATALOG.substr($zip_name, 15);

            if (is_file($file) && strtolower(substr($zip_name, 0, 15)) == 'upload/catalog/') {
                $json['overwrite'][] = substr($zip_name, 7);
            }

            // Compare image files
            $file = DIR_IMAGE.substr($zip_name, 13);

            if (is_file($file) && strtolower(substr($zip_name, 0, 13)) == 'upload/image/') {
                $json['overwrite'][] = substr($zip_name, 7);
            }

            // Compare system files
            $file = DIR_SYSTEM.substr($zip_name, 14);

            if (is_file($file) && strtolower(substr($zip_name, 0, 14)) == 'upload/system/') {
                $json['overwrite'][] = substr($zip_name, 7);
            }

            // Compare system files
            $file = DIR_VQMOD.substr($zip_name, 7);
            if (is_file($file) && strtolower(substr($zip_name, 0, 13)) == 'upload/vqmod/') {
                $json['overwrite'][] = substr($zip_name, 7);
            }
        }
    }

    public function prepareSteps($file, $path, &$json)
    {
        if (file_exists($file)) {
            $zip = zip_open($file);

            if ($zip) {
                // Zip
                $json['step'][] = array(
                    'text' => $this->language->get('text_unzip'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/unzip', 'token='.$this->session->data['token'], 'SSL')),
                    'path' => $path
                );

                // FTP
                $json['step'][] = array(
                    'text' => $this->language->get('text_ftp'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/parseFiles', 'token='.$this->session->data['token'], 'SSL')),
                    'path' => $path
                );

                // Send make and array of actions to carry out
                $this->readZip($zip, $path, $json);

                # Refresh Modification
                $json['step'][] = array(
                    'text' => $this->language->get('text_modification'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/modification/refresh', 'token='.$this->session->data['token'].'&extensionInstaller=1', 'SSL')),
                    'path' => $path
                );
                // Clear temporary files
                $json['step'][] = array(
                    'text' => $this->language->get('text_remove'),
                    'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/remove', 'token='.$this->session->data['token'], 'SSL')),
                    'path' => $path
                );

                zip_close($zip);
            } else {
                $json['error'] = $this->language->get('error_unzip');
            }
        } else {
            $json['error'] = $this->language->get('error_file');
        }
    }

    public function addExtensionTheme($directory)
    {
        $this->load->model('extension/installer');

        // Skip file scan for translations as they're all in one place
        if (isset($this->session->data['installer_info']) && array_key_exists('translation', $this->session->data['installer_info'])) {
            unset($this->session->data['product_id']);
            unset($this->session->data['installer_info']);

            return;
        }

        if (isset($this->session->data['product_id'])) {
            $this->load->model('extension/marketplace');

            $addon = $this->model_extension_marketplace->getAddon($this->session->data['product_id']);
            $params = json_decode($addon['params'], true);
        } else {
            $addon = array();
            $params = array();
            $params['theme_ids'] = array();
            $params['extension_ids'] = array();
        }

        $addon_files = array();

        // Get all files
        $files = $this->indexFiles($directory);

        foreach ($files as $id => $file) {
            $addon_files[] = $file['relative_path_name'];

            $type = $this->getAddonType($file['relative_path_name']);

            if (empty($type)) {
                continue;
            }

            $data = array();
            $data['params'] = array();

            if (isset($this->session->data['installer_info'])) {
                $data['info'] = $this->session->data['installer_info'];
            } else {
                $data['info'] = array(
                    'author' => array(
                        'name' => '',
                        'email' => '',
                        'website' => ''
                    )
                );
            }

            if (strstr($type, 'theme_')) {
                $this->load->model('appearance/theme');

                $tmp = explode('_', $type);

                $data['code'] = $tmp[1];

                if (empty($data['info']['author']['name'])) {
                    $info = $directory . '/upload/catalog/view/theme/' . $data['code'] . '/info.json';
                    if (file_exists($info)) {
                        $content = file_get_contents($info);

                        $data['info'] = json_decode($content, true);
                    } else {
                        $data['info']['theme'] = array(
                            'name' => ucfirst($data['code']),
                            'description' => '',
                            'version' => '',
                            'product_id' => ''
                        );
                    }
                }

                $theme_id = $this->model_extension_installer->themeExist($data['code']);
                if (!$theme_id) {
                    $theme_id = $this->model_appearance_theme->addTheme($data);
                }

                $params['theme_ids'][] = $theme_id;
            } else {
                $this->load->model('extension/extension');

                $code = str_replace('.php', '', $file['file_name']);

                $data['type'] = $type;
                $data['code'] = $code;

                if (empty($data['info']['author']['name'])) {
                    $data['info']['extension'] = array(
                        'description' => '',
                        'version' => '',
                        'product_id' => ''
                    );
                }

                // Will use the heading_title of the language file
                $data['info']['extension']['name'] = '';

                $extension_id = $this->model_extension_installer->extensionExist($type, $code);
                if (!$extension_id) {
                    $extension_id = $this->model_extension_extension->addExtension($data);
                }

                $params['extension_ids'][] = $extension_id;

                // Call install method, if exists
                $this->load->controller($type . '/' . $code . '/install');
            }
        }

        if (empty($params)) {
            // What do you do :)
        }

        $addon['params'] = $params;
        $addon['files'] = json_encode($addon_files);

        if (!empty($addon['addon_id'])) {
            $this->model_extension_marketplace->editAddon($addon['addon_id'], $addon);
        } else {
            $this->load->model('extension/marketplace');

            $addon['product_id'] = '';
            $addon['product_name'] = '';
            $addon['product_type'] = '';
            $addon['product_version'] = '';

            $this->model_extension_marketplace->addAddon($addon);
        }

        unset($this->session->data['product_id']);
        unset($this->session->data['installer_info']);
    }

    protected function indexFiles($path)
    {
        $data = array();

        $upload_path = $path;
        if (file_exists($path . '/upload')) {
            $upload_path = $path . '/upload';
        }

        if (!file_exists($upload_path)) {
            return $data;
        }

        $finder = new SFinder();
        $finder->files()->in($upload_path);

        foreach ($finder as $file) {
            $f = array();
            $f['file_name'] = $file->getFilename(); // amazon_button.php
            $f['relative_path'] = $file->getRelativePath(); // admin\controller\module
            $f['relative_path_name'] = $file->getRelativePathname(); // admin\controller\module\amazon_button.php

            $data[] = $f;
        }

        if (file_exists($path . '/install.xml')) {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $xml = file_get_contents($path . '/install.xml');
            $dom->loadXML($xml);

            $code = $dom->getElementsByTagName('code')->item(0);
            $code = $code->nodeValue;

            $f = array();
            $f['file_name'] = $code . '.xml';
            $f['relative_path'] = 'system' . DIRECTORY_SEPARATOR . 'xml';
            $f['relative_path_name'] = $f['relative_path'] . DIRECTORY_SEPARATOR . $f['file_name'];

            $data[] = $f;
        }

        return $data;
    }

    protected function getAddonType($path)
    {
        $type = '';

        if (!strstr($path, 'admin' . DIRECTORY_SEPARATOR . 'controller') && !strstr($path, 'common' . DIRECTORY_SEPARATOR . 'header.tpl')) {
            return $type;
        }

        $tmp = explode('\\', $path);

        $ext_types = array('captcha', 'editor', 'feed', 'module', 'other', 'payment', 'shipping', 'total', 'twofactorauth');

        if (isset($tmp[2]) && in_array($tmp[2], $ext_types)) {
            $type = $tmp[2];
        } elseif (isset($tmp[4]) && isset($tmp[5]) && isset($tmp[6]) && ($tmp[6] == 'header.tpl')) { // catalog/view/theme/xyz/template/common/header.tpl
            $type = 'theme_'.$tmp[3];
        } else {
            $type = 'other';
        }

        return $type;
    }
}
