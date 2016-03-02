<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonLanguage extends Controller {
    public function index() {
        $this->load->language('common/language');

        $data['text_language'] = $this->language->get('text_language');

        $data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

        $data['code'] = $this->session->data['language'];

        $this->load->model('localisation/language');

        $data['languages'] = array();

        $results = $this->model_localisation_language->getLanguages();

        foreach ($results as $result) {
            if ($result['status']) {
                $data['languages'][] = array(
                    'name'  => $result['name'],
                    'code'  => $result['code'],
                    'image' => $result['image']
                );
            }
        }

        $url_data = $this->request->get;
        unset($url_data['lang']);
        unset($url_data['_route_']);

        if (!isset($url_data['route'])) {
            $url_data['route'] = 'common/home';
        }

        $data['redirect'] = htmlspecialchars(urldecode(http_build_query($url_data, '', '&')));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/language.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/language.tpl', $data);
        } else {
            return $this->load->view('default/template/common/language.tpl', $data);
        }
    }

    public function language() {
        if (isset($this->request->post['code'])) {
            $this->session->data['language'] = $this->request->post['code'];
        }

        if (empty($this->request->post['redirect'])) {
            return;
        }

        parse_str(str_replace('&amp;', '&', $this->request->post['redirect']), $query);

        if ($this->config->get('config_seo_lang_code')) {
            $query['lang'] = $this->session->data['language'];
        }

        $route = $query['route'];
        unset($query['route']);

        $url = '&' . urldecode(http_build_query($query, '', '&'));

        $link = $this->url->link($route, $url, $this->request->server['HTTPS']);

        $this->response->redirect($link);
    }
}
