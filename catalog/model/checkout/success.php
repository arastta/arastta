<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelCheckoutSuccess extends Model
{
    public function getMessage($order_id)
    {
        $message = '';
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

        if (!empty($query->num_rows)) {
            $order_status_id = $query->row['order_status_id'];

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id ='" . $this->config->get('config_language_id') ."'") ;

            if (!empty($query->num_rows)) {
                $message = $query->row['message'];
            }
        }

        return $message;
    }
}
