<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerDashboardSale extends Controller {
    public function index() {
        $this->load->language('dashboard/sale');

        $this->load->model('localisation/currency');

        $config_currency = $this->config->get('config_currency');

        $currency = $this->model_localisation_currency->getCurrencyByCode($config_currency);

        $data['heading_title'] = $this->language->get('heading_title') . ' (' . $currency['title'] . ')';

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

        $sale_total = $this->currency->format($this->model_report_sale->getTotalSales(), $config_currency, '', false);

        if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1) . $this->language->get('trillion_suffix');
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1) . $this->language->get('billion_suffix');
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1) . $this->language->get('million_suffix');
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1) . $this->language->get('thousand_suffix');
        } else {
            $data['total'] = round($sale_total);
        }
        
        $data['sale'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/sale.tpl', $data);
    }
}