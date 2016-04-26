<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

namespace DB;

final class MySQLi {

    private $adapter;

    public function __construct($hostname, $username, $password, $database) {
        $this->adapter = new \MySQLi($hostname, $username, $password, $database);

        if ($this->adapter->connect_error) {
            trigger_error('Error: Could not make a database link (' . $this->adapter->connect_errno . ') ' . $this->adapter->connect_error);
            exit();
        }

        $this->adapter->set_charset("utf8");
        $this->adapter->query("SET SQL_MODE = ''");
    }

    public function query($sql) {
        $query = $this->adapter->query($sql);

        if (!$this->adapter->errno) {
            if ($query instanceof \mysqli_result) {
                $data = array();

                while ($row = $query->fetch_assoc()) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : array();
                $result->rows = $data;

                $query->close();

                return $result;
            } else {
                return true;
            }
        } else {
            trigger_error('Error: ' . $this->adapter->error  . '<br />Error No: ' . $this->adapter->errno . '<br />' . $sql);
        }
    }

    public function escape($value) {
        return $this->adapter->real_escape_string($value);
    }

    public function countAffected() {
        return $this->adapter->affected_rows;
    }

    public function getLastId() {
        return $this->adapter->insert_id;
    }

    public function getVersion() {
        return mysqli_get_server_info($this->adapter);
    }

    public function getCollation() {
        // Get the database collation from server
        $result = $this->query('SHOW VARIABLES LIKE "collation_database"');

        if (!empty($result->row) && isset($result->row['Value'])) {
            return $result->row['Value'];
        } else {
            return '';
        }
    }

    public function setTimezone($timezone) {
        $this->adapter->query("SET TIME_ZONE = '" . $timezone . "'");
    }

    public function __destruct() {
        $this->adapter->close();
    }
}
