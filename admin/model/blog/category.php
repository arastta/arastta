<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelBlogCategory extends Model
{

    public function addCategory($data)
    {
        $this->trigger->fire('pre.admin.blog.category.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category SET parent_id = '" . (int) $data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', date_added = NOW()");

        $category_id = $this->db->getLastId();

        if (!empty($data['top'])) {
            $this->addMenu($category_id, $data);
        }

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
        }

        foreach ($data['category_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_description SET category_id = '" . (int) $category_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        // MySQL Hierarchical Data Closure Table Pattern
        $level = 0;

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

        if ($query->num_rows) {
            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

                $level++;
            }
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

        if (isset($data['category_store'])) {
            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        if (isset($data['category_layout'])) {
            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_to_layout SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        if (isset($data['seo_url'])) {
            $this->load->model('catalog/url_alias');

            foreach ($data['seo_url'] as $language_id => $value) {
                $alias = empty($value) ? $data['category_description'][$language_id]['name'] : $value;

                $this->model_catalog_url_alias->addAlias('blog_category', $category_id, $alias, $language_id);
            }
        }

        $this->cache->delete('blog.category');

        $this->trigger->fire('post.admin.blog.category.add', array(&$category_id));

        return $category_id;
    }

    public function editCategory($category_id, $data)
    {
        $this->trigger->fire('pre.admin.blog.category.edit', array(&$data));

        $isTop = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category` WHERE category_id = '" . (int)$category_id . "'");

        $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET parent_id = '" . (int) $data['parent_id'] . "', sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "' WHERE category_id = '" . (int) $category_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int) $category_id . "'");
        }

        $this->editMenu($category_id, $isTop, $data);

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . (int) $category_id . "'");

        foreach ($data['category_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_description SET category_id = '" . (int) $category_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        // MySQL Hierarchical Data Closure Table Pattern
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

        if ($query->rows) {
            foreach ($query->rows as $category_path) {
                // Delete the path below the current one
                $this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

                $path = array();

                // Get the nodes new parents
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Get whats left of the nodes current path
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Combine the paths with a new level
                $level = 0;

                foreach ($path as $path_id) {
                    $this->db->query("REPLACE INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

                    $level++;
                }
            }
        } else {
            // Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$category_id . "'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_to_store WHERE category_id = '" . (int) $category_id . "'");

        if (isset($data['category_store'])) {
            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . (int) $category_id . "'");

        if (isset($data['category_layout'])) {
            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_to_layout SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        $this->load->model('catalog/url_alias');

        $this->model_catalog_url_alias->clearAliases('category', $category_id);

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['category_description'][$language_id]['name'] : $value;

            $this->model_catalog_url_alias->addAlias('blog_category', $category_id, $alias, $language_id);
        }

        $this->cache->delete('blog.category');

        $this->trigger->fire('post.admin.blog.category.edit', array(&$category_id));
    }

    public function deleteCategory($category_id)
    {
        $this->trigger->fire('pre.admin.blog.category.delete', array(&$category_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_path WHERE category_id = '" . (int)$category_id . "'");

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_path WHERE path_id = '" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $this->deleteCategory($result['category_id']);
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category WHERE category_id = '" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_to_store WHERE category_id = '" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int) $category_id . "'");

        $this->load->model('catalog/url_alias');

        $this->model_catalog_url_alias->clearAliases('blog_category', $category_id);

        $this->deleteMenu($category_id);

        $this->cache->delete('blog.category');

        $this->trigger->fire('post.admin.blog.category.delete', array(&$category_id));
    }

    public function repairCategories($parent_id = 0)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category WHERE parent_id = '" . (int)$parent_id . "'");

        foreach ($query->rows as $category) {
            // Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

            $this->repairCategories($category['category_id']);
        }
    }

    public function updateCategory($category_id, $key, $value)
    {
        $this->trigger->fire('pre.admin.blog.category.update', array(&$category_id, $key, $value));

        $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

        if ($key == 'name') {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_category_description SET " . $key . " = '" . $this->db->escape($value) . "' WHERE category_id = '" . (int) $category_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");
        } elseif ($key == 'status') {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET " . $key . " = '" . $this->db->escape($value) . "' WHERE category_id = '" . (int) $category_id . "'");

            // Main menu changed category status
            $category_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_type = 'blog_category' AND md.link = " . (int)$category_id);

            if ($category_menus->num_rows) {
                foreach ($category_menus->rows as $category_menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu SET status = '" . (int)$value . "' WHERE menu_id = '" . (int)$category_menu['menu_id'] . "'");
                }
            }

            // Child menu changed category status
            $category_child_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) WHERE mc.menu_type = 'blog_category' AND mcd.link = " . (int)$category_id);

            if ($category_child_menus->num_rows) {
                foreach ($category_child_menus->rows as $category_child_menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu_child SET status = '" . (int)$value . "' WHERE menu_child_id = '" . (int)$category_child_menu['menu_child_id'] . "'");
                }
            }
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET " . $key . " = '" . $this->db->escape($value) . "' WHERE category_id = '" . (int) $category_id . "'");
        }

        $this->trigger->fire('post.admin.blog.category.update', array(&$category_id, $key, $value));
    }

    public function getCategory($category_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $category = $query->row;

        if ($category) {
            $category['seo_url'] = array();

            $this->load->model('catalog/url_alias');

            $aliases = $this->model_catalog_url_alias->getAliases('blog_category', $category_id);

            if ($aliases) {
                foreach ($aliases as $row) {
                    $category['seo_url'][$row['language_id']] = $row['keyword'];
                }
            }
        }

        return $category;
    }

    public function getCategories($data = array(), $parent_id = 0)
    {
        if ($data) {
            $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.status, c1.sort_order FROM " . DB_PREFIX . "blog_category_path cp LEFT JOIN " . DB_PREFIX . "blog_category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "blog_category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "blog_category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            if (!empty($data['filter_name'])) {
                $sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
            }

            if (isset($data['filter_status']) and !is_null($data['filter_status'])) {
                $sql .= " AND c1.status = '".$data['filter_status']."'";
            }

            $sql .= " GROUP BY cp.category_id";

            $sort_data = array(
                'name',
                'status',
                'sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY sort_order";
            }

            if (isset($data['sort']) && $data['sort'] == 'sort_order') {
                $sql .= ", name";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $category_data = $this->cache->get('blog.category.' . $this->config->get('config_language_id'));

            if (!$category_data) {
                $category_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

                foreach ($query->rows as $result) {
                    $category_data[] = array(
                        'category_id' => $result['category_id'],
                        'name'             => $this->getPath($result['category_id']),
                        'status'           => $result['status'],
                        'sort_order'       => $result['sort_order']
                    );

                    $category_data = array_merge($category_data, $this->getCategories(null, $result['category_id']));
                }

                $this->cache->set('blog.category.' . $this->config->get('config_language_id'), $category_data);
            }

            return $category_data;
        }
    }

    public function getCategoryDescriptions($category_id)
    {
        $category_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $category_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'description'      => $result['description']
            );
        }

        return $category_description_data;
    }

    public function getCategoryStores($category_id)
    {
        $category_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_store WHERE category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $category_store_data[] = $result['store_id'];
        }

        return $category_store_data;
    }

    public function getCategoryLayouts($category_id)
    {
        $category_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $category_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $category_layout_data;
    }

    public function getTotalCategories($data = array())
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id)";

        $sql .= " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND cd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) and !is_null($data['filter_status'])) {
            $sql .= " AND c.status = '".$data['filter_status']."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCategoriesByLayoutId($layout_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_category_to_layout WHERE layout_id = '" . (int) $layout_id . "'");

        return $query->row['total'];
    }

    public function getPath($category_id)
    {
        $query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

        $category_info = $query->row;

        if ($category_info['parent_id']) {
            return $this->getPath($category_info['parent_id']) . " > " . $category_info['name'];
        } else {
            return $category_info['name'];
        }
    }

    protected function addMenu($category_id, $data)
    {
        if (empty($data['parent_id'])) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int)$data['sort_order'] . "', menu_type = 'blog_category', status = '" . $data['status'] . "'");

            $menu_id = $this->db->getLastId();

            foreach ($data['category_description'] as $language_id => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', link = '" . $category_id . "'");
            }

            if (isset($data['category_store'])) {
                foreach ($data['category_store'] as $store_id) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$store_id . "'");
                }
            }
        } else {
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int)$data['parent_id'] . "'");

            if (empty($query->num_rows)) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int)$data['sort_order'] . "', menu_type = 'blog_category', status = '" . $data['status'] . "'");

                $menu_id = $this->db->getLastId();

                foreach ($data['category_description'] as $language_id => $value) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', link = '" . $category_id . "'");
                }

                if (isset($data['category_store'])) {
                    foreach ($data['category_store'] as $store_id) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$store_id . "'");
                    }
                }
            } else {
                $menu_id = $query->row['menu_id'];

                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child SET menu_id = '" . (int)$menu_id . "', sort_order = '" . (int)$data['sort_order'] . "', menu_type = 'blog_category', status = '" . $data['status'] . "'");

                $menu_child_id = $this->db->getLastId();

                foreach ($data['category_description'] as $language_id => $value) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_description SET menu_child_id = '" . (int)$menu_child_id . "', language_id = '" . (int)$language_id  . "', menu_id = '" . (int)$menu_id . "', name = '" . $this->db->escape($value['name']) . "', link = '" . $category_id . "'");
                }

                if (isset($data['category_store'])) {
                    foreach ($data['category_store'] as $store_id) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_to_store SET menu_child_id = '" . (int)$menu_child_id . "', store_id = '" . (int)$store_id . "'");
                    }
                }
            }
        }
    }

    protected function editMenu($category_id, $isTop, $data)
    {
        if (!empty($data['top'])) {
            if (empty($data['parent_id'])) {
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                if (empty($query->num_rows)) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int) $data['sort_order'] . "', menu_type = 'blog_category', status = '" . $data['status'] . "'");

                    $menu_id = $this->db->getLastId();

                    foreach ($data['category_description'] as $language_id => $value) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int) $menu_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', link = '" . $category_id . "'");
                    }

                    if (isset($data['category_store'])) {
                        foreach ($data['category_store'] as $store_id) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int) $menu_id . "', store_id = '" . (int) $store_id . "'");
                        }
                    }
                } else {
                    if (!empty($data['update_menu_name'])) {
                        foreach ($data['category_description'] as $language_id => $value) {
                            $this->db->query("UPDATE " . DB_PREFIX . "menu_description AS md LEFT JOIN " . DB_PREFIX . "menu AS m ON md.menu_id = m.menu_id SET md.name = '" . $this->db->escape($value['name']) . "' WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "' AND md.language_id = '" . (int) $language_id . "'");
                        }

                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                        if (!empty($query->row['menu_id'])) {
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int) $query->row['menu_id'] . "'");

                            if (isset($data['category_store'])) {
                                foreach ($data['category_store'] as $store_id) {
                                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int) $query->row['menu_id'] . "', store_id = '" . (int) $store_id . "'");
                                }
                            }
                        }
                    }

                    // Main menu changed category status
                    $category_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_type = 'blog_category' AND md.link = " . (int)$category_id);

                    if ($category_menus->num_rows) {
                        foreach ($category_menus->rows as $category_menu) {
                            $this->db->query("UPDATE " . DB_PREFIX . "menu SET status = '" . (int)$data['status'] . "' WHERE menu_id = '" . (int)$category_menu['menu_id'] . "'");
                        }
                    }
                }
            } else {
                $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $data['parent_id'] . "'");

                if (empty($query->num_rows)) {
                    if (empty($query->row['menu_id'])) {
                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                        if (empty($query->num_rows)) {
                            $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int) $data['sort_order'] . "', menu_type = 'blog_category', status = '" . $data['status'] . "'");

                            $menu_id = $this->db->getLastId();

                            foreach ($data['category_description'] as $language_id => $value) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int) $menu_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', link = '" . $category_id . "'");
                            }

                            if (isset($data['category_store'])) {
                                foreach ($data['category_store'] as $store_id) {
                                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int) $menu_id . "', store_id = '" . (int) $store_id . "'");
                                }
                            }
                        } else {
                            if (!empty($data['update_menu_name'])) {
                                foreach ($data['category_description'] as $language_id => $value) {
                                    $this->db->query("UPDATE " . DB_PREFIX . "menu_description AS md LEFT JOIN " . DB_PREFIX . "menu AS m ON md.menu_id = m.menu_id SET md.name = '" . $this->db->escape($value['name']) . "' WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "' AND md.language_id = '" . (int) $language_id . "'");

                                    $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                                    if (!empty($query->row['menu_id'])) {
                                        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int) $query->row['menu_id'] . "'");

                                        if (isset($data['category_store'])) {
                                            foreach ($data['category_store'] as $store_id) {
                                                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int) $query->row['menu_id'] . "', store_id = '" . (int) $store_id . "'");
                                            }
                                        }
                                    }
                                }
                            }

                            // Child menu changed category status
                            $category_child_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) WHERE mc.menu_type = 'blog_category' AND mcd.link = " . (int)$category_id);

                            if ($category_child_menus->num_rows) {
                                foreach ($category_child_menus->rows as $category_child_menu) {
                                    $this->db->query("UPDATE " . DB_PREFIX . "menu_child SET status = '" . (int)$data['status'] . "' WHERE menu_child_id = '" . (int)$category_child_menu['menu_child_id'] . "'");
                                }
                            }
                        }
                    } else {
                        $menu_id = $query->row['menu_id'];

                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_child_description` AS md LEFT JOIN `" . DB_PREFIX . "menu_child` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                        if (empty($query->num_rows)) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child SET menu_id = '" . (int) $menu_id . "', sort_order = '" . (int) $data['sort_order'] . "', menu_type = 'blog_category', status = '" . $data['status'] . "'");

                            $menu_child_id = $this->db->getLastId();

                            foreach ($data['category_description'] as $language_id => $value) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_description SET menu_child_id = '" . (int) $menu_child_id . "', language_id = '" . (int) $language_id . "', menu_id = '" . (int) $menu_id . "', name = '" . $this->db->escape($value['name']) . "', link = '" . $category_id . "'");
                            }

                            if (isset($data['category_store'])) {
                                foreach ($data['category_store'] as $store_id) {
                                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_to_store SET menu_child_id = '" . (int) $menu_child_id . "', store_id = '" . (int) $store_id . "'");
                                }
                            }
                        } elseif (!empty($data['update_menu_name'])) {
                            foreach ($data['category_description'] as $language_id => $value) {
                                $this->db->query("UPDATE " . DB_PREFIX . "menu_child_description AS md LEFT JOIN " . DB_PREFIX . "menu_child AS m ON md.menu_id = m.menu_id SET md.name = '" . $this->db->escape($value['name']) . "' WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "' AND md.language_id = '" . (int) $language_id . "'");

                                $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_child_description` AS md LEFT JOIN `" . DB_PREFIX . "menu_child` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                                if (!empty($query->row['menu_child_id'])) {
                                    $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . (int) $query->row['menu_child_id'] . "'");

                                    if (isset($data['category_store'])) {
                                        foreach ($data['category_store'] as $store_id) {
                                            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_to_store SET menu_id = '" . (int) $query->row['menu_child_id'] . "', store_id = '" . (int) $store_id . "'");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if ($isTop->row['top']) {
                if (empty($data['parent_id'])) {
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                    if (!empty($query->row['menu_id'])) {
                        $menu_id = $query->row['menu_id'];

                        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . (int) $menu_id . "'");
                        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . (int) $menu_id . "'");
                        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int) $menu_id . "'");
                    }
                } else {
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $data['parent_id'] . "'");

                    if (empty($query->row['menu_id'])) {
                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int) $category_id . "'");

                        if (!empty($query->row['menu_id'])) {
                            $menu_id = $query->row['menu_id'];

                            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_` WHERE menu_id = '" . (int) $menu_id . "'");
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . (int) $menu_id . "'");
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int) $menu_id . "'");
                        }
                    } else {
                        if (!empty($query->row['menu_id'])) {
                            $menu_id = $query->row['menu_id'];

                            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child` WHERE menu_id = '" . (int) $menu_id . "'");
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_id = '" . (int) $menu_id . "'");
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_id = '" . (int) $menu_id . "'");
                        }
                    }
                }
            }
        }
    }

    protected function deleteMenu($category_id)
    {
        // Main Menu Item
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'blog_category' AND md.link = '" . (int)$category_id . "'");

        if (!empty($query->row['menu_id'])) {
            $menu_id = $query->row['menu_id'];

            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . (int)$menu_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . (int)$menu_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int)$menu_id . "'");
        }

        // Child Menu Item
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_child_description` AS mcd LEFT JOIN `" . DB_PREFIX . "menu_child` AS mc ON mc.menu_child_id = mcd.menu_child_id WHERE mc.menu_type = 'blog_category' AND mcd.link = '" . (int)$category_id . "'");

        if (!empty($query->row['menu_child_id'])) {
            $menu_child_id = $query->row['menu_child_id'];

            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
        }
    }
}
