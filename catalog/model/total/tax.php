<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelTotalTax extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        foreach ($taxes as $key => $value) {
            if ($value > 0) {
                $total_data[] = array(
                    'code'       => 'tax',
                    'title'      => $this->tax->getRateName($key),
                    'value'      => $value,
                    'sort_order' => $this->config->get('tax_sort_order')
                );

                $total += $value;
            }
        }
    }
}
