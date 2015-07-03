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
	
	public function saveConfig($data) {
        $this->session->data['db_hostname'] = $data['db_hostname'];
        $this->session->data['db_username'] = $data['db_username'];
        $this->session->data['db_password'] = $data['db_password'];
        $this->session->data['db_database'] = $data['db_database'];

		$content  = '<?php' . "\n";
		$content .= '/**' . "\n";
		$content .= ' * @package		Arastta eCommerce' . "\n";
		$content .= ' * @copyright	    Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)' . "\n";
		$content .= ' * @license		GNU General Public License version 3; see LICENSE.txt' . "\n";
		$content .= ' */' . "\n";
		$content .= "\n";
		$content .= '// DB' . "\n";
		$content .= 'define(\'DB_DRIVER\', \'mysqli\');' . "\n";
		$content .= 'define(\'DB_HOSTNAME\', \'' . addslashes($data['db_hostname']) . '\');' . "\n";
		$content .= 'define(\'DB_USERNAME\', \'' . addslashes($data['db_username']) . '\');' . "\n";
		$content .= 'define(\'DB_PASSWORD\', \'' . addslashes($data['db_password']) . '\');' . "\n";
		$content .= 'define(\'DB_DATABASE\', \'' . addslashes($data['db_database']) . '\');' . "\n";
		$content .= 'define(\'DB_PREFIX\', \'' . addslashes($data['db_prefix']) . '\');' . "\n";
		
		try {
			$this->filesystem->dumpFile(DIR_ROOT . 'config.php', $content);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

    public function validateDatabaseConnection($data) {
        error_reporting(0);

        $conn = new MySQLi($data['db_hostname'], $data['db_username'], $data['db_password']);

        if ($conn->connect_error) {
            $conn->close();
            error_reporting(E_ALL);

            return false;
        }
        else {
			// Try to create database, if doesn't exist
			$sql = "CREATE DATABASE IF NOT EXISTS ".$data['db_database'];
			if ($conn->query($sql) === false) {
				// Couldn't create it, just check if exists
				$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$data['db_database']."'";
				if (!$conn->query($sql)->num_rows) {
					$conn->close();
					error_reporting(E_ALL);

					return false;
				}
			}
		
            $conn->close();
            error_reporting(E_ALL);

            return true;
        }
    }
	
	public function createDatabaseTables($data) {
        $this->session->data['store_name'] = $data['store_name'];
        $this->session->data['store_email'] = $data['store_email'];
        $this->session->data['admin_username'] = $data['admin_username'];
        $this->session->data['admin_email'] = $data['admin_email'];
        $this->session->data['admin_password'] = $data['admin_password'];
        $this->session->data['install_demo_data'] = isset($data['install_demo_data']);

        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		$file = DIR_APPLICATION . 'tables.sql';

		if (!file_exists($file)) {
			exit('Could not load sql file: ' . $file);
		}

		$lines = file($file);

		if ($lines) {
			$sql = '';

			foreach($lines as $line) {
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$sql .= $line;

					if (preg_match('/;\s*$/', $line)) {
						$sql = str_replace("DROP TABLE IF EXISTS `ar_", "DROP TABLE IF EXISTS `" . DB_PREFIX, $sql);
						$sql = str_replace("CREATE TABLE `ar_", "CREATE TABLE `" . DB_PREFIX, $sql);
						$sql = str_replace("CREATE TABLE IF NOT EXISTS `ar_", "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX, $sql);
						$sql = str_replace("INSERT INTO `ar_", "INSERT INTO `" . DB_PREFIX, $sql);

						$db->query($sql);

						$sql = '';
					}
				}
			}

			if (isset($data['install_demo_data'])) {
				$file = DIR_APPLICATION . 'demo.sql';

				if (!file_exists($file)) {
					exit('Could not load sql file: ' . $file);
				}

				$lines = file($file);

				foreach($lines as $line) {
					if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
						$sql .= $line;

						if (preg_match('/;\s*$/', $line)) {
							$sql = str_replace("INSERT INTO `ar_", "INSERT INTO `" . DB_PREFIX, $sql);

							$db->query($sql);

							$sql = '';
						}
					}
				}
			}

			$db->query("SET CHARACTER SET utf8");

			$db->query("SET @@session.sql_mode = 'MYSQL40'");

			$db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '1'");

			$db->query("INSERT INTO `" . DB_PREFIX . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['admin_username']) . "', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($data['admin_password'])))) . "', firstname = 'Ada', lastname = 'Bulut', image = 'catalog/demo/ada-bulut.png', email = '" . $db->escape($data['admin_email']) . "', status = '1', date_added = NOW()");

            $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_name'");
            $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_name', value = '" . $db->escape($data['store_name']) . "'");

            $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_meta_title'");
            $db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_meta_title', value = '" . $db->escape($data['store_name']) . "'");

			$db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_email'");
			$db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['store_email']) . "'");

			$db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_url'");
			$db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_url', value = '" . $db->escape(HTTP_CATALOG) . "'");

			$db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_encryption'");
			$db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(md5(mt_rand())) . "'");

			$db->query("UPDATE `" . DB_PREFIX . "product` SET `viewed` = '0'");

			// create order API user
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$api_username = '';
			$api_password = '';

			for ($i = 0; $i < 64; $i++) {
				$api_username .= $characters[rand(0, strlen($characters) - 1)];
			}

			for ($i = 0; $i < 256; $i++) {
				$api_password .= $characters[rand(0, strlen($characters) - 1)];
			}

			$db->query("INSERT INTO `" . DB_PREFIX . "api` SET username = '" . $db->escape($api_username) . "', `password` = '" . $db->escape($api_password) . "', status = 1, date_added = NOW(), date_modified = NOW()");

			$api_id = $db->getLastId();

			$db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_api_id'");
			$db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");
		}
	}
	
	public function removeInstall() {
		$dirs = array(
			DIR_ROOT . 'image/',
			DIR_ROOT . 'download/',
			DIR_SYSTEM . 'cache/',
			DIR_SYSTEM . 'log/',
		);
		
		try {
			$this->filesystem->chmod($dirs, 0755, 0000, true);
		} catch (Exception $e) {
			// Discard chmod failure, some systems may not support it
		}
		
		try {
			$this->filesystem->remove(DIR_ROOT . 'install');
		} catch (Exception $e) {
			return false;
		}

		return true;
	}
}