<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelSaleFraud extends Model {
    public function getFraud($order_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_fraud` WHERE order_id = '" . (int)$order_id . "'");

        return $query->row;
    }
}
