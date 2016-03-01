<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelLocalisationGeoZone extends Model {
    public function addGeoZone($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "geo_zone SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");

        $geo_zone_id = $this->db->getLastId();

        if (isset($data['zone_to_geo_zone'])) {
            foreach ($data['zone_to_geo_zone'] as $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '" . (int)$value['country_id'] . "', zone_id = '" . (int)$value['zone_id'] . "', geo_zone_id = '" . (int)$geo_zone_id . "', date_added = NOW()");
            }
        }

        $this->cache->delete('geo_zone');
        
        return $geo_zone_id;
    }

    public function editGeoZone($geo_zone_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "geo_zone SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', date_modified = NOW() WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

        if (isset($data['zone_to_geo_zone'])) {
            foreach ($data['zone_to_geo_zone'] as $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '" . (int)$value['country_id'] . "', zone_id = '" . (int)$value['zone_id'] . "', geo_zone_id = '" . (int)$geo_zone_id . "', date_added = NOW()");
            }
        }

        $this->cache->delete('geo_zone');
    }

    public function deleteGeoZone($geo_zone_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

        $this->cache->delete('geo_zone');
    }

    public function getGeoZone($geo_zone_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

        return $query->row;
    }

    public function getGeoZones($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "geo_zone";
            
            $isWhere = 0;
            $_sql = array();    
            
            if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
                $isWhere = 1;
                
                $_sql[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
            }
            
            if (isset($data['filter_description']) && !is_null($data['filter_description'])) {
                $isWhere = 1;
                
                $_sql[] = "description LIKE '" . $this->db->escape($data['filter_description']) . "%'";
            }

            if($isWhere) {
                $sql .= " WHERE " . implode(" AND ", $_sql);
            }

            $sort_data = array(
                'name',
                'description'
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
            $geo_zone_data = $this->cache->get('geo_zone');

            if (!$geo_zone_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name ASC");

                $geo_zone_data = $query->rows;

                $this->cache->set('geo_zone', $geo_zone_data);
            }

            return $geo_zone_data;
        }
    }

    public function getTotalGeoZones() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "geo_zone");

        return $query->row['total'];
    }

    public function getTotalGeoZonesFilter($data) {
        $sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "geo_zone");
        
        $isWhere = 0;
        $_sql = array();    
        
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            
            $_sql[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
        
        if (isset($data['filter_description']) && !is_null($data['filter_description'])) {
            $isWhere = 1;
            
            $_sql[] = "description LIKE '" . $this->db->escape($data['filter_description']) . "%'";
        }

        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }

        $query = $this->db->query($sql);    
        
        return $query->row['total'];
    }

    public function getZoneToGeoZones($geo_zone_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

        return $query->rows;
    }

    public function getTotalZoneToGeoZoneByGeoZoneId($geo_zone_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

        return $query->row['total'];
    }

    public function getTotalZoneToGeoZoneByCountryId($country_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$country_id . "'");

        return $query->row['total'];
    }

    public function getTotalZoneToGeoZoneByZoneId($zone_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE zone_id = '" . (int)$zone_id . "'");

        return $query->row['total'];
    }
}
