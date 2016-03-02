<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerDashboardRecenttabs extends Controller
{
    public function index()
    {
        $this->load->language('dashboard/recenttabs');

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['text_products_and_sales'] = $this->language->get('text_products_and_sales');
        $data['text_best_sellers'] = $this->language->get('text_best_sellers');
        $data['text_less_sellers'] = $this->language->get('text_less_sellers');
        $data['text_most_viewed'] = $this->language->get('text_most_viewed');
        $data['text_last_order'] = $this->language->get('text_last_order');
        
        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_product_id'] = $this->language->get('column_product_id');

        $data['button_view'] = $this->language->get('button_view');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['button_view'] = $this->language->get('button_view');

        $data['token'] = $this->session->data['token'];
        
        
        $this->load->model('dashboard/recenttabs');

        // 5 best seller product
        $results = $this->model_dashboard_recenttabs->getBestSellers();
        $bestseller = array();
        foreach ($results as $product) {
            $bestseller[] = array(
                'product_id'   => $product['product_id'],
                'name'   => $product['name'],
                'total'   => $product['total'],
                'edit'   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id='.$product['product_id'], 'SSL')
            );
        }

        $data['bestseller'] = $bestseller;

        // // 5 less seller product
        $results = $this->model_dashboard_recenttabs->getLessSellers();
        $lessseller = array();
        foreach ($results as $product) {
            $lessseller[] = array(
                'product_id'   => $product['product_id'],
                'name'   => $product['name'],
                'total'   => $product['total'],
                'edit'   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id='.$product['product_id'], 'SSL')
            );
        }

        $data['lessseller'] = $lessseller;

        // 5 most viewed product
        $results = $this->model_dashboard_recenttabs->getMostViewed();
        $viewed = array();
        foreach ($results as $product) {
            $viewed[] = array(
                'product_id'   => $product['product_id'],
                'name'   => $product['name'],
                'total'   => $product['viewed'],
                'edit'   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id='.$product['product_id'], 'SSL')
            );
        }

        $data['viewed'] = $viewed;

        // Last 5 Orders
        $filter_data = array(
            'sort'  => 'o.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10
        );

        $this->load->model('sale/order');
        $results = $this->model_sale_order->getOrders($filter_data);
        $data['orders'] = array();
        foreach ($results as $result) {
            $data['orders'][] = array(
                'order_id'   => $result['order_id'],
                'customer'   => $result['customer'],
                'status'     => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'view'       => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
            );
        }

        return $this->load->view('dashboard/recenttabs.tpl', $data);
    }
}
