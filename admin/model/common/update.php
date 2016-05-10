<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelCommonUpdate extends Model
{

    public function check()
    {
        // Fire event
        $this->trigger->fire('pre.admin.update.check');

        $this->cache->remove('addon');
        $this->cache->remove('update');
        $this->cache->remove('version');

        return true;
    }

    public function changelog()
    {
        $output = '';

        $url = 'https://api.github.com/repos/arastta/arastta/releases';

        $json = $this->utility->getRemoteData($url);

        if (empty($json)) {
            return $output;
        }

        $parsedown = new ParsedownExtra();

        $releases = json_decode($json);

        foreach ($releases as $release) {
            if ($release->tag_name <= VERSION) {
                continue;
            }
            
            if ($release->prerelease == true) {
                continue;
            }

            if (empty($release->body)) {
                continue;
            }

            $output .= '<h2><span class="label label-primary">'.$release->tag_name.'</span></h2>';

            // Parse markdown output
            $markdown = str_replace('## Changelog', '', $release->body);

            $output .= $parsedown->text($markdown);

            $output .= '<hr>';
        }

        return $output;
    }

    // Upgrade
    public function update()
    {
        $version = $this->request->get['version'];
        $product_id = $this->request->get['product_id'];

        if (!$data = $this->downloadUpdate($product_id, $version)) {
            return false;
        }

        $path = 'temp-' . md5(mt_rand());
        $file = DIR_UPLOAD . $path . '/upload.zip';

        if (!is_dir(DIR_UPLOAD . $path)) {
            $this->filesystem->mkdir(DIR_UPLOAD . $path);
        }

        $uploaded = is_int(file_put_contents($file, $data)) ? true : false;

        if (!$uploaded) {
            return false;
        }

        // Fire event
        $this->trigger->fire('pre.admin.update.update', array(&$product_id));

        // Force enable maintenance mode
        $maintenance_mode = $this->config->get('maintenance_mode');
        $this->config->set('maintenance_mode', 1);

        $installer = new Installer($this->registry);

        if (!$installer->unzip($file)) {
            return false;
        }

        // Remove Zip
        $this->filesystem->remove($file);

        if ($product_id == 'core') {
            $temp_path = DIR_UPLOAD . $path;
            $install_path = $temp_path . '/install';

            // Load the update script, if available
            if (is_file($install_path.'/update.php')) {
                require_once($install_path.'/update.php');
            }

            // Don't copy the install folder
            $this->filesystem->remove($install_path);

            // Move all files/folders from temp path
            try {
                $this->filesystem->mirror($temp_path, DIR_ROOT, null, array('override' => true));
            } catch (Exception $e) {
                return false;
            }

            // Delete the temp path
            $this->filesystem->remove($temp_path);
        } else {
            // Required for ftp & remove extension functions
            $this->request->post['path'] = $path;

            if ($this->filesystem->exists(DIR_UPLOAD . $path . '/install.json')) {
                $install_data = json_decode(file_get_contents(DIR_UPLOAD . $path . '/install.json'), true);

                // Set it to use in the next step, addExtensionTheme
                $this->session->data['product_id'] = $product_id;
                $this->session->data['installer_info'] = $install_data;
            }

            $ftp = $this->load->controller('extension/installer/parseFiles');
            $remove = $this->load->controller('extension/installer/remove');

            $this->db->query("UPDATE `" . DB_PREFIX . "addon` SET `product_version` = '" . $this->db->escape($version) . "' WHERE `product_id` = '" . (int)$product_id . "'");
        }

        // Restore maintenance mode
        $this->config->set('maintenance_mode', $maintenance_mode);

        // Fire event
        $this->trigger->fire('post.admin.update.update', array(&$product_id));

        return true;
    }

    public function countUpdates()
    {
        return array_sum(array_map("count", $this->getUpdates()));
    }

    public function getUpdates()
    {
        $data = $this->cache->get('update');

        if (empty($data)) {
            $data = array();

            $this->load->model('extension/marketplace');

            $addons = $this->model_extension_marketplace->getAddons(true);

            $versions = $this->getVersions($addons);

            foreach ($versions as $key => $version) {
                // Addons (extensions, themes, translations) comes as array
                if (is_array($version)) {
                    foreach ($version as $id => $addon_version) {
                        $addon = $addons[$id];
                        $type = $addon['product_type'];

                        if (version_compare($addon['product_version'], $addon_version) != 0) {
                            $data[$type][$addon['product_id']] = $addon_version;
                        }
                    }
                } elseif ($key == 'core') {
                    if (version_compare(VERSION, $version) != 0) {
                        $data['core'] = $version;
                    }
                }
            }

            $this->cache->set('update', $data);
        }

        return $data;
    }

    public function getVersions($addons = array())
    {
        $data = $this->cache->get('version');

        if (empty($data)) {
            $data = array();

            // Check core first
            $info = $this->utility->getInfo();
            $base_url = 'http://arastta.io';

            $url = $base_url.'/core/1.0/version/'.$info['arastta'].'/'.$info['php'].'/'.$info['mysql'].'/'.$info['langs'].'/'.$info['stores'];

            $data['core'] = $this->getRemoteVersion($url);

            // Then addons
            if (!empty($addons)) {
                foreach ($addons as $addon) {
                    $type = $addon['product_type'];

                    $url = $base_url.'/'.$type.'/1.0/version/'.$addon['product_id'].'/'.$addon['product_version'].'/'.$info['arastta'];

                    $data[$type][$addon['product_id']] = $this->getRemoteVersion($url);
                }
            }

            $this->cache->set('version', $data);
        }

        return $data;
    }

    public function getRemoteVersion($url)
    {
        $remote_data = $this->utility->getRemoteData($url, array('referrer' => true));

        if (is_string($remote_data)) {
            $version = json_decode($remote_data);

            if (is_object($version)) {
                $latest = $version->latest;
            } else {
                $latest = '0.0.0';
            }
        } else {
            $latest = '0.0.0';
        }

        return $latest;
    }

    public function downloadUpdate($product_id, $version)
    {
        // Check core first
        $info = $this->utility->getInfo();
        $base_url = 'http://arastta.io';

        if ($product_id == 'core') {
            $url = $base_url.'/core/1.0/update/'.$version.'/'.$info['php'].'/'.$info['mysql'];
        } else {
            $this->load->model('extension/marketplace');

            $addons = $this->model_extension_marketplace->getAddons(true);
            
            $type = $addons[$product_id]['product_type'];

            $url = $base_url.'/'.$type.'/1.0/update/'.$product_id.'/'.$version.'/'.$info['arastta'].'/'.$info['api'];
        }

        $options['timeout'] = 30;
        $options['referrer'] = true;

        $file = $this->utility->getRemoteData($url, $options);

        return $file;
    }
}
