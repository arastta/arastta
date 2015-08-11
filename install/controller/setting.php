<?php
/**
 * @package     Arastta eCommerce
 * @copyright   Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license     GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSetting extends Controller {

	public function index() {
		$data = $this->language->all();

        $data['action'] = $this->url->link('setting', 'lang='.$this->request->get['lang']);

		if (isset($this->session->data['store_name'])) {
			$data['store_name'] = $this->session->data['store_name'];
		} else {
			$data['store_name'] = '';
		}

		if (isset($this->session->data['store_email'])) {
			$data['store_email'] = $this->session->data['store_email'];
		} else {
			$data['store_email'] = '';
		}

		if (isset($this->session->data['admin_username'])) {
			$data['admin_username'] = $this->session->data['admin_username'];
		} else {
			$data['admin_username'] = '';
		}

		if (isset($this->session->data['admin_email'])) {
			$data['admin_email'] = $this->session->data['admin_email'];
		} else {
			$data['admin_email'] = '';
		}

		if (isset($this->session->data['admin_password'])) {
			$data['admin_password'] = $this->session->data['admin_password'];
		} else {
			$data['admin_password'] = '';
		}

		if (isset($this->session->data['admin_first_name'])) {
			$data['admin_first_name'] = $this->session->data['admin_first_name'];
		} else {
			$data['admin_first_name'] = '';
		}

		if (isset($this->session->data['admin_last_name'])) {
			$data['admin_last_name'] = $this->session->data['admin_last_name'];
		} else {
			$data['admin_last_name'] = '';
		}

		if (isset($this->session->data['install_demo_data']) && !$this->session->data['install_demo_data']) {
			$data['install_demo_data'] = 0;
		} else {
			$data['install_demo_data'] = 1;
		}

		$json['output'] = $this->load->view('settings.tpl', $data);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save() {
		$json = $this->validate();

		if (empty($json)) {
			$this->load->model('setting');

			set_time_limit(300); // 5 minutes

			$this->model_setting->createDatabaseTables($this->request->post);

			if (!isset($this->request->post['install_demo_data'])) {
				try {
					$this->filesystem->remove(DIR_ROOT . 'image/catalog/demo');
				} catch (Exception $e) {
					// Discard exception
				}
			}

            $this->load->controller('finish');
		} else {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	protected function validate() {
		$json = array();

		if (empty($this->request->post['store_name'])) {
			$json['error']['store-name'] = $this->language->get('error_store_name');
		}

		if (empty($this->request->post['store_email'])) {
			$json['error']['store-email'] = $this->language->get('error_store_email');
		}

		if ((utf8_strlen(trim($this->request->post['admin_username'])) < 3) || (utf8_strlen(trim($this->request->post['admin_username'])) > 32)) {
			$json['error']['admin-username'] = $this->language->get('error_admin_username');
		}

		if (empty($this->request->post['admin_email'])) {
			$json['error']['admin-email'] = $this->language->get('error_admin_email');
		}

		if (empty($this->request->post['admin_password'])) {
			$json['error']['admin-password'] = $this->language->get('error_admin_password');
		}

		return $json;
	}
}
