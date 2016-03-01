<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonMaintenance extends Controller {
    private $error = array();

    public function index() {
        if ($this->config->get('config_maintenance')) {
            $route = '';
            
            if (isset($this->request->get['route'])) {
                if ($this->request->get['route'] == 'common/maintenance/login'){
                    $this->login();

                    if (isset($this->error['warning'])) {
                        return $this->info();
                    }
                }
                
                $part = explode('/', $this->request->get['route']);
                
                if (isset($part[0])) {
                    $route .= $part[0];
                }
            }
            
            // Show site if logged in as admin
            $this->load->library('user');
            
            $this->user = new User($this->registry);
            
            if (($route != 'payment' && $route != 'api') && !$this->user->isLogged()) {
                return new Action('common/maintenance/info');
            }
        }
    }
    
    public function info() {
        $this->load->language('common/maintenance');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        if ($this->request->server['SERVER_PROTOCOL'] == 'HTTP/1.1') {
            $this->response->addHeader('HTTP/1.1 503 Service Unavailable');
        } else {
            $this->response->addHeader('HTTP/1.0 503 Service Unavailable');
        }
        
        $this->response->addHeader('Retry-After: 3600');
        
        $data = $this->language->all();
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_maintenance'),
            'href' => $this->url->link('common/maintenance')
        );
        
        $data['message'] = $this->language->get('text_message');
        
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
        
        $data['action'] = $this->url->link('common/maintenance/login', '', 'SSL');
        
        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }
        
        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }
        
        $this->load->model('tool/image');
        
        if ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 85, 85);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 85, 85);
        }
        
        // Language list
        $this->load->model('localisation/language');
        
        $total_languages = $this->model_localisation_language->getTotalLanguages();
        if ($total_languages > 1) {
            $data['languages'] = $this->model_localisation_language->getLanguages();
        } else {
            $data['languages'] = '';
            $this->session->data['admin_language'] = $this->config->get('config_admin_language');
        }
        
        $data['config_admin_language'] = $this->config->get('config_admin_language');
        
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/maintenance.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/maintenance.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/maintenance.tpl', $data));
        }
    }
    
    public function login() {
        $this->load->language('common/maintenance');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        // Show site if logged in as admin
        $this->load->library('user');
        
        $this->user = new User($this->registry);
        
        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('common/home', '', 'SSL'));
        }
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!empty($this->request->post['lang'])) {
                $this->session->data['admin_language'] = $this->request->post['lang'];
            }
            
            if ($this->config->get('config_sec_admin_login')) {
                
                $mailData = array(
                    'username'   => $this->request->post['email'],
                    'store_name' => $this->config->get('config_name'),
                    'ip_address' => $this->request->server['REMOTE_ADDR'],
                );
                
                $subject = $this->emailtemplate->getSubject('Login', 'admin_1', $mailData);
                $message = $this->emailtemplate->getMessage('Login', 'admin_1', $mailData);
                
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($this->config->get('config_sec_admin_login'));
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHtml($message);
                $mail->send();
                
            }
            
            if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
                $this->response->redirect($this->url->link('common/maintenance', '', 'SSL'));
            } else {
                $this->response->redirect($this->url->link('common/home', '', 'SSL'));
            }
        }
    }
    
    protected function validate() {
        if (!isset($this->request->post['email']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['email'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }
        
        return !$this->error;
    }
}
