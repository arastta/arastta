<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerFeedFacebookStore extends Controller
{

    private $error = array();
    
    public function index()
    {
        $this->load->language('feed/facebook_store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addStyle('view/stylesheet/facebook-store.css');
        $this->document->addStyle('view/javascript/jquery/layout/jquery-ui.css');
        
        $this->document->addScript('view/javascript/jquery/layout/jquery-ui.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery-lockfixed.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery.ui.touch-punch.js');
        $this->document->addScript('view/javascript/facebook-store/facebook-store.js');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('facebook_store', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (!$this->cache->clear()) {
                $json['error'] = $this->language->get('error_cache_not_cleared');
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $route = $this->request->get['route'];
                $module_id = '';
                if (isset($this->request->get['module_id'])) {
                    $module_id = '&module_id=' . $this->request->get['module_id'];
                } elseif ($this->db->getLastId()) {
                    $module_id = '&module_id=' . $this->db->getLastId();
                }
                $this->response->redirect($this->url->link($route, 'token=' . $this->session->data['token'] . $module_id, 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['app_id'])) {
            $data['error_app_id'] = $this->error['app_id'];
        } else {
            $data['error_app_id'] = array();
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_feed'),
            'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('feed/facebook_store', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('feed/facebook_store', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['facebook_store_app_id'])) {
            $data['app_id'] = $this->request->post['facebook_store_app_id'];
        } elseif ($this->config->get('facebook_store_app_id')) {
            $data['app_id'] = $this->config->get('facebook_store_app_id');
        } else {
            $data['app_id'] = '';
        }

        if (isset($this->request->post['facebook_store_show_header_currency'])) {
            $data['show_header_currency'] = $this->request->post['facebook_store_show_header_currency'];
        } elseif ($this->config->get('facebook_store_show_header_currency')) {
            $data['show_header_currency'] = $this->config->get('facebook_store_show_header_currency');
        } else {
            $data['show_header_currency'] = 1;
        }

        if (isset($this->request->post['facebook_store_show_header_language'])) {
            $data['show_header_language'] = $this->request->post['facebook_store_show_header_language'];
        } elseif ($this->config->get('facebook_store_show_header_language')) {
            $data['show_header_language'] = $this->config->get('facebook_store_show_header_language');
        } else {
            $data['show_header_language'] = 1;
        }

        if (isset($this->request->post['facebook_store_show_header_category'])) {
            $data['show_header_category'] = $this->request->post['facebook_store_show_header_category'];
        } elseif ($this->config->get('facebook_store_show_header_category')) {
            $data['show_header_category'] = $this->config->get('facebook_store_show_header_category');
        } else {
            $data['show_header_category'] = 1;
        }

        if (isset($this->request->post['facebook_store_show_header_search'])) {
            $data['show_header_search'] = $this->request->post['facebook_store_show_header_search'];
        } elseif ($this->config->get('facebook_store_show_header_search')) {
            $data['show_header_search'] = $this->config->get('facebook_store_show_header_search');
        } else {
            $data['show_header_search'] = 1;
        }

        if (isset($this->request->post['facebook_store_show_footer'])) {
            $data['show_footer'] = $this->request->post['facebook_store_show_footer'];
        } elseif ($this->config->get('facebook_store_show_footer')) {
            $data['show_footer'] = $this->config->get('facebook_store_show_footer');
        } else {
            $data['show_footer'] = 0;
        }

        $data['modules'] = $this->getModules();

        if (isset($this->request->post['facebook_store_feed'])) {
            $feeds = $this->request->post['facebook_store_feed'];
        } elseif ($this->config->get('facebook_store_feed')) {
            $feeds = $this->config->get('facebook_store_feed');
        } else {
            $data['feeds'] = array();
        }

        if (isset($feeds)) {
            $data['feeds'] = $this->getFeeds($feeds);
        }

        if (isset($this->request->post['facebook_store_show_product_description'])) {
            $data['show_product_description'] = $this->request->post['facebook_store_show_product_description'];
        } elseif ($this->config->get('facebook_store_show_product_description')) {
            $data['show_product_description'] = $this->config->get('facebook_store_show_product_description');
        } else {
            $data['show_product_description'] = 0;
        }

        if (isset($this->request->post['facebook_store_show_product_price'])) {
            $data['show_product_price'] = $this->request->post['facebook_store_show_product_price'];
        } elseif ($this->config->get('facebook_store_show_product_price')) {
            $data['show_product_price'] = $this->config->get('facebook_store_show_product_price');
        } else {
            $data['show_product_price'] = 1;
        }

        if (isset($this->request->post['facebook_store_show_product_rating'])) {
            $data['show_product_rating'] = $this->request->post['facebook_store_show_product_rating'];
        } elseif ($this->config->get('facebook_store_show_product_rating')) {
            $data['show_product_rating'] = $this->config->get('facebook_store_show_product_rating');
        } else {
            $data['show_product_rating'] = 0;
        }

        if (isset($this->request->post['facebook_store_show_addtocart'])) {
            $data['show_addtocart'] = $this->request->post['facebook_store_show_addtocart'];
        } elseif ($this->config->get('facebook_store_show_addtocart')) {
            $data['show_addtocart'] = $this->config->get('facebook_store_show_addtocart');
        } else {
            $data['show_addtocart'] = 1;
        }

        if (isset($this->request->post['facebook_store_status'])) {
            $data['status'] = $this->request->post['facebook_store_status'];
        } elseif ($this->config->get('facebook_store_status')) {
            $data['status'] = $this->config->get('facebook_store_status');
        } else {
            $data['status'] = 0;
        }

        $data['token'] = $this->session->data['token'];

        $data['data_feed'] = htmlspecialchars('<iframe src="' . HTTPS_CATALOG . 'index.php?route=feed/facebook_store" frameborder="0" scrolling="no" width="810" height="1400"></iframe>');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('feed/facebook_store.tpl', $data));
    }

    public function getProduct()
    {
        if (isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        }

        $json = array();

        if (isset($product_id)) {
            $this->load->model('tool/image');
            $this->load->model('catalog/product');

            $result = $this->model_catalog_product->getProduct($product_id);

            $json = array(
              'product_id'  => $result['product_id'],
              'name'        => $result['name'],
              'image'       => $this->model_tool_image->resize($result['image'], "45", "45"),
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'feed/facebook_store')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (utf8_strlen($this->request->post['facebook_store_app_id']) < 1) {
            $this->error['app_id'] = $this->language->get('error_app_id');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function getModules()
    {
        $this->load->model('extension/module');

        $results = array();

        $special_module = array(
            'latest',
            'special',
            'bestseller',
        );

        $modules = $this->model_extension_module->getModules();

        foreach ($modules as $module) {
            $module_setting = unserialize($module['setting']);

            if ((!empty($module_setting['feed']) && !empty($module_setting['status']) && !empty($module_setting['product'])) || (in_array($module['code'], $special_module) && !empty($module_setting['status']))) {
                $results[] = array(
                    'module_id' => $module['module_id'],
                    'name'      => $module['name'],
                    'code'      => $module['code'],
                    'setting'   => $module['setting']
                );
            }
        }

        return $results;
    }

    protected function getFeeds($data)
    {
        $result = array();

        foreach ($data as $value) {
            $type = explode('-', $value);

            if ($type[0] == 'product') {
                $this->load->model('tool/image');
                $this->load->model('catalog/product');

                $product = $this->model_catalog_product->getProduct($type[1]);

                $result[] = array(
                    'code'  => 'product',
                    'id'    => $product['product_id'],
                    'name'  => $product['name']
                );
            } else {
                $this->load->model('tool/image');
                $this->load->model('extension/module');

                $module = $this->model_extension_module->getModule($type[1]);

                $result[] = array(
                    'code'  => $type[0],
                    'id'    => $type[1],
                    'name'  => $module['name']
                );

            }
        }

        return $result;
    }
}
