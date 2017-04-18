<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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
                    $code = $this->getBrowserDefaultLanguage($languages['code']);
                }
            } else {
                $code = $this->getBrowserDefaultLanguage($languages['code']);
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

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = 1 ORDER BY sort_order, name");

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
