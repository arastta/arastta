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
}
