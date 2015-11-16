<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ModelAppearanceTheme extends Model {
	public function editTheme($theme, $data) {
		$this->trigger->fire('pre.admin.theme.edit', array(&$theme));

		$this->trigger->fire('pre.admin.theme.edit', array(&$theme));
	}

	public function deleteTheme($theme) {
		$this->trigger->fire('pre.admin.theme.delete', array(&$theme));

		$this->cache->delete('theme');

		$this->trigger->fire('post.admin.theme.delete', array(&$theme));
	}

	public function getTheme($theme) {
		return $themes = array(
			'theme'       => $theme,
			'name'        => 'Test Name',
			'author'      => 'Cüneyt Þentürk',
			'description' => 'Test Description',
			'version'	  => '1.0.0',
			'status'      => 1
		);
	}

	public function getThemes() {
        $themes = array();

        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

		$sort = 1;
        foreach($directories as $directory) {
			$key = 0;

			if ($this->config->get('config_template') != basename($directory)) {
				$key = $sort;
				$sort++;
			}

			$themes[$key] = array(
				'theme'       => basename($directory),
				'name'        => 'Test Name',
				'author'      => 'Cüneyt Þentürk',
				'description' => 'Test Description',
				'status'      => 1
			);
        }

		ksort($themes);

		return $themes;
	}

	public function getTotalThemes($data = array()) {
        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

        return count($directories);
	}
}