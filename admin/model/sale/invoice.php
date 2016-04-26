<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelSaleInvoice extends Model
{

    public function getInvoice($invoice_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice WHERE invoice_id = '" . (int)$invoice_id . "'");

        return $query->row;
    }

    public function getInvoiceByOrder($order_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice WHERE order_id = '" . (int)$order_id . "'");

        return $query->row;
    }

    public function getOrders($data = array())
    {
        $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.currency_code, o.currency_value, o.date_added AS order_date FROM `" . DB_PREFIX . "order` o";

        if (isset($data['filter_order_status'])) {
            $implode = array();

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
            }

            if ($implode) {
                $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
            } else {

            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_order_date'])) {
            $sql .= " AND DATE(order_date) = DATE('" . $this->db->escape($data['filter_order_date']) . "')";
        }

        $sql .= " AND invoice_no = ''";

        $sort_data = array(
            'o.order_id',
            'invoice_number',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getInvoices($data = array())
    {
        $sql = "SELECT i.invoice_id, i.invoice_date, o.order_id, CONCAT(o.invoice_prefix, '', o.invoice_no) AS invoice_number, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added AS order_date FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "order` AS o ON i.order_id = o.order_id";

        if (isset($data['filter_order_status'])) {
            $implode = array();

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
            }

            if ($implode) {
                $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
            } else {

            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_invoice_number'])) {
            $sql .= " AND CONCAT(o.invoice_no, '', o.invoice_prefix) = '" . $this->db->escape($data['filter_invoice_number']) . "'";
        }

        if (!empty($data['filter_invoice_date'])) {
            $sql .= " AND DATE(i.invoice_date) = DATE('" . $this->db->escape($data['filter_invoice_date']) . "')";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_order_date'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_order_date']) . "')";
        }

        $sort_data = array(
            'o.order_id',
            'invoice_number',
            'customer',
            'status',
            'order_date',
            'i.invoice_date',
            'o.total'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalInvoices($data = array())
    {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` AS i LEFT JOIN `" . DB_PREFIX . "order` AS o ON i.order_id = o.order_id";

        if (!empty($data['filter_order_status'])) {
            $implode = array();

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
            }

            if ($implode) {
                $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_invoice_number'])) {
            $sql .= " AND CONCAT(o.invoice_no, '', o.invoice_prefix) = '" . $this->db->escape($data['filter_invoice_number']) . "'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_order_date'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_order_date']) . "')";
        }

        if (!empty($data['filter_invoice_date'])) {
            $sql .= " AND DATE(i.invoice_date) = DATE('" . $this->db->escape($data['filter_invoice_date']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getHistories($invoice_id, $start = 0, $limit = 10)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT date_added, comment, notify FROM " . DB_PREFIX . "invoice_history WHERE invoice_id = '" . (int)$invoice_id . "' ORDER BY date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalHistories($invoice_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice_history WHERE invoice_id = '" . (int)$invoice_id . "'");

        return $query->row['total'];
    }

    public function addHistory($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_history SET `invoice_id` = '" . (int) $data['invoice_id'] . "', `comment` = '" . $this->db->escape($data['comment']) . "', `notify` = '" . (int) $data['notify'] . "', `date_added` = NOW()");

        $history_id = $this->db->getLastId();

        return $history_id;
    }
}
