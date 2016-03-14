<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonFooter extends Controller {
    public function index() {
        $this->load->language('common/footer');

        $data['text_yes']  = $this->language->get('text_yes');
        $data['text_no']  = $this->language->get('text_no');
        $data['text_selected']  = $this->language->get('text_selected');
        $data['text_advanced_message']  = $this->language->get('text_advanced_message');
        $data['text_basic_message']  = $this->language->get('text_basic_message');
        $data['button_cancel']  = $this->language->get('button_cancel');
        $data['button_delete']  = $this->language->get('button_delete');

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $data['text_footer']  = $this->language->get('text_footer');
            $data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
        } else {
            $data['text_footer']  = '';
            $data['text_version'] = '';
        }

        return $this->load->view('common/footer.tpl', $data);
    }
}
