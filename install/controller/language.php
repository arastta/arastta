<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerLanguage extends Controller
{

    public function index()
    {
        $this->load->model('language');

        $data = $this->language->all();

        $this->document->setTitle('Arastta');

        $data['languages'] = $this->model_language->getLanguages();

        $data['header'] = $this->load->controller('header');
        $data['footer'] = $this->load->controller('footer');

        $this->response->setOutput($this->load->view('language.tpl', $data));
    }

    public function save()
    {
        $this->load->model('language');

        $json = $this->validate();

        if (empty($json)) {
            $lang = $this->model_language->downloadLanguage($this->request->post);

            if (!$lang) {
                $json['error']['lang_download'] = $this->language->get('error_lang_download');
            } else {
                $json['lang'] = $lang;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        $json = array();

        if (empty($this->request->post['lang_code'])) {
            $json['error']['lang_code'] = $this->language->post('error_lang_code');
        }

        return $json;
    }
}
