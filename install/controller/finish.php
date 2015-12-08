<?php
/**
 * @package     Arastta eCommerce
 * @copyright       Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license     GNU General Public License version 3; see LICENSE.txt
 */

class ControllerFinish extends Controller
{

    public function index()
    {
        $data = $this->language->all();

        $data['store'] = HTTP_CATALOG;
        $data['admin'] = HTTP_CATALOG.'admin';

        $data['text_finish_header'] = $this->language->get('text_finish_header');

        $json['output'] = $this->load->view('finish.tpl', $data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeInstall()
    {
        $this->load->model('finish');

        $json['success'] = $this->model_finish->removeInstall();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
