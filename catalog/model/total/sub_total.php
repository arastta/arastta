<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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
