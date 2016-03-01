<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerAffiliateAccount extends Controller {
    public function index() {
        if (!$this->affiliate->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('affiliate/account', '', 'SSL');

            $this->response->redirect($this->url->link('affiliate/login', '', 'SSL'));
        }

        $this->load->language('affiliate/account');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('affiliate/account', '', 'SSL')
        );

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_my_tracking'] = $this->language->get('text_my_tracking');
        $data['text_my_commission'] = $this->language->get('text_my_commission');
        $data['text_my_logout'] = $this->language->get('text_my_logout');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_tracking'] = $this->language->get('text_tracking');
        $data['text_commission'] = $this->language->get('text_commission');
        $data['text_logout'] = $this->language->get('text_logout');    

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['edit'] = $this->url->link('affiliate/edit', '', 'SSL');
        $data['password'] = $this->url->link('affiliate/password', '', 'SSL');
        $data['payment'] = $this->url->link('affiliate/payment', '', 'SSL');
        $data['tracking'] = $this->url->link('affiliate/tracking', '', 'SSL');
        $data['commission'] = $this->url->link('affiliate/commission', '', 'SSL');
        $data['logout'] = $this->url->link('affiliate/logout', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/account.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/affiliate/account.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/affiliate/account.tpl', $data));
        }
    }
}
