<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventAppMultiselect extends Event
{

    public function preLoadHeader(&$data)
    {
        $this->document->addStyle('view/javascript/jquery/cs-selectable/jquery.cs-selectable.css');
        $this->document->addScript('view/javascript/jquery/cs-selectable/jquery.cs-selectable.js');
    }
}
