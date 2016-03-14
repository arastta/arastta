<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerUserUserPermission extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        $this->getList();
    }

    public function add() {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $user_group_id = $this->model_user_user_group->addUserGroup($this->request->post);

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
                $this->response->redirect($this->url->link('user/user_permission/edit', 'user_group_id='.$user_group_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('user/user_permission/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }            
            
            $this->response->redirect($this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_user_user_group->editUserGroup($this->request->get['user_group_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('user/user_permission/edit', 'user_group_id='.$this->request->get['user_group_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('user/user_permission/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }        
            
            $this->response->redirect($this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $user_group_id) {
                $this->model_user_user_group->deleteUserGroup($user_group_id);
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

            $this->response->redirect($this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
            'href' => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['add'] = $this->url->link('user/user_permission/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('user/user_permission/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['user_groups'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $user_group_total = $this->model_user_user_group->getTotalUserGroups();

        $results = $this->model_user_user_group->getUserGroups($filter_data);

        foreach ($results as $result) {
            $data['user_groups'][] = array(
                'user_group_id' => $result['user_group_id'],
                'name'          => $result['name'],
                'edit'          => $this->url->link('user/user_permission/edit', 'token=' . $this->session->data['token'] . '&user_group_id=' . $result['user_group_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_confirm_title'] = sprintf($this->language->get('text_confirm_title'), $this->language->get('heading_title'));
        $data['text_bulk_action'] = $this->language->get('text_bulk_action');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

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

        $data['sort_name'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $user_group_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($user_group_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($user_group_total - $this->config->get('config_limit_admin'))) ? $user_group_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $user_group_total, ceil($user_group_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_group_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_access'] = $this->language->get('tab_access');
        $data['tab_modify'] = $this->language->get('tab_modify');

        $data['text_form'] = !isset($this->request->get['user_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_dashboard'] = $this->language->get('entry_dashboard');
        $data['entry_access'] = $this->language->get('entry_access');
        $data['entry_modify'] = $this->language->get('entry_modify');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');        
        $data['button_cancel'] = $this->language->get('button_cancel');

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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['user_group_id'])) {
            $data['action'] = $this->url->link('user/user_permission/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('user/user_permission/edit', 'token=' . $this->session->data['token'] . '&user_group_id=' . $this->request->get['user_group_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['user_group_id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {
            $user_group_info = $this->model_user_user_group->getUserGroup($this->request->get['user_group_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($user_group_info)) {
            $data['name'] = $user_group_info['name'];
        } else {
            $data['name'] = '';
        }

        $ignore = array(
            'common/dashboard',
            'common/startup',
            'common/login',
            'common/logout',
            'common/forgotten',
            'common/reset',
            'error/not_found',
            'error/permission',
            'common/footer',
            'common/header',
            'dashboard/activity',
            'dashboard/chart',
            'dashboard/charts',
            'dashboard/customer',
            'dashboard/map',
            'dashboard/online',
            'dashboard/order',
            'dashboard/recent',                
            'dashboard/recenttabs',            
            'dashboard/sale'
        );

        $data['dashboards'] = array(
            'dashboard/activity'    => $this->getDashboardText('dashboard/activity'),
            'dashboard/charts'      => $this->getDashboardText('dashboard/charts'),
            'dashboard/customer'    => $this->getDashboardText('dashboard/customer'),
            'dashboard/map'         => $this->getDashboardText('dashboard/map'),
            'dashboard/online'      => $this->getDashboardText('dashboard/online'),
            'dashboard/order'       => $this->getDashboardText('dashboard/order'),
            'dashboard/recenttabs'  => $this->getDashboardText('dashboard/recenttabs'),
            'dashboard/sale'        => $this->getDashboardText('dashboard/sale')
        );

        asort($data['dashboards']);

        $data['permissions'] = array();

        $files = glob(DIR_ADMIN . 'controller/*/*.php');

        foreach ($files as $file) {
            $part = explode('/', dirname($file));

            $permission = end($part) . '/' . basename($file, '.php');

            if (!in_array($permission, $ignore)) {
                $folder = $this->getFolderName(end($part), $permission);
                $data['permissions'][$folder][$permission] = $this->getDashboardText($permission);
            }
        }

        ksort($data['permissions']);

        $data['permissions'] = $this->getSortFiles($data['permissions']);

        if (isset($this->request->post['permission']['dashboard'])) {
            $data['dashboard'] = $this->request->post['permission']['dashboard'];
        } elseif (isset($user_group_info['permission']['dashboard'])) {
            $data['dashboard'] = $user_group_info['permission']['dashboard'];
        } else {
            $data['dashboard'] = array();
        }

        if (isset($this->request->post['permission']['access'])) {
            $data['access'] = $this->request->post['permission']['access'];
        } elseif (isset($user_group_info['permission']['access'])) {
            $data['access'] = $user_group_info['permission']['access'];
        } else {
            $data['access'] = array();
        }

        if (isset($this->request->post['permission']['modify'])) {
            $data['modify'] = $this->request->post['permission']['modify'];
        } elseif (isset($user_group_info['permission']['modify'])) {
            $data['modify'] = $user_group_info['permission']['modify'];
        } else {
            $data['modify'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_group_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'user/user_permission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'user/user_permission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('user/user');

        foreach ($this->request->post['selected'] as $user_group_id) {
            $user_total = $this->model_user_user->getTotalUsersByGroupId($user_group_id);

            if ($user_total) {
                $this->error['warning'] = sprintf($this->language->get('error_user'), $user_total);
            }
        }

        return !$this->error;
    }

    protected function getDashboardText($route, $get_text = 'heading_title') {
        $route = $route == 'user/user_permission' ? 'user/user_group' : $route;
        $this->load->language($route);

        $text = $this->language->get('heading_title');

        if(empty($text)) {
            $text = $this->language->get($get_text);
        }

        return $text;
    }

    protected function getFolderName($folder, $permission, $replace = array()) {
        $folders = array(
            'design/banner'         => 'appearance',
            'sale/customer'         => 'customer',
            'sale/customer_group'   => 'customer',
            'sale/custom_field'     => 'customer',
            'sale/customer_ban_ip'  => 'customer'
        );

        $replace = array_merge($replace, $folders);

        if (array_key_exists($permission, $replace)) {
            foreach ($replace as $key => $value) {
                if ($key == $permission) {
                    $folder = $value;
                }
            }
        }

        return $folder;
    }

    protected function getSortFiles($files) {
        $total_file = $count = $i = 0;

        foreach ($files as $key => $file) {
            $file_group[$key] = count($file);
            $total_file      += count($file);
        }

        $slice = ceil($total_file / 4);

        foreach ($file_group as $key => $value) {
            if ($count  < $slice) {
                $file_sort[$i][] = $key;
                $count += $value;
            } else {
                $i++;
                $count = 0;
                $file_sort[$i][] = $key;
                $count += $value;
            }
        }

        foreach ($file_sort as $key => $value) {
            foreach ($value as $folder) {
                $result[$key][$folder] = $files[$folder];
            }
        }

        return $result;
    }
}
