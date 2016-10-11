<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerApiSettings extends Controller
{
    
    public function getSettings($args = array())
    {
        $this->load->language('api/settings');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('setting/setting');

            $store_id = 0;

            if (!empty($args['url'])) {
                $this->load->model('setting/store');

                $stores = $this->model_setting_store->getStores();

                if (!empty($stores)) {
                    foreach ($stores as $store) {
                        $url = str_replace('http://', '', $store['url']);
                        $url = str_replace('https://', '', $url);
                        $url = rtrim($url, '/');

                        if ($url != $args['url']) {
                            continue;
                        }

                        $store_id = $store['store_id'];
                        break;
                    }
                }
            }

            $code = !empty($args['code']) ? $args['code'] : 'config';

            $config = $this->model_setting_setting->getSetting($code, $store_id);

            if (!empty($args['url'])) {
                $config['config_url'] = $args['url'];
            } else {
                $config_url = !empty($config['config_url']) ? $config['config_url'] : $this->config->get('config_url');
                
                $url = str_replace('http://', '', $config_url);
                $url = str_replace('https://', '', $url);
                $url = rtrim($url, '/');

                $config['config_url'] = $url;
            }

            if (!empty($config['config_image'])) {
                $config['config_image'] = 'image/' . $config['config_image'];
            } else {
                $config['config_image'] = 'image/placeholder.png';
            }

            $config['config_logo'] = 'image/' . $config['config_logo'];
            $config['config_icon'] = 'image/' . $config['config_icon'];

            $json = $config;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
