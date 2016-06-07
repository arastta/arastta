<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonSearch extends Controller {
    public function index() {
        $this->load->language('common/search');

        $data['text_search'] = $this->language->get('text_search');

        if (isset($this->request->get['search'])) {
            $data['search'] = $this->request->get['search'];
        } else {
            $data['search'] = '';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/search.tpl', $data);
        } else {
            return $this->load->view('default/template/common/search.tpl', $data);
        }
    }

    public function liveSearch()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->language('product/product');
            $this->load->model('catalog/product');
            $this->load->model('tool/image');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $filter_data = array(
                'filter_name' => $filter_name,
            );

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
                } else {
                    $tax = false;
                }

                $image = $image = $this->model_tool_image->resize($result['image'], '45', '45');

                if ($price && $tax) {
                    $image = $this->model_tool_image->resize($result['image'], '70', '70');
                } elseif ($price) {
                    $image = $this->model_tool_image->resize($result['image'], '55', '55');
                }

                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'href'       => $this->url->link('product/product', '&product_id=' . $result['product_id']),
                    'searchall'  => $this->url->link('product/search', '&search=' . $filter_name),
                    'price'      => $price,
                    'special'    => $special,
                    'tax'        => ($tax) ? $this->language->get('text_tax') . ' ' . $tax : false,
                    'image'      => $image
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
