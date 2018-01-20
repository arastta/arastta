<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelBlogCategory extends Model
{
    public function getCategory($category_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'");

        return $query->row;
    }

    public function getCategories($parent_id = 0)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

        return $query->rows;
    }

    public function getCategoryLayoutId($category_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . (int) $category_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getTotalCategoriesByCategoryId($parent_id = 0)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'");

        return $query->row['total'];
    }

    public function getCategoriesByParentId($category_id)
    {
        $blog_category_data = array();

        $blog_category_data[] = $category_id;

        $blog_category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "blog_category WHERE parent_id = '" . (int) $category_id . "'");

        foreach ($blog_category_query->rows as $blog_category) {
            $children = $this->getBlogCategoriesByParentId($blog_category['category_id']);

            if ($children) {
                $blog_category_data = array_merge($children, $blog_category_data);
            }
        }

        return $blog_category_data;
    }
}
