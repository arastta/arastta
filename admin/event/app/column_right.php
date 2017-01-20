<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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
