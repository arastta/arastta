<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelTotalHandling extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        if (($this->cart->getSubTotal() > $this->config->get('handling_total')) && ($this->cart->getSubTotal() > 0)) {
            $this->load->language('total/handling');

            $total_data[] = array(
                'code'       => 'handling',
                'title'      => $this->language->get('text_handling'),
                'value'      => $this->config->get('handling_fee'),
                'sort_order' => $this->config->get('handling_sort_order')
            );

            if ($this->config->get('handling_tax_class_id')) {
                $tax_rates = $this->tax->getRates($this->config->get('handling_fee'), $this->config->get('handling_tax_class_id'));

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($taxes[$tax_rate['tax_rate_id']])) {
                        $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                    } else {
                        $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                    }
                }
            }

            $total += $this->config->get('handling_fee');
        }
    }
}
