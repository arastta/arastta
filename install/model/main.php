<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ModelMain extends Model {

	public function checkRequirements(){
		$errors = array();

        if (version_compare(PHP_VERSION, '5.3.10', '<')) {
            $errors[] = 'Warning: PHP 5.3.10 or above needs to be used!';
		}

		if (ini_get('safe_mode')) {
            $errors[] = 'Warning: Safe Mode needs to be disabled!';
		}

		if (ini_get('register_globals')) {
            $errors[] = 'Warning: Register Globals needs to be disabled!';
		}

		if (ini_get('magic_quotes_gpc')) {
            $errors[] = 'Warning: Magic Quotes needs to be disabled!';
		}

		if (!ini_get('file_uploads')) {
            $errors[] = 'Warning: File Uploads needs to be enabled!';
		}

		if (ini_get('session.auto_start')) {
            $errors[] = 'Warning: Session Auto Start needs to be disabled!';
		}

		if (!extension_loaded('mysqli')) {
            $errors[] = 'Warning: MySQLi extension needs to be loaded!';
		}

		if (!extension_loaded('gd')) {
            $errors[] = 'Warning: GD extension needs to be loaded!';
		}

		if (!extension_loaded('curl')) {
            $errors[] = 'Warning: cURL extension needs to be loaded!';
		}

		if (!function_exists('mcrypt_encrypt')) {
            $errors[] = 'Warning: mCrypt extension needs to be loaded!';
		}

		if (!extension_loaded('zlib')) {
            $errors[] = 'Warning: Zlib extension needs to be loaded!';
		}

		if (!function_exists('iconv')) {
			if (!extension_loaded('mbstring')) {
				$errors[] = 'Warning: mbstring extension needs to be loaded!';
			}
		}
		
		if (!is_writable(DIR_SYSTEM . 'cache')) {
			$errors[] = 'Warning: Cache directory needs to be writable!';
		}

		if (!is_writable(DIR_SYSTEM . 'log')) {
			$errors[] = 'Warning: Logs directory needs to be writable!';
		}

		if (!is_writable(DIR_ROOT . 'download')) {
			$errors[] = 'Warning: Download directory needs to be writable!';
		}
		
		if (!is_writable(DIR_ROOT . 'upload')) {
			$errors[] = 'Warning: Upload directory needs to be writable!';
		}
		
		if (!is_writable(DIR_IMAGE)) {
			$errors[] = 'Warning: Image directory needs to be writable!';
		}

		if (!is_writable(DIR_IMAGE . 'cache')) {
			$errors[] = 'Warning: Image cache directory needs to be writable!';
		}

		if (!is_writable(DIR_IMAGE . 'catalog')) {
			$errors[] = 'Warning: Image catalog directory needs to be writable!';
		}

		return empty($errors) ? '' : $errors;
	}
}