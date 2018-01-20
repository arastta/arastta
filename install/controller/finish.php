<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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
