<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelUserUser extends Model {

    public function addUser($data) {
        $this->trigger->fire('pre.admin.user.add', array(&$data));

        $this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', params = '" . $this->db->escape(json_encode($data['params'])) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

        $user_id = $this->db->getLastId();

        $this->trigger->fire('post.admin.user.add', array(&$user_id));

        return $user_id;
    }

    public function editUser($user_id, $data) {
        $this->trigger->fire('pre.admin.user.edit', array(&$data));

        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', params = '" . $this->db->escape(json_encode($data['params'])) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

        if ($data['password']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
        }

        $this->trigger->fire('post.admin.user.edit', array(&$user_id));
    }

    public function editPassword($user_id, $password) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
    }

    public function editCode($email, $code) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function editUserParams($user_id, $data) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

        $params = json_decode($query->row['params'], true);

        foreach ($data as $key => $value) {
            $params[$key] = $value;
        }

        $params = json_encode($params);

        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET params = '" . $this->db->escape($params) . "' WHERE user_id = '" . (int)$user_id . "'");
    }
    
    public function deleteUser($user_id) {
        $this->trigger->fire('pre.admin.user.delete', array(&$user_id));

        $this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");

        $this->trigger->fire('post.admin.user.delete', array(&$user_id));
    }

    public function getUser($user_id) {
        $query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

        return $query->row;
    }

    public function getUserByEmail($email) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE email = '" . $this->db->escape($email) . "'");

        return $query->row;
    }

    public function getUserByUsername($username) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");

        return $query->row;
    }

    public function getUserByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = array()) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "user`";
        
        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $isWhere = 1;
            
            $_sql[] = "username LIKE '" . $this->db->escape($data['filter_user_name']) . "%'";
        }
        
        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group'])) {
            $isWhere = 1;
            
            $_sql[] = "user_group_id LIKE ( SELECT ug.user_group_id FROM `" . DB_PREFIX . "user_group` ug WHERE ug.name LIKE '" . $this->db->escape($data['filter_user_group']) . "%') ";
        }
        
        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $isWhere = 1;
            
            $_sql[] = "firstname LIKE '" . $this->db->escape($data['filter_first_name']) . "%'";
        }
        
        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $isWhere = 1;
            
            $_sql[] = "lastname LIKE '" . $this->db->escape($data['filter_last_name']) . "%'";
        }
        
        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $isWhere = 1;
            
            $_sql[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }
        
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;
            
            $_sql[] = "status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }
        
        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }        

        $sort_data = array(
            'username',
            'status',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY email";
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

    public function getTotalUsers() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");

        return $query->row['total'];
    }

    public function getTotalUsersFilter($data) {
        $sql = ("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");
        
        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $isWhere = 1;
            
            $_sql[] = "username LIKE '" . $this->db->escape($data['filter_user_name']) . "%'";
        }
        
        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group'])) {
            $isWhere = 1;
            
            $_sql[] = "user_group_id LIKE ( SELECT ug.user_group_id FROM `" . DB_PREFIX . "user_group` ug WHERE ug.name LIKE '" . $this->db->escape($data['filter_user_group']) . "%') ";
        }
        
        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $isWhere = 1;
            
            $_sql[] = "firstname LIKE '" . $this->db->escape($data['filter_first_name']) . "%'";
        }
        
        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $isWhere = 1;
            
            $_sql[] = "lastname LIKE '" . $this->db->escape($data['filter_last_name']) . "%'";
        }
        
        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $isWhere = 1;
            
            $_sql[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }
        
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;
            
            $_sql[] = "status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }
        
        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }    

        $query = $this->db->query($sql);
        
        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($user_group_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }
}
