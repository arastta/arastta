<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class Url extends Object {

    protected $domain;
    protected $ssl;
    protected $rewrite = array();

    protected $config;

	public function __construct($domain, $ssl = '', $registry = '') {
		$this->domain = $domain;
		$this->ssl = $ssl;

        if (is_object($registry)) {
            $this->config = $registry->get('config');
        }
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
        if (empty($this->ssl)) {
            $this->ssl = str_replace('http://', 'https://', $this->domain);
        }

        if (is_object($this->config)) { // keep B/C alive
            $config_secure = $this->config->get('config_secure');

            if ($config_secure == 3) { // everywhere
                $url = $this->ssl;
            }
            else if (($config_secure == 2) and (Client::isAdmin() == false)) { // catalog
                $url = $this->ssl;
            }
            else if (($config_secure == 1) and (Client::isAdmin() == false) and ($secure == true)) { // checkout
                $url = $this->ssl;
            }
            else {
                $url = $this->domain;
            }
        }
        else {
            if ($secure) {
                $url = $this->ssl;
            }
            else {
                $url = $this->domain;
            }
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
        if (!$secure) {
            $domain = $this->get('domain');
        }
        else {
            $domain = $this->get('ssl');
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
}