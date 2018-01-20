<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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

        $layout_path = DIR_CATALOG . 'view/theme/' . $theme . '/layout.json';

        if (file_exists($layout_path)) {
            $json = file_get_contents($layout_path);

            $layout = json_decode($json, true);

            if (isset($layout)) {
                $this->trigger->fire('pre.catalog.layout.position', array(&$layout['positions']));

                return $layout['positions'];
            }
        }

        $this->trigger->fire('pre.catalog.layout.position', array(&$positions));

        return $positions;
    }
}
