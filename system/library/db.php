<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class DB {

    private $db;

    public function __construct($driver, $hostname, $username, $password, $database) {
        $class = 'DB\\' . $driver;

        if (class_exists($class)) {
            $this->db = new $class($hostname, $username, $password, $database);
        } else {
            exit('Error: Could not load database driver ' . $driver . '!');
        }
    }

    public function query($sql) {
        return $this->db->query($sql);
    }

    public function escape($value) {
        return $this->db->escape($value);
    }

    public function countAffected() {
        return $this->db->countAffected();
    }

    public function getLastId() {
        return $this->db->getLastId();
    }

    public function getVersion() {
        return $this->db->getVersion();
    }

    public function getCollation() {
        return $this->db->getCollation();
    }
}
