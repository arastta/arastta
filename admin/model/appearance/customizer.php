<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelAppearanceCustomizer extends Model
{
    public function saveCustomizer($code, $data, $store_id = 0)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code . "_" . $this->config->get('config_template')) . "'");

        $customizerCss = '';

        foreach ($data as $key => $value) {
            if ($key != 'save' && $key != 'custom-css' && $key != 'custom-js' && $key != 'advance-conrol') {
                if (!is_array($value)) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code . "_" . $this->config->get('config_template')) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code . "_" . $this->config->get('config_template')) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
                }
            } elseif ($key == 'custom-css' || $key == 'custom-js') {
                $element = explode("-", $key);

                if ($element[1] == 'css') {
                    $value = htmlspecialchars_decode(iconv("CP1257", "UTF-8", $value));

                    $this->filesystem->dumpFile(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css', $value);
                } else {
                    $value = htmlspecialchars_decode(iconv("CP1257", "UTF-8", $value));

                    $this->filesystem->dumpFile(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/javascript/custom.js', $value);
                }
            }

            if ($key != 'save' && $key != 'sitename' && $key != 'font' && $key != 'custom-css' && $key != 'custom-js') {
                $item = $this->getCustomizerItem($key);

                if ($key == 'layout_width' || $key == 'container_background-color' || $key == 'container-color_color' || $key == 'container_background-image') {
                    $element = explode("_", $key);

                    if (!empty($item['selector'])) {
                        if (!empty($value)) {
                            if ($key == 'container_background-image') {
                                $customizerCss .= $item['selector'] . " { \r\n \t" .  $element[1] . " : url('../../../../../image/" . $value . "'); \r\n } \n\n ";
                            } else {
                                $customizerCss .= $item['selector'] . " { \r\n \t" . $element[1] . " : " . $value . "; \r\n } \n\n ";
                            }
                        }
                    } else {
                        if (!empty($value)) {
                            if ($key == 'container_background-image') {
                                $customizerCss .= "body { \r\n \t" .  $element[1] . " : url('../../../../../image/" . $value . "'); \r\n } \n\n ";
                            } else {
                                $customizerCss .= "body { \r\n \t" . $element[1] . " : " . $value . "; \r\n } \n\n ";
                            }
                        }
                    }
                } elseif ($key == 'logo') {
                    if (!empty($value)) {
                        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = 'config' AND `key` = 'config_logo'");

                        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = 'config', `key` = 'config_logo', `value` = '" . $this->db->escape($value) . "'");
                    }
                }

                if ($item == false || $key == 'logo') {
                    continue;
                }

                $element = explode("_", $key);

                if ($item['type'] != 'image') {
                    $customizerCss .= $item['selector'] . " { \r\n \t" .  $element[1] . " : " . $value . "; \r\n } \n\n ";
                } else {
                    if (!empty($value)) {
                        $customizerCss .= $item['selector'] . " { \r\n \t" .  $element[1] . " : url('../../../../../image/" . $value . "'); \r\n } \n\n ";
                    }
                }
            } elseif ($key == 'font') {
                $customizerCss .=  " body { \r\n \tfont-family : " . $value . "; \r\n } \n\n ";
            }
        }

        $this->filesystem->dumpFile(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css', $customizerCss);
    }

    public function resetCustomizer($code, $store_id = 0)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code . "_" . $this->config->get('config_template')) . "'");

        $this->filesystem->remove(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');
        $this->filesystem->remove(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css');
        $this->filesystem->remove(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/javascript/custom.js');
    }

    public function getDefaultData($code, $store_id = 0)
    {
        $setting_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code . "_" . $this->config->get('config_template')) . "'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $check_png = strpos($result['value'], '.png');
                $check_jpg = strpos($result['value'], '.jpg');
                $check_jpeg = strpos($result['value'], '.jpeg');
                $check_jpe = strpos($result['value'], '.jpe');
                $check_gif = strpos($result['value'], '.gif');
                $check_bmp = strpos($result['value'], '.bmp');
                $check_ico = strpos($result['value'], '.ico');

                $this->load->model('tool/image');

                if ($check_png !== false || $check_jpg !== false || $check_jpeg !== false || $check_jpe !== false || $check_gif !== false || $check_bmp !== false || $check_ico !== false) {
                    $setting_data[$result['key']. '_raw'] = $result['value'];

                    $result['value'] = $this->model_tool_image->resize($result['value'], 100, 100);
                }

                $setting_data[$result['key']] = $result['value'];
            } else {
                $setting_data[$result['key']] = unserialize($result['value']);
            }
        }

        if (is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css')) {
            $setting_data['custom-css'] = file_get_contents(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css');
        }

        if (is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/javascript/custom.js')) {
            $setting_data['custom-js'] = file_get_contents(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/javascript/custom.js');
        }

        return $setting_data;
    }

    public function getCustomizerItem($key)
    {
        $theme_customizer = DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/customizer.json';
        $default_customizer = DIR_CATALOG . 'view/theme/default/customizer.json';
        
        if (is_file($theme_customizer)) {
            $json = file_get_contents($theme_customizer);
        } elseif (is_file($default_customizer)) {
            $json = file_get_contents($default_customizer);
        } else {
            return false;
        }
    
        $items = json_decode($json, true);

        foreach ($items as $item_name => $item_value) {
            if (empty($item_value['control'][$key])) {
                continue;
            }
            
            return $item_value['control'][$key];
        }

        return false;
    }

    public function changeTheme($template)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($template) . "' WHERE `code` = 'config' AND `key` = 'config_template' AND store_id = '" . $this->config->get('config_store_id') . "'");
    }
}
