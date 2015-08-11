<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSystemEmailtemplate extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('system/email_template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('system/email_template');

		$this->getList();
	}

	public function edit() {
		$this->load->language('system/email_template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('system/email_template');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	
			$this->model_system_email_template->editEmailTemplate($this->request->get['email_template'], $this->request->post['email_template_description']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                 $this->response->redirect($this->url->link('system/email_template/edit', 'email_template='.$this->request->get['email_template'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

			$this->response->redirect($this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	protected function getList() {
		if (isset($this->request->get['filter_text'])) {
            $filter_text = $this->request->get['filter_text'];
        } else {
            $filter_text = null;
        }	

		if (isset($this->request->get['filter_context'])) {
            $filter_context = $this->request->get['filter_context'];
        } else {
            $filter_context = null;
        }	

		if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }	

		if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = null;
        }	

		if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }	

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
        if (isset($this->request->get['filter_text'])) {
            $url .= '&filter_text=' . $this->request->get['filter_text'];
        }		

        if (isset($this->request->get['filter_context'])) {
            $url .= '&filter_context=' . $this->request->get['filter_context'];
        }

		if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

		if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->request->get['filter_type'];
        }

		if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }		

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['emailTemplates'] = array();

		$filter_data = array(
			'sort'  		 => $sort,
			'order' 		 => $order,
			'start' 		 => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 		 => $this->config->get('config_limit_admin'),
			'filter_text' 	 => $filter_text,
			'filter_context' => $filter_context,
			'filter_name' 	 => $filter_name,
			'filter_type' 	 => $filter_type,
			'filter_status'	 => $filter_status
		);

		$email_template_total = $this->model_system_email_template->getTotalEmailTemplates($filter_data);

		$results = $this->model_system_email_template->getEmailTemplates($filter_data);

		foreach ($results as $result) {
			$data['emailTemplates'][] = array(
				'id'		 => $result['id'],
				'text'		 => $result['text'],
				'type'		 => $result['type'],
				'context'	 => $result['context'],
				'status'     => $result['status'],
				'edit'       => $this->url->link('system/email_template/edit', 'token=' . $this->session->data['token'] . '&email_template=' . $result['type'] . '_' . $result['text_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		#Text
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all']			= $this->language->get('text_all');

		#Column
		$data['column_text'] = $this->language->get('column_text');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_context'] = $this->language->get('column_context');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		#Button
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_show_filter'] = $this->language->get('button_show_filter');
		$data['button_hide_filter'] = $this->language->get('button_hide_filter');	

		#Filter
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_context'] = $this->language->get('entry_context');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_status'] = $this->language->get('entry_status');
		
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_text'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . '&sort=sort_text' . $url, 'SSL');
		$data['sort_context'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . '&sort=sort_context' . $url, 'SSL');
		$data['sort_type'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . '&sort=sort_type' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $email_template_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
			
		$data['filter_text'] 	= $filter_text;
		$data['filter_context'] = $filter_context;
		$data['filter_name'] 	= $filter_name;
		$data['filter_type'] 	= $filter_type;
		$data['filter_status'] 	= $filter_status;

		$data['types'] = $this->_getEmailTypes();
		
		$data['token'] = $this->session->data['token']; 
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($email_template_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($email_template_total - $this->config->get('config_limit_admin'))) ? $email_template_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $email_template_total, ceil($email_template_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('system/email_template_list.tpl', $data));
	}
	
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');

		// shortcodes
		$data['text_shortcodes']	= $this->language->get('text_shortcodes');
		$data['help_shortcodes']	= $this->language->get('help_shortcodes');

		$vars = array(
			// admin
			'admin_forgotten', 'admin_login',
			// affiliate
			'affiliate_affiliate_approve', 'affiliate_order', 'affiliate_add_commission',
			'affiliate_register', 'affiliate_approve', 'affiliate_password_reset',
			// contact
			'contact_confirmation',
			// customer
			'customer_credit', 'customer_voucher', 'customer_approve', 'customer_password_reset',
			'customer_register_approval', 'customer_register',
			// order
			'order_status_voided'
		);

		foreach( $vars as $var ) {
			$data['text_shortcode_' . $var]	= $this->language->get('text_shortcode_' . $var);
		}

		unset( $vars );

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');

		$data['button_save'] = $this->language->get('button_save');
        $data['button_saveclose'] = $this->language->get('button_saveclose');   		
		$data['button_cancel'] = $this->language->get('button_cancel');   

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
				
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['email_template'])) {
			$this->response->redirect($this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		} else {
			$data['action'] = $this->url->link('system/email_template/edit', 'token=' . $this->session->data['token'] . '&email_template=' . $this->request->get['email_template'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['email_template']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$email_template_info = $this->model_system_email_template->getEmailTemplate($this->request->get['email_template']);
		}
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['email_template_description'] = $this->request->post['name'];
		} elseif (!empty($email_template_info['data'])) {
			$data['email_template_description'] = $email_template_info['data'];
		} else {
			$data['email_template_description'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['email_template_description'] = $this->request->post['description'];
		} elseif (!empty($email_template_info['data'])) {
			$data['email_template_description'] = $email_template_info['data'];
		} else {
			$data['email_template_description'] = '';
		}

		$data['context'] = ( !empty( $email_template_info['context'] ) ? str_replace( '.', '_', $email_template_info['context'] ) : '' );

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		// Text Editor
		$data['text_editor'] = $this->config->get('config_text_editor');
		
		if(empty($data['text_editor'])) {
			$data['text_editor'] = 'tinymce';
		}

		// localized language for editors
		$data['lang_editor'] = '';

		if( $data['text_editor'] == 'tinymce' ) {
			// f** langs, they have both: de & de_XX
			$script = 'view/javascript/tinymce/langs/'. str_replace( '-', '_', $this->language->get('code') ) . '.js';

			if( file_exists( DIR_APPLICATION . $script ) ) {
				$data['lang_editor'] = 'language: \'' . str_replace( '-', '_', $this->language->get('code') ) . '\',';
			}else{
				$script = 'view/javascript/tinymce/langs/'. substr( $this->language->get('code'), 0, 2 ) . '.js';

				if( file_exists( DIR_APPLICATION . $script ) ) {
					$data['lang_editor'] = 'language: \'' . substr( $this->language->get('code'), 0, 2 ) . '\',';
				}
			}
		}else{
			$script = 'view/javascript/summernote/lang/summernote-' . $this->language->get( 'code' ) . '.js';
			if( file_exists( DIR_APPLICATION . $script ) ) {
				$this->document->addScript( $script );
				$data['lang_editor'] = 'lang: \'' . $this->language->get( 'code' ) . '\',';
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('system/email_template_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'system/email_template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
	
	protected function _getEmailTypes() {
		$result = array ( 'Order Status', 'Customer', 'Affiliate', 'Contact', 'Reviews', 'Cron', 'Mail', 'Admin' );
		$count = count( $result ) - 1;
	
		for ($i = 0; $i <= $count; ++$i){
			$types[] = array(
				'id'	=> $i+1,
				'value'	=> $result[$i]
			);
		}
		return $types;
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('system/email_template');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_system_email_template->getEmailTemplates($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}