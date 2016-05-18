<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelAccountCustomer extends Model {
    public function addCustomer($data) {
        $this->trigger->fire('pre.customer.add', array(&$data));

        if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $data['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

        $customer_id = $this->db->getLastId();

        if (!empty($data['country_id'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

            $address_id = $this->db->getLastId();

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
        }

        $credit = $this->getCreditTotal($customer_id);

        $data['credit'] = $credit;

        #Get Email Template
        if(!$customer_group_info['approval']){
            #Customer Registration Register
            $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
        } else {
            #Customer Registration Approve
            $subject = $this->emailtemplate->getSubject('Customer', 'customer_2', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_2', $data);
        }

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setHTML($message);
        $mail->send();

        // Send to main admin email if new account email is enabled
        if ($this->config->get('config_account_mail')) {
            $mail->setTo($this->config->get('config_email'));
            $mail->send();

            $emails = explode(',', $this->config->get('config_alert_emails'));

            foreach ($emails as $email) {
                if (strlen($email) > 0 && preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }

        $this->trigger->fire('post.customer.add', array(&$customer_id));

        return $customer_id;
    }

    public function editCustomer($data) {
        $this->trigger->fire('pre.customer.edit', array(&$data));

        $customer_id = $this->customer->getId();

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");

        $this->trigger->fire('post.customer.edit', array(&$customer_id));
    }

    public function editPassword($email, $password) {
        $this->trigger->fire('pre.customer.edit.password');

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        $this->trigger->fire('post.customer.edit.password');
    }

    public function editNewsletter($newsletter) {
        $this->trigger->fire('pre.customer.edit.newsletter');

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

        $this->trigger->fire('post.customer.edit.newsletter');
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row;
    }

    public function getCustomerByEmail($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getCustomerByToken($token) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

        return $query->row;
    }

    public function getTotalCustomersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }

    public function getIps($customer_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->rows;
    }

    public function isBanIp($ip) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

        return $query->num_rows;
    }
    
    public function addLoginAttempt($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
        
        if (!$query->num_rows) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
        }            
    }    
    
    public function getLoginAttempts($email) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }
    
    public function deleteLoginAttempts($email) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }
    
    public function resetPasswordMail($email, $password) {
        $customer = $this->getCustomerByEmail($email);

        $credit = $this->getCreditTotal($customer['customer_id']);

        $data = array (
            'firstname' => $customer['firstname'],
            'lastname'  => $customer['lastname'],
            'email'     => $customer['email'],
            'credit'    => $credit,
            'password'  => $password
        );

        #Reset Password id = 3
        $subject = $this->emailtemplate->getSubject('Customer', 'customer_3', $data);
        $message = $this->emailtemplate->getMessage('Customer', 'customer_3', $data);

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setHTML($message);
        $mail->send();
    }

    public function getCreditTotal($customer_id) {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['total'];
    }
}
