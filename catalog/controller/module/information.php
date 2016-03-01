<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerModuleInformation extends Controller {
    public function index() {
        $this->load->language('module/information');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_sitemap'] = $this->language->get('text_sitemap');

        $this->load->model('catalog/information');

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            $data['informations'][] = array(
                'title' => $result['title'],
                'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
            );
        }

        $data['contact'] = $this->url->link('information/contact');
        $data['sitemap'] = $this->url->link('information/sitemap');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/information.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/information.tpl', $data);
        } else {
            return $this->load->view('default/template/module/information.tpl', $data);
        }
    }
}
