<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

final class Client {

    private static $name;

    public static function getName() {
        return self::$name;
    }

    public static function setName($client) {
        self::$name = $client;
    }

    public static function getDir() {
        $dir = 'DIR_'.strtoupper(self::getName());

        return constant($dir);
    }

    public static function isAdmin() {
        return (self::getDir() == DIR_ADMIN);
    }

    public static function isCatalog() {
        return (self::getDir() == DIR_CATALOG);
    }
}