<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelSystemUrlmanager extends Model
{

    public function addAlias($data)
    {
        $this->trigger->fire('pre.admin.alias.add', array(&$data));

        $url_alias_id = 0;

        foreach ($data['alias'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET keyword = '" . $this->db->escape($value['seo_url']) . "', language_id = '" . (int) $language_id . "', query = '" . $this->db->escape($data['query']) . "'");

            $url_alias_id = $this->db->getLastId();
        }

        $this->trigger->fire('post.admin.alias.add');

        return $url_alias_id;
    }

    public function editAlias($data)
    {
        $this->trigger->fire('pre.admin.alias.edit', array(&$data));

        foreach ($data['alias'] as $language_id => $value) {
            if (!empty($data['alias_ids'][$language_id])) {
                $url_alias_id = $data['alias_ids'][$language_id];

                $this->db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword = '" . $this->db->escape($value['seo_url']) . "', query = '" . $this->db->escape($data['query']) . "' WHERE url_alias_id = '" . (int) $url_alias_id . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET keyword = '" . $this->db->escape($value['seo_url']) . "', language_id = '" . (int) $language_id . "', query = '" . $this->db->escape($data['query']) . "'");
            }
        }

        $this->trigger->fire('post.admin.alias.edit');
    }

    public function deleteAlias($url_alias_id)
    {
        $this->trigger->fire('pre.admin.alias.delete', array(&$url_alias_id));

        $tmp = $this->getAliasById($url_alias_id);

        $aliases = $this->getAliasesByQuery($tmp['query']);

        foreach ($aliases as $alias) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE url_alias_id = '" . (int) $alias['url_alias_id'] . "'");
        }

        $this->trigger->fire('post.admin.alias.delete');
    }

    public function updateAlias($url_alias_id, $key, $value)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "url_alias SET " . $key . " = '" . $this->db->escape($value) . "' WHERE url_alias_id = '" . (int)$url_alias_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
    }

    public function getAliasById($url_alias_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE url_alias_id = '" . (int) $url_alias_id . "'");

        return $query->row;
    }

    public function getAliasesByQuery($query)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($query) . "'");

        return $query->rows;
    }

    public function getAliases($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "url_alias";

            $implode = array();

            if (!empty($data['filter_seo_url'])) {
                $implode[] = "keyword LIKE '%" . $this->db->escape($data['filter_seo_url']) . "%'";
            }

            if (!empty($data['filter_query'])) {
                $implode[] = "query LIKE '%" . $this->db->escape($data['filter_query']) . "%'";
            }

            if (!empty($data['filter_type'])) {
                $type = $this->db->escape($data['filter_type']);

                if ($type == 'other') {
                    $implode[] = "SUBSTRING_INDEX(`query`, '=', 1) NOT IN ('product_id', 'category_id', 'information_id', 'manufacturer_id')";
                } else {
                    $implode[] = "SUBSTRING_INDEX(`query`, '=', 1) = '" . $type . '_id' . "'";
                }
            }

            if (!empty($data['filter_language'])) {
                $implode[] = "language_id = '" . $this->db->escape($data['filter_language']) . "'";
            }

            if ($implode) {
                $sql .= " WHERE " . implode(" AND ", $implode);
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            }

            $alias_data = $this->db->query($sql)->rows;

            return $alias_data;
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "url_alias ORDER BY keyword";

            $alias_data = $this->db->query($sql)->rows;

            return $alias_data;
        }
    }

    public function getTotalAliases($data = array())
    {
        if ($data) {
            $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "url_alias";

            $implode = array();

            if (!empty($data['filter_seo_url'])) {
                $implode[] = "keyword LIKE '%" . $this->db->escape($data['filter_seo_url']) . "%'";
            }

            if (!empty($data['filter_query'])) {
                $implode[] = "query LIKE '%" . $this->db->escape($data['filter_query']) . "%'";
            }

            if (!empty($data['filter_type'])) {
                $implode[] = "query = '" . $this->db->escape($data['filter_type']) . "'";
            }

            if (!empty($data['filter_language'])) {
                $implode[] = "language_id = '" . $this->db->escape($data['filter_language']) . "'";
            }

            if ($implode) {
                $sql .= " WHERE " . implode(" AND ", $implode);
            }

            $query = $this->db->query($sql);

            return $query->row['total'];
        } else {
            $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "url_alias");

            return $query->row['total'];
        }
    }
}
