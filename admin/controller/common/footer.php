<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

        $data['text_yes']  = $this->language->get('text_yes');
        $data['text_no']  = $this->language->get('text_no');

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