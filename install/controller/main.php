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

		$data['header'] = $this->load->controller('header');
		$data['footer'] = $this->load->controller('footer');

		$this->response->setOutput($this->load->view('main.tpl', $data));
	}

	public function displayDatabase() {
		$data = $this->language->all();

		$data['action'] = $this->url->link('main');

		if (isset($this->session->data['db_hostname'])) {
			$data['db_hostname'] = $this->session->data['db_hostname'];
		} else {
			$data['db_hostname'] = 'localhost';
		}

		if (isset($this->session->data['db_username'])) {
			$data['db_username'] = $this->session->data['db_username'];
		} else {
			$data['db_username'] = '';
		}

		if (isset($this->session->data['db_password'])) {
			$data['db_password'] = $this->session->data['db_password'];
		} else {
			$data['db_password'] = '';
		}

		if (isset($this->session->data['db_database'])) {
			$data['db_database'] = $this->session->data['db_database'];
		} else {
			$data['db_database'] = '';
		}

		$data['db_prefix'] = $this->generatePrefix();

		$json['output'] = $this->load->view('database.tpl', $data);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function saveDatabase() {
		$this->load->model('main');

		$json = $this->validateDatabase();

		if (empty($json)) {
			$this->model_main->saveConfig($this->request->post);

			$this->displaySettings();
		} else {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function displaySettings() {
		$data = $this->language->all();

		$data['action'] = $this->url->link('main');

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

		$json['output'] = $this->load->view('settings.tpl', $data);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function saveSettings() {
		$json = $this->validateSettings();

		if (empty($json)) {
			$this->load->model('main');

			set_time_limit(300); // 5 minutes

			$this->model_main->createDatabaseTables($this->request->post);

			if (!isset($this->request->post['install_demo_data'])) {
				try {
					$this->filesystem->remove(DIR_ROOT . 'image/catalog/demo');
				} catch (Exception $e) {
					// Discard exception
				}
			}

			$this->displayFinish();
		} else {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function displayFinish() {
		$data = $this->language->all();

		$data['store'] = HTTP_CATALOG;
		$data['admin'] = HTTP_CATALOG.'admin';

		$data['text_finish_header'] = $this->language->get('text_finish_header');

		$json['output'] = $this->load->view('finish.tpl', $data);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeInstall() {
		$this->load->model('main');

		$json['success'] = $this->model_main->removeInstall();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateDatabase() {
		$json = array();

		if (empty($this->request->post['db_hostname'])) {
			$json['error']['hostname'] = $this->language->get('error_db_hostname');
		}

		if (empty($this->request->post['db_username'])) {
			$json['error']['username'] = $this->language->get('error_db_username');
		}

		if (empty($this->request->post['db_database'])) {
			$json['error']['database'] = $this->language->get('error_db_database');
		}

		if (!empty($this->request->post['db_prefix']) && !preg_match("/^[a-z0-9_]+$/", $this->request->post['db_prefix'])) {
			$json['error']['prefix'] = $this->language->get('error_db_prefix');
		}

		if (!$this->model_main->validateDatabaseConnection($this->request->post)) {
			$json['error']['prefix'] = $this->language->get('error_db_connect');
		}

		return $json;
	}

	protected function validateSettings() {
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

	protected function generatePrefix() {
		// Create the random prefix.
		$prefix = '';
		$chars = range('a', 'z');
		$numbers = range(0, 9);

		// We want the fist character to be a random letter.
		shuffle($chars);
		$prefix .= $chars[0];

		// Next we combine the numbers and characters to get the other characters.
		$symbols = array_merge($numbers, $chars);
		shuffle($symbols);

		for ($i = 0, $j = 3 - 1; $i < $j; ++$i)
		{
			$prefix .= $symbols[$i];
		}

		// Append the underscore.
		$prefix .= '_';

		return $prefix;
	}
}
