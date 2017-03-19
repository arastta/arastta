<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Symfony\Component\Finder\Finder;

class ModelLocalisationLanguage extends Model {
    public function addLanguage($data) {
        $this->trigger->fire('pre.admin.language.add', array(&$data));

        $this->load->model('catalog/url_alias');

        $this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");

        $this->cache->delete('language');

        $language_id = $this->db->getLastId();

        // Attribute
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $attribute) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($attribute['name']) . "'");
        }

        // Attribute Group
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $attribute_group) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($attribute_group['name']) . "'");
        }

        $this->cache->delete('attribute');

        // Banner
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $banner_image) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($banner_image['title']) . "', link = '" . $this->db->escape($banner_image['link']) . "', image = '" . $this->db->escape($banner_image['image']) . "', sort_order = '" . $this->db->escape($banner_image['sort_order']) . "'");
        }

        $this->cache->delete('banner');

        // Category
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $category) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($category['name']) . "', meta_description = '" . $this->db->escape($category['meta_description']) . "', meta_keyword = '" . $this->db->escape($category['meta_keyword']) . "', description = '" . $this->db->escape($category['description']) . "'");

            $seo_url = $this->model_catalog_url_alias->getAlias('category', $category['category_id'], (int)$this->config->get('config_language_id'));

            $alias = empty($seo_url['keyword']) ? $category['name'] : $seo_url['keyword'];

            $this->model_catalog_url_alias->addAlias('category', $category['category_id'], $alias, $language_id);
        }

        $this->cache->delete('category');

        // Customer Group
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $customer_group) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($customer_group['name']) . "', description = '" . $this->db->escape($customer_group['description']) . "'");
        }

        // Custom Field
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $custom_field) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_description SET custom_field_id = '" . (int)$custom_field['custom_field_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($custom_field['name']) . "'");
        }

        // Custom Field Value
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $custom_field_value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value_description SET custom_field_value_id = '" . (int)$custom_field_value['custom_field_value_id'] . "', language_id = '" . (int)$language_id . "', custom_field_id = '" . (int)$custom_field_value['custom_field_id'] . "', name = '" . $this->db->escape($custom_field_value['name']) . "'");
        }

        // Download
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $download) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
        }

        // Filter
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $filter) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter['filter_id'] . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter['filter_group_id'] . "', name = '" . $this->db->escape($filter['name']) . "'");
        }

        // Filter Group
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $filter_group) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group['filter_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($filter_group['name']) . "'");
        }

        // Information
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $information) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($information['title']) . "', description = '" . $this->db->escape($information['description']) . "'");

            $seo_url = $this->model_catalog_url_alias->getAlias('information', $information['information_id'], (int)$this->config->get('config_language_id'));

            $alias = empty($seo_url['keyword']) ? $information['title'] : $seo_url['keyword'];

            $this->model_catalog_url_alias->addAlias('information', $information['information_id'], $alias, $language_id);
        }

        $this->cache->delete('information');

        // Length
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $length) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($length['title']) . "', unit = '" . $this->db->escape($length['unit']) . "'");
        }

        $this->cache->delete('length_class');

        // Option
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $option) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($option['name']) . "'");
        }

        // Option Value
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $option_value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . $this->db->escape($option_value['name']) . "'");
        }

        // Product
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $product) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($product['name']) . "', meta_description = '" . $this->db->escape($product['meta_description']) . "', meta_keyword = '" . $this->db->escape($product['meta_keyword']) . "', description = '" . $this->db->escape($product['description']) . "', tag = '" . $this->db->escape($product['tag']) . "'");

            $seo_url = $this->model_catalog_url_alias->getAlias('product', $product['product_id'], (int)$this->config->get('config_language_id'));

            $alias = empty($seo_url['keyword']) ? $product['name'] : $seo_url['keyword'];

            $this->model_catalog_url_alias->addAlias('product', $product['product_id'], $alias, $language_id);
        }

        $this->cache->delete('product');

        // Product Attribute
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $product_attribute) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape($product_attribute['text']) . "'");
        }

        // Voucher Theme
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $voucher_theme) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($voucher_theme['name']) . "'");
        }

        $this->cache->delete('voucher_theme');

        // Weight Class
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $weight_class) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($weight_class['title']) . "', unit = '" . $this->db->escape($weight_class['unit']) . "'");
        }

        $this->cache->delete('weight_class');

        // Profiles
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $recurring) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "recurring_description SET recurring_id = '" . (int)$recurring['recurring_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($recurring['name']));
        }

        // Manufacturer
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $manufacturer) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($manufacturer['name']) . "', description = '" . $this->db->escape($manufacturer['description']) . "', meta_title = '" . $this->db->escape($manufacturer['meta_title']) . "', meta_description = '" . $this->db->escape($manufacturer['meta_description']) . "', meta_keyword = '" . $this->db->escape($manufacturer['meta_keyword']) . "' ;");

            $seo_url = $this->model_catalog_url_alias->getAlias('manufacturer', $manufacturer['manufacturer_id'], (int)$this->config->get('config_language_id'));

            $alias = empty($seo_url['keyword']) ? $manufacturer['name'] : $seo_url['keyword'];

            $this->model_catalog_url_alias->addAlias('manufacturer', $manufacturer['manufacturer_id'], $alias, $language_id);
        }

        $this->cache->delete('manufacturer');

        // Menu
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $menu) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu['menu_id'] . "', name = '" . $menu['name'] . "', link = '" . $menu['link'] . "', language_id = '" . (int)$language_id . "'");
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_child_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $menu_child) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "menu_child_description SET menu_child_id = '" . (int)$menu_child['menu_child_id'] . "', menu_id = '" . (int)$menu_child['menu_id'] . "', name = '" . $menu_child['name'] . "', link = '" . $menu_child['link'] . "', language_id = '" . (int)$language_id . "'");
        }

        $this->cache->delete('menu');

        // Insert Email templates, order statuses, stock statuses, return statuses and return reasons languages
        $this->language->load('email_template');
        $this->language->load('order_status');
        $this->language->load('stock_status');
        $this->language->load('return_status');
        $this->language->load('return_reason');
        $this->language->load('return_action');

        $this->prepareLanguages($language_id, $this->db);

        $this->cache->delete('email_template');
        $this->cache->delete('order_status');
        $this->cache->delete('stock_status');
        $this->cache->delete('return_status');
        $this->cache->delete('return_reason');
        $this->cache->delete('return_action');

        $this->trigger->fire('post.admin.language.add', array(&$language_id));

        return $language_id;
    }

    public function prepareLanguages($language_id, $db)
    {
        $data = $this->language->all();
        $this->emailTemplateLanguages($data, $language_id, $db);
        $this->orderStatusLanguages($data, $language_id, $db);
        $this->stockStatusLanguages($data, $language_id, $db);
        $this->returnStatusLanguages($data, $language_id, $db);
        $this->returnReasonLanguages($data, $language_id, $db);
        $this->returnActionLanguages($data, $language_id, $db);
    }

    private function emailTemplateLanguages($data, $language_id, $db)
    {
        $finder = new Finder();
        $finder->files()->in(DIR_ADMIN . 'view/template/email');
        foreach ($finder as $email_template)
        {
            $email_template_id = rtrim($email_template->getFilename(), '.' . $email_template->getExtension());
            $item              = explode('_', $email_template_id);
            $query             = $db->query("SELECT id FROM " . DB_PREFIX . "email WHERE type = '" . $item[0] . "' AND text_id = " . $item[1]);

            $content = $this->load->view('email/' . $email_template->getFilename(), $data);


            $sql = 'INSERT INTO ' . DB_PREFIX . 'email_description (`email_id`, `name`, `description`, `status`, `language_id`) VALUES ' .
                "(" . (int) $query->row['id'] . "," .
                "'" . $db->escape(htmlspecialchars($data[$email_template_id . '_subject'])) . "'," .
                "'" . $db->escape(htmlspecialchars($content)) . "', 1, " . $language_id . ")";

            $db->query($sql);
        }
    }

    private function stockStatusLanguages($data, $language_id, $db)
    {
        $sql = 'INSERT INTO `' . DB_PREFIX . 'stock_status` (`stock_status_id`, `language_id`, `name`, `color`, `preorder`) VALUES ';

        $values[] = "(5, {$language_id}, '" . $data['stock_out_of_stock'] . "', '#FF0000', 0)";
        $values[] = "(6, {$language_id}, '" . $data['stock_2_3_days'] . "', '#FFA500', 0)";
        $values[] = "(7, {$language_id}, '" . $data['stock_in_stock'] . "', '#008000', 0)";
        $values[] = "(8, {$language_id}, '" . $data['stock_pre_order'] . "', '#FFFF00', 1)";

        $sql .= implode(',', $values);

        $db->query($sql);
    }

    private function orderStatusLanguages($data, $language_id, $db)
    {
        $sql = 'INSERT INTO `' . DB_PREFIX . 'order_status` (`language_id`, `name`, `message`) VALUES ';

        $values[] = "({$language_id}, '" . $data['order_pending'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_processing'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_shipped'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_complete'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_cancelled'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_denied'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_cancelled_reversal'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_failed'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_refunded'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_reversed'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_chargeback'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_expired'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_processed'] . "', '')";
        $values[] = "({$language_id}, '" . $data['order_voided'] . "', '')";

        $sql .= implode(',', $values);

        $db->query($sql);
    }

    private function returnStatusLanguages($data, $language_id, $db)
    {
        $sql = 'INSERT INTO `' . DB_PREFIX . 'return_status` (`language_id`, `name`) VALUES ';

        $values[] = "({$language_id}, '" . $data['return_pending'] . "')";
        $values[] = "({$language_id}, '" . $data['return_awaiting_products'] . "')";
        $values[] = "({$language_id}, '" . $data['return_complete'] . "')";

        $sql .= implode(',', $values);

        $db->query($sql);
    }

    private function returnReasonLanguages($data, $language_id, $db)
    {
        $sql = 'INSERT INTO `' . DB_PREFIX . 'return_reason` (`language_id`, `name`) VALUES ';

        $values[] = "({$language_id}, '" . $data['reason_dead_on_arrival'] . "')";
        $values[] = "({$language_id}, '" . $data['reason_received_wrong_item'] . "')";
        $values[] = "({$language_id}, '" . $data['reason_order_error'] . "')";
        $values[] = "({$language_id}, '" . $data['reason_faulty_supply_details'] . "')";

        $sql .= implode(',', $values);

        $db->query($sql);
    }

    private function returnActionLanguages($data, $language_id, $db)
    {
        $sql = 'INSERT INTO `' . DB_PREFIX . 'return_action` (`language_id`, `name`) VALUES ';

        $values[] = "({$language_id}, '" . $data['action_refunded'] . "')";
        $values[] = "({$language_id}, '" . $data['action_credit_issued'] . "')";
        $values[] = "({$language_id}, '" . $data['action_replacement_sent'] . "')";

        $sql .= implode(',', $values);

        $db->query($sql);
    }

    public function editLanguage($language_id, $data) {
        $this->trigger->fire('pre.admin.language.edit', array(&$data));

        $this->db->query("UPDATE " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "' WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('language');

        $this->trigger->fire('post.admin.language.edit', array(&$language_id));
    }

    public function deleteLanguage($language_id) {
        $this->trigger->fire('pre.admin.language.delete', array(&$language_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('language');

        $this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '" . (int)$language_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE language_id = '" . (int)$language_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('category');

        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('information');

        $this->db->query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('length_class');

        $this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('order_status');

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('product');

        $this->db->query("DELETE FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('return_action');

        $this->db->query("DELETE FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('return_reason');

        $this->db->query("DELETE FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('return_status');

        $this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('stock_status');

        $this->db->query("DELETE FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('voucher_theme');

        $this->db->query("DELETE FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('weight_class');

        $this->db->query("DELETE FROM " . DB_PREFIX . "recurring_description WHERE language_id = '" . (int)$language_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = '" . (int)$language_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "menu_child_description WHERE language_id = '" . (int)$language_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "email_description WHERE language_id = '" . (int)$language_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE language_id = '" . (int)$language_id . "'");

        $this->trigger->fire('post.admin.language.delete', array(&$language_id));
    }

    public function getLanguage($language_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        return $query->row;
    }

    public function getLanguages($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "language";

            if (!isset($data['status'])) {
                $sql .= " WHERE status = '1'";
            }

            $sort_data = array(
                'name',
                'code',
                'sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY sort_order, name";
            }

            if (isset($data['sort']) && $data['sort'] == 'sort_order') {
                $sql .= ", name";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $language_data = $this->cache->get('language');

            if (!$language_data) {
                $language_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' ORDER BY sort_order, name");

                foreach ($query->rows as $result) {
                    $language_data[$result['code']] = array(
                        'language_id' => $result['language_id'],
                        'name'        => $result['name'],
                        'code'        => $result['code'],
                        'locale'      => $result['locale'],
                        'image'       => $result['image'],
                        'directory'   => $result['directory'],
                        'sort_order'  => $result['sort_order'],
                        'status'      => $result['status']
                    );
                }

                $this->cache->set('language', $language_data);
            }

            return $language_data;
        }
    }

    public function getTotalLanguages($data = array()) {
        $where = "";

        if (!isset($data['status'])) {
            $where = " WHERE status = '1'";
        }

        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language" . $where);

        return $query->row['total'];
    }
    
    public function getLanguageByCode($code)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `code` = '" . $this->db->escape($code) . "'");

        return $query->row;
    }
}
