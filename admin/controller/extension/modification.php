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
			
			//Log
			$log = array();

			// Clear all modification files
			$files = array();
			
			// Make path into an array
			$path = array(DIR_MODIFICATION . '*');

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}
			
			// Reverse sort the file array
			rsort($files);
			
			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_MODIFICATION . 'index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);
	
					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
			}	
			
			// Begin
			$xml = array();

			// Get the default modification file

            $results = glob(DIR_SYSTEM.'xml/*.xml');

			foreach ($results as $result) {
			    $xml[] = file_get_contents($result);
			}

			$modification = array();

			foreach ($xml as $xml) {
				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->preserveWhiteSpace = false;
				$dom->loadXml($xml);
				
				// Log
				$log[] = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

				// Wipe the past modification store in the backup array
				$recovery = array();
				
				// Set the a recovery of the modification code in case we need to use it if an abort attribute is used.
				if (isset($modification)) {
					$recovery = $modification;
				}

				$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

				foreach ($files as $file) {
					$operations = $file->getElementsByTagName('operation');

					$path = '';

					// Get the full path of the files that are going to be used for modification
					if (substr($file->getAttribute('path'), 0, 7) == 'catalog') {
						$path = DIR_CATALOG . str_replace('../', '', substr($file->getAttribute('path'), 8));
					}

					if (substr($file->getAttribute('path'), 0, 5) == 'admin') {
						$path = DIR_APPLICATION . str_replace('../', '', substr($file->getAttribute('path'), 6));
					}

					if (substr($file->getAttribute('path'), 0, 6) == 'system') {
						$path = DIR_SYSTEM . str_replace('../', '', substr($file->getAttribute('path'), 7));
					}

					if ($path) {
						$files = glob($path, GLOB_BRACE);

						if ($files) {
							foreach ($files as $file) {
								// Get the key to be used for the modification cache filename.
								if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
									$key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
								}

								if (substr($file, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
									$key = 'admin/' . substr($file, strlen(DIR_APPLICATION));
								}

								if (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
									$key = 'system/' . substr($file, strlen(DIR_SYSTEM));
								}
								
								// If file contents is not already in the modification array we need to load it.
								if (!isset($modification[$key])) {
									$content = file_get_contents($file);

									$modification[$key] = preg_replace('~\r?\n~', "\n", $content);
									$original[$key] = preg_replace('~\r?\n~', "\n", $content);

									// Log
									$log[] = 'FILE: ' . $key;
								}
								
								foreach ($operations as $operation) {
									$error = $operation->getAttribute('error');
									
									// Ignoreif
									$ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);
									
									if ($ignoreif) {
										if ($ignoreif->getAttribute('regex') != 'true') {
											if (strpos($modification[$key], $ignoreif->textContent) !== false) {
												continue;
											}												
										} else {
											if (preg_match($ignoreif->textContent, $modification[$key])) {
												continue;
											}
										}
									}
									
									$status = false;
									
									// Search and replace
									if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
										// Search
										$search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
										$trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
										$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');
										
										// Trim line if no trim attribute is set or is set to true.
										if (!$trim || $trim == 'true') {
											$search = trim($search);
										}
																				
										// Add
										$add = trim($operation->getElementsByTagName('add')->item(0)->textContent);
										$trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
										$position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
										$offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');										
										
										if ($offset == '') {
                                            $offset = 0;
                                        }

										// Trim line if is set to true.
										if ($trim == 'true') {
											$add = trim($add);
										}
										
										// Log
										$log[] = 'CODE: ' . $search;
										
										// Check if using indexes
										if ($index !== '') {
											$indexes = explode(',', $index);
										} else {
											$indexes = array();
										}
										
										// Get all the matches
										$i = 0;
										
										$lines = explode("\n", $modification[$key]);

										for ($line_id = 0; $line_id < count($lines); $line_id++) {
											$line = $lines[$line_id];
											
											// Status
											$match = false;
											
											// Check to see if the line matches the search code.
											if (stripos($line, $search) !== false) {
												// If indexes are not used then just set the found status to true.
												if (!$indexes) {
													$match = true;
												} elseif (in_array($i, $indexes)) {
													$match = true;
												}
												
												$i++;
											}
											
											// Now for replacing or adding to the matched elements
											if ($match) {
												switch ($position) {
													default:
													case 'replace':
														if ($offset < 0) {
															array_splice($lines, $line_id + $offset, abs($offset) + 1, array(str_replace($search, $add, $line)));
															
															$line_id -= $offset;
														} else {
															array_splice($lines, $line_id, $offset + 1, array(str_replace($search, $add, $line)));
														}
														break;
													case 'before':
														$new_lines = explode("\n", $add);
														
														array_splice($lines, $line_id - $offset, 0, $new_lines);
														
														$line_id += count($new_lines);
														break;
													case 'after':
														$new_lines = explode("\n", $add);

														array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

														$line_id += count($new_lines);
														break;
												}
												
												// Log
												$log[] = 'LINE: ' . $line_id;
												
												$status = true;										
											}
										}
										
										$modification[$key] = implode("\n", $lines);
									} else {									
										$search = $operation->getElementsByTagName('search')->item(0)->textContent;
										$limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
										$replace = $operation->getElementsByTagName('add')->item(0)->textContent;
										
										// Limit
										if (!$limit) {
											$limit = -1;
										}

										// Log
										$match = array();

										preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

										// Remove part of the the result if a limit is set.
										if ($limit > 0) {
											$match[0] = array_slice($match[0], 0, $limit);
										}

										if ($match[0]) {
											$log[] = 'REGEX: ' . $search;

											for ($i = 0; $i < count($match[0]); $i++) {
												$log[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
											}
											
											$status = true;
										}

										// Make the modification
										$modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
									}
									
									if (!$status) {
										// Log
										$log[] = 'NOT FOUND!';

										// Skip current operation
										if ($error == 'skip') {
											break;
										}
										
										// Abort applying this modification completely.
										if ($error == 'abort') {
											$modification = $recovery;
											
											// Log
											$log[] = 'ABORTING!';
										
											break 4;
										}
									}									
								}
							}
						}
					}
				}
				
				// Log
				$log[] = '----------------------------------------------------------------';				
			}

			// Log
			$ocmod = new Log('ocmod.log');
			$ocmod->write(implode("\n", $log));

            if(file_exists(DIR_VQMOD . 'xml')) {
                require_once(DIR_SYSTEM . 'library/modification.php');
                $_modCheck = new ArasttaModification();

				if(empty($original)) { $original = ''; }
				
                $_vQmod =  $_modCheck->checkVqmod($modification, $original);
                $modification = $_vQmod['modification'];
                $original = $_vQmod['original'];
				
            }

			// Write all modification files
			foreach ($modification as $key => $value) {
				// Only create a file if there are changes
				if ($original[$key] != $value) {
					$path = '';

					$directories = explode('/', dirname($key));

					foreach ($directories as $directory) {
						$path = $path . '/' . $directory;

						if (!is_dir(DIR_MODIFICATION . $path)) {
                            $this->filesystem->mkdir(DIR_MODIFICATION . $path);
						}
					}

					$handle = fopen(DIR_MODIFICATION . $key, 'w');

					fwrite($handle, $value);

					fclose($handle);
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
			$files = array();
			
			// Make path into an array
			$path = array(DIR_MODIFICATION . '*');

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}
			
			// Reverse sort the file array
			rsort($files);
			
			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_MODIFICATION . 'index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);
	
					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
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
			$handle = fopen(DIR_LOG . 'ocmod.log', 'w+');

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
                try {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->loadXml($xml);

                    $name = $dom->getElementsByTagName('name')->item(0);

                    if (!empty($name)) {
                        $name = $name->nodeValue;
                    }
                    else {
                        $name = '';
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
                        $author = '';
                    }

                    $version = $dom->getElementsByTagName('version')->item(0);

                    if (!empty($version)) {
                        $version = $version->nodeValue;
                    }
                    else {
                        $version = '';
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
                    );
                } catch(Exception $exception) {
                    $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
                }
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
		$file = DIR_LOG . 'ocmod.log';

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
					try {
						$dom = new DOMDocument('1.0', 'UTF-8');
						$dom->loadXml($xml);

						$name = $dom->getElementsByTagName('id')->item(0);

						if (!empty($name)) {
							$name = $name->nodeValue;
						}
						else {
							$name = '';
						}
						$_code = explode('.', $file_name);
						$code  = $_code[0];

						$author = $dom->getElementsByTagName('author')->item(0);

						if (!empty($author)) {
							$author = $author->nodeValue;
						}
						else {
							$author = '';
						}

						$version = $dom->getElementsByTagName('version')->item(0);

						if (!empty($version)) {
							$version = $version->nodeValue;
						}
						else {
							$version = '';
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
							'status'          => $status,
							'date_added'      => '',
							'enable'          => $this->url->link('extension/modification/enable', 'token=' . $this->session->data['token'] . '&modification_id=' . $vqmod_id, 'SSL'),
							'disable'         => $this->url->link('extension/modification/disable', 'token=' . $this->session->data['token'] . '&modification_id=' . $vqmod_id, 'SSL'),
							'enabled'         => $status,
						);
					} catch(Exception $exception) {
						$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
					}
				}
			}
		}
		
		if(empty($data)) {
			$data = NULL;
		}

        return $data;
    }
}