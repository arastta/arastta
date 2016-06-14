<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerApiCustomers extends Controller
{
    
    public function getCustomer($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->getCustomer($args['id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCustomer($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->addCustomer($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editCustomer($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->editCustomer($args['id'], $args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteCustomer($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $this->model_api_customers->deleteCustomer($args['id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCustomers($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->getCustomers($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAddresses($args = array())
    {
        $this->load->language('api/customers');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->getAddresses($args['id']);
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
            $this->load->model('api/customers');

            $customer_data = array();

            $results = $this->model_api_customers->getOrders($args['id'], $args);

            if (!empty($results)) {
                $this->load->model('checkout/order');

                foreach ($results as $result) {
                    $result['nice_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);

                    $customer_data[] = $result;
                }
            }

            $json = $customer_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
