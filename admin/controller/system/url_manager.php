<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSystemUrlmanager extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('system/url_manager');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();
    }

    public function add()
    {
        $this->load->language('system/url_manager');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('system/url_manager');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $url_alias_id = $this->model_system_url_manager->addAlias($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_seo_url'])) {
                $url .= '&filter_seo_url=' . urlencode(html_entity_decode($this->request->get['filter_seo_url'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_query'])) {
                $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_type'])) {
                $url .= '&filter_type=' . $this->request->get['filter_type'];
            }

            if (isset($this->request->get['filter_language'])) {
                $url .= '&filter_language=' . $this->request->get['filter_language'];
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
                $this->response->redirect($this->url->link('system/url_manager/edit', 'url_alias_id=' . $url_alias_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('system/url_manager/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('system/url_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('system/url_manager');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('system/url_manager');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_system_url_manager->editAlias($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_seo_url'])) {
                $url .= '&filter_seo_url=' . urlencode(html_entity_decode($this->request->get['filter_seo_url'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_query'])) {
                $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_type'])) {
                $url .= '&filter_type=' . $this->request->get['filter_type'];
            }

            if (isset($this->request->get['filter_language'])) {
                $url .= '&filter_language=' . $this->request->get['filter_language'];
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
                $this->response->redirect($this->url->link('system/url_manager/edit', 'url_alias_id=' . $this->request->get['url_alias_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('system/url_manager/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('system/url_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('system/url_manager');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('system/url_manager');

        if (isset($this->request->post['selected']) && $this->validate()) {
            foreach ($this->request->post['selected'] as $alias_id) {
                $this->model_system_url_manager->deleteAlias($alias_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_seo_url'])) {
                $url .= '&filter_seo_url=' . urlencode(html_entity_decode($this->request->get['filter_seo_url'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_query'])) {
                $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_type'])) {
                $url .= '&filter_type=' . $this->request->get['filter_type'];
            }

            if (isset($this->request->get['filter_language'])) {
                $url .= '&filter_language=' . $this->request->get['filter_language'];
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

            $this->response->redirect($this->url->link('system/url_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        $this->load->model('system/url_manager');
        $this->load->model('localisation/language');

        $data = $this->language->all();

        if (isset($this->request->get['filter_seo_url'])) {
            $filter_seo_url = $this->request->get['filter_seo_url'];
        } else {
            $filter_seo_url = null;
        }

        if (isset($this->request->get['filter_query'])) {
            $filter_query = $this->request->get['filter_query'];
        } else {
            $filter_query = null;
        }

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = '';
        }

        if (isset($this->request->get['filter_language'])) {
            $filter_language = $this->request->get['filter_language'];
        } else {
            $filter_language = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'keyword';
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

        if (isset($this->request->get['filter_seo_url'])) {
            $url .= '&filter_seo_url=' . urlencode(html_entity_decode($this->request->get['filter_seo_url'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_query'])) {
            $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->request->get['filter_type'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
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

        $data['add']    = $this->url->link('system/url_manager/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('system/url_manager/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $filter_data = array(
            'filter_seo_url'  => $filter_seo_url,
            'filter_query'    => $filter_query,
            'filter_type'     => $filter_type,
            'filter_language' => $filter_language,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        if (!empty($filter_seo_url) || !empty($filter_query) || !empty($filter_type) || !empty($filter_language)) {
            $total = $this->model_system_url_manager->getTotalAliases($filter_data);
        } else {
            $total = $this->model_system_url_manager->getTotalAliases();
        }

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['aliases'] = array();

        $results = $this->model_system_url_manager->getAliases($filter_data);

        foreach ($results as $result) {
            $query = str_replace('category_id', 'path', $result['query']);

            $type = $data['text_other'];

            if (strstr($result['query'], 'product_id=')) {
                $type = $data['text_product'];
            } elseif (strstr($result['query'], 'category_id=')) {
                $type = $data['text_category'];
            } elseif (strstr($result['query'], 'manufacturer_id=')) {
                $type = $data['text_manufacturer'];
            } elseif (strstr($result['query'], 'information_id=')) {
                $type = $data['text_information'];
            }

            $language_name  = '';
            $language_image = '';

            foreach ($data['languages'] as $language) {
                if ($language['language_id'] != $result['language_id']) {
                    continue;
                }

                $language_name  = $language['name'];
                $language_image = $language['image'];
            }

            $data['aliases'][] = array(
                'url_alias_id'   => $result['url_alias_id'],
                'query'          => $query,
                'keyword'        => $result['keyword'],
                'language_id'    => $result['language_id'],
                'language_name'  => $language_name,
                'language_image' => $language_image,
                'type'           => $type,
                'edit'           => $this->url->link('system/url_manager/edit', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $result['url_alias_id'] . $url, 'SSL')
            );
        }


        $data['types'] = array(
            array('value' => 'product', 'name' => $data['text_product']),
            array('value' => 'category', 'name' => $data['text_category']),
            array('value' => 'manufacturer', 'name' => $data['text_manufacturer']),
            array('value' => 'information', 'name' => $data['text_information']),
            array('value' => 'other', 'name' => $data['text_other'])
        );

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

        if (isset($this->request->get['filter_seo_url'])) {
            $url .= '&filter_seo_url=' . urlencode(html_entity_decode($this->request->get['filter_seo_url'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_query'])) {
            $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->request->get['filter_type'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
        }

        $pagination        = new Pagination();
        $pagination->total = $total;
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url   = $this->url->link('system/url_manager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['filter_seo_url']  = $filter_seo_url;
        $data['filter_query']    = $filter_query;
        $data['filter_type']     = $filter_type;
        $data['filter_language'] = $filter_language;
        $data['token']           = $this->session->data['token'];

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('system/url_manager_list.tpl', $data));
    }

    protected function getForm()
    {
        $data = $this->language->all();

        $data['text_form'] = !isset($this->request->get['url_alias_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = array();
        }

        if (isset($this->error['query'])) {
            $data['error_query'] = $this->error['query'];
        } else {
            $data['error_query'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_seo_url'])) {
            $url .= '&filter_seo_url=' . urlencode(html_entity_decode($this->request->get['filter_seo_url'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_query'])) {
            $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->request->get['filter_type'];
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->request->get['filter_language'];
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

        if (!isset($this->request->get['url_alias_id'])) {
            $data['action'] = $this->url->link('system/url_manager/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('system/url_manager/edit', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $this->request->get['url_alias_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('system/url_manager', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['alias'])) {
            $data['alias'] = $this->request->post['alias'];
            $data['query'] = $this->request->post['query'];
        } elseif (isset($this->request->get['url_alias_id'])) {
            $alias = $this->model_system_url_manager->getAliasById($this->request->get['url_alias_id']);

            // Get other languages
            // TO DO: create url_alias_description table
            if (count($data['languages']) > 1) {
                $aliases = $this->model_system_url_manager->getAliasesByQuery($alias['query']);

                foreach ($aliases as $alias) {
                    $data['alias'][$alias['language_id']]['seo_url']      = $alias['keyword'];
                    $data['alias'][$alias['language_id']]['url_alias_id'] = $alias['url_alias_id'];
                }
            } else {
                $data['alias'][$alias['language_id']]['seo_url']      = $alias['keyword'];
                $data['alias'][$alias['language_id']]['url_alias_id'] = $alias['url_alias_id'];
            }

            $data['query'] = $alias['query'];
        } else {
            $data['alias'] = array();
            $data['query'] = '';
        }

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('system/url_manager_form.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'system/url_manager')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'system/url_manager')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['alias'] as $language_id => $value) {
            if ((utf8_strlen($value['seo_url']) < 3) || (utf8_strlen($value['seo_url']) > 64)) {
                $this->error['seo_url'][$language_id] = $this->language->get('error_seo_url');
            }
        }

        if (!strstr($this->request->post['query'], '=')) {
            $this->error['query'] = $this->language->get('error_query');
        }

        return !$this->error;
    }

    public function inline()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateInline()) {
            $this->load->model('system/url_manager');

            foreach ($this->request->post as $key => $value) {
                $this->model_system_url_manager->updateAlias($this->request->get['url_alias_id'], $key, $value);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateInline()
    {
        if (!$this->user->hasPermission('modify', 'system/url_manager')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['keyword'])) {
            $this->error['warning'] = $this->language->get('error_inline_field');
        }

        return !$this->error;
    }
}
