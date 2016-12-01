<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventAppMultiselect extends Event
{

    public function preLoadHeader(&$data)
    {
        $this->document->addStyle('view/javascript/jquery/cs-selectable/jquery.cs-selectable.css');
        $this->document->addScript('view/javascript/jquery/cs-selectable/jquery.cs-selectable.js');
    }
}
