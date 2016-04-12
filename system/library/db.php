<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class DB {

    private $adapter;

    public function __construct($driver, $hostname, $username, $password, $database) {
        $class = 'DB\\' . $driver;

        if (class_exists($class)) {
            $this->adapter = new $class($hostname, $username, $password, $database);
        } else {
            exit('Error: Could not load database driver ' . $driver . '!');
        }
    }

    public function query($sql) {
        return $this->adapter->query($sql);
    }

    public function escape($value) {
        return $this->adapter->escape($value);
    }

    public function countAffected() {
        return $this->adapter->countAffected();
    }

    public function getLastId() {
        return $this->adapter->getLastId();
    }

    public function getVersion() {
        return $this->adapter->getVersion();
    }

    public function getCollation() {
        return $this->adapter->getCollation();
    }

    public function setTimezone($timezone) {
        $this->adapter->setTimezone($timezone);
    }
}
