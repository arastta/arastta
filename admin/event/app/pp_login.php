<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class EventAppPplogin extends Event {

    public function postCustomerLogout(&$data) {
        $this->load->controller("module/pp_login/logout", $data);
    }
}