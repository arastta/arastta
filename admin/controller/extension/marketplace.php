<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerExtensionMarketplace extends Controller
{

    private $error = array();
    public $apiBaseUrl = 'https://arastta.pro/';

    public function index()
    {
        $this->load->language('extension/marketplace');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/marketplace');

        $this->document->addScript('../catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('../catalog/view/javascript/jquery/magnific/magnific-popup.css');
        $this->document->addScript('../catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('../catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('../catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $this->document->addScript('view/javascript/marketplace/marketplace.js');
        $this->document->addStyle('view/stylesheet/marketplace.css');

        unset($this->session->data['cookie']);

        if ($this->validate()) {
            // API
            $this->load->model('user/api');

            $post['api_key'] = $this->config->get('api_key');

            if ($post) {
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, $this->apiBaseUrl . 'index.php?route=api/key');
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
                curl_setopt($curl, CURLOPT_REFERER, $this->url->getDomain());

                $json = curl_exec($curl);

                if (!$json) {
                    $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
                } else {
                    $response = json_decode($json, true);
                    if (isset($response['error'])) {
                        $this->error['warning'] = $response['error'];
                        $data['error'] = $response['error'];
                    } elseif (isset($response['cookie'])) {
                        $this->session->data['cookie'] = $response['cookie'];
                    } else {
                        $this->error['warning'] = $this->language->get('error_default');
                    }
                }
                curl_close($curl);
            }
        }

        $data['apiBaseUrl'] = $this->apiBaseUrl;
        $data['base_url'] = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_list'] = $this->language->get('text_list');
        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['api_key'] = $this->config->get('api_key');
        $data['changeApiKey'] = false;

        $data['extension_installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
        $data['extension_modifications'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');
        $data['button_installer'] = $this->language->get('button_installer');
        $data['button_modifications'] = $this->language->get('button_modifications');

        if ((isset($this->request->get['changeApiKey']) && $this->request->get['changeApiKey']) || (empty($data['api_key']) || isset($data['error']))) {
            Client::setName('catalog');
            $this->load->language('account/login');
            Client::setName('admin');

            $data['text_new_customer'] = $this->language->get('text_new_customer');
            $data['text_register'] = $this->language->get('text_register');
            $data['text_forgotten'] = $this->language->get('text_forgotten');
            $data['text_login_api_key'] = $this->language->get('text_login_api_key');
            $data['text_login_email'] = $this->language->get('text_login_email');
            $data['text_signin_same_email'] = $this->language->get('text_signin_same_email');
            $data['text_by_registering_account'] = $this->language->get('text_by_registering_account');

            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_password'] = $this->language->get('entry_password');

            $data['button_continue'] = $this->language->get('button_continue');
            $data['button_login'] = $this->language->get('button_login_marketplace');

            $data['entry_api_key'] = $this->language->get('entry_api_key');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['help_api_key'] = $this->language->get('help_api_key');

            $data['email'] = '';
            $data['password'] = '';

            $data['action'] = $this->url->link('extension/marketplace/saveApiKey', 'token=' . $this->session->data['token'], 'SSL');

            $data['register'] = "Marketplace.loadweb(baseUrl + 'index.php?route=extension/marketplace/api&api=api/register')";
            $data['login_action'] = "Marketplace.loadweb(baseUrl + 'index.php?route=extension/marketplace/login&api=api/marketplace/login', $('form').serialize())";

            $data['forgotten'] = 'https://extensions.arastta.pro/index.php?route=account/forgotten';
            $data['api_key_href'] = 'https://extensions.arastta.pro/index.php?route=account/api';

        }

        if ((isset($this->request->get['changeApiKey']) && $this->request->get['changeApiKey'])) {
            $data['changeApiKey'] = true;
        }

        $data['url_data'] = array();
        foreach ($this->request->get as $key => $value) {
            if ($key !== 'route' && $key !== 'token') {
                $data['url_data'][$key] = $value;
            }
        }


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/marketplace.tpl', $data));
    }

    public function api()
    {
        $json = array();

        $this->load->language('extension/marketplace');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['api'])) {
            // Include any URL parameters
            $url_data = array();

            foreach ($this->request->get as $key => $value) {
                if ($key !== 'route' && $key !== 'token' && $key !== 'store_id' && $key !== 'api') {
                    $url_data[$key] = $value;
                }
            }

            if (isset($this->request->get['store']) && !empty($this->request->get['store'])) {
                $this->apiBaseUrl = str_replace(parse_url($this->apiBaseUrl, PHP_URL_HOST), $this->request->get['store'] . '.' . parse_url($this->apiBaseUrl, PHP_URL_HOST), $this->apiBaseUrl);
            }

            $this->load->model('extension/marketplace');

            $addons = $this->model_extension_marketplace->getAddons();
            foreach ($addons as $addon) {
                $data[$addon['product_id']] = $addon['product_version'];
            }

            if (isset($data)) {
                $this->request->post['addons'] = $data;
            }

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $this->apiBaseUrl . 'index.php?route=' . $this->request->get['api'] . ($url_data ? '&' . http_build_query($url_data) : ''));

            if ($this->request->post) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));
            }

            if (isset($this->session->data['cookie'])) {
                curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');
            }

            $json = curl_exec($curl);

            curl_close($curl);
        }

        if (strpos($this->request->get['api'], 'api/register') !== false) {
            if (isset($this->request->get['callback']) && strpos($json, $this->request->get['callback']) !== false) {
                $response = str_replace($this->request->get['callback'], '', $json);
                $response = trim($response, '()');
                $response = json_decode($response);
            } else {
                $response = json_decode($json);
            }

            if (isset($response->api_key)) {
                $this->request->post['api_key'] = $response->api_key;
                $this->saveApiKey();
            }

            if (isset($response->cookie)) {
                $this->session->data['cookie'] = $response->cookie;
            }
        }

        if (!empty($this->request->server['HTTP_X_REQUESTED_WITH'])) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput($json);
        } else {
            $this->response->addHeader('Content-Type: text/plain');
            $this->response->setOutput($json);
        }
    }

    public function uninstall()
    {
        $this->load->model('extension/marketplace');

        $json = array();

        if (empty($this->request->get['product_id'])) {
            return;
        }

        $addon = $this->model_extension_marketplace->getAddon($this->request->get['product_id']);

        if (empty($addon)) {
            $json['error'] = $this->language->get('error_uninstall_already');

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

            return;
        }

        $params = json_decode($addon['params'], true);

        if ($addon['product_type'] === 'translation') {
            $this->load->model('localisation/language');

            $lang = $this->model_localisation_language->getLanguage($params['language_id']);

            if (empty($lang['directory'])) {
                return;
            }

            $this->model_localisation_language->deleteLanguage($params['language_id']);

            $this->filesystem->remove(DIR_ROOT . 'admin/language/'. $lang['directory']);
            $this->filesystem->remove(DIR_ROOT . 'catalog/language/'. $lang['directory']);
        } else {
            // Uninstall extensions
            if (!empty($params['extension_ids'])) {
                $this->load->model('extension/extension');

                foreach ($params['extension_ids'] as $extension_id) {
                    $extension = $this->model_extension_extension->getExtension($extension_id);

                    if (!$extension) {
                        continue;
                    }

                    // Call uninstall method if it exsits
                    $this->load->controller($extension['type'] . '/' . $extension['code'] . '/uninstall');

                    // Delete extension
                    $this->model_extension_extension->deleteExtension($extension_id);
                }
            }

            // Uninstall themes
            if (!empty($params['theme_ids'])) {
                $this->load->model('appearance/theme');

                foreach ($params['theme_ids'] as $theme_id) {
                    $theme = $this->model_appearance_theme->getTheme($theme_id);

                    if (!$theme) {
                        continue;
                    }

                    // Delete theme
                    $this->model_appearance_theme->deleteTheme($theme_id);
                }

            }

            // No files to delete
            if (!empty($addon['files'])) {
                $absolute_paths = array();

                $files = json_decode($addon['files'], true);

                foreach ($files as $file) {
                    $absolute_paths[] = DIR_ROOT . $file;
                }

                // Remove files
                $this->filesystem->remove($absolute_paths);
            }
        }

        // Delete addon
        $this->model_extension_marketplace->deleteAddon($addon['addon_id']);

        $json['success'] = $this->language->get('text_uninstall_success');

        // Refresh modifications
        $this->request->get['extensionInstaller'] = 1;
        $this->load->controller('extension/modification/refresh');
        unset($this->request->get['extensionInstaller']);

        // Clear cache
        $this->cache->remove('addon');
        $this->cache->remove('update');
        $this->cache->remove('version');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/marketplace')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->config->get('api_key')) {
            $this->error['warning'] = $this->language->get('error_api_key');
        }

        if (!extension_loaded('zip')) {
            $this->error['warning'] = $this->language->get('error_zip');
        }

        if (!extension_loaded('xml')) {
            $this->error['warning'] = $this->language->get('error_xml');
        }

        return !$this->error;
    }

    public function saveApiKey()
    {
        if (!empty($this->request->post['api_key'])) {
            $this->load->model('setting/setting');
            $api['api_key'] = $this->request->post['api_key'];
            $this->model_setting_setting->editSetting('api', $api);
        }

        if (basename($this->request->get['route']) === 'saveApiKey') {
            $this->response->redirect($this->url->link('extension/marketplace', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function login()
    {
        $this->api();
        $json = $this->response->get('output');

        $json = str_replace($this->request->get['callback'], '', $json);
        $json = trim($json, '()');
        $json = json_decode($json);

        if (isset($json->cookie)) {
            $this->session->data['cookie'] = $json->cookie;
        }

        if (isset($json->api_key)) {
            $this->request->post['api_key'] = $json->api_key;
            $this->saveApiKey();
        }
    }
}
