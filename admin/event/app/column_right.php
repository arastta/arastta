<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventAppColumnRight extends Event
{

    public function postLoadController($route, &$ret = null)
    {
        if ($route != 'common/column_left') {
            return;
        }

        $column_right = $this->load->controller('common/column_right');

        $ret = chr(13) . chr(13) . $column_right . $ret;
    }
}
