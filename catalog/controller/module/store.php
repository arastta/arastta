<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerModuleStore extends Controller {
    public function index() {
        $status = true;

        if ($this->config->get('store_admin')) {
            $this->load->library('user');

            $this->user = new User($this->registry);

            $status = $this->user->isLogged();
        }

        if ($status) {
            $this->load->language('module/store');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_store'] = $this->language->get('text_store');

            $data['store_id'] = $this->config->get('config_store_id');

            $data['stores'] = array();

            $data['stores'][] = array(
                'store_id' => 0,
                'name'     => $this->language->get('text_default'),
                'url'      => ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER . 'index.php?route=common/home&session_id=' . $this->session->getId()
            );

            $this->load->model('setting/store');

            $results = $this->model_setting_store->getStores();

            foreach ($results as $result) {
                $data['stores'][] = array(
                    'store_id' => $result['store_id'],
                    'name'     => $result['name'],
                    'url'      => $result['url'] . 'index.php?route=common/home&session_id=' . $this->session->getId()
                );
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/store.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/store.tpl', $data);
            } else {
                return $this->load->view('default/template/module/store.tpl', $data);
            }
        }
    }
}
