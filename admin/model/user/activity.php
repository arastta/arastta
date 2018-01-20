<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelUserActivity extends Model
{

    public function addActivity($key, $data)
    {
        if (isset($data['user_id'])) {
            $user_id = $data['user_id'];
        } else {
            $user_id = 0;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "user_activity` SET `user_id` = '" . (int)$user_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
    }

    public function getActivities($data = array())
    {
        $sql = "SELECT CONCAT(u.firstname, ' ', u.lastname) AS name, ua.activity_id, ua.user_id, ua.key, ua.data, ua.ip, ua.date_added FROM " . DB_PREFIX . "user_activity ua LEFT JOIN " . DB_PREFIX . "user u ON (ua.user_id = u.user_id)";

        $implode = array();

        if (!empty($data['filter_user'])) {
            $implode[] = "CONCAT(u.firstname, ' ', u.lastname) LIKE '" . $this->db->escape($data['filter_user']) . "'";
        }

        if (!empty($data['filter_user_email'])) {
            $implode[] = "u.email = '" . $this->db->escape($data['filter_user_email']) . "'";
        }

        if (!empty($data['filter_user_id'])) {
            $implode[] = "ua.user_id = '" . $this->db->escape($data['filter_user_id']) . "'";
        }

        if (!empty($data['filter_key'])) {
            $implode[] = "ua.key = '" . $this->db->escape($data['filter_key']) . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ua.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(ua.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(ua.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'ua.activity_id',
            'ua.user_id',
            'ua.key',
            'ua.ip',
            'ua.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY ua.date_added";
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
}
