<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerModuleLogin extends Controller
{
    public function index()
    {
        $this->load->language('module/login');

        if ($data['logged'] = $this->customer->isLogged()) {
            $data['text_greeting'] = sprintf($this->language->get('text_logged'), $this->customer->getFirstName());
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['entry_email_address'] = $this->language->get('entry_email_address');
        $data['entry_password'] = $this->language->get('entry_password');
        
        $data['button_login'] = $this->language->get('button_login');
        $data['button_logout'] = $this->language->get('button_logout');
        $data['button_create'] = $this->language->get('button_create');
        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_my_orders'] = $this->language->get('text_my_orders');
        $data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
        $data['text_information'] = $this->language->get('text_information');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_history'] = $this->language->get('text_history');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_create'] = $this->language->get('text_create');
        $data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['text_welcome'] = $this->language->get('text_welcome');

        $data['edit'] = $this->url->link('account/edit', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['action'] = $this->url->link('account/login');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/login.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/login.tpl', $data);
        } else {
            return $this->load->view('default/template/module/login.tpl', $data);
        }
    }
}
