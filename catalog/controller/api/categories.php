<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerApiCategories extends Controller
{

    public function getCategory($args = array())
    {
        $this->load->language('api/categories');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/categories');

            $category = $this->model_api_categories->getCategory($args);

            $category['name'] = html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8');
            $category['description'] = html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8');

            if ($this->request->server['HTTPS']) {
                $category['image'] = str_replace($this->config->get('config_ssl'), '', $category['image']);
            } else {
                $category['image'] = str_replace($this->config->get('config_url'), '', $category['image']);
            }

            $json = $category;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function getCategories($args = array())
    {
        $this->load->language('api/categories');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/categories');

            $category_data = array();

            $results = $this->model_api_categories->getCategories($args);

            if (!empty($results)) {
                foreach ($results as $result) {
                    $result['name'] = html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8');
                    $result['description'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');

                    if ($this->request->server['HTTPS']) {
                        $result['image'] = str_replace($this->config->get('config_ssl'), '', $result['image']);
                    } else {
                        $result['image'] = str_replace($this->config->get('config_url'), '', $result['image']);
                    }

                    $category_data[] = $result;
                }
            }

            $json = $category_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = array())
    {
        $this->load->language('api/categories');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/categories');

            $json = $this->model_api_categories->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($args = array())
    {
        $vars = array();
        $vars['category'] = $args['id'];

        $this->load->controller('api/products/getproducts', $vars);
    }
}
