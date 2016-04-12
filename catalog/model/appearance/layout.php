<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelAppearanceLayout extends Model
{
    public function getPositions()
    {
        $theme = $this->config->get('config_template');

        $positions = array(
            'header_top'    => array(),
            'top'           => array(),
            'left'          => array('column_left'),
            'main_top'      => array('content_top'),
            'main_bottom'   => array('content_bottom'),
            'right'         => array('column_right'),
            'bottom'        => array(),
            'footer_bottom' => array()
        );

        $setting_path = DIR_CATALOG . 'view/theme/' . $theme . '/setting.json';

        if (file_exists($setting_path)) {
            $json = file_get_contents($setting_path);

            $setting = json_decode($json, true);

            if (isset($setting['positions'])) {
                $this->trigger->fire('pre.admin.layout.position', array(&$setting['positions']));

                return $setting['positions'];
            }
        }

        $this->trigger->fire('pre.admin.layout.position', array(&$positions));

        return $positions;
    }
}
