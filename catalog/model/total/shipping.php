<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelTotalShipping extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
            $total_data[] = array(
                'code'       => 'shipping',
                'title'      => $this->session->data['shipping_method']['title'],
                'value'      => $this->session->data['shipping_method']['cost'],
                'sort_order' => $this->config->get('shipping_sort_order')
            );

            if ($this->session->data['shipping_method']['tax_class_id']) {
                $tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($taxes[$tax_rate['tax_rate_id']])) {
                        $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                    } else {
                        $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                    }
                }
            }

            $total += $this->session->data['shipping_method']['cost'];
        }
    }
}
