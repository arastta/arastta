<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class Utility extends Object {

	public function __construct($registry) {
		$this->db = $registry->get('db');
        $this->url = $registry->get('url');
        $this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->cache = $registry->get('cache');	
	}

    public function getRemoteData($url, $options = array('timeout' => 10)) {
        $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36";
        $data = false;

        // cURL
        if (extension_loaded('curl')) {
            $process = @curl_init($url);

            @curl_setopt($process, CURLOPT_HEADER, false);
            @curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
            @curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
            @curl_setopt($process, CURLOPT_AUTOREFERER, true);
            @curl_setopt($process, CURLOPT_FAILONERROR, true);
            @curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt($process, CURLOPT_TIMEOUT, $options['timeout']);
            @curl_setopt($process, CURLOPT_CONNECTTIMEOUT, $options['timeout']);
            @curl_setopt($process, CURLOPT_MAXREDIRS, 20);

            if (!empty($options['referrer'])) {
                @curl_setopt($process, CURLOPT_REFERER, $this->url->getDomain());
            }

            if (!empty($options['post'])) {
                @curl_setopt($process, CURLOPT_POST, 1);
            }

            $data = @curl_exec($process);

            @curl_close($process);

            return $data;
        }

        // fsockopen
        if (function_exists('fsockopen')) {
            $errno = 0;
            $errstr = '';

            $url_info = parse_url($url);
            if($url_info['host'] == 'localhost')  {
                $url_info['host'] = '127.0.0.1';
            }

            // Open socket connection
            if ($url_info['scheme'] == 'http') {
                $fsock = @fsockopen($url_info['scheme'].'://'.$url_info['host'], 80, $errno, $errstr, 5);
            } else {
                $fsock = @fsockopen('ssl://'.$url_info['host'], 443, $errno, $errstr, 5);
            }

            if ($fsock) {
                @fputs($fsock, 'GET '.$url_info['path'].(!empty($url_info['query']) ? '?'.$url_info['query'] : '').' HTTP/1.1'."\r\n");
                @fputs($fsock, 'HOST: '.$url_info['host']."\r\n");
                @fputs($fsock, "User-Agent: ".$user_agent."\n");
                @fputs($fsock, 'Connection: close'."\r\n\r\n");

                // Set timeout
                @stream_set_blocking($fsock, 1);
                @stream_set_timeout($fsock, 5);

                $data = '';
                $passed_header = false;
                while (!@feof($fsock)) {
                    if ($passed_header) {
                        $data .= @fread($fsock, 1024);
                    } else {
                        if (@fgets($fsock, 1024) == "\r\n") {
                            $passed_header = true;
                        }
                    }
                }

                // Clean up
                @fclose($fsock);

                // Return data
                return $data;
            }
        }

        // fopen
        if (function_exists('fopen') && ini_get('allow_url_fopen')) {
            // Set timeout
            if (ini_get('default_socket_timeout') < 5) {
                ini_set('default_socket_timeout', 5);
            }

            @stream_set_blocking($handle, 1);
            @stream_set_timeout($handle, 5);
            @ini_set('user_agent',$user_agent);

            $url = str_replace('://localhost', '://127.0.0.1', $url);

            $handle = @fopen($url, 'r');

            if ($handle) {
                $data = '';
                while (!feof($handle)) {
                    $data .= @fread($handle, 8192);
                }

                // Clean up
                @fclose($handle);

                // Return data
                return $data;
            }
        }

        // file_get_contents
        if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
            $url = str_replace('://localhost', '://127.0.0.1', $url);
            @ini_set('user_agent',$user_agent);
            $data = @file_get_contents($url);

            // Return data
            return $data;
        }

        return $data;
    }

    public function getInfo() {
        $info = array();

        $info['arastta'] = VERSION;

        $info['php'] = phpversion();

        if (method_exists($this->db, 'getVersion')) {
            $info['mysql'] = $this->db->getVersion();
        } else {
            $info['mysql'] = 'N/A';
        }

        $langs = array();
        $languages = $this->getLanguages();
        foreach ($languages as $language) {
            $langs[] = $language['code'];
        }

        $info['langs'] = implode(',', $langs);

        $info['stores'] = (int) $this->getTotalStores() + 1;

        return $info;
    }

    public function getLanguages() {
        $language_data = $this->cache->get('language');

        if (!$language_data) {
            $language_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

            foreach ($query->rows as $result) {
                $language_data[$result['code']] = array(
                    'language_id' => $result['language_id'],
                    'name'        => $result['name'],
                    'code'        => $result['code'],
                    'locale'      => $result['locale'],
                    'image'       => $result['image'],
                    'directory'   => $result['directory'],
                    'sort_order'  => $result['sort_order'],
                    'status'      => $result['status']
                );
            }

            $this->cache->set('language', $language_data);
        }

        return $language_data;
    }

    public function getTotalStores() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store");

        return $query->row['total'];
    }
}