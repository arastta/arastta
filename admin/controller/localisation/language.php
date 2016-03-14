<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerLocalisationLanguage extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('localisation/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/language');

        $this->getList();
    }

    public function add() {
        $this->load->language('localisation/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/language');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $data = $this->request->post;

            if (empty($data['locale'])) {
                $data['locale'] = '';
            }

            $language_id = $this->model_localisation_language->addLanguage($data);

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
                $this->response->redirect($this->url->link('localisation/language/edit', 'language_id='.$language_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('localisation/language/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }            
            
            $this->response->redirect($this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('localisation/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/language');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $data = $this->request->post;
            $language_id = $this->request->get['language_id'];

            if (empty($data['locale'])) {
                $data['locale'] = '';
            }

            $this->model_localisation_language->editLanguage($language_id, $data);

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
                $this->response->redirect($this->url->link('localisation/language/edit', 'language_id='.$language_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('localisation/language/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }            
            
            $this->response->redirect($this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('localisation/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/language');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $language_id) {
                $this->model_localisation_language->deleteLanguage($language_id);
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

            $this->response->redirect($this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
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
            'href' => $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );        

        $data['add'] = $this->url->link('localisation/language/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('localisation/language/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['upload'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');

        $data['languages'] = array();

        $filter_data = array(
            'status'=> '*',
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $language_total = $this->model_localisation_language->getTotalLanguages($filter_data);

        $results = $this->model_localisation_language->getLanguages($filter_data);

        foreach ($results as $result) {
            $data['languages'][] = array(
                'language_id' => $result['language_id'],
                'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null),
                'code'        => $result['code'],
                'image'       => $result['image'],
                'directory'   => $result['directory'],
                'sort_order'  => $result['sort_order'],
                'status'      => $result['status'],
                'edit'        => $this->url->link('localisation/language/edit', 'token=' . $this->session->data['token'] . '&language_id=' . $result['language_id'] . $url, 'SSL')
            );
        }

        $data = $this->language->all($data);

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } else if (isset($this->error['warning'])) {
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

        $data['sort_name'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_code'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . '&sort=code' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $language_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($language_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($language_total - $this->config->get('config_limit_admin'))) ? $language_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $language_total, ceil($language_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/language_list.tpl', $data));
    }

    protected function getForm() {
        $data = $this->language->all();
        $data['text_form'] = !isset($this->request->get['language_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } else if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }

        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = '';
        }

        if (isset($this->error['directory'])) {
            $data['error_directory'] = $this->error['directory'];
        } else {
            $data['error_directory'] = '';
        }

        if (isset($this->error['filename'])) {
            $data['error_filename'] = $this->error['filename'];
        } else {
            $data['error_filename'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

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

        if (!isset($this->request->get['language_id'])) {
            $data['action'] = $this->url->link('localisation/language/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/language/edit', 'token=' . $this->session->data['token'] . '&language_id=' . $this->request->get['language_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['language_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $language_info = $this->model_localisation_language->getLanguage($this->request->get['language_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($language_info)) {
            $data['name'] = $language_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['code'])) {
            $data['code'] = $this->request->post['code'];
        } elseif (!empty($language_info)) {
            $data['code'] = $language_info['code'];
        } else {
            $data['code'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($language_info)) {
            $data['image'] = $language_info['image'];
        } else {
            $data['image'] = '';
        }

        if (isset($this->request->post['directory'])) {
            $data['directory'] = $this->request->post['directory'];
        } elseif (!empty($language_info)) {
            $data['directory'] = $language_info['directory'];
        } else {
            $data['directory'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($language_info)) {
            $data['sort_order'] = $language_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($language_info)) {
            $data['status'] = $language_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/language_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'localisation/language')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (utf8_strlen($this->request->post['code']) < 2) {
            $this->error['code'] = $this->language->get('error_code');
        }

        if (!$this->request->post['directory']) {
            $this->error['directory'] = $this->language->get('error_directory');
        }

        if ((utf8_strlen($this->request->post['image']) < 3) || (utf8_strlen($this->request->post['image']) > 32)) {
            $this->error['image'] = $this->language->get('error_image');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'localisation/language')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('setting/store');
        $this->load->model('sale/order');

        foreach ($this->request->post['selected'] as $language_id) {
            $language_info = $this->model_localisation_language->getLanguage($language_id);

            if ($language_info) {
                if ($this->config->get('config_language') == $language_info['code']) {
                    $this->error['warning'] = $this->language->get('error_default');
                }

                if ($this->config->get('config_admin_language') == $language_info['code']) {
                    $this->error['warning'] = $this->language->get('error_admin');
                }

                $store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);

                if ($store_total) {
                    $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
                }
            }

            $order_total = $this->model_sale_order->getTotalOrdersByLanguageId($language_id);

            if ($order_total) {
                $this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
            }
        }

        return !$this->error;
    }
}
