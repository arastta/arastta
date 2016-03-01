<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventCatalogBasic extends Event
{

    public function preAdminProductAdd(&$data)
    {
        if (!isset($this->session->data['new_product'])) {
            return null;
        }

        $images = $this->session->data['new_product'];
        unset($this->session->data['new_product']);

        foreach ($images as $key => $value) {
            $data['product_image'][] = array(
                'image'      => 'catalog/' . $value,
                'sort_order' => $key
            );
        }
        return $data;
    }

    public function preAdminProductEdit(&$data)
    {
        if (!isset($this->session->data['edit_product'])) {
            return null;
        }

        $images = $this->session->data['edit_product'];
        unset($this->session->data['edit_product']);

        foreach ($images as $key => $value) {
            $data['product_image'][] = array(
                'image'      => 'catalog/' . $value,
                'sort_order' => $key
            );
        }
        return $data;
    }
}
