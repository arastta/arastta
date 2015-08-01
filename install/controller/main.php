<?php
/**
 * @package     Arastta eCommerce
 * @copyright   Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license     GNU General Public License version 3; see LICENSE.txt
 */

class ControllerMain extends Controller {

	public function index() {
		$this->load->model('main');

		$data = $this->language->all();

		$this->document->setTitle($data['heading_main']);

		$data['requirements'] = $this->model_main->checkRequirements();

        $data['lang'] = $this->request->get['lang'];

		$data['header'] = $this->load->controller('header');
		$data['footer'] = $this->load->controller('footer');

		$this->response->setOutput($this->load->view('main.tpl', $data));
	}
}
