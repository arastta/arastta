<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelLocalisationCountry extends Model {
    public function addCountry($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "'");

        $this->cache->delete('country');
        
        return $this->db->getLastId();
    }

    public function editCountry($country_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "' WHERE country_id = '" . (int)$country_id . "'");

        $this->cache->delete('country');
    }

    public function deleteCountry($country_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

        $this->cache->delete('country');
    }

    public function getCountry($country_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

        return $query->row;
    }

    public function getCountries($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "country";
            
            $isWhere = 0;
            $_sql = array();    
            
            if (isset($data['filter_country']) && !is_null($data['filter_country'])) {
                $isWhere = 1;
                
                $_sql[] = "name LIKE '" . $this->db->escape($data['filter_country']) . "%'";
            }
            
            if (isset($data['filter_iso_code_2']) && !is_null($data['filter_iso_code_2'])) {
                $isWhere = 1;
                
                $_sql[] = "iso_code_2 LIKE '" . $this->db->escape($data['filter_iso_code_2']) . "%'";
            }
            
            if (isset($data['filter_iso_code_3']) && !is_null($data['filter_iso_code_3'])) {
                $isWhere = 1;
                
                $_sql[] = "iso_code_3 LIKE '" . $this->db->escape($data['filter_iso_code_3']) . "%'";
            }
            
            if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
                $isWhere = 1;
                
                $_sql[] = "status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
            }
            
            if($isWhere) {
                $sql .= " WHERE " . implode(" AND ", $_sql);
            }

            $sort_data = array(
                'name',
                'iso_code_2',
                'iso_code_3'
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
        } else {
            $country_data = $this->cache->get('country');

            if (!$country_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");

                $country_data = $query->rows;

                $this->cache->set('country', $country_data);
            }

            return $country_data;
        }
    }

    public function getTotalCountries() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");

        return $query->row['total'];
    }
    
    public function getTotalCountriesFilter($data) {
        $sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");
        
        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_country']) && !is_null($data['filter_country'])) {
            $isWhere = 1;
            
            $_sql[] = "name LIKE '" . $this->db->escape($data['filter_country']) . "%'";
        }
        
        if (isset($data['filter_iso_code_2']) && !is_null($data['filter_iso_code_2'])) {
            $isWhere = 1;
            
            $_sql[] = "iso_code_2 LIKE '" . $this->db->escape($data['filter_iso_code_2']) . "%'";
        }
        
        if (isset($data['filter_iso_code_3']) && !is_null($data['filter_iso_code_3'])) {
            $isWhere = 1;
            
            $_sql[] = "iso_code_3 LIKE '" . $this->db->escape($data['filter_iso_code_3']) . "%'";
        }
        
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;
            
            $_sql[] = "status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }
        
        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }
        
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
