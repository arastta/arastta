<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ModelCommonEdit extends Model {

    public function changeStatus($type, $ids, $status, $extension = false) {
        if($extension){
            foreach($ids as $id) {
                $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = {$status} WHERE `code` = '{$id}' AND `key` = '{$id}_status'", 'query');
            }
        }
        else{
            foreach($ids as $id) {
                $this->db->query("UPDATE " . DB_PREFIX . "{$type} SET status = {$status} WHERE {$type}_id = {$id}", 'query');
            }
        }
    }
}