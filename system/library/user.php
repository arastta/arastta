<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class User extends Object {

    protected $user_id;
    protected $username;
    protected $email;
    protected $user_group_id;
    protected $theme;
    protected $params;
    protected $permission = array();

    protected $db;
    protected $config;
    protected $request;
    protected $session;
    protected $response;

    public function __construct($registry) {
        $this->db = $registry->get('db');
        $this->config = $registry->get('config');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');
        $this->response = $registry->get('response');

        if (isset($this->session->data['user_id'])) {
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

            if ($user_query->num_rows) {
                $this->user_id = $user_query->row['user_id'];
                $this->username = $user_query->row['username'];
                $this->email = $user_query->row['email'];
                $this->user_group_id = $user_query->row['user_group_id'];

                $params = json_decode($user_query->row['params'], true);

                if (!isset($this->session->data['theme'])) {
                    $this->session->data['theme'] = !empty($params['theme']) ? $params['theme'] : 'basic';
                }
                
                $this->theme = !empty($params['theme']) ? $params['theme'] : 'basic';
                $this->params = $params;

                $this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

                $user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

                $permissions = unserialize($user_group_query->row['permission']);

                if (is_array($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $this->permission[$key] = $value;
                    }
                }
            } else {
                $this->logout();
            }
        }
    }

    public function login($email, $password) {
        $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE email = '" . $this->db->escape($email) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

        if ($user_query->num_rows) {
            $this->session->data['user_id'] = $user_query->row['user_id'];

            $params = json_decode($user_query->row['params'], true);

            $this->session->data['theme'] = !empty($params['theme']) ? $params['theme'] : 'basic';
            $this->session->data['admin_language'] = !empty($params['language']) ? $params['language'] : $this->config->get('config_admin_language');

            $this->user_id = $user_query->row['user_id'];
            $this->username = $user_query->row['username'];
            $this->email = $user_query->row['email'];
            $this->user_group_id = $user_query->row['user_group_id'];
            $this->theme = !empty($params['theme']) ? $params['theme'] : 'basic';
            $this->params = $params;

            $user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

            $permissions = unserialize($user_group_query->row['permission']);

            if (is_array($permissions)) {
                foreach ($permissions as $key => $value) {
                    $this->permission[$key] = $value;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        unset($this->session->data['user_id']);
        unset($this->session->data['theme']);

        $this->user_id = '';
        $this->username = '';
        $this->email = '';
        $this->theme = '';
        $this->params = '';
    }

    public function hasPermission($key, $value) {
        if (isset($this->permission[$key])) {
            return in_array($value, $this->permission[$key]);
        } else {
            return false;
        }
    }

    public function validate($action = 'modify', $route = '') {
        if (empty($route) && isset($this->request->get['route'])) {
            $route = $this->request->get['route'];
        }

        return $this->user->hasPermission($action, $route);
    }

    public function isLogged() {
        return $this->user_id;
    }

    public function getId() {
        return $this->user_id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function getGroupId() {
        return $this->user_group_id;
    }

    public function getTheme() {
        return $this->theme;
    }
    
    public function getParams() {
        return $this->params;
    }
}
