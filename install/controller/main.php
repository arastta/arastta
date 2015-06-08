<?php
/**
 * @package	Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license	GNU General Public License version 3; see LICENSE.txt
 */

class ControllerMain extends Controller {
	public function index() {
		$this->load->model('main');

		$data = $this->language->all();

		$this->document->setTitle($data['heading_main']);

        	$data['requirements'] = $this->model_main->checkRequirements();

        	$data['header'] = $this->load->controller('header');
        	$data['footer'] = $this->load->controller('footer');

		$this->response->setOutput($this->load->view('main.tpl', $data));
	}

    public function displayDatabase() {
        $data = array();
        $data = $this->language->all();

        $vars = array(
			'db_hostname'	=> 'localhost',
			'db_username'	=> '',
			'db_password'	=> '',
			'db_database'	=> ''
		);

		foreach( $vars as $k => $v ) {
			if (isset($this->session->data[$k])) {
	            $data[$k] = $this->session->data[$k];
	        } else {
	            $data[$k] = $v;
	        }
		}

		unset( $vars );

		$data['action']		= $this->url->link('main');
        $data['db_prefix']	= $this->generatePrefix();

        $json['output'] = $this->load->view('database.tpl', $data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	public function saveDatabase() {
        $this->load->model('main');

        $json = $this->validateDatabase();

		if (empty($json)) {
            $this->model_main->saveConfig($this->request->post);

            $this->displaySettings();
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
		}
	}

    public function displaySettings() {
        $data = array();
        $data = $this->language->all();

		$vars = array(
			'store_name'		=> '',
			'store_email'		=> '',
			'admin_username'	=> '',
			'admin_email'		=> '',
			'admin_password'	=> ''
		);

		foreach( $vars as $k => $v ) {
			if (isset($this->session->data[$k])) {
	            $data[$k] = $this->session->data[$k];
	        } else {
	            $data[$k] = $v;
	        }
		}

        $data['action'] = $this->url->link('main');

        $json['output'] = $this->load->view('settings.tpl', $data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	public function saveSettings() {
        $json = $this->validateSettings();

        if (empty($json)) {
            $this->load->model('main');

            $this->model_main->createDatabaseTables($this->request->post);

            $this->displayFinish();
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
	}

    public function displayFinish() {
        $data = array();

        $data['button_store']		= $this->language->get('button_store');
        $data['button_admin']		= $this->language->get('button_admin');
        $data['text_finish_header'] = $this->language->get('text_finish_header');

        $data['store'] = HTTP_CATALOG;
        $data['admin'] = HTTP_CATALOG.'admin';

        $json['output'] = $this->load->view('finish.tpl', $data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeInstall() {
        $this->load->model('main');
        $this->model_main->removeInstall();
    }

    protected function validateDatabase() {
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

        if (empty($this->request->post['db_prefix'])) {
            $json['error']['prefix'] = $this->language->get('error_db_prefix');
        }

        if (!$this->model_main->validateDatabaseConnection($this->request->post)) {
            $json['error']['prefix'] = $this->language->get('error_db_connect');
        }

        return $json;
    }

    protected function validateSettings() {
        $json = array();

        if (empty($this->request->post['store_name'])) {
            $json['error']['store-name'] = $this->language->get('error_store_name');
        }

        if (empty($this->request->post['store_email'])) {
            $json['error']['store-email'] = $this->language->get('error_store_email');
        }

        if ((utf8_strlen(trim($this->request->post['admin_username'])) < 3) || (utf8_strlen(trim($this->request->post['admin_username'])) > 32)) {
            $json['error']['admin-username'] = $this->language->get('error_admin_username');
        }

        if (empty($this->request->post['admin_email'])) {
            $json['error']['admin-email'] = $this->language->get('error_admin_email');
        }

        if (empty($this->request->post['admin_password'])) {
            $json['error']['admin-password'] = $this->language->get('error_admin_password');
        }

        return $json;
    }

    protected function generatePrefix() {
        // Create the random prefix.
        $prefix = '';
        $chars = range('a', 'z');
        $numbers = range(0, 9);

        // We want the fist character to be a random letter.
        shuffle($chars);
        $prefix .= $chars[0];

        // Next we combine the numbers and characters to get the other characters.
        $symbols = array_merge($numbers, $chars);
        shuffle($symbols);

        for ($i = 0, $j = 3 - 1; $i < $j; ++$i)
        {
            $prefix .= $symbols[$i];
        }

        // Append the underscore.
        $prefix .= '_';

        return $prefix;
    }
}
