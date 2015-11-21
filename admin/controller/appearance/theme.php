<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */
use Arastta\Component\Form\Form as AForm;

class ControllerAppearanceTheme extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('appearance/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('appearance/theme');

		$this->getList();
	}

	public function edit() {
		$this->load->language('appearance/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$setting['theme_' . $this->request->get['theme']] = json_encode($this->request->post);

			$this->model_setting_setting->editSetting('theme' , $setting, $this->config->get('config_store_id'));

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('appearance/theme');

		$this->getForm();
	}

	public function activate() {
		$this->load->language('appearance/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (isset($this->request->get['theme']) && $this->validateForm()) {
			$this->model_setting_setting->editSettingValue('config', 'config_template', $this->request->get['theme'], $this->config->get('config_store_id'));

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function delete() {
		$this->load->language('appearance/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['theme']) && $this->validateDelete() && file_exists(DIR_CATALOG . 'view/theme/' . $this->request->get['theme'])) {
			$this->load->model('appearance/theme');

			$this->model_appearance_theme->deleteTheme($this->request->get['theme']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$data = $this->language->all();
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['add'] = $this->url->link('extension/marketplace', 'store=themes&token=' . $this->session->data['token'], 'SSL');
		$data['upload'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete'] = $this->url->link('appearance/theme/delete', 'token=' . $this->session->data['token'], 'SSL');

		$data['themes'] = array();

		$action = array();

		$results = $this->model_appearance_theme->getThemes();

		$this->load->model('tool/image');

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . 'templates/' . $result['code'] . '.png')) {
				$image = $this->model_tool_image->resize('templates/' . $result['code'] . '.png', 880, 660);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 880, 660);
			}

			if ($result['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $result['code'] . '/setting.php')) {
				$action['setting'] =  array(
					'text' => $this->language->get('text_setting'),
					'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $result['code'], 'SSL'),
				);

				$this->trigger->fire('pre.admin.theme.action', array(&$action));
			}

			$data['themes'][] = array(
				'code' 	  	  => $result['code'],
				'name'        => $result['name'],
				'author'      => $result['author'],
				'description' => html_entity_decode($result['description']),
				'thumb'	      => $image,
				'action'	  => $action,
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'        => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'], 'SSL'),
				'activate'    => $this->url->link('appearance/theme/activate', 'token=' . $this->session->data['token'] . '&theme=' . $result['code'], 'SSL'),
				'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $result['code'], 'SSL')
			);
		}

		$data['active_theme'] = $this->config->get('config_template');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$this->document->addStyle('view/stylesheet/theme.css');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('appearance/theme_list.tpl', $data));
	}

	protected function getForm() {
		$this->load->language('theme/' . $this->request->get['theme']);

        $data = $this->language->all();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['theme'])) {
			$data['action'] = $this->url->link('appearance/theme/', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $this->request->get['theme'] , 'SSL');
		}

		$data['cancel'] = $this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['theme']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$data['theme_info'] = $this->model_appearance_theme->getThemeSetting($this->request->get['theme']);
		} else if (isset($this->request->get['theme'])) {
			$data['theme_info'] = $this->model_appearance_theme->getThemeSetting($this->request->get['theme']);
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('appearance/theme_form.tpl', $data));
	}

	public function info() {
		$this->load->language('appearance/theme');
		$this->load->model('appearance/theme');

		$data = $this->language->all();

		if (isset($this->request->post['theme'])) {
			$theme = $this->request->post['theme'];
		} elseif (isset($this->request->get['theme'])) {
			$theme = $this->request->get['theme'];
		} else {
			$theme = 'default';
		}

		$theme_info = $this->model_appearance_theme->getTheme($theme);

		$data['theme'] = array();

		$action = array();

		if ($theme_info) {
			$this->load->model('tool/image');

			if (is_file(DIR_IMAGE . 'templates/' . $theme_info['code'] . '.png')) {
				$image = $this->model_tool_image->resize('templates/' . $theme_info['code'] . '.png', 880, 660);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 880, 660);
			}

			$data['active_theme'] = $this->config->get('config_template');

			if ($theme_info['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $theme_info['code'] . '/setting.php')) {
				$action['setting'] = array(
					'text' => $this->language->get('text_setting'),
					'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
				);

				$this->trigger->fire('pre.admin.theme.action', array(&$action));
			}

			$data['theme'] = array(
				'code' 	  	  => $theme_info['code'],
				'name'        => $theme_info['name'],
				'author'      => $theme_info['author'],
				'description' => html_entity_decode($theme_info['description']),
				'thumb'	      => $image,
				'version'     => $theme_info['version'],
				'action'      => $action,
				'delete'      => $this->url->link('appearance/theme/delete', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
				'activate'    => $this->url->link('appearance/theme/activate', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
				'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL')
			);
		}

		$this->response->setOutput($this->load->view('appearance/theme_info.tpl', $data));
	}

	public function next() {
		$this->load->language('appearance/theme');
		$this->load->model('appearance/theme');

		$data = $this->language->all();

		if (isset($this->request->post['theme'])) {
			$theme = $this->request->post['theme'];
		} elseif (isset($this->request->get['theme'])) {
			$theme = $this->request->get['theme'];
		} else {
			$theme = 'default';
		}

		$theme_info = $this->model_appearance_theme->getTheme($theme);

		$data['theme'] = array();

		$action = array();

		if ($theme_info) {
			$this->load->model('tool/image');

			if (is_file(DIR_IMAGE . 'templates/' . $theme_info['theme'] . '.png')) {
				$image = $this->model_tool_image->resize('templates/' . $theme_info['theme'] . '.png', 880, 660);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 880, 660);
			}

			$data['active_theme'] = $this->config->get('config_template');

			if ($theme_info['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $theme_info['code'] . '/setting.php')) {
				$action['setting'] = array(
					'text' => $this->language->get('text_setting'),
					'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
				);

				$this->trigger->fire('pre.admin.theme.action', array(&$action));
			}

			$data['theme'] = array(
				'theme' 	  => $theme_info['theme'],
				'name'        => $theme_info['name'],
				'author'      => $theme_info['author'],
				'description' => html_entity_decode($theme_info['description']),
				'thumb'	      => $image,
				'version'     => $theme_info['version'],
				'edit'        => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'], 'SSL'),
				'action'      => $action,
				'delete'      => $this->url->link('appearance/theme/delete', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
				'activate'    => $this->url->link('appearance/theme/activate', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
				'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL')
			);
		}

		$this->response->setOutput($this->load->view('appearance/theme_info.tpl', $data));
	}

	public function previous() {
		$this->load->language('appearance/theme');
		$this->load->model('appearance/theme');

		$data = $this->language->all();

		if (isset($this->request->post['theme'])) {
			$theme = $this->request->post['theme'];
		} elseif (isset($this->request->get['theme'])) {
			$theme = $this->request->get['theme'];
		} else {
			$theme = 'default';
		}

		$theme_info = $this->model_appearance_theme->getTheme($theme);

		$data['theme'] = array();

		$action = array();

		if ($theme_info) {
			$this->load->model('tool/image');

			if (is_file(DIR_IMAGE . 'templates/' . $theme_info['code'] . '.png')) {
				$image = $this->model_tool_image->resize('templates/' . $theme_info['code'] . '.png', 880, 660);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 880, 660);
			}

			$data['active_theme'] = $this->config->get('config_template');

			if ($theme_info['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $theme_info['code'] . '/setting.php')) {
				$action['setting'] = array(
					'text' => $this->language->get('text_setting'),
					'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
				);

				$this->trigger->fire('pre.admin.theme.action', array(&$action));
			}

			$data['theme'] = array(
				'code' 	  	  => $theme_info['code'],
				'name'        => $theme_info['name'],
				'author'      => $theme_info['author'],
				'description' => html_entity_decode($theme_info['description']),
				'thumb'	      => $image,
				'version'     => $theme_info['version'],
				'edit'        => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'], 'SSL'),
				'action'      => $action,
				'delete'      => $this->url->link('appearance/theme/delete', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
				'activate'    => $this->url->link('appearance/theme/activate', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
				'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL')
			);
		}

		$this->response->setOutput($this->load->view('appearance/theme_info.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'appearance/theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!AForm::isValid('form-' . $this->request->get['theme'] . '-theme-elements')) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'appearance/theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}