<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelCommonEdit extends Model
{
    public function changeStatus($type, $ids, $status, $extension = false)
    {
        $data = array(
            'selected'  => $ids,
            'status'    => $status,
            'extension' => $extension
        );

        $folder = explode('/', $this->request->post['route']);

        if (is_array($folder)) {
            $this->trigger->addFolder($folder[0]);
        }

        $this->changeMenuStatus($type, $data);

        $this->trigger->fire('pre.admin.' . $type . '.status.edit', array($data));

        if ($extension) {
            foreach ($ids as $id) {
                $extension_name = basename($id);

                $current = $this->config->get($extension_name . '_status');

                if (is_null($current)) {
                    $store_id = $this->config->get('config_store_id');

                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `store_id` = " . (int) $store_id . " `code` = '" . $this->db->escape($extension_name) . "', `key` = '" . $this->db->escape($extension_name) . "_status', `value` = " . (int) $status . ", `serialized` = '0'");
                } else {
                    $this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = " . (int) $status . " WHERE `code` = '" . $this->db->escape($extension_name) . "' AND `key` = '" . $this->db->escape($extension_name) . "_status'");
                }
            }
        } else {
            foreach ($ids as $id) {
                $this->db->query("UPDATE `" . DB_PREFIX . $type . "` SET `status` = " . (int) $status . " WHERE `" . $type . "_id` = " . $this->db->escape($id));
            }
        }

        $this->trigger->fire('post.admin.' . $type . '.status.edit', array($data));
    }

    public function changeMenuStatus($type, $data)
    {
        if ($type != 'category' && $type != 'product' && $type != 'manufacturer' && $type != 'information') {
            return;
        }

        foreach ($data['selected'] as $menu_id) {
            // Main menu changed status
            $menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_type = '" . $type . "' AND md.link = " . (int) $menu_id);

            if ($menus->num_rows) {
                foreach ($menus->rows as $menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu SET status = '" . (int) $data['status'] . "' WHERE menu_id = '" . (int) $menu['menu_id'] . "'");
                }
            }

            // Child menu changed status
            $child_menus = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) WHERE mc.menu_type = '" . $type . "' AND mcd.link = " . (int) $menu_id);

            if ($child_menus->num_rows) {
                foreach ($child_menus->rows as $child_menu) {
                    $this->db->query("UPDATE " . DB_PREFIX . "menu_child SET status = '" . (int) $data['status'] . "' WHERE menu_child_id = '" . (int) $child_menu['menu_child_id'] . "'");
                }
            }
        }
    }

    public function changeSortOrder($type, $data, $extension = false)
    {
        $route = $data['route'];
        $sort  = !empty($data['sort']) ? $data['sort'] : '';
        $order = !empty($data['order']) ? $data['order'] : '';
        $page  = !empty($data['page']) ? $data['page'] : '1';

        $items = $data['items']['sort_order'];

        $folder = explode('/', $route);

        if (is_array($folder)) {
            $this->trigger->addFolder($folder[0]);
        }

        $this->trigger->fire('pre.admin.' . $type . '.sort.edit', array($data));

        if ($extension) {
            $sort_order = (($page - 1) * $this->config->get('config_limit_admin')) + 1;

            $codes = $data['items']['code'];

            foreach ($items as $key => $item) {
                $extension_name = $codes[$key];

                $status = $this->config->get($extension_name . '_status');

                if (empty($status)) {
                    continue;
                }

                $current = $this->config->get($extension_name . '_sort_order');

                if (is_null($current)) {
                    $store_id = $this->config->get('config_store_id');

                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `store_id` = " . (int)$store_id . " `code` = '" . $this->db->escape($extension_name) . "', `key` = '" . $this->db->escape($extension_name . "_sort_order") . "' , `value` = '" . (int)$sort_order . "', `serialized` = '0'");
                } else {
                    $this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . (int)$sort_order . "' WHERE `code` = '" . $this->db->escape($extension_name) . "' AND `key` = '" . $this->db->escape($extension_name . "_sort_order") . "'");
                }

                $sort_order++;
            }
        } else {
            $sort_order = (($page - 1) * $this->config->get('config_limit_admin')) + 1;

            foreach ($items as $item) {
                $this->db->query("UPDATE `" . DB_PREFIX . $type . "` SET `sort_order` = " . (int)$sort_order . " WHERE `" . $type . "_id` = " . (int)$item);

                $sort_order++;
            }

            $post_keys = array_keys($data);

            $is_filter = preg_grep("/filter_+/", $post_keys);

            if (!$is_filter) {
                // Get route all items
                $this->load->model($route);

                $filter_data = array(
                    'sort'  => $sort,
                    'order' => $order
                );

                $model = 'model_' . str_replace('/', '_', $route);

                $function = 'get' . str_replace('ry', 'rie', $type) . 's';

                $results = $this->{$model}->{$function}($filter_data);

                if (empty($results)) {
                    return false;
                }

                //
                foreach ($results as $key => $result) {
                    if (in_array($result[$type . '_id'], $items)) {
                        unset($results[$key]);
                    }
                }

                unset($key, $result);

                foreach ($results as $result) {
                    if (!empty($result['sort_order']) && $result['sort_order'] < $sort_order) {
                        continue;
                    }

                    if ($type == 'category' && $result['parent_id']) {
                        continue;
                    }

                    $this->db->query("UPDATE `" . DB_PREFIX . $type . "` SET `sort_order` = " . (int)$sort_order . " WHERE `" . $type . "_id` = " . (int)$result[$type . '_id']);

                    $sort_order++;
                }
            }
        }

        $this->trigger->fire('post.admin.' . $type . '.sort.edit', array($data));
    }
}
