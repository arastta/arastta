<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventAppExtension extends Event
{

    public function postAppInitialise()
    {
        $this->url->addRewrite($this);
    }

    public function rewrite($url)
    {
        if (!strstr($url, 'extension/')) {
            return $url;
        }

        $uri = new Uri($url);

        switch ($uri->getVar('route')) {
            case 'extension/payment':
            case 'extension/shipping':
            case 'extension/module':
            case 'extension/total':
            case 'extension/feed':
                $uri->setVar('route', 'extension/extension');
                break;
        }

        if (isset($this->request->get['filter_name'])) {
            $uri->setVar('filter_name', urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8')));
        }

        if (isset($this->request->get['filter_author'])) {
            $uri->setVar('filter_author', urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8')));
        }

        if (isset($this->request->get['filter_status'])) {
            $uri->setVar('filter_status', $this->request->get['filter_status']);
        }

        if (isset($this->request->get['filter_type'])) {
            $uri->setVar('filter_type', $this->request->get['filter_type']);
        }

        if (isset($this->request->get['sort'])) {
            $uri->setVar('sort', $this->request->get['sort']);
        }

        if (isset($this->request->get['order'])) {
            $uri->setVar('order', $this->request->get['order']);
        }

        $page = $uri->getVar('page');
        if (empty($page) && isset($this->request->get['page'])) {
            $uri->setVar('page', $this->request->get['page']);
        }

        return $uri->toString();
    }
}
