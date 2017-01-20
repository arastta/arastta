<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

// Error Reporting
error_reporting(E_ALL);

if (version_compare(PHP_VERSION, '5.3.10', '<')) {
    die('Your host needs to use PHP 5.3.10 or higher to run Arastta.');
}

define('AREXE', 1);

require_once('define.php');

// Startup
require_once(DIR_SYSTEM . 'library/client.php');
Client::setName('install');

require_once(DIR_SYSTEM . 'startup.php');

// App
$app = new Install();

// Initialise main classes
$app->initialise();

// Dispatch the app
$app->dispatch();

// Render the output
$app->render();
