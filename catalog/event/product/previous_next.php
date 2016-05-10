<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventProductPreviousNext extends Event
{
    public function preProductDisplay(&$data, $type)
    {
        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        if ($type != 'product' || !$product_id) {
            return;
        }

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string)$this->request->get['path']);

            $category = array_pop($parts);
            $category_id = (int)$category;
        } else {
            $categories = $this->model_catalog_product->getCategories($product_id);

            if (!$categories) {
                return;
            }

            $category = array_shift($categories);
            $category_id = (int)$category['category_id'];
        }

        $filter_data = array(
            'filter_category_id' => $category_id,
            'sort'               => 'pd.name',
            'order'              => 'ASC',
            'start'              => 0,
            'limit'              => 1000
        );

        $results = $this->model_catalog_product->getProducts($filter_data);

        $product = false;

        $previous_product = $next_product = null;

        foreach ($results as $result) {
            if ($result['name'] == $product_info['name']) {
                $product = true;
            } elseif (!$product) {
                $previous_product = $result;
            } else {
                $next_product = $result;
                break;
            }
        }

        $data['next'] = array();
        $data['previous'] = array();

        if ($next_product) {
            $data['next'] = array(
                'name'  => $next_product['name'],
                'image' => $this->model_tool_image->resize($next_product['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                'price' => ($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price') ? $this->currency->format($this->tax->calculate($next_product['price'], $next_product['tax_class_id'], $this->config->get('config_tax'))) : false,
                'href'  => $this->url->link('product/product', 'product_id=' .  $next_product['product_id'])
            );
        }

        if ($previous_product) {
            $data['previous'] = array(
                'name'  => $previous_product['name'],
                'image' => $this->model_tool_image->resize($previous_product['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                'price' => ($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price') ? $this->currency->format($this->tax->calculate($previous_product['price'], $previous_product['tax_class_id'], $this->config->get('config_tax'))) : false,
                'href'  => $this->url->link('product/product', 'product_id=' . $previous_product['product_id'])
            );
        }
    }
}
