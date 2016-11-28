<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventCatalogCombination extends Event
{

    public function postAdminProductAdd($product_id)
    {
        $this->postAdminProductEdit($product_id);
    }

    public function postAdminProductEdit($product_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_combination WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_combination_value WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_combination_value_description WHERE product_id = '" . (int)$product_id . "'");

        if (isset($this->request->post['product_option_combination'])) {
            $product_option_combination = $this->request->post['product_option_combination'];

            foreach ($product_option_combination['product_option_id'] as $key => $product_option_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_combination SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option_combination['option_id'][$key] . "', parent = '" . (int)1 . "'");

                if (!isset($combination_id)) {
                    $combination_id = $this->db->getLastId();
                }
            }

            if (isset($product_option_combination['product_option_value'])) {
                foreach ($product_option_combination['product_option_value'] as $product_option_value) {
                    $parent_id = 1;
                    $combination_value_id = 1;

                    foreach ($product_option_value['option_value_id'] as $key => $option_value_id) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_combination_value SET product_option_combination_id = '" . (int)$combination_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option_combination['option_id'][$key] . "', option_value_id = '" . (int)$option_value_id . "', parent_id = '" . (int)$parent_id . "'");

                        $parent_id = $this->db->getLastId();
                    }

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_combination_value_description SET product_option_combination_value_id = '" . (int)$combination_value_id . "', product_id = '" . (int)$product_id . "', model = '" . $this->db->escape($product_option_value['model']) . "', sku = '" . $this->db->escape($product_option_value['sku']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
                }
            }
        }
    }

    public function postAdminProductDelete($product_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_combination WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_combination_value WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_combination_value_description WHERE product_id = '" . (int)$product_id . "'");
    }
}
