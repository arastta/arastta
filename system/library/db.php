<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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
