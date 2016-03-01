<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Language {

    private $default = 'en-GB';
    private $directory;
    private $data = array();

    public function __construct($directory = '', $registry = '') {
        $this->directory = $directory;

        if (!is_object($registry)) {
            return;
        }

        // Set default language from config
        $default_lang = $registry->get('utility')->getDefaultLanguage();
        if (!empty($default_lang)) {
            $this->default = $default_lang['directory'];
        }

        // Load main language file
        $this->load('default');

        // Try to load the language file based on the route variable
        if (!empty($registry->get('request')->get['route'])) {
            $g_route = $registry->get('request')->get['route'];
            $a_route = explode('/', $g_route);

            $n_route = array();
            for ($i = 0; $i < 2; $i++) {
                if (empty($a_route[$i])) {
                    continue;
                }

                $n_route[] = $a_route[$i];
            }

            // load the language file if we have ab/cd
            if (count($n_route) == 2) {
                $this->load(implode('/', $n_route));
            }
        }
    }

    public function get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : $key);
    }

    public function all($data = array(), $skip = array()) {
        foreach ($this->data as $key => $value) {
            // Don't add if the key found in the skip list
            if (in_array($key, $skip)) {
                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }

    public function load($filename) {
        $_ = array();

        $dir = Client::getDir();

        // Step 1: Load the en-GB language translation (it's the safest fallback) 
        $file = $dir . 'language/' . 'en-GB/' . $filename . '.php';

        if (file_exists($file)) {
            require($file);
        }

        // Step 2: Load the store's default language translation (it's the store owner preferred fallback)
        $file = $dir . 'language/' . $this->default . '/' . $filename . '.php';

        if (file_exists($file)) {
            require($file);
        }

        // Step 3: Load the user's selected language translation (it's the user preferred language)
        $file = $dir . 'language/' . $this->directory . '/' . $filename . '.php';

        if (file_exists($file)) {
            require($file);
        }

        // Step 4: Load the user's selected language override (it's the store owner preferred language)
        $file = $dir . 'language/' . 'override/' . $this->directory . '/' . $filename . '.php';

        if (file_exists($file)) {
            require($file);
        }

        $this->data = array_merge($this->data, $_);

        return $this->data;
    }

    public function override($filename) {
        $_ = array();

        $file = Client::getDir() . 'language/' . 'override/' . $this->directory . '/' . $filename . '.php';

        if (file_exists($file)) {
            require($file);
        }

        $this->data = array_merge($this->data, $_);

        return $this->data;
    }
}
