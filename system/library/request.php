<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Request {

    public $get = array();
    public $post = array();
    public $cookie = array();
    public $files = array();
    public $server = array();

    protected $db;
    protected $config;
    protected $security;
    protected $purifier;

    public function __construct($registry = '') {
        if (!empty($registry)) {
            $this->db = $registry->get('db');
            $this->config = $registry->get('config');
            $this->security = $registry->get('security');
        }

        if (is_object($this->config) and $this->config->get('config_sec_htmlpurifier')) {
            $config = HTMLPurifier_Config::createDefault();
            $this->purifier = new HTMLPurifier($config);
        }

        if (is_object($this->security) && Client::isCatalog()) {
            $this->security->checkRequest($_GET, 'get');
            $this->security->checkRequest($_POST, 'post');
        }

        $this->get = $this->clean($_GET);
        $this->post = $this->clean($_POST);
        $this->request = $this->clean($_REQUEST);
        $this->cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
        $this->server = $this->clean($_SERVER);
    }

    public function clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $clean_value = $this->clean($value);

                // Accept only "a-Z 0-9 / - ." for route variable
                if (($key == 'route') && !is_array($clean_value)) {
                    $clean_value = preg_replace('~[^\w-/\.]*~', '', $clean_value);;
                }

                $data[$this->clean($key)] = $clean_value;
            }
        }
        else {
            if (is_object($this->purifier)) {
                $data = $this->purifier->purify($data);
            }
            else {
                $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
            }
        }

        return $data;
    }

    public function isGet() {
        static $status = null;

        if ($status === null) {
            $status = ($this->server['REQUEST_METHOD'] === 'GET');
        }

        return $status;
    }

    public function isPost() {
        static $status = null;

        if ($status === null) {
            $status = ($this->server['REQUEST_METHOD'] === 'POST');
        }

        return $status;
    }

    public function isSSL() {
        static $status = null;

        if ($status === null) {
            $https = $this->server['HTTPS'];

            $status = (($https === 'on') or ($https === '1'));
        }

        return $status;
    }

    public function isAjax() {
        static $status = null;

        if ($status === null) {
            if (!empty($this->server['HTTP_X_REQUESTED_WITH'])) {
                $x_request = $this->server['HTTP_X_REQUESTED_WITH'];

                $status = (strtolower($x_request) === 'xmlhttprequest');
            }
            else {
                $status = false;
            }
        }

        return $status;
    }
}
