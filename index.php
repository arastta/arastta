<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

define('AREXE', 1);

require_once('define.php');

// Startup
require_once(DIR_SYSTEM . 'library/client.php');
Client::setName('catalog');

require_once(DIR_SYSTEM . 'startup.php');

// App
$app = new Catalog();

// Initialise main classes
$app->initialise();

// Load eCommerce classes
$app->ecommerce();

// Route the app
$app->route();

// Dispatch the app
$app->dispatch();

// Render the output
$app->render();
