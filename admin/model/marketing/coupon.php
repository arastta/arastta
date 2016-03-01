<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelMarketingCoupon extends Model {
    public function addCoupon($data) {
        $this->trigger->fire('pre.admin.coupon.add', array(&$data));

        $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

        $coupon_id = $this->db->getLastId();

        if (isset($data['coupon_product'])) {
            foreach ($data['coupon_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
            }
        }

        if (isset($data['coupon_category'])) {
            foreach ($data['coupon_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
            }
        }

        $this->trigger->fire('post.admin.coupon.add', array(&$coupon_id));

        return $coupon_id;
    }

    public function editCoupon($coupon_id, $data) {
        $this->trigger->fire('pre.admin.coupon.edit', array(&$data));

        $this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "' WHERE coupon_id = '" . (int)$coupon_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

        if (isset($data['coupon_product'])) {
            foreach ($data['coupon_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");

        if (isset($data['coupon_category'])) {
            foreach ($data['coupon_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
            }
        }

        $this->trigger->fire('post.admin.coupon.edit', array(&$coupon_id));
    }

    public function deleteCoupon($coupon_id) {
        $this->trigger->fire('pre.admin.coupon.delete', array(&$coupon_id));

        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");

        $this->trigger->fire('post.admin.coupon.delete', array(&$coupon_id));
    }

    public function getCoupon($coupon_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");

        return $query->row;
    }

    public function getCouponByCode($code) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function getCoupons($data = array()) {
        $sql = "SELECT coupon_id, name, code, discount, date_start, date_end, status FROM " . DB_PREFIX . "coupon";

        $isWhere = 0;
        $_sql = array();        
                
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            
            $_sql[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_code']) && !is_null($data['filter_code'])) {
            $isWhere = 1;

            $_sql[] = "code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
        }
        
        if (isset($data['filter_date_start']) && !is_null($data['filter_date_start'])) {
            $isWhere = 1;

            $_sql[] = "date_start = '" . $this->db->escape($data['filter_date_start']) . "'" ;
        } 

        if (isset($data['filter_date_end']) && !is_null($data['filter_date_end'])) {
            $isWhere = 1;

            $_sql[] = "date_end = '" . $this->db->escape($data['filter_date_end']) . "'" ;
        }
        
        if (isset($data['filter_discount']) && !is_null($data['filter_discount'])) {
            $isWhere = 1;

            $_sql[] = "discount = '" . $this->db->escape($data['filter_discount']) . "'" ;
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "status = '" . $this->db->escape($data['filter_status']) . "'" ;
        }
        
        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }
        
        $sort_data = array(
            'name',
            'code',
            'discount',
            'date_start',
            'date_end',
            'status'
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

    public function getCouponProducts($coupon_id) {
        $coupon_product_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

        foreach ($query->rows as $result) {
            $coupon_product_data[] = $result['product_id'];
        }

        return $coupon_product_data;
    }

    public function getCouponCategories($coupon_id) {
        $coupon_category_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");

        foreach ($query->rows as $result) {
            $coupon_category_data[] = $result['category_id'];
        }

        return $coupon_category_data;
    }

    public function getTotalCoupons() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");

        return $query->row['total'];
    }
    
    public function getTotalCouponsFilter($data) {
        $sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");
        
        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            
            $_sql[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_code']) && !is_null($data['filter_code'])) {
            $isWhere = 1;

            $_sql[] = "code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
        }
        
        if (isset($data['filter_date_start']) && !is_null($data['filter_date_start'])) {
            $isWhere = 1;

            $_sql[] = "date_start = '" . $this->db->escape($data['filter_date_start']) . "'" ;
        } 

        if (isset($data['filter_date_end']) && !is_null($data['filter_date_end'])) {
            $isWhere = 1;

            $_sql[] = "date_end = '" . $this->db->escape($data['filter_date_end']) . "'" ;
        }
        
        if (isset($data['filter_discount']) && !is_null($data['filter_discount'])) {
            $isWhere = 1;

            $_sql[] = "discount = '" . $this->db->escape($data['filter_discount']) . "'" ;
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "status = '" . $this->db->escape($data['filter_status']) . "'" ;
        }
        
        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }
        
        
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCouponHistories($coupon_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, ch.amount, ch.date_added FROM " . DB_PREFIX . "coupon_history ch LEFT JOIN " . DB_PREFIX . "customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int)$coupon_id . "' ORDER BY ch.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalCouponHistories($coupon_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");

        return $query->row['total'];
    }
}
