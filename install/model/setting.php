<?php
/**
 * @package         Arastta eCommerce
 * @copyright       Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits         See CREDITS.txt for credits and other copyright notices.
 * @license         GNU General Public License version 3; see LICENSE.txt
 */

class ModelSetting extends Model
{

    public function createDatabaseTables($data)
    {
        $this->session->data['store_name'] = $data['store_name'];
        $this->session->data['store_email'] = $data['store_email'];
        $this->session->data['admin_email'] = $data['admin_email'];
        $this->session->data['admin_password'] = $data['admin_password'];
        $this->session->data['install_demo_data'] = isset($data['install_demo_data']);

        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        $file = DIR_INSTALL . 'tables.sql';

        if (!file_exists($file)) {
            exit('Could not load sql file: ' . $file);
        }

        $lines = file($file);

        if (empty($lines)) {
            return false;
        }

        $sql = '';

        foreach ($lines as $line) {
            if (!$line || (substr($line, 0, 2) == '--') || (substr($line, 0, 1) == '#')) {
                continue;
            }

            $sql .= $line;

            if (!preg_match('/;\s*$/', $line)) {
                continue;
            }

            $sql = str_replace("DROP TABLE IF EXISTS `ar_", "DROP TABLE IF EXISTS `" . DB_PREFIX, $sql);
            $sql = str_replace("CREATE TABLE `ar_", "CREATE TABLE `" . DB_PREFIX, $sql);
            $sql = str_replace("CREATE TABLE IF NOT EXISTS `ar_", "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX, $sql);
            $sql = str_replace("INSERT INTO `ar_", "INSERT INTO `" . DB_PREFIX, $sql);

            $db->query($sql);

            $sql = '';
        }

        if (isset($data['install_demo_data'])) {
            $file = DIR_INSTALL . 'demo.sql';

            if (!file_exists($file)) {
                exit('Could not load sql file: ' . $file);
            }

            $lines = file($file);

            if (!empty($lines)) {
                $sql = '';

                foreach ($lines as $line) {
                    if (!$line || (substr($line, 0, 2) == '--') || (substr($line, 0, 1) == '#')) {
                        continue;
                    }

                    $sql .= $line;

                    if (!preg_match('/;\s*$/', $line)) {
                        continue;
                    }

                    $sql = str_replace("INSERT INTO `ar_", "INSERT INTO `" . DB_PREFIX, $sql);

                    $db->query($sql);

                    $sql = '';
                }
            }
        }

        $db->query("SET CHARACTER SET utf8");

        $db->query("SET @@session.sql_mode = 'MYSQL40'");
        
        // Check if admin uses Gravatar
        $gravatar = $this->utility->getRemoteData('https://www.gravatar.com/avatar/' . md5(strtolower($data['admin_email'])).'?size=45&d=404', array('timeout' => 3));

        if ($gravatar) {
            $user_image = '';
        } else {
            $user_image = 'admin-default.png';
        }
        
        $params = '{"theme":"basic","basic_mode_message":"show","language":"' . $this->session->data['lang_code'] . '","editor":"summernote"}';

        $db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '1'");

        $db->query("INSERT INTO `" . DB_PREFIX . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['admin_email']) . "', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($data['admin_password'])))) . "', image = '" . $db->escape($user_image) . "', email = '" . $db->escape($data['admin_email']) . "', params = '" . $db->escape($params) . "', status = '1', date_added = NOW()");

        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_name'");
        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_name', value = '" . $db->escape($data['store_name']) . "'");

        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_meta_title'");
        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_meta_title', value = '" . $db->escape($data['store_name']) . "'");

        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_email'");
        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['store_email']) . "'");

        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_url'");
        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_url', value = '" . $db->escape(HTTP_CATALOG) . "'");

        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_encryption'");
        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(md5(mt_rand())) . "'");

        $db->query("UPDATE `" . DB_PREFIX . "product` SET `viewed` = '0'");

        // create order API user
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $api_username = '';
        $api_password = '';

        for ($i = 0; $i < 64; $i++) {
            $api_username .= $characters[rand(0, strlen($characters) - 1)];
        }

        for ($i = 0; $i < 256; $i++) {
            $api_password .= $characters[rand(0, strlen($characters) - 1)];
        }

        $db->query("INSERT INTO `" . DB_PREFIX . "api` SET username = '" . $db->escape($api_username) . "', `password` = '" . $db->escape($api_password) . "', status = 1, date_added = NOW(), date_modified = NOW()");

        $api_id = $db->getLastId();

        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_api_id'");
        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");

        // Add language
        $lang_name = $this->session->data['lang_name'];
        $lang_code = $this->session->data['lang_code'];
        $lang_image = $this->session->data['lang_image'];
        $lang_directory = $this->session->data['lang_directory'];

        $db->query("INSERT INTO `" . DB_PREFIX . "language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `sort_order`, `status`)
                    VALUES (1, '" . $db->escape($lang_name) . "', '" . $db->escape($lang_code) . "', '', '" . $db->escape($lang_image) . "', '" . $db->escape($lang_directory) . "', 1, 1);");

        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_language', value = '" . $db->escape($lang_code) . "'");
        $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_admin_language', value = '" . $db->escape($lang_code) . "'");
    }
}
