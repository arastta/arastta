<?php
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
