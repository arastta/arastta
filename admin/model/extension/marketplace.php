<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ModelExtensionMarketplace extends Model {

	public function addMarketplace($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "marketplace SET code = '" . $this->db->escape($data['code']) . "', name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', version = '" . $this->db->escape($data['version']) . "', link = '" . $this->db->escape($data['link']) . "', xml = '" . $this->db->escape($data['xml']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function deleteMarketplace($marketplace_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "marketplace WHERE marketplace_id = '" . (int)$marketplace_id . "'");
	}

	public function enableMarketplace($marketplace_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "marketplace SET status = '1' WHERE marketplace_id = '" . (int)$marketplace_id . "'");
	}

	public function disableMarketplace($marketplace_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "marketplace SET status = '0' WHERE marketplace_id = '" . (int)$marketplace_id . "'");
	}

	public function getMarketplace($marketplace_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "marketplace WHERE marketplace_id = '" . (int)$marketplace_id . "'");

		return $query->row;
	}

	public function getMarketplaces($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "marketplace";

		$sort_data = array(
			'name',
			'author',
			'version',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
	}

	public function getTotalMarketplaces() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "marketplace");

		return $query->row['total'];
	}
	
	public function getMarketplaceByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "marketplace WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}	
}