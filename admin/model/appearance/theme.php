<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ModelAppearanceTheme extends Model {
	public function addTheme($data) {
		$this->trigger->fire('pre.admin.theme.edit', array(&$data));

		$this->db->query("INSERT INTO " . DB_PREFIX . "theme SET code = '" . $this->db->escape($data['info']['theme']['code']) . "', files = '" . $this->db->escape(json_encode($data['files'])) . "', info = '" . $this->db->escape(json_encode($data['info'])) . "', params = '" . $this->db->escape(json_encode($data['params'])) . "'");

		$theme_id = $this->db->getLastId();

		$this->trigger->fire('pre.admin.theme.edit', array(&$data));

		return $theme_id;
	}

	public function editTheme($theme_id, $data) {
		$this->trigger->fire('pre.admin.theme.edit', array(&$data));

		$this->db->query("UPDATE " . DB_PREFIX . "theme SET code = '" . $this->db->escape($data['code']) . "', files = '" . $this->db->escape(json_encode($data['files'])) . "', info = '" . $this->db->escape(json_encode($data['info'])) . "', params = '" . $this->db->escape(json_encode($data['params'])) . "' WHERE theme_id = '" . (int)$theme_id . "'");

		$this->trigger->fire('pre.admin.theme.edit', array(&$theme_id));

		return $theme_id;
	}

	public function deleteTheme($code) {
		$this->trigger->fire('pre.admin.theme.delete', array(&$code));

		$this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE `code` = '" . $code . "'");
		$this->cache->delete('theme');

		$this->trigger->fire('post.admin.theme.delete', array(&$code));
	}

	public function getTheme($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "theme WHERE code = '" . $code . "'");

		$result = $query->row;
		$info = json_decode($result['info'], true);

		return $theme = array(
			'code'        => $result['code'],
			'author'      => $info['author']['name'],
			'name'        => $info['theme']['name'],
			'description' => $info['theme']['description'],
			'version' 	  => $info['theme']['version'],
			'product_id'  => $info['theme']['product_id'],
			'status'	  => 1
		);
	}

	public function getThemes() {
        $themes = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "theme");

		$sort = 1;

        foreach($query->rows as $theme) {
			$key = 0;

			if ($this->config->get('config_template') != $theme['code']) {
				$key = $sort;
				$sort++;
			}

			$info = json_decode($theme['info'], true);

			$themes[$key] = array(
				'code'        => $theme['code'],
				'author'      => $info['author']['name'],
				'name'        => $info['theme']['name'],
				'description' => $info['theme']['description'],
				'version' 	  => $info['theme']['version'],
				'product_id'  => $info['theme']['product_id'],
				'status'	  => 1
			);
        }

		ksort($themes);

		return $themes;
	}

	public function getTotalThemes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "theme");

		return $query->row['total'];
	}
}