<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelAppearanceTheme extends Model
{

    public function addTheme($data)
    {
        $this->trigger->fire('pre.admin.theme.edit', array(&$data));

        $info = json_encode($data['info']);
        $params = json_encode($data['params']);

        $this->db->query("INSERT INTO " . DB_PREFIX . "theme SET code = '" . $this->db->escape($data['code']) . "', info = '" . $this->db->escape($info) . "', params = '" . $this->db->escape($params) . "'");

        $theme_id = $this->db->getLastId();

        $this->trigger->fire('pre.admin.theme.edit', array(&$data));

        return $theme_id;
    }

    public function editTheme($theme_id, $data)
    {
        $this->trigger->fire('pre.admin.theme.edit', array(&$data));

        $info = json_encode($data['info']);
        $params = json_encode($data['params']);

        $this->db->query("UPDATE " . DB_PREFIX . "theme SET code = '" . $this->db->escape($data['code']) . "', info = '" . $this->db->escape($info) . "', params = '" . $this->db->escape($params) . "' WHERE theme_id = '" . (int)$theme_id . "'");

        $this->trigger->fire('pre.admin.theme.edit', array(&$theme_id));

        return $theme_id;
    }

    public function deleteTheme($theme_id)
    {
        $this->trigger->fire('pre.admin.theme.delete', array(&$theme_id));

        $this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE `theme_id` = '" . $theme_id . "'");

        $this->cache->delete('theme');

        $this->trigger->fire('post.admin.theme.delete', array(&$theme_id));
    }

    public function getTheme($theme_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "theme WHERE theme_id = '" . $theme_id . "'");

        $result = $query->row;
        $info = json_decode($result['info'], true);

        return $theme = array(
            'theme_id'    => $result['theme_id'],
            'code'        => $result['code'],
            'author'      => $info['author']['name'],
            'name'        => $info['theme']['name'],
            'description' => $info['theme']['description'],
            'version'     => $info['theme']['version'],
            'product_id'  => $info['theme']['product_id']
        );
    }

    public function getThemeByCode($code)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "theme WHERE code = '" . $code . "'");

        $result = $query->row;
        $info = json_decode($result['info'], true);

        return $theme = array(
            'theme_id'    => $result['theme_id'],
            'code'        => $result['code'],
            'author'      => $info['author']['name'],
            'name'        => $info['theme']['name'],
            'description' => $info['theme']['description'],
            'version'     => $info['theme']['version'],
            'product_id'  => $info['theme']['product_id']
        );
    }

    public function getThemes()
    {
        $themes = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "theme");

        $sort = 1;

        foreach ($query->rows as $theme) {
            $key = 0;

            if ($this->config->get('config_template') != $theme['code']) {
                $key = $sort;
                $sort++;
            }

            $info = json_decode($theme['info'], true);

            $themes[$key] = array(
                'code'        => $theme['code'],
                'author'      => $info['author']['name'],
                'name'        => $info['theme']['name'],
                'description' => $info['theme']['description'],
                'version'     => $info['theme']['version'],
                'product_id'  => $info['theme']['product_id'],
                'status'      => 1
            );
        }

        ksort($themes);

        return $themes;
    }

    public function getThemeSetting($code, $action)
    {
        $form = new Arastta\Component\Form\Form('form-' . $code . '-theme-elements', str_replace('&amp;', '&', $action));

        if (file_exists(DIR_CATALOG . 'view/theme/' . $code . '/setting_.php')) {
            include(DIR_CATALOG . 'view/theme/' . $code . '/setting.php');
        } elseif (file_exists(DIR_CATALOG . 'view/theme/' . $code . '/setting.json')) {
            $this->jsonParser(DIR_CATALOG . 'view/theme/' . $code . '/setting.json', $form);
        }

        return $form->render(true);
    }

    public function getTotalThemes()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "theme");

        return $query->row['total'];
    }

    public function jsonParser($path, $form)
    {
        $json = file_get_contents($path);

        $items = json_decode($json, true);

        $theme = $this->config->get('config_template');
        $config = json_decode($this->config->get('theme_' . $theme), true);

        foreach ($items as $item) {
            if (empty($item['type'])) {
                $form->addElement(new Arastta\Component\Form\Element\HTML($this->language->get($item['title'])));
            } elseif (empty($tab)) {
                $tab = true;

                $form->addElement(new Arastta\Component\Form\Element\HTML('<ul class="nav nav-tabs">'));

                foreach ($items as $_item) {
                    if ($item['type'] == 'tab') {
                        $form->addElement(new Arastta\Component\Form\Element\HTML('<li><a href="#tab-' . $this->language->get($_item['title']) . '" data-toggle="tab">' . $this->language->get($_item['title']) . '</a></li>'));
                    }
                }

                $form->addElement(new Arastta\Component\Form\Element\HTML('</ul>'));
                $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-content">'));

            }

            if (!empty($tab)) {
                $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-pane" id="tab-' . $this->language->get($item['title']) . '">'));
            }

            foreach ($item['element'] as $element) {
                $attribute = $option = null;

                $class_name = 'Arastta\Component\Form\Element\\' . ucfirst($element['type']);

                $label = $this->language->get($element['label']);
                $name  = $element['name'];

                if (isset($element['option'])) {
                    $option = $element['option'];
                }

                if (isset($element['attribute'])) {
                    $attribute = $element['attribute'];
                }

                //Set Config value
                if (isset($config[$name])) {
                    $attribute['value'] = $config[$name];
                }

                if ($element['type'] == 'yesno') {
                    $option['value'] = $config[$name];
                    $attribute['yes'] = $this->language->get('text_enabled');
                    $attribute['no'] = $this->language->get('text_disabled');
                }
                if (empty($option) && empty($attribute)) {
                    $form->addElement(new $class_name($label, $name));
                } elseif (!empty($option) && empty($attribute)) {
                    $form->addElement(new $class_name($label, $name, $option));
                } elseif (empty($option) && !empty($attribute)) {
                    $form->addElement(new $class_name($label, $name, $attribute));
                } else {
                    $form->addElement(new $class_name($label, $name, $option, $attribute));
                }
            }

            if (!empty($tab)) {
                $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));
            }
        }

        if (!empty($tab)) {
            $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));
        }
    }

    public function getDiscoverThemes()
    {
        $themes = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "theme");

        foreach ($query->rows as $theme) {
            $themes[$theme['code']] = $theme;
        }

        return $themes;
    }
}
