<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */
 
# VERSION = the current version
# $version = the version to be updated
 
// 1.0.3 changes;
if (version_compare(VERSION, '1.0.3', '<')) {
    // Delete language english directory
    $this->filesystem->remove(DIR_LANGUAGE . 'english');
    $this->filesystem->remove(DIR_CATALOG . 'language/english');

    // Update language directory name
    $this->db->query("UPDATE `" . DB_PREFIX . "language` SET `directory` = 'en-GB' WHERE `code` = 'en';");

    // Add field ('params') Addon table
    $addon = array();

    $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "addon`");

    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $addon[] = $row['Field'];
        }
    }

    if (!in_array('params', $addon)) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "addon` ADD COLUMN `params` TEXT DEFAULT NULL");
    }
}

// 1.1.0 changes;
if (version_compare(VERSION, '1.1.0', '<')) {
    // Update the user groups
    $user_groups = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group");

    foreach ($user_groups->rows as $user_group) {
        $user_group['permission'] = unserialize($user_group['permission']);

        $user_group['permission']['access'][] = 'tool/system_info';
        $user_group['permission']['modify'][] = 'tool/system_info';

        $user_group['permission']['access'][] = 'feed/facebook_store';
        $user_group['permission']['modify'][] = 'feed/facebook_store';

        $user_group['permission']['dashboard'] = array(
            '0' => 'dashboard/charts',
            '1' => 'dashboard/online',
            '2' => 'dashboard/recenttabs',
            '3' => 'dashboard/customer',
            '4' => 'dashboard/order',
            '5' => 'dashboard/sale',
            '6' => 'dashboard/map',
        );

        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($user_group['name']) . "', permission = '" . $this->db->escape(serialize($user_group['permission'])) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'");
    }

    // Update the modules
    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module`");

    foreach ($query->rows as $module) {
        $module_setting = unserialize($module['setting']);

        if (isset($module_setting['product']) || ($module['code'] == 'featured')) {
            $module_setting['feed'] = 1;

            $this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($module_setting['name']) . "', `setting` = '" . $this->db->escape(serialize($module_setting)) . "' WHERE `module_id` = '" . (int)$module['module_id'] . "'");
        }
    }
}

// 1.1.3 changes;
if (version_compare(VERSION, '1.1.3', '<')) {
    // Delete modification table
    $this->db->query("DROP TABLE `" . DB_PREFIX . "modification`");
}

// 1.2.0 changes;
if (version_compare(VERSION, '1.2.0', '<')) {
    // Update user table
    $user = array();

    $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "user`");

    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $user[] = $row['Field'];
        }
    }

    if (!in_array('params', $user)) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `username` VARCHAR(100)");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `password` VARCHAR(100)");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `email` VARCHAR(100)");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "user` ADD `params` text AFTER `date_added`");
    }

    // Update stock status table
    $stock_status = array();

    $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "stock_status`");

    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $stock_status[] = $row['Field'];
        }
    }

    if (!in_array('color', $stock_status)) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "stock_status` ADD `color` VARCHAR(32) NOT NULL AFTER `name`");
    }

    // Added stock status default color
    $this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#FF0000' WHERE `stock_status_id` = '5'");
    $this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#FFA500' WHERE `stock_status_id` = '6'");
    $this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#008000' WHERE `stock_status_id` = '7'");
    $this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#FFFF00' WHERE `stock_status_id` = '8'");

    // Insert email template for return request
    $this->db->query("INSERT INTO `" . DB_PREFIX . "email` SET `text` = 'Return Request', `text_id` = '1', `context` = 'added', `type` = 'return', `status` = '1'");
    $email_id = $this->db->getLastId();

    $this->db->query("INSERT INTO `" . DB_PREFIX . "email_description` SET `email_id` = '" . $email_id . "', `name` = '{store_name} - Product Return Request', `description` = '&lt;div style=&quot;width:695px;&quot;&gt;&lt;p style=&quot;margin-top:0px;margin-bottom:20px&quot;&gt;You have a new product return request.&lt;/p&gt;            &lt;table style=&quot;border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px&quot;&gt;    &lt;thead&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot; colspan=&quot;2&quot;&gt;Order Details&lt;/td&gt;      &lt;/tr&gt;    &lt;/thead&gt;    &lt;tbody&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;b&gt;Order ID:&amp;nbsp;&lt;/b&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{order_id}&lt;/span&gt;&lt;br&gt;          &lt;b&gt;Date Ordered:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{date_ordered}&lt;/span&gt;&lt;br&gt;&lt;b style=&quot;color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;&quot;&gt;Customer&lt;/b&gt;&lt;b&gt;:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{firstname} {lastname}&lt;/span&gt;&lt;br&gt;&lt;b style=&quot;color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;&quot;&gt;E-mail&lt;/b&gt;&lt;b&gt;:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{email}&lt;/span&gt;&lt;br&gt;&lt;b&gt;Telephone:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{telephone}&lt;/span&gt;&lt;br&gt;&lt;/td&gt;      &lt;/tr&gt;    &lt;/tbody&gt;  &lt;/table&gt;&lt;table style=&quot;border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px&quot;&gt;    &lt;thead&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot;&gt;Product&lt;/td&gt;                &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot;&gt;Model&lt;/td&gt;&lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align: right;padding:7px;color:#222222&quot;&gt;Quantity&lt;/td&gt;              &lt;/tr&gt;    &lt;/thead&gt;    &lt;tbody&gt;      &lt;tr&gt;        &lt;td style=&quot;width: 40%;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{product}&lt;/span&gt;&lt;br&gt;&lt;/td&gt;                &lt;td style=&quot;width: 40%;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{model}&lt;/span&gt;&lt;/td&gt;&lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align: right;padding:7px&quot;&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{quantity}&lt;/span&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;table style=&quot;border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px&quot;&gt;  &lt;thead&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot; colspan=&quot;2&quot;&gt;Return Details&lt;/td&gt;      &lt;/tr&gt;    &lt;/thead&gt;  &lt;tbody&gt;		&lt;tr&gt;			&lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;  &lt;b&gt;Return Reason:&amp;nbsp;&lt;/b&gt;  &lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{return_reason}&lt;/span&gt;  &lt;br&gt;  &lt;b&gt;Opened:&amp;nbsp;&lt;/b&gt;  &lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{opened}&lt;/span&gt;  &lt;br&gt;&lt;br&gt;  &lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{comment}&lt;/span&gt;&lt;/td&gt;		&lt;/tr&gt;	&lt;/tbody&gt;&lt;/table&gt;	&lt;/div&gt;', `status` = '1', `language_id` = '1'");

    // Insert email template for out of stock
    $this->db->query("INSERT INTO `" . DB_PREFIX . "email` SET `text` = 'Out Of Stock', `text_id` = '1', `context` = 'out', `type` = 'stock', `status` = '1'");
    $email_id = $this->db->getLastId();

    $this->db->query("INSERT INTO `" . DB_PREFIX . "email_description` SET `email_id` = '" . $email_id . "', `name` = '{store_name} - {total_products} Product(s) Out Of Stock', `description` = 'Hello,&lt;br&gt;&lt;br&gt;We would like to notify that you have &lt;b&gt;{total_products}&lt;/b&gt; product(s) out of stock in store&nbsp;&lt;strong&gt;{store_name}&lt;/strong&gt;.&lt;br&gt;&lt;br&gt;You can view them by clicking on Notifications icon -&gt; Out of Stock, or browse to Catalog -&gt; Products and filter quantity = 0.&lt;br&gt;&lt;br&gt;Best Regards,&lt;br&gt;&lt;br&gt;The {store_name} team', `status` = '1', `language_id` = '1'");

    // Extension/Theme manager
    $addon = array();

    $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "addon`");

    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $addon[] = $row['Field'];
        }
    }

    if (!in_array('addon_files', $addon)) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "addon` CHANGE `addon_files` `files` TEXT NULL");
    }

    $extension = array();

    $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "extension`");

    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $extension[] = $row['Field'];
        }
    }

    if (!in_array('info', $extension)) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "extension` ADD `info` TEXT NULL AFTER `code`, ADD `params` TEXT NULL AFTER `info`");
    }

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "theme` (
        `theme_id` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(32) NOT NULL,
        `info` text NULL,
        `params` text NULL,
        PRIMARY KEY (`theme_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    // Update params of addons
    $addons = $this->db->query("SELECT * FROM `" . DB_PREFIX . "addon`")->rows;

    foreach ($addons as $addon) {
        $params = json_decode($addon['params'], true);

        // Update to language_id
        if (isset($params['localisation/language'])) {
            $params['language_id'] = $params['localisation/language'];
            unset($params['localisation/language']);
        }

        // Set theme/extension ids
        $params['theme_ids'] = array();
        $params['extension_ids'] = array();

        $this->db->query("UPDATE `" . DB_PREFIX . "addon` SET `params` = '" . json_encode($params) . "' WHERE `addon_id` = '" . $addon['addon_id'] . "'");
    }

    // Invoices
    $this->db->query("CREATE TABLE `" . DB_PREFIX . "invoice` (
        `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
        `order_id` int(11) NOT NULL,
        `invoice_date` datetime NOT NULL,
        PRIMARY KEY (`invoice_id`),
        KEY `order_id` (`order_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE `" . DB_PREFIX . "invoice_history` (
        `invoice_history_id` int(11) NOT NULL AUTO_INCREMENT,
        `invoice_id` int(11) NOT NULL,
        `notify` tinyint(1) NOT NULL DEFAULT '0',
        `comment` text NOT NULL,
        `date_added` datetime NOT NULL,
        PRIMARY KEY (`invoice_history_id`),
        KEY `invoice_id` (`invoice_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $orders = $this->db->query("SELECT order_id, date_added FROM `" . DB_PREFIX . "order` WHERE `invoice_no` <> ''")->rows;

    foreach ($orders as $order) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "invoice SET invoice_date = '" . $this->db->escape($order['date_added']) . "', order_id = '" . (int) $order['order_id'] . "'");
    }

    $this->db->query("INSERT INTO `" . DB_PREFIX . "email` SET `text` = 'Invoice Mail', `text_id` = '1', `context` = 'invoice.mail', `type` = 'invoice', `status` = '1'");
    $email_id = $this->db->getLastId();
    $this->db->query("INSERT INTO `" . DB_PREFIX . "email_description` SET `email_id` = '" . $email_id . "', `name` = 'Invoice for your {order_id} order', `description` = '&lt;p&gt;Hello {customer},&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Thank you for your interest in {store_name} products. You can find the invoice in the attachment.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Please reply to this e-mail if you have any questions.&lt;br&gt;&lt;/p&gt;', `status` = '1', `language_id` = '1'");

    $this->db->query("INSERT INTO `" . DB_PREFIX . "email` SET `text` = 'Invoice History', `text_id` = '2', `context` = 'invoice.history', `type` = 'invoice', `status` = '1'");
    $email_id = $this->db->getLastId();
    $this->db->query("INSERT INTO `" . DB_PREFIX . "email_description` SET `email_id` = '" . $email_id . "', `name` = '{store_name} Invoice - {invoice_no} History', `description` = '&lt;p&gt;Invoice No.:&amp;nbsp;{invoice_no}&lt;/p&gt;&lt;p&gt;Invoice Date:&amp;nbsp;{date_added}&lt;br&gt;&lt;/p&gt;&lt;p&gt;The comments for your invoice are:&lt;/p&gt;&lt;p&gt;{comment}&lt;/p&gt;&lt;p&gt;Please reply to this email if you have any questions.&lt;br&gt;&lt;/p&gt;', `status` = '1', `language_id` = '1'");

    // Update user groups
    $user_groups = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group");

    foreach ($user_groups->rows as $user_group) {
        $user_group['permission'] = unserialize($user_group['permission']);

        $user_group['permission']['access'][] = 'appearance/theme';
        $user_group['permission']['modify'][] = 'appearance/theme';

        $user_group['permission']['access'][] = 'extension/extension';
        $user_group['permission']['modify'][] = 'extension/extension';

        $user_group['permission']['access'][] = 'sale/invoice';
        $user_group['permission']['modify'][] = 'sale/invoice';

        $user_group['permission']['access'][] = 'editor/summernote';
        $user_group['permission']['modify'][] = 'editor/summernote';

        $user_group['permission']['access'][] = 'editor/tinymce';
        $user_group['permission']['modify'][] = 'editor/tinymce';

        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($user_group['name']) . "', permission = '" . $this->db->escape(serialize($user_group['permission'])) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'");
    }

    // Update users
    $users = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user");

    foreach ($users->rows as $user) {
        $params = '{"theme":"basic","basic_mode_message":"show","language":"' . $this->session->data['admin_language'] . '","editor":"summernote"}';

        $this->db->query("UPDATE " . DB_PREFIX . "user SET params = '" . $this->db->escape($params) . "' WHERE user_id = '" . (int)$user['user_id'] . "'");
    }

    if (!file_exists(DIR_ADMIN . 'controller/module/pp_login.php')) {
        $this->filesystem->remove(DIR_CATALOG . 'event/app/pp_login.php');
    }
}

// 1.2.4 changes;
if (version_compare(VERSION, '1.2.4', '<')) {
    // Update download table
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "download` CHANGE `filename` `filename` varchar(255) NOT NULL");
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "download` CHANGE `mask` `mask` varchar(255) NOT NULL");
}

// 1.3.0 changes;
if (version_compare(VERSION, '1.3.0', '<')) {
    // Insert setting table
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_admin_template', `value` = 'basic'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_admin_template_message', `value` = 'show'");

    $addon = $this->db->query("SELECT * FROM " . DB_PREFIX . "addon WHERE `files` LIKE '%summernote%'");

    if (empty($addon->num_rows)) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET type = 'editor', `code` = 'summernote'");

        $extension_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "addon SET product_id = '0', `product_name` = 'Summernote', `product_type` = 'editor', `product_version` = '1.0.0', `files` = '[\"admin\\/controller\\\\editor\\\\summernote.php","admin\\/language\\\\en-GB\\\\editor\\\\summernote.php","admin\\/view\\\\template\\\\editor\\\\summernote.tpl\"]', `params` = '{\"theme_ids\":[],\"extension_ids\":[" . $extension_id . "]}'");
    }

    $summernote = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "setting WHERE `code` = 'summernote' AND `key` = 'summernote_status' AND `value` = '1'");

    if (empty($summernote->num_rows)) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_status', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_height', `value` = '300'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_sort_order', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_style', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_font_bold', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_font_italic', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_font_underline', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_font_clear', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_fontname', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_fontsize', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_color', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_para_ol', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_para_ul', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_para_paragraph', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_height', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_table', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_insert_link', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_insert_picture', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_insert_hr', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_view_fullscreen', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_view_codeview', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'summernote', `key` = 'summernote_tool_help', `value` = '1'");
    }

    $addon = $this->db->query("SELECT * FROM " . DB_PREFIX . "addon WHERE `files` LIKE '%tinymce%'");

    if (empty($addon->num_rows)) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET type = 'editor', `code` = 'tinymce'");

        $extension_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "addon SET product_id = '0', `product_name` = 'Tinymce', `product_type` = 'editor', `product_version` = '1.0.0', `files` = '[\"admin\\/controller\\\\editor\\\\tinymce.php","admin\\/language\\\\en-GB\\\\editor\\\\tinymce.php","admin\\/view\\\\template\\\\editor\\\\tinymce.tpl\"]', `params` = '{\"theme_ids\":[],\"extension_ids\":[" . $extension_id . "]}'");
    }

    $tinymce = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "setting WHERE `code` = 'tinymce' AND `key` = 'tinymce_status' AND `value` = '1'");

    if (empty($tinymce->num_rows)) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_status', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_height', `value` = '300'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_sort_order', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_edit_undo', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_edit_redo', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_bold', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_italic', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_view_alignleft', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_view_aligncenter', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_view_alignright', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_view_alignjustify', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_file_bullist', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_file_numlist', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_file_outdent', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_file_indent', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_insert_link', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_insert_image', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_tools_imagetools', `value` = '1'");
    }

    // Update length_class_description table
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "length_class_description` CHANGE length_class_id length_class_id INT(11) NOT NULL");

    // Update weight_class_description table
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "weight_class_description` CHANGE weight_class_id weight_class_id INT(11) NOT NULL");

    // Update information_description table
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "information_description` CHANGE description description MEDIUMTEXT NOT NULL");

    // Insert Module table Cart module => Modal after cart 
    $this->db->query("INSERT INTO " . DB_PREFIX . "module SET `name` = 'Cart - Modal after Add to Cart', `code` = 'cart', `setting` = 'a:15:{s:4:\"name\";s:30:\"Cart - Modal after Add to Cart\";s:5:\"popup\";s:1:\"1\";s:5:\"theme\";s:9:\"mini_cart\";s:6:\"status\";s:1:\"1\";s:13:\"product_image\";s:1:\"1\";s:12:\"product_name\";s:1:\"1\";s:13:\"product_model\";s:1:\"0\";s:16:\"product_quantity\";s:1:\"1\";s:13:\"product_price\";s:1:\"1\";s:13:\"product_total\";s:1:\"1\";s:15:\"button_continue\";s:1:\"1\";s:11:\"button_cart\";s:1:\"1\";s:15:\"button_checkout\";s:1:\"1\";s:6:\"coupon\";s:1:\"1\";s:7:\"message\";s:1:\"1\";}'");

    // Update layout_module table
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "layout_module` CHANGE `position` `position` varchar(64) NOT NULL");

    // Update coupon table
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon` CHANGE `code` `code` varchar(128) NOT NULL");
}

// 1.3.1 changes;
if (version_compare(VERSION, '1.3.1', '<')) {
    $tinymce = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "setting WHERE `code` = 'tinymce'");

    if (!empty($tinymce->num_rows)) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menubar', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_edit_insertfile', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_styleselect_styleselect', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_underline', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_visualchars', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_visualblocks', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_ltr', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_rtl', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_fontselect', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_fontsizeselect', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_charmap', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_forecolor', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_format_backcolor', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_file_table', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_insert_image_manager', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_insert_media', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_insert_hr', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_tools_fullscreen', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_tools_code', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_tools_searchreplace', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_tools_pagebreak', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_tools_nonbreaking', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'tinymce', `key` = 'tinymce_menu_tools_emoticons', `value` = '0'");
    }
}

// 1.3.2 changes;
if (version_compare(VERSION, '1.3.2', '<')) {
    $email_descriptions = $this->db->query("SELECT * FROM " . DB_PREFIX . "email_description");

    foreach ($email_descriptions->rows as $email_description) {
        // Store logo short code changed
        $description = str_replace('&lt;a href=&quot;{site_url}&quot; title=&quot;{store_name}&quot;&gt; &lt;img src=&quot;{logo}&quot;&gt; &lt;/a&gt;', '{store_logo}', $email_description['description']);

        // Product image short code changed
        $description = str_replace('&lt;img src=&quot;{product_image}&quot; style=&quot;width: 50px;height: 50px;padding: auto;&quot;&gt;', '{product_image}', $description);

        // Order href code changed
        $description = str_replace('<a href="{order_href}" target="_blank">{order_href}</a>', '{order_href}', $description);
        $description = str_replace('&lt;a href=&quot;{order_href}&quot; target=&quot;_blank&quot;>{order_href} &lt;/a&gt;', '{order_href}', $description);

        $this->db->query("UPDATE `" . DB_PREFIX . "email_description` SET `description` = '" . $this->db->escape($description) . "' WHERE `id` = '" . $email_description['id'] . "';");
    }
}

// 1.4.0 changes;
if (version_compare(VERSION, '1.4.0', '<')) {
    // Update banner_image table
    $banner_image = array();

    $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "banner_image`");

    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $banner_image[] = $row['Field'];
        }
    }

    if (!in_array('language_id', $banner_image)) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD `language_id` INT(11) NOT NULL AFTER `banner_id`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image`  ADD `title` VARCHAR( 64 ) NOT NULL AFTER `language_id`");
    }

    $banners = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner WHERE 1");

    if ($banners->num_rows) {
        $banner_image_data = array();

        foreach ($banners->rows as $banner) {
            $banner_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner['banner_id'] . "' ORDER BY sort_order ASC");

            foreach ($banner_image_query->rows as $banner_image) {
                $banner_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner['banner_id'] . "'");

                foreach ($banner_image_description_query->rows as $banner_image_description) {
                    $banner_image_data[$banner['banner_id']][$banner_image_description['language_id']][] = array(
                        'banner_id'  => $banner['banner_id'],
                        'title'      => $banner_image_description['title'],
                        'link'       => $banner_image['link'],
                        'image'      => $banner_image['image'],
                        'sort_order' => $banner_image['sort_order']
                    );
                }
            }
        }

        // Truncate banner_image table
        $this->db->query("TRUNCATE `" . DB_PREFIX . "banner_image`");

        if ($banner_image_data) {
            foreach ($banner_image_data as $banner_id => $banner_image_value) {
                foreach ($banner_image_value as $language_id => $value) {
                    foreach ($value as $banner_image) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($banner_image['title']) . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', sort_order = '" .  (int)$banner_image['sort_order'] . "'");
                    }
                }
            }
        }
    }

    // Delete banner_image_description table
    $this->db->query("DROP TABLE `" . DB_PREFIX . "banner_image_description`");

    // Customer Search
    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_search` (
        `customer_search_id` int(11) NOT NULL AUTO_INCREMENT,
        `store_id` int(11) NOT NULL,
        `language_id` int(11) NOT NULL,
        `customer_id` int(11) NOT NULL,
        `keyword` varchar(255) NOT NULL,
        `category_id` int(11),
        `sub_category` tinyint(1) NOT NULL,
        `description` tinyint(1) NOT NULL,
        `products` int(11) NOT NULL,
        `ip` varchar(40) NOT NULL,
        `date_added` datetime NOT NULL,
        PRIMARY KEY (`customer_search_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    // Insert setting table
    if ($this->config->get('config_customer_search', null) == null) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `store_id` = 0, `code` = 'config', `key` = 'config_customer_search', `value` = '0', `serialized` = 0");
    }

    // Update user groups
    $user_groups = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group");

    foreach ($user_groups->rows as $user_group) {
        $user_group['permission'] = unserialize($user_group['permission']);

        $user_group['permission']['access'][] = 'report/customer_search';
        $user_group['permission']['modify'][] = 'report/customer_search';

        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($user_group['name']) . "', permission = '" . $this->db->escape(serialize($user_group['permission'])) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'");
    }
}

// 1.4.1 changes;
if (version_compare(VERSION, '1.4.1', '<')) {
    if ($this->config->get('config_customer_activity', null) == null) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_customer_activity', `value` = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_affiliate_activity', `value` = '0'");
    }
}

// 1.5.0 changes;
if (version_compare(VERSION, '1.5.0', '<')) {
    // Update the user groups
    $user_groups = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group");

    foreach ($user_groups->rows as $user_group) {
        $user_group['permission'] = unserialize($user_group['permission']);

        $user_group['permission']['access'][] = 'tool/cache';
        $user_group['permission']['modify'][] = 'tool/cache';

        $user_group['permission']['access'][] = 'report/graph';
        $user_group['permission']['modify'][] = 'report/graph';

        $user_group['permission']['access'][] = 'system/url_manager';
        $user_group['permission']['modify'][] = 'system/url_manager';

        $user_group['permission']['access'][] = 'analytics/google';
        $user_group['permission']['modify'][] = 'analytics/google';

        $user_group['permission']['access'][] = 'analytics/woopra';
        $user_group['permission']['modify'][] = 'analytics/woopra';

        $user_group['permission']['access'][] = 'antifraud/maxmind';
        $user_group['permission']['modify'][] = 'antifraud/maxmind';

        $user_group['permission']['access'][] = 'captcha/basic';
        $user_group['permission']['modify'][] = 'captcha/basic';

        $user_group['permission']['access'][] = 'captcha/google';
        $user_group['permission']['modify'][] = 'captcha/google';

        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($user_group['name']) . "', permission = '" . $this->db->escape(serialize($user_group['permission'])) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'");
    }

    // Create user_activity table
    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "user_activity` (
        `activity_id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `key` varchar(64) NOT NULL,
        `data` text NOT NULL,
        `ip` varchar(40) NOT NULL,
        `date_added` datetime NOT NULL,
        PRIMARY KEY (`activity_id`),
        KEY `user_id` (`user_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    // Update stock_status table
    $stock_status = array();

    $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "stock_status`");

    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $stock_status[] = $row['Field'];
        }
    }

    if (!in_array('preorder', $stock_status)) {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "stock_status` ADD `preorder` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `color`");
    }

    // Add maintenance display settings
    if ($this->config->get('config_image_maintenance_width', null) == null) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_maintenance_message', `value` = ''");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_maintenance_image', `value` = ''");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_maintenance_login', `value` = '1'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_image_maintenance_width', `value` = '268'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_image_maintenance_height', `value` = '50'");
    }

    // Update config for new extension types: analytics, anti-fraud, captcha
    $this->load->model('setting/setting');

    $store_id = $this->config->get('config_store_id');

    $config = $this->model_setting_setting->getSetting('config', $store_id);

    if ($this->config->get('config_google_captcha_status')) {
        $data = array();

        $data['google_captcha_status'] = '1';
        $data['google_captcha_public'] = $this->config->get('config_google_captcha_public');
        $data['google_captcha_secret'] = $this->config->get('config_google_captcha_secret');

        $this->model_setting_setting->editSetting('google_captcha', $data, $store_id);

        $config['config_captcha'] = 'google';

        $this->model_setting_setting->editSetting('config', $config, $store_id);
    } else {
        $config['config_captcha'] = '';

        $this->model_setting_setting->editSetting('config', $config, $store_id);
    }

    if ($this->config->get('config_google_analytics_status')) {
        $data = array();

        $data['google_analytics_status'] = '1';
        $data['google_analytics_code'] = $this->config->get('config_google_analytics');

        $this->model_setting_setting->editSetting('google_analytics', $data, $store_id);
    }

    if ($this->config->get('config_fraud_detection')) {
        $data = array();

        $data['maxmind_antifraud_status'] = '1';
        $data['maxmind_antifraud_key'] = $this->config->get('config_fraud_key');
        $data['maxmind_antifraud_score'] = $this->config->get('config_fraud_score');
        $data['maxmind_antifraud_status_id'] = $this->config->get('config_fraud_status_id');

        $this->model_setting_setting->editSetting('maxmind_antifraud', $data, $store_id);
    }
}

// 1.5.1 changes;
if (version_compare(VERSION, '1.5.1', '<')) {
    $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'basic_status'");
    $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'google_status'");
    $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'maxmind_status'");
    $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'woopra_status'");
}

// 1.5.2 changes;
if (version_compare(VERSION, '1.5.2', '<')) {
    // remove command folder
    $this->filesystem->remove(DIR_SYSTEM . 'library/command');
}

// 1.6.0 changes;
if (version_compare(VERSION, '1.6.0', '<')) {
    // Add CSRF setting
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_sec_csrf', `value` = 'a:7:{i:0;s:20:\"account/address/edit\";i:1;s:12:\"account/edit\";i:2;s:18:\"account/newsletter\";i:3;s:16:\"account/password\";i:4;s:14:\"affiliate/edit\";i:5;s:18:\"affiliate/password\";i:6;s:17:\"affiliate/payment\";}', `serialized` = '1'");

    // Add blog tables
    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post` (
        `post_id` int(11) NOT NULL AUTO_INCREMENT,
        `allow_comment` int(1) NOT NULL DEFAULT '1',
        `featured` int(1) NOT NULL DEFAULT '0',
        `viewed` int(11) NOT NULL DEFAULT '0',
        `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `date_available` date NOT NULL DEFAULT '0000-00-00',
        `sort_order` int(3) NOT NULL,
        `status` int(1) NOT NULL DEFAULT '1',
        `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`post_id`),
        KEY `allow_comment` (`allow_comment`),
        KEY `viewed` (`viewed`),
        KEY `author` (`author`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_description` (
        `post_id` int(11) NOT NULL,
        `language_id` int(11) NOT NULL,
        `name` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `tag` text NOT NULL,
        `meta_title` varchar(255) NOT NULL,
        `meta_description` varchar(255) NOT NULL,
        `meta_keyword` varchar(255) NOT NULL,
        PRIMARY KEY (`post_id`,`language_id`),
        KEY `name` (`name`),
        KEY `language_id` (`language_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_to_category` (
        `post_id` int(11) NOT NULL,
        `category_id` int(11) NOT NULL,
        PRIMARY KEY (`post_id`,`category_id`),
        KEY `category_id` (`category_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_to_layout` (
        `post_id` int(11) NOT NULL,
        `store_id` int(11) NOT NULL,
        `layout_id` int(11) NOT NULL,
        PRIMARY KEY (`post_id`,`store_id`),
        KEY `store_id` (`store_id`),
        KEY `layout_id` (`layout_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_post_to_store` (
        `post_id` int(11) NOT NULL,
        `store_id` int(11) NOT NULL,
        PRIMARY KEY (`post_id`,`store_id`),
        KEY `store_id` (`store_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_comment` (
        `comment_id` int(11) NOT NULL AUTO_INCREMENT,
        `post_id` int(11) NOT NULL,
        `customer_id` int(11) NOT NULL,
        `email` varchar(96) NOT NULL,
        `author` varchar(64) NOT NULL,
        `text` text NOT NULL,
        `status` tinyint(1) NOT NULL DEFAULT '0',
        `date_added` datetime NOT NULL,
        `date_modified` datetime NOT NULL,
        PRIMARY KEY (`comment_id`),
        KEY `post_id` (`post_id`),
        KEY `customer_id` (`customer_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category` (
        `category_id` int(11) NOT NULL AUTO_INCREMENT,
        `image` varchar(255) DEFAULT NULL,
        `parent_id` int(11) NOT NULL DEFAULT '0',
        `top` tinyint(1) NOT NULL,
        `sort_order` int(3) NOT NULL DEFAULT '0',
        `status` int(1) NOT NULL DEFAULT '1',
        `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`category_id`),
        KEY `parent_id` (`parent_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_description` (
        `category_id` int(11) NOT NULL,
        `language_id` int(11) NOT NULL,
        `name` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `meta_title` varchar(255) NOT NULL,
        `meta_description` varchar(255) NOT NULL,
        `meta_keyword` varchar(255) NOT NULL,
        PRIMARY KEY (`category_id`,`language_id`),
        KEY `name` (`name`),
        KEY `language_id` (`language_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_path` (
        `category_id` int(11) NOT NULL,
        `path_id` int(11) NOT NULL,
        `level` int(11) NOT NULL,
        PRIMARY KEY (`category_id`,`path_id`),
        KEY `path_id` (`path_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_to_layout` (
        `category_id` int(11) NOT NULL,
        `store_id` int(11) NOT NULL,
        `layout_id` int(11) NOT NULL,
        PRIMARY KEY (`category_id`,`store_id`),
        KEY `store_id` (`store_id`),
        KEY `layout_id` (`layout_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "blog_category_to_store` (
        `category_id` int(11) NOT NULL,
        `store_id` int(11) NOT NULL,
        PRIMARY KEY (`category_id`,`store_id`),
        KEY `store_id` (`store_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

    // Update the user groups
    $user_groups = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group");

    foreach ($user_groups->rows as $user_group) {
        $user_group['permission'] = unserialize($user_group['permission']);

        $user_group['permission']['access'][] = 'blog/category';
        $user_group['permission']['modify'][] = 'blog/category';

        $user_group['permission']['access'][] = 'blog/comment';
        $user_group['permission']['modify'][] = 'blog/comment';

        $user_group['permission']['access'][] = 'blog/post';
        $user_group['permission']['modify'][] = 'blog/post';

        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($user_group['name']) . "', permission = '" . $this->db->escape(serialize($user_group['permission'])) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'");
    }

    // Add Blog Layout
    $this->db->query("INSERT INTO " . DB_PREFIX . "layout SET name = 'Blog Home'");

    $layout_id = $this->db->getLastId();

    $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', `store_id` = '0', `route` = 'blog/home'");

    // Add Module
    $this->db->query("INSERT INTO " . DB_PREFIX . "module SET name = 'Recent posts', `code` = 'blog_latest', `setting` = 'a:5:{s:4:\"name\";s:12:\"Recent posts\";s:5:\"limit\";s:1:\"5\";s:5:\"width\";s:2:\"60\";s:6:\"height\";s:2:\"40\";s:6:\"status\";s:1:\"1\";}'");

    $module_id = $this->db->getLastId();

    // Add Blog Modules
    $this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$layout_id . "', `code` = 'blog_latest." .$module_id ."', `position` = 'column_right', `sort_order` = '0'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "layout SET name = 'Blog Post'");

    $layout_id = $this->db->getLastId();

    $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', `store_id` = '0', `route` = 'blog/post'");

    // Add Module
    $this->db->query("INSERT INTO " . DB_PREFIX . "module SET name = 'Recent comments', `code` = 'blog_comment', `setting` = 'a:7:{s:4:\"name\";s:15:\"Recent comments\";s:5:\"limit\";s:1:\"2\";s:18:\"description_length\";s:3:\"300\";s:5:\"image\";s:1:\"1\";s:5:\"width\";s:2:\"40\";s:6:\"height\";s:2:\"40\";s:6:\"status\";s:1:\"1\";}'");

    $module_id = $this->db->getLastId();

    // Add Blog Modules
    $this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$layout_id . "', `code` = 'blog_comment." .$module_id ."', `position` = 'column_right', `sort_order` = '0'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "layout SET name = 'Blog Category'");

    $layout_id = $this->db->getLastId();

    $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', `store_id` = '0', `route` = 'blog/category'");

    // Add Blog Items
    // Add Blog Category
    $this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category` SET `image` = '', `parent_id` = '0', `top` = '0', `sort_order` = '0', `status` = '1', `date_added` = '2017-04-06 14:06:13', `date_modified` = '0000-00-00 00:00:00'");

    $blog_category_id = $this->db->getLastId();

    $this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_description` SET `category_id` = " . (int)$blog_category_id . ", `language_id` = '1', `name` = 'General', `description` = '', `meta_title` = 'General', `meta_description` = '', `meta_keyword` = ''");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_path SET category_id = '" . (int)$blog_category_id . "', `path_id` = '1', `level` = '0'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_to_layout SET category_id = '" . (int)$blog_category_id . "', `store_id` = '0', `layout_id` = '0'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_to_store SET category_id = '" . (int)$blog_category_id . "', `store_id` = '0'");

    // Add Blog Category Url Alias
    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'blog_category_id=" . (int)$blog_category_id . "', `keyword` = 'general', `language_id` = '1'");

    // Add Blog Post
    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post SET `allow_comment` = '1', `featured` = '1', `viewed` = '37', `image` = 'catalog/demo/blog/yet-another.jpg', `author` = 'Cüneyt Şentürk', `date_available` = '2017-04-06', `sort_order` = '0', `status` = '1', `date_added` = '2017-04-06 14:13:54', `date_modified` = '2017-04-06 14:26:07'");

    $blog_post_id = $this->db->getLastId();

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_description SET post_id = '" . (int)$blog_post_id . "', `language_id` = '1', `name` = 'Yet another great post', `description` = '&lt;p&gt;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.&lt;/p&gt;\r\n&lt;h1&gt;HTML Ipsum Presents&lt;/h1&gt;\r\n&lt;p&gt;&lt;strong&gt;Pellentesque habitant morbi tristique&lt;/strong&gt; senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. &lt;em&gt;Aenean ultricies mi vitae est.&lt;/em&gt; Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, &lt;code&gt;commodo vitae&lt;/code&gt;, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. &lt;a href=&quot;#&quot;&gt;Donec non enim&lt;/a&gt; in turpis pulvinar facilisis. Ut felis.&lt;/p&gt;\r\n&lt;h2&gt;Header Level 2&lt;/h2&gt;\r\n&lt;ol&gt;\r\n&lt;li&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit.&lt;/li&gt;\r\n&lt;li&gt;Aliquam tincidunt mauris eu risus.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;blockquote&gt;\r\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.&lt;/p&gt;\r\n&lt;/blockquote&gt;\r\n&lt;h3&gt;Header Level 3&lt;/h3&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit.&lt;/li&gt;\r\n&lt;li&gt;Aliquam tincidunt mauris eu risus.&lt;/li&gt;\r\n&lt;/ul&gt;', `tag` = 'tag 1,tag 2,tag 3', `meta_title` = 'Yet another great post', `meta_description` = '', `meta_keyword` = ''");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_category SET post_id = '" . (int)$blog_post_id . "', category_id = '" . (int)$blog_category_id . "'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_layout SET post_id = '" . (int)$blog_post_id . "', `store_id` = '0', `layout_id` = '0'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_store SET post_id = '" . (int)$blog_post_id . "', `store_id` = '0'");

    // Add Blog Comment
    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET `post_id` = '" . (int)$blog_post_id . "', `customer_id` = '0', `email` = 'test@test.com', `author` = 'Denis', `text` = 'Hey, this is cool! I like it.', `status` = '1', `date_added` = '2017-04-06 14:54:56', `date_modified` = '0000-00-00 00:00:00'");

    // Add Blog Post Url Alias
    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'blog_post_id=" . (int)$blog_post_id . "', `keyword` = 'yet-another-great-post', `language_id` = '1'");

    // Add Blog Post
    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post SET `allow_comment` = '1', `featured` = '0', `viewed` = '10', `image` = 'catalog/demo/blog/arastta.jpg', `author` = 'Enes Ertuğrul', `date_available` = '2017-04-06', `sort_order` = '0', `status` = '1', `date_added` = '2017-04-06 14:31:03', `date_modified` = '2017-04-06 15:34:24'");

    $blog_post_id = $this->db->getLastId();

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_description SET post_id = '" . (int)$blog_post_id . "', `language_id` = '1', `name` = 'Arastta 1.6 Released', `description` = '&lt;p&gt;Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.&lt;/p&gt;\r\n&lt;h1&gt;HTML Ipsum Presents&lt;/h1&gt;\r\n&lt;p&gt;&lt;strong&gt;Pellentesque habitant morbi tristique&lt;/strong&gt; senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. &lt;em&gt;Aenean ultricies mi vitae est.&lt;/em&gt; Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, &lt;code&gt;commodo vitae&lt;/code&gt;, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. &lt;a href=&quot;#&quot;&gt;Donec non enim&lt;/a&gt; in turpis pulvinar facilisis. Ut felis.&lt;/p&gt;\r\n&lt;h2&gt;Header Level 2&lt;/h2&gt;\r\n&lt;ol&gt;\r\n&lt;li&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit.&lt;/li&gt;\r\n&lt;li&gt;Aliquam tincidunt mauris eu risus.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;blockquote&gt;\r\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.&lt;/p&gt;\r\n&lt;/blockquote&gt;\r\n&lt;h3&gt;Header Level 3&lt;/h3&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit.&lt;/li&gt;\r\n&lt;li&gt;Aliquam tincidunt mauris eu risus.&lt;/li&gt;\r\n&lt;/ul&gt;', `tag` = '', `meta_title` = 'Arastta 1.6 Released', `meta_description` = '', `meta_keyword` = ''");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_category SET post_id = '" . (int)$blog_post_id . "', category_id = '" . (int)$blog_category_id . "'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_layout SET post_id = '" . (int)$blog_post_id . "', `store_id` = '0', `layout_id` = '0'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_store SET post_id = '" . (int)$blog_post_id . "', `store_id` = '0'");

    // Add Blog Comment
    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment SET `post_id` = '" . (int)$blog_post_id . "', `customer_id` = '0', `email` = 'test@test2.com', `author` = 'Cüneyt Şentürk', `text` = 'This release is awesome! I will update my store as soon as possible.', `status` = '1', `date_added` = '2017-04-06 14:55:32', `date_modified` = '0000-00-00 00:00:00'");

    // Add Blog Post Url Alias
    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'blog_post_id=" . (int)$blog_post_id . "', `keyword` = 'arastta-1-6-released', `language_id` = '1'");

    // Add Blog Post
    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post SET `allow_comment` = '1', `featured` = '1', `viewed` = '5', `image` = 'catalog/demo/blog/big-thing-arastta.jpg', `author` = 'Denis Duliçi', `date_available` = '2017-04-06', `sort_order` = '0', `status` = '1', `date_added` = '2017-04-06 15:00:51', `date_modified` = '0000-00-00 00:00:00'");

    $blog_post_id = $this->db->getLastId();

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_description SET post_id = '" . (int)$blog_post_id . "', `language_id` = '1', `name` = 'The next big Arastta thing ;)', `description` = '&lt;p&gt;Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.&lt;/p&gt;\r\n&lt;h1&gt;HTML Ipsum Presents&lt;/h1&gt;\r\n&lt;p&gt;&lt;strong&gt;Pellentesque habitant morbi tristique&lt;/strong&gt; senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. &lt;em&gt;Aenean ultricies mi vitae est.&lt;/em&gt; Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, &lt;code&gt;commodo vitae&lt;/code&gt;, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. &lt;a href=&quot;#&quot;&gt;Donec non enim&lt;/a&gt; in turpis pulvinar facilisis. Ut felis.&lt;/p&gt;\r\n&lt;h2&gt;Header Level 2&lt;/h2&gt;\r\n&lt;ol&gt;\r\n&lt;li&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit.&lt;/li&gt;\r\n&lt;li&gt;Aliquam tincidunt mauris eu risus.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;blockquote&gt;\r\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.&lt;/p&gt;\r\n&lt;/blockquote&gt;\r\n&lt;h3&gt;Header Level 3&lt;/h3&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit.&lt;/li&gt;\r\n&lt;li&gt;Aliquam tincidunt mauris eu risus.&lt;/li&gt;\r\n&lt;/ul&gt;', `tag` = '', `meta_title` = 'The next big Arastta thing ;)', `meta_description` = '', `meta_keyword` = ''");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_category SET post_id = '" . (int)$blog_post_id . "', category_id = '" . (int)$blog_category_id . "'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_layout SET post_id = '" . (int)$blog_post_id . "', `store_id` = '0', `layout_id` = '0'");

    $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_store SET post_id = '" . (int)$blog_post_id . "', `store_id` = '0'");

    // Add Blog Post Url Alias
    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'blog_post_id=" . (int)$blog_post_id . "', `keyword` = 'the-next-big-arastta-thing', `language_id` = '1'");

    // Add Blog Home Url Alias
    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'route=blog/home', `keyword` = 'blog', `language_id` = '1'");

    // Add setting table
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_width', `value` = '600'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_height', `value` = '400'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_form_width', `value` = '600'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_form_height', `value` = '400'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_name', `value` = 'Arastta Blog'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_description', `value` = '&lt;p&gt;Welcome to the Arastta Blog\'s home&lt;/p&gt;'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_featured_slide', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_meta_title', `value` = 'Arastta Blog'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_meta_description', `value` = ''");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_meta_keyword', `value` = ''");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_limit', `value` = '5'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_description_length', `value` = '300'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_sort_order', `value` = 'p.date_added-DESC'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_date', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_comment', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_read', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_list_author', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_form_date', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_form_comment', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_form_read', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_form_author', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_post_form_share', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_comment_enable', `value` = '1'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_comment_status', `value` = '0'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_comment_guest', `value` = '0'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_comment_limit', `value` = '5'");
    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = 'config_blog_comment_mail', `value` = '1'");
}

// 1.7.0 changes;
if (version_compare(VERSION, '1.7.0', '<')) {
    $this->db->query("ALTER TABLE `" . DB_PREFIX . "setting` MODIFY `code` VARCHAR(64) NOT NULL");

    $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `maximum` INT(11) NOT NULL DEFAULT '0' AFTER `minimum`");
}
