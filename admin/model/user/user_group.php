<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelUserUserGroup extends Model {
    public function addUserGroup($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(serialize($data['permission'])) : '') . "'");
    
        return $this->db->getLastId();
    }

    public function editUserGroup($user_group_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(serialize($data['permission'])) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
    }

    public function deleteUserGroup($user_group_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
    }

    public function getUserGroup($user_group_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        $user_group = array(
            'name'       => $query->row['name'],
            'permission' => unserialize($query->row['permission'])
        );

        return $user_group;
    }

    public function getUserGroups($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "user_group";
        
        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group']) && $data['filter_user_group'] != '*') {    
            $sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_user_group']) . "%'";
        }        
        
        $sql .= " ORDER BY name";

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

    public function getTotalUserGroups() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

        return $query->row['total'];
    }

    public function addPermission($user_group_id, $type, $route) {
        $user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        if ($user_group_query->num_rows) {
            $data = unserialize($user_group_query->row['permission']);

            $data[$type][] = $route;

            $this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(serialize($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
        }
    }

    public function removePermission($user_group_id, $type, $route) {
        $user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        if ($user_group_query->num_rows) {
            $data = unserialize($user_group_query->row['permission']);

            $data[$type] = array_diff($data[$type], array($route));

            $this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(serialize($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
        }
    }
}
