<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
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

    public static function __callStatic($name, $arguments)
    {
        $status = false;

        if (strpos($name, 'is') !== false) {
            $constant_name = 'DIR_' . strtoupper(substr($name, 2));

            if (defined($constant_name)) {
                $status = (self::getDir() == constant($constant_name));
            }
        }

        return $status;
    }
}
