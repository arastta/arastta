<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerExtensionMarketplace extends Controller {
	private $error = array();
	public $apiBaseUrl = 'http://arastta.pro/';

	public function index() {
		$this->load->language('extension/marketplace');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/marketplace');

        $this->document->addScript('../catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('../catalog/view/javascript/jquery/magnific/magnific-popup.css');
        $this->document->addScript('../catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('../catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('../catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->document->addScript('view/javascript/marketplace/marketplace.js');
		$this->document->addStyle('view/stylesheet/marketplace.css');

		unset($this->session->data['cookie']);

		if ($this->validate()) {
			// API
			$this->load->model('user/api');

			$post['api_key'] = $this->config->get('api_key');

			if ($post) {
				$curl = curl_init();

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, $this->apiBaseUrl . 'index.php?route=api/key');
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
				curl_setopt($curl, CURLOPT_REFERER, $this->url->getDomain());

				$json = curl_exec($curl);

				if (!$json) {
					$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
				} else {
					$response = json_decode($json, true);
					if (isset($response['error'])) {
						$this->error['warning'] = $response['error'];
						$data['error'] = $response['error'];
					} elseif (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					} else {
						$this->error['warning'] = $this->language->get('error_default');
					}
				}
				curl_close($curl);
			}
		}

		$data['apiBaseUrl'] = $this->apiBaseUrl;
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/marketplace', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['api_key'] = $this->config->get('api_key');
		$data['changeApiKey'] = false;

		$data['extension_installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
		$data['extension_modifications'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');
		$data['button_installer'] = $this->language->get('button_installer');
		$data['button_modifications'] = $this->language->get('button_modifications');

		if ((isset($this->request->get['changeApiKey']) and $this->request->get['changeApiKey']) or (empty($data['api_key']) or isset($data['error']))) {
			$data['entry_api_key'] = $this->language->get('entry_api_key');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['help_api_key'] = $this->language->get('help_api_key');
			$data['changeApiKey'] = true;

			$data['api_key_href'] = 'http://extensions.arastta.pro/index.php?route=account/api';
			$data['action'] = $this->url->link('extension/marketplace/saveApiKey', 'token=' . $this->session->data['token'], 'SSL');
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/marketplace.tpl', $data));
	}

	public function api() {
		$json = array();

		$this->load->language('extension/marketplace');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->validate()) {

			if (isset($this->session->data['cookie']) && isset($this->request->get['api'])) {
				// Include any URL parameters
				$url_data = array();

				foreach ($this->request->get as $key => $value) {
					if ($key != 'route' && $key != 'token' && $key != 'store_id' && $key != 'api') {
						$url_data[$key] = $value;
					}
				}

				if (isset($this->request->get['store']) and !empty($this->request->get['store'])) {
					$this->apiBaseUrl = str_replace(parse_url($this->apiBaseUrl, PHP_URL_HOST), $this->request->get['store'] . '.' . parse_url($this->apiBaseUrl, PHP_URL_HOST), $this->apiBaseUrl);
				}

				$addon_lib = new Addon($this->registry);
				$addons = $addon_lib->getAddons();
				foreach ($addons as $addon) {
					$data[$addon['product_id']] = $addon['product_version'];
				}

				if (isset($data)) {
					$this->request->post['addons'] = $data;
				}

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, $this->apiBaseUrl . 'index.php?route=' . $this->request->get['api'] . ($url_data ? '&' . http_build_query($url_data) : ''));

				if ($this->request->post) {
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));
				}

				curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

				$json = curl_exec($curl);

				curl_close($curl);
			}
		} else {

			$response = array();
			$response['error']['warning'] = $this->error;
			unset($this->error);

			$json = json_encode($response);
		}
		if (!empty($this->request->server['HTTP_X_REQUESTED_WITH'])) {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput($json);
		} else {
			$this->response->addHeader('Content-Type: text/plain');
			$this->response->setOutput($json);
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/marketplace')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->config->get('api_key')) {
			$this->error['warning'] = $this->language->get('error_api_key');
		}

		if (!extension_loaded('zip')) {
			$this->error['warning'] = $this->language->get('error_zip');
		}

		if (!extension_loaded('xml')) {
			$this->error['warning'] = $this->language->get('error_xml');
		}

		return !$this->error;
	}

	public function saveApiKey() {
		if (!empty($this->request->post['api_key'])) {
			$this->load->model('setting/setting');
			$api['api_key'] = $this->request->post['api_key'];
			$this->model_setting_setting->editSetting('api', $api);
		}

		$this->response->redirect($this->url->link('extension/marketplace', 'token=' . $this->session->data['token'], 'SSL'));
	}
}