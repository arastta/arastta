<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventAnalyticsGoogle extends Event
{

    public function preLoadHeader(&$data)
    {
        // Hacked by Cüneyt
        // TO DO: call header controller only 1 time
        static $added;
        if (!empty($added)) {
            return;
        }

        if (!$this->config->get('google_analytics_status')) {
            return;
        }

        $analytics = html_entity_decode($this->config->get('google_analytics_code'), ENT_QUOTES, 'UTF-8');

        $this->document->addScriptDeclaration($analytics, 'text/javascript', true);

        $added = true;
    }
}
