<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventEditorReadmore extends Event
{
    private $readmore = '&lt;p&gt;&lt;!--readmore--&gt;&lt;/p&gt;';

    public function preProductDisplay(&$result, $page)
    {
        //$readmore = preg_match_all('~(&lt;img.*view/image/read-more.png[\s\w\d;&=]*&gt;)~', $result['description'], $matches);
        switch ($page) {
            case 'product':
                $result['description'] = str_replace("\r\n<p><!--readmore--></p>\r\n", "", $result['description']);
                $result['description'] = str_replace('<p><!--readmore--></p>', '', $result['description']);
                break;
            default:
                $check_readmore = strpos($result['description'], $this->readmore);

                if ($check_readmore !== false) {
                    $description = explode($this->readmore, $result['description']);
                    $result['description'] = strip_tags(html_entity_decode($description[0])) . '..';
                } else {
                    $result['description'] = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..';
                }
        }
    }
}
