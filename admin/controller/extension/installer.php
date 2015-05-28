<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerExtensionInstaller extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('extension/installer');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_upload'] = $this->language->get('entry_upload');
		$data['entry_overwrite'] = $this->language->get('entry_overwrite');
		$data['entry_progress'] = $this->language->get('entry_progress');

		$data['help_upload'] = $this->language->get('help_upload');

		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_continue'] = $this->language->get('button_continue');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['token'] = $this->session->data['token'];

		$directories = glob(DIR_UPLOAD . 'temp-*', GLOB_ONLYDIR);

		if ($directories) {
			$data['error_warning'] = $this->language->get('error_temporary');
		} else {
			$data['error_warning'] = '';
		}

        if (!extension_loaded('xml')) {
            $this->error['error_warning'] = $this->language->get('error_xml');
        }

        if (!extension_loaded('zip')) {
            $this->error['error_warning'] = $this->language->get('error_zip');
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/installer.tpl', $data));
	}

	public function upload() {
		$this->load->language('extension/installer');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name'])) {
				if ((strrchr($this->request->files['file']['name'], '.') != '.zip' && strrchr($this->request->files['file']['name'], '.') != '.xml') && (substr($this->request->files['file']['name'], -10) != '.ocmod.zip' && substr($this->request->files['file']['name'], -10) != '.ocmod.xml')) {
					$json['error'] = $this->language->get('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			// If no temp directory exists create it
			$path = 'temp-' . md5(mt_rand());

			if (!is_dir(DIR_UPLOAD . $path)) {
				$this->filesystem->mkdir(DIR_UPLOAD . $path);
			}

			// Set the steps required for installation
			$json['step'] = array();
			$json['overwrite'] = array();

            if (strrchr($this->request->files['file']['name'], '.') != '.zip') {
                $xml = file_get_contents($this->request->files['file']['tmp_name']);

                if ($xml) {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->loadXml($xml);
                    $code = $dom->getElementsByTagName('code')->item(0);
                }
            } else {
                $code = NULL;
            }

			if (strrchr($this->request->files['file']['name'], '.') == '.xml') {
				$file = DIR_UPLOAD . $path . '/install.xml';

                if(empty($code)) {
                    $this->session->data['vqmod_file_name'] = $this->request->files['file']['name'];
                    $file = DIR_UPLOAD . $path . '/' . $this->request->files['file']['name'];

                    $_file = DIR_VQMOD . 'xml/' . $this->request->files['file']['name'];

                    $vqmodNameLen = strlen($this->request->files['file']['name']);
                    $vqmodNameLen = $vqmodNameLen + 11;

                    if (is_file($_file) && strtolower(substr($_file, -$vqmodNameLen)) == '/vqmod/xml/' . $this->request->files['file']['name']) {
                        $json['overwrite'][] = 'vqmod/xml/' . $this->request->files['file']['name'];
                    }
                }
				
				// If xml file copy it to the temporary directory
				move_uploaded_file($this->request->files['file']['tmp_name'], $file);

				if (file_exists($file)) {
					$json['step'][] = array(
						'text' => $this->language->get('text_xml'),
						'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/xml', 'token=' . $this->session->data['token'], 'SSL')),
						'path' => $path
					);

                    // Refresh Modification
                    $json['step'][] = array(
                        'text' => $this->language->get('text_modification'),
                        'url'  => str_replace('&amp;', '&', $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'] . '&extensionInstaller=1', 'SSL')),
                        'path' => $path
                    );
					// Clear temporary files
					$json['step'][] = array(
						'text' => $this->language->get('text_remove'),
						'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/remove', 'token=' . $this->session->data['token'], 'SSL')),
						'path' => $path
					);
				} else {
					$json['error'] = $this->language->get('error_file');
				}
			}

			// If zip file copy it to the temp directory
			if (strrchr($this->request->files['file']['name'], '.') == '.zip') {
				$file = DIR_UPLOAD . $path . '/upload.zip';

				move_uploaded_file($this->request->files['file']['tmp_name'], $file);

				$this->prepareSteps($file, $path, $json);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unzip() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Sanitize the filename
		$file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/upload.zip';

		if (!file_exists($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.unzip', $file);

            $installer = new Installer($this->registry);

			if (!$installer->unzip($file)) {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function ftp() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

        $check_folder = 0;
        if ($folder = opendir(DIR_UPLOAD . $this->request->post['path'].'/')) {
            while (false !== ($sub_folder = readdir($folder))) {
                if ($sub_folder != "." && $sub_folder != "..") {
                    if (is_dir(DIR_UPLOAD . $this->request->post['path'] . '/' . $sub_folder)) {
                        $check_folder = 1 ;
                        break;
                    }
                }
            }
        }

        if(empty($check_folder)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

		$directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/upload/';

        if (!is_dir($directory)) {
			$directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/UPLOAD/';
		}

		if (!is_dir($directory)) {
			$directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/Upload/';
		}

		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.ftp', $directory);

            // Get a list of files ready to upload
			$files = array();

			$path = array($directory . '*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}
					
					$edit_page_url = explode("upload", strtolower($file));
					$edit_page_url = 'upload'.$edit_page_url[2];

					$this->permission_control($edit_page_url);
                    $this->replaceFileArastta($file);

                    $files[] = $file;
				}
			}

			# admin / controller / extension / to Arastta Root.
			$root = dirname( dirname( dirname( __DIR__ ) ) ) . '/';

            foreach ($files as $file) {
                // Upload everything in the upload directory
                
                $destination = substr($file, strlen($directory));
                if (is_dir($file)) {
                    if (!is_dir($root.$destination)) {
                        if (!mkdir($root.$destination)) {
                            $json['error'] = sprintf($this->language->get('error_ftp_directory'), $destination);
                            exit();
                        }
                    }
                }

                if (is_file($file)) {
                    if (!copy($file, $root.$destination)) {
                        $json['error'] = sprintf($this->language->get('error_ftp_file'), $file);
                    }
                }
            }		
			
		    $this->response->setOutput(json_encode($json));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function permission_control($edit_page_url){
        $search_page = array(
            'appearance' 			=> 'appearance',
            'catalog' 			    => 'catalog',
            'product' 				=> 'product',
            'common' 				=> 'common',
            'dashboard' 			=> 'dashboard',
            'design' 		    	=> 'design',
            'error' 				=> 'error',
            'extension' 			=> 'extension',
            'feed' 		        	=> 'feed',
            'localisation' 		    => 'localisation',
            'marketing' 		    => 'marketing',
            'module' 	    		=> 'module',
            'payment' 				=> 'payment',
            'report'                => 'report',
            'sale' 				    => 'sale',
            'search' 				=> 'search',
            'setting' 				=> 'setting',
            'shipping' 				=> 'shipping',
            'system' 				=> 'system',
            'tool'   				=> 'tool',
            'total' 				=> 'total',
            'user' 				    => 'user'
        );
		
        foreach($search_page as $page) {
			if (strpos($edit_page_url, 'upload/admin/controller/'.$page)!== false){

				$permissions_page = explode($page,$edit_page_url);
				$permissions_page = $page.str_replace('.php','',$permissions_page[1]);

				$this->permission($permissions_page);

			}
        }
    }

    public function permission($page){
        //insert permission for support/support
        $permission = $this->db->query("SELECT permission FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = 1");

        $permission = unserialize($permission->row['permission']);

        if (!array_search($page, $permission['access'])){
            $permission['access'][] = $page;
            $permission['modify'][] = $page;
        }

        $permission = serialize($permission);

        $this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '".$permission."' WHERE `user_group_id` = 1");
    }

    public function replaceFileArastta($file) {
        $replace_text = array(
            'VQMod::modCheck' => 'modification',
            '$this->event->trigger' => '$this->trigger->fire',
            'new Mail()' => 'new Mail($this->config->get(\'config_mail\'))'
        );

        if(is_file($file)){
            $content = file_get_contents($file);
			
			// Just add admin file in content.
			if(strpos($file, 'admin') !== false && strpos($content, 'design/layout') !== false){
                $replace_text['design/layout'] = 'appearance/layout';
                $replace_text['design_layout'] = 'appearance_layout';
            }

            foreach($replace_text as $key => $value){
                $content = str_replace($key, $value, $content);
            }

            file_put_contents($file, $content);
        }
    }

	public function sql() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.sql';

		if (!file_exists($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$lines = file($file);

			if ($lines) {
                // Fire event
                $this->trigger->fire('pre.admin.extension.sql', $lines);

                try {
					$sql = '';

					foreach ($lines as $line) {
						if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
							$sql .= $line;

							if (preg_match('/;\s*$/', $line)) {
								$sql = str_replace(" `oc_", " `" . DB_PREFIX, $sql);
								$sql = str_replace(" `ar_", " `" . DB_PREFIX, $sql);

								$this->db->query($sql);

								$sql = '';
							}
						}
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function xml() {
		$this->load->language('extension/installer');

        $isVqmod = 0;
		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

        if(file_exists(DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.xml')){
            $file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.xml';
        } else if (file_exists(DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/' . $this->session->data['vqmod_file_name'])){
            $file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/' . $this->session->data['vqmod_file_name'];
        }
		
		if (!file_exists($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
            $json['overwrite'] = array();
			$this->load->model('extension/modification');

			// If xml file just put it straight into the DB
			$xml = file_get_contents($file);

			if ($xml) {
                // Fire event
                $this->trigger->fire('pre.admin.extension.xml', $xml);

                try {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($xml);
					
					$name = $dom->getElementsByTagName('name')->item(0);

					if ($name) {
						$name = $name->nodeValue;
					} else {
						$name = '';
					}
					
					$code = $dom->getElementsByTagName('code')->item(0);
					if(!empty($code)) {
						if ($code) {
							$code = $code->nodeValue;
							
							// Check to see if the modification is already installed or not.
							$modification_info = $this->model_extension_modification->getModificationByCode($code);
							
							if ($modification_info) {
								$json['overwrite'][] =  $code . '.xml';
								$json['error'] = sprintf($this->language->get('error_exists'), $modification_info['name']);
							}
						} else {
							$json['error'] = $this->language->get('error_code');
						}
					} else {
                        $isVqmod = 1 ;
                    }	
					$author = $dom->getElementsByTagName('author')->item(0);

					if ($author) {
						$author = $author->nodeValue;
					} else {
						$author = '';
					}

					$version = $dom->getElementsByTagName('version')->item(0);

					if ($version) {
						$version = $version->nodeValue;
					} else {
						$version = '';
					}

					$link = $dom->getElementsByTagName('link')->item(0);

					if ($link) {
						$link = $link->nodeValue;
					} else {
						$link = '';
					}

                    #Xml replace text '', Because It doesn't necessary Arastta
                    $xml = '';
					
					$modification_data = array(
						'name'    => $name,
						'code'    => $code,
						'author'  => $author,
						'version' => $version,
						'link'    => $link,
						'xml'     => $xml,
						'status'  => 1
					);
					
					if (!$json['overwrite']) {
						$this->model_extension_modification->addModification($modification_data);
						$this->session->data['addon_params']['modification'][] = $this->db->getLastId();
					}
					
                    if ($isVqmod) {
                        $file  = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/' . $this->session->data['vqmod_file_name'];
                        $msmod = DIR_VQMOD.'xml/'. $this->session->data['vqmod_file_name'];
                    } else {
						if(is_file(DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']).'/install.xml')){
                            $file  = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']).'/install.xml';
                        } else{
                            $file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/' . $this->session->data['vqmod_file_name'];
                        }
                        $msmod = DIR_SYSTEM . 'xml/' . $code . '.xml';
                    }

                    if (!copy($file, $msmod)) {
                        $json['error'] =   $this->language->get('error_copy_xmls_file');
                    }

				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

        unset($this->session->data['vqmod_file_name']);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function php() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.php';

		if (!file_exists($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.php', $file);

            try {
				include($file);
			} catch(Exception $exception) {
				$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function json() {
		$this->load->language('extension/installer');
		$this->load->model('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']) . '/install.json';

		if (!file_exists($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.json', $file);

			$data = file_get_contents($file);
			$data = json_decode($data, true);

			if (json_last_error() != JSON_ERROR_NONE) {
				$json['error'] = $this->language->get('error_json_' . json_last_error());
			} elseif (count($data) and array_key_exists('translation', $data)) {

				if ($this->model_extension_installer->languageExist($data['translation']['directory'])) {
					$json['error'] = $this->language->get('error_language_exist');
				} else {
					$this->load->model('localisation/language');

					$data['translation']['locale'] = null;
					$data['translation']['status'] = 1;
					$data['translation']['sort_order'] = 0;
					$language_id = $this->model_localisation_language->addLanguage($data['translation']);
					$this->session->data['addon_params']['language'][] = $language_id;

				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_UPLOAD . str_replace(array('../', '..\\', '..'), '', $this->request->post['path']);

		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
            // Fire event
            $this->trigger->fire('pre.admin.extension.remove', $directory);

			// Add addon to addon table
			if (isset($this->request->post['product_id']) and isset($this->request->post['product_name']) and isset($this->request->post['install_url']) and isset($this->request->post['product_version'])) {
				$addon = new Addon($this->registry);
				$data = array(
					'product_id' => $this->request->post['product_id'],
					'product_name' => $this->request->post['product_name'],
					'install_url' => $this->request->post['install_url'],
					'product_version' => $this->request->post['product_version'],
					'addon_params' => isset($this->session->data['addon_params']) ? $this->session->data['addon_params'] : null,
					'dir' => $directory,
				);

				$addon->addAddon($data);

				unset($this->session->data['addon_params']);

				$this->cache->remove('addon');
				$this->cache->remove('update');
				$this->cache->remove('version');
			}

			// Get a list of files ready to upload
			$files = array();

			$path = array($directory);

			while (count($path) != 0) {
				$next = array_shift($path);

				// We have to use scandir function because glob will not pick up dot files.
				foreach (array_diff(scandir($next), array('.', '..')) as $file) {
					$file = $next . '/' . $file;
					
					if (is_dir($file)) {
						$path[] = $file;
					}

					$files[] = $file;
				}
			}

			sort($files);
			rsort($files);

			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}

			if (file_exists($directory)) {
				rmdir($directory);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$directories = glob(DIR_UPLOAD . 'temp-*', GLOB_ONLYDIR);

			foreach ($directories as $directory) {
				// Get a list of files ready to upload
				$files = array();

				$path = array($directory);

				while (count($path) != 0) {
					$next = array_shift($path);
					
					// We have to use scandir function because glob will not pick up dot files.
					foreach (array_diff(scandir($next), array('.', '..')) as $file) {
						$file = $next . '/' . $file;
						
						if (is_dir($file)) {
							$path[] = $file;
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}

				if (file_exists($directory)) {
					rmdir($directory);
				}
			}

			$json['success'] = $this->language->get('text_clear');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		$this->load->language('extension/installer');
		$json = array();
		$data = $this->utility->getRemoteData(html_entity_decode($this->request->post['install_url']), array('referrer' => true));

		if ($data) {
			$path = 'temp-' . md5(mt_rand());
			$file = DIR_UPLOAD . $path . '/upload.zip';

			if (!is_dir(DIR_UPLOAD . $path)) {
				$this->filesystem->mkdir(DIR_UPLOAD . $path);
			}
			$ret = is_int(file_put_contents($file, $data)) ? true : false;

			if ($ret) {
				$this->request->post['path'] = $path;
				$this->prepareSteps($file, $path, $json);
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_download');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function readZip($zip, $path, &$json) {
		while ($entry = zip_read($zip)) {
			$zip_name = zip_entry_name($entry);

			// SQL
			if (substr($zip_name, 0, 11) == 'install.sql') {
				$json['step'][] = array(
					'text' => $this->language->get('text_sql'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/sql', 'token='.$this->session->data['token'], 'SSL')),
					'path' => $path
				);
			}

			// XML
			if (substr($zip_name, 0, 11) == 'install.xml') {
				$json['step'][] = array(
					'text' => $this->language->get('text_xml'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/xml', 'token='.$this->session->data['token'], 'SSL')),
					'path' => $path
				);
			}
			else if (substr($zip_name, -4) == '.xml') {
				$check = strpos($zip_name, '/');
				if (!empty($check) && $check > 0) {
					$_msmod   = explode('/', $zip_name);
					$zip_name = end($_msmod);
				}

				if (is_file(DIR_VQMOD.'xml/'.$zip_name)) {
					$_file = DIR_VQMOD.'xml/'.$zip_name;
				}
				else {
					$_file = DIR_SYSTEM.'xml/'.$zip_name;
				}

				$vqmodNameLen = strlen($zip_name);
				$vqmodNameLen = $vqmodNameLen + 11;

				if (is_file($_file) && strtolower(substr($_file, -$vqmodNameLen)) == '/vqmod/xml/'.$zip_name) {
					$json['overwrite'][] = 'vqmod/xml/'.$zip_name;
				}
				else if (is_file($_file) && strtolower(substr($_file, -$vqmodNameLen)) == '/system/xml/'.$zip_name) {
					$json['overwrite'][] = 'system/xml/'.$zip_name;
				}

			}
			// PHP
			if (substr($zip_name, 0, 11) == 'install.php') {
				$json['step'][] = array(
					'text' => $this->language->get('text_php'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/php', 'token='.$this->session->data['token'], 'SSL')),
					'path' => $path
				);
			}
			// JSON
			if (substr($zip_name, 0, 12) == 'install.json') {
				$json['step'][] = array(
					'text' => $this->language->get('text_json'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/json', 'token='.$this->session->data['token'], 'SSL')),
					'path' => $path
				);
			}

			// Compare admin files
			$file = DIR_APPLICATION.substr($zip_name, 13);
			if (is_file($file) && strtolower(substr($zip_name, 0, 13)) == 'upload/admin/') {
				$json['overwrite'][] = substr($zip_name, 7);
			}

			// Compare catalog files
			$file = DIR_CATALOG.substr($zip_name, 15);

			if (is_file($file) && strtolower(substr($zip_name, 0, 15)) == 'upload/catalog/') {
				$json['overwrite'][] = substr($zip_name, 7);
			}

			// Compare image files
			$file = DIR_IMAGE.substr($zip_name, 13);

			if (is_file($file) && strtolower(substr($zip_name, 0, 13)) == 'upload/image/') {
				$json['overwrite'][] = substr($zip_name, 7);
			}

			// Compare system files
			$file = DIR_SYSTEM.substr($zip_name, 14);

			if (is_file($file) && strtolower(substr($zip_name, 0, 14)) == 'upload/system/') {
				$json['overwrite'][] = substr($zip_name, 7);
			}

			// Compare system files
			$file = DIR_VQMOD.substr($zip_name, 7);
			if (is_file($file) && strtolower(substr($zip_name, 0, 13)) == 'upload/vqmod/') {
				$json['overwrite'][] = substr($zip_name, 7);
			}
		}
	}

	public function prepareSteps($file, $path, &$json) {
		if (file_exists($file)) {
			$zip = zip_open($file);

			if ($zip) {
				// Zip
				$json['step'][] = array(
					'text' => $this->language->get('text_unzip'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/unzip', 'token='.$this->session->data['token'], 'SSL')),
					'path' => $path
				);

				// FTP
				$json['step'][] = array(
					'text' => $this->language->get('text_ftp'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/ftp', 'token='.$this->session->data['token'], 'SSL')),
					'path' => $path
				);

				// Send make and array of actions to carry out
				$this->readZip($zip, $path, $json);

				# Refresh Modification
				$json['step'][] = array(
					'text' => $this->language->get('text_modification'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/modification/refresh', 'token='.$this->session->data['token'].'&extensionInstaller=1', 'SSL')),
					'path' => $path
				);
				// Clear temporary files
				$json['step'][] = array(
					'text' => $this->language->get('text_remove'),
					'url'  => str_replace('&amp;', '&', $this->url->link('extension/installer/remove', 'token='.$this->session->data['token'], 'SSL')),
					'path' => $path
				);

				zip_close($zip);
			}
			else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}
		else {
			$json['error'] = $this->language->get('error_file');
		}
	}

	public function uninstall() {
		$this->load->language('extension/installer');

		$json = array();

		$addon_lib = new Addon($this->registry);
		$addon = $addon_lib->getAddon($this->request->get['product_id']);

		if (empty($addon)) {
			$json['error'] = $this->language->get('error_uninstall_already');
		}

		if (!$json) {
			$files = json_decode($addon['addon_files']);

            // Fire event
            $this->trigger->fire('pre.admin.extension.uninstall', $files);

            $absolutePaths = $codes = array();
			foreach ($files as $file) {
				$absolutePaths[] = DIR_ROOT . $file;
			}

			// Remove files
			$this->filesystem->remove($absolutePaths);

			if ($addon['product_type'] == 'translation') {
				$this->load->model('localisation/language');
				$params = json_decode($addon['params']);
				foreach ($params->language as $id) {
					$lang = $this->model_localisation_language->getLanguage($id);
					if (isset($lang['directory'])) {
						$this->filesystem->remove(array(DIR_ROOT . 'admin/language/'. $lang['directory'], DIR_ROOT . 'catalog/language/'. $lang['directory']));
					}
				}

			}

			// Remove addon and modification if exists from table
			$addon_lib->removeAddon($this->request->get['product_id'], $addon['params']);

			// Refresh modifications
			$this->request->get['extensionInstaller'] = 1;
			$this->load->controller('extension/modification/refresh');
			unset($this->request->get['extensionInstaller']);

			$json['success'] = $this->language->get('text_uninstall_success');

			unset($this->session->data['addon_params']);

			$this->cache->remove('addon');
			$this->cache->remove('update');
			$this->cache->remove('version');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}