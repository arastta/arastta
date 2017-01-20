<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventAppPosition extends Event
{
    public function preLoadView($template, &$data)
    {
        if (!isset($data['column_left'])) {
            return;
        }

        $this->load->model('appearance/layout');
        
        $positions = $this->model_appearance_layout->getPositions();

        if (empty($positions)) {
            return;
        }

        $exclude = array(
          'column_left',
          'column_right',
          'content_top',
          'content_bottom'
        );

        foreach ($positions as $position) {
            if (!is_array($position)) {
                continue;
            }

            foreach ($position as $pos) {
                if (in_array($pos, $exclude)) {
                    continue;
                }

                $data[$pos] = $this->load->controller('common/position', $pos);
            }
        }

        $data['header'] = $this->load->controller('common/header');
    }
}
