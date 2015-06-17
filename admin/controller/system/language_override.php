<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSystemLanguageoverride extends Controller {
	private $error = array();

	public function index() {
        $this->load->model('system/language_override');
		$this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->saveList();
        }

        $this->getList();
	}

	public function saveList() {
        $this->load->model('system/language_override');

		if (isset($this->request->post['lstrings']) && $this->validateForm()) {
			$msg = $this->model_system_language_override->saveStrings($this->request->post['lstrings']);
			$this->session->data['success'] = sprintf( $this->language->get('text_success_saved'), $msg );
			$this->response->redirect( $this->url->link( 'system/language_override', $this->getUrl(), 'SSL' ) );
		}

		$this->getList();
	}

	protected function getList() {
        $data = $this->language->all();
        $vars = array(
			'filter_text'		=> null,
			'filter_client'		=> 'catalog',
			'filter_language'	=> null,
			'filter_folder'		=> null,
			'filter_path'		=> null,
			'filter_limit'		=> $this->config->get('config_limit_admin'),
			'page'				=> 1
		);

		foreach( $vars as $k => $v ) {
			if (isset($this->request->get[$k])) {
	            $$k = $this->request->get[$k];
	        } else {
	            $$k = $v;
	        }

	        $data[$k] = $$k;
		}

		$data['breadcrumbs'] = array(
			array(
				'text' => $data['text_home'],
				'href' => $this->url->link( 'common/dashboard', 'token=' . $this->session->data['token'], 'SSL' )
			),
			array(
				'text' => $data['heading_title'],
				'href' => $this->url->link( 'system/language_override', $this->getUrl(), 'SSL' )
			)
		);

        $data['action'] = $this->url->link( 'system/language_override/savelist', $this->getUrl(), 'SSL' );

        $filter_data = array(
            'start'				=> ($page - 1) * $filter_limit,
            'filter_text'		=> $filter_text,
            'filter_client'		=> $filter_client,
            'filter_language'	=> $filter_language,
            'filter_folder'		=> $filter_folder,
            'filter_path'		=> $filter_path,
            'limit'				=> $filter_limit
        );

        $data['languages'] = $this->model_system_language_override->getLanguages($filter_data);

        list($data['files'], $data['total'], $data['folders'], $data['paths']) = $this->model_system_language_override->getStrings($filter_data);

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

		$pagination = new Pagination();
		$pagination->total	= $data['total'];
		$pagination->page	= $page;
		$pagination->limit	= $filter_limit;
		$pagination->url	= $this->url->link( 'system/language_override', $this->getUrl(false), 'SSL' );

		$data['pagination']	= $pagination->render();
		$data['token']		= $this->session->data['token'];
		$data['results']	= sprintf($this->language->get('text_pagination'), ($data['total']) ? (($page - 1) * $filter_limit) + 1 : 0, ((($page - 1) * $filter_limit) > ($data['total'] - $filter_limit)) ? $data['total'] : ((($page - 1) * $filter_limit) + $filter_limit), $data['total'], ceil($data['total'] / $filter_limit));

		$this->document->addStyle( 'view/stylesheet/language_override.css' );

		$data['header']			= $this->load->controller('common/header');
		$data['column_left']	= $this->load->controller('common/column_left');
		$data['footer']			= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('system/language_override.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'system/language_override')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	/**
	 * build url
	 * @param bool	$page	if true return request, else placeholder for pagination
	 * @return string
	 */
	private function getUrl( $page = true ) {
		$ret = '';

		if (isset($this->request->get['filter_text'])) {
            $ret .= '&filter_text=' . urlencode(html_entity_decode($this->request->get['filter_text'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_client'])) {
            $ret .= '&filter_client=' . $this->request->get['filter_client'];
        }

        if (isset($this->request->get['filter_language'])) {
            $ret .= '&filter_language=' . $this->request->get['filter_language'];
        }

        if (isset($this->request->get['filter_folder'])) {
            $ret .= '&filter_folder=' . urlencode(html_entity_decode($this->request->get['filter_folder'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_path'])) {
            $ret .= '&filter_path=' . urlencode(html_entity_decode($this->request->get['filter_path'], ENT_QUOTES, 'UTF-8'));
        }

        if( $page ) {
	        if (isset($this->request->get['page'])) {
				$ret .= '&page=' . $this->request->get['page'];
			}
		}else{
			$ret .= '&page={page}';
		}

		$ret .= '&token=' . $this->session->data['token'];

        return $ret;
	}
}
