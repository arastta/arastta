<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSystemEmailtemplate extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('system/email_template');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('system/email_template');

        $this->getList();
    }

    public function edit()
    {
        $this->load->language('system/email_template');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('system/email_template');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
    
            $this->model_system_email_template->editEmailTemplate($this->request->get['email_template'], $this->request->post['email_template_description']);

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
                 $this->response->redirect($this->url->link('system/email_template/edit', 'email_template='.$this->request->get['email_template'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_text'])) {
            $filter_text = $this->request->get['filter_text'];
        } else {
            $filter_text = null;
        }

        if (isset($this->request->get['filter_context'])) {
            $filter_context = $this->request->get['filter_context'];
        } else {
            $filter_context = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'id';
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
        
        if (isset($this->request->get['filter_text'])) {
            $url .= '&filter_text=' . $this->request->get['filter_text'];
        }

        if (isset($this->request->get['filter_context'])) {
            $url .= '&filter_context=' . $this->request->get['filter_context'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->request->get['filter_type'];
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
            'href' => $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        $data['emailTemplates'] = array();

        $filter_data = array(
            'sort'           => $sort,
            'order'          => $order,
            'start'          => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'          => $this->config->get('config_limit_admin'),
            'filter_text'    => $filter_text,
            'filter_context' => $filter_context,
            'filter_name'    => $filter_name,
            'filter_type'    => $filter_type,
            'filter_status'  => $filter_status
        );

        $email_template_total = $this->model_system_email_template->getTotalEmailTemplates($filter_data);

        $results = $this->model_system_email_template->getEmailTemplates($filter_data);

        foreach ($results as $result) {
            $data['emailTemplates'][] = array(
                'id'         => $result['id'],
                'text'       => $result['text'],
                'type'       => $result['type'],
                'context'    => $result['context'],
                'status'     => $result['status'],
                'edit'       => $this->url->link('system/email_template/edit', 'token=' . $this->session->data['token'] . '&email_template=' . $result['type'] . '_' . $result['text_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        #Text
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        #Column
        $data['column_text'] = $this->language->get('column_text');
        $data['column_type'] = $this->language->get('column_type');
        $data['column_context'] = $this->language->get('column_context');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        #Button
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        #Filter
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_context'] = $this->language->get('entry_context');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_status'] = $this->language->get('entry_status');
        
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

        $data['sort_text'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . '&sort=sort_text' . $url, 'SSL');
        $data['sort_context'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . '&sort=sort_context' . $url, 'SSL');
        $data['sort_type'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . '&sort=sort_type' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $email_template_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();
            
        $data['filter_text']    = $filter_text;
        $data['filter_context'] = $filter_context;
        $data['filter_name']    = $filter_name;
        $data['filter_type']    = $filter_type;
        $data['filter_status']  = $filter_status;

        $data['types'] = $this->getEmailTypes();
        
        $data['token'] = $this->session->data['token'];
        
        $data['results'] = sprintf($this->language->get('text_pagination'), ($email_template_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($email_template_total - $this->config->get('config_limit_admin'))) ? $email_template_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $email_template_total, ceil($email_template_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('system/email_template_list.tpl', $data));
    }
    
    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_form'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_short_codes'] = $this->language->get('text_short_codes');
        $data['text_show_hide'] = $this->language->get('text_show_hide');
        $data['text_html_preview'] = $this->language->get('text_html_preview');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');

        $data['button_show_shortcode'] = $this->language->get('button_show_shortcode');
        $data['button_hide_shortcode'] = $this->language->get('button_hide_shortcode');

        $data['button_save'] = $this->language->get('button_save');
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
            'href' => $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        if (!isset($this->request->get['email_template'])) {
            $this->response->redirect($this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        } else {
            $data['action'] = $this->url->link('system/email_template/edit', 'token=' . $this->session->data['token'] . '&email_template=' . $this->request->get['email_template'] . $url, 'SSL');
        }

        $data['html_preview'] = $this->url->link('system/email_template/html', 'token=' . $this->session->data['token'] . '&email_template=' . $this->request->get['email_template'] . $url, 'SSL');
        $data['cancel'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['email_template']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $email_template_info = $this->model_system_email_template->getEmailTemplate($this->request->get['email_template']);
        }
        
        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['email_template_description'] = $this->request->post['email_template_description']['name'];
        } elseif (!empty($email_template_info)) {
            $data['email_template_description'] = $email_template_info;
        } else {
            $data['email_template_description'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['email_template_description'] = $this->request->post['email_template_description']['description'];
        } elseif (!empty($email_template_info)) {
            $data['email_template_description'] = $email_template_info;
        } else {
            $data['email_template_description'] = '';
        }

        $data['short_codes'] = $this->model_system_email_template->getShortCodes($this->request->get['email_template']);

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('system/email_template_form.tpl', $data));
    }

    public function html()
    {
        $this->load->language('mail/shortcode');

        $this->load->model('system/email_template');

        $message = $this->model_system_email_template->getEmailTemplate($this->request->get['email_template']);

        $codes = $this->model_system_email_template->getShortCodes($this->request->get['email_template']);

        foreach ($codes as $code) {
            $find[] = $code['code'];
            $replace[] = $this->language->get('demo_' . preg_replace('~\{([\w]*)\}~', '$1', $code['code']));
        }

        preg_match('/{comment:start}(.*){comment:stop}/Uis', $message[$this->request->get['language_id']]['description'], $template_comment);

        if (sizeof($template_comment) > 0) {
            $message[$this->request->get['language_id']]['description'] = str_replace($template_comment[1], '', $message[$this->request->get['language_id']]['description']);
        }

        if (!empty($template_comment)) {
            $message[$this->request->get['language_id']]['description'] = str_replace('{comment:start}{comment:stop}', $template_comment[1], $message[$this->request->get['language_id']]['description']);
        }

        // Products
        preg_match('/{product:start}(.*){product:stop}/Uis', $message[$this->request->get['language_id']]['description'], $template_product);

        if (sizeof($template_product) > 0) {
            $message[$this->request->get['language_id']]['description'] = str_replace($template_product[1], '', $message[$this->request->get['language_id']]['description']);
        }

        $find_products = $this->emailtemplate->getProductFind();

        foreach ($find_products as $product_code) {
            $find_product[] = $product_code;
            $replace_product[] = $this->language->get('demo_' . preg_replace('~\{([\w]*)\}~', '$1', $product_code));
        }

        if (!empty($template_product)) {
            $template_product = trim(str_replace($find_product, $replace_product, $template_product[1]));
        }

        // Total
        preg_match('/{total:start}(.*){total:stop}/Uis', $message[$this->request->get['language_id']]['description'], $template_total);

        if (sizeof($template_total) > 0) {
            $message[$this->request->get['language_id']]['description'] = str_replace($template_total[1], '', $message[$this->request->get['language_id']]['description']);
        }

        $find_totals = $this->emailtemplate->getTotalFind();

        foreach ($find_totals as $total_code) {
            $find_total[] = $total_code;
            $replace_total[] = $this->language->get('demo_' . preg_replace('~\{([\w]*)\}~', '$1', $total_code));
        }

        if (!empty($template_total)) {
            $template_total[1] = trim(str_replace($find_total, $replace_total, $template_total[1]));
        }

        if (!empty($template_total)) {
            $message[$this->request->get['language_id']]['description'] = str_replace('{total:start}{total:stop}', $template_total[1], $message[$this->request->get['language_id']]['description']);
        }

        $message = trim(str_replace($find, $replace, $message[$this->request->get['language_id']]['description']));

        if (!empty($template_product)) {
            $message = str_replace('demo_{product:start}demo_{product:stop}', $template_product, $message);
        }
        $data['message'] = html_entity_decode($message, ENT_QUOTES, 'UTF-8');

        $this->response->setOutput($this->load->view('system/email_template_html.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'system/email_template')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        return !$this->error;
    }
    
    protected function getEmailTypes()
    {
        $result = array ( 'Order Status', 'Customer', 'Affiliate', 'Contact', 'Reviews', 'Cron', 'Mail' );
    
        for ($i = 0; $i <= 6; $i++) {
            $types[] = array(
                'id'    => $i+1,
                'value'     => $result[$i]
            );
        }
        return $types;
    }
    
    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('system/email_template');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_system_email_template->getEmailTemplates($filter_data);

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
}
