<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

final class Loader {

	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }

	public function controller($route, $args = array()) {
        $event_args = array(&$route, &$args);
        $this->trigger->fire('pre.load.controller', $event_args);

        $action = new Action($route, $args);

		$ret = $action->execute($this->registry);

        $this->trigger->fire('post.load.controller', $ret);

        return $ret;
    }

	public function model($model) {
        $file = Client::getDir() . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) {
            $this->trigger->fire('pre.load.model', $model);

            include_once($file);

            $model_class = new $class($this->registry);

			$this->registry->set('model_' . str_replace('/', '_', $model), $model_class);

            $this->trigger->fire('post.load.model', $model_class);
		} else {
			trigger_error('Error: Could not load model ' . $file . '!');
			exit();
		}
	}

	public function view($template, $data = array()) {
        $file = DIR_TEMPLATE . $template;

		if (file_exists($file)) {
            $event_args = array(&$template, &$data);
            $this->trigger->fire('pre.load.view', $event_args);

            extract($data);

			ob_start();

			require($file);

			$output = ob_get_contents();

			ob_end_clean();

            $this->trigger->fire('post.load.view', $output);

			return $output;
		} else {
			trigger_error('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function library($library) {
        $file = DIR_SYSTEM . 'library/' . $library . '.php';

		if (file_exists($file)) {
            $this->trigger->fire('pre.load.library', $library);

            include_once($file);

            $this->trigger->fire('post.load.library', $library);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}
	}

	public function helper($helper) {
        $file = DIR_SYSTEM . 'helper/' . $helper . '.php';

		if (file_exists($file)) {
            $this->trigger->fire('pre.load.helper', $helper);

            include_once($file);

            $this->trigger->fire('post.load.helper', $helper);
		} else {
			trigger_error('Error: Could not load helper ' . $file . '!');
			exit();
		}
	}

	public function config($config) {
        $this->trigger->fire('pre.load.config', $config);

        $this->registry->get('config')->load($config);

        $this->trigger->fire('post.load.config', $config);
	}

	public function language($language) {
        $this->trigger->fire('pre.load.language', $language);

		$lang = $this->registry->get('language')->load($language);

        $this->trigger->fire('post.load.language', $language);

        return $lang;
    }
}