<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelSystemEmailtemplate extends Model
{

    public function editEmailTemplate($email_template, $email_template_description)
    {
        $this->trigger->fire('pre.admin.emailtemplate.edit', array(&$email_template_description));

        $item = explode("_", $email_template);
        
        $query = $this->db->query("SELECT id FROM " . DB_PREFIX . "email WHERE type = '" . $item[0] ."' AND text_id = '" . (int)$item[1] ."'");

        $email_id = $query->row['id'];

        $this->db->query("DELETE FROM " . DB_PREFIX . "email_description WHERE email_id = '" . (int)$email_id . "'");

        foreach ($email_template_description as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "email_description SET  email_id = '" . (int)$email_id . "', name = '". $this->db->escape($value['name']) . "', description = '". $this->db->escape($value['description']) . "', status = '1', language_id = '". (int)$language_id . "'");
        }

        $this->trigger->fire('post.admin.emailtemplate.edit', array(&$email_template_description));
    }

    public function getEmailTemplate($email_template)
    {
        $item = explode("_", $email_template);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "email AS e LEFT JOIN " . DB_PREFIX . "email_description AS ed ON ed.email_id = e.id WHERE e.type = '" . $item[0] . "' AND e.text_id = '" . $item[1] . "'");
        
        foreach ($query->rows as $result) {
            $email_template_data[$result['language_id']] = array(
                'text'         => $result['text'],
                'text_id'      => $result['text_id'],
                'type'         => $result['type'],
                'context'      => $result['context'],
                'name'         => $result['name'],
                'description'  => $result['description'],
                'status'       => $result['status']
            );
        }

        return $email_template_data;
    }

    public function getEmailTemplates($data = array())
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "email` AS e";
        
        $isWhere = 0;
        $_sql = array();
        
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            
            $sql .= " LEFT JOIN `" . DB_PREFIX . "email_description` AS ed ON e.id = ed.email_id ";
            $_sql[] = "ed.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
        
        if (isset($data['filter_text']) && !is_null($data['filter_text'])) {
            $isWhere = 1;
            
            $_sql[] = "e.text LIKE '" . $this->db->escape($data['filter_text']) . "%'";
        }

        if (isset($data['filter_context']) && !is_null($data['filter_context'])) {
            $isWhere = 1;
            
            $_sql[] = "e.context LIKE '" . $this->db->escape($data['filter_context']) . "%'";
        }

        if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
            $isWhere = 1;
            
            $filterType = $this->getEmailTypes($data['filter_type']);
            
            $_sql[] = "e.type = '" . $this->db->escape($filterType) . "'";
        }
                
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;
            
            $_sql[] = "e.status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }

        if ($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }


        $sort_data = array(
            'name',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY e.type";
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

        if (!empty($query->num_rows)) {
            foreach ($query->rows as $key => $email_temp) {
                if ($email_temp['type'] == 'order') {
                    $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE order_status_id ='". $email_temp['text_id'] ."' AND language_id ='" . $this->config->get('config_language_id') ."'") ;

                    if (!empty($result->num_rows) && !empty($result->row['name'])) {
                        $query->rows[$key]['text'] = $result->row['name'];
                    }
                }
            }
        }

        return $query->rows;
    }
    
    protected function getEmailTypes($item)
    {
        $result = array ( 'order', 'customer', 'affiliate', 'review', 'contact', 'cron', 'mail' );

        if ($item < 1  || $item > 7) {
            $item = 1;
        }

        return $result[$item-1];
    }
    
    public function getEmailTemplatesStores($email_template)
    {
        $manufacturer_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE email_template = '" . (int)$email_template . "'");

        foreach ($query->rows as $result) {
            $manufacturer_store_data[] = $result['store_id'];
        }

        return $manufacturer_store_data;
    }

    public function getTotalEmailTemplates($data)
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "email AS e";

        $isWhere = 0;
        $_sql = array();
        
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            
            $sql .= " LEFT JOIN `" . DB_PREFIX . "email_description` AS ed ON e.id = ed.email_id ";
            $_sql[] = "ed.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
        
        if (isset($data['filter_text']) && !is_null($data['filter_text'])) {
            $isWhere = 1;
            
            $_sql[] = "e.text LIKE '" . $this->db->escape($data['filter_text']) . "%'";
        }

        if (isset($data['filter_context']) && !is_null($data['filter_context'])) {
            $isWhere = 1;
            
            $_sql[] = "e.context LIKE '" . $this->db->escape($data['filter_context']) . "%'";
        }

        if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
            $isWhere = 1;
            
            $filterType = $this->getEmailTypes($data['filter_type']);
            
            $_sql[] = "e.type = '" . $this->db->escape($filterType) . "'";
        }
                
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;
            
            $_sql[] = "e.status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }
                
        if ($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }

        $query = $this->db->query($sql);
        
        return $query->row['total'];
    }

    public function getShortCodes($email_template)
    {
        $item = explode("_", $email_template);

        $result = array();

        switch ($item[0]) {
            case 'admin':
                $codes = $this->emailtemplate->getLoginFind();
                break;
            case 'affiliate':
                $codes = $this->emailtemplate->getAffiliateFind();
                break;
            case 'contact':
                $codes = $this->emailtemplate->getContactFind();
                break;
            case 'customer':
                $codes = $this->emailtemplate->getCustomerFind();
                break;
            case 'order':
                $codes = $this->emailtemplate->getOrderAllFind();
                break;
            case 'reviews':
                $codes = $this->emailtemplate->getReviewFind();
                break;
            case 'voucher':
                $codes = $this->emailtemplate->getVoucherFind();
                break;
            case 'invoice':
                $codes = $this->emailtemplate->getInvoiceFind();
                break;
            case 'stock':
                $codes = $this->emailtemplate->getStockFind();
                break;
            case 'return':
                $codes = $this->emailtemplate->getReturnFind();
                break;
        }

        foreach ($codes as $code) {
            $result[] = array(
                'code' => $code,
                'text' => $this->language->get($code)
            );
        }

        return $result;
    }

    public function getDemoData()
    {
        $this->load->language('mail/shortcode');

        $data = $this->language->all();

        $this->trigger->fire('post.emailtemplate.demo.data', array(&$data));

        return $data;
    }
}
