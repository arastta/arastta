<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerModuleGoogleHangouts extends Controller {
    public function index() {
        $this->load->language('module/google_hangouts');

        $data['heading_title'] = $this->language->get('heading_title');

        if ($this->request->server['HTTPS']) {
            $data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('google_hangouts_code')));
        } else {
            $data['code'] = html_entity_decode($this->config->get('google_hangouts_code'));
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/google_hangouts.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/google_hangouts.tpl', $data);
        } else {
            return $this->load->view('default/template/module/google_hangouts.tpl', $data);
        }
    }
}
