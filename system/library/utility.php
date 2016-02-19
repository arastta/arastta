<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Utility extends Object
{

    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    public function getLanguage()
    {
        $lang = null;
        $languages = array();

        $prefix = Client::isAdmin() ? 'admin_' : '';

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");

        foreach ($query->rows as $result) {
            $languages[$result['code']] = $result;
        }

        if (isset($this->request->get['lang']) && array_key_exists($this->request->get['lang'], $languages) && $languages[$this->request->get['lang']]['status']) {
            $code = $this->request->get['lang'];
        } elseif (isset($this->session->data[$prefix.'language']) && array_key_exists($this->session->data[$prefix.'language'], $languages) && $languages[$this->session->data[$prefix.'language']]['status']) {
            $code = $this->session->data[$prefix.'language'];
        } elseif (isset($this->request->cookie[$prefix.'language']) && array_key_exists($this->request->cookie[$prefix.'language'], $languages) && $languages[$this->request->cookie[$prefix.'language']]['status']) {
            $code = $this->request->cookie[$prefix.'language'];
        } else {
            // Try to get the language from SEF URL
            if (Client::isCatalog() && $this->config->get('config_seo_url')) {
                $route = str_replace($this->url->getFullUrl(), '', rawurldecode($this->uri->toString()));
                $route = str_replace('?'.$this->uri->getQuery(), '', $route);
                $route_parts = explode('/', str_replace('index.php/', '', $route));

                if (!empty($route_parts[0]) && (strlen($route_parts[0]) == 2)) {
                    $code = $route_parts[0];
                } else {
                    $code = $this->getBrowserDefaultLanguage($languages);
                }
            } else {
                $code = $this->getBrowserDefaultLanguage($languages);
            }
        }

        // Check if lang code exists in db, otherwise return the first lang
        if (isset($languages[$code])) {
            $lang = $languages[$code];
        } else {
            reset($languages);
            $lang = current($languages);
        }

        return $lang;
    }

    public function getBrowserDefaultLanguage($languages)
    {
        $browser = $this->getBrowserLangCode($languages);

        if (is_object($this->config)) {
            $default = Client::isAdmin() ? $this->config->get('config_admin_language') : $this->config->get('config_language');
        } else {
            $default = 'en';
        }

        $code = $browser ? $browser : $default;

        return $code;
    }

    public function getDefaultLanguage()
    {
        if (!is_object($this->config)) {
            return;
        }

        $store_id = $this->config->get('config_store_id');

        if (Client::isAdmin()) {
            $sql = "SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'config_admin_language' AND `store_id` = '" . $store_id . "'";
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'config_language' AND `store_id` = '" . $store_id . "'";
        }
        $query = $this->db->query($sql);
        $code = $query->row['value'];

        $language = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE `code` = '" . $code . "'");

        return $language->row;
    }

    public function getBrowserLangCode($system_langs)
    {
        $lang = null;

        if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return $lang;
        }

        $browser_langs = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);

        foreach ($browser_langs as $browser_lang) {
            // Slice out the part before ; on first step, the part before - on second, place into array
            $browser_lang = substr($browser_lang, 0, strcspn($browser_lang, ';'));
            $primary_browser_lang = substr($browser_lang, 0, 2);

            foreach ($system_langs as $system_lang) {
                if (!$system_lang['status']) {
                    continue;
                }

                $system_code = $system_lang['code'];
                $system_dir = $system_lang['directory'];

                // Take off 3 letters (zh-yue) iso code languages as they can't match browsers' languages and default them to en
                // http://www.w3.org/International/articles/language-tags/#extlang
                if (strlen($system_dir) > 5) {
                    continue;
                }

                if (strtolower($browser_lang) == strtolower(substr($system_dir, 0, strlen($browser_lang)))) {
                    return $system_code;
                } elseif ($primary_browser_lang == substr($system_dir, 0, 2)) {
                    $primary_detected_code = $system_code;
                }
            }

            if (isset($primary_detected_code)) {
                return $primary_detected_code;
            }
        }

        return $lang;
    }

    public function getRemoteData($url, $options = array('timeout' => 10))
    {
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
                @curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($options['post_fields']));
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
            if ($url_info['host'] == 'localhost') {
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
            @ini_set('user_agent', $user_agent);

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
            @ini_set('user_agent', $user_agent);
            $data = @file_get_contents($url);

            // Return data
            return $data;
        }

        return $data;
    }

    public function getInfo()
    {
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

        $info['api'] = $this->config->get('api_key', '');

        return $info;
    }

    public function getLanguages()
    {
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

    public function getTotalStores()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store");

        return $query->row['total'];
    }
}
