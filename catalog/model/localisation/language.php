<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelLocalisationLanguage extends Model {
    public function getLanguage($language_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        return $query->row;
    }

    public function getLanguages() {
        $language_data = $this->cache->get('language');

        if (!$language_data) {
            $language_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' ORDER BY sort_order, name");

            foreach ($query->rows as $result) {
                $language_data[$result['code']] = array(
                    'language_id' => $result['language_id'],
                    'name'        => $result['name'],
                    'code'        => $result['code'],
                    'locale'      => $result['locale'],
                    'image'       => $result['image'],
                    'directory'   => $result['directory'],
                    'sort_order'  => $result['sort_order'],
                    'status'      => $result['status']
                );
            }

            $this->cache->set('language', $language_data);
        }

        return $language_data;
    }
    
    public function getTotalLanguages() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language");

        return $query->row['total'];
    }
}
