<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventAppTheme extends Event
{

    public function preLoadView($template, &$data)
    {
        $theme = $this->config->get('config_template');

        $data['theme_' . $theme] = json_decode($this->config->get('theme_' . $theme), true);
    }
}
