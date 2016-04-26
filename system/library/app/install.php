<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

class Install extends App
{

    protected $route = 'main';

    public function initialise()
    {
        // File System
        $this->registry->set('filesystem', new Filesystem());

        // Loader
        $this->registry->set('load', new Loader($this->registry));

        // Trigger
        $this->registry->set('trigger', new Trigger($this->registry));

        // Url
        $this->registry->set('url', new Url(HTTP_SERVER, HTTPS_SERVER, $this->registry));

        // Uri
        $this->registry->set('uri', new Uri());

        // Security
        $this->registry->set('security', new Security($this->registry));

        // Request
        $this->registry->set('request', new Request($this->registry));

        // Response
        $response = new Response();
        $response->addHeader('Content-Type: text/html; charset=utf-8');
        $this->registry->set('response', $response);

        // Session
        $this->registry->set('session', new Session());

        // Utility
        $utility = new Utility($this->registry);
        $this->registry->set('utility', $utility);

        // Language
        if (isset($this->request->get['lang']) && $this->filesystem->exists(DIR_LANGUAGE . $this->request->get['lang'])) {
            $lang = $this->request->get['lang'];
        } else {
            $lang                       = 'en-GB';
            $this->request->get['lang'] = $lang;
            $this->route                = 'language';
        }

        $language = new Language($lang, $this->registry);
        $this->registry->set('language', $language);

        // Document
        $this->registry->set('document', new Document());
        
        $this->trigger->fire('post.app.initialise');
    }

    public function dispatch()
    {
        // Front Controller
        $controller = new Front($this->registry);

        // Router
        if (isset($this->request->get['route'])) {
            $action = new Action($this->request->get['route']);
        } else {
            $action = new Action($this->route);
        }

        // Dispatch
        $controller->dispatch($action, new Action('error/not_found'));

        $this->trigger->fire('post.app.dispatch');
    }

    public function render()
    {
        // Render
        $this->response->output();

        $this->trigger->fire('post.app.render');
    }
}
