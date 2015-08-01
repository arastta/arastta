<?php
/**
 * @package     Arastta eCommerce
 * @copyright   Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license     GNU General Public License version 3; see LICENSE.txt
 */

class ControllerDatabase extends Controller {

    public function index() {
        $this->load->model('database');

        $data = $this->language->all();

        $data['action'] = $this->url->link('database', 'lang='.$this->request->get['lang']);

        if (isset($this->session->data['db_hostname'])) {
            $data['db_hostname'] = $this->session->data['db_hostname'];
        } else {
            $data['db_hostname'] = 'localhost';
        }

        if (isset($this->session->data['db_username'])) {
            $data['db_username'] = $this->session->data['db_username'];
        } else {
            $data['db_username'] = '';
        }

        if (isset($this->session->data['db_password'])) {
            $data['db_password'] = $this->session->data['db_password'];
        } else {
            $data['db_password'] = '';
        }

        if (isset($this->session->data['db_database'])) {
            $data['db_database'] = $this->session->data['db_database'];
        } else {
            $data['db_database'] = '';
        }

        $data['db_prefix'] = $this->model_database->generatePrefix();

        if (isset($this->session->data['db_driver'])) {
            $data['db_driver'] = $this->session->data['db_driver'];
        } else {
            $data['db_driver'] = 'mysqli';
        }

        $json['output'] = $this->load->view('database.tpl', $data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function save() {
        $this->load->model('database');

        $json = $this->validate();

        if (empty($json)) {
            if(!$this->model_database->saveConfig($this->request->post)) {
                $json['error']['config'] = $this->language->get('error_config');

                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
            } else {
                $this->load->controller('setting');
            }
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    protected function validate() {
        $json = array();

        if (empty($this->request->post['db_hostname'])) {
            $json['error']['hostname'] = $this->language->get('error_db_hostname');
        }

        if (empty($this->request->post['db_username'])) {
            $json['error']['username'] = $this->language->get('error_db_username');
        }

        if (empty($this->request->post['db_database'])) {
            $json['error']['database'] = $this->language->get('error_db_database');
        }

        if (!empty($this->request->post['db_prefix']) && !preg_match("/^[a-z0-9_]+$/", $this->request->post['db_prefix'])) {
            $json['error']['prefix'] = $this->language->get('error_db_prefix');
        }

        if (!$this->model_database->validateConnection($this->request->post)) {
            $json['error']['prefix'] = $this->language->get('error_db_connect');
        }

        return $json;
    }
}
