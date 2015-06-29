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
