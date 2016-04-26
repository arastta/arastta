<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelCatalogUrlAlias extends Model {

    public function getUrlAlias($keyword, $language_id = 1) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' AND language_id = '" . $this->db->escape($language_id) . "'");

        return $query->row;
    }

    public function getAlias($type, $id, $language_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($type) . "_id=" . (int)$id . "' AND language_id = '" . (int)$language_id . "'");

        return $query->row;
    }

    public function addAlias($type, $id, $alias, $language_id) {
        $alias = $this->seo->generateAlias($alias, $id, $language_id);

        if ($alias) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($type) . "_id=" . (int)$id . "', keyword = '" . $this->db->escape($alias) . "', language_id = '" . (int)$language_id . "'");
        }
    }

    public function clearAliases($type, $id, $language_id = null) {
        if ($language_id) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($type) . "_id=" . (int)$id . "' AND language_id = '" . (int)$language_id . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($type) . "_id=" . (int)$id . "'");
        }
    }

    public function getAliases($type, $id, $language_id = null) {
        if ($language_id) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($type) . "_id=" . (int)$id . "' AND language_id = '" . (int)$language_id . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($type) . "_id=" . (int)$id . "'");
        }

        return $query->rows;
    }
}
