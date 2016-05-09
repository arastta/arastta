<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCatalogCategory extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/category');

        $this->getList();
    }

    public function add() {
        $this->load->language('catalog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $category_id = $this->model_catalog_category->addCategory($this->request->post);

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
                 $this->response->redirect($this->url->link('catalog/category/edit', 'category_id='.$category_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                 $this->response->redirect($this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
            
            $this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('catalog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);

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
                 $this->response->redirect($this->url->link('catalog/category/edit', 'category_id='.$this->request->get['category_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                 $this->response->redirect($this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
            
            $this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/category');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $category_id) {
                $this->model_catalog_category->deleteCategory($category_id);
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

            $this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function repair() {
        $this->load->language('catalog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/category');

        if ($this->validateRepair()) {
            $this->model_catalog_category->repairCategories();

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
 
            $this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
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
            'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        $data['add'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['repair'] = $this->url->link('catalog/category/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['categories'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        if (!empty($filter_name) || !empty($filter_status) || !(is_null($filter_status))) {
            $category_total = $this->model_catalog_category->getTotalCategoriesFilter($filter_data);
        } else {
            $category_total = $this->model_catalog_category->getTotalCategories();
        }

        $results = $this->model_catalog_category->getCategories($filter_data);

        foreach ($results as $result) {
            $data['categories'][] = array(
                'category_id' => $result['category_id'],
                'name'        => $result['name'],
                'sort_order'  => $result['sort_order'],
                'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit'        => $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL'),
                'delete'      => $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_confirm_title'] = sprintf($this->language->get('text_confirm_title'), $this->language->get('heading_title'));
        $data['text_select'] = $this->language->get('text_select');
        $data['text_bulk_action'] = $this->language->get('text_bulk_action');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_rebuild'] = $this->language->get('button_rebuild');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
        $data['button_filter'] = $this->language->get('button_filter');        
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
            
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

        $data['sort_name'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

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
        $pagination->total = $category_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();
        
        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;
        $data['token'] = $this->session->data['token'];   

        $data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/category_list.tpl', $data));
    }

    protected function getForm() {
        $data = $this->language->all();
        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_layout'] = $this->language->get('entry_layout');

        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_column'] = $this->language->get('help_column');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
            
        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_design'] = $this->language->get('tab_design');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
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
            'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        if (!isset($this->request->get['category_id'])) {
            $data['action'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['category_description'])) {
            $data['category_description'] = $this->request->post['category_description'];
        } elseif (isset($this->request->get['category_id'])) {
            $data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
        } else {
            $data['category_description'] = array();
        }

        if (isset($this->request->post['path'])) {
            $data['path'] = $this->request->post['path'];
        } elseif (!empty($category_info)) {
            $data['path'] = $category_info['path'];
        } else {
            $data['path'] = '';
        }

        if (isset($this->request->post['parent_id'])) {
            $data['parent_id'] = $this->request->post['parent_id'];
        } elseif (!empty($category_info)) {
            $data['parent_id'] = $category_info['parent_id'];
        } else {
            $data['parent_id'] = 0;
        }

        $this->load->model('catalog/filter');

        if (isset($this->request->post['category_filter'])) {
            $filters = $this->request->post['category_filter'];
        } elseif (isset($this->request->get['category_id'])) {
            $filters = $this->model_catalog_category->getCategoryFilters($this->request->get['category_id']);
        } else {
            $filters = array();
        }

        $data['category_filters'] = array();

        foreach ($filters as $filter_id) {
            $filter_info = $this->model_catalog_filter->getFilter($filter_id);

            if ($filter_info) {
                $data['category_filters'][] = array(
                    'filter_id' => $filter_info['filter_id'],
                    'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
                );
            }
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['category_store'])) {
            $data['category_store'] = $this->request->post['category_store'];
        } elseif (isset($this->request->get['category_id'])) {
            $data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->get['category_id']);
        } else {
            $data['category_store'] = array(0);
        }

        if (isset($this->request->post['seo_url'])) {
            $data['seo_url'] = $this->request->post['seo_url'];
        } elseif (!empty($category_info)) {
            $data['seo_url'] = $category_info['seo_url'];
        } else {
            $data['seo_url'] = array();
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($category_info)) {
            $data['image'] = $category_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['top'])) {
            $data['top'] = $this->request->post['top'];
        } elseif (!empty($category_info)) {
            $data['top'] = $category_info['top'];
        } else {
            $data['top'] = 0;
        }

        if (isset($this->request->post['column'])) {
            $data['column'] = $this->request->post['column'];
        } elseif (!empty($category_info)) {
            $data['column'] = $category_info['column'];
        } else {
            $data['column'] = 1;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($category_info)) {
            $data['sort_order'] = $category_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($category_info)) {
            $data['status'] = $category_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['category_layout'])) {
            $data['category_layout'] = $this->request->post['category_layout'];
        } elseif (isset($this->request->get['category_id'])) {
            $data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->get['category_id']);
        } else {
            $data['category_layout'] = array();
        }
        
        if (isset($this->request->get['category_id'])) { 
            $data['menu_name_override'] = '1';
        }

        $data['category_id'] = isset($this->request->get['category_id']) ? $this->request->get['category_id'] : 0;

        // Preview link
        foreach ($data['languages'] as $language) {
            $data['preview'][$language['language_id']] = $this->getSeoLink($data['category_id'], $language['code']);
        }
            
        $this->load->model('appearance/layout');

        $data['layouts'] = $this->model_appearance_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/category_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['category_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        $this->load->model('catalog/url_alias');

        foreach ($this->request->post['seo_url'] as $language_id => $value) {
            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($value, $language_id);

            if ($url_alias_info && isset($this->request->get['category_id']) && $url_alias_info['query'] != 'category_id=' . $this->request->get['category_id']) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }

            if ($url_alias_info && !isset($this->request->get['category_id'])) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateRepair() {
        if (!$this->user->hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/category');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'sort'        => 'name',
                'order'       => 'ASC',
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_catalog_category->getCategories($filter_data);

            foreach ($results as $result) {
                $result['index'] = $result['name'];

                if(strpos($result['index'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                    $result['index'] = explode ('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['index']);
                    $result['index'] = end($result['index']);
                }
                
                $json[] = array(
                    'category_id' => $result['category_id'],
                    'index'       => $result['index'],
                    'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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
        $this->load->language('catalog/category');

        $json = array();

        $this->load->model('catalog/category');
        $this->load->model('catalog/url_alias');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateQuick()) {
            $this->trigger->fire('pre.admin.category.quick', array(&$this->request->post));

            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            if ($this->request->post['name']) {
                foreach ($languages as $language) {
                    $this->request->post['category_description'][$language['language_id']]['name'] = $this->request->post['name'];
                    $this->request->post['category_description'][$language['language_id']]['description'] = '';
                    $this->request->post['category_description'][$language['language_id']]['meta_description'] = '';
                    $this->request->post['category_description'][$language['language_id']]['meta_keyword'] = '';
                    
                    $this->request->post['seo_url'][$language['language_id']] = '';
                }
            }

            $this->request->post['top'] = 1;

            $this->request->post['category_store'][] = 0;

            $this->load->model('setting/store');

            $stores = $this->model_setting_store->getStores();

            if ($stores) {
                foreach ($stores as $store) {
                    $this->request->post['category_store'][] = $store['store_id'];
                }
            }

            $category_id = $this->model_catalog_category->addCategory($this->request->post);          

            $this->trigger->fire('post.admin.category.quick', array($category_id));

            $json['success'] = $this->language->get('text_success');
            $json['category_id'] = $category_id;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateQuick() 
    {
        if (!$this->user->hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->trigger->fire('post.admin.category.validate.quick', array(&$this->error));

        return !$this->error;
    }

    public function inline() {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateInline()) {
            $this->load->model('catalog/category');

            if (isset($this->request->post['seo_url'])) {
                $this->load->model('catalog/url_alias');

                $this->model_catalog_url_alias->addAlias('category', $this->request->get['category_id'], $this->request->post['seo_url'], $this->request->post['language_id']);
                $json['language_id'] = $this->request->post['language_id'];
            } else {
                foreach ($this->request->post as $key => $value) {
                    $this->model_catalog_category->updateCategory($this->request->get['category_id'], $key, $value);
                }
            }
        }        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateInline() {
        if (!$this->user->hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['name']) && !isset($this->request->post['status']) && !isset($this->request->post['seo_url'])) {
            $this->error['warning'] = $this->language->get('error_inline_field');
        }

        return !$this->error;
    }

    public function getSeoLink($category_id, $language_code) {
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

        $url .= ltrim($route->rewrite('index.php?route=product/category&path='.$category_id), '/');
        
        if (!empty($old_session_code)) {
            $this->session->data['language'] = $old_session_code;
        }
        
        $this->config->set('config_language', $old_config_code);

        return $url;
    }
}
