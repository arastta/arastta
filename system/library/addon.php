<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Finder\Finder;

class Addon extends Object {

    public function __construct($registry) {
        $this->db = $registry->get('db');
        $this->cache = $registry->get('cache');
        $this->config = $registry->get('config');
        $this->utility = $registry->get('utility');
    }

    public function getAddons() {
        $data = $this->cache->get('addon');

        if (empty($data)) {
            $data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addon ORDER BY product_type, addon_id");

            foreach ($query->rows as $result) {
                $data[$result['product_id']] = array(
                    'product_id'          => $result['product_id'],
                    'product_name'        => $result['product_name'],
                    'product_type'        => $result['product_type'],
                    'product_version'     => $result['product_version'],
                    'addon_files'         => $result['addon_files']
                );
            }

            $this->cache->set('addon', $data);
        }

        return $data;
    }

    public function getAddon($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addon WHERE `product_id` = " . $id);

        return $query->row;
    }

    public function addAddon($data) {
        // Get files the zip includes
        $files = json_encode($this->indexFiles($data['dir']));
        $params = json_encode($data['addon_params']);

		$this->db->query("INSERT INTO " . DB_PREFIX . "addon SET `product_id` = " . (int) $data['product_id'] . ", `product_name` = '" . $this->db->escape($data['product_name']) . "', `product_type` = '" . $this->db->escape($data['product_type']) . "', `product_version` = '" . $this->db->escape($data['product_version']) . "', `addon_files` = '" . $this->db->escape($files) . "', `params` = '" . $this->db->escape($params) . "'");
    }

    public function removeAddon($id) {
        $this->db->query("DELETE FROM ". DB_PREFIX . "addon WHERE `product_id` = " . $id);
    }

    public function indexFiles($path) {
        $upload_path = $path;
        if (file_exists($path . '/upload')) {
            $upload_path = $path . '/upload';
        }

        $finder = new Finder();
        $finder->files()->in($upload_path);

        $data = array();

        foreach ($finder as $file) {
            $data[] = $file->getRelativePathname();
        }

        if (file_exists($path . '/install.xml')) {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $xml = file_get_contents($path . '/install.xml');
            $dom->loadXml($xml);

            $code = $dom->getElementsByTagName('code')->item(0);
            $code = $code->nodeValue;

            $data[] = 'system/xml/' . $code . '.xml';
        }

        return $data;
    }
}