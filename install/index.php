<?php
/**
 * @package         Arastta eCommerce
 * @copyright       Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits         See CREDITS.txt for credits and other copyright notices.
 * @license         GNU General Public License version 3; see LICENSE.txt
 */

// Error Reporting
error_reporting(E_ALL);

if (version_compare(PHP_VERSION, '5.3.10', '<')) {
    die('Your host needs to use PHP 5.3.10 or higher to run Arastta.');
}

define('AREXE', 1);

require_once('define.php');
require_once(DIR_SYSTEM . 'library/client.php');

Client::setName('install');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// File System
$filesystem = new Filesystem();
$registry->set('filesystem', $filesystem);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Trigger
$trigger = new Trigger($registry);
$registry->set('trigger', $trigger);

// Url
$url = new Url(HTTP_SERVER, HTTPS_SERVER, $registry);
$registry->set('url', $url);

// Uri
$uri = new Uri();
$registry->set('uri', $uri);

// Security
$security = new Security($registry);
$registry->set('security', $security);

// Request
$request = new Request($registry);
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=UTF-8');
$registry->set('response', $response);

// Document
$document = new Document();
$registry->set('document', $document);

// Session
$session = new Session();
$registry->set('session', $session);

// Utility
$utility = new Utility($registry);
$registry->set('utility', $utility);

// Language
if (isset($request->get['lang']) && $filesystem->exists(DIR_LANGUAGE . $request->get['lang'])) {
    $lang = $request->get['lang'];
    $route = 'main';
} else {
    $lang = 'en-GB';
    $request->get['lang'] = $lang;
    $route = 'language';
}

$language = new Language($lang, $registry);
$registry->set('language', $language);

// Config
if (file_exists(DIR_ROOT . 'config.php') && filesize(DIR_ROOT . 'config.php') > 0) {
    require_once(DIR_ROOT . 'config.php');
}

// Front Controller
$controller = new Front($registry);

// Router
if (isset($request->get['route'])) {
    $action = new Action($request->get['route']);
} else {
    $action = new Action($route);
}

// Dispatch
$controller->dispatch($action, new Action('not_found'));

// Output
$response->output();
