<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventAppDemo extends Event
{

    public function preLoadView(&$template, &$data)
    {
        if ($template == 'setting/setting.tpl') {
            //$data['heading_title'] = 'My Custom Title';
        }
    }
}
