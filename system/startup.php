<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

// Installation check, and check on removal of the install directory.
if ((!file_exists(DIR_ROOT . 'config.php') || (filesize(DIR_ROOT . 'config.php') < 10)) && !Client::isInstall() && !Client::isCli()) {
    if (file_exists(DIR_INSTALL . 'index.php')) {
        header('Location: ' . str_replace(array('admin', 'index.php', '//'), array('', '', '/'), $_SERVER['REQUEST_URI']) . 'install/index.php');

        exit();
    } else {
        die('No configuration file found and no installation code available. Exiting...');
    }
}

// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(PHP_VERSION, '5.3.10', '<')) {
    die('Your host needs to use PHP 5.3.10 or higher to run Arastta.');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
    $_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $_SERVER['HTTPS'] = true;
} else {
    $_SERVER['HTTPS'] = false;
}

// Composer
if (!file_exists(DIR_SYSTEM.'vendor/autoload.php')) {
    die('You need to run Composer first. More details https://github.com/arastta/arastta');
}
require_once(DIR_SYSTEM.'vendor/autoload.php');

// Modification Override
function modification($filename, $override = 'catalog')
{
    if (Client::isCatalog()) {
        $file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(Client::getDir()));
    } else {
        $file = DIR_MODIFICATION . 'admin/' .  substr($filename, strlen(Client::getDir()));
    }

    if (Client::isCli()) {
        $file = DIR_MODIFICATION . $override . '/' . substr($filename, strlen(Client::getDir()));
    }

    if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
        $file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
    }

    if (is_file($file)) {
        return $file;
    }

    return $filename;
}

// Autoloader
function autoload($class)
{
    $lib = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';
    $app = DIR_SYSTEM . 'library/app/' . str_replace('\\', '/', strtolower($class)) . '.php';

    $command = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

    if (is_file($lib)) {
        include(modification($lib));

        return true;
    } elseif (is_file($app)) {
        include(modification($app));

        return true;
    } elseif (is_file(modification($command))) {
        include(modification($command));

        return true;
    }

    return false;
}

spl_autoload_register('autoload');
spl_autoload_extensions('.php');

// Version
$version = new Version();
define('VERSION', $version->getShortVersion());

// Engine
require_once(modification(DIR_SYSTEM . 'engine/action.php'));
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/front.php'));
require_once(modification(DIR_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));

// Helper
require_once(DIR_SYSTEM . 'helper/utf8.php');
