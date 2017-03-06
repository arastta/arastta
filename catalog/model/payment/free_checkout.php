<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelPaymentFreeCheckout extends Model {
    public function getMethod($address, $total) {
        $this->load->language('payment/free_checkout');

        if ($total <= 0.00) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'free_checkout',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('free_checkout_sort_order')
            );
        }

        return $method_data;
    }
}
