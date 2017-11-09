<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerBlogCategory extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('blog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/category');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('blog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $category_id = $this->model_blog_category->addCategory($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

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

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('blog/category/edit', 'category_id=' . $category_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('blog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('blog/category', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('blog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_blog_category->editCategory($this->request->get['category_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

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

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('blog/category/edit', 'category_id=' . $this->request->get['category_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('blog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('blog/category', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('blog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/category');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $category_id) {
                $this->model_blog_category->deleteCategory($category_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->url->link('blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function repair()
    {
        $this->load->language('blog/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/category');

        if ($this->validateRepair()) {
            $this->model_blog_category->repairCategories();

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

            $this->response->redirect($this->url->link('blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
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

        #Get All Language Text
        $data = $this->language->all();

        $data['add']    = $this->url->link('blog/category/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('blog/category/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['repair'] = $this->url->link('blog/category/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['categories'] = array();

        $filter_data = array(
            'filter_name'   => $filter_name,
            'filter_status' => $filter_status,
            'sort'          => $sort,
            'order'         => $order,
            'start'         => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'         => $this->config->get('config_limit_admin')
        );

        $category_total = $this->model_blog_category->getTotalCategories($filter_data);

        $results = $this->model_blog_category->getCategories($filter_data);

        foreach ($results as $result) {
            $data['categories'][] = array(
                'category_id' => $result['category_id'],
                'parent_id'   => $result['parent_id'],
                'name'        => $result['name'],
                'sort_order'  => $result['sort_order'],
                'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit'        => $this->url->link('blog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL'),
                'delete'      => $this->url->link('blog/category/delete', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL')
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
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name']       = $this->url->link('blog/category', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_status']     = $this->url->link('blog/category', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('blog/category', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

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

        if (isset($this->request->get['sortable'])) {
            $url .= '&sortable=' . $this->request->get['sortable'];
        }

        $pagination        = new Pagination();
        $pagination->total = $category_total;
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url   = $this->url->link('blog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['filter_name']   = $filter_name;
        $data['filter_status'] = $filter_status;

        $data['token']         = $this->session->data['token'];

        $data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

        $data['sort']  = $sort;
        $data['order'] = $order;

        $data['sortable'] = (isset($this->request->get['sortable']) && $this->request->get['sortable'] == 'active') ? true : false;

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/category_list.tpl', $data));
    }

    protected function getForm()
    {
        #Get All Language Text
        $data = $this->language->all();

        $data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

        if (!isset($this->request->get['category_id'])) {
            $data['action'] = $this->url->link('blog/category/add', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('blog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id']);
        }

        $data['cancel'] = $this->url->link('blog/category', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $category_info = $this->model_blog_category->getCategory($this->request->get['category_id']);
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $categories = $this->model_blog_category->getCategories();

        if (isset($category_info)) {
            foreach ($categories as $key => $category) {
                if ($category['category_id'] == $category_info['category_id']) {
                    unset($categories[$key]);
                }
            }
        }

        $data['categories'] = $categories;

        if (isset($this->request->post['category_description'])) {
            $data['category_description'] = $this->request->post['category_description'];
        } elseif (isset($category_info)) {
            $data['category_description'] = $this->model_blog_category->getCategoryDescriptions($this->request->get['category_id']);
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
        } elseif (isset($category_info)) {
            $data['parent_id'] = $category_info['parent_id'];
        } else {
            $data['parent_id'] = 0;
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['category_store'])) {
            $data['category_store'] = $this->request->post['category_store'];
        } elseif (isset($category_info)) {
            $data['category_store'] = $this->model_blog_category->getCategoryStores($this->request->get['category_id']);
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

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (isset($category_info)) {
            $data['sort_order'] = $category_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($category_info)) {
            $data['status'] = $category_info['status'];
        } else {
            $data['status'] = 1;
        }

        if (isset($this->request->post['category_layout'])) {
            $data['category_layout'] = $this->request->post['category_layout'];
        } elseif (isset($category_info)) {
            $data['category_layout'] = $this->model_blog_category->getCategoryLayouts($this->request->get['category_id']);
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

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/category_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'blog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['category_description'] as $language_id => $value) {
            if ((strlen(utf8_decode($value['name'])) < 2) || (strlen(utf8_decode($value['name'])) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        $this->load->model('catalog/url_alias');

        foreach ($this->request->post['seo_url'] as $language_id => $value) {
            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($value, $language_id);

            if ($url_alias_info && isset($this->request->get['category_id']) && $url_alias_info['query'] != 'blog_category_id=' . $this->request->get['category_id']) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }

            if ($url_alias_info && !isset($this->request->get['category_id'])) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'blog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateRepair()
    {
        if (!$this->user->hasPermission('modify', 'blog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('blog/category');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'sort'        => 'name',
                'order'       => 'ASC',
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_blog_category->getCategories($filter_data);

            foreach ($results as $result) {
                $result['index'] = $result['name'];

                if (strpos($result['index'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                    $result['index'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['index']);
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
        $this->load->language('blog/category');

        $json = array();

        $this->load->model('blog/category');
        $this->load->model('catalog/url_alias');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateQuick()) {
            $this->trigger->fire('pre.admin.blog.category.quick', array(&$this->request->post));

            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            if ($this->request->post['name']) {
                foreach ($languages as $language) {
                    $this->request->post['category_description'][$language['language_id']]['name']             = $this->request->post['name'];
                    $this->request->post['category_description'][$language['language_id']]['description']      = '';
                    $this->request->post['category_description'][$language['language_id']]['meta_description'] = '';
                    $this->request->post['category_description'][$language['language_id']]['meta_keyword']     = '';

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

            $category_id = $this->model_blog_category->addCategory($this->request->post);

            $this->trigger->fire('post.admin.blog.category.quick', array($category_id));

            $json['success']     = $this->language->get('text_success');
            $json['category_id'] = $category_id;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateQuick()
    {
        if (!$this->user->hasPermission('modify', 'blog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->trigger->fire('post.admin.blog.category.validate.quick', array(&$this->error));

        return !$this->error;
    }

    public function inline()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateInline()) {
            $this->load->model('blog/category');

            if (isset($this->request->post['seo_url'])) {
                $this->load->model('catalog/url_alias');

                $this->model_catalog_url_alias->clearAliases('blog_category', $this->request->get['category_id'], $this->request->post['language_id']);

                $this->model_catalog_url_alias->addAlias('blog_category', $this->request->get['category_id'], $this->request->post['seo_url'], $this->request->post['language_id']);

                $json['language_id'] = $this->request->post['language_id'];
            } else {
                foreach ($this->request->post as $key => $value) {
                    $this->model_blog_category->updateCategory($this->request->get['category_id'], $key, $value);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateInline()
    {
        if (!$this->user->hasPermission('modify', 'blog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['name']) && !isset($this->request->post['status']) && !isset($this->request->post['seo_url'])) {
            $this->error['warning'] = $this->language->get('error_inline_field');
        }

        return !$this->error;
    }

    public function getSeoLink($category_id, $language_code)
    {
        $old_session_code = isset($this->session->data['language']) ? $this->session->data['language'] : '';
        $old_config_code  = $this->config->get('config_language');

        $this->session->data['language'] = $language_code;
        $this->config->set('config_language', $language_code);

        $url = $this->config->get('config_url');

        if (empty($url)) {
            $url = HTTP_SERVER;

            $admin_folder = str_replace(DIR_ROOT, '', DIR_ADMIN);

            $url = str_replace($admin_folder, '', $url);
        }

        $route = new Route($this->registry);

        $url .= ltrim($route->rewrite('index.php?route=blog/category&path=' . $category_id), '/');

        if (!empty($old_session_code)) {
            $this->session->data['language'] = $old_session_code;
        }

        $this->config->set('config_language', $old_config_code);

        return $url;
    }
}
