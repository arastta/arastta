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

        $sql = "SELECT * FROM `" . DB_PREFIX . "menu` m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY m.sort_order";

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

        $sql = "SELECT * FROM `" . DB_PREFIX . "menu_child` mc LEFT JOIN " . DB_PREFIX . "menu_child_description mcd ON (mc.menu_child_id = mcd.menu_child_id) WHERE mcd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY mc.sort_order";

        $query = $this->db->query($sql);

        if ($query->rows) {
            foreach ($query->rows as $menu_child) {
                $data[$menu_child['menu_child_id']] = $menu_child;
            }
        }

        return $data;
    }

    public function getMenuStores($menu_id)
    {
        $menu_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_to_store WHERE menu_id = '" . (int)$menu_id . "'");

        foreach ($query->rows as $result) {
            $menu_store_data[] = $result['store_id'];
        }

        return $menu_store_data;

    }

    public function getChildMenuStores($menu_child_id)
    {

        $menu_child_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child_to_store WHERE menu_child_id = '" . (int)$menu_child_id . "'");

        foreach ($query->rows as $result) {
            $menu_child_store_data[] = $result['store_id'];
        }

        return $menu_child_store_data;
    }

    public function add($data, $languages)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "menu SET  sort_order= '1', columns = '1', menu_type = '" . $this->db->escape($data['type']) . "', status = '1'");

        $menu_id = $this->db->getLastId();

        if ($data['type'] == 'custom') {
            $link = $data['link'];

            $query = new stdClass();
            $query->rows = array();

            foreach ($languages as $language) {
                $query->rows[] = array('name' => $data['name'], 'language_id' => $language['language_id']);
            }
        } else {
            $link = (int)$data['id'];

            if ($data['type'] == 'information') {
                $fields = 'title AS name, '.$data['type'].'_id, language_id';
            } else {
                $fields = 'name, '.$data['type'].'_id, language_id';
            }

            $query = $this->db->query("SELECT " . $fields . " FROM " . DB_PREFIX . $data['type'] . "_description WHERE ". $data['type'] ."_id = '" . (int)$data['id'] . "'");
        }

        $data['menu_desc'] = $query->rows;

        foreach ($data['menu_desc'] as $desc) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$desc['language_id'] . "', name = '" . $this->db->escape($desc['name']) . "', link = '" . $link . "'");
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int)$menu_id . "', store_id = '0'");

        $menu = array(
            'name' =>$data['menu_desc'][0]['name'],
            'menu_type' =>$data['type'],
            'menu_id' => $menu_id
        );

        return $menu;
    }

    public function save($data)
    {
        foreach ($data['menu-item-typeMenu'] as $key => $value) {
            $_menu_id = explode('-', $key);
        }
        $menu_id = $_menu_id[1];

        $this->db->query("UPDATE `" . DB_PREFIX . "menu` SET columns = '" . (int)$data['menu_columns'] . "' WHERE menu_id = '" . (int)$menu_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");

        foreach ($data['menu_name'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value) . "', link = '" . $this->db->escape($data['menu_link'][$language_id]) . "'");
        }

        if (!empty($data['menu_store'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_store WHERE menu_id = '" . (int)$menu_id . "'");

            foreach ($data['menu_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
    }

    public function saveChild($data)
    {
        foreach ($data['menu-item-typeMenu'] as $key => $value) {
            $_menu_id = explode('-', $key);
        }
        $menu_child_id = $_menu_id[1];

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child WHERE menu_child_id = '" . (int)$menu_child_id . "'");

        $menu_id = $query->row['menu_id'];

        $this->db->query("DELETE FROM " . DB_PREFIX . "menu_child_description WHERE menu_child_id = '" . (int)$menu_child_id . "'");

        foreach ($data['menu_child_name'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_description SET menu_id = '" . (int)$menu_id . "', menu_child_id = '" . (int)$menu_child_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value) . "', link = '" . $this->db->escape($data['menu_child_link'][$language_id]) . "'");
        }

        if (!empty($data['menu_store'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "menu_child_to_store WHERE menu_child_id = '" . (int)$menu_child_id . "'");

            foreach ($data['menu_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_to_store SET menu_child_id = '" . (int)$menu_child_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
    }

    public function deleteMenu($menu_id)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . (int)$menu_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . (int)$menu_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . (int)$menu_id . "'");
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child WHERE menu_id = '" . (int)$menu_id . "'");
        
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child` WHERE menu_id = '" . (int)$menu_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_id = '" . (int)$menu_id . "'");
        
        if (!empty($query->num_rows)) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . (int)$query->row['menu_child_id'] . "'");
        }
    }

    public function deleteChildMenu($menu_child_id)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . (int)$menu_child_id . "'");
    }

    public function getMenuDesc()
    {
        $data = array();

        $link = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_description AS md LEFT JOIN " . DB_PREFIX . "menu AS m  ON m.menu_id = md.menu_id ");

        foreach ($query->rows as $result) {
            // Quick fix for multilanguage
            // Check if link exists to set for later usage
            if (!empty($result['link'])) {
                $link[$result['menu_id']] = $result['link'];
            }

            // Try to get the link from previous set if not already exists
            if (empty($result['link']) and !empty($link[$result['menu_id']])) {
                $result['link'] = $link[$result['menu_id']];
            }

            $data[$result['menu_id']][$result['language_id']] = $result;
        }

        return $data;
    }

    public function getMenuChildDesc()
    {
        $data = array();

        $link = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child_description AS md LEFT JOIN " . DB_PREFIX . "menu_child AS m  ON m.menu_child_id = md.menu_child_id ");

        foreach ($query->rows as $result) {
            // Quick fix for multilanguage
            // Check if link exists to set for later usage
            if (!empty($result['link'])) {
                $link[$result['menu_child_id']] = $result['link'];
            }

            // Try to get the link from previous set if not already exists
            if (empty($result['link']) and !empty($link[$result['menu_child_id']])) {
                $result['link'] = $link[$result['menu_child_id']];
            }

            $data[$result['menu_child_id']][$result['language_id']] = $result;
        }

        return $data;
    }

    public function enableMenu($menu_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "menu` SET status = '1' WHERE menu_id = '" . (int)$menu_id . "'");
    }

    public function enableChildMenu($menu_child_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "menu_child` SET status = '1' WHERE menu_child_id = '" . (int)$menu_child_id . "'");
    }

    public function disableMenu($menu_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "menu` SET status = '0' WHERE menu_id = '" . (int)$menu_id . "'");
    }

    public function disableChildMenu($menu_child_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "menu_child` SET status = '0' WHERE menu_child_id = '" . (int)$menu_child_id . "'");
    }
    
    public function changeMenuPosition($data)
    {
        $menuOrder = 1;
        $menuSubOrder = 1;

        foreach ($data['menu-item-db-id'] as $key => $value) {
            $menuType = explode('-', $key);
            
            $subMenu = 0;

            if (($menuType[0] == 'ChildMenu') && $data['menu-item-parent-id'][$key] == 0) {
                $insertData = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_child` WHERE menu_child_id = '" . $menuType[1] . "'");
                $insertDataDesc = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_child_id = '" . $menuType[1] . "'");

                $this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET sort_order = '" . (int)$insertData->row['sort_order'] . "', columns = '1', menu_type = '" . $insertData->row['menu_type'] . "', status = '1'");

                $menu_id = $this->db->getLastId();

                foreach ($insertDataDesc->rows as $dataDesc) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$dataDesc['language_id'] . "', name = '" . $this->db->escape($dataDesc['name']) . "', link = '" . $this->db->escape($dataDesc['link']) . "'");
                }

                $childStore = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . $menuType[1] . "'");
                if (!empty($childStore->num_rows)) {
                    foreach ($childStore->rows as $storeData) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int)$menu_id . "', store_id = '" . $storeData['store_id'] . "'");
                    }
                }

                $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child` WHERE menu_child_id = '" . $menuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_description` WHERE menu_child_id = '" . $menuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_child_to_store` WHERE menu_child_id = '" . $menuType[1] . "'");

                $menuType[0] = 'MainMenu';
                $menuType[1] = $menu_id;
            }

            if (($menuType[0] == 'MainMenu') && $data['menu-item-parent-id'][$key] <> 0) {
                $insertData = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . $menuType[1] . "'");
                $insertDataDesc = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . $menuType[1] . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child SET menu_id = '" . $data['menu-item-parent-id'][$key] ."', sort_order = '" . (int)$insertData->row['sort_order'] . "', menu_type = '" . $insertData->row['menu_type'] . "', status = '1'");

                $menu_child_id = $this->db->getLastId();

                foreach ($insertDataDesc->rows as $dataDesc) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_description SET menu_id = '" . $data['menu-item-parent-id'][$key] . "', menu_child_id = '" . (int)$menu_child_id . "', language_id = '" . (int)$dataDesc['language_id'] . "', name = '" . $this->db->escape($dataDesc['name']) . "', link = '" . $this->db->escape($dataDesc['link']) . "'");
                }

                $mainStore = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . $menuType[1] . "'");
                if (!empty($mainStore->num_rows)) {
                    foreach ($mainStore->rows as $storeData) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_to_store SET menu_child_id = '" . (int)$menu_child_id . "', store_id = '" . $storeData['store_id'] . "'");
                    }
                }

                $this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE menu_id = '" . $menuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE menu_id = '" . $menuType[1] . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "menu_to_store` WHERE menu_id = '" . $menuType[1] . "'");

                $menuType[1] = $menu_child_id;

                $subMenu = '1';
            }

            if ($menuType[0] == 'MainMenu' && empty($subMenu)) {
                $this->db->query("UPDATE `" . DB_PREFIX . "menu` SET sort_order = '" . (int)$menuOrder . "' WHERE menu_id = '" . $menuType[1] . "'");
                $menuOrder++;
                $menuSubOrder = 1;
            } else {
                $this->db->query("UPDATE `" . DB_PREFIX . "menu_child` SET sort_order = '" . (int)$menuSubOrder . "', menu_id = '" . $data['menu-item-parent-id'][$key] . "' WHERE menu_child_id = '" . $menuType[1] . "'");
                $this->db->query("UPDATE `" . DB_PREFIX . "menu_child_description` SET  menu_id = '" . $data['menu-item-parent-id'][$key] . "' WHERE menu_child_id = '" . $menuType[1] . "'");
                $menuSubOrder++;
            }
        }
    }
}
