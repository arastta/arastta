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

        return $this->getStats($sql, $data, 'order');
    }

    public function getDailyProducts($data = array())
    {
        $sql = "SELECT COUNT(*) AS number FROM " . DB_PREFIX . "product";

        return $this->getStats($sql, $data, 'product');
    }

    public function getDailyCustomers($data = array())
    {
        $sql = "SELECT COUNT(*) AS number FROM " . DB_PREFIX . "customer";

        return $this->getStats($sql, $data, 'customer');
    }

    public function getStats($sql, $data = array(), $type= '')
    {
        $stats = array();

        $date = new DateTime($data['date_from']);
        $date_end = new DateTime($data['date_to']);

        while ($date <= $date_end) {
            $day = $date->format('Y-m-d');

            $sql .= " WHERE DATE(date_added) = DATE('" . $day . "')" . $this->getExtraConditions($data, $type);

            $query = $this->db->query($sql);
            
            $row = $query->row;
            $row['date'] = $day;

            $stats[] = $row;

            $date->add(new DateInterval('P1D'));
        }

        return $stats;
    }

    private function getExtraConditions($data, $type)
    {
        $sql = '';

        $implode = array();

        if ($type == 'order') {
            if (isset($data['status'])) {
                $implode2 = array();

                $order_statuses = explode(',', $data['status']);

                foreach ($order_statuses as $order_status_id) {
                    $implode2[] = "order_status_id = '" . (int) $order_status_id . "'";
                }

                if ($implode2) {
                    $implode[] = "(" . implode(" OR ", $implode2) . ")";
                } else {
                    $implode[] = "order_status_id > '0'";
                }
            } else {
                $implode[] = "order_status_id > '0'";
            }
        }

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        return $sql;
    }
}
