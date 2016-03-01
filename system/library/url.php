<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Url extends Object {

    protected $domain;
    protected $ssl;
    protected $rewrite = array();

    protected $config;

    public function __construct($domain, $ssl = '', $registry = '') {
        $this->domain = $domain;
        $this->ssl = str_replace('http://', 'https://', $ssl);

        if (is_object($registry)) {
            $this->config = $registry->get('config');
        }
    }

    public function addRewrite($rewrite) {
        $this->rewrite[] = $rewrite;
    }

    public function link($route, $args = '', $secure = false) {
        if (strstr($route, 'extension/') && Client::isAdmin()) {
            $this->checkExtManagerRoute($route, $args);
        }

        if (empty($this->ssl)) {
            $this->ssl = str_replace('http://', 'https://', $this->domain);
        }

        if ($this->useSSL($secure)) {
            $url = $this->get('ssl');
        }
        else {
            $url = $this->get('domain');
        }

        // fix if admin forgot the trailing slash
        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        $url .= 'index.php?route=' . $route;

        if ($args) {
            $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
        }

        foreach ($this->rewrite as $rewrite) {
            $url = $rewrite->rewrite($url);
        }

        return $url;
    }

    public function getDomain($secure = false) {
        if ($this->useSSL($secure)) {
            $domain = $this->get('ssl');
        }
        else {
            $domain = $this->get('domain');
        }

        if (empty($domain)) {
            $domain = $this->getFullUrl(false, true);
        }

        return $domain;
    }

    public function getSubdomain() {
        return $this->getFullUrl(true);
    }

    public function getFullUrl($path_only = false, $host_only = false) {
        $url = '';

        if ($host_only == false) {
            if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI'])) {
                $script_name = $_SERVER['PHP_SELF'];
            }
            else {
                $script_name = $_SERVER['SCRIPT_NAME'];
            }

            $url = rtrim(dirname($script_name), '/.\\');
        }

        if ($path_only == false) {
            $port = 'http://';
            if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
                $port = 'https://';
            }

            $url = $port . $_SERVER['HTTP_HOST'] . $url;
        }

        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        return $url;
    }

    public function useSSL($secure) {
        $ret = false;

        if (is_object($this->config)) { // keep B/C alive
            $config_secure = $this->config->get('config_secure');

            if ($config_secure == 3) { // everywhere
                $ret = true;
            }
            else if (($config_secure == 2) and (Client::isCatalog())) { // catalog
                $ret = true;
            }
            else if (($config_secure == 1) and (Client::isCatalog()) and ($secure == true)) { // checkout
                $ret = true;
            }
        }
        else {
            if ($secure) {
                $ret = true;
            }
        }

        return $ret;
    }

    public function checkExtManagerRoute(&$route, &$args) {
        switch ($route) {
            case 'extension/payment':
            case 'extension/shipping':
            case 'extension/module':
            case 'extension/total':
            case 'extension/feed':
                $r = explode('/', $route);

                $route = 'extension/extension';
                $args = 'filter_type='.$r[1].'&'.$args;
                break;
        }
    }
}
