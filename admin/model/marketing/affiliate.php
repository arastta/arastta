<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelMarketingAffiliate extends Model {
    public function addAffiliate($data) {
        $this->trigger->fire('pre.admin.affiliate.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', code = '" . $this->db->escape($data['code']) . "', commission = '" . (float)$data['commission'] . "', tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

        $affiliate_id = $this->db->getLastId();

        if(!empty($data['send_email'])){
            $this->sendAffiliateRegisterMail($data);
        }

        $this->trigger->fire('post.admin.affiliate.add', array(&$affiliate_id));

        return $affiliate_id;
    }

    public function editAffiliate($affiliate_id, $data) {
        $this->trigger->fire('pre.admin.affiliate.edit', array(&$data));

        $this->db->query("UPDATE " . DB_PREFIX . "affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', code = '" . $this->db->escape($data['code']) . "', commission = '" . (float)$data['commission'] . "', tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', status = '" . (int)$data['status'] . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        if ($data['password']) {
            $this->db->query("UPDATE " . DB_PREFIX . "affiliate SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");
        }

        $this->trigger->fire('post.admin.affiliate.edit', array(&$affiliate_id));
    }

    public function deleteAffiliate($affiliate_id) {
        $this->trigger->fire('pre.admin.affiliate.delete', array(&$affiliate_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_activity WHERE affiliate_id = '" . (int)$affiliate_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_commission WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        $this->trigger->fire('post.admin.affiliate.delete', array(&$affiliate_id));
    }

    public function getAffiliate($affiliate_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        return $query->row;
    }

    public function getAffiliateByEmail($email) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getAffiliates($data = array()) {
        $sql = "SELECT *, CONCAT(a.firstname, ' ', a.lastname) AS name, (SELECT SUM(at.amount) FROM " . DB_PREFIX . "affiliate_commission at WHERE at.affiliate_id = a.affiliate_id GROUP BY at.affiliate_id) AS balance FROM " . DB_PREFIX . "affiliate a";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(a.firstname, ' ', a.lastname) LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "LCASE(a.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
        }

        if (!empty($data['filter_code'])) {
            $implode[] = "a.code = '" . $this->db->escape($data['filter_code']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "a.status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'a.email',
            'a.code',
            'a.status',
            'a.approved',
            'a.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

    public function approve($affiliate_id) {

        $affiliate_info = $this->getAffiliate($affiliate_id);

        if ($affiliate_info) {
            $this->trigger->fire('pre.admin.affiliate.approve', array(&$affiliate_id));

            $this->db->query("UPDATE " . DB_PREFIX . "affiliate SET approved = '1' WHERE affiliate_id = '" . (int)$affiliate_id . "'");

            $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_4', $affiliate_info);
            $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_4', $affiliate_info);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo(html_entity_decode($affiliate_info['email'], ENT_QUOTES, 'UTF-8'));
            $mail->setFrom(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $this->trigger->fire('post.admin.affiliate.approve', array(&$affiliate_id));
        }
    }

    public function getAffiliatesByNewsletter() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE newsletter = '1' ORDER BY firstname, lastname, email");

        return $query->rows;
    }

    public function getTotalAffiliates($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "LCASE(email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalAffiliatesAwaitingApproval() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE status = '0' OR approved = '0'");

        return $query->row['total'];
    }

    public function getTotalAffiliatesByCountryId($country_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE country_id = '" . (int)$country_id . "'");

        return $query->row['total'];
    }

    public function getTotalAffiliatesByZoneId($zone_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE zone_id = '" . (int)$zone_id . "'");

        return $query->row['total'];
    }

    public function addCommission($affiliate_id, $description = '', $amount = '', $order_id = 0) {
        $affiliate_info = $this->getAffiliate($affiliate_id);

        if ($affiliate_info) {
            $this->trigger->fire('pre.admin.affiliate.commission.add', array(&$affiliate_id));

            $this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_commission SET affiliate_id = '" . (int)$affiliate_id . "', order_id = '" . (float)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");

            $affiliate_commission_id = $this->db->getLastId();

            $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_5', $affiliate_info);
            $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_5', $affiliate_info);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo(html_entity_decode($affiliate_info['email'], ENT_QUOTES, 'UTF-8'));
            $mail->setFrom(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $this->trigger->fire('post.admin.affiliate.commission.add', array(&$affiliate_commission_id));

            return $affiliate_commission_id;
        }
    }

    public function deleteCommission($order_id) {
        $this->trigger->fire('pre.admin.affiliate.commission.delete', array(&$order_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_commission WHERE order_id = '" . (int)$order_id . "'");

        $this->trigger->fire('post.admin.affiliate.commission.delete', array(&$order_id));
    }

    public function getCommissions($affiliate_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate_commission WHERE affiliate_id = '" . (int)$affiliate_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalCommissions($affiliate_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "affiliate_commission WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        return $query->row['total'];
    }

    public function getCommissionTotal($affiliate_id) {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "affiliate_commission WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        return $query->row['total'];
    }

    public function getTotalCommissionsByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate_commission WHERE order_id = '" . (int)$order_id . "'");

        return $query->row['total'];
    }
    
    public function getTotalLoginAttempts($email) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_login` WHERE `email` = '" . $this->db->escape($email) . "'");

        return $query->row;
    }    

    public function deleteLoginAttempts($email) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_login` WHERE `email` = '" . $this->db->escape($email) . "'");
    }

    public function sendAffiliateRegisterMail($data){

        $code = $this->getAffiliateByEmail($data['email']);

        $data['code'] = $code['code'];

        if (!$this->config->get('config_affiliate_approval')) {
            #Affilate Registration Register
            $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_1', $data);
            $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_1', $data);
        } else {
            #Affilate Registration Approve
            $subject = $this->emailtemplate->getSubject('Affiliate', 'affiliate_3', $data);
            $message = $this->emailtemplate->getMessage('Affiliate', 'affiliate_3', $data);
        }

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo(html_entity_decode($data['email'], ENT_QUOTES, 'UTF-8'));
        $mail->setFrom(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();
    }

}
