<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerExtensionModification extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		$this->getList();
	}

	public function delete() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $modification_id) {

                if(file_exists(DIR_SYSTEM . 'xml/'.$modification_id)) {
                    unlink(DIR_SYSTEM . 'xml/'.$modification_id);
                } else if(file_exists(DIR_VQMOD . 'xml/'.$modification_id)){
                    unlink(DIR_VQMOD . 'xml/'.$modification_id);
                }
			}

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

            if(empty($this->request->get['extensionInstaller'])) {
                $this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            } else {
                $json = array();
                $json['success'] = $this->language->get('text_success');
                $json['files']   = $this->request->post['selected'];
            }
		}

        if(empty($this->request->get['extensionInstaller'])) {
            $this->getList();
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
	}

	public function refresh() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if ($this->validate()) {
            // Clear Log
            $this->clearlog(true);

			// Clear all modification files
			$this->filesystem->remove(DIR_MODIFICATION);

			// Apply vQmods and OCmods
			$this->modification->applyMod();

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
			if(empty($this->request->get['extensionInstaller'])) {
			    $this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			} else {
				$json = array();
                unset($this->session->data['success']);
				$json['notice'] = $this->language->get('text_success');
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
			}
		}

		if(empty($this->request->get['extensionInstaller'])) {
			$this->getList();
		}
	}

	public function clear() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if ($this->validate()) {
			// Clear all modification files
			$this->filesystem->remove(DIR_MODIFICATION);

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

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function enable() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');
		$json = array();

		$json['success'] = "0";
		$json['error']   = $this->language->get('error_enabled');
		$json['status']  = "0";
		$json['link']    = "0";
		$json['enable']  = "0";
		$json['disable'] = "0";
		if (isset($this->request->get['modification_id']) && $this->validate()) {

            if(file_exists(DIR_SYSTEM . 'xml/' . $this->request->get['modification_id'])) {
                rename(DIR_SYSTEM . 'xml/' . $this->request->get['modification_id'], DIR_SYSTEM . 'xml/' . str_replace('.xml_', '.xml', $this->request->get['modification_id']));
			} else if(file_exists(DIR_VQMOD . 'xml/' . $this->request->get['modification_id'])){
                rename(DIR_VQMOD . 'xml/' . $this->request->get['modification_id'], DIR_VQMOD . 'xml/' . str_replace('.xml_', '.xml', $this->request->get['modification_id']));
            }

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

			$json['success'] = $this->language->get('text_success');
			$json['error']   = "0";
			$json['status']  = "1";
			$json['enable']  = "1";
			$json['disable'] = "0";
			$json['link']    =  $this->url->link('extension/modification/disable', 'token=' . $this->session->data['token'] . '&modification_id=' . str_replace('.xml_', '.xml', $this->request->get['modification_id']), 'SSL');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function disable() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');
		$json = array();

		$json['success'] = "0";
		$json['error']   = $this->language->get('error_enabled');
		$json['status']  = "0";
		$json['link']    = "0";
		$json['enable']  = "0";
		$json['disable'] = "0";

		if (isset($this->request->get['modification_id']) && $this->validate()) {

            if(file_exists(DIR_SYSTEM . 'xml/' . $this->request->get['modification_id'])) {
                rename(DIR_SYSTEM . 'xml/' . $this->request->get['modification_id'], DIR_SYSTEM . 'xml/' . str_replace('.xml', '.xml_', $this->request->get['modification_id']));
            } else if(file_exists(DIR_VQMOD . 'xml/' . $this->request->get['modification_id'])) {
                rename(DIR_VQMOD . 'xml/' . $this->request->get['modification_id'], DIR_VQMOD . 'xml/' . str_replace('.xml', '.xml_', $this->request->get['modification_id']));
            }

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

			$json['success'] = $this->language->get('text_success');
			$json['error']   = 0;
			$json['status']  = "1";
			$json['disable'] = "1";
			$json['enable']  = "0";
			$json['link']    =  $this->url->link('extension/modification/enable', 'token=' . $this->session->data['token'] . '&modification_id=' . str_replace('.xml', '.xml_', $this->request->get['modification_id']), 'SSL');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clearlog($clean = false) {
		$this->load->language('extension/modification');

		if ($this->validate()) {
			$handle = fopen(DIR_LOG . 'modification.log', 'w+');

			fclose($handle);

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

            if(!$clean) {
                $this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
		}

		if(!$clean) {
			$this->getList();
		}
	}

	protected function getList() {
		// To catch XML syntax errors
		set_error_handler(array('Modification', 'handleXMLError'));
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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
			'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['refresh'] = $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['clear'] = $this->url->link('extension/modification/clear', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('extension/modification/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['modifications'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


        $files_enable = glob(DIR_SYSTEM . 'xml/*.xml');
        $files_disable = glob(DIR_SYSTEM . 'xml/*.xml_');

        $files_hide = array('core.xml');

        $files = array_merge($files_enable, $files_disable);

        foreach($files as $file) {

            $file_path = explode('/', $file);
            $file_name = end($file_path);

            // Do not show hidden XML files
            if (in_array($file_name, $files_hide)) {
                continue;
            }

            $xml = file_get_contents($file);

            if (!empty($xml)) {
				$dom = new DOMDocument('1.0', 'UTF-8');
				$invalid_xml = '';
				try {
					$dom->loadXml($xml);
				} catch(Exception $exception) {
					$invalid_xml = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}

				$name = $dom->getElementsByTagName('name')->item(0);

				if (!empty($name)) {
					$name = $name->nodeValue;
				}
				else {
					$name = basename($file, '');
				}

				$code = $dom->getElementsByTagName('code')->item(0);

				if (!empty($code)) {
					$code = $code->nodeValue;
				}
				else {
					$code = '';
				}

				$author = $dom->getElementsByTagName('author')->item(0);

				if (!empty($author)) {
					$author = $author->nodeValue;
				}
				else {
					$author = $this->language->get('text_none');
				}

				$version = $dom->getElementsByTagName('version')->item(0);

				if (!empty($version)) {
					$version = $version->nodeValue;
				}
				else {
					$version = $this->language->get('text_none');
				}

				$link = $dom->getElementsByTagName('link')->item(0);

				if (!empty($link)) {
					$link = $link->nodeValue;
				}
				else {
					$link = '';
				}

				$status = (substr($file, -5) != '.xml_') ? 1 : 0;
				$file_suffix = (substr($file, -5) != '.xml_') ? '.xml' : '.xml_';
				$modification_id = $code . $file_suffix;

				$data['modifications'][] = array(
					'modification_id' => $modification_id,
					'name'            => $name,
					'code'            => $code,
					'author'          => $author,
					'version'         => $version,
					'link'            => $link,
					'status'          => ($status) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'enable'          => $this->url->link('extension/modification/enable', 'token=' . $this->session->data['token'] . '&modification_id=' . $modification_id, 'SSL'),
					'disable'         => $this->url->link('extension/modification/disable', 'token=' . $this->session->data['token'] . '&modification_id=' . $modification_id, 'SSL'),
					'enabled'         => $status,
					'invalid_xml'     => $invalid_xml,
				);
            }
        }

        if(file_exists(DIR_VQMOD . 'xml/')){

            $data['vqmods'] = $this->_checkVqmodfile();

			if(!empty($data['vqmods'])) {

				$modification_vqmod = array();


				foreach($data['modifications'] as $modification){
					if( substr($modification['modification_id'], -5) != '.xml_') {
						# enable OCMOD
						$modification_vqmod [] = $modification;
				   } else {
						if(empty($vqmod_add)) {
							$vqmod_add = 1;
							foreach($data['vqmods'] as $vqmod){
								if( substr($vqmod['vqmod_id'], -5) != '.xml_') {
									# enable VQMOD
									$modification_vqmod [] = $vqmod;
								}
							}
						}
						$modification_vqmod [] = $modification;
				   }

                }

                if(empty($vqmod_add)){
                    foreach($data['vqmods'] as $vqmod){
                        if( substr($vqmod['vqmod_id'], -5) != '.xml_') {
                            # enable VQMOD
                            $modification_vqmod [] = $vqmod;
                        }
                    }
                }

				foreach($data['vqmods'] as $vqmod){
					if( substr($vqmod['vqmod_id'], -5) == '.xml_') {
						# disable VQMOD
						$modification_vqmod [] = $vqmod;
					}
				}

				$data['modifications'] = $modification_vqmod;

			}
        }

		restore_error_handler();

        $modification_total = count($data['modifications']);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_refresh'] = $this->language->get('text_refresh');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_version'] = $this->language->get('column_version');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_link'] = $this->language->get('button_link');
		$data['button_enable'] = $this->language->get('button_enable');
		$data['button_disable'] = $this->language->get('button_disable');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_log'] = $this->language->get('tab_log');

		$data['token'] = $this->session->data['token'];

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

		$data['sort_name'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_author'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
		$data['sort_version'] = $this->url->link('extension/version', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $modification_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($modification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($modification_total - $this->config->get('config_limit_admin'))) ? $modification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $modification_total, ceil($modification_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		// Log
		$file = DIR_LOG . 'modification.log';

		if (file_exists($file)) {
			$data['log'] = htmlentities(file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
		} else {
			$data['log'] = '';
		}

		$data['clear_log'] = $this->url->link('extension/modification/clearlog', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/modification.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function _checkVqmodfile(){
        $files_enable  = glob(DIR_VQMOD . 'xml/*.xml');
        $files_disable = glob(DIR_VQMOD . 'xml/*.xml_');

        $files = array_merge($files_enable, $files_disable);

		if(!empty($files)) {

			foreach($files as $file) {

				$file_path = explode('/', $file);
				$file_name = end($file_path);

				$xml = file_get_contents($file);

				if (!empty($xml)) {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$invalid_xml = '';
					try {
						$dom->loadXml($xml);
					} catch(Exception $exception) {
						$invalid_xml = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
					}

					$name = $dom->getElementsByTagName('id')->item(0);

					if (!empty($name)) {
						$name = $name->nodeValue;
					}
					else {
						$name = basename($file, '');
					}
					$_code = explode('.', $file_name);
					$code  = $_code[0];

					$author = $dom->getElementsByTagName('author')->item(0);

					if (!empty($author)) {
						$author = $author->nodeValue;
					}
					else {
						$author = $this->language->get('text_none');
					}

					$version = $dom->getElementsByTagName('version')->item(0);

					if (!empty($version)) {
						$version = $version->nodeValue;
					}
					else {
						$version = $this->language->get('text_none');
					}

					$link = '';

					$status = (substr($file, -5) != '.xml_') ? 1 : 0;
					$file_suffix = (substr($file, -5) != '.xml_') ? '.xml' : '.xml_';
					$vqmod_id = $code . $file_suffix;

					$data[] = array(
						'vqmod_id'        => $vqmod_id,
						'name'            => $name,
						'code'            => $code,
						'author'          => $author,
						'version'         => $version,
						'link'            => $link,
						'status'          => ($status) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
						'enable'          => $this->url->link('extension/modification/enable', 'token=' . $this->session->data['token'] . '&modification_id=' . $vqmod_id, 'SSL'),
						'disable'         => $this->url->link('extension/modification/disable', 'token=' . $this->session->data['token'] . '&modification_id=' . $vqmod_id, 'SSL'),
						'enabled'         => $status,
						'invalid_xml' 	  => $invalid_xml
					);
				}
			}
		}

		if(empty($data)) {
			$data = NULL;
		}

        return $data;
    }
}