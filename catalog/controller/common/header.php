<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonHeader extends Controller {
    public function index() {
        $data['title'] = $this->document->getTitle();

        if (!empty($this->request->get['route']) and ($this->request->get['route'] != 'common/home')) {
            if ($this->config->get('config_meta_title_add', 0) == 'pre') {
                $data['title'] = $this->config->get('config_meta_title', '') . ' - ' . $data['title'];
            } elseif ($this->config->get('config_meta_title_add', 0) == 'post') {
                $data['title'] = $data['title'] . ' - '  . $this->config->get('config_meta_title', '');
            }
        }

        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        // Allow 3rd parties to interfere
        $this->trigger->fire('pre.load.header', array(&$data));

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['style_declarations'] = $this->document->getStyleDeclarations();
        $data['scripts'] = $this->document->getScripts();
        $data['script_declarations'] = $this->document->getScriptDeclarations();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $data['google_analytics'] = '';
        
        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $server . 'image/' . $this->config->get('config_icon');
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

        // Allow 3rd parties to interfere
        $this->trigger->fire('post.load.header', array(&$data));

        $this->load->language('common/header');

        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');

        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
                    $status = false;

                    break;
                }
            }
        }

        // Menu
        $this->load->model('appearance/menu');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $data['categories'] = array();

        $menus = $this->model_appearance_menu->getMenus();
        $menu_child = $this->model_appearance_menu->getChildMenus();

        foreach ($menus as $id => $menu) {
            $children_data = array();

            $child_active = false;

            foreach ($menu_child as $child_id => $child_menu) {
                $active = false;

                if (($menu['menu_id'] != $child_menu['menu_id']) or !is_numeric($child_id)) {
                    continue;
                }

                $child_name = '';

                if (($menu['menu_type'] == 'category') and ($child_menu['menu_type'] == 'category')) {
                    $filter_data = array(
                        'filter_category_id'  => $child_menu['link'],
                        'filter_sub_category' => true
                    );

                    $child_name = ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '');
                }

                $active = $this->checkActive($child_menu);

                if (!($child_active) && $active) {
                    $child_active = true;
                }

                $children_data[] = array(
                    'name'   => $child_menu['name'] . $child_name,
                    'href'   => $this->getMenuLink($menu, $child_menu),
                    'active' => $active
                );
            }

            $data['categories'][] = array(
                'name'     => $menu['name'] ,
                'children' => $children_data,
                'column'   => $menu['columns'] ? $menu['columns'] : 1,
                'href'     => $this->getMenuLink($menu),
                'active'   => ($child_active) ? true : $this->checkActive($menu)
            );
        }
        
        $data['language'] = $this->load->controller('common/language');
        $data['currency'] = $this->load->controller('common/currency');
        $data['search'] = $this->load->controller('common/search');
        $data['cart'] = $this->load->controller('common/cart');

        // For page specific css
        if (isset($this->request->get['route'])) {
            if (isset($this->request->get['product_id'])) {
                $class = '-' . $this->request->get['product_id'];
            } elseif (isset($this->request->get['path'])) {
                $class = '-' . $this->request->get['path'];
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $class = '-' . $this->request->get['manufacturer_id'];
            } else {
                $class = '';
            }

            $data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
        } else {
            $data['class'] = 'common-home';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
        } else {
            return $this->load->view('default/template/common/header.tpl', $data);
        }
    }

    public function getMenuLink($parent, $child = null)
    {
        $item = empty($child) ? $parent : $child;

        switch ($item['menu_type']) {
            case 'category':
                $route = 'product/category';

                if (!empty($child)) {
                    $args = 'path=' . $parent['link'] . '_' . $item['link'];
                } else {
                    $args = 'path=' . $item['link'];
                }
                break;
            case 'product':
                $route = 'product/product';
                $args = 'product_id=' . $item['link'];
                break;
            case 'manufacturer':
                $route = 'product/manufacturer/info';
                $args = 'manufacturer_id=' . $item['link'];
                break;
            case 'information':
                $route = 'information/information';
                $args = 'information_id=' . $item['link'];
                break;
            default:
                $tmp = explode('&', str_replace('index.php?route=', '', $item['link']));

                if (!empty($tmp)) {
                    $route = $tmp[0];
                    unset($tmp[0]);
                    $args = (!empty($tmp)) ? implode('&', $tmp) : '';
                } else {
                    $route = $item['link'];
                    $args = '';
                }

                break;
        }

        if (strpos($item['link'], 'http') !== false) {
            $link = $item['link'];
        } elseif (empty($route)) {
            $link = '#';
        } else {
            $link = $this->url->link($route, $args);
        }

        return $link;
    }

    public function checkActive($menu)
    {
        if (!isset($this->request->get['route'])) {
            return false;
        }

        $route = basename($this->request->get['route']);

        if ($route != $menu['menu_type'] && $this->request->get['route'] != 'product/manufacturer/info') {
            return false;
        }

        switch ($menu['menu_type']) {
            case 'category':
                if (isset($this->request->get['path'])) {
                    $paths = explode('_', $this->request->get['path']);

                    if (in_array($menu['link'], $paths)) {
                        return true;
                    }
                } elseif (isset($this->request->get['category_id']) && $menu['link'] == $this->request->get['category_id']) {
                    return true;
                }
                break;
            case 'product':
                if (isset($this->request->get['product_id']) && $menu['link'] == $this->request->get['product_id']) {
                    return true;
                }
                break;
            case 'manufacturer':
                if (isset($this->request->get['manufacturer_id']) && $menu['link'] == $this->request->get['manufacturer_id']) {
                    return true;
                }
                break;
            case 'information':
                if (isset($this->request->get['information_id']) && $menu['link'] == $this->request->get['information_id']) {
                    return true;
                }
                break;
            default :
                return false;
        }

        return false;
    }
}
