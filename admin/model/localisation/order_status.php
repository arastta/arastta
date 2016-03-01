<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelLocalisationOrderStatus extends Model {
    public function addOrderStatus($data) {
        foreach ($data['order_status'] as $language_id => $value) {
            if (isset($order_status_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', message = '" . $this->db->escape($value['message']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', message = '" . $this->db->escape($value['message']) . "'");

                $order_status_id = $this->db->getLastId();

                 $sql  = 'INSERT INTO `' . DB_PREFIX . 'email` (`text`, `text_id`, `context`, `type`, `status`) VALUES ';
                 $sql .= "('" . $this->db->escape($value['name']) . "', '" . $order_status_id . "', 'status." . strtolower($this->db->escape($value['name'])) ."', 'order', 1);";

                 $this->db->query($sql);

                 $email_id = $this->db->getLastId();
            }

            if (empty($value['email_template'])) {
                $value['email_template'] = "&lt;div style=&quot;width:695px;&quot;&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;a href=&quot;{site_url}&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;{logo}&quot;&gt;&lt;/a&gt;&lt;/p&gt;&lt;p style=&quot;margin-top:0px;margin-bottom:20px&quot;&gt;Thank you for your interest in {store_name} products. But your order canceld,&lt;/p&gt;    &lt;p style=&quot;margin-top:0px;margin-bottom:20px&quot;&gt;To view your order click on the link below:&lt;/p&gt;  &lt;p style=&quot;margin-top:0px;margin-bottom:20px&quot;&gt;&lt;a href=&quot;{order_href}&quot; target=&quot;_blank&quot;&gt;{order_href}&lt;/a&gt;&lt;/p&gt;      &lt;table style=&quot;border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px&quot;&gt;    &lt;thead&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot; colspan=&quot;2&quot;&gt;Order Datails&lt;/td&gt;      &lt;/tr&gt;    &lt;/thead&gt;    &lt;tbody&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;b&gt;Order ID:&amp;nbsp;&lt;/b&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{order_id}&lt;/span&gt;&lt;br&gt;          &lt;b&gt;Date Added:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{date}&lt;/span&gt;&lt;br&gt;&lt;b style=&quot;color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;&quot;&gt;Payment Method&lt;/b&gt;&lt;b&gt;:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{payment}&lt;/span&gt;&lt;br&gt;&lt;b style=&quot;color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;&quot;&gt;Shipping Method&lt;/b&gt;&lt;b&gt;:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{shipment}&lt;/span&gt;&lt;/td&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;b style=&quot;color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;&quot;&gt;E-mail&lt;/b&gt;&lt;b&gt;:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{email}&lt;/span&gt;&lt;br&gt;&lt;b&gt;Telephone:&lt;/b&gt;&amp;nbsp;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{telephone}&lt;/span&gt;&lt;br&gt;&lt;b style=&quot;color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;&quot;&gt;IP Address&lt;/b&gt;&lt;b&gt;:&lt;/b&gt; {ip}&lt;br&gt;&lt;b&gt;Order Status:&lt;/b&gt; " . $this->db->escape($value['name']) . "&lt;br&gt;&lt;/td&gt;      &lt;/tr&gt;    &lt;/tbody&gt;  &lt;/table&gt;&lt;table style=&quot;border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px&quot;&gt;    &lt;thead&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot;&gt;Payment Address&lt;/td&gt;                &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot;&gt;Shipping Address&lt;/td&gt;              &lt;/tr&gt;    &lt;/thead&gt;    &lt;tbody&gt;      &lt;tr&gt;        &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{payment_address}&lt;/span&gt;&lt;br&gt;&lt;/td&gt;                &lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{shipping_address}&lt;/span&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;{comment:start}&lt;table style=&quot;border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px&quot;&gt;	&lt;tbody&gt;		&lt;tr&gt;			&lt;td style=&quot;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;&lt;span style=&quot;font-size: 13px; line-height: 18px; text-align: right;&quot;&gt;{comment}&lt;/span&gt;&lt;/td&gt;		&lt;/tr&gt;	&lt;/tbody&gt;&lt;/table&gt;{comment:stop}&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;div style=&quot;border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px&quot;&gt;	&lt;div id=&quot;product_head&quot;&gt;		&lt;div id=&quot;product_image&quot; style=&quot;float:left;width: 100px; font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot;&gt;Product Image&lt;/div&gt;		&lt;div id=&quot;product&quot; style=&quot;float:left;width: 100px; font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot;&gt;Product&lt;/div&gt;		&lt;div id=&quot;product_model&quot; style=&quot;float:left;width: 100px; font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222&quot;&gt;Model&lt;/div&gt;		&lt;div id=&quot;product_quantity&quot; style=&quot;float:left;width: 100px; font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:right;padding:7px;color:#222222&quot;&gt;Quantity&lt;/div&gt;		&lt;div id=&quot;product_price&quot; style=&quot;float:left;width: 100px; font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:right;padding:7px;color:#222222&quot;&gt;Price&lt;/div&gt;		&lt;div id=&quot;product_total&quot; style=&quot;float:left;width: 100px; font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:right;padding:7px;color:#222222&quot;&gt;Total&lt;/div&gt;	&lt;/div&gt;	&lt;div style=&quot;clear:both;&quot;&gt;&lt;/div&gt;	{product:start}	&lt;div&gt;		&lt;div style=&quot;float:left;width: 100px;height:60px;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;			&lt;img src=&quot;{product_image}&quot; style=&quot;width: 50px;height: 50px;padding: auto;&quot;&gt;		&lt;/div&gt;		&lt;div style=&quot;float:left;width: 100px;height:60px;  font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;			&lt;span style=&quot;font-size: 13px; line-height: 60px; text-align: right;&quot;&gt;{product_name}&lt;/span&gt;		&lt;/div&gt;		&lt;div style=&quot;float:left;width: 100px;height:60px;  font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px&quot;&gt;			&lt;span style=&quot;font-size: 13px; line-height: 60px; text-align: right;&quot;&gt;{product_model}&lt;/span&gt;		&lt;/div&gt;		&lt;div style=&quot;float:left;width: 100px;height:60px;  font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px&quot;&gt;			&lt;span style=&quot;font-size: 13px; line-height: 60px;&quot;&gt;{product_quantity}&lt;/span&gt;		&lt;/div&gt;		&lt;div style=&quot;float:left;width: 100px;height:60px;  font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px&quot;&gt;			&lt;span style=&quot;font-size: 13px; line-height: 60px;&quot;&gt;{product_price}&lt;/span&gt;		&lt;/div&gt;		&lt;div style=&quot;float:left;width: 100px;height:60px;  font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px&quot;&gt;			&lt;span style=&quot;font-size: 13px; line-height: 60px;&quot;&gt;{product_total}&lt;/span&gt;		&lt;/div&gt;	&lt;/div&gt;	&lt;div style=&quot;clear:both;&quot;&gt;&lt;/div&gt;	{product:stop}	&lt;div style=&quot;clear:both;&quot;&gt;&lt;/div&gt;	{total:start}	&lt;div&gt;		&lt;div style=&quot;float:left;width: 560px;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px&quot;&gt;{total_title}&lt;/div&gt;		&lt;div style=&quot;float:left;width: 100px;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px&quot;&gt;{total_value}&lt;/div&gt;	&lt;/div&gt;	{total:stop}	&lt;div style=&quot;clear:both;&quot;&gt;&lt;/div&gt;&lt;/div&gt;	&lt;div style=&quot;clear:both;&quot;&gt;&lt;/div&gt;&lt;p&gt;  &lt;/p&gt;&lt;p style=&quot;margin-top:0px;margin-bottom:20px&quot;&gt;Please reply to this e-mail if you have any questions.&lt;/p&gt;&lt;/div&gt;";
            }

            $sql  = "INSERT INTO " . DB_PREFIX . "email_description SET";
            $sql .= " email_id = '" . (int)$email_id . "', name = '". $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['email_template']) . "',";
            $sql .= " status = '1', language_id = '". (int)$language_id . "'";

            $this->db->query($sql);
        }

        $this->cache->delete('order_status');
        
        return $order_status_id;
    }

    public function editOrderStatus($order_status_id, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

        foreach ($data['order_status'] as $language_id => $value) {
            if($value['message'] == '&lt;p&gt;&lt;br&gt;&lt;/p&gt;') {
                $value['message'] = Null;
            }

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', message = '" . $this->db->escape($value['message']) . "'");

            $email_template = $this->db->query("SELECT id FROM " . DB_PREFIX . "email WHERE type = 'order' AND text_id = '". $order_status_id . "'");

            $this->db->query("UPDATE " . DB_PREFIX . "email_description SET description = '" . $this->db->escape($value['email_template']) . "' WHERE language_id = '" . (int)$language_id . "' AND email_id = '" . $email_template->row['id'] . "'");
        }

        $this->cache->delete('order_status');
    }

    public function deleteOrderStatus($order_status_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "email WHERE text_id = '" . (int)$order_status_id . "'");

        if(!empty($result->num_rows)) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "email WHERE text_id = '" . (int)$order_status_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "email_description WHERE email_id = '" . (int)$result->row['id'] . "'");
        }

        $this->cache->delete('order_status');
    }

    public function getOrderStatus($order_status_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getOrderStatuses($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sql .= " ORDER BY name";

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
        } else {
            $order_status_data = $this->cache->get('order_status.' . (int)$this->config->get('config_language_id'));

            if (!$order_status_data) {
                $query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

                $order_status_data = $query->rows;

                $this->cache->set('order_status.' . (int)$this->config->get('config_language_id'), $order_status_data);
            }

            return $order_status_data;
        }
    }

    public function getOrderStatusDescriptions($order_status_id) {
        $order_status_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

        foreach ($query->rows as $result) {
            $order_status_data[$result['language_id']] = array('name' => $result['name'], 'message' => $result['message']);
        }

        return $order_status_data;
    }

    public function getTotalOrderStatuses() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
}
