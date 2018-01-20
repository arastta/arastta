<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventCatalogDemo extends Event
{

    public function postAdminManufacturerEdit($manufacturer_id)
    {
        $a = 'Dummy text '.$manufacturer_id;
    }
}
