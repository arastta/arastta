<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Application;

class Cli extends App
{

    protected $console;

    public function __construct(Application $console)
    {
        parent::__construct();

        $this->console = $console;
    }

    public function initialise()
    {
        // File System
        $filesystem = new Filesystem();
        $this->registry->set('filesystem', $filesystem);

        // Config
        $this->registry->set('config', new Object());

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
        }

        $cache = new Cache($this->config->get('config_cache_storage', 'file'), $this->config->get('config_cache_lifetime', 86400), $this->config);
        $this->registry->set('cache', $cache);

        $this->addCommandsToConsole();

        $this->trigger->fire('post.app.initialise');
    }

    public function ecommerce()
    {
        // Set time zone
        date_default_timezone_set($this->config->get('config_timezone', 'UTC'));
        $dt = new \DateTime();
        $this->db->setTimezone($dt->format('P'));

        $this->trigger->fire('post.app.ecommerce');
    }

        public function call($callback, array $parameters = array())
    {
        return call_user_func_array($callback, $parameters);
    }

    private function addCommandsToConsole()
    {
        foreach ($this->getCommands() as $command_name) {
            $this->console->add(new $command_name($this, $this->admin, $this->catalog, $this->install, $this->apps));
        }
    }

    private function getCommands()
    {
        $commands = array();

        $finder = new Finder();

        $finder->files()->in(DIR_SYSTEM . 'library/command')->name('*.php');

        foreach ($finder as $file) {
            $name = str_replace('.php', '', $file->getRelativePathname());

            $commands[] = 'Command\\' . ucfirst($name);
        }

        return $commands;
    }
}
