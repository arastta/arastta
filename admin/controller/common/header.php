<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonHeader extends Controller {
    public function index() {
        $data['title'] = $this->document->getTitle();

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $this->trigger->fire('pre.admin.editor');
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['links'] = $this->document->getLinks();
        $data['style_declarations'] = $this->document->getStyleDeclarations();
        $data['styles'] = $this->document->getStyles();
        $data['script_declarations'] = $this->document->getScriptDeclarations();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG . 'image/' . $this->config->get('config_icon');
        } else {
            $data['icon'] = '';
        }

        $this->load->language('common/header');

        $data = $this->language->all($data, array('text_logged'));
        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new'] = $this->language->get('text_new');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_basic_mode'] = $this->language->get('text_basic_mode');
        $data['text_close'] = $this->language->get('text_close');

        if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
            $data['logged'] = '';

            $data['home'] = $this->url->link('common/dashboard', '', 'SSL');
        } else {
            $data['logged'] = true;

            $data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
            $data['setting'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
            $data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');

            $data['preturn_update'] = $this->user->hasPermission('access','common/update');
            $data['update'] = $this->url->link('common/update', 'token=' . $this->session->data['token'], 'SSL');

            // News Added Menu
            $data['new_category'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'], 'SSL');
            $data['new_customer'] = $this->url->link('sale/customer/add', 'token=' . $this->session->data['token'], 'SSL');
            $data['new_download'] = $this->url->link('catalog/download/add', 'token=' . $this->session->data['token'], 'SSL');
            $data['new_manufacturer'] = $this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'], 'SSL');
            $data['new_product'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'], 'SSL');
                        
            // Orders
            $this->load->model('sale/order');

            // Processing Orders
            $data['order_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status'))));
            $data['order_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_processing_status')), 'SSL');

            // Complete Orders
            $data['complete_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status'))));
            $data['complete_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_complete_status')), 'SSL');

            // Returns
            $this->load->model('sale/return');

            $return_total = $this->model_sale_return->getTotalReturns(array('filter_return_status_id' => $this->config->get('config_return_status_id')));

            $data['return_total'] = $return_total;

            $data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');

            // Customers
            $this->load->model('report/customer');

            $data['online_total'] = $this->model_report_customer->getTotalCustomersOnline();

            $data['online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');

            $this->load->model('sale/customer');

            $customer_total = $this->model_sale_customer->getTotalCustomers(array('filter_approved' => false));

            $data['customer_total'] = $customer_total;
            $data['customer_approval'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0', 'SSL');

            // Products
            $this->load->model('catalog/product');

            $product_total = $this->model_catalog_product->getTotalProducts(array('filter_quantity' => 0));

            $data['product_total'] = $product_total;

            $data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&filter_quantity=0', 'SSL');

            // Reviews
            $this->load->model('catalog/review');

            $review_total = $this->model_catalog_review->getTotalReviews(array('filter_status' => false));

            $data['review_total'] = $review_total;

            $data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&filter_status=0', 'SSL');

            // Affliate
            $this->load->model('marketing/affiliate');

            $affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates(array('filter_approved' => false));

            $data['affiliate_total'] = $affiliate_total;
            $data['affiliate_approval'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'] . '&filter_approved=1', 'SSL');

            $data['alerts'] = $customer_total + $product_total + $review_total + $return_total + $affiliate_total;

            // Online Stores
            $data['stores'] = array();

            $data['stores'][] = array(
                'name' => $this->config->get('config_name'),
                'href' => ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG
            );

            $processing_total = $this->model_sale_order->getTotalOrdersByProcessingStatus();

            $this->load->model('common/update');

            $data['alert_order'] = $return_total + $processing_total;
            $data['alert_customer'] = $customer_total;
            $data['alert_product'] = $product_total + $review_total + $affiliate_total;
            $data['alert_update'] = $this->model_common_update->countUpdates();

            // Languages
            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            if (count($languages) > 1) {
                $extra_link = '';

                $route = !empty($this->request->get['route']) ? $this->request->get['route'] : 'common/dashboard';

                if (!empty($this->request->get)) {
                    foreach ($this->request->get as $name => $value) {
                        $skip_vars = array('route', 'token', 'lang');

                        if (in_array($name, $skip_vars)) {
                            continue;
                        }

                        $extra_link .= '&' . $name . '=' . $value;
                    }
                }

                foreach ($languages as $language) {
                    $data['languages'][] = array(
                        'name' => $language['name'],
                        'image' => $language['image'],
                        'link' => $this->url->link($route, 'lang=' . $language['code'] . $extra_link . '&token=' . $this->session->data['token'], 'SSL')
                    );
                }
            } else {
                $data['languages'] = '';
            }

            $this->load->language('user/user');

            $data['entry_theme'] = $this->language->get('entry_theme');

            // Themes
            $data['themes'][] = array(
                'theme'       => 'advanced',
                'text'       => $this->language->get('text_theme_advanced'),
                'link'       => $this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'].'&theme=advanced', 'SSL')
            );

            $templates = glob(DIR_ADMIN . 'view/theme/*', GLOB_ONLYDIR);

            foreach ($templates as $template) {
                $data['themes'][] = array(
                    'theme'       => basename($template),
                    'text'       => $this->language->get('text_theme_' . basename($template)),
                    'link'       => $this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'].'&theme=' . basename($template), 'SSL')
                );
            }
            
            $data['theme'] = $this->session->data['theme'];

            $this->load->language('common/menu');

            $this->load->model('user/user');

            $this->load->model('tool/image');

            $user_info = $this->model_user_user->getUser($this->user->getId());

            if ($user_info) {
                if (!empty($user_info['firstname'])) {
                    $data['name'] = $user_info['firstname'] . ' ' . $user_info['lastname'];
                } else {
                    $data['name'] = $user_info['email'];
                }

                $data['user_group'] = $user_info['user_group'] ;

                if (is_file(DIR_IMAGE . $user_info['image'])) {
                    $data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
                } else {
                    $data['image'] = 'https://www.gravatar.com/avatar/' . md5(strtolower($user_info['email'])).'?size=45&d=mm';
                }
                
                $user_params =  json_decode($user_info['params'], true);
                
                $data['basic_mode_message'] = isset($user_params['basic_mode_message']) ? $user_params['basic_mode_message'] : 'show';
            } else {
                $data['name'] = '';
                $data['image'] = '';
                $data['basic_mode_message'] = 'hide';
            }

            $data['url_user'] = $this->url->link('user/user/edit', 'user_id='.$this->user->getId().'&token=' . $this->session->data['token'], 'SSL');

            $this->load->model('setting/store');

            $results = $this->model_setting_store->getStores();

            foreach ($results as $result) {
                $data['stores'][] = array(
                    'name' => $result['name'],
                    'href' => $result['url']
                );
            }

            $data['show_menu'] = (isset($this->session->data['show_menu']) && $this->session->data['show_menu'] == 'right') ? 'right' : 'left';
        }

        $data['sitename'] = (strlen($this->config->get('config_name')) > 14) ? substr($this->config->get('config_name'),0,14) . "..." : $this->config->get('config_name');
        
        $data['site_url'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG;

        $data['bootstrap_select_lang'] = '';

        $lang_tag = str_replace('-', '_', $this->config->get('config_language_dir'));

        if (is_file(DIR_ADMIN . 'view/javascript/bootstrap-select/js/i18n/defaults-' . $lang_tag . '.min.js')) {
            $data['bootstrap_select_lang'] = $lang_tag;
        }

        $data['langauge_dir'] = $this->config->get('config_language_dir');

        $moment_special = array(
            'en' => 'en-gb',
            'br' => 'pt-br',
            'zh' => 'zh-cn',
            'tw' => 'zh-tw',
            'no' => 'nb'
        );

        $data['moment_lang'] = $this->session->data['admin_language'];

        if (array_key_exists($this->session->data['admin_language'], $moment_special)) {
            $data['moment_lang'] = $moment_special[$this->session->data['admin_language']];
        }
        
        $data['search'] = $this->load->controller('search/search');

        return $this->load->view('common/header.tpl', $data);
    }
}
