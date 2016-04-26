<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelLocalisationZone extends Model {
    public function addZone($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "'");

        $this->cache->delete('zone');
        
        return $this->db->getLastId();
    }

    public function editZone($zone_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "' WHERE zone_id = '" . (int)$zone_id . "'");

        $this->cache->delete('zone');
    }

    public function deleteZone($zone_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

        $this->cache->delete('zone');
    }

    public function getZone($zone_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

        return $query->row;
    }

    public function getZones($data = array()) {
        $sql = "SELECT *, z.name, c.name AS country FROM " . DB_PREFIX . "zone z LEFT JOIN " . DB_PREFIX . "country c ON (z.country_id = c.country_id)";

        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_country']) && !is_null($data['filter_country'])) {
            $isWhere = 1;
            
            $_sql[] = "c.name LIKE '" . $this->db->escape($data['filter_country']) . "%'";
        }
        
        if (isset($data['filter_zone_name']) && !is_null($data['filter_zone_name'])) {
            $isWhere = 1;
            
            $_sql[] = "z.name LIKE '" . $this->db->escape($data['filter_zone_name']) . "%'";
        }
        
        if (isset($data['filter_zone_code']) && !is_null($data['filter_zone_code'])) {
            $isWhere = 1;
            
            $_sql[] = "z.code LIKE '" . $this->db->escape($data['filter_zone_code']) . "%'";
        }
        
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;
            
            $_sql[] = "z.status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }
        
        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }

        $sort_data = array(
            'c.name',
            'z.name',
            'z.code'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY c.name";
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

    public function getZoneStatus()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone");

        $zone_status = array();

        foreach ($query->rows as $row) {
            $zone_status[$row['zone_id']] = array(
                'status' => $row['status']
            );
        }

        return $zone_status;
    }
    
    public function getZonesByCountryId($country_id) {
        $zone_data = $this->cache->get('zone.' . (int)$country_id);

        if (!$zone_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

            $zone_data = $query->rows;

            $this->cache->set('zone.' . (int)$country_id, $zone_data);
        }

        return $zone_data;
    }

    public function getTotalZones() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone");

        return $query->row['total'];
    }
    
    public function getTotalZonesFilter($data) {
        $sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone");
        
        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_zone_name']) && !is_null($data['filter_zone_name'])) {
            $isWhere = 1;
            
            $_sql[] = "name LIKE '" . $this->db->escape($data['filter_zone_name']) . "%'";
        }
        
        if (isset($data['filter_zone_code']) && !is_null($data['filter_zone_code'])) {
            $isWhere = 1;
            
            $_sql[] = "code LIKE '" . $this->db->escape($data['filter_zone_code']) . "%'";
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

    public function getTotalZonesByCountryId($country_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "'");

        return $query->row['total'];
    }
}
