<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelCheckoutOrder extends Model {
    public function addOrder($data) {
        $this->trigger->fire('pre.order.add', array(&$data));

        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? serialize($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? serialize($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");

        $order_id = $this->db->getLastId();

        // Products
        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

                $order_product_id = $this->db->getLastId();

                foreach ($product['option'] as $option) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
                }
            }
        }

        // Gift Voucher
        $this->load->model('checkout/voucher');

        // Vouchers
        if (isset($data['vouchers'])) {
            foreach ($data['vouchers'] as $voucher) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

                $order_voucher_id = $this->db->getLastId();

                $voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $voucher);

                $this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
            }
        }

        // Totals
        if (isset($data['totals'])) {
            foreach ($data['totals'] as $total) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            }
        }

        $this->trigger->fire('post.order.add', array(&$order_id));

        return $order_id;
    }

    public function editOrder($order_id, $data) {
        $this->trigger->fire('pre.order.edit', array(&$data));

        // Void the order first
        $this->addOrderHistory($order_id, 0);

        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(serialize($data['custom_field'])) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(serialize($data['payment_custom_field'])) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(serialize($data['shipping_custom_field'])) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");

        // Products
        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

                $order_product_id = $this->db->getLastId();

                foreach ($product['option'] as $option) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
                }
            }
        }

        // Gift Voucher
        $this->load->model('checkout/voucher');

        $this->model_checkout_voucher->disableVoucher($order_id);

        // Vouchers
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

        if (isset($data['vouchers'])) {
            foreach ($data['vouchers'] as $voucher) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

                $order_voucher_id = $this->db->getLastId();

                $voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $voucher);

                $this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
            }
        }

        // Totals
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

        if (isset($data['totals'])) {
            foreach ($data['totals'] as $total) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            }
        }

        $this->trigger->fire('post.order.edit', array(&$order_id));
    }

    public function deleteOrder($order_id) {
        $this->trigger->fire('pre.order.delete', array(&$order_id));

        // Void the order first
        $this->addOrderHistory($order_id, 0);

        $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_fraud` WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");

        $this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_commission` WHERE order_id = '" . (int)$order_id . "'");

        // Gift Voucher
        $this->load->model('checkout/voucher');

        $this->model_checkout_voucher->disableVoucher($order_id);

        $this->trigger->fire('post.order.delete', array(&$order_id));
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

        if ($order_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
            }

            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

            return array(
                'order_id'                => $order_query->row['order_id'],
                'invoice_no'              => $order_query->row['invoice_no'],
                'invoice_prefix'          => $order_query->row['invoice_prefix'],
                'store_id'                => $order_query->row['store_id'],
                'store_name'              => $order_query->row['store_name'],
                'store_url'               => $order_query->row['store_url'],
                'customer_id'             => $order_query->row['customer_id'],
                'firstname'               => $order_query->row['firstname'],
                'lastname'                => $order_query->row['lastname'],
                'email'                   => $order_query->row['email'],
                'telephone'               => $order_query->row['telephone'],
                'fax'                     => $order_query->row['fax'],
                'custom_field'            => unserialize($order_query->row['custom_field']),
                'payment_firstname'       => $order_query->row['payment_firstname'],
                'payment_lastname'        => $order_query->row['payment_lastname'],
                'payment_company'         => $order_query->row['payment_company'],
                'payment_address_1'       => $order_query->row['payment_address_1'],
                'payment_address_2'       => $order_query->row['payment_address_2'],
                'payment_postcode'        => $order_query->row['payment_postcode'],
                'payment_city'            => $order_query->row['payment_city'],
                'payment_zone_id'         => $order_query->row['payment_zone_id'],
                'payment_zone'            => $order_query->row['payment_zone'],
                'payment_zone_code'       => $payment_zone_code,
                'payment_country_id'      => $order_query->row['payment_country_id'],
                'payment_country'         => $order_query->row['payment_country'],
                'payment_iso_code_2'      => $payment_iso_code_2,
                'payment_iso_code_3'      => $payment_iso_code_3,
                'payment_address_format'  => $order_query->row['payment_address_format'],
                'payment_custom_field'    => unserialize($order_query->row['payment_custom_field']),
                'payment_method'          => $order_query->row['payment_method'],
                'payment_code'            => $order_query->row['payment_code'],
                'shipping_firstname'      => $order_query->row['shipping_firstname'],
                'shipping_lastname'       => $order_query->row['shipping_lastname'],
                'shipping_company'        => $order_query->row['shipping_company'],
                'shipping_address_1'      => $order_query->row['shipping_address_1'],
                'shipping_address_2'      => $order_query->row['shipping_address_2'],
                'shipping_postcode'       => $order_query->row['shipping_postcode'],
                'shipping_city'           => $order_query->row['shipping_city'],
                'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
                'shipping_zone'           => $order_query->row['shipping_zone'],
                'shipping_zone_code'      => $shipping_zone_code,
                'shipping_country_id'     => $order_query->row['shipping_country_id'],
                'shipping_country'        => $order_query->row['shipping_country'],
                'shipping_iso_code_2'     => $shipping_iso_code_2,
                'shipping_iso_code_3'     => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_custom_field'   => unserialize($order_query->row['shipping_custom_field']),
                'shipping_method'         => $order_query->row['shipping_method'],
                'shipping_code'           => $order_query->row['shipping_code'],
                'comment'                 => $order_query->row['comment'],
                'total'                   => $order_query->row['total'],
                'order_status_id'         => $order_query->row['order_status_id'],
                'order_status'            => $order_query->row['order_status'],
                'affiliate_id'            => $order_query->row['affiliate_id'],
                'commission'              => $order_query->row['commission'],
                'language_id'             => $order_query->row['language_id'],
                'language_code'           => $language_code,
                'language_directory'      => $language_directory,
                'currency_id'             => $order_query->row['currency_id'],
                'currency_code'           => $order_query->row['currency_code'],
                'currency_value'          => $order_query->row['currency_value'],
                'ip'                      => $order_query->row['ip'],
                'forwarded_ip'            => $order_query->row['forwarded_ip'],
                'user_agent'              => $order_query->row['user_agent'],
                'accept_language'         => $order_query->row['accept_language'],
                'date_modified'           => $order_query->row['date_modified'],
                'date_added'              => $order_query->row['date_added']
            );
        } else {
            return false;
        }
    }

    public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false) {
        $this->trigger->fire('pre.order.history.add', array(&$order_id));

        $order_info = $this->getOrder($order_id);

        if ($order_info) {
            // Fraud Detection
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

            if ($customer_info && $customer_info['safe']) {
                $safe = true;
            } else {
                $safe = false;
            }

            if ($this->config->get('config_fraud_detection')) {
                $this->load->model('checkout/fraud');

                $risk_score = $this->model_checkout_fraud->getFraudScore($order_info);

                if (!$safe && $risk_score > $this->config->get('config_fraud_score')) {
                    $order_status_id = $this->config->get('config_fraud_status_id');
                }
            }

            // Ban IP
            if (!$safe) {
                $status = false;

                if ($order_info['customer_id']) {
                    $results = $this->model_account_customer->getIps($order_info['customer_id']);

                    foreach ($results as $result) {
                        if ($this->model_account_customer->isBanIp($result['ip'])) {
                            $status = true;

                            break;
                        }
                    }
                } else {
                    $status = $this->model_account_customer->isBanIp($order_info['ip']);
                }

                if ($status) {
                    $order_status_id = $this->config->get('config_order_status_id');
                }
            }

            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

            // If current order status is not processing or complete but new status is processing or complete then commence completing the order
            if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) &&
                in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
                // Stock subtraction
                $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

                foreach ($order_product_query->rows as $order_product) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

                    $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");

                    foreach ($order_option_query->rows as $option) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
                    }
                }
                
                // Send out of stock email to admin
                if ($this->config->get('config_stock_mail')) {
                    $stock_query = $this->db->query("SELECT COUNT(DISTINCT product_id) AS total FROM " . DB_PREFIX . "product WHERE quantity < 1");
                    $outofstock = $stock_query->row['total'];
                    
                    if ($outofstock > 0) {
                        $stock_data = array('outofstock' => $outofstock);
                        
                        $subject = $this->emailtemplate->getSubject('Stock', 'stock_1', $stock_data);
                        $message = $this->emailtemplate->getMessage('Stock', 'stock_1', $stock_data);

                        $mail = new Mail($this->config->get('config_mail'));
                        $mail->setFrom($this->config->get('config_email'));
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setTo($this->config->get('config_email'));
                        $mail->setSubject($subject);
                        $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                        $mail->send();
                        
                        if ($this->config->get('config_alert_emails')) {
                            $emails = explode(',', $this->config->get('config_alert_emails'));
                            
                            foreach ($emails as $email) {
                                if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                                    $mail->setTo($email);
                                    $mail->send();
                                }
                            }
                        }
                    }
                }

                // Redeem coupon, vouchers and reward points
                $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/' . $order_total['code']);

                    if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                        $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
                    }
                }

                // Add commission if sale is linked to affiliate referral.
                if ($order_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
                    $this->load->model('affiliate/affiliate');

                    $this->model_affiliate_affiliate->addCommission($order_info['affiliate_id'], $order_info['commission'], $order_id);
                }
            }

            // If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
            if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
                // Restock
                $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

                foreach ($product_query->rows as $product) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

                    $option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

                    foreach ($option_query->rows as $option) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
                    }
                }

                // Remove coupon, vouchers and reward points history
                $this->load->model('account/order');

                $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/' . $order_total['code']);

                    if (method_exists($this->{'model_total_' . $order_total['code']}, 'unconfirm')) {
                        $this->{'model_total_' . $order_total['code']}->unconfirm($order_id);
                    }
                }

                // Remove commission if sale is linked to affiliate referral.
                if ($order_info['affiliate_id']) {
                    $this->load->model('affiliate/affiliate');

                    $this->model_affiliate_affiliate->deleteCommission($order_id);
                }
            }

            $this->cache->delete('product');

            if (in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) || $notify) {
                $order_status = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

                if ($order_status->num_rows) {
                    $order_status = $order_status->row['name'];
                } else {
                    $order_status = '';
                }

                // Account Href
                $order_href = '';

                if ($order_info['customer_id']) {
                    $order_href = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_info['order_id'];
                }

                #Address Shipping and Payment
                $totals = array();
                $tax_amount = 0;

                if (strlen($order_info['shipping_firstname']) <> 0) {
                    $address = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'] . '<br />' . ((strlen($order_info['shipping_company']) <> 0) ? $order_info['shipping_company'] . '<br />' : '') . '' . $order_info['shipping_address_1'] . '<br />' . $order_info['shipping_city'] . ' ' . $order_info['shipping_postcode'] . '<br />' . $order_info['shipping_zone'] . ' ' . $order_info['shipping_country'];
                } else {
                    $address = '';
                }

                if (strlen($order_info['payment_firstname']) <> 0) {
                    $payment_address = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'] . '<br />' . ((strlen($order_info['payment_company']) <> 0) ? $order_info['payment_company'] . '<br />' : '') . '' . $order_info['payment_address_1'] . '<br />' . $order_info['payment_city'] . ' ' . $order_info['payment_postcode'] . '<br />' . $order_info['payment_zone'] . ' ' . $order_info['payment_country'];
                } else {
                    $payment_address = '';
                }

                $order_total = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_info['order_id'] . "'");

                foreach ($order_total->rows as $total) {
                    $totals[$total['code']][] = array(
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                        'value' => $total['value']
                    );

                    if ($total['code'] == 'tax') {
                        $tax_amount += $total['value'];
                    }
                }

                $special = NULL;

                $data = array(
                    'template_id'       => 'order_' . (int)$order_status_id,
                    'order_info'        => $order_info,
                    'address'           => $address,
                    'payment_address'   => $payment_address,
                    'special'           => $special,
                    'order_href'        => $order_href,
                    'order_status'      => $order_status,
                    'totals'            => $totals,
                    'tax_amount'        => $tax_amount,
                    'invoice_no'        => !empty($invoice_no) ? $invoice_no : '',
                    'comment'            => nl2br($comment)
                );

                $subject = $this->emailtemplate->getSubject('OrderAll', 'order_' . (int)$order_status_id, $data);
                $message = $this->emailtemplate->getMessage('OrderAll', 'order_' . (int)$order_status_id, $data);

                $getTotal = $order_total->rows;

                $textData = array(
                    'order_info'   => $order_info,
                    'order_id'     => $order_id,
                    'order_status' => $order_status,
                    'comment'      => $comment,
                    'notify'       => $notify,
                    'getProducts'  => $this->getOrderProducts($order_id),
                    'getVouchers'  => $this->getOrderVouchers($order_id),
                    'getTotal'     => $getTotal
                );

                $text = $this->emailtemplate->getText('Order', 'order',$textData);
                
                #Send Email
                if ((!$order_info['order_status_id'] && $order_status_id) || ($order_info['order_status_id'] && $order_status_id && $notify)) {
                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($order_info['email']);
                    $mail->setFrom($this->config->get('config_email'));
                    $mail->setSender($order_info['store_name']);
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->setText($text);
                    $mail->send();
                }

                if ($this->config->get('config_order_mail')) {

                    if (!isset($mail)) {
                        $mail = new Mail($this->config->get('config_mail'));
                        $mail->setFrom($this->config->get('config_email'));
                        $mail->setSender($order_info['store_name']);
                    }

                    $mail->setSubject($subject);
                    $mail->setHTML($message);
                    $mail->setText($text);
                    $mail->setTo($this->config->get('config_email'));
                    $mail->send();

                    $emails = explode(',', $this->config->get('config_alert_emails'));

                    foreach ($emails as $email) {
                        if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                            $mail->setTo($email);
                            $mail->send();
                        }
                    }
                }
            }
        }
        // If order status in the complete range create any vouchers that where in the order need to be made available.
        if (!in_array($order_info['order_status_id'], $this->config->get('config_complete_status')) && in_array($order_status_id, $this->config->get('config_complete_status'))) {
            // Send out any gift voucher mails
            $this->load->model('checkout/voucher');

            $this->model_checkout_voucher->confirm($order_id);
        }

        $this->trigger->fire('post.order.history.add', array(&$order_id));
    }

    protected function _prepareProductSpecial($customer_group_id, $limit) {
        $special = array();
        $product_special = $this->_getProductSpecial((int)$customer_group_id, $limit);

        if (sizeof($product_special) <> 0) {
            foreach ($product_special as $product) {
                $discount = round((($product['price'] - $product['special'])/$product['price'])*100, 0);

                $special[] = '<a href="' . $this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL') . '">' . $product['name'] . '</a> (<font color="red">-' . $discount . '%</font>)';
            }
        }

        return $special;
    }

    protected function _getProductSpecial($customer_group_id, $limit = 5) {
        $sql = "SELECT DISTINCT ps.product_id, ps.price AS special, p.price, pd.name, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id ORDER BY p.sort_order ASC LIMIT " . (int)$limit;

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product_data[] = array(
                'product_id' => $result['product_id'],
                'price'      => $result['price'],
                'special'    => $result['special'],
                'name'       => $result['name']
            );
        }

        return $product_data;
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

        return $query->rows;
    }
}
