<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventLocalisationLanguage extends Event
{
    public function preAdminLanguageEdit($data)
    {
        $data['return'] = 'back';

        $data['selected'][] = $this->request->get['language_id'];

        $this->preAdminLanguageStatusEdit($data);


        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
            $this->response->redirect($this->url->link('localisation/language/edit', 'language_id=' . $this->request->get['language_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
            $this->response->redirect($this->url->link('localisation/language/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->response->redirect($this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function preAdminLanguageStatusEdit($data)
    {
        if (!empty($data['status'])) {
            return;
        }

        $this->load->model('setting/store');
        $this->load->model('localisation/language');

        $this->language->load('localisation/language');

        $stores = $this->model_setting_store->getStores();

        if ($stores) {
            $this->load->model('setting/setting');

            $store_languages = array();

            foreach ($stores as $store) {
                $setting = $this->model_setting_setting->getSetting('config', $store['store_id']);

                $language = $this->model_localisation_language->getLanguageByCode($setting['config_language']);

                $store_languages[] = array(
                    'store_id'      => $store['store_id'],
                    'language_id'   => $language['language_id']
                );
            }
        }

        $site_language = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language'));
        $admin_language = $this->model_localisation_language->getLanguageByCode($this->config->get('config_admin_language'));

        $default = $store = false;
        $store_total = 0;

        foreach ($data['selected'] as $id) {
            if ($site_language['language_id'] == $id && $admin_language['language_id'] == $id) {
                $default = true;
                $store_total++;

                $this->session->data['warning'] = $this->language->get('error_both_status');
            } else if ($site_language['language_id'] == $id) {
                $default = true;
                $store_total++;

                $this->session->data['warning'] = $this->language->get('error_default_status');
            } else if ($admin_language['language_id'] == $id) {
                $default = true;

                $this->session->data['warning'] = $this->language->get('error_admin_status');
            }

            if ($store_languages) {
                foreach ($store_languages as $store_language) {
                    if ($store_language['language_id'] == $id) {
                        $default = true;
                        $store = true;
                        $store_total++;
                    }
                }
            }
        }

        if ($default) {
            if ($store_total > 1 || $store) {
                $this->session->data['warning'] =  sprintf($this->language->get('error_store_status'), $store_total);
            }

            if (!empty($data['return']) && $data['return'] == 'back') {
                return;
            }

            $json['redirect'] = str_replace('&amp;', '&', $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL'));

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            $this->response->output();

            exit();
        }
    }
}
