<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerDashboardMap extends Controller {
    public function index() {
        $this->load->language('dashboard/map');

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_order'] = $this->language->get('text_order');
        $data['text_sale'] = $this->language->get('text_sale');
        
        $data['token'] = $this->session->data['token'];
                
        return $this->load->view('dashboard/map.tpl', $data);
    }
    
    public function map() {
        $json = array();
        
        $this->load->model('report/sale');
        
        $results = $this->model_report_sale->getTotalOrdersByCountry();
        
        foreach ($results as $result) {
            $json[strtolower($result['iso_code_2'])] = array(
                'total'  => $result['total'],
                'amount' => $this->currency->format($result['amount'], $this->config->get('currency_code'))
            );
        }
    
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
