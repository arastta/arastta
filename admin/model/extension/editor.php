<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelExtensionEditor extends Model
{
    public function getEditor($editor)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `code` = '" . $this->db->escape($editor) . "'");

        return $result->rows;
    }

    public function getEditorByUser($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = $this->user->getId();
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE `user_id` = '" . (int)$user_id . "'");

        $params = json_decode($query->row['params'], true);

        $editor_code = 'tinymce';

        if (!empty($params['editor'])) {
            $editor_code = $params['editor'];
        }

        $this->language->load('editor/' . $editor_code);

        $editor_data = array(
            'value' => $editor_code,
            'text'  => $this->language->get('heading_title')
        );

        return $editor_data;
    }

    public function getEditors()
    {
        $editor_data = $this->cache->get('editor');

        if (!$editor_data) {
            $editor_data = array();

            $editors = glob(DIR_ADMIN . 'controller/editor/*');

            foreach ($editors as $editor) {
                if (basename($editor) == 'index.html') {
                    continue;
                }

                $editor_code = basename($editor, '.php');

                $rows = $this->getEditor($editor_code);

                foreach ($rows as $row) {
                    if ($row['key'] != $editor_code . '_status' || ($row['key'] == $editor_code . '_status' && empty($row['value']))) {
                        continue;
                    }

                    $this->language->load('editor/' . $editor_code);

                    $editor_data[] = array(
                        'value' => $editor_code,
                        'text'  => $this->language->get('heading_title')
                    );
                }
            }

            $this->cache->set('editor', $editor_data);
        }

        return $editor_data;
    }

    public function check($editor_code, $data)
    {
        if ($data[$editor_code . '_status']) {
            return true;
        }

        $result = true;

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `code` = 'config' AND `key` = 'config_text_editor'");

        if ($query->row['value'] == $editor_code) {
            $result = false;
            $this->language->load('extension/extension');

            $this->session->data['warning'] = $this->language->get('error_editor_setting');
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE `status` = '1'");

        $user_total = 0;

        foreach ($query->rows as $user) {
            $params = json_decode($user['params'], true);

            if ($editor_code == $params['editor']) {
                $user_total++;
                $result = false;
            }
        }

        if ($user_total) {
            $this->language->load('extension/extension');

            $this->session->data['warning'] = sprintf($this->language->get('error_editor_user'), $user_total);
        }

        $this->cache->delete('editor');

        return $result;
    }
}
