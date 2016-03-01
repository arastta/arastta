<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCheckoutAddress extends Controller
{

    public function index()
    {
        $this->load->language('checkout/checkout');

        $data = $this->language->all();

        $this->load->model('account/address');

        $data['addresses'] = $this->model_account_address->getAddresses();

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        // Custom Fields
        $this->load->model('account/custom_field');

        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        $data = $this->getData($data);

        $data['shipping_required'] = $this->cart->hasShipping();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/address.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/address.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/address.tpl', $data));
        }
    }

    public function save()
    {
        $this->load->language('checkout/checkout');

        $json = array();

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
            $this->session->data['comment'] = strip_tags($this->request->post['comment']);

            if (!empty($this->request->post['same_address']) or !$this->cart->hasShipping()) {
                if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
                    $this->load->model('account/address');

                    if (empty($this->request->post['payment_address_id'])) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    } elseif (!in_array($this->request->post['payment_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }

                    if (!$json) {
                        // Default Shipping Address
                        $this->load->model('account/address');

                        $address = $this->model_account_address->getAddress($this->request->post['payment_address_id']);

                        // Set payment address
                        $this->session->data['payment_address'] = $address;

                        unset($this->session->data['payment_method']);
                        unset($this->session->data['payment_methods']);

                        // Set shipping address
                        $this->session->data['shipping_address'] = $address;

                        unset($this->session->data['shipping_method']);
                        unset($this->session->data['shipping_methods']);

                        $this->load->controller('checkout/shipping_method');
                        $this->load->controller('checkout/shipping_method/save');
                    }
                } else {
                    $json = $this->validateFields('payment');

                    if (!$json) {
                        $this->saveAddress('same');
                    }
                }
            } else {
                if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
                    $this->load->model('account/address');

                    if (empty($this->request->post['payment_address_id'])) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    } elseif (!in_array($this->request->post['payment_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }

                    if (!$json) {
                        // Default Payment Address
                        $this->load->model('account/address');

                        $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['payment_address_id']);

                        unset($this->session->data['payment_method']);
                        unset($this->session->data['payment_methods']);
                    }
                } else {
                    $json_payment = $this->validateFields('payment');

                    if (!$json_payment) {
                        $this->saveAddress('payment');
                    }
                }

                if (isset($this->request->post['shipping_address']) && $this->request->post['shipping_address'] == 'existing') {
                    $this->load->model('account/address');

                    if (empty($this->request->post['shipping_address_id'])) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    } elseif (!in_array($this->request->post['shipping_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }

                    if (!$json) {
                        // Default Shipping Address
                        $this->load->model('account/address');

                        $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->post['shipping_address_id']);

                        unset($this->session->data['shipping_method']);
                        unset($this->session->data['shipping_methods']);

                        $this->load->controller('checkout/shipping_method');
                        $this->load->controller('checkout/shipping_method/save');
                    }
                } else {
                    $json_shipping = $this->validateFields('shipping');

                    if (!$json_shipping) {
                        $this->saveAddress('shipping');
                    }
                }

                if ($json_payment && $json_shipping) {
                    $json['error'] = array_merge($json_payment['error'], $json_shipping['error']);
                } elseif ($json_payment) {
                    $json['error'] = $json_payment['error'];
                } elseif ($json_shipping) {
                    $json['error'] = $json_shipping['error'];
                }

            }

            if (!$json and $this->cart->hasShipping()) {
                $shipping_address = $this->session->data['shipping_address'];

                if ($shipping_address['address_format']) {
                    $format = $shipping_address['address_format'];
                } else {
                    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
                }

                $find = array(
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{zone_code}',
                    '{country}'
                );

                $replace = array(
                    'firstname' => $shipping_address['firstname'],
                    'lastname'  => $shipping_address['lastname'],
                    'company'   => $shipping_address['company'],
                    'address_1' => $shipping_address['address_1'],
                    'address_2' => $shipping_address['address_2'],
                    'city'      => $shipping_address['city'],
                    'postcode'  => $shipping_address['postcode'],
                    'zone'      => $shipping_address['zone'],
                    'zone_code' => $shipping_address['zone_code'],
                    'country'   => $shipping_address['country']
                );

                $address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

                $json['address'] = $address;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveAddress($src)
    {
        $prefix = ($src == 'same') ? 'payment_' : $src.'_';

        // Get the post data related with the $src (payment or shipping)
        $data = array();
        foreach ($this->request->post as $name => $value) {
            if (!strstr($name, $prefix)) {
                continue;
            }

            $org_name = str_replace($prefix, '', $name);

            $data[$org_name] = $value;
        }

        // Save and get the address
        if ($this->customer->isLogged()) {
            $this->load->model('account/address');

            $address_id = $this->model_account_address->addAddress($data);

            $address = $this->model_account_address->getAddress($address_id);
        } else {
            $this->load->model('localisation/country');
            $this->load->model('localisation/zone');

            if (!empty($data['country_id'])) {
                $country = $this->model_localisation_country->getCountry($data['country_id']);

                $data['country'] = $country['name'];
                $data['iso_code_2'] = $country['iso_code_2'];
                $data['iso_code_3'] = $country['iso_code_3'];
                $data['address_format'] = $country['address_format'];
            } else {
                $data['country'] = '';
                $data['iso_code_2'] = '';
                $data['iso_code_3'] = '';
                $data['address_format'] = '';
            }

            if (!empty($data['zone_id'])) {
                $zone = $this->model_localisation_zone->getZone($data['zone_id']);

                $data['zone'] = $zone['name'];
                $data['zone_code'] = $zone['code'];
            } else {
                $data['zone'] = '';
                $data['zone_code'] = '';
            }

            $address = $data;
        }

        // Set payment address
        if (($src == 'same') or ($src == 'payment')) {
            $this->session->data['payment_address'] = $address;

            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
        }

        // Set shipping address
        if (($src == 'same') or ($src == 'shipping')) {
            $this->session->data['shipping_address'] = $address;

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);

            $this->load->controller('checkout/shipping_method');
            $this->load->controller('checkout/shipping_method/save');
        }

        $this->load->model('account/activity');

        $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
        );

        $this->model_account_activity->addActivity('address_add', $activity_data);
    }

    public function validateFields($prefix)
    {
        $json = array();

        if ((utf8_strlen(trim($this->request->post[$prefix.'_firstname'])) < 1) || (utf8_strlen(trim($this->request->post[$prefix.'_firstname'])) > 32)) {
            $json['error'][$prefix.'_firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post[$prefix.'_lastname'])) < 1) || (utf8_strlen(trim($this->request->post[$prefix.'_lastname'])) > 32)) {
            $json['error'][$prefix.'_lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen(trim($this->request->post[$prefix.'_address_1'])) < 3) || (utf8_strlen(trim($this->request->post[$prefix.'_address_1'])) > 128)) {
            $json['error'][$prefix.'_address_1'] = $this->language->get('error_address_1');
        }

        if ((utf8_strlen(trim($this->request->post[$prefix.'_city'])) < 2) || (utf8_strlen(trim($this->request->post[$prefix.'_city'])) > 128)) {
            $json['error'][$prefix.'_city'] = $this->language->get('error_city');
        }

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->post[$prefix.'_country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post[$prefix.'_postcode'])) < 2 || utf8_strlen(trim($this->request->post[$prefix.'_postcode'])) > 10)) {
            $json['error'][$prefix.'_postcode'] = $this->language->get('error_postcode');
        }

        if ($this->request->post[$prefix.'_country_id'] == '') {
            $json['error'][$prefix.'_country'] = $this->language->get('error_country');
        }

        if (!isset($this->request->post[$prefix.'_zone_id']) || $this->request->post[$prefix.'_zone_id'] == '') {
            $json['error'][$prefix.'_zone'] = $this->language->get('error_zone');
        }

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($this->request->post[$prefix.'_custom_field'][$custom_field['custom_field_id']])) {
                $json['error'][$prefix.'_custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        return $json;
    }

    public function getData($data)
    {
        // Payment Address
        if ($this->customer->isLogged()) {
            if (isset($this->session->data['payment_address']['address_id'])) {
                $data['payment_address_id'] = $this->session->data['payment_address']['address_id'];
            } else {
                $data['payment_address_id'] = $this->customer->getAddressId();
            }
        }

        if (isset($this->session->data['payment_address']['firstname'])) {
            $data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
        } else {
            $data['payment_firstname'] = '';
        }

        if (isset($this->session->data['payment_address']['lastname'])) {
            $data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
        } else {
            $data['payment_lastname'] = '';
        }

        if (isset($this->session->data['payment_address']['company'])) {
            $data['payment_company'] = $this->session->data['payment_address']['company'];
        } else {
            $data['payment_company'] = '';
        }

        if (isset($this->session->data['payment_address']['address_1'])) {
            $data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
        } else {
            $data['payment_address_1'] = '';
        }

        if (isset($this->session->data['payment_address']['address_2'])) {
            $data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
        } else {
            $data['payment_address_2'] = '';
        }

        if (isset($this->session->data['payment_address']['postcode'])) {
            $data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
        } else {
            $data['payment_postcode'] = '';
        }

        if (isset($this->session->data['payment_address']['city'])) {
            $data['payment_city'] = $this->session->data['payment_address']['city'];
        } else {
            $data['payment_city'] = '';
        }

        if (isset($this->session->data['payment_address']['country_id'])) {
            $data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
        } else {
            $data['payment_country_id'] = $this->config->get('config_country_id');
        }

        if (isset($this->session->data['payment_address']['zone_id'])) {
            $data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
        } else {
            $data['payment_zone_id'] = $this->config->get('config_zone_id');
        }

        if (isset($this->session->data['payment_address']['custom_field'])) {
            $data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];
        } else {
            $data['payment_address_custom_field'] = array();
        }

        // Shipping Address
        if ($this->customer->isLogged()) {
            if (isset($this->session->data['shipping_address']['address_id'])) {
                $data['shipping_address_id'] = $this->session->data['shipping_address']['address_id'];
            } else {
                $data['shipping_address_id'] = $this->customer->getAddressId();
            }
        }

        if (isset($this->session->data['shipping_address']['firstname'])) {
            $data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
        } else {
            $data['shipping_firstname'] = '';
        }

        if (isset($this->session->data['shipping_address']['lastname'])) {
            $data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
        } else {
            $data['shipping_lastname'] = '';
        }

        if (isset($this->session->data['shipping_address']['company'])) {
            $data['shipping_company'] = $this->session->data['shipping_address']['company'];
        } else {
            $data['shipping_company'] = '';
        }

        if (isset($this->session->data['shipping_address']['address_1'])) {
            $data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
        } else {
            $data['shipping_address_1'] = '';
        }

        if (isset($this->session->data['shipping_address']['address_2'])) {
            $data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
        } else {
            $data['shipping_address_2'] = '';
        }

        if (isset($this->session->data['shipping_address']['postcode'])) {
            $data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
        } else {
            $data['shipping_postcode'] = '';
        }

        if (isset($this->session->data['shipping_address']['city'])) {
            $data['shipping_city'] = $this->session->data['shipping_address']['city'];
        } else {
            $data['shipping_city'] = '';
        }

        if (isset($this->session->data['shipping_address']['country_id'])) {
            $data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
        } else {
            $data['shipping_country_id'] = $this->config->get('config_country_id');
        }

        if (isset($this->session->data['shipping_address']['zone_id'])) {
            $data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
        } else {
            $data['shipping_zone_id'] = $this->config->get('config_zone_id');
        }

        if (isset($this->session->data['shipping_address']['custom_field'])) {
            $data['shipping_address_custom_field'] = $this->session->data['shipping_address']['custom_field'];
        } else {
            $data['shipping_address_custom_field'] = array();
        }

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        return $data;
    }
}
