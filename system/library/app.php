<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Joomla\Profiler\Profiler;

class App extends Object
{

    protected $registry;

    public function __construct()
    {
        $this->registry = new Registry();

        // Config
        if (file_exists(DIR_ROOT . 'config.php')) {
            require_once(DIR_ROOT . 'config.php');
        }

        $this->registry->set('profiler', new Profiler('Trigger'));
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    public function initialise()
    {
        $this->trigger->fire('post.app.initialise');
    }

    public function ecommerce()
    {
        $this->trigger->fire('post.app.ecommerce');
    }

    public function route()
    {
        $this->trigger->fire('post.app.route');
    }

    public function dispatch()
    {
        $this->trigger->fire('post.app.dispatch');
    }

    public function render()
    {
        // Render
        $this->response->output();

        $this->trigger->fire('post.app.render');

        if ($this->config->get('config_debug_system') and $this->request->isGet() and !$this->request->isAjax()) {
            echo '<div id="profiler">'.$this->profiler.'</div>';
        }
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        // error suppressed with @
        if (error_reporting() === 0) {
            return false;
        }

        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $error = 'Notice';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $error = 'Warning';
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $error = 'Fatal Error';
                break;
            default:
                $error = 'Unknown';
                break;
        }

        if ($this->config->get('config_error_display')) {
            echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
        }

        if ($this->config->get('config_error_log')) {
            $this->log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
        }

        return true;
    }
}
