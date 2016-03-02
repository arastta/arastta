<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerApiCurrency extends Controller
{
    public function index()
    {
        $this->load->language('api/currency');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('localisation/currency');
            
            $currency_info = $this->model_localisation_currency->getCurrencyByCode($this->request->post['currency']);
            
            if ($currency_info) {
                $this->currency->set($this->request->post['currency']);
    
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_currency');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
