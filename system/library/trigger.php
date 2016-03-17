<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Finder\Finder;

class Trigger extends Object
{

    protected $registry;
    protected $stop = false;
    protected $folders = array('app', 'menu', 'editor', 'captcha');
    protected $listeners = array();
    protected $add_listeners = array();
    protected $skip_listeners = array();

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

    public function addFolder($folder)
    {
        $this->folders[] = $folder;
    }

    public function addListener($event, $path)
    {
        $this->add_listeners[$event][] = $path;
    }

    public function skipListener($event, $path)
    {
        $this->skip_listeners[$event][] = $path;
    }

    /**
     * Triggers an event by dispatching arguments to all listeners/observers that handle
     * the event and returning their return values.
     *
     * @param   string  $event      The event to trigger.
     * @param   array   $args       An array of arguments.
     *
     * @return  array  An array of results from each function call.
     *
     * @since   1.0
     */
    public function fire($event, $args = array())
    {
        $result = array();

        $this->set('stop', false);
        $this->loadListeners($event);

        $method = $this->getEventMethod($event);

        foreach ($this->listeners[$event] as $listener) {
            if (is_callable(array($listener, $method))) {
                $args = (array) $args;
                $value = call_user_func_array(array($listener, $method), $args);
            }

            if (!empty($value)) {
                $result[] = $value;
            }

            if ($this->get('stop', false) == true) {
                break;
            }
        }

        if (is_object($this->config) && $this->config->get('config_debug_system') && !$this->profiler->hasPoint($method)) {
            $this->profiler->mark($method);
        }

        return $result;
    }

    public function loadListeners($event)
    {
        if (!empty($this->listeners[$event])) {
            return;
        }

        $this->listeners[$event] = array();

        $add_listeners = array();
        if (!empty($this->add_listeners[$event])) {
            $add_listeners = $this->add_listeners[$event];
        }

        $paths = array_merge($this->getFolderListeners(), $this->getCallbackListeners());
        $paths = array_merge($add_listeners, $paths);

        foreach ($paths as $path) {
            if (isset($this->skip_listeners[$event]) && in_array($path, $this->skip_listeners[$event])) {
                continue;
            }

            $file = Client::getDir() . 'event/' . $path . '.php';

            if (!file_exists($file)) {
                continue;
            }

            require_once($file);

            $class = 'Event' . preg_replace('/[^a-zA-Z0-9]/', '', $path);

            $this->listeners[$event][] = new $class($this->registry);
        }
    }

    public function getFolderListeners()
    {
        $listeners = array();

        if (empty($this->folders)) {
            return $listeners;
        }

        $files = new Finder();

        foreach ($this->folders as $folder) {
            if (!file_exists(Client::getDir().'event/'.$folder.'/')) {
                continue;
            }

            $files->files()->in(Client::getDir().'event/'.$folder.'/');
            $files->files()->name('*.php');

            foreach ($files as $file) {
                $file_name = str_replace('\\', '/', $file->getRelativePathname());
                $file_name = str_replace('.php', '', $file_name);

                $listeners[] = $folder.'/'.$file_name;
            }

        }

        return $listeners;
    }

    public function getCallbackListeners()
    {
        $listeners = array();

        $folder = $this->getCallbackFolder();
        if (empty($folder) or !file_exists(Client::getDir().'event/'.$folder.'/')) {
            return $listeners;
        }

        $files = new Finder();
        $files->files()->in(Client::getDir().'event/'.$folder.'/');
        $files->files()->name('*.php');

        foreach ($files as $file) {
            $file_name = str_replace('\\', '/', $file->getRelativePathname());
            $file_name = str_replace('.php', '', $file_name);

            $listeners[] = $folder.'/'.$file_name;
        }

        return $listeners;
    }

    public function getCallbackFolder()
    {
        $class = '';

        //get the trace
        $trace = debug_backtrace();

        // Get the class that is asking for who awoke it
        $trace_class = (isset($trace[4]['class']) ? $trace[4]['class'] : null);

        // +4 to i cos we have to account for calling this function
        for ($i = 4; $i < count($trace); $i++) {
            // is it set?
            if (!isset($trace[$i]) || !isset($trace[$i]['class'])) {
                continue;
            }

            // continue if it's not the calling class
            if ($trace_class != $trace[$i]['class']) {
                continue;
            }

            $split = preg_split("/(?<=[a-z])(?![a-z])/", $trace[$i]['class'], -1, PREG_SPLIT_NO_EMPTY);

            if (empty($split[1]) || empty($split[0]) || (($split[0] != 'Controller') && ($split[0] != 'Model'))) {
                continue;
            }

            $class = strtolower($split[1]);

            break;
        }

        return $class;
    }

    public function getEventMethod($event)
    {
        $method = '';

        $tmp = explode('.', $event);

        for ($i = 0; $i < count($tmp); $i++) {
            if (empty($tmp[$i])) {
                continue;
            }

            if ($i == 0) {
                $method .= $tmp[$i];
            } else {
                $method .= ucfirst($tmp[$i]);
            }
        }

        return $method;
    }
}
