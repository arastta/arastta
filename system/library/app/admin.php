<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

class Admin extends App
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
            $store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $this->db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/.\\') . '/') . "'");
        } else {
            $store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/.\\') . '/') . "'");
        }

        if ($store_query->num_rows) {
            $this->config->set('config_store_id', $store_query->row['store_id']);
        } else {
            $this->config->set('config_store_id', 0);
        }

        // Settings
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");

        foreach ($query->rows as $setting) {
            if (!$setting['serialized']) {
                $this->config->set($setting['key'], $setting['value']);
            } else {
                $this->config->set($setting['key'], unserialize($setting['value']));
            }
        }

        // Loader
        $this->registry->set('load', new Loader($this->registry));

        // Trigger
        $this->registry->set('trigger', new Trigger($this->registry));

        // Url
        $this->registry->set('url', new Url(HTTP_SERVER, HTTPS_SERVER, $this->registry));

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

        if (!isset($this->session->data['admin_language']) || $this->session->data['admin_language'] != $language['code']) {
            $this->session->data['admin_language'] = $language['code'];
        }

        //$this->config->set('config_language', $language['code']);
        $this->config->set('config_language_dir', $language['directory']);
        $this->config->set('config_language_id', $language['language_id']);

        // Language
        $this->registry->set('language', new Language($language['directory'], $this->registry));

        // Document
        $this->registry->set('document', new Document());

        // SEO
        $this->registry->set('seo', new SEO($this->registry));
        
        $this->trigger->fire('post.app.initialise');
    }

    public function ecommerce()
    {
        // Set time zone
        date_default_timezone_set($this->config->get('config_timezone', 'UTC'));
        $dt = new \DateTime();
        $this->db->setTimezone($dt->format('P'));

        // Currency
        $this->registry->set('currency', new Currency($this->registry));

        // Weight
        $this->registry->set('weight', new Weight($this->registry));

        // Length
        $this->registry->set('length', new Length($this->registry));

        // User
        $this->registry->set('user', new User($this->registry));

        // Encryption
        $this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));

        // Email Template
        $this->registry->set('emailtemplate', new Emailtemplate($this->registry));

        // Theme
        if (isset($this->request->get['theme'])) {
            $this->session->data['theme'] = $this->request->get['theme'];
        }

        $this->trigger->fire('post.app.ecommerce');
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
        # B/C end

        if (!$this->canAccessAdmin()) {
            $catalog = Client::isAdmin() ? HTTPS_CATALOG : HTTPS_SERVER;

            $this->response->redirect($catalog);
        }
        
        // Front Controller
        $controller = new Front($this->registry);

        // Login
        $controller->addPreAction(new Action('common/login/check'));

        // Permission
        $controller->addPreAction(new Action('error/permission/check'));

        // Router
        if (isset($this->request->get['route'])) {
            $action = new Action($this->request->get['route']);
        } else {
            $action = new Action('common/dashboard');
        }

        // Dispatch
        $controller->dispatch($action, new Action('error/not_found'));

        $this->trigger->fire('post.app.dispatch');
    }

    protected function canAccessAdmin()
    {
        if (isset($this->session->data['user_id'])) {
            return true;
        }

        if (!$this->config->get('config_sec_admin_keyword')) {
            return true;
        }

        if (isset($this->request->get[$this->config->get('config_sec_admin_keyword')])) {
            return true;
        }

        if (isset($this->request->get['route']) && ($this->request->get['route'] == 'common/login') && !empty($this->request->post['email']) && !empty($this->request->post['password'])) {
            return true;
        }

        return false;
    }
}
