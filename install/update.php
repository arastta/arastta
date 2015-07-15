<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */
 
// Version 1.0.3 changes;
if(version_compare($version, '1.0.3', '<')) {
    // Delete language english directory
    $this->filesystem->remove(DIR_LANGUAGE . 'english');
    $this->filesystem->remove(DIR_CATALOG . 'language/english');
	
	// Update language directory name
	$this->db->query("UPDATE `" . DB_PREFIX . "language` SET `directory` = 'en-GB' WHERE `code` = 'en';");

	// Add field ('params') Addon table
	$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "addon`");
	
	if(!in_array('params', $query->rows)) {
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "addon` ADD COLUMN `params` TEXT DEFAULT NULL");
	}
}

// Version 1.1.0 changes;
if(version_compare($version, '1.1.0', '<')) {
	$user_groups = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group");

	foreach ($user_groups->rows as $user_group) {
		$user_group['permission'] = unserialize($user_group['permission']);
		
		$user_group['permission']['access'][] = 'tool/system_info';
		$user_group['permission']['modify'][] = 'tool/system_info';
		
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
}
