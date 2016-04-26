<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelExtensionMarketplace extends Model
{

    public function getAddons($by_product_id = false)
    {
        $data = $this->cache->get('addon');

        if (empty($data)) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addon ORDER BY product_type, addon_id");

            $data = $query->rows;

            $this->cache->set('addon', $data);
        }

        if ($by_product_id) {
            $addons = $data;

            $data = array();

            foreach ($addons as $addon) {
                if (empty($addon['product_id'])) {
                    continue;
                }

                $data[$addon['product_id']] = $addon;
            }
        }

        return $data;
    }

    public function getAddon($product_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addon WHERE `product_id` = " . $product_id);

        return $query->row;
    }

    public function addAddon($data)
    {
        $params = json_encode($data['params']);

        $this->db->query("INSERT INTO " . DB_PREFIX . "addon SET `product_id` = " . (int) $data['product_id'] . ", `product_name` = '" . $this->db->escape($data['product_name']) . "', `product_type` = '" . $this->db->escape($data['product_type']) . "', `product_version` = '" . $this->db->escape($data['product_version']) . "', `files` = '" . $this->db->escape($data['files']) . "', `params` = '" . $this->db->escape($params) . "'");
    }

    public function editAddon($addon_id, $data)
    {
        $params = json_encode($data['params']);

        $this->db->query("UPDATE " . DB_PREFIX . "addon SET `product_id` = '" . (int)$data['product_id'] . "', `product_name` = '" . $this->db->escape($data['product_name']) . "', `product_type` = '" . $this->db->escape($data['product_type']) . "', `product_version` = '" . $this->db->escape($data['product_version']) . "', `files` = '" . $this->db->escape($data['files']) . "', `params` = '" . $this->db->escape($params) . "' WHERE `addon_id` = '" . (int)$addon_id . "'");
    }

    public function deleteAddon($addon_id)
    {
        $this->db->query("DELETE FROM ". DB_PREFIX . "addon WHERE `addon_id` = " . $addon_id);
    }
}
