<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class Language {

	private $default = 'english';
	private $directory;
	private $data = array();

	public function __construct($directory = '', $registry = '') {
		$this->directory = $directory;

        // Try to load the language file based on the route variable
        if (is_object($registry) and !empty($registry->get('request')->get['route'])) {
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

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
		}

		$file = DIR_LANGUAGE . 'override/' . $this->directory . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}

	public function override($filename) {
		$_ = array();

		$file = DIR_LANGUAGE . 'override/' . $this->directory . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}
}