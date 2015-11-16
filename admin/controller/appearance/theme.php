<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

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
			$this->model_setting_setting->editSettingValue('config', 'config_template', $this->request->get['config_template'], $this->config->get('config_store_id'));

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

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

			$this->filesystem->remove(DIR_CATALOG . 'view/theme/' . $this->request->get['theme']);

			if (is_file(DIR_IMAGE . 'templates/' . $this->request->get['theme'] . '.png')) {
				$this->filesystem->remove(DIR_IMAGE . 'templates/' . $this->request->get['theme'] . '.png');
			}

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

		$results = $this->model_appearance_theme->getThemes();

		$this->load->model('tool/image');

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . 'templates/' . $result['code'] . '.png')) {
				$image = $this->model_tool_image->resize('templates/' . $result['code'] . '.png', 880, 660);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 880, 660);
			}

			$data['themes'][] = array(
				'code' 	  	  => $result['code'],
				'name'        => $result['name'],
				'author'      => $result['author'],
				'description' => html_entity_decode($result['description']),
				'thumb'	      => $image,
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

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('appearance/theme', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['category_id'])) {
			$data['action'] = $this->url->link('appearance/theme/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('appearance/theme', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->model_appearance_theme->getCategory($this->request->get['category_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description'])) {
			$data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_description'] = $this->model_appearance_theme->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$data['category_description'] = array();
		}

		if (isset($this->request->post['path'])) {
			$data['path'] = $this->request->post['path'];
		} elseif (!empty($category_info)) {
			$data['path'] = $category_info['path'];
		} else {
			$data['path'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$data['parent_id'] = $category_info['parent_id'];
		} else {
			$data['parent_id'] = 0;
		}

		$this->load->model('catalog/filter');

		if (isset($this->request->post['category_filter'])) {
			$filters = $this->request->post['category_filter'];
		} elseif (isset($this->request->get['category_id'])) {
			$filters = $this->model_appearance_theme->getCategoryFilters($this->request->get['category_id']);
		} else {
			$filters = array();
		}

		$data['category_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$data['category_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_store'] = $this->model_appearance_theme->getCategoryStores($this->request->get['category_id']);
		} else {
			$data['category_store'] = array(0);
		}

		if (isset($this->request->post['seo_url'])) {
			$data['seo_url'] = $this->request->post['seo_url'];
		} elseif (!empty($category_info)) {
			$data['seo_url'] = $category_info['seo_url'];
		} else {
			$data['seo_url'] = array();
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($category_info)) {
			$data['image'] = $category_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['top'])) {
			$data['top'] = $this->request->post['top'];
		} elseif (!empty($category_info)) {
			$data['top'] = $category_info['top'];
		} else {
			$data['top'] = 0;
		}

		if (isset($this->request->post['column'])) {
			$data['column'] = $this->request->post['column'];
		} elseif (!empty($category_info)) {
			$data['column'] = $category_info['column'];
		} else {
			$data['column'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$data['sort_order'] = $category_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$data['status'] = $category_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['category_layout'])) {
			$data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_layout'] = $this->model_appearance_theme->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$data['category_layout'] = array();
		}

		if (isset($this->request->get['category_id'])) {
			$data['menu_name_override'] = '1';
		}

		$this->load->model('appearance/layout');

		$data['layouts'] = $this->model_appearance_layout->getLayouts();

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

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'appearance/theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}