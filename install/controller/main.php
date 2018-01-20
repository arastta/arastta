<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerMain extends Controller
{

    public function index()
    {
        $this->load->model('main');

        $data = $this->language->all();

        $this->document->setTitle($data['heading_main']);

        $data['requirements'] = $this->model_main->checkRequirements();

        $data['lang'] = $this->request->get['lang'];

        $data['header'] = $this->load->controller('header');
        $data['footer'] = $this->load->controller('footer');

        $this->response->setOutput($this->load->view('main.tpl', $data));
    }
}
