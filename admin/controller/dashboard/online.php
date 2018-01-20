<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerDashboardOnline extends Controller {
    public function index() {
        $this->load->language('dashboard/online');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('report/customer');
        
        // Customers Online
        $online_total = $this->model_report_customer->getTotalCustomersOnline();
        
        if ($online_total > 1000000000000) {
            $data['total'] = round($online_total / 1000000000000, 1) . $this->language->get('trillion_suffix');
        } elseif ($online_total > 1000000000) {
            $data['total'] = round($online_total / 1000000000, 1) . $this->language->get('billion_suffix');
        } elseif ($online_total > 1000000) {
            $data['total'] = round($online_total / 1000000, 1) . $this->language->get('million_suffix');
        } elseif ($online_total > 1000) {
            $data['total'] = round($online_total / 1000, 1) . $this->language->get('thousand_suffix');
        } else {
            $data['total'] = $online_total;
        }            
        
        $data['online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/online.tpl', $data);
    }
}