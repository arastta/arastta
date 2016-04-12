<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

class Catalog extends App
{

    public function initialise()
    {
        // File System
        $this->registry->set('filesystem', new Filesystem());

        // Config
        $this->registry->set('config', new Object());

        // Database
        $this->registry->set('db', new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));

        // Store
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $this->db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        } else {
            $store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        }

        if ($store_query->num_rows) {
            $this->config->set('config_store_id', $store_query->row['store_id']);
        } else {
            $this->config->set('config_store_id', 0);
        }

        // Settings
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $this->config->set($result['key'], $result['value']);
            } else {
                $this->config->set($result['key'], unserialize($result['value']));
            }
        }

        if (!$store_query->num_rows) {
            $this->config->set('config_url', HTTP_SERVER);
            $this->config->set('config_ssl', HTTPS_SERVER);
        }

        // Loader
        $this->registry->set('load', new Loader($this->registry));

        // Trigger
        $this->registry->set('trigger', new Trigger($this->registry));

        // Url
        $this->registry->set('url', new Url($this->config->get('config_url'), $this->config->get('config_ssl'), $this->registry));

        // Uri
        $this->registry->set('uri', new Uri());

        // Log
        $this->registry->set('log', new Log($this->config->get('config_error_filename')));

        // Error Handler
        if ($this->config->get('config_error_display', 0) == 2) {
            ErrorHandler::register();
            ExceptionHandler::register();
        } else {
            set_error_handler(array($this, 'errorHandler'));
        }

        // Security
        $this->registry->set('security', new Security($this->registry));

        // Request
        $this->registry->set('request', new Request($this->registry));

        // Response
        $response = new Response();
        $response->addHeader('Content-Type: text/html; charset=utf-8');
        $response->setCompression($this->config->get('config_compression'));
        $this->registry->set('response', $response);

        // Cache
        $cache = new Cache($this->config->get('config_cache_storage', 'file'), $this->config->get('config_cache_lifetime', 86400), $this->config);
        $this->registry->set('cache', $cache);

        // Session
        $this->registry->set('session', new Session());

        // Utility
        $utility = new Utility($this->registry);
        $this->registry->set('utility', $utility);

        // Language Detection
        $language = $utility->getLanguage();

        if (!isset($this->session->data['language']) || $this->session->data['language'] != $language['code']) {
            $this->session->data['language'] = $language['code'];
        }

        if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $language['code']) {
            setcookie('language', $language['code'], time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
        }

        $this->config->set('config_language', $language['code']);
        $this->config->set('config_language_dir', $language['directory']);
        $this->config->set('config_language_id', $language['language_id']);

        // Language
        $this->registry->set('language', new Language($language['directory'], $this->registry));

        // Page Cache
        $pagecache = new Pagecache($this->registry);
        $pagecache->getPage();
        $this->registry->set('pagecache', $pagecache);

        // Document
        $this->registry->set('document', new Document());

        $this->trigger->fire('post.app.initialise');
    }

    public function ecommerce()
    {
        // Set time zone
        date_default_timezone_set($this->config->get('config_timezone', 'UTC'));
        $dt = new \DateTime();
        $this->db->setTimezone($dt->format('P'));

        // Customer
        $this->registry->set('customer', new Customer($this->registry));

        // Customer Group
        if ($this->customer->isLogged()) {
            $this->config->set('config_customer_group_id', $this->customer->getGroupId());
        } elseif (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id'])) {
            // For API calls
            $this->config->set('config_customer_group_id', $this->session->data['customer']['customer_group_id']);
        } elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['customer_group_id'])) {
            $this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
        }

        // Tracking Code
        if (isset($this->request->get['tracking'])) {
            setcookie('tracking', $this->request->get['tracking'], time() + 3600 * 24 * 1000, '/');

            $this->db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $this->db->escape($this->request->get['tracking']) . "'");
        }

        // Affiliate
        $this->registry->set('affiliate', new Affiliate($this->registry));

        // Currency
        $this->registry->set('currency', new Currency($this->registry));

        // Tax
        $this->registry->set('tax', new Tax($this->registry));

        // Weight
        $this->registry->set('weight', new Weight($this->registry));

        // Length
        $this->registry->set('length', new Length($this->registry));

        // Cart
        $this->registry->set('cart', new Cart($this->registry));

        // Encryption
        $this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));

        // Email Template
        $this->registry->set('emailtemplate', new Emailtemplate($this->registry));

        $this->trigger->fire('post.app.ecommerce');
    }

    public function route()
    {
        // Route
        $route = new Route($this->registry);

        // Parse
        $route->parse();

        // Set
        $this->registry->set('route', $route);

        $this->trigger->fire('post.app.route');
    }

    public function dispatch()
    {
        # B/C start
        global $registry;
        $registry = $this->registry;

        global $config;
        $config = $this->registry->get('config');

        global $db;
        $db = $this->registry->get('db');

        global $log;
        $log = $this->registry->get('log');

        global $loader;
        $loader = $this->registry->get('load');
        # B/C end
        
        // Front Controller
        $controller = new Front($this->registry);

        // Maintenance Mode
        $controller->addPreAction(new Action('common/maintenance'));

        // Router
        if (isset($this->request->get['route'])) {
            $action = new Action($this->request->get['route']);
        } else {
            $action = new Action('common/home');
        }

        // Dispatch
        $controller->dispatch($action, new Action('error/not_found'));

        // Set the page cache if enabled
        $this->pagecache->setPage($this->response);

        $this->trigger->fire('post.app.dispatch');
    }
}
