<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCheckoutCheckout extends Controller {

    public function index() {
        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
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
                $this->response->redirect($this->url->link('checkout/cart'));
            }
        }

        $this->language->load('checkout/checkout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        // Required by klarna
        if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
            $this->document->addScript('https://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL')
        );

        $data = $this->language->all($data, array('error_agree'));

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $data['error_agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        } else {
            $data['error_agree'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        $data['logged'] = $this->customer->isLogged();

        if (isset($this->session->data['account'])) {
            $data['account'] = $this->session->data['account'];
        } else {
            $data['account'] = '';
        }

        $data['shipping_required'] = $this->cart->hasShipping();

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['config'] = $this->config;

        $data['checkout_guest'] = ($this->config->get('config_checkout_guest') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/checkout.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/checkout.tpl', $data));
        }
    }

    public function country() {
        $json = array();

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
                'country_id'        => $country_info['country_id'],
                'name'              => $country_info['name'],
                'iso_code_2'        => $country_info['iso_code_2'],
                'iso_code_3'        => $country_info['iso_code_3'],
                'address_format'    => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status'            => $country_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function customfield() {
        $json = array();

        $this->load->model('account/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            $json[] = array(
                'custom_field_id' => $custom_field['custom_field_id'],
                'required'        => $custom_field['required']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAddressFromPost($args) {
        $type = $args[0];
        $is_guest = $args[1];

        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');

        $address = array();

        $address['country_id'] = !empty($this->request->post['address_country_id']) ? $this->request->post['address_country_id'] : 0;
        $address['zone_id'] = !empty($this->request->post['address_zone_id']) ? $this->request->post['address_zone_id'] : 0;
        $address['city'] = !empty($this->request->post['address_city']) ? $this->request->post['address_city'] : '';
        $address['postcode'] = !empty($this->request->post['address_postcode']) ? $this->request->post['address_postcode'] : '';

        if (!empty($address['country_id'])) {
            $country = $this->model_localisation_country->getCountry($address['country_id']);

            $address['country'] = $country['name'];
            $address['iso_code_2'] = $country['iso_code_2'];
            $address['iso_code_3'] = $country['iso_code_3'];
            $address['address_format'] = $country['address_format'];
        }
        else {
            $address['country'] = '';
            $address['iso_code_2'] = '';
            $address['iso_code_3'] = '';
            $address['address_format'] = '';
        }

        if (!empty($address['zone_id'])) {
            $zone = $this->model_localisation_zone->getZone($address['zone_id']);

            $address['zone'] = $zone['name'];
            $address['zone_code'] = $zone['code'];
        }
        else {
            $address['zone'] = '';
            $address['zone_code'] = '';
        }

        $this->session->data[$type.'_address']['country_id'] = $address['country_id'];
        $this->session->data[$type.'_address']['zone_id'] = $address['zone_id'];
        $this->session->data[$type.'_address']['city'] = $address['city'];
        $this->session->data[$type.'_address']['postcode'] = $address['postcode'];

        if ($is_guest == true) {
            $this->session->data[$type.'_address']['country'] = $address['country'];
            $this->session->data[$type.'_address']['iso_code_2'] = $address['iso_code_2'];
            $this->session->data[$type.'_address']['iso_code_3'] = $address['iso_code_3'];
            $this->session->data[$type.'_address']['address_format'] = $address['address_format'];
            $this->session->data[$type.'_address']['zone'] = $address['zone'];
            $this->session->data[$type.'_address']['zone_code'] = $address['zone_code'];
        }

        return $address;
    }
}
