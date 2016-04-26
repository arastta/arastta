<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerUserUser extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        $this->getList();
    }

    public function add() {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $user_id = $this->model_user_user->addUser($this->request->post);

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
                $this->response->redirect($this->url->link('user/user/edit', 'user_id='.$user_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('user/user/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }            
            
            $this->response->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('user/user/edit', 'user_id='.$this->request->get['user_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('user/user/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
            
            $this->response->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $user_id) {
                $this->model_user_user->deleteUser($user_id);
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

            $this->response->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_user_group'])) {
            $filter_user_group = $this->request->get['filter_user_group'];
        } else {
            $filter_user_group = null;
        }

        if (isset($this->request->get['filter_firstname'])) {
            $filter_firstname = $this->request->get['filter_firstname'];
        } else {
            $filter_firstname = null;
        }

        if (isset($this->request->get['filter_lastname'])) {
            $filter_lastname = $this->request->get['filter_lastname'];
        } else {
            $filter_lastname = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }
        
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'firstname';
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
        
        if (isset($this->request->get['filter_user_group'])) {
            $url .= '&filter_user_group=' . urlencode(html_entity_decode($this->request->get['filter_user_group'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_firstname'])) {
            $url .= '&filter_firstname=' . urlencode(html_entity_decode($this->request->get['filter_firstname'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_lastname'])) {
            $url .= '&filter_lastname=' . urlencode(html_entity_decode($this->request->get['filter_lastname'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

        $data['add'] = $this->url->link('user/user/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('user/user/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['users'] = array();

        $filter_data = array(
            'filter_user_group' => $filter_user_group,
            'filter_firstname' => $filter_firstname,
            'filter_lastname' => $filter_lastname,
            'filter_email' => $filter_email,
            'filter_status' => $filter_status,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        if (!empty($filter_user_name) || !empty($filter_user_group) || !empty($filter_firstname) || !empty($filter_lastname) || !empty($filter_email) || !empty($filter_status)) {
            $user_total = $this->model_user_user->getTotalUsersFilter($filter_data);
        } else {
            $user_total = $this->model_user_user->getTotalUsers();
        }

        $this->load->model('user/user_group');
        
        $results = $this->model_user_user->getUsers($filter_data);

        foreach ($results as $result) {
            $group = $this->model_user_user_group->getUserGroup($result['user_group_id']);

            $data['users'][] = array(
                'user_id'    => $result['user_id'],
                'firstname'  => $result['firstname'],
                'lastname'   => $result['lastname'],
                'email'      => $result['email'],
                'user_group' => $group['name'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'edit'       => $this->url->link('user/user/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
            );
        }

        $data = $this->language->all($data);

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

        $data['sort_firstname'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=firstname' . $url, 'SSL');
        $data['sort_lastname'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=lastname' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=email' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $user_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($user_total - $this->config->get('config_limit_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $user_total, ceil($user_total / $this->config->get('config_limit_admin')));

        $data['filter_firstname'] = $filter_firstname;
        $data['filter_lastname'] = $filter_lastname;
        $data['filter_email'] = $filter_email;
        $data['filter_user_group'] = $filter_user_group;
        $data['filter_status'] = $filter_status;        
        
        $data['sort'] = $sort;
        $data['order'] = $order;
        
        $data ['token'] = $this->session->data['token'];    

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_list.tpl', $data));
    }

    protected function getForm() {
        $data = $this->language->all();

        $data['text_form'] = !isset($this->request->get['user_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['twofactorauth'])) {
            $data['error_twofactorauth'] = $this->error['twofactorauth'];
        } else {
            $data['error_twofactorauth'] = '';
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

        if (!isset($this->request->get['user_id'])) {
            $data['action'] = $this->url->link('user/user/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('user/user/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $user_info = $this->model_user_user->getUser($this->request->get['user_id']);
        }

        if (isset($this->request->post['user_group_id'])) {
            $data['user_group_id'] = $this->request->post['user_group_id'];
        } elseif (!empty($user_info)) {
            $data['user_group_id'] = $user_info['user_group_id'];
        } else {
            $data['user_group_id'] = '';
        }

        $this->load->model('user/user_group');

        $data['user_groups'] = $this->model_user_user_group->getUserGroups();

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($user_info)) {
            $data['firstname'] = $user_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($user_info)) {
            $data['lastname'] = $user_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($user_info)) {
            $data['email'] = $user_info['email'];
        } else {
            $data['email'] = '';
        }
        
        // Params
        if (!empty($user_info)) {
            $params = json_decode($user_info['params'], true);
        }

        if (isset($this->request->post['params']) && isset($this->request->post['params']['theme'])) {
            $data['use_theme'] = $this->request->post['params']['theme'];
        } elseif (!empty($user_info)) {
            $data['use_theme'] = $params['theme'];
        } else {
            $data['use_theme'] = $this->config->get('config_admin_template');
        }

        // Themes
        $data['themes'][] = array(
            'theme' => 'advanced',
            'text'    => $this->language->get('text_theme_advanced')
        );

        $themes = glob(DIR_ADMIN . 'view/theme/*', GLOB_ONLYDIR);
        
        foreach ($themes as $theme) {
            $data['themes'][] = array(
                'theme' => basename($theme),
                'text'  => $this->language->get('text_theme_' . basename($theme))
            );
        }
        
        if (isset($this->request->post['params']) && isset($this->request->post['params']['basic_mode_message'])) {
            $data['basic_mode_message'] = $this->request->post['params']['basic_mode_message'];
        } elseif (!empty($user_info)) {
            $data['basic_mode_message'] = $params['basic_mode_message'];
        } else {
            $data['basic_mode_message'] = $this->config->get('config_admin_theme');
        }

        // Language option
        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['params']) && isset($this->request->post['params']['language'])) {
            $data['use_language'] = $this->request->post['params']['language'];
        } elseif (!empty($user_info)) {
            $data['use_language'] = $params['language'];
        } else {
            $data['use_language'] = $this->config->get('config_admin_language');
        }

        // Editor option
        $this->load->model('extension/editor');

        $data['editors'] = $this->model_extension_editor->getEditors();
        if (isset($this->request->post['params']) && isset($this->request->post['params']['editor'])) {
            $data['use_editor'] = $this->request->post['params']['editor'];
        } elseif (!empty($user_info)) {
            $data['use_editor'] = $params['editor'];
        } else {
            $data['use_editor'] = $this->config->get('config_text_editor');
        }

        // 2FA option
        $this->load->model('extension/extension');

        $twofactorauths = $this->model_extension_extension->getEnabledExtensions(array('filter_type' => 'twofactorauth'));

        if (!empty($twofactorauths)) {
            $data['twofactorauths'] = array();

            foreach ($twofactorauths as $twofactorauth) {
                $tfa = array();

                $this->language->load('twofactorauth/' . $twofactorauth['code']);

                $tfa['extension_id'] = $twofactorauth['extension_id'];
                $tfa['code'] = $twofactorauth['code'];
                $tfa['text'] = $this->language->get('heading_title');
                $tfa['info'] = $twofactorauth['info'];
                $tfa['params'] = $twofactorauth['params'];

                $data['twofactorauths'][] = $tfa;
            }

            if (!empty($params['twofactorauth'])) {
                $data['use_twofactorauth'] = $params['twofactorauth']['method'];
            } elseif (!empty($this->request->post['params']['twofactorauth']['method'])) {
                $data['use_twofactorauth'] = $this->request->post['params']['twofactorauth']['method'];
            } else {
                $data['use_twofactorauth'] = 'none';
            }
        } else {
            $data['twofactorauths'] = '';
            $data['use_twofactorauth'] = 'none';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($user_info)) {
            $data['image'] = $user_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($user_info) && $user_info['image'] && is_file(DIR_IMAGE . $user_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($user_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($user_info)) {
            $data['status'] = $user_info['status'];
        } else {
            $data['status'] = 0;
        }

        $data['token'] = $this->session->data['token'];
        $data['user_id'] = isset($this->request->get['user_id']) ?: 0;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'user/user')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (utf8_strlen($this->request->post['email']) < 3) {
            $this->error['email'] = $this->language->get('error_email_short');
        }

        $user_info = $this->model_user_user->getUserByEmail($this->request->post['email']);

        if (!isset($this->request->get['user_id'])) {
            if ($user_info) {
                $this->error['warning'] = $this->language->get('error_email_exists');
            }
        } else {
            if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_email_exists');
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if (($this->request->post['params']['twofactorauth']['method'] != 'none') && !$this->validateTwofactorauth()) {
            $this->error['twofactorauth'] = $this->language->get('error_twofactorauth');
        }

        return !$this->error;
    }

    public function validateTwofactorauth()
    {
        $method = $this->request->post['params']['twofactorauth']['method'];

        // Check if already set up
        $this->load->model('user/user');
        $user_info = $this->model_user_user->getUser($this->request->get['user_id']);

        $params = json_decode($user_info['params'], true);

        if (isset($params['twofactorauth']) && ($params['twofactorauth']['method'] == $method)) {
            return true;
        }

        // Validate new set up
        $this->trigger->addFolder('twofactorauth');

        $valid = $this->trigger->fire('pre.admin.twofactorauth.validate', array(&$method));

        return $valid;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'user/user')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['selected'] as $user_id) {
            if ($this->user->getId() == $user_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        return !$this->error;
    }
    
    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_user_name']) || isset($this->request->get['filter_user_group']) || isset($this->request->get['filter_firstname']) || isset($this->request->get['filter_lastname']) || isset($this->request->get['filter_email'])) {
            $this->load->model('user/user');

            if (isset($this->request->get['filter_user_name'])) {
                $filter_user_name = $this->request->get['filter_user_name'];
            } else {
                $filter_user_name = '';
            }

            if (isset($this->request->get['filter_user_group'])) {
                $filter_user_group = $this->request->get['filter_user_group'];
                if(empty($filter_user_group)){
                    $filter_user_group = '*';
                }
            } else {
                $filter_user_group = '';
            }
            
            if (isset($this->request->get['filter_firstname'])) {
                $filter_firstname = $this->request->get['filter_firstname'];
            } else {
                $filter_firstname = '';
            }
            
            if (isset($this->request->get['filter_lastname'])) {
                $filter_lastname = $this->request->get['filter_lastname'];
            } else {
                $filter_lastname = '';
            }
            
            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_user_name'  => $filter_user_name,
                'filter_user_group' => $filter_user_group,
                'filter_firstname' => $filter_firstname,
                'filter_lastname' => $filter_lastname,
                'filter_email' => $filter_email,
                'start'        => 0,
                'limit'        => $limit
            );

            if(empty($filter_user_group)) {
                $results = $this->model_user_user->getUsers($filter_data);
            } else {
                $this->load->model('user/user_group');

                $_results = $this->model_user_user_group->getUserGroups($filter_data);
            }
            
            if(!empty ($results)) {
                foreach ($results as $result) {
                    $json[] = array(
                            'user_id'    => $result['user_id'],
                            'username'   => $result['username'],
                            'firstname'  => $result['firstname'],
                            'lastname'     => $result['lastname'],
                            'email'         => $result['email']
                        );
                }
            } else if (!empty ($_results)){
                foreach ($_results as $result) {
                    $json[] = array(
                            'user_group_id' => $result['user_group_id'],
                            'user_group' => $result['name']
                        );
                }
            }                
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }    
    
    public function hide()
    {
        $json = array();

        if (isset($this->request->get['basic_mode_message'])) {
            $this->load->model('user/user');

            $data['basic_mode_message'] = $this->request->get['basic_mode_message'];

            $user_id = !empty($this->request->get['user_id']) ? $this->request->get['user_id'] : $this->user->getId();

            $this->model_user_user->editUserParams($user_id, $data);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function twofactorauth()
    {
        $method = $this->request->get['method'];

        if ($method == 'none') {
            $this->response->setOutput('');
            return;
        }

        $this->language->load('twofactorauth/' . $method);

        $data = $this->language->all();

        $this->load->model('user/user');
        $user_info = $this->model_user_user->getUser($this->request->get['user_id']);

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['email'] = $user_info['email'];

        // Get extra data from extensions
        $this->trigger->addFolder('twofactorauth');
        $this->trigger->fire('pre.admin.twofactorauth.display', array(&$method, &$data, &$user_info));

        $data['token'] = $this->session->data['token'];
        $data['user_id'] = isset($this->request->get['user_id']) ?: 0;

        $this->response->setOutput($this->load->view('twofactorauth/' . $method . '_user.tpl', $data));
    }
}
