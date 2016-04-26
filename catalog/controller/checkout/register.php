<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCheckoutRegister extends Controller {
    public function index() {
        $this->load->language('checkout/checkout');

        $data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_your_address'] = $this->language->get('text_your_address');
        $data['text_your_password'] = $this->language->get('text_your_password');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_shipping'] = $this->language->get('entry_shipping');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_upload'] = $this->language->get('button_upload');

        $data['customer_groups'] = array();

        if (is_array($this->config->get('config_customer_group_display'))) {
            $this->load->model('account/customer_group');

            $customer_groups = $this->model_account_customer_group->getCustomerGroups();

            foreach ($customer_groups  as $customer_group) {
                if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                    $data['customer_groups'][] = $customer_group;
                }
            }
        }

        if (isset($this->session->data['customer']['customer_group_id'])) {
            $data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
        } else {
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        if (isset($this->session->data['customer']['customer_group_id'])) {
            $data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
        } else {
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        if (isset($this->session->data['customer']['firstname'])) {
            $data['firstname'] = $this->session->data['customer']['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->session->data['customer']['lastname'])) {
            $data['lastname'] = $this->session->data['customer']['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->session->data['customer']['email'])) {
            $data['email'] = $this->session->data['customer']['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->session->data['customer']['telephone'])) {
            $data['telephone'] = $this->session->data['customer']['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->session->data['customer']['fax'])) {
            $data['fax'] = $this->session->data['customer']['fax'];
        } else {
            $data['fax'] = '';
        }

        // Custom Fields
        $this->load->model('account/custom_field');

        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

        if ($this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        $data['shipping_required'] = $this->cart->hasShipping();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/register.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/register.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/register.tpl', $data));
        }
    }

    public function save() {
        $this->load->language('checkout/checkout');

        $json = array();

        // Validate if customer is already logged out.
        if ($this->customer->isLogged()) {
            //$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            $this->customer->logout();
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }
        }

        if (!$json) {
            $this->load->model('account/customer');

            if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
                $json['error']['firstname'] = $this->language->get('error_firstname');
            }

            if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
                $json['error']['lastname'] = $this->language->get('error_lastname');
            }

            if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
                $json['error']['warning'] = $this->language->get('error_exists');
            }

            if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
                $json['error']['telephone'] = $this->language->get('error_telephone');
            }

            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $json['error']['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['confirm'] != $this->request->post['password']) {
                $json['error']['confirm'] = $this->language->get('error_confirm');
            }

            if ($this->config->get('config_account_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }

            // Customer Group
            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            // Custom field validation
            $this->load->model('account/custom_field');

            $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

            foreach ($custom_fields as $custom_field) {
                if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
                    $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }
        }

        if (!$json) {
            $customer_id = $this->model_account_customer->addCustomer($this->request->post);
            
            // Clear any previous login attempts for unregistered accounts.
            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

            $this->session->data['account'] = 'register';

            $this->load->model('account/customer_group');

            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

            if ($customer_group_info && !$customer_group_info['approval']) {
                $this->customer->login($this->request->post['email'], $this->request->post['password']);
            } else {
                $json['redirect'] = $this->url->link('account/success');
            }

            unset($this->session->data['guest']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $customer_id,
                'name'        => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
            );

            $this->model_account_activity->addActivity('register', $activity_data);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
