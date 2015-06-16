<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

// Error Reporting
error_reporting(E_ALL);

if (version_compare(PHP_VERSION, '5.3.10', '<')) {
	die('Your host needs to use PHP 5.3.10 or higher to run Arastta.');
}

define('AREXE', 1);
define('INSTALLER', 1);

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
$language = new Language('en-GB', $registry);
$registry->set('language', $language);

// Upgrade
$upgrade = false;

if (file_exists('../config.php')) {
	if (filesize('../config.php') > 0) {
		$upgrade = true;

		$lines = file(DIR_ROOT . 'config.php');

		foreach ($lines as $line) {
			if (strpos(strtoupper($line), 'DB_') !== false) {
				eval($line);
			}
		}
	}
}

// Front Controller
$controller = new Front($registry);

$action = new Action('main');
// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} elseif ($upgrade) {
	//$action = new Action('upgrade');
} else {
	$action = new Action('main');
}

// Dispatch
$controller->dispatch($action, new Action('not_found'));

// Output
$response->output();