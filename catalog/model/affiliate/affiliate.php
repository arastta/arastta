<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelAffiliateAffiliate extends Model {
    public function addAffiliate($data) {
        $this->trigger->fire('pre.affiliate.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', code = '" . $this->db->escape(uniqid()) . "', commission = '" . (float)$this->config->get('config_affiliate_commission') . "', tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', status = '1', approved = '" . (int)!$this->config->get('config_affiliate_approval') . "', date_added = NOW()");

        $affiliate_id = $this->db->getLastId();

        $_code = $this->getAffiliateByEmail($data['email']);
        $code = $_code['code'];

        $data['code'] = $code;
        
        if (!$this->config->get('config_affiliate_approval')) {
            #Affiliate Registration Register
            $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_1', $data);
            $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_1', $data);
        } else {
            #Affiliate Registration Approve
            $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_3', $data);
            $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_3', $data);
        }

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo(html_entity_decode($this->request->post['email'], ENT_QUOTES, 'UTF-8'));
        $mail->setFrom(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();

        // Send to main admin email if new affiliate email is enabled
        if ($this->config->get('config_affiliate_mail')) {
            $mail->setTo($this->config->get('config_email'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            // Send to additional alert emails if new affiliate email is enabled
            $emails = explode(',', $this->config->get('config_mail_alert'));

            foreach ($emails as $email) {
                if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }

        $this->trigger->fire('post.affiliate.add', array(&$affiliate_id));

        return $affiliate_id;
    }

    public function editAffiliate($data) {
        $this->trigger->fire('pre.affiliate.edit', array(&$data));

        $affiliate_id = $this->affiliate->getId();

        $this->db->query("UPDATE " . DB_PREFIX . "affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        $this->trigger->fire('post.affiliate.edit', array(&$affiliate_id));
    }

    public function editPayment($data) {
        $this->trigger->fire('pre.affiliate.edit.payment', array(&$data));

        $affiliate_id = $this->affiliate->getId();

        $this->db->query("UPDATE " . DB_PREFIX . "affiliate SET tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        $this->trigger->fire('post.affiliate.edit.payment', array(&$affiliate_id));
    }

    public function editPassword($email, $password) {
        $affiliate_id = $this->affiliate->getId();

        $this->trigger->fire('pre.affiliate.edit.password', array(&$affiliate_id));

        $this->db->query("UPDATE " . DB_PREFIX . "affiliate SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        $this->trigger->fire('post.affiliate.edit.password', array(&$affiliate_id));
    }

    public function getAffiliate($affiliate_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        return $query->row;
    }

    public function getAffiliateByEmail($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getAffiliateByCode($code) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function getTotalAffiliatesByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }

    public function addCommission($affiliate_id, $amount = '', $order_id = 0) {
        $affiliate_info = $this->getAffiliate($affiliate_id);

        if ($affiliate_info) {
            $this->trigger->fire('pre.affiliate.add.commission');


            $this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_commission SET affiliate_id = '" . (int)$affiliate_id . "', order_id = '" . (float)$order_id . "', description = '" . $this->db->escape($this->language->get('text_order_id') . ' ' . $order_id) . "', amount = '" . (float)$amount . "', date_added = NOW()");

            $affiliate_commission_id = $this->db->getLastId();

            $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_5', $affiliate_info);
            $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_5', $affiliate_info);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo(html_entity_decode($this->request->post['email'], ENT_QUOTES, 'UTF-8'));
            $mail->setFrom(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $this->trigger->fire('post.affiliate.add.commission', array(&$affiliate_commission_id));
        }
    }

    public function deleteCommission($order_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_commission WHERE order_id = '" . (int)$order_id . "'");
    }

    public function getCommissionTotal($affiliate_id) {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "affiliate_commission WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        return $query->row['total'];
    }
    
    public function addLoginAttempt($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
        
        if (!$query->num_rows) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "affiliate_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE affiliate_login_id = '" . (int)$query->row['affiliate_login_id'] . "'");
        }            
    }    
    
    public function getLoginAttempts($email) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }
    
    public function deleteLoginAttempts($email) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function resetPasswordMail($email, $password) {
        $affiliate = $this->getAffiliateByEmail($email);

        $data = array (
            'firstname' => $affiliate['firstname'],
            'lastname'  => $affiliate['lastname'],
            'email'     => $affiliate['email'],
            'password'  => $password
        );

        $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_2', $data);
        $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_2', $data);

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setHTML($message);
        $mail->send();
    }
}
