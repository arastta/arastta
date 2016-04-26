<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerDashboardSale extends Controller {
    public function index() {
        $this->load->language('dashboard/sale');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('report/sale');

        $today = $this->model_report_sale->getTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-1 day'))));

        $yesterday = $this->model_report_sale->getTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-2 day'))));

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $sale_total = $this->model_report_sale->getTotalSales();

        if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1);
            $suffix = 'T';
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1);
            $suffix = 'B';
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1);
            $suffix = 'M';
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1);
            $suffix = 'K';
        } else {
            $data['total'] = round($sale_total);
            $suffix = '';
        }

        $data['total'] = $this->currency->format($data['total'], $this->config->get('config_currency')) . $suffix;
        
        $data['sale'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/sale.tpl', $data);
    }
}
