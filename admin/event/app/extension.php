<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventAppExtension extends Event
{

    public function postAppInitialise()
    {
        $this->url->addRewrite($this);
    }

    public function rewrite($url)
    {
        if (!strstr($url, 'extension/')
            && !strstr($url, 'analytics/')
            && !strstr($url, 'antifraud/')
            && !strstr($url, 'captcha/')
            && !strstr($url, 'editor/')
            && !strstr($url, 'feed/')
            && !strstr($url, 'module/')
            && !strstr($url, 'other/')
            && !strstr($url, 'payment/')
            && !strstr($url, 'shipping/')
            && !strstr($url, 'total/')
            && !strstr($url, 'twofactorauth/')) {
            return $url;
        }

        $uri = new Uri($url);

        switch ($uri->getVar('route')) {
            case 'extension/analytics':
            case 'extension/antifraud':
            case 'extension/captcha':
            case 'extension/editor':
            case 'extension/feed':
            case 'extension/module':
            case 'extension/other':
            case 'extension/payment':
            case 'extension/shipping':
            case 'extension/total':
            case 'extension/twofactorauth':
            case 'extension/extension':
                $uri->setVar('route', 'extension/extension');
                break;
            default:
                return $url;
        }

        $filter_name = $uri->getVar('filter_name');
        if (empty($filter_name) && isset($this->request->get['filter_name'])) {
            $uri->setVar('filter_name', urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8')));
        }

        $filter_author = $uri->getVar('filter_author');
        if (empty($filter_author) && isset($this->request->get['filter_author'])) {
            $uri->setVar('filter_author', urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8')));
        }

        $filter_status = $uri->getVar('filter_status');
        if (empty($filter_status) && isset($this->request->get['filter_status'])) {
            $uri->setVar('filter_status', $this->request->get['filter_status']);
        }

        $filter_type = $uri->getVar('filter_type');
        if (empty($filter_type) && isset($this->request->get['filter_type'])) {
            $uri->setVar('filter_type', $this->request->get['filter_type']);
        }

        $sort = $uri->getVar('sort');
        if (empty($sort) && isset($this->request->get['sort'])) {
            $uri->setVar('sort', $this->request->get['sort']);
        }

        $order = $uri->getVar('order');
        if (empty($order) && isset($this->request->get['order'])) {
            $uri->setVar('order', $this->request->get['order']);
        }

        $page = $uri->getVar('page');
        if (empty($page) && isset($this->request->get['page'])) {
            $uri->setVar('page', $this->request->get['page']);
        }

        return $uri->toString();
    }
}
