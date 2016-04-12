<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSettingSetting extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $config_mail = $this->request->post['config_mail'];

            $config_mail['sendmail_path']       = !isset($config_mail['sendmail_path']) ? '/usr/sbin/sendmail -bs' : $config_mail['sendmail_path'];
            $config_mail['smtp_hostname']       = !isset($config_mail['smtp_hostname']) ? '' : $config_mail['smtp_hostname'];
            $config_mail['smtp_username']       = !isset($config_mail['smtp_username']) ? '' : $config_mail['smtp_username'];
            $config_mail['smtp_password']       = !isset($config_mail['smtp_password']) ? '' : $config_mail['smtp_password'];
            $config_mail['smtp_port']           = !isset($config_mail['smtp_port']) ? '25' : $config_mail['smtp_port'];
            $config_mail['smtp_encryption']     = !isset($config_mail['smtp_encryption']) ? '' : $config_mail['smtp_encryption'];

            $this->request->post['config_mail'] = $config_mail;

            if ($this->request->post['config_pagecache_exclude']) {
                $ex_routes = '';

                foreach (explode("\n", $this->request->post['config_pagecache_exclude']) as $route) {
                    $route = trim($route);

                    if ($route) {
                        $ex_routes .= $route.',';
                    }
                }

                $this->request->post['config_pagecache_exclude'] = trim($ex_routes, ',');
            }

            if ($this->request->post['config_cache_memcache_servers']) {
                $memcache_servers = '';

                foreach (explode("\n", $this->request->post['config_cache_memcache_servers']) as $server) {
                    $server = trim($server);

                    if ($server) {
                        $memcache_servers .= $server.',';
                    }
                }

                $this->request->post['config_cache_memcache_servers'] = trim($memcache_servers, ',');
            }

            $this->model_setting_setting->editSetting('config', $this->request->post);

            if ($this->config->get('config_currency_auto')) {
                $this->load->model('localisation/currency');

                $this->model_localisation_currency->refresh();
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                 $this->response->redirect($this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                 $this->response->redirect($this->url->link('setting/store/add', 'token=' . $this->session->data['token'], 'SSL'));
            }
            
            $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data = $this->language->all();
        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_google_captcha'] = $this->language->get('text_google_captcha');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['help_geocode'] = $this->language->get('help_geocode');
        $data['help_google_captcha'] = $this->language->get('help_google_captcha');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_google'] = $this->language->get('tab_google');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['owner'])) {
            $data['error_owner'] = $this->error['owner'];
        } else {
            $data['error_owner'] = '';
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = '';
        }

        if (isset($this->error['country'])) {
            $data['error_country'] = $this->error['country'];
        } else {
            $data['error_country'] = '';
        }

        if (isset($this->error['zone'])) {
            $data['error_zone'] = $this->error['zone'];
        } else {
            $data['error_zone'] = '';
        }
    
        if (isset($this->error['customer_group_display'])) {
            $data['error_customer_group_display'] = $this->error['customer_group_display'];
        } else {
            $data['error_customer_group_display'] = '';
        }
        
        if (isset($this->error['login_attempts'])) {
            $data['error_login_attempts'] = $this->error['login_attempts'];
        } else {
            $data['error_login_attempts'] = '';
        }    
        
        if (isset($this->error['voucher_min'])) {
            $data['error_voucher_min'] = $this->error['voucher_min'];
        } else {
            $data['error_voucher_min'] = '';
        }

        if (isset($this->error['voucher_max'])) {
            $data['error_voucher_max'] = $this->error['voucher_max'];
        } else {
            $data['error_voucher_max'] = '';
        }

        if (isset($this->error['processing_status'])) {
            $data['error_processing_status'] = $this->error['processing_status'];
        } else {
            $data['error_processing_status'] = '';
        }

        if (isset($this->error['complete_status'])) {
            $data['error_complete_status'] = $this->error['complete_status'];
        } else {
            $data['error_complete_status'] = '';
        }

        if (isset($this->error['image_category'])) {
            $data['error_image_category'] = $this->error['image_category'];
        } else {
            $data['error_image_category'] = '';
        }

        if (isset($this->error['image_thumb'])) {
            $data['error_image_thumb'] = $this->error['image_thumb'];
        } else {
            $data['error_image_thumb'] = '';
        }

        if (isset($this->error['image_popup'])) {
            $data['error_image_popup'] = $this->error['image_popup'];
        } else {
            $data['error_image_popup'] = '';
        }

        if (isset($this->error['image_product'])) {
            $data['error_image_product'] = $this->error['image_product'];
        } else {
            $data['error_image_product'] = '';
        }

        if (isset($this->error['image_additional'])) {
            $data['error_image_additional'] = $this->error['image_additional'];
        } else {
            $data['error_image_additional'] = '';
        }

        if (isset($this->error['image_related'])) {
            $data['error_image_related'] = $this->error['image_related'];
        } else {
            $data['error_image_related'] = '';
        }

        if (isset($this->error['image_compare'])) {
            $data['error_image_compare'] = $this->error['image_compare'];
        } else {
            $data['error_image_compare'] = '';
        }

        if (isset($this->error['image_wishlist'])) {
            $data['error_image_wishlist'] = $this->error['image_wishlist'];
        } else {
            $data['error_image_wishlist'] = '';
        }

        if (isset($this->error['image_cart'])) {
            $data['error_image_cart'] = $this->error['image_cart'];
        } else {
            $data['error_image_cart'] = '';
        }

        if (isset($this->error['image_location'])) {
            $data['error_image_location'] = $this->error['image_location'];
        } else {
            $data['error_image_location'] = '';
        }

        if (isset($this->error['error_filename'])) {
            $data['error_error_filename'] = $this->error['error_filename'];
        } else {
            $data['error_error_filename'] = '';
        }

        if (isset($this->error['product_limit'])) {
            $data['error_product_limit'] = $this->error['product_limit'];
        } else {
            $data['error_product_limit'] = '';
        }

        if (isset($this->error['product_description_length'])) {
            $data['error_product_description_length'] = $this->error['product_description_length'];
        } else {
            $data['error_product_description_length'] = '';
        }

        if (isset($this->error['limit_admin'])) {
            $data['error_limit_admin'] = $this->error['limit_admin'];
        } else {
            $data['error_limit_admin'] = '';
        }

        if (isset($this->error['encryption'])) {
            $data['error_encryption'] = $this->error['encryption'];
        } else {
            $data['error_encryption'] = '';
        }

        if (isset($this->error['cache_lifetime'])) {
            $data['error_cache_lifetime'] = $this->error['cache_lifetime'];
        } else {
            $data['error_cache_lifetime'] = '';
        }

        if (isset($this->error['cache_memcache_servers'])) {
            $data['error_cache_memcache_servers'] = $this->error['cache_memcache_servers'];
        } else {
            $data['error_cache_memcache_servers'] = '';
        }

        if (isset($this->error['cache_redis_server'])) {
            $data['error_cache_redis_server'] = $this->error['cache_redis_server'];
        } else {
            $data['error_cache_redis_server'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_stores'),
            'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['config_name'])) {
            $data['config_name'] = $this->request->post['config_name'];
        } else {
            $data['config_name'] = $this->config->get('config_name');
        }

        if (isset($this->request->post['config_owner'])) {
            $data['config_owner'] = $this->request->post['config_owner'];
        } else {
            $data['config_owner'] = $this->config->get('config_owner');
        }

        if (isset($this->request->post['config_address'])) {
            $data['config_address'] = $this->request->post['config_address'];
        } else {
            $data['config_address'] = $this->config->get('config_address');
        }

        if (isset($this->request->post['config_geocode'])) {
            $data['config_geocode'] = $this->request->post['config_geocode'];
        } else {
            $data['config_geocode'] = $this->config->get('config_geocode');
        }

        if (isset($this->request->post['config_email'])) {
            $data['config_email'] = $this->request->post['config_email'];
        } else {
            $data['config_email'] = $this->config->get('config_email');
        }

        if (isset($this->request->post['config_telephone'])) {
            $data['config_telephone'] = $this->request->post['config_telephone'];
        } else {
            $data['config_telephone'] = $this->config->get('config_telephone');
        }

        if (isset($this->request->post['config_fax'])) {
            $data['config_fax'] = $this->request->post['config_fax'];
        } else {
            $data['config_fax'] = $this->config->get('config_fax');
        }

        if (isset($this->request->post['config_image'])) {
            $data['config_image'] = $this->request->post['config_image'];
        } else {
            $data['config_image'] = $this->config->get('config_image');
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
        } elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['config_open'])) {
            $data['config_open'] = $this->request->post['config_open'];
        } else {
            $data['config_open'] = $this->config->get('config_open');
        }

        if (isset($this->request->post['config_comment'])) {
            $data['config_comment'] = $this->request->post['config_comment'];
        } else {
            $data['config_comment'] = $this->config->get('config_comment');
        }

        $this->load->model('localisation/location');

        $data['locations'] = $this->model_localisation_location->getLocations();

        if (isset($this->request->post['config_location'])) {
            $data['config_location'] = $this->request->post['config_location'];
        } elseif ($this->config->get('config_location')) {
            $data['config_location'] = $this->config->get('config_location');
        } else {
            $data['config_location'] = array();
        }

        if (isset($this->request->post['config_meta_title'])) {
            $data['config_meta_title'] = $this->request->post['config_meta_title'];
        } else {
            $data['config_meta_title'] = $this->config->get('config_meta_title');
        }

        if (isset($this->request->post['config_meta_description'])) {
            $data['config_meta_description'] = $this->request->post['config_meta_description'];
        } else {
            $data['config_meta_description'] = $this->config->get('config_meta_description');
        }

        if (isset($this->request->post['config_meta_keyword'])) {
            $data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
        } else {
            $data['config_meta_keyword'] = $this->config->get('config_meta_keyword');
        }

        if (isset($this->request->post['config_layout_id'])) {
            $data['config_layout_id'] = $this->request->post['config_layout_id'];
        } else {
            $data['config_layout_id'] = $this->config->get('config_layout_id');
        }

        $this->load->model('appearance/layout');

        $data['layouts'] = $this->model_appearance_layout->getLayouts();

        if (isset($this->request->post['config_template'])) {
            $data['config_template'] = $this->request->post['config_template'];
        } else {
            $data['config_template'] = $this->config->get('config_template');
        }

        $data['templates'] = array();

        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $data['templates'][] = basename($directory);
        }

        if (isset($this->request->post['config_admin_template'])) {
            $data['config_admin_template'] = $this->request->post['config_admin_template'];
        } else {
            $data['config_admin_template'] = $this->config->get('config_admin_template', 'basic');
        }

        $data['admin_templates'][] = array(
            'theme' => 'advanced',
            'text'    => $this->language->get('text_error_advanced')
        );

        $admin_templates = glob(DIR_ADMIN . 'view/theme/*', GLOB_ONLYDIR);

        foreach ($admin_templates as $admin_template) {
            $data['admin_templates'][] = array(
                'theme' => basename($admin_template),
                'text'  => $this->language->get('text_error_' . basename($admin_template))
            );
        }

        if (isset($this->request->post['config_admin_template_message'])) {
            $data['config_admin_template_message'] = $this->request->post['config_admin_template_message'];
        } else {
            $data['config_admin_template_message'] = $this->config->get('config_admin_template_message', 'show');
        }

        if (isset($this->request->post['config_country_id'])) {
            $data['config_country_id'] = $this->request->post['config_country_id'];
        } else {
            $data['config_country_id'] = $this->config->get('config_country_id');
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        if (isset($this->request->post['config_zone_id'])) {
            $data['config_zone_id'] = $this->request->post['config_zone_id'];
        } else {
            $data['config_zone_id'] = $this->config->get('config_zone_id');
        }

        if (isset($this->request->post['config_language'])) {
            $data['config_language'] = $this->request->post['config_language'];
        } else {
            $data['config_language'] = $this->config->get('config_language');
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['config_admin_language'])) {
            $data['config_admin_language'] = $this->request->post['config_admin_language'];
        } else {
            $data['config_admin_language'] = $this->config->get('config_admin_language');
        }

        if (isset($this->request->post['config_currency'])) {
            $data['config_currency'] = $this->request->post['config_currency'];
        } else {
            $data['config_currency'] = $this->config->get('config_currency');
        }

        if (isset($this->request->post['config_currency_auto'])) {
            $data['config_currency_auto'] = $this->request->post['config_currency_auto'];
        } else {
            $data['config_currency_auto'] = $this->config->get('config_currency_auto');
        }

        $this->load->model('localisation/currency');

        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        if (isset($this->request->post['config_length_class_id'])) {
            $data['config_length_class_id'] = $this->request->post['config_length_class_id'];
        } else {
            $data['config_length_class_id'] = $this->config->get('config_length_class_id');
        }

        $this->load->model('localisation/length_class');

        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        if (isset($this->request->post['config_weight_class_id'])) {
            $data['config_weight_class_id'] = $this->request->post['config_weight_class_id'];
        } else {
            $data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
        }

        $this->load->model('localisation/weight_class');

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        if (isset($this->request->post['config_product_limit'])) {
            $data['config_product_limit'] = $this->request->post['config_product_limit'];
        } else {
            $data['config_product_limit'] = $this->config->get('config_product_limit');
        }

        if (isset($this->request->post['config_product_description_length'])) {
            $data['config_product_description_length'] = $this->request->post['config_product_description_length'];
        } else {
            $data['config_product_description_length'] = $this->config->get('config_product_description_length');
        }

        if (isset($this->request->post['config_limit_admin'])) {
            $data['config_limit_admin'] = $this->request->post['config_limit_admin'];
        } else {
            $data['config_limit_admin'] = $this->config->get('config_limit_admin');
        }

        $this->load->model('extension/editor');

        $data['editors'] = $this->model_extension_editor->getEditors();

        if (isset($this->request->post['config_text_editor'])) {
            $data['config_text_editor'] = $this->request->post['config_text_editor'];
        } else {
            $data['config_text_editor'] = $this->config->get('config_text_editor');
        }

        if (isset($this->request->post['config_product_count'])) {
            $data['config_product_count'] = $this->request->post['config_product_count'];
        } else {
            $data['config_product_count'] = $this->config->get('config_product_count');
        }

        if (isset($this->request->post['config_review_status'])) {
            $data['config_review_status'] = $this->request->post['config_review_status'];
        } else {
            $data['config_review_status'] = $this->config->get('config_review_status');
        }

        if (isset($this->request->post['config_review_guest'])) {
            $data['config_review_guest'] = $this->request->post['config_review_guest'];
        } else {
            $data['config_review_guest'] = $this->config->get('config_review_guest');
        }

        if (isset($this->request->post['config_review_mail'])) {
            $data['config_review_mail'] = $this->request->post['config_review_mail'];
        } else {
            $data['config_review_mail'] = $this->config->get('config_review_mail');
        }

        if (isset($this->request->post['config_voucher_min'])) {
            $data['config_voucher_min'] = $this->request->post['config_voucher_min'];
        } else {
            $data['config_voucher_min'] = $this->config->get('config_voucher_min');
        }

        if (isset($this->request->post['config_voucher_max'])) {
            $data['config_voucher_max'] = $this->request->post['config_voucher_max'];
        } else {
            $data['config_voucher_max'] = $this->config->get('config_voucher_max');
        }

        if (isset($this->request->post['config_tax'])) {
            $data['config_tax'] = $this->request->post['config_tax'];
        } else {
            $data['config_tax'] = $this->config->get('config_tax');
        }

        if (isset($this->request->post['config_tax_default'])) {
            $data['config_tax_default'] = $this->request->post['config_tax_default'];
        } else {
            $data['config_tax_default'] = $this->config->get('config_tax_default');
        }

        if (isset($this->request->post['config_tax_customer'])) {
            $data['config_tax_customer'] = $this->request->post['config_tax_customer'];
        } else {
            $data['config_tax_customer'] = $this->config->get('config_tax_customer');
        }

        if (isset($this->request->post['config_customer_online'])) {
            $data['config_customer_online'] = $this->request->post['config_customer_online'];
        } else {
            $data['config_customer_online'] = $this->config->get('config_customer_online');
        }

        if (isset($this->request->post['config_customer_group_id'])) {
            $data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
        } else {
            $data['config_customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['config_customer_group_display'])) {
            $data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
        } elseif ($this->config->get('config_customer_group_display')) {
            $data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
        } else {
            $data['config_customer_group_display'] = array();
        }

        if (isset($this->request->post['config_customer_price'])) {
            $data['config_customer_price'] = $this->request->post['config_customer_price'];
        } else {
            $data['config_customer_price'] = $this->config->get('config_customer_price');
        }

        if (isset($this->request->post['config_login_attempts'])) {
            $data['config_login_attempts'] = $this->request->post['config_login_attempts'];
        } elseif ($this->config->has('config_login_attempts')) {
            $data['config_login_attempts'] = $this->config->get('config_login_attempts');
        } else {
            $data['config_login_attempts'] = 5;
        }

        if (isset($this->request->post['config_account_id'])) {
            $data['config_account_id'] = $this->request->post['config_account_id'];
        } else {
            $data['config_account_id'] = $this->config->get('config_account_id');
        }

        $this->load->model('catalog/information');

        $data['informations'] = $this->model_catalog_information->getInformations();

        if (isset($this->request->post['config_account_mail'])) {
            $data['config_account_mail'] = $this->request->post['config_account_mail'];
        } else {
            $data['config_account_mail'] = $this->config->get('config_account_mail');
        }

        if (isset($this->request->post['config_api_id'])) {
            $data['config_api_id'] = $this->request->post['config_api_id'];
        } else {
            $data['config_api_id'] = $this->config->get('config_api_id');
        }

        $this->load->model('user/api');

        $data['apis'] = $this->model_user_api->getApis();

        if (isset($this->request->post['config_cart_weight'])) {
            $data['config_cart_weight'] = $this->request->post['config_cart_weight'];
        } else {
            $data['config_cart_weight'] = $this->config->get('config_cart_weight');
        }

        if (isset($this->request->post['config_checkout_guest'])) {
            $data['config_checkout_guest'] = $this->request->post['config_checkout_guest'];
        } else {
            $data['config_checkout_guest'] = $this->config->get('config_checkout_guest');
        }

        if (isset($this->request->post['config_checkout_id'])) {
            $data['config_checkout_id'] = $this->request->post['config_checkout_id'];
        } else {
            $data['config_checkout_id'] = $this->config->get('config_checkout_id');
        }

        if (isset($this->request->post['config_invoice_prefix'])) {
            $data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
        } elseif ($this->config->get('config_invoice_prefix')) {
            $data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
        } else {
            $data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
        }

        if (isset($this->request->post['config_order_status_id'])) {
            $data['config_order_status_id'] = $this->request->post['config_order_status_id'];
        } else {
            $data['config_order_status_id'] = $this->config->get('config_order_status_id');
        }

        if (isset($this->request->post['config_processing_status'])) {
            $data['config_processing_status'] = $this->request->post['config_processing_status'];
        } elseif ($this->config->get('config_processing_status')) {
            $data['config_processing_status'] = $this->config->get('config_processing_status');
        } else {
            $data['config_processing_status'] = array();
        }

        if (isset($this->request->post['config_complete_status'])) {
            $data['config_complete_status'] = $this->request->post['config_complete_status'];
        } elseif ($this->config->get('config_complete_status')) {
            $data['config_complete_status'] = $this->config->get('config_complete_status');
        } else {
            $data['config_complete_status'] = array();
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['config_order_mail'])) {
            $data['config_order_mail'] = $this->request->post['config_order_mail'];
        } else {
            $data['config_order_mail'] = $this->config->get('config_order_mail');
        }

        if (isset($this->request->post['config_stock_display'])) {
            $data['config_stock_display'] = $this->request->post['config_stock_display'];
        } else {
            $data['config_stock_display'] = $this->config->get('config_stock_display');
        }

        if (isset($this->request->post['config_stock_warning'])) {
            $data['config_stock_warning'] = $this->request->post['config_stock_warning'];
        } else {
            $data['config_stock_warning'] = $this->config->get('config_stock_warning');
        }

        if (isset($this->request->post['config_stock_checkout'])) {
            $data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
        } else {
            $data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
        }
        
        if (isset($this->request->post['config_stock_mail'])) {
            $data['config_stock_mail'] = $this->request->post['config_stock_mail'];
        } elseif ($this->config->has('config_stock_mail')) {
            $data['config_stock_mail'] = $this->config->get('config_stock_mail');
        } else {
            $data['config_stock_mail'] = '';
        }

        if (isset($this->request->post['config_affiliate_auto'])) {
            $data['config_affiliate_approval'] = $this->request->post['config_affiliate_approval'];
        } elseif ($this->config->has('config_affiliate_commission')) {
            $data['config_affiliate_approval'] = $this->config->get('config_affiliate_approval');
        } else {
            $data['config_affiliate_approval'] = '';
        }

        if (isset($this->request->post['config_affiliate_auto'])) {
            $data['config_affiliate_auto'] = $this->request->post['config_affiliate_auto'];
        } elseif ($this->config->has('config_affiliate_auto')) {
            $data['config_affiliate_auto'] = $this->config->get('config_affiliate_auto');
        } else {
            $data['config_affiliate_auto'] = '';
        }

        if (isset($this->request->post['config_affiliate_commission'])) {
            $data['config_affiliate_commission'] = $this->request->post['config_affiliate_commission'];
        } elseif ($this->config->has('config_affiliate_commission')) {
            $data['config_affiliate_commission'] = $this->config->get('config_affiliate_commission');
        } else {
            $data['config_affiliate_commission'] = '5.00';
        }

        if (isset($this->request->post['config_affiliate_mail'])) {
            $data['config_affiliate_mail'] = $this->request->post['config_affiliate_mail'];
        } elseif ($this->config->has('config_affiliate_mail')) {
            $data['config_affiliate_mail'] = $this->config->get('config_affiliate_mail');
        } else {
            $data['config_affiliate_mail'] = '';
        }

        if (isset($this->request->post['config_affiliate_id'])) {
            $data['config_affiliate_id'] = $this->request->post['config_affiliate_id'];
        } else {
            $data['config_affiliate_id'] = $this->config->get('config_affiliate_id');
        }

        if (isset($this->request->post['config_return_id'])) {
            $data['config_return_id'] = $this->request->post['config_return_id'];
        } else {
            $data['config_return_id'] = $this->config->get('config_return_id');
        }

        if (isset($this->request->post['config_return_status_id'])) {
            $data['config_return_status_id'] = $this->request->post['config_return_status_id'];
        } else {
            $data['config_return_status_id'] = $this->config->get('config_return_status_id');
        }
    
    if (isset($this->request->post['config_return_mail'])) {
            $data['config_return_mail'] = $this->request->post['config_return_mail'];
        } elseif ($this->config->has('config_return_mail')) {
            $data['config_return_mail'] = $this->config->get('config_return_mail');
        } else {
            $data['config_return_mail'] = '';
        }

        $this->load->model('localisation/return_status');

        $data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

        if (isset($this->request->post['config_logo'])) {
            $data['config_logo'] = $this->request->post['config_logo'];
        } else {
            $data['config_logo'] = $this->config->get('config_logo');
        }

        if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
            $data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
        } elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
        } else {
            $data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_icon'])) {
            $data['config_icon'] = $this->request->post['config_icon'];
        } else {
            $data['config_icon'] = $this->config->get('config_icon');
        }

        if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
            $data['icon'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
        } elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
        } else {
            $data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_image_category_width'])) {
            $data['config_image_category_width'] = $this->request->post['config_image_category_width'];
        } else {
            $data['config_image_category_width'] = $this->config->get('config_image_category_width');
        }

        if (isset($this->request->post['config_image_category_height'])) {
            $data['config_image_category_height'] = $this->request->post['config_image_category_height'];
        } else {
            $data['config_image_category_height'] = $this->config->get('config_image_category_height');
        }

        if (isset($this->request->post['config_image_thumb_width'])) {
            $data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
        } else {
            $data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
        }

        if (isset($this->request->post['config_image_thumb_height'])) {
            $data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
        } else {
            $data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
        }

        if (isset($this->request->post['config_image_popup_width'])) {
            $data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
        } else {
            $data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
        }

        if (isset($this->request->post['config_image_popup_height'])) {
            $data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
        } else {
            $data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
        }

        if (isset($this->request->post['config_image_product_width'])) {
            $data['config_image_product_width'] = $this->request->post['config_image_product_width'];
        } else {
            $data['config_image_product_width'] = $this->config->get('config_image_product_width');
        }

        if (isset($this->request->post['config_image_product_height'])) {
            $data['config_image_product_height'] = $this->request->post['config_image_product_height'];
        } else {
            $data['config_image_product_height'] = $this->config->get('config_image_product_height');
        }

        if (isset($this->request->post['config_image_additional_width'])) {
            $data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
        } else {
            $data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
        }

        if (isset($this->request->post['config_image_additional_height'])) {
            $data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
        } else {
            $data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
        }

        if (isset($this->request->post['config_image_related_width'])) {
            $data['config_image_related_width'] = $this->request->post['config_image_related_width'];
        } else {
            $data['config_image_related_width'] = $this->config->get('config_image_related_width');
        }

        if (isset($this->request->post['config_image_related_height'])) {
            $data['config_image_related_height'] = $this->request->post['config_image_related_height'];
        } else {
            $data['config_image_related_height'] = $this->config->get('config_image_related_height');
        }

        if (isset($this->request->post['config_image_compare_width'])) {
            $data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
        } else {
            $data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
        }

        if (isset($this->request->post['config_image_compare_height'])) {
            $data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
        } else {
            $data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
        }

        if (isset($this->request->post['config_image_wishlist_width'])) {
            $data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
        } else {
            $data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
        }

        if (isset($this->request->post['config_image_wishlist_height'])) {
            $data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
        } else {
            $data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
        }

        if (isset($this->request->post['config_image_cart_width'])) {
            $data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
        } else {
            $data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
        }

        if (isset($this->request->post['config_image_cart_height'])) {
            $data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
        } else {
            $data['config_image_cart_height'] = $this->config->get('config_image_cart_height');
        }

        if (isset($this->request->post['config_image_location_width'])) {
            $data['config_image_location_width'] = $this->request->post['config_image_location_width'];
        } else {
            $data['config_image_location_width'] = $this->config->get('config_image_location_width');
        }

        if (isset($this->request->post['config_image_location_height'])) {
            $data['config_image_location_height'] = $this->request->post['config_image_location_height'];
        } else {
            $data['config_image_location_height'] = $this->config->get('config_image_location_height');
        }

        if (isset($this->request->post['config_mail'])) {
            $config_mail = $this->request->post['config_mail'];

            $data['config_mail_protocol']       = empty($config_mail['protocol']) ? 'phpmail' : $config_mail['protocol'];
            $data['config_mail_sendmail_path']  = empty($config_mail['sendmail_path']) ? '/usr/sbin/sendmail -bs' : $config_mail['sendmail_path'];
            $data['config_smtp_hostname']       = empty($config_mail['smtp_hostname']) ? '' : $config_mail['smtp_hostname'];
            $data['config_smtp_username']       = empty($config_mail['smtp_username']) ? '' : $config_mail['smtp_username'];
            $data['config_smtp_password']       = empty($config_mail['smtp_password']) ? '' : $config_mail['smtp_password'];
            $data['config_smtp_port']           = empty($config_mail['smtp_port']) ? 25 : $config_mail['smtp_port'];
            $data['config_smtp_encryption']     = empty($config_mail['smtp_encryption']) ? 'none' : $config_mail['smtp_encryption'];
        } elseif ($this->config->get('config_mail')) {
            $config_mail = $this->config->get('config_mail');

            $data['config_mail_protocol']       = $config_mail['protocol'];
            $data['config_mail_sendmail_path']  = $config_mail['sendmail_path'];
            $data['config_smtp_hostname']       = $config_mail['smtp_hostname'];
            $data['config_smtp_username']       = $config_mail['smtp_username'];
            $data['config_smtp_password']       = $config_mail['smtp_password'];
            $data['config_smtp_port']           = $config_mail['smtp_port'];
            $data['config_smtp_encryption']     = $config_mail['smtp_encryption'];
        } else {
            $data['config_mail_protocol'] = 'phpmail';
            $data['config_mail_sendmail_path'] = '/usr/sbin/sendmail -bs';
            $data['config_smtp_hostname'] = '';
            $data['config_smtp_username'] = '';
            $data['config_smtp_password'] = '';
            $data['config_smtp_port'] = 25;
            $data['config_smtp_encryption'] = 'none';
        }

        if (isset($this->request->post['config_mail_alert'])) {
            $data['config_mail_alert'] = $this->request->post['config_mail_alert'];
        } else {
            $data['config_mail_alert'] = $this->config->get('config_mail_alert');
        }

        // SEO
        if (isset($this->request->post['config_seo_url'])) {
            $data['config_seo_url'] = $this->request->post['config_seo_url'];
        } else {
            $data['config_seo_url'] = $this->config->get('config_seo_url');
        }

        if (isset($this->request->post['config_seo_rewrite'])) {
            $data['config_seo_rewrite'] = $this->request->post['config_seo_rewrite'];
        } else {
            $data['config_seo_rewrite'] = $this->config->get('config_seo_rewrite');
        }

        if (isset($this->request->post['config_seo_suffix'])) {
            $data['config_seo_suffix'] = $this->request->post['config_seo_suffix'];
        } else {
            $data['config_seo_suffix'] = $this->config->get('config_seo_suffix');
        }

        if (isset($this->request->post['config_seo_category'])) {
            $data['config_seo_category'] = $this->request->post['config_seo_category'];
        } else {
            $data['config_seo_category'] = $this->config->get('config_seo_category');
        }

        if (isset($this->request->post['config_seo_translate'])) {
            $data['config_seo_translate'] = $this->request->post['config_seo_translate'];
        } else {
            $data['config_seo_translate'] = $this->config->get('config_seo_translate');
        }

        if (isset($this->request->post['config_seo_lang_code'])) {
            $data['config_seo_lang_code'] = $this->request->post['config_seo_lang_code'];
        } else {
            $data['config_seo_lang_code'] = $this->config->get('config_seo_lang_code');
        }

        if (isset($this->request->post['config_seo_canonical'])) {
            $data['config_seo_canonical'] = $this->request->post['config_seo_canonical'];
        } else {
            $data['config_seo_canonical'] = $this->config->get('config_seo_canonical');
        }

        if (isset($this->request->post['config_seo_www_red'])) {
            $data['config_seo_www_red'] = $this->request->post['config_seo_www_red'];
        } else {
            $data['config_seo_www_red'] = $this->config->get('config_seo_www_red');
        }

        if (isset($this->request->post['config_seo_nonseo_red'])) {
            $data['config_seo_nonseo_red'] = $this->request->post['config_seo_nonseo_red'];
        } else {
            $data['config_seo_nonseo_red'] = $this->config->get('config_seo_nonseo_red');
        }

        if (isset($this->request->post['config_meta_title_add'])) {
            $data['config_meta_title_add'] = $this->request->post['config_meta_title_add'];
        } else {
            $data['config_meta_title_add'] = $this->config->get('config_meta_title_add');
        }

        if (isset($this->request->post['config_meta_generator'])) {
            $data['config_meta_generator'] = $this->request->post['config_meta_generator'];
        } else {
            $data['config_meta_generator'] = $this->config->get('config_meta_generator');
        }

        if (isset($this->request->post['config_meta_googlekey'])) {
            $data['config_meta_googlekey'] = $this->request->post['config_meta_googlekey'];
        } else {
            $data['config_meta_googlekey'] = $this->config->get('config_meta_googlekey');
        }

        if (isset($this->request->post['config_meta_alexakey'])) {
            $data['config_meta_alexakey'] = $this->request->post['config_meta_alexakey'];
        } else {
            $data['config_meta_alexakey'] = $this->config->get('config_meta_alexakey');
        }

        $data['config_sitemap_all'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap'));
        $data['config_sitemap_products'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap/products'));
        $data['config_sitemap_categories'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap/categories'));
        $data['config_sitemap_manufacturers'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap/manufacturers'));

        if (isset($this->request->post['config_google_analytics'])) {
            $data['config_google_analytics'] = $this->request->post['config_google_analytics'];
        } else {
            $data['config_google_analytics'] = $this->config->get('config_google_analytics');
        }

        if (isset($this->request->post['config_google_analytics_status'])) {
            $data['config_google_analytics_status'] = $this->request->post['config_google_analytics_status'];
        } else {
            $data['config_google_analytics_status'] = $this->config->get('config_google_analytics_status');
        }

        // Cache
        if (isset($this->request->post['config_cache_storage'])) {
            $data['config_cache_storage'] = $this->request->post['config_cache_storage'];
        } else {
            $data['config_cache_storage'] = $this->config->get('config_cache_storage', 'file');
        }

        if (isset($this->request->post['config_cache_memcache_servers'])) {
            $data['config_cache_memcache_servers'] = $this->request->post['config_cache_memcache_servers'];
        } else {
            $memcache_servers = '';

            foreach (explode(",", $this->config->get('config_cache_memcache_servers', '')) as $server) {
                $server = trim($server);

                if ($server) {
                    $memcache_servers .= $server."\n";
                }
            }

            $data['config_cache_memcache_servers'] = $memcache_servers;
        }

        if (isset($this->request->post['config_cache_redis_server'])) {
            $data['config_cache_redis_server'] = $this->request->post['config_cache_redis_server'];
        } else {
            $data['config_cache_redis_server'] = $this->config->get('config_cache_redis_server', '');
        }

        if (isset($this->request->post['config_cache_lifetime'])) {
            $data['config_cache_lifetime'] = $this->request->post['config_cache_lifetime'];
        } else {
            $data['config_cache_lifetime'] = $this->config->get('config_cache_lifetime', 86400);
        }

        if (isset($this->request->post['config_pagecache'])) {
            $data['config_pagecache'] = $this->request->post['config_pagecache'];
        } else {
            $data['config_pagecache'] = $this->config->get('config_pagecache');
        }

        if (isset($this->request->post['config_cache_clear'])) {
            $data['config_cache_clear'] = $this->request->post['config_cache_clear'];
        } else {
            $data['config_cache_clear'] = $this->config->get('config_cache_clear');
        }

        if (isset($this->request->post['config_pagecache_exclude'])) {
            $data['config_pagecache_exclude'] = $this->request->post['config_pagecache_exclude'];
        } else {
            $ex_routes = '';

            foreach (explode(",", $this->config->get('config_pagecache_exclude', '')) as $route) {
                $route = trim($route);

                if ($route) {
                    $ex_routes .= $route."\n";
                }
            }

            $data['config_pagecache_exclude'] = $ex_routes;
        }

        // Security
        if (isset($this->request->post['config_secure'])) {
            $data['config_secure'] = $this->request->post['config_secure'];
        } else {
            $data['config_secure'] = $this->config->get('config_secure');
        }

        if (isset($this->request->post['config_encryption'])) {
            $data['config_encryption'] = $this->request->post['config_encryption'];
        } else {
            $data['config_encryption'] = $this->config->get('config_encryption');
        }

        if (isset($this->request->post['config_sec_admin_login'])) {
            $data['config_sec_admin_login'] = $this->request->post['config_sec_admin_login'];
        } else {
            $data['config_sec_admin_login'] = $this->config->get('config_sec_admin_login');
        }

        if (isset($this->request->post['config_sec_admin_keyword'])) {
            $data['config_sec_admin_keyword'] = $this->request->post['config_sec_admin_keyword'];
        } else {
            $data['config_sec_admin_keyword'] = $this->config->get('config_sec_admin_keyword');
        }

        if (isset($this->request->post['config_sec_lfi'])) {
            $data['config_sec_lfi'] = $this->request->post['config_sec_lfi'];
        } else {
            $data['config_sec_lfi'] = $this->config->get('config_sec_lfi', array());
        }

        if (isset($this->request->post['config_sec_rfi'])) {
            $data['config_sec_rfi'] = $this->request->post['config_sec_rfi'];
        } else {
            $data['config_sec_rfi'] = $this->config->get('config_sec_rfi', array());
        }

        if (isset($this->request->post['config_sec_sql'])) {
            $data['config_sec_sql'] = $this->request->post['config_sec_sql'];
        } else {
            $data['config_sec_sql'] = $this->config->get('config_sec_sql', array());
        }

        if (isset($this->request->post['config_sec_xss'])) {
            $data['config_sec_xss'] = $this->request->post['config_sec_xss'];
        } else {
            $data['config_sec_xss'] = $this->config->get('config_sec_xss', array());
        }

        if (isset($this->request->post['config_sec_htmlpurifier'])) {
            $data['config_sec_htmlpurifier'] = $this->request->post['config_sec_htmlpurifier'];
        } else {
            $data['config_sec_htmlpurifier'] = $this->config->get('config_sec_htmlpurifier');
        }

        if (isset($this->request->post['config_file_max_size'])) {
            $data['config_file_max_size'] = $this->request->post['config_file_max_size'];
        } else {
            $data['config_file_max_size'] = $this->config->get('config_file_max_size', 300000);
        }

        if (isset($this->request->post['config_file_ext_allowed'])) {
            $data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
        } else {
            $data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
        }

        if (isset($this->request->post['config_file_mime_allowed'])) {
            $data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
        } else {
            $data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
        }

        if (isset($this->request->post['config_google_captcha_public'])) {
            $data['config_google_captcha_public'] = $this->request->post['config_google_captcha_public'];
        } else {
            $data['config_google_captcha_public'] = $this->config->get('config_google_captcha_public');
        }

        if (isset($this->request->post['config_google_captcha_secret'])) {
            $data['config_google_captcha_secret'] = $this->request->post['config_google_captcha_secret'];
        } else {
            $data['config_google_captcha_secret'] = $this->config->get('config_google_captcha_secret');
        }

        if (isset($this->request->post['config_google_captcha_status'])) {
            $data['config_google_captcha_status'] = $this->request->post['config_google_captcha_status'];
        } else {
            $data['config_google_captcha_status'] = $this->config->get('config_google_captcha_status');
        }

        // Fraud
        if (isset($this->request->post['config_fraud_detection'])) {
            $data['config_fraud_detection'] = $this->request->post['config_fraud_detection'];
        } else {
            $data['config_fraud_detection'] = $this->config->get('config_fraud_detection');
        }

        if (isset($this->request->post['config_fraud_key'])) {
            $data['config_fraud_key'] = $this->request->post['config_fraud_key'];
        } else {
            $data['config_fraud_key'] = $this->config->get('config_fraud_key');
        }

        if (isset($this->request->post['config_fraud_score'])) {
            $data['config_fraud_score'] = $this->request->post['config_fraud_score'];
        } else {
            $data['config_fraud_score'] = $this->config->get('config_fraud_score');
        }

        if (isset($this->request->post['config_fraud_status_id'])) {
            $data['config_fraud_status_id'] = $this->request->post['config_fraud_status_id'];
        } else {
            $data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');
        }

        if (isset($this->request->post['config_timezone'])) {
            $data['config_timezone'] = $this->request->post['config_timezone'];
        } else {
            $data['config_timezone'] = $this->config->get('config_timezone', 'UTC');
        }

        $data['timezones'] = $this->getTimezones();

        if (isset($this->request->post['config_shared'])) {
            $data['config_shared'] = $this->request->post['config_shared'];
        } else {
            $data['config_shared'] = $this->config->get('config_shared');
        }

        if (isset($this->request->post['config_robots'])) {
            $data['config_robots'] = $this->request->post['config_robots'];
        } else {
            $data['config_robots'] = $this->config->get('config_robots');
        }

        if (isset($this->request->post['config_maintenance'])) {
            $data['config_maintenance'] = $this->request->post['config_maintenance'];
        } else {
            $data['config_maintenance'] = $this->config->get('config_maintenance');
        }

        if (isset($this->request->post['config_password'])) {
            $data['config_password'] = $this->request->post['config_password'];
        } else {
            $data['config_password'] = $this->config->get('config_password');
        }

        if (isset($this->request->post['config_compression'])) {
            $data['config_compression'] = $this->request->post['config_compression'];
        } else {
            $data['config_compression'] = $this->config->get('config_compression');
        }

        if (isset($this->request->post['config_debug_system'])) {
            $data['config_debug_system'] = $this->request->post['config_debug_system'];
        } else {
            $data['config_debug_system'] = $this->config->get('config_debug_system');
        }

        if (isset($this->request->post['config_error_display'])) {
            $data['config_error_display'] = $this->request->post['config_error_display'];
        } else {
            $data['config_error_display'] = $this->config->get('config_error_display');
        }

        if (isset($this->request->post['config_error_log'])) {
            $data['config_error_log'] = $this->request->post['config_error_log'];
        } else {
            $data['config_error_log'] = $this->config->get('config_error_log');
        }

        if (isset($this->request->post['config_error_filename'])) {
            $data['config_error_filename'] = $this->request->post['config_error_filename'];
        } else {
            $data['config_error_filename'] = $this->config->get('config_error_filename');
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/setting.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['config_name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
            $this->error['owner'] = $this->language->get('error_owner');
        }

        if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if ((utf8_strlen($this->request->post['config_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['config_email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (!$this->request->post['config_meta_title']) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }
        
        if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
            $this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
        }
        
        if ($this->request->post['config_login_attempts'] < 1) {
            $this->error['login_attempts'] = $this->language->get('error_login_attempts');
        }
        
        if (!$this->request->post['config_voucher_min']) {
            $this->error['voucher_min'] = $this->language->get('error_voucher_min');
        }

        if (!$this->request->post['config_voucher_max']) {
            $this->error['voucher_max'] = $this->language->get('error_voucher_max');
        }

        if (!isset($this->request->post['config_processing_status'])) {
            $this->error['processing_status'] = $this->language->get('error_processing_status');
        }

        if (!isset($this->request->post['config_complete_status'])) {
            $this->error['complete_status'] = $this->language->get('error_complete_status');
        }

        if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
            $this->error['image_category'] = $this->language->get('error_image_category');
        }

        if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
            $this->error['image_thumb'] = $this->language->get('error_image_thumb');
        }

        if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
            $this->error['image_popup'] = $this->language->get('error_image_popup');
        }

        if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
            $this->error['image_product'] = $this->language->get('error_image_product');
        }

        if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
            $this->error['image_additional'] = $this->language->get('error_image_additional');
        }

        if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
            $this->error['image_related'] = $this->language->get('error_image_related');
        }

        if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
            $this->error['image_compare'] = $this->language->get('error_image_compare');
        }

        if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
            $this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
        }

        if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
            $this->error['image_cart'] = $this->language->get('error_image_cart');
        }

        if (!$this->request->post['config_image_location_width'] || !$this->request->post['config_image_location_height']) {
            $this->error['image_location'] = $this->language->get('error_image_location');
        }

        if (!$this->request->post['config_error_filename']) {
            $this->error['error_error_filename'] = $this->language->get('error_error_filename');
        } else {
            $this->request->post['config_error_filename'] = str_replace(array('../', '..\\', '..'), '', $this->request->post['config_error_filename']);
         }

        if (!$this->request->post['config_product_limit']) {
            $this->error['product_limit'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_product_description_length']) {
            $this->error['product_description_length'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_limit_admin']) {
            $this->error['limit_admin'] = $this->language->get('error_limit');
        }

        if ((utf8_strlen($this->request->post['config_encryption']) < 3) || (utf8_strlen($this->request->post['config_encryption']) > 32)) {
            $this->error['encryption'] = $this->language->get('error_encryption');
        }

        if (!$this->request->post['config_cache_lifetime']) {
            $this->error['cache_lifetime'] = $this->language->get('error_cache_lifetime');
        }

        if ((($this->request->post['config_cache_storage'] == 'memcache') || ($this->request->post['config_cache_storage'] == 'memcached')) && !$this->request->post['config_cache_memcache_servers']) {
            $this->error['cache_memcache_servers'] = $this->language->get('error_cache_memcache_servers');
        }

        if (($this->request->post['config_cache_storage'] == 'redis') && !$this->request->post['config_cache_redis_server']) {
            $this->error['cache_redis_server'] = $this->language->get('error_cache_redis_server');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function template() {
        if ($this->request->server['HTTPS']) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }

        if (is_file(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
            $this->response->setOutput($server . 'image/templates/' . basename($this->request->get['template']) . '.png');
        } else {
            $this->response->setOutput($server . 'image/no_image.png');
        }
    }

    public function country() {
        $json = array();

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
                'country_id'        => $country_info['country_id'],
                'name'              => $country_info['name'],
                'iso_code_2'        => $country_info['iso_code_2'],
                'iso_code_3'        => $country_info['iso_code_3'],
                'address_format'    => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status'            => $country_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clearCache() {
        $json = array();

        if ($this->cache->clear()) {
            $json['message'] = $this->language->get('text_cache_cleared');
        }else {
            $json['error'] = $this->language->get('error_cache_not_cleared');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTimezones()
    {
        // The list of available timezone groups to use.
        $use_zones = array('Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific');

        // Get the list of time zones from the server.
        $zones = DateTimeZone::listIdentifiers();

        // Build the group lists.
        foreach ($zones as $zone) {
            // Time zones not in a group we will ignore.
            if (strpos($zone, '/') === false) {
                continue;
            }

            // Get the group/locale from the timezone.
            list ($group, $locale) = explode('/', $zone, 2);

            // Only use known groups.
            if (in_array($group, $use_zones)) {
                // Initialize the group if necessary.
                if (!isset($groups[$group])) {
                    $groups[$group] = array();
                }

                // Only add options where a locale exists.
                if (!empty($locale)) {
                    $groups[$group][$zone] = str_replace('_', ' ', $locale);
                }
            }
        }

        // Sort the group lists.
        ksort($groups);

        return $groups;
    }
}
