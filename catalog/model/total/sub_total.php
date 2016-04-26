<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelTotalSubTotal extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/sub_total');

        $sub_total = $this->cart->getSubTotal();

        if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $sub_total += $voucher['amount'];
            }
        }

        $total_data[] = array(
            'code'       => 'sub_total',
            'title'      => $this->language->get('text_sub_total'),
            'value'      => $sub_total,
            'sort_order' => $this->config->get('sub_total_sort_order')
        );

        $total += $sub_total;
    }
}
