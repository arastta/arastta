<?php/** * @package		Arastta eCommerce * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org) * @license		GNU General Public License version 3; see LICENSE.txt */
class ModelExtensionExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
}