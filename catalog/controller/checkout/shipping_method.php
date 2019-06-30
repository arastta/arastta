<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerCheckoutShippingMethod extends Controller {
    public function index() {
        $this->load->language('checkout/checkout');

        if (!empty($this->request->post['address_id'])) {
            $this->load->model('account/address');

            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->post['address_id']);
        }
        else if ($this->customer->isLogged() and !empty($this->request->post['address_type'])) {
            $type = $this->request->post['address_type'];

            if (!empty($this->session->data[$type.'_address_id'])) {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->session->data[$type.'_address_id']);
            }
            else {
                $this->session->data['shipping_address'] = $this->load->controller('checkout/checkout/getAddressFromPost', array($type, false));
            }
        }
        else if ((!empty($this->request->post['address_country_id']) or !empty($this->request->post['address_zone_id'])) and !empty($this->request->post['address_type'])) {
            $type = $this->request->post['address_type'];

            $this->session->data['shipping_address'] = $this->load->controller('checkout/checkout/getAddressFromPost', array($type, true));
        }

        if (isset($this->session->data['shipping_address'])) {
            // Shipping Methods
            $method_data = array();

            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('shipping');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('shipping/' . $result['code']);

                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

                    if ($quote) {
                        $method_data[$result['code']] = array(
                            'title'      => $quote['title'],
                            'description'=> (isset($quote['description']) ? $quote['description'] : ''),
                            'quote'      => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error'      => $quote['error']
                        );
                    }
                }
            }

            $sort_order = array();

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);

            $this->session->data['shipping_methods'] = $method_data;
        }

        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['shipping_methods'])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'])) {
            $data['shipping_methods'] = $this->session->data['shipping_methods'];
        } else {
            $data['shipping_methods'] = array();
        }

        if (isset($this->session->data['shipping_method']['code'])) {
            $data['code'] = $this->session->data['shipping_method']['code'];
        } else {
            $data['code'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping_method.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/shipping_method.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/shipping_method.tpl', $data));
        }
    }

    public function save() {
        $this->load->language('checkout/checkout');

        $json = array();

        // Validate if shipping is required. If not the customer should not have reached this page.
        if (!$this->cart->hasShipping()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate if shipping address has been set.
        if (!isset($this->session->data['shipping_address'])) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers']))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Get products in cart.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            // Validate minimum and maximum quantity requirements.
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

            if ($product['maximum'] != 0 && $product['maximum'] < $product_total) {
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }

            // Validate cart has stock or pre-order
            if ($product['preorder'] || $product['stock'] || $this->config->get('config_stock_checkout')) {
                continue;
            } else if ((!$product['preorder'] || !$product['stock'])) {
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }
        }

        if (!isset($this->request->post['shipping_method'])) {
            $json['error']['warning'] = $this->language->get('error_shipping');
        } else {
            $shipping = explode('.', $this->request->post['shipping_method']);

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            }
        }

        if (!$json) {
            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
