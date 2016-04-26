<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerPaymentCod extends Controller {
    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');
        
        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/cod.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/cod.tpl', $data);
        }
    }

    public function confirm() {
        if ($this->session->data['payment_method']['code'] == 'cod') {
            $this->load->model('checkout/order');

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
        }
    }
}
