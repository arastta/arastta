<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelTotalTotal extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/total');

        $total_data[] = array(
            'code'       => 'total',
            'title'      => $this->language->get('text_total'),
            'value'      => max(0, $total),
            'sort_order' => $this->config->get('total_sort_order')
        );
    }
}
