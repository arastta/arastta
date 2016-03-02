<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Seo extends Object
{

    protected $db;
    protected $config;
    protected $request;
    protected $session;
    protected $language;

    public function __construct($registry = '')
    {
        if (empty($registry)) {
            return;
        }

        $this->db = $registry->get('db');
        $this->config = $registry->get('config');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');
        $this->language = $registry->get('language');
    }

    public function getAlias($id, $type = 'product')
    {
        $id = intval($id);

        if (empty($id)) {
            return '';
        }

        static $rows = array('product' => array(), 'category' => array(), 'manufacturer' => array(), 'information' => array());

        if (!isset($rows[$type][$id])) {
            $query = $type.'_id='.$id;
            $results = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($query) . "'");

            if ($results->num_rows) {
                $tmp = '';
                $found = '';

                // Get the language id
                $lang_id = $this->getLanguageId();

                foreach ($results->rows as $result) {
                    if (!$result['keyword']) {
                        continue;
                    }

                    $tmp = $result['keyword'];

                    if ($result['language_id'] != $lang_id) {
                        continue;
                    }

                    $found = $result['keyword'];

                    break;
                }

                $alias = !empty($found) ? $found : $tmp;
            } else {
                $alias = $type.'-'.$id;
            }

            $rows[$type][$id] = $alias;
        }

        return $rows[$type][$id];
    }

    public function getAliasQuery($keyword)
    {
        $query = false;

        $results = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "'");

        if ($results->num_rows) {
            $tmp = '';
            $found = '';

            // Get the language id
            $lang_id = $this->getLanguageId();

            foreach ($results->rows as $result) {
                if (!$result['query']) {
                    continue;
                }

                $tmp = $result['query'];

                if ($result['language_id'] != $lang_id) {
                    continue;
                }

                $found = $result['query'];

                break;
            }

            $query = !empty($found) ? $found : $tmp;
        } elseif (strpos($keyword, '-')) {
            $tmp = explode('-', $keyword);
            $routes = array('product', 'category', 'manufacturer', 'information');

            if (!empty($tmp[0]) && in_array($tmp[0], $routes) && !empty($tmp[1]) && is_numeric($tmp[1])) {
                $query = $tmp[0].'_id='.$tmp[1];
            }
        }

        return $query;
    }

    public function getLanguageId()
    {
        static $data = array();

        if ($this->config->get('config_seo_translate')) {
            $code = $this->session->data['language'];
        } else {
            $code = $this->config->get('config_language');
        }

        if (!isset($data[$code])) {
            $sql = $this->db->query("SELECT language_id FROM `" . DB_PREFIX . "language` WHERE status = '1' AND code = '" . $this->db->escape($code) . "' LIMIT 1");

            if ($sql->num_rows) {
                $data[$code] = $sql->row['language_id'];
            } else {
                $data[$code] = 1;
            }
        }

        return $data[$code];
    }

    public function generateAlias($title, $id = null, $language_id = null)
    {
        $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');

        $alias = $this->safeAlias($title);

        if ($id && $language_id) {
            $count = 0;
            $baseAlias = $alias;

            while ($this->db->query("SELECT COUNT(*) AS count FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($alias) . "' AND language_id = '" . (int)$language_id . "'")->row['count'] > 0) {
                if ($count == 0) {
                    $baseAlias = ($alias = $alias . '-' . $id) . '-';
                } else {
                    $alias = $baseAlias . $count;
                }

                $count++;
            }
        }

        return $alias;
    }

    // From Joomla.Platform
    public function safeAlias($string)
    {
        // Replace double byte whitespaces by single byte (East Asian languages)
        $str = preg_replace('/\xE3\x80\x80/', ' ', $string);

        // Remove any '-' from the string as they will be used as concatenator.
        // Would be great to let the spaces in but only Firefox is friendly with this

        $str = str_replace('-', ' ', $str);

        // Replace forbidden characters by whitespaces
        $str = preg_replace('#[:\#\*"@+=;!><&\.%()\]\/\'\\\\|\[]#', "\x20", $str);

        // Delete all '?'
        $str = str_replace('?', '', $str);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(utf8_strtolower($str));

        // Remove any duplicate whitespace and replace whitespaces by hyphens
        $str = preg_replace('#\x20+#', '-', $str);

        return $str;
    }

    public function getCategoryIdBySortOrder($product_id)
    {
        static $data = array();

        if (!isset($data[$product_id])) {
            $data[$product_id] = null;

            $sql = "SELECT pc.category_id FROM " . DB_PREFIX . "product_to_category AS pc";
            $sql .= " LEFT JOIN " . DB_PREFIX . "category AS c ON (pc.category_id = c.category_id)";
            $sql .= " LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";
            $sql .= " WHERE pc.product_id = '" . (int)$product_id . "'";
            $sql .= " AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
            $sql .= " AND c.status = '1'";
            $sql .= " ORDER BY c.sort_order";
            $sql .= " LIMIT 1";

            $query = $this->db->query($sql);

            if (!$query->num_rows) {
                return $data[$product_id];
            }

            $data[$product_id] = $query->row['category_id'];
        }

        return $data[$product_id];
    }

    public function getParentCategoriesIds($category_id = 0)
    {
        static $data = array();

        if (!isset($data[$category_id])) {
            $data[$category_id] = array();

            $sql = "SELECT c.parent_id FROM " . DB_PREFIX . "category c";
            $sql .= " LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";
            $sql .= " WHERE c.category_id = '" . (int)$category_id . "'";
            $sql .= " AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
            $sql .= " ORDER BY c.sort_order";
            $sql .= " LIMIT 1";

            $query = $this->db->query($sql);

            if (empty($query->row) || !isset($query->row['parent_id'])) {
                return $data[$category_id];
            }

            $data[$category_id][] = $query->row['parent_id'];

            $data[$category_id] = array_merge($this->getParentCategoriesIds($query->row['parent_id']), $data[$category_id]);
        }

        return $data[$category_id];
    }
}
