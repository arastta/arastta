<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
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
        $this->trigger->fire('pre.load.controller', array(&$route, &$args));

        $action = new Action($route, $args);

        $ret = $action->execute($this->registry);

        $this->trigger->fire('post.load.controller', array(&$route, &$ret));

        return $ret;
    }

    public function model($model) {
        // Create model name
        $model_name = 'model_' . str_replace('/', '_', $model);
        
        // Return model instance if already created
        if ($this->registry->get($model_name) !== null) {
            return $this->registry->get($model_name);
        }
        
        $file = Client::getDir() . 'model/' . $model . '.php';
        $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

        if (file_exists($file)) {
            $this->trigger->fire('pre.load.model', array(&$model));

            include_once($file);

            $model_class = new $class($this->registry);

            $this->registry->set($model_name, $model_class);

            $this->trigger->fire('post.load.model', array(&$model_class));
        } else {
            trigger_error('Error: Could not load model ' . $file . '!');
            exit();
        }
    }

    public function view($template, $data = array()) {
        $theme_dir = Client::isCatalog() ? 'theme' : 'template';
        
        if (Client::isAdmin() && isset($this->session->data['theme']) && $this->session->data['theme'] != 'advanced') {
            if (file_exists(Client::getDir() . 'view/theme/' . $this->session->data['theme'] . '/template/' . $template)) {
                $theme_dir = 'theme/' . $this->session->data['theme'] . '/template';
            }
        }

        $file = Client::getDir() . 'view/' . $theme_dir . '/' . $template;

        if (file_exists($file)) {
            $this->trigger->fire('pre.load.view', array(&$template, &$data));

            extract($data);

            ob_start();

            require($file);

            $output = ob_get_contents();

            ob_end_clean();

            $this->trigger->fire('post.load.view', array(&$output));

            return $output;
        } else {
            trigger_error('Error: Could not load template ' . $file . '!');
            exit();
        }
    }

    public function output($file, $data) {
        if (Client::isAdmin()) {
            $output = $this->view($file.'.tpl', $data);
        } else {
            if (file_exists(Client::getDir() . 'view/theme/'. $this->config->get('config_template') . '/template/'.$file.'.tpl')) {
                $output = $this->view($this->config->get('config_template') . '/template/'.$file.'.tpl', $data);
            } else {
                $output = $this->view('default/template/'.$file.'.tpl', $data);
            }
        }

        return $output;
    }

    public function library($library) {
        $file = DIR_SYSTEM . 'library/' . $library . '.php';

        if (file_exists($file)) {
            $this->trigger->fire('pre.load.library', array(&$library));

            include_once($file);

            $this->trigger->fire('post.load.library', array(&$library));
        } else {
            trigger_error('Error: Could not load library ' . $file . '!');
            exit();
        }
    }

    public function helper($helper) {
        $file = DIR_SYSTEM . 'helper/' . $helper . '.php';

        if (file_exists($file)) {
            $this->trigger->fire('pre.load.helper', array(&$helper));

            include_once($file);

            $this->trigger->fire('post.load.helper', array(&$helper));
        } else {
            trigger_error('Error: Could not load helper ' . $file . '!');
            exit();
        }
    }

    public function config($config) {
        $this->trigger->fire('pre.load.config', array(&$config));

        $this->registry->get('config')->load($config);

        $this->trigger->fire('post.load.config', array(&$config));
    }

    public function language($language) {
        $this->trigger->fire('pre.load.language', array(&$language));

        $lang = $this->registry->get('language')->load($language);

        $this->trigger->fire('post.load.language', array(&$language));

        return $lang;
    }
}
