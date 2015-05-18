<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class Update extends Object {

	public function __construct($registry) {
		$this->db = $registry->get('db');
        $this->cache = $registry->get('cache');
        $this->config = $registry->get('config');
        $this->utility = $registry->get('utility');
        $this->addon = new Addon($registry);
	}

    public function countUpdates() {
        return array_sum(array_map("count", $this->getUpdates()));
    }

    public function getUpdates() {
        $data = $this->cache->get('update');

        if (empty($data)) {
            $data = array();

            $addons = $this->addon->getAddons();

            $versions = $this->getVersions();

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
                }
                elseif ($key == 'core') {
                    if (version_compare(VERSION, $version) != 0) {
                        $data['core'] = $version;
                    }
                }
            }

            $this->cache->set('update', $data);
        }

        return $data;
    }

    public function getVersions() {
        $data = $this->cache->get('version');

        if (empty($data)) {
            $data = array();

            // Check core first
            $info = $this->utility->getInfo();
            $base_url = 'http://arastta.io/';

            $url = $base_url.'core/1.0/version/'.$info['arastta'].'/'.$info['php'].'/'.$info['mysql'].'/'.$info['langs'].'/'.$info['stores'];

            $data['core'] = $this->getRemoteVersion($url);

            // Then addons
            $addons = $this->addon->getAddons();

            if (!empty($addons)) {
                foreach ($addons as $addon) {
                    $type = $addon['product_type'];

                    $url = $base_url.$type.'/1.0/version/'.$addon['product_id'].'/'.$addon['product_version'].'/'.$info['arastta'];

                    $data[$type][$addon['product_id']] = $this->getRemoteVersion($url);
                }
            }

            $this->cache->set('version', $data);
        }

        return $data;
    }

    public function getRemoteVersion($url) {
        $remote_data = $this->utility->getRemoteData($url, array('referrer' => true));

        if (is_string($remote_data)) {
            $version = json_decode($remote_data);

            if (is_object($version)) {
                $latest = $version->latest;
            }
            else {
                $latest = '0.0.0';
            }
        }
        else {
            $latest = '0.0.0';
        }

        return $latest;
    }

    public function downloadUpdate($product_id, $version) {
        // Check core first
        $info = $this->utility->getInfo();
        $base_url = 'http://arastta.io/';

        if ($product_id == 'core') {
            $url = $base_url.'core/1.0/update/'.$version.'/'.$info['php'].'/'.$info['mysql'];
        }
        else {
            $addons = $this->addon->getAddons();
            $type = $addons[$product_id]['product_type'];

            //$url = $base_url.$type.'/v1.0/update/'.$product_id.'/'.$version.'/'.$info['arastta'].'/'.$this->config->get('api_key', 0);
            $url = $base_url.$type.'/1.0/update/'.$product_id.'/'.$version.'/'.$info['arastta'];
        }

        $file = $this->utility->getRemoteData($url, array('referrer' => true));

        return $file;
    }
}