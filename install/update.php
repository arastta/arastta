<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
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
	$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "addon`");
	
	if (!in_array('params', $query->rows)) {
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
	$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `username` VARCHAR(100)");
	$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `password` VARCHAR(100)");
	$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `email` VARCHAR(100)");
	
	// Update stock status table
	$this->db->query("ALTER TABLE `" . DB_PREFIX . "stock_status` ADD `color` VARCHAR(32) NOT NULL AFTER `name`");
	
	// Added stock status default color 
	$this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#FF0000' WHERE `stock_status_id` = '5'");
	$this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#FFA500' WHERE `stock_status_id` = '6'");
	$this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#008000' WHERE `stock_status_id` = '7'");
	$this->db->query("UPDATE `" . DB_PREFIX . "stock_status` SET `color` = '#FFFF00' WHERE `stock_status_id` = '8'");
	
	// Extension/Theme manager
	$this->db->query("ALTER TABLE `" . DB_PREFIX . "addon` CHANGE `addon_files` `files` TEXT NULL");
	$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension` ADD `info` TEXT NULL AFTER `code`, ADD `params` TEXT NULL AFTER `info`");
	$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "theme` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `info` text NULL,
  `params` text NULL,
  PRIMARY KEY (`theme_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;", 'query');

	// Update params of addons
	$addons = $this->db->query("SELECT * FROM `" . DB_PREFIX . "addon`")->rows;
	foreach ($addons as $addon) {
		$params = json_decode($addon['params'], true);
		
		// Update to language_id
		if (isset($params->['localisation/language'])) {
			$params['language_id'] = $params->['localisation/language'];
			unset($params->['localisation/language']);
		}
		
		// Set theme/extension ids
		$params['theme_ids'] = array();
		$params['extension_ids'] = array();
			
		$this->db->query("UPDATE `" . DB_PREFIX . "addon` SET `params` = '" . json_encode($params) . "' WHERE `addon_id` = '" . $addon['addon_id'] . "'");
	}
	
	// Update the user groups
	$user_groups = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group");

	foreach ($user_groups->rows as $user_group) {
		$user_group['permission'] = unserialize($user_group['permission']);
		
		$user_group['permission']['access'][] = 'extension/extension';
		$user_group['permission']['modify'][] = 'extension/extension';
		
		$user_group['permission']['access'][] = 'appearance/theme';
		$user_group['permission']['modify'][] = 'appearance/theme';
		
		$this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($user_group['name']) . "', permission = '" . $this->db->escape(serialize($user_group['permission'])) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'");
}