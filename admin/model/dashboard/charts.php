<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelDashboardCharts extends Model
{
    public function getSales($date_start, $date_end, $group)
    {
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT SUM(total) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "order` WHERE order_status_id IN " . $complete_status_ids . " AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalSales($date_start, $date_end)
    {
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN " . $complete_status_ids . " AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getOrders($date_start, $date_end, $group)
    {
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "order` WHERE order_status_id IN " . $complete_status_ids . " AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalOrders($date_start, $date_end)
    {
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN " . $complete_status_ids . " AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getCustomers($date_start, $date_end, $group)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "customer` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalCustomers($date_start, $date_end)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getReviews($date_start, $date_end, $group)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "review` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalReviews($date_start, $date_end)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "review` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getAffiliates($date_start, $date_end, $group)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "affiliate` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalAffiliates($date_start, $date_end)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "affiliate` WHERE status = 1 AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getRewards($date_start, $date_end, $group)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "customer_reward` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalRewards($date_start, $date_end)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }
}
