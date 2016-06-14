<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerApiProducts extends Controller
{

    public function getProduct($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('catalog/product');

            $json = $this->model_catalog_product->getProduct($args['id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function getProducts($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $product_data = array();

            $results = $this->model_api_products->getProducts($args);

            if (!empty($results)) {
                $this->load->model('catalog/product');

                foreach ($results as $result) {
                    $product_data[] = $this->model_catalog_product->getProduct($result['product_id']);
                }
            }

            $json = $product_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $json = $this->model_api_products->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
