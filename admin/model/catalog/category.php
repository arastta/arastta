<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelCatalogCategory extends Model {
    public function addCategory($data) {
        $this->trigger->fire('pre.admin.category.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

        $category_id = $this->db->getLastId();

        if (!empty($data['top'])) {
            if (empty($data['parent_id'])) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int)$data['sort_order'] . "', columns = '" . (int)$data['column'] . "', menu_type = 'category', status = '" . $data['status'] . "'");

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
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int)$data['parent_id'] . "'");

                if (empty($query->num_rows)) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int)$data['sort_order'] . "', columns = '" . (int)$data['column'] . "', menu_type = 'category', status = '" . $data['status'] . "'");

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

                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child SET menu_id = '" . (int)$menu_id . "', sort_order = '" . (int)$data['sort_order'] . "', menu_type = 'category', status = '" . $data['status'] . "'");

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

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
        }

        foreach ($data['category_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];
         
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        // MySQL Hierarchical Data Closure Table Pattern
        $level = 0;

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

        foreach ($query->rows as $result) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

            $level++;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

        if (isset($data['category_filter'])) {
            foreach ($data['category_filter'] as $filter_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
            }
        }

        if (isset($data['category_store'])) {
            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        // Set which layout to use with this category
        if (isset($data['category_layout'])) {
            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['category_description'][$language_id]['name'] : $value;
            $this->model_catalog_url_alias->addAlias('category', $category_id, $alias, $language_id);
        }

        $this->cache->delete('category');

        $this->trigger->fire('post.admin.category.add', array(&$category_id));

        return $category_id;
    }

    public function editCategory($category_id, $data) {
        $this->trigger->fire('pre.admin.category.edit', array(&$data));

        $isTop = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE category_id = '" . (int)$category_id . "'");

        $this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
        }

        if (!empty($data['top'])) {
            if (empty($data['parent_id'])) {
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

                if (empty($query->num_rows)) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int) $data['sort_order'] . "', columns = '" . (int) $data['column'] . "', menu_type = 'category', status = '" . $data['status'] . "'");

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
                            $this->db->query("UPDATE " . DB_PREFIX . "menu_description AS md LEFT JOIN " . DB_PREFIX . "menu AS m ON md.menu_id = m.menu_id SET md.name = '" . $this->db->escape($value['name']) . "' WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "' AND md.language_id = '" . (int) $language_id . "'");
                        }

                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

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
                    $category_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_type = 'category' AND md.link = " . (int)$category_id);

                    if ($category_menus->num_rows) {
                        foreach ($category_menus->rows as $category_menu) {
                            $this->db->query("UPDATE " . DB_PREFIX . "menu SET status = '" . (int)$data['status'] . "' WHERE menu_id = '" . (int)$category_menu['menu_id'] . "'");
                        }
                    }
                }
            } else {
                $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $data['parent_id'] . "'");

                if (empty($query->num_rows)) {
                    if (empty($query->row['menu_id'])) {
                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

                        if (empty($query->num_rows)) {
                            $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int) $data['sort_order'] . "', columns = '" . (int) $data['column'] . "', menu_type = 'category', status = '" . $data['status'] . "'");

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
                                    $this->db->query("UPDATE " . DB_PREFIX . "menu_description AS md LEFT JOIN " . DB_PREFIX . "menu AS m ON md.menu_id = m.menu_id SET md.name = '" . $this->db->escape($value['name']) . "' WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "' AND md.language_id = '" . (int) $language_id . "'");

                                    $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

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
                            $category_child_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) WHERE mc.menu_type = 'category' AND mcd.link = " . (int)$category_id);

                            if ($category_child_menus->num_rows) {
                                foreach ($category_child_menus->rows as $category_child_menu) {
                                    $this->db->query("UPDATE " . DB_PREFIX . "menu_child SET status = '" . (int)$data['status'] . "' WHERE menu_child_id = '" . (int)$category_child_menu['menu_child_id'] . "'");
                                }
                            }
                        }
                    } else {
                        $menu_id = $query->row['menu_id'];

                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_child_description` AS md LEFT JOIN `" . DB_PREFIX . "menu_child` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

                        if (empty($query->num_rows)) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child SET menu_id = '" . (int) $menu_id . "', sort_order = '" . (int) $data['sort_order'] . "', menu_type = 'category', status = '" . $data['status'] . "'");

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
                                $this->db->query("UPDATE " . DB_PREFIX . "menu_child_description AS md LEFT JOIN " . DB_PREFIX . "menu_child AS m ON md.menu_id = m.menu_id SET md.name = '" . $this->db->escape($value['name']) . "' WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "' AND md.language_id = '" . (int) $language_id . "'");

                                $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_child_description` AS md LEFT JOIN `" . DB_PREFIX . "menu_child` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

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
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

                    if (!empty($query->row['menu_id'])) {
                        $menu_id = $query->row['menu_id'];

                        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . (int) $menu_id . "'");
                        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . (int) $menu_id . "'");
                        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int) $menu_id . "'");
                    }
                } else {
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $data['parent_id'] . "'");

                    if (empty($query->row['menu_id'])) {
                        $query = $this->db->query("SELECT m.* FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int) $category_id . "'");

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

        $this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

        foreach ($data['category_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        // MySQL Hierarchical Data Closure Table Pattern
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

        if ($query->rows) {
            foreach ($query->rows as $category_path) {
                // Delete the path below the current one
                $this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

                $path = array();

                // Get the nodes new parents
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Get whats left of the nodes current path
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Combine the paths with a new level
                $level = 0;

                foreach ($path as $path_id) {
                    $this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

                    $level++;
                }
            }
        } else {
            // Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

        if (isset($data['category_filter'])) {
            foreach ($data['category_filter'] as $filter_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

        if (isset($data['category_store'])) {
            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

        if (isset($data['category_layout'])) {
            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $this->model_catalog_url_alias->clearAliases('category', $category_id);

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['category_description'][$language_id]['name'] : $value;
            $this->model_catalog_url_alias->addAlias('category', $category_id, $alias, $language_id);
        }

        $this->cache->delete('category');

        $this->trigger->fire('post.admin.category.edit', array(&$category_id));
    }

    public function deleteCategory($category_id) {
        $this->trigger->fire('pre.admin.category.delete', array(&$category_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $this->deleteCategory($result['category_id']);
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
        
        $this->load->model('catalog/url_alias');
        $this->model_catalog_url_alias->clearAliases('category', $category_id);
        
        // Main Menu Item 
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` AS md LEFT JOIN `" . DB_PREFIX . "menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '" . (int)$category_id . "'");
         
        if(!empty($query->row['menu_id'])){
            $menu_id = $query->row['menu_id'];
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . (int)$menu_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . (int)$menu_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int)$menu_id . "'");
        }
        
        // Child Menu Item
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_child_description` AS mcd LEFT JOIN `" . DB_PREFIX . "menu_child` AS mc ON mc.menu_child_id = mcd.menu_child_id WHERE mc.menu_type = 'category' AND mcd.link = '" . (int)$category_id . "'");
        
        if(!empty($query->row['menu_child_id'])){
            $menu_child_id = $query->row['menu_child_id'];
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
        }

        $this->cache->delete('category');

        $this->trigger->fire('post.admin.category.delete', array(&$category_id));
    }

    public function repairCategories($parent_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

        foreach ($query->rows as $category) {
            // Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

            $this->repairCategories($category['category_id']);
        }
    }

    public function updateCategory($category_id, $key, $value) {
        $this->db->query("UPDATE " . DB_PREFIX . "category SET date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

        if ($key == 'name') {
            $this->db->query("UPDATE " . DB_PREFIX . "category_description SET " . $key . " = '" . $this->db->escape($value) . "' WHERE category_id = '" . (int) $category_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");
        } elseif ($key == 'status') {
            $this->db->query("UPDATE " . DB_PREFIX . "category SET " . $key . " = '" . $this->db->escape($value) . "' WHERE category_id = '" . (int) $category_id . "'");

            // Main menu changed category status
            $category_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_type = 'category' AND md.link = " . (int)$category_id);

            if ($category_menus->num_rows) {
                foreach ($category_menus->rows as $category_menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu SET status = '" . (int)$value . "' WHERE menu_id = '" . (int)$category_menu['menu_id'] . "'");
                }
            }

            // Child menu changed category status
            $category_child_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) WHERE mc.menu_type = 'category' AND mcd.link = " . (int)$category_id);

            if ($category_child_menus->num_rows) {
                foreach ($category_child_menus->rows as $category_child_menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu_child SET status = '" . (int)$value . "' WHERE menu_child_id = '" . (int)$category_child_menu['menu_child_id'] . "'");
                }
            }
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "category SET " . $key . " = '" . $this->db->escape($value) . "' WHERE category_id = '" . (int) $category_id . "'");
        }
    }

    public function getCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $category = $query->row;
        $category['seo_url'] = array();
        
        $this->load->model('catalog/url_alias');
        $aliases = $this->model_catalog_url_alias->getAliases('category', $category_id);

        if ($aliases) {
            foreach ($aliases as $row) {
                $category['seo_url'][$row['language_id']] = $row['keyword'];
            }
        }

        return $category;
    }

    public function getCategories($data = array()) {
        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.status, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
    }

    public function getCategoryDescriptions($category_id) {
        $category_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

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

    public function getCategoryFilters($category_id) {
        $category_filter_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $category_filter_data[] = $result['filter_id'];
        }

        return $category_filter_data;
    }

    public function getCategoryStores($category_id) {
        $category_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $category_store_data[] = $result['store_id'];
        }

        return $category_store_data;
    }

    public function getCategoryLayouts($category_id) {
        $category_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $category_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $category_layout_data;
    }

    public function getTotalCategories() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

        return $query->row['total'];
    }

    public function getTotalCategoriesFilter($data) {
        $sql = ("SELECT COUNT(distinct c.category_id) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id");

        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            $_sql[] = "	cd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
                
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;
            
            $_sql[] = "c.status = '" . $this->db->escape($data['filter_status']) . "'";
        }

        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }

        $query = $this->db->query($sql);
                
        return $query->row['total'];
    }
    
    public function getTotalCategoriesByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row['total'];
    }    
}
