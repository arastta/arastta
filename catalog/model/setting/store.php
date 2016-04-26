<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelSettingStore extends Model {
    public function getStores($data = array()) {
        $store_data = $this->cache->get('store');

        if (!$store_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

            $store_data = $query->rows;

            $this->cache->set('store', $store_data);
        }

        return $store_data;
    }
}
