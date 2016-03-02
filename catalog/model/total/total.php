<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
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
