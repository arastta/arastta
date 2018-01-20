<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerApiInformations extends Controller
{

    public function getInformation($args = array())
    {
        $this->load->language('api/informations');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/informations');

            $information = $this->model_api_informations->getInformation($args);

            $information['title'] = html_entity_decode($information['title'], ENT_QUOTES, 'UTF-8');
            $information['description'] = html_entity_decode($information['description'], ENT_QUOTES, 'UTF-8');

            $json = $information;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function getInformations($args = array())
    {
        $this->load->language('api/informations');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/informations');

            $information_data = array();

            $results = $this->model_api_informations->getInformations($args);

            if (!empty($results)) {
                foreach ($results as $result) {
                    $result['title'] = html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8');
                    $result['description'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');

                    $information_data[] = $result;
                }
            }

            $json = $information_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = array())
    {
        $this->load->language('api/informations');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/informations');

            $json = $this->model_api_informations->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
