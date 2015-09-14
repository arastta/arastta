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