<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelCheckoutMarketing extends Model {
    public function getMarketingByCode($code) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "marketing WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }
}
