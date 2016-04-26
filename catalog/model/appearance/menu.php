<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelAppearanceMenu extends Model
{

    public function getMenus()
    {
        $data = array();

        $store_id = $this->config->get('config_store_id', 0);

        $sql = "SELECT * FROM `" . DB_PREFIX . "menu` m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) LEFT JOIN " . DB_PREFIX . "menu_to_store ms ON (m.menu_id = ms.menu_id) WHERE ms.store_id = '" . $store_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m.status = 1 ORDER BY m.sort_order";

        $query = $this->db->query($sql);

        if ($query->rows) {
            foreach ($query->rows as $menu) {
                $data[$menu['menu_id']] = $menu;
            }
        }

        return $data;
    }

    public function getChildMenus()
    {
        $data = array();

        $store_id = $this->config->get('config_store_id', 0);

        $sql = "SELECT * FROM `" . DB_PREFIX . "menu_child` mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) LEFT JOIN " . DB_PREFIX . "menu_child_to_store mcs ON (mc.menu_child_id = mcs.menu_child_id) WHERE mcs.store_id = '" . $store_id . "'AND mcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND mc.status = 1 ORDER BY mc.sort_order";

        $query = $this->db->query($sql);

        if ($query->rows) {
            foreach ($query->rows as $menu_child) {
                $data[$menu_child['menu_child_id']] = $menu_child;
            }
        }

        return $data;
    }
}
