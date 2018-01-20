<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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
