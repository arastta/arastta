<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelFeedFacebookStore extends Model
{
    public function getProduct($product_id)
    {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            $query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
            $query->row['rating'] = (int)$query->row['rating'];

            return $query->row;
        } else {
            return false;
        }
    }

    public function getProducts($data, $feeds)
    {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        if ($data['filter_category_id'] > 0) {
            $product_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product as p LEFT JOIN " . DB_PREFIX . "product_to_category as pc ON p.product_id = pc.product_id WHERE pc.category_id = " . $data['filter_category_id']);

            if ($query->num_rows) {
                foreach ($query->rows as $row) {
                    $product_data[$row['product_id']] = $this->getProduct($row['product_id']);
                }
            }

            return $product_data;
        }

        $cache = md5(http_build_query($data));
        
        $product_data = $this->cache->get('facebook.store.product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);

        if ($data['start'] > 0) {
            $product_data = false;
        }

        if (!$product_data && $feeds) {
            foreach ($feeds as $feed) {
                $feed_type = explode('-', $feed);

                if ($feed_type[0] == 'product') {
                    $result[] = array(
                        'type' => 'product',
                        'code' => $feed_type[1]
                    );
                } else {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$feed_type[1] . "'");

                    if ($query->row) {
                        $module = unserialize($query->row['setting']);
                    } else {
                        $module = array();
                    }

                    $special_module = array(
                        'latest',
                        'special',
                        'bestseller',
                    );

                    if (isset($module) && isset($module['product'])) {
                        $limit = 0;

                        foreach ($module['product'] as $product_id) {
                            if (isset($module['limit']) && $module['limit'] <= $limit) {
                                break;
                            }

                            $result[] = array(
                                'type' => $feed_type[0],
                                'code' => $product_id
                            );

                            $limit++;
                        }
                    } elseif (in_array($feed_type[0], $special_module) && $module['status']) {
                        $module_data = $this->{$feed_type[0].'Product'}();

                        $setting = $module;
                        $limit = 0;

                        foreach ($module_data as $module) {
                            if (isset($module)) {
                                if (isset($setting['limit']) && $setting['limit'] <= $limit) {
                                    break;
                                }

                                $result[] = array(
                                    'type' => $feed_type[0],
                                    'code' => $module['product_id']
                                );

                                $limit++;
                            }
                        }
                    }
                }
            }

            if ($result) {
                $limit = 0;
                $start = 0;

                foreach ($result as $value) {
                    if ($data['start'] > 0 && $start < $data['start']) {
                        $start++;
                        continue;
                    }

                    if (isset($data['limit']) && $data['limit'] <= $limit) {
                        break;
                    }

                    $product_data[] = $this->getProduct($value['code']);

                    $limit++;
                }

                array($product_data);

                $this->cache->set('facebook.store.product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
            }
        }
        
        return $product_data;
    }
        
    public function getTotalProducts($data, $feeds)
    {
        if ($data['filter_category_id'] > 0) {
            $product_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product as p LEFT JOIN " . DB_PREFIX . "product_to_category as pc ON p.product_id = pc.product_id WHERE pc.category_id = " . $data['filter_category_id']);

            if ($query->num_rows) {
                foreach ($query->rows as $row) {
                    $product_data[$row['product_id']] = $this->getProduct($row['product_id']);
                }
            }

            return count($product_data);
        }
    
        $result = array();

        if ($feeds) {
            foreach ($feeds as $feed) {
                $feed_type = explode('-', $feed);

                if ($feed_type[0] == 'product') {
                    $result[] = array(
                        'type' => 'product',
                        'code' => $feed_type[1]
                    );
                } else {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int) $feed_type[1] . "'");

                    if ($query->row) {
                        $module = unserialize($query->row['setting']);
                    } else {
                        $module = array();
                    }

                    $special_module = array(
                        'latest',
                        'special',
                        'bestseller',
                    );

                    if (isset($module) && isset($module['product'])) {
                        $limit = 0;

                        foreach ($module['product'] as $product_id) {
                            if (isset($module['limit']) && $module['limit'] <= $limit) {
                                break;
                            }

                            $result[] = array(
                                'type' => $feed_type[0],
                                'code' => $product_id
                            );

                            $limit++;
                        }
                    } elseif (in_array($feed_type[0], $special_module) && $module['status']) {
                        $module_data = $this->{$feed_type[0] . 'Product'}();

                        $setting = $module;
                        $limit   = 0;

                        foreach ($module_data as $module) {
                            if (isset($module)) {
                                if (isset($setting['limit']) && $setting['limit'] <= $limit) {
                                    break;
                                }

                                $result[] = array(
                                    'type' => $feed_type[0],
                                    'code' => $module['product_id']
                                );

                                $limit++;
                            }
                        }
                    }
                }
            }
        }

        return count($result);
    }

    public function specialProduct()
    {
        $this->load->model('catalog/product');

        $results = $this->model_catalog_product->getProductSpecials(array());

        return $results;
    }

    public function bestsellerProduct()
    {
        $this->load->model('catalog/product');

        $results = $this->model_catalog_product->getBestSellerProducts(100);

        return $results;
    }

    public function latestProduct()
    {
        $this->load->model('catalog/product');

        $results = $this->model_catalog_product->getLatestProducts(100);

        return $results;
    }
}
