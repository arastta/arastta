<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerModuleHTML extends Controller {
    public function index($setting) {
        if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
            $data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
            $data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
        
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/html.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/html.tpl', $data);
            } else {
                return $this->load->view('default/template/module/html.tpl', $data);
            }
        }
    }
}
