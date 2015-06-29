<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ModelCatalogUrlAlias extends Model {

	public function getUrlAlias($keyword, $language_id = 1) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' AND language_id = '" . $this->db->escape($language_id) . "'");

		return $query->row;
	}

    public function generateAlias($title) {
        $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');

        $alias = $this->safeAlias($title);

        return $alias;
    }

    // From Joomla.Framework
    public function safeAlias($string) {
        // Replace double byte whitespaces by single byte (East Asian languages)
        $str = preg_replace('/\xE3\x80\x80/', ' ', $string);

        // Remove any '-' from the string as they will be used as concatenator.
        // Would be great to let the spaces in but only Firefox is friendly with this

        $str = str_replace('-', ' ', $str);

        // Replace forbidden characters by whitespaces
        $str = preg_replace('#[:\#\*"@+=;!><&\.%()\]\/\'\\\\|\[]#', "\x20", $str);

        // Delete all '?'
        $str = str_replace('?', '', $str);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(utf8_strtolower($str));

        // Remove any duplicate whitespace and replace whitespaces by hyphens
        $str = preg_replace('#\x20+#', '-', $str);

        return $str;
    }
}