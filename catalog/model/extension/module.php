<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelExtensionModule extends Model {
    public function getModule($module_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");
        
        if ($query->row) {
            return unserialize($query->row['setting']);
        } else {
            return array();    
        }
    }

    public function getModules() {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` ORDER BY `code`");

        return $query->rows;
    }

    public function getModulesByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

        return $query->rows;
    }      
}
