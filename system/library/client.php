<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

final class Client
{

    private static $name = 'catalog';

    public static function getName()
    {
        return self::$name;
    }

    public static function setName($client)
    {
        self::$name = $client;
    }

    public static function getDir()
    {
        $dir = 'DIR_'.strtoupper(self::getName());

        return constant($dir);
    }

    public static function isAdmin()
    {
        return (self::getDir() == DIR_ADMIN);
    }

    public static function isCatalog()
    {
        return (self::getDir() == DIR_CATALOG);
    }

    public static function isInstall()
    {
        return (self::getDir() == DIR_INSTALL);
    }

    /**
     * Allow for new apps to be added and checked e.g. isBlog(), isCli()
     * @param $name
     * @param $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments) {

        if(strpos($name, 'is') !== false) {

            $constant_name = 'DIR_' . strtoupper(substr($name, 2));
            if(defined($constant_name)) {
                return (self::getDir() == constant($constant_name));
            }
        }

        return false;
    }
}
