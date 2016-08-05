<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelApiStats extends Model
{

    public function getDailyOrders($data = array())
    {
        $sql = "SELECT COUNT(*) AS number, SUM(total) AS price FROM " . DB_PREFIX . "order";

        return $this->getStats($sql, $data);
    }

    public function getDailyProducts($data = array())
    {
        $sql = "SELECT COUNT(*) AS number FROM " . DB_PREFIX . "product";

        return $this->getStats($sql, $data);
    }

    public function getDailyCustomers($data = array())
    {
        $sql = "SELECT COUNT(*) AS number FROM " . DB_PREFIX . "customer";

        return $this->getStats($sql, $data);
    }

    public function getStats($sql, $data = array())
    {
        $stats = array();

        $date = new DateTime($data['date_from']);
        $date_end = new DateTime($data['date_to']);

        while ($date <= $date_end) {
            $day = $date->format('Y-m-d');

            $query = $this->db->query($sql . " WHERE DATE(date_added) = DATE('" . $day . "')");

            $stats[$day] = $query->row;

            $date->add(new DateInterval('P1D'));
        }

        return $stats;
    }
}
