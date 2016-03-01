<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSystemLanguageoverride extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->model('system/language_override');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->saveList();
        }

        $this->getList();
    }

    public function saveList()
    {
        $this->load->model('system/language_override');

        if (isset($this->request->post['lstrings']) && $this->validateForm()) {
            $this->model_system_language_override->saveStrings($this->request->post['lstrings']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_text'])) {
                $url .= '&filter_text=' . urlencode(html_entity_decode($this->request->get['filter_text']));
            }

            if (isset($this->request->get['filter_client'])) {
                $url .= '&filter_client=' . $this->request->get['filter_client'];
            }

            if (isset($this->request->get['filter_language'])) {
                $url .= '&filter_language=' . $this->request->get['filter_language'];
            }

            if (isset($this->request->get['filter_folder'])) {
                $url .= '&filter_folder=' . urlencode(html_entity_decode($this->request->get['filter_folder'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_path'])) {
                $url .= '&filter_path=' . urlencode(html_entity_decode($this->request->get['filter_path'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('system/language_override', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        $data = $this->language->all();

        if (isset($this->request->get['filter_text'])) {
            $filter_text = $this->request->get['filter_text'];
        } else {
            $filter_text = null;
        }

        if (isset($this->request->get['filter_client'])) {
            $filter_client = $this->request->get['filter_client'];
        } else {
            $filter_client = 'admin';
        }

        if (isset($this->request->get['filter_language'])) {
            $filter_language = $this->request->get['filter_language'];
        } else {
            $filter_language = null;
        }

        if (isset($this->request->get['filter_folder'])) {
            $filter_folder = $this->request->get['filter_folder'];
        } else {
            $filter_folder = null;
        }

        if (isset($this->request->get['filter_path'])) {
            $filter_path = $this->request->get['filter_path'];
        } else {
            $filter_path = null;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
        
        if (isset($this->request->get['filter_text'])) {
            $url .= '&filter_text=' . urlencode(html_entity_decode($this->request->get['filter_text'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_client'])) {
            $url .= '&filter_client=' . $this->request->get['filter_client'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
        }

        if (isset($this->request->get['filter_folder'])) {
            $url .= '&filter_folder=' . urlencode(html_entity_decode($this->request->get['filter_folder'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_path'])) {
            $url .= '&filter_path=' . urlencode(html_entity_decode($this->request->get['filter_path'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['action'] = $this->url->link('system/language_override/savelist', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $filter_data = array(
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'filter_text' => $filter_text,
            'filter_client' => $filter_client,
            'filter_language' => $filter_language,
            'filter_folder' => $filter_folder,
            'filter_path' => $filter_path,
            'limit' => $this->config->get('config_limit_admin')
        );

        $data['languages'] = $this->model_system_language_override->getLanguages($filter_data);

        list($data['files'], $total) = $this->model_system_language_override->getStrings($filter_data);

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

        $url = '';

        if (isset($this->request->get['filter_text'])) {
            $url .= '&filter_text=' . urlencode(html_entity_decode($this->request->get['filter_text'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_client'])) {
            $url .= '&filter_client=' . $this->request->get['filter_client'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
        }

        if (isset($this->request->get['filter_folder'])) {
            $url .= '&filter_folder=' . urlencode(html_entity_decode($this->request->get['filter_folder'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_path'])) {
            $url .= '&filter_path=' . urlencode(html_entity_decode($this->request->get['filter_path'], ENT_QUOTES, 'UTF-8'));
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('system/language_override', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['filter_text'] = $filter_text;
        $data['filter_client'] = $filter_client;
        $data['filter_language'] = $filter_language;
        $data['filter_folder'] = $filter_folder;
        $data['filter_path'] = $filter_path;
        $data['token'] = $this->session->data['token'];

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('system/language_override.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'system/language_override')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
