<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelApiManufacturers extends Model
{

    public function getManufacturer($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)";

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getManufacturers($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)";

        $sql .= $this->getExtraConditions($data);

        $sort_data = array(
            'md.name',
            'm.status',
            'm.sort_order',
            'm.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY md.name";
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

    public function getTotals($data = array())
    {
        $sql = "SELECT COUNT(DISTINCT m.manufacturer_id) AS number FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)";

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->row;
    }

    private function getExtraConditions($data)
    {
        $sql = '';

        $implode = array();

        if (!empty($data['id'])) {
            $implode[] = "m.manufacturer_id = '" . (int)$data['id'] . "'";
        }

        if (!empty($data['search'])) {
            $implode[] = "(md.name LIKE '%" . $this->db->escape($data['search']) . "%' OR md.description LIKE '%" . $this->db->escape($data['search']) . "%')";
        }

        if (!empty($data['status'])) {
            $implode[] = "m.status = '" . (int)$data['status'] . "'";
        }

        $implode[] = "md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $implode[] = "m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        return $sql;
    }
}
