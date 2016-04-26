<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelToolSysteminfo extends Model
{

    public function getGeneral()
    {
        $data = array();

        $version = new Version;

        $data['platform']       = $version->getLongVersion();
        $data['php_version']    = phpversion();
        $data['php_build']      = php_uname();
        $data['db_version']         = $this->db->getVersion();
        $data['db_collation']   = $this->db->getCollation();
        $data['server']             = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : getenv('SERVER_SOFTWARE');
        $data['sapi']           = php_sapi_name();
        $data['user_agent']         = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

        return $data;
    }

    public function getPermissions()
    {
        $data = array();

        $folders = array('admin', 'catalog', 'download', 'image', 'cache', 'log', 'modification', 'upload', 'vqmod');

        foreach ($folders as $folder) {
            $constant = constant('DIR_'.strtoupper($folder));

            $name = rtrim(str_replace(DIR_ROOT, '', $constant), '/');

            $data[$name] = is_writable($constant) ? '1' : '0';
        }

        return $data;
    }

    public function getPhpSettings()
    {
        $data = array();

        $data['safe_mode']              = ini_get('safe_mode') == '1';
        $data['register_globals']       = ini_get('register_globals') == '1';
        $data['magic_quotes_gpc']       = ini_get('magic_quotes_gpc') == '1';
        $data['file_uploads']           = ini_get('file_uploads') == '1';
        $data['session_auto_start']         = ini_get('session.auto_start') == '1';
        $data['session_save_path']      = ini_get('session.save_path');
        $data['display_errors']             = ini_get('display_errors') == '1';
        $data['output_buffering']       = (bool) ini_get('output_buffering');
        $data['gd']                     = extension_loaded('gd');
        $data['curl']                   = extension_loaded('curl');
        $data['mcrypt_encrypt']         = function_exists('mcrypt_encrypt');
        $data['zip']                    = function_exists('zip_open') && function_exists('zip_read');
        $data['zlib']                   = extension_loaded('zlib');
        $data['mbstring']               = extension_loaded('mbstring');
        $data['iconv']                  = function_exists('iconv');
        $data['open_basedir']           = ini_get('open_basedir');
        $data['disable_functions']      = ini_get('disable_functions');

        return $data;
    }
    
    public function getPhpInfo()
    {
        $php_info = '';

        // phpinfo disabled
        if (!function_exists('phpinfo')) {
            return $php_info;
        }

        ob_start();
        date_default_timezone_set('UTC');
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
        $php_info = ob_get_contents();
        ob_end_clean();
        
        preg_match_all('#<body[^>]*>(.*)</body>#siU', $php_info, $output);
        $output = preg_replace('#<table[^>]*>#', '<div class="table-responsive"><table class="table table-bordered table-hover">', $output[1][0]);
        $output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
        $output = preg_replace('#<hr />#', '', $output);
        $output = str_replace('<div class="center">', '', $output);
        $output = preg_replace('#<tr class="h">(.*)<\/tr>#', '<thead><tr class="h">$1</tr></thead><tbody>', $output);
        $output = str_replace('</div>', '', $output);
        $output = str_replace('</table>', '</tbody></table></div>', $output);

        return $output;
    }
}
