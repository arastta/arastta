<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonLogin extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('common/login');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addStyle('view/stylesheet/login.css');

        // Include extra event folders
        $this->trigger->addFolder('authentication');
        $this->trigger->addFolder('twofactorauth');

        // Useful for 3rd party authentication
        $this->trigger->fire('pre.admin.login');

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // Required for 2FA
            $this->trigger->fire('post.admin.login');

            $this->session->data['token'] = md5(mt_rand());

            if (!empty($this->request->post['lang']) && $this->request->post['lang'] != '*') {
                $this->session->data['admin_language'] = $this->request->post['lang'];
            }

            if ($this->config->get('config_sec_admin_login')) {
                $mailData = array(
                    'email'   => $this->request->post['email'],
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
                $this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
            } else {
                $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $data = $this->language->all();

        if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
            $this->error['warning'] = $this->language->get('error_token');
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('common/login', '', 'SSL');

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

        if (isset($this->request->get['route'])) {
            $route = $this->request->get['route'];

            unset($this->request->get['route']);
            unset($this->request->get['token']);

            $url = '';

            if ($this->request->get) {
                $url .= http_build_query($this->request->get);
            }

            $data['redirect'] = $this->url->link($route, $url, 'SSL');
        } else {
            $data['redirect'] = '';
        }

        if ($this->config->get('config_password')) {
            if ($this->config->get('config_sec_admin_keyword')) {
                $data['forgotten'] = $this->url->link('common/forgotten', $this->config->get('config_sec_admin_keyword'), 'SSL');
            } else {
                $data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
            }
        } else {
            $data['forgotten'] = '';
        }
        
        $this->load->model('tool/image');

        if ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 85, 85);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 85, 85);
        }
        
        $data['store'] = array(
            'name' => $this->config->get('config_name'),
            'href' => ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG
        );

        // Language list
        $this->load->model('localisation/language');

        $total_languages = $this->model_localisation_language->getTotalLanguages();
        if ($total_languages > 1) {
            $data['languages'] = $this->model_localisation_language->getLanguages();
        } else {
            $data['languages'] = '';
            $this->session->data['admin_language'] = $this->config->get('config_admin_language');
        }

        // Two-Factor Authenticators
        $this->load->model('extension/extension');

        $twofactorauths = $this->model_extension_extension->getEnabledExtensions(array('filter_type' => 'twofactorauth'));

        if (!empty($twofactorauths)) {
            $data['twofactorauth'] = 'enabled';
        } else {
            $data['twofactorauth'] = '';
        }

        $data['config_admin_language'] = $this->config->get('config_admin_language');

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/login.tpl', $data));
    }

    protected function validate() {
        if (!isset($this->request->post['email']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['email'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_match');
        }

        return !$this->error;
    }

    public function check() {
        $route = '';

        if (isset($this->request->get['route'])) {
            $part = explode('/', $this->request->get['route']);

            if (isset($part[0])) {
                $route .= $part[0];
            }

            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }
        }

        $ignore = array(
            'common/login',
            'common/forgotten',
            'common/reset'
        );

        if (!$this->user->isLogged() && !in_array($route, $ignore)) {
            return new Action('common/login');
        }

        if (isset($this->request->get['route'])) {
            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return new Action('common/login');
            }
        } else {
            if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return new Action('common/login');
            }
        }
    }
}
