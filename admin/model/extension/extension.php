<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelExtensionExtension extends Model {

    public function getInstalled($type) {
        $extension_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

        foreach ($query->rows as $result) {
            $extension_data[] = $result['code'];
        }

        return $extension_data;
    }

    public function install($type, $code) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
    }

    public function uninstall($type, $code) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
    }

    public function addExtension($data) {
        $info = json_encode($data['info']);
        $params = json_encode($data['params']);

        $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($data['type']) . "', `code` = '" . $this->db->escape($data['code']) . "', `info` = '" . $this->db->escape($info) . "', `params` = '" . $this->db->escape($params) . "'");

        $extension_id = $this->db->getLastId();

        return $extension_id;
    }

    public function editExtension($extension_id, $data) {
        $info = json_encode($data['info']);
        $params = json_encode($data['params']);

        $this->db->query("UPDATE " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($data['type']) . "', `code` = '" . $this->db->escape($data['code']) . "', `info` = '" . $this->db->escape($info) . "', `params` = '" . $this->db->escape($params) . "' WHERE `extension_id` = '" . (int)$extension_id . "'");
    }

    public function getExtension($extension_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "extension WHERE `extension_id` = '" . $this->db->escape($extension_id) . "'";

        $extension = $this->db->query($sql)->row;

        return $extension;
    }

    public function getExtensionByCode($type, $code) {
        $sql = "SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'";

        $extension = $this->db->query($sql)->row;

        return $extension;
    }

    public function getExtensions($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "extension WHERE extension_id <> 0";

            if (!empty($data['filter_type'])) {
                $sql .= " AND type = '" . $this->db->escape($data['filter_type']) . "'";
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

            $extension_data = $this->db->query($sql)->rows;

            return $extension_data;
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "extension ORDER BY code";

            $extension_data = $this->db->query($sql)->rows;

            return $extension_data;
        }
    }

    public function getEnabledExtensions($data = array()) {
        $list = array();

        $extensions = $this->getExtensions($data);

        if (empty($extensions)) {
            return $list;
        }

        $this->load->model('setting/setting');

        foreach ($extensions as $extension) {
            $setting = $this->model_setting_setting->getSetting($extension['code']);

            if (!isset($setting[$extension['code'] . '_status']) || !$setting[$extension['code'] . '_status']) {
                continue;
            }

            $list[] = $extension;
        }

        return $list;
    }

    public function getTotalExtensions($data = array()) {
        $extensions = $this->getExtensions($data);

        return count($extensions);
    }

    public function deleteExtension($extension_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `extension_id` = '" . $this->db->escape($extension_id) . "'");
    }

    public function deleteExtensionByCode($type, $code) {
        // Keep B/C
        $this->uninstall($type, $code);
    }

    public function getInstances($type, $code) {
        $sql = "SELECT * FROM " . DB_PREFIX . $type ." WHERE code = '" . $this->db->escape($code) . "'";

        $instances = $this->db->query($sql)->rows;

        return $instances;
    }

    public function getDiscoverExtensions() {
        $data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");

        foreach ($query->rows as $result) {
            $data[$result['type']][$result['code']] = $result;
        }

        return $data;
    }
}
