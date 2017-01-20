<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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

        $this->document->addScriptDeclaration($analytics, 'text/javascript', false);

        $added = true;
    }
}
