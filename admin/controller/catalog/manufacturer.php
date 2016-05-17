<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCatalogManufacturer extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/manufacturer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/manufacturer');

        $this->getList();
    }

    public function add() {
        $this->load->language('catalog/manufacturer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/manufacturer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                 $this->response->redirect($this->url->link('catalog/manufacturer/edit', 'manufacturer_id='.$manufacturer_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                 $this->response->redirect($this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
            
            $this->response->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('catalog/manufacturer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/manufacturer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_manufacturer->editManufacturer($this->request->get['manufacturer_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                 $this->response->redirect($this->url->link('catalog/manufacturer/edit', 'manufacturer_id='.$this->request->get['manufacturer_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                 $this->response->redirect($this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
            
            $this->response->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/manufacturer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $manufacturer_id) {
                $this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        $data = $this->language->all();

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        $data['add'] = $this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/manufacturer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['manufacturers'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        if (!empty($filter_name) || !empty($filter_status)) {
            $manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturersFilter($filter_data);
        } else {
            $manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers();
        }

        $results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

        foreach ($results as $result) {
            $data['manufacturers'][] = array(
                'manufacturer_id' => $result['manufacturer_id'],
                'name'            => $result['name'],
                'sort_order'      => $result['sort_order'],
                'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit'            => $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $result['manufacturer_id'] . $url, 'SSL')
            );
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['text_confirm_title'] = sprintf($this->language->get('text_confirm_title'), $this->language->get('heading_title'));

        $data['sort_name'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $manufacturer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($manufacturer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($manufacturer_total - $this->config->get('config_limit_admin'))) ? $manufacturer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $manufacturer_total, ceil($manufacturer_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/manufacturer_list.tpl', $data));
    }

    protected function getForm() {
        $data = $this->language->all();

        $data['text_form'] = !isset($this->request->get['manufacturer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = array();
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        if (!isset($this->request->get['manufacturer_id'])) {
            $data['action'] = $this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['manufacturer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['manufacturer_description'])) {
            $data['manufacturer_description'] = $this->request->post['manufacturer_description'];
        } elseif (isset($this->request->get['manufacturer_id'])) {
            $data['manufacturer_description'] = $this->model_catalog_manufacturer->getManufacturerDescriptions($this->request->get['manufacturer_id']);
        } else {
            $data['manufacturer_description'] = array();
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['manufacturer_store'])) {
            $data['manufacturer_store'] = $this->request->post['manufacturer_store'];
        } elseif (isset($this->request->get['manufacturer_id'])) {
            $data['manufacturer_store'] = $this->model_catalog_manufacturer->getManufacturerStores($this->request->get['manufacturer_id']);
        } else {
            $data['manufacturer_store'] = array(0);
        }

        if (isset($this->request->post['seo_url'])) {
            $data['seo_url'] = $this->request->post['seo_url'];
        } elseif (!empty($manufacturer_info)) {
            $data['seo_url'] = $manufacturer_info['seo_url'];
        } else {
            $data['seo_url'] = array();
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($manufacturer_info)) {
            $data['image'] = $manufacturer_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($manufacturer_info) && is_file(DIR_IMAGE . $manufacturer_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($manufacturer_info)) {
            $data['sort_order'] = $manufacturer_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($manufacturer_info)) {
            $data['status'] = $manufacturer_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['manufacturer_id'] = isset($this->request->get['manufacturer_id']) ? $this->request->get['manufacturer_id'] : 0;

        // Preview link
        foreach ($data['languages'] as $language) {
            $data['preview'][$language['language_id']] = $this->getSeoLink($data['manufacturer_id'], $language['code']);
        }

        $this->load->model('appearance/layout');

        $data['layouts'] = $this->model_appearance_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/manufacturer_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['manufacturer_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        $this->load->model('catalog/url_alias');

        foreach ($this->request->post['seo_url'] as $language_id => $value) {
            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($value, $language_id);

            if ($url_alias_info && isset($this->request->get['manufacturer_id']) && $url_alias_info['query'] != 'manufacturer_id=' . $this->request->get['manufacturer_id']) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }

            if ($url_alias_info && !isset($this->request->get['manufacturer_id'])) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }
        }
        
        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('catalog/product');

        foreach ($this->request->post['selected'] as $manufacturer_id) {
            $product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);

            if ($product_total) {
                $this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
            }
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/manufacturer');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'manufacturer_id' => $result['manufacturer_id'],
                    'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function quick()
    {
        $this->load->language('catalog/manufacturer');

        $json = array();

        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/url_alias');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateQuick()) {
            $this->trigger->fire('pre.admin.manufacturer.quick', array(&$this->request->post));

            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            if ($this->request->post['name']) {
                foreach ($languages as $language) {
                    $this->request->post['manufacturer_description'][$language['language_id']]['name'] = $this->request->post['name'];
                    $this->request->post['manufacturer_description'][$language['language_id']]['description'] = '';
                    $this->request->post['manufacturer_description'][$language['language_id']]['meta_description'] = '';
                    $this->request->post['manufacturer_description'][$language['language_id']]['meta_keyword'] = '';
                    $this->request->post['seo_url'][$language['language_id']] = '';
                }
            }

            $this->request->post['manufacturer_store'][] = 0;

            $this->load->model('setting/store');

            $stores = $this->model_setting_store->getStores();

            if ($stores) {
                foreach ($stores as $store) {
                    $this->request->post['manufacturer_store'][] = $store['store_id'];
                }
            }

            $manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($this->request->post);

            $this->trigger->fire('post.admin.manufacturer.quick', array($manufacturer_id));

            $json['success'] = $this->language->get('text_success');
            $json['manufacturer_id'] = $manufacturer_id;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateQuick() 
    {
        if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        $this->trigger->fire('post.admin.manufacturer.validate.quick', array(&$this->error));

        return !$this->error;
    }

    public function inline() {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateInline()) {
            $this->load->model('catalog/manufacturer');
            
            if (isset($this->request->post['seo_url'])) {
                $this->load->model('catalog/url_alias');

                $this->model_catalog_url_alias->addAlias('manufacturer', $this->request->get['manufacturer_id'], $this->request->post['seo_url'], $this->request->post['language_id']);
                $json['language_id'] = $this->request->post['language_id'];
            } else {
                foreach ($this->request->post as $key => $value) {
                    $this->model_catalog_manufacturer->updateManufacturer($this->request->get['manufacturer_id'], $key, $value);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateInline() {
        if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['name']) && !isset($this->request->post['status']) && !isset($this->request->post['seo_url'])) {
            $this->error['warning'] = $this->language->get('error_inline_field');
        }

        return !$this->error;
    }

    public function getSeoLink($manufacturer_id, $language_code) {
        $old_session_code = isset($this->session->data['language']) ? $this->session->data['language'] : '';
        $old_config_code = $this->config->get('config_language');

        $this->session->data['language'] = $language_code;
        $this->config->set('config_language', $language_code);

        $url = $this->config->get('config_url');

        if (empty($url)) {
            $url = HTTP_SERVER;

            $admin_folder = str_replace(DIR_ROOT, '', DIR_ADMIN);

            $url = str_replace($admin_folder, '', $url);
        }

        $route = new Route($this->registry);

        $url .= ltrim($route->rewrite('index.php?route=product/manufacturer/info&manufacturer_id=' . $manufacturer_id), '/');
        
        if (!empty($old_session_code)) {
            $this->session->data['language'] = $old_session_code;
        }

        $this->config->set('config_language', $old_config_code);

        return $url;
    }
}
