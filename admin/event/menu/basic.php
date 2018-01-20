<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventMenuBasic extends Event
{

    public function preAdminMenuRender(&$menu)
    {
        if ($this->session->data['theme'] != 'basic') {
            return true;
        }
        
        // Catalog Menu Items
        $menu->removeMenuItem('recurring', 'catalog');
        $menu->removeMenuItem('filter', 'catalog');
        $menu->removeMenuItem('attribute', 'catalog');
        $menu->removeMenuItem('download', 'catalog');
        
        // Sale Menu Item
        $menu->removeMenuItem('order_recurring', 'sale');
    }
}
