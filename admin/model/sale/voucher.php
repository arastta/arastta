<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelSaleVoucher extends Model {
    public function addVoucher($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
    }

    public function editVoucher($voucher_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '" . (int)$data['status'] . "' WHERE voucher_id = '" . (int)$voucher_id . "'");
    }

    public function deleteVoucher($voucher_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "voucher WHERE voucher_id = '" . (int)$voucher_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "voucher_history WHERE voucher_id = '" . (int)$voucher_id . "'");
    }

    public function getVoucher($voucher_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "voucher WHERE voucher_id = '" . (int)$voucher_id . "'");

        return $query->row;
    }

    public function getVoucherByCode($code) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "voucher WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function getVouchers($data = array()) {
        $sql = "SELECT v.voucher_id, v.code, v.from_name, v.from_email, v.to_name, v.to_email, (SELECT vtd.name FROM " . DB_PREFIX . "voucher_theme_description vtd WHERE vtd.voucher_theme_id = v.voucher_theme_id AND vtd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS theme, v.amount, v.status, v.date_added FROM " . DB_PREFIX . "voucher v";

        $sort_data = array(
            'v.code',
            'v.from_name',
            'v.from_email',
            'v.to_name',
            'v.to_email',
            'v.theme',
            'v.amount',
            'v.status',
            'v.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY v.date_added";
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

    public function sendVoucher($voucher_id) {
        $voucher_info = $this->getVoucher($voucher_id);

        if ($voucher_info) {
            if ($voucher_info['order_id']) {
                $order_id = $voucher_info['order_id'];
            } else {
                $order_id = 0;
            }

            $this->load->model('sale/order');

            $order_info = $this->model_sale_order->getOrder($order_id);

            // If voucher belongs to an order
            if ($order_info) {
                $this->load->model('localisation/language');

                // HTML Mail
                $data = array();

                $this->load->model('sale/voucher_theme');

                $voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);

                if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
                    $data['image'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
                } else {
                    $data['image'] = '';
                }

                $data['recip_name'] = $voucher_info['to_name'];
                $data['recip_email'] = $voucher_info['to_email'];

                $data['amount'] = $this->currency->format($voucher_info['amount'], $order_info['currency_code'], $order_info['currency_value']);
                $data['name'] = $voucher_info['from_name'];
                $data['code'] = $voucher_info['code'];

                $data['store_name'] = $order_info['store_name'];
                $data['store_href'] = $order_info['store_url'];
                $data['message'] = nl2br($voucher_info['message']);

                $subject = $this->emailtemplate->getSubject('Voucher', 'customer_5', $data);
                $message = $this->emailtemplate->getMessage('Voucher', 'customer_5', $data);

                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($voucher_info['to_email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject($subject);
                $mail->setHtml($message);
                $mail->send();

            // If voucher does not belong to an order
            }  else {
                $this->load->language('mail/voucher');

                $data = array();

                $this->load->model('sale/voucher_theme');

                $voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);

                $data['recip_name'] = $voucher_info['to_name'];
                $data['recip_email'] = $voucher_info['to_email'];

                $data['amount'] = $this->currency->format($voucher_info['amount'], $this->currency->getCode(), $this->currency->getValue($this->currency->getCode()));
                $data['name'] = $voucher_info['from_name'];
                $data['code'] = $voucher_info['code'];

                if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
                    $data['image'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
                } else {
                    $data['image'] = '';
                }

                $data['store_name'] = $this->config->get('config_name');
                $data['store_href'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG;
                $data['message'] = nl2br($voucher_info['message']);

                $subject = $this->emailtemplate->getSubject('Voucher', 'customer_5', $data);
                $message = $this->emailtemplate->getMessage('Voucher', 'customer_5', $data);

                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($voucher_info['to_email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHtml($message);
                $mail->send();
            }
        }
    }

    public function getTotalVouchers() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher");

        return $query->row['total'];
    }

    public function getTotalVouchersByVoucherThemeId($voucher_theme_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");

        return $query->row['total'];
    }

    public function getVoucherHistories($voucher_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT vh.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, vh.amount, vh.date_added FROM " . DB_PREFIX . "voucher_history vh LEFT JOIN `" . DB_PREFIX . "order` o ON (vh.order_id = o.order_id) WHERE vh.voucher_id = '" . (int)$voucher_id . "' ORDER BY vh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalVoucherHistories($voucher_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher_history WHERE voucher_id = '" . (int)$voucher_id . "'");

        return $query->row['total'];
    }
}
