<?php

use Symfony\Component\Console\Application;

class Cli extends App {

    protected $console;

    protected $catalog;
    protected $admin;
    protected $apps; //any other apps that are added

    public function __construct(Application $console) {

        parent::__construct();

        $this->console = $console;

    }

    public function initialise(Catalog $catalog = null, Admin $admin = null, $apps = array()) {

        $this->catalog = $catalog;
        $this->admin = $admin;
        $this->apps = $apps;

        //TODO: Not sure of the best way around this...
        if (!defined('HTTP_SERVER')) {
            define('HTTP_SERVER', '');
        }
        if (!defined('HTTPS_SERVER')) {
            define('HTTPS_SERVER', '');
        }

        // File System
        $filesystem = new Filesystem();
        $this->registry->set('filesystem', $filesystem);
        // Config
        $this->registry->set('config', new Config());
        // Loader
        $loader = new Loader($this->registry);
        $this->registry->set('load', $loader);
        // Trigger
        $trigger = new Trigger($this->registry);
        $this->registry->set('trigger', $trigger);
        // Url
        $url = new Url(HTTP_SERVER, HTTPS_SERVER, $this->registry);
        $this->registry->set('url', $url);
        // Uri
        $uri = new Uri();
        $this->registry->set('uri', $uri);
        // Security
        $security = new Security($this->registry);
        $this->registry->set('security', $security);
        // Session
        $session = new Session();
        $this->registry->set('session', $session);

        // Config
        if (is_installed()) {
            require_once(DIR_ROOT . 'config.php');

            $this->registry->set('db', new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));

            // Utility
            $utility = new Utility($this->registry);
            $this->registry->set('utility', $utility);
            // Language
            $lang = 'en-GB';
            $language = new Language($lang, $this->registry);
            $this->registry->set('language', $language);

            $this->registry->set('update', new Update($this->registry));
        }

        $cache = new Cache($this->config->get('config_cache_storage', 'file'), $this->config->get('config_cache_lifetime', 86400));
        $this->registry->set('cache', $cache);

        $this->addCommandsToConsole();

    }

    public function call($callback, array $parameters = array())
    {
        return call_user_func_array($callback, $parameters);
    }

    private function addCommandsToConsole() {
        foreach ($this->getCommands() as $command_name) {
            $this->console->add(new $command_name($this, $this->catalog, $this->admin, $this->apps));
        }
    }

    private function getCommands() {
        $commands = array();

        if(is_installed()) {

        } else {
            $commands[] = 'Command\InstallCommand';
        }

        //always available commands
        $commands[] = 'Command\ClearCacheCommand';

        return $commands;
    }

}