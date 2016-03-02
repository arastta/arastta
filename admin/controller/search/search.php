<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSearchSearch extends Controller
{
    public function index()
    {
        if (empty($this->session->data['token'])) {
            return;
        }
    
        $this->load->language('search/search');

        $data = array();

        $data = $this->language->all();

        $data['search_link'] = $this->url->link('search/search/search', 'token=' . $this->session->data['token'], 'SSL');
        
        return $this->load->view('search/search.tpl', $data);
    }

    public function search()
    {
        $this->load->language('search/search');
        
        $data = $this->language->all();

        $data['token'] = $this->session->data['token'];

        if (!empty($this->request->get['query'])) {
            $_data['query'] = $this->request->get['query'];
        } else {
            $json['error'] = $this->language->get('text_empty_query');
        }

        if (!empty($this->request->get['search-option'])) {
            $search_option = $this->request->get['search-option'];
        } else {
            $search_option = 'catalog';
        }

        if (!empty($json['error'])) {
            $this->response->setOutput(json_encode($json));
            return;
        }

        $this->load->model('tool/image');
        $data['no_image'] = $this->model_tool_image->resize('no_image.png', 30, 30);

        $this->load->model('search/search');

        switch ($search_option) {
            case 'catalog':
                // Get products
                $data['products'] = $this->model_search_search->getProducts($_data);

                foreach ($data['products'] as $key => $product) {
                    if (!empty($product['image'])) {
                        $data['products'][$key]['image'] = $this->model_tool_image->resize($product['image'], 30, 30);
                    } else {
                        $data['products'][$key]['image'] = $this->model_tool_image->resize('no_image.png', 30, 30);
                    }

                    $data['products'][$key]['url'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');
                }

                // Get categories
                $data['categories'] = $this->model_search_search->getCategories($_data);

                foreach ($data['categories'] as $key => $category) {
                    if (!empty($category['image'])) {
                        $data['categories'][$key]['image'] = $this->model_tool_image->resize($category['image'], 30, 30);
                    } else {
                        $data['categories'][$key]['image'] = $this->model_tool_image->resize('no_image.png', 30, 30);
                    }

                    $data['categories'][$key]['url'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $category['category_id'], 'SSL');
                }

                // Get manufacturers
                $data['manufacturers'] = $this->model_search_search->getManufacturers($_data);

                foreach ($data['manufacturers'] as $key => $manufacturer) {
                    if (!empty($category['image'])) {
                        $data['manufacturers'][$key]['image'] = $this->model_tool_image->resize($manufacturer['image'], 30, 30);
                    } else {
                        $data['manufacturers'][$key]['image'] = $this->model_tool_image->resize('no_image.png', 30, 30);
                    }

                    $data['manufacturers'][$key]['url'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $manufacturer['manufacturer_id'], 'SSL');
                }

                $json['result'] = $this->load->view('search/catalog_result.tpl', $data);

                break;
            case 'customers':
                $data['customers'] = $this->model_search_search->getCustomers($_data);

                foreach ($data['customers'] as $key => $customer) {
                    $data['customers'][$key]['url'] = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer['customer_id'], 'SSL');
                }

                $json['result'] = $this->load->view('search/customers_result.tpl', $data);
                break;
            case 'orders':
                $data['orders'] = $this->model_search_search->getOrders($_data);

                foreach ($data['orders'] as $key => $order) {
                    $data['orders'][$key]['url'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order['order_id'], 'SSL');
                }

                $json['result'] = $this->load->view('search/orders_result.tpl', $data);
                break;
            default:
                break;
        }

        $this->response->setOutput(json_encode($json));
    }
}
