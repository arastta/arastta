<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Pagecache extends Object
{

    protected $key = null;
    protected $uri = null;
    protected $cache = null;
    protected $config = null;
    protected $request = null;
    protected $session = null;

    public function __construct($registry)
    {
        $this->uri = $registry->get('uri');
        $this->cache = $registry->get('cache');
        $this->config = $registry->get('config');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');

        if (!$this->config->get('config_pagecache', 0)) {
            return;
        }

        $this->key = $this->getKey();
    }

    public function getPage()
    {
        if (!$this->canCache()) {
            return false;
        }

        $response = $this->cache->get($this->key);

        if ($response instanceof Response) {
            $response->output();

            exit();
        }

        return false;
    }

    public function setPage($response)
    {
        if (!$this->canCache()) {
            return false;
        }

        $output = $response->getOutput();
        if (empty($output)) {
            return false;
        }

        $this->cache->set($this->key, $response);
    }

    public function getKey()
    {
        $language = '_'.$this->config->get('config_language');

        // Currency class not instantiated yet so we should get data from GET or SESSION or CONFIG
        if (!empty($this->request->get['currency'])) {
            $currency = '_'.$this->request->get['currency'];
        } elseif (!empty($this->session->data['currency'])) {
            $currency = '_'.$this->session->data['currency'];
        } else {
            $currency = '_'.$this->config->get('config_currency');
        }

        $key = strtolower($this->uri->toString().$language.$currency);

        return $key;
    }

    public function canCache()
    {
        // Don't cache if disabled
        if (!$this->config->get('config_pagecache', 0)) {
            return false;
        }

        // Don't cache admin side
        if (IS_ADMIN) {
            return false;
        }

        // Don't cache other requests but GET
        if (!$this->request->isGet()) {
            return false;
        }

        // Don't cache secure pages
        if ($this->request->isSSL()) {
            return false;
        }

        // Don't cache if GET has affiliate, tracking, redirect
        if (!empty($this->request->get['affiliate']) or !empty($this->request->get['tracking']) or !empty($this->request->get['redirect'])) {
            return false;
        }

        // Don't cache if customer/affiliate logged in or cart is not empty
        if (!empty($this->session->data['customer_id']) or !empty($this->session->data['affiliate_id']) or !empty($this->session->data['cart'])) {
            return false;
        }

        // Don't cache if patterns match to the URL
        $url = $this->uri->toString(array('path', 'query'));

        $patterns = array(
            '#/captcha#',
            '#account/#',
            '#affiliate/#',
            '#checkout/#',
            '#information/contact#',
            '#information/sitemap#',
            '#product/compare#',
            '#product/product/upload#',
            '#register/#'
        );

        if ($this->config->get('config_pagecache_exclude')) {
            foreach (explode(",", $this->config->get('config_pagecache_exclude')) as $id) {
                $id = trim($id);

                if ($id) {
                    $patterns[] = '#'.$id.'#';
                }
            }
        }

        foreach ($patterns as $pattern) {
            if (!preg_match($pattern, $url)) {
                continue;
            }

            return false;
        }

        return true;
    }
}
