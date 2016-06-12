<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelApiOrders extends Model
{

    public function getOrders($data = array())
    {
        $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

        $sql .= $this->getExtraConditions($data);

        $sort_data = array(
            'o.order_id',
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

    public function getTotals($data = array())
    {
        $sql = "SELECT COUNT(*) AS number, SUM(o.total) AS price FROM `" . DB_PREFIX . "order` o";

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->row;
    }
    
    public function getOrderProducts($data = array())
    {
        $sql = "SELECT
                    op.product_id,
                    op.name,
                    op.quantity,
                    (op.price+op.tax) AS price,
                    op.model,
                    op.order_product_id,
                    p.image,
                    o.currency_code,
                    o.currency_value,
                    p.sku,
                    p.upc,
                    p.ean,
                    p.jan,
                    p.isbn,
                    p.manufacturer_id,
                    p.weight,
                    p.length,
                    p.width,
                    p.height,
                    m.name AS manufacturer_name
                FROM `" . DB_PREFIX . "order_product` AS op
                LEFT JOIN `" . DB_PREFIX . "order` o ON op.order_id = o.order_id
                LEFT JOIN `" . DB_PREFIX . "product` AS p ON op.product_id = p.product_id
                LEFT JOIN `" . DB_PREFIX . "manufacturer_description` AS m ON m.manufacturer_id = p.manufacturer_id
                WHERE op.order_id = '" . $data['id'] . "'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    private function getExtraConditions($data)
    {
        $sql = '';

        if (isset($data['status'])) {
            $implode = array();

            $order_statuses = explode(',', $data['status']);

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

        if (!empty($data['search'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['search']) . "%'";
        }

        if (!empty($data['customer'])) {
            $sql .= " AND o.customer_id = '" . (float)$data['customer'] . "'";
        }

        if (!empty($data['date_from'])) {
            $implode[] = "DATE(o.date_added) >= DATE('" . $this->db->escape($data['date_from']) . "')";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "DATE(o.date_added) <= DATE('" . $this->db->escape($data['date_to']) . "')";
        }

        if (!empty($data['total'])) {
            $sql .= " AND o.total = '" . (float)$data['total'] . "'";
        }

        return $sql;
    }
}
