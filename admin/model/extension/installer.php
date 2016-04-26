<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelExtensionInstaller extends Model
{

    public function extensionExist($type, $code)
    {
        $query = $this->db->query("SELECT extension_id FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");

        if ($query->num_rows) {
            return $query->row['extension_id'];
        } else {
            return false;
        }
    }

    public function languageExist($dir)
    {
        $query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language WHERE `directory` = '" . $this->db->escape($dir) . "'");

        if ($query->num_rows) {
            return $query->row['language_id'];
        } else {
            return false;
        }
    }

    public function themeExist($code)
    {
        $query = $this->db->query("SELECT theme_id FROM " . DB_PREFIX . "theme WHERE `code` = '" . $this->db->escape($code) . "'");

        if ($query->num_rows) {
            return $query->row['theme_id'];
        } else {
            return false;
        }
    }

    public function addPermission($page)
    {
        $permission = $this->db->query("SELECT permission FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = 1");

        $permission = unserialize($permission->row['permission']);

        if (!array_search($page, $permission['access'])) {
            $permission['access'][] = $page;
            $permission['modify'][] = $page;
        }

        $permission = serialize($permission);

        $this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '".$permission."' WHERE `user_group_id` = 1");
    }
}
