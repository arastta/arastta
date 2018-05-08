<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventAppTheme extends Event
{

    public function preLoadView($template, &$data)
    {
        $theme = $this->config->get('config_template');

        $obj = new BaseObject();
        $obj->setProperties(json_decode($this->config->get('theme_' . $theme), true));

        $data['theme'] = $theme;
        $data['theme_config'] = $obj;

        // BC for Arastta < 1.2.0
        $data['theme_' . $theme] = json_decode($this->config->get('theme_' . $theme), true);
    }
}
