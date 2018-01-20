<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerCommonColumnLeft extends Controller {
    public function index() {
        if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $data['menu'] = $this->load->controller('common/menu');
            $data['class'] = '';

            if (empty($this->session->data['show_menu_position'])) {
                $data['class'] = 'active';
            }

            if (!empty($this->session->data['show_menu'])) {
                $data['class'] = ' right';
            }

            return $this->load->view('common/column_left.tpl', $data);
        }
    }
}
