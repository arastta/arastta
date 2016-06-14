<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerApiOrders extends Controller
{

    public function getOrder($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            $order = $this->model_checkout_order->getOrder($args['id']);

            $order['nice_total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value']);

            $json = $order;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOrder($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            $json['order_id'] = $this->model_checkout_order->addOrder($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editOrder($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            $this->model_checkout_order->editOrder($args['id'], $args);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function deleteOrder($args = array())
    {
        $this->load->language('api/order');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            $order_info = $this->model_checkout_order->getOrder($args['id']);

            if ($order_info) {
                $this->model_checkout_order->deleteOrder($args['id']);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrders($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $order_data = array();

            $results = $this->model_api_orders->getOrders($args);

            if (!empty($results)) {
                $this->load->model('checkout/order');

                foreach ($results as $result) {
                    $order = $this->model_checkout_order->getOrder($result['order_id']);

                    $order['nice_total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value']);

                    $order_data[] = $order;
                }
            }

            $json = $order_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = array())
    {
        $this->load->language('api/orders');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $total = $this->model_api_orders->getTotals($args);

            $total['nice_price'] = $this->currency->format($total['price']);

            $json = $total;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($args = array())
    {
        $this->load->language('api/orders');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $rows = $this->model_api_orders->getOrderProducts($args);

            $order_products = array();

            if ($rows) {
                $this->load->model('tool/image');
                $this->load->model('tool/upload');
                $this->load->model('account/order');

                foreach ($rows as $row) {
                    $currency_value = false;

                    if (isset($data['currency_code'])) {
                        $currency_code = $data['currency_code'];
                    } elseif (isset($row['currency_code'])) {
                        $currency_code = $row['currency_code'];
                        $currency_value = $row['currency_value'];
                    } else {
                        $currency_code = $this->config->get('config_currency');
                    }

                    $row['nice_price'] = $this->currency->format($row['price'], $currency_code, $currency_value);
                    $row['quantity'] = intval($row['quantity']);

                    $order_product_options = $this->model_account_order->getOrderOptions($data['id'], $row['order_product_id']);

                    $option_data = array();
                    foreach ($order_product_options as $option) {
                        if ($option['type'] != 'file') {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                            if ($upload_info) {
                                $value = $option['value'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = array($option['name'] => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 26) . '..' : $value));
                    }

                    $row['options'] = $option_data;

                    if (!$data['skip_images']) {
                        $thumb_width = $this->config->get('config_image_thumb_width', 300);
                        $thumb_height = $this->config->get('config_image_thumb_height', 300);

                        if (isset($row['image']) && strlen($row['image']) > 0) {
                            $row['image'] = $this->model_tool_image->resize($row['image'], $thumb_width, $thumb_height);
                        } else {
                            $row['image'] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);
                        }
                    }

                    $order_products[] = $row;
                }
            }

            $json = $order_products;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getHistories($args = array())
    {
        $this->load->language('api/orders');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('account/order');

            $json = $this->model_account_order->getOrderHistories($args['id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addHistory($args = array())
    {
        $this->load->language('api/orders');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            // Add keys for missing post vars
            $keys = array(
                'notify',
                'append',
                'comment'
            );

            foreach ($keys as $key) {
                if (!isset($args[$key])) {
                    $args[$key] = '';
                }
            }

            $args['order_status_id'] = $args['status'];

            $order_info = $this->model_checkout_order->getOrder($args['id']);

            if ($order_info) {
                $this->model_checkout_order->addOrderHistory($args['id'], $args['order_status_id'], $args['comment'], $args['notify']);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
