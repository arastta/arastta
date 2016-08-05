<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerApiStats extends Controller
{

    public function getStats($args = array())
    {
        $this->load->language('api/stats');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('api/products');
            $this->load->model('api/customers');

            $data = array();

            $orders = $this->model_api_orders->getTotals($args);
            $orders['nice_price'] = $this->currency->format($orders['price']);

            $products = $this->model_api_products->getTotals($args);

            $customers = $this->model_api_customers->getTotals($args);

            if (!empty($args['date_from']) && !empty($args['date_to'])) {
                $this->load->model('api/stats');

                $orders['daily'] = $this->model_api_stats->getDailyOrders($args);
                $products['daily'] = $this->model_api_stats->getDailyProducts($args);
                $customers['daily'] = $this->model_api_stats->getDailyCustomers($args);
            }

            $data['orders'] = $orders;
            $data['products'] = $products;
            $data['customers'] = $customers;

            $json = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrders($args = array())
    {
        $this->load->controller('api/orders/gettotals', $args);
    }

    public function getCustomers($args = array())
    {
        $this->load->controller('api/customers/gettotals', $args);
    }

    public function getProducts($args = array())
    {
        $this->load->controller('api/products/gettotals', $args);
    }
}
