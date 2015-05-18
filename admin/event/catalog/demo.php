<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class EventCatalogDemo extends Event {

    public function postAdminManufacturerEdit($manufacturer_id) {
        $a = 'Dummy text '.$manufacturer_id;
    }
}