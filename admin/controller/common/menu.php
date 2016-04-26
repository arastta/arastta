<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonMenu extends Controller {

    protected $menu;

    public function index($data) {
        $this->load->language('common/menu');

        $data = $this->language->all($data);

        #Catalog permissions
        $p_return_category = $this->user->hasPermission('access','catalog/category');
        $p_return_product = $this->user->hasPermission('access','catalog/product');
        $p_return_recurring = $this->user->hasPermission('access','catalog/recurring');
        $p_return_filter = $this->user->hasPermission('access','catalog/filter');
        $p_return_attribute = $this->user->hasPermission('access','catalog/attribute');
        $p_return_attribute_group = $this->user->hasPermission('access','catalog/attribute_group');
        $p_return_option = $this->user->hasPermission('access','catalog/option');
        $p_return_manufacturer = $this->user->hasPermission('access','catalog/manufacturer');
        $p_return_download = $this->user->hasPermission('access','catalog/download');
        $p_return_review = $this->user->hasPermission('access','catalog/review');
        $p_return_information = $this->user->hasPermission('access','catalog/information');

        #Sales permissions
        $p_return_order = $this->user->hasPermission('access','sale/order');
        $p_return_invoice = $this->user->hasPermission('access','sale/invoice');
        $p_return_order_recurring = $this->user->hasPermission('access','sale/recurring');
        $p_return_return = $this->user->hasPermission('access','sale/return');
        $p_return_paypal = $this->user->hasPermission('access','payment/pp_express');
        #Customers permissions
        $p_return_customer = $this->user->hasPermission('access','sale/customer');
        $p_return_customer_group = $this->user->hasPermission('access','sale/customer_group');
        $p_return_customer_ban_ip = $this->user->hasPermission('access','sale/customer_ban_ip');
        $p_return_custom_field = $this->user->hasPermission('access','sale/custom_field');

        #Marketing permissions
        $p_return_marketing_affiliate = $this->user->hasPermission('access','marketing/affiliate');
        $p_return_contact = $this->user->hasPermission('access','marketing/contact');
        $p_return_coupon = $this->user->hasPermission('access','marketing/coupon');
        $p_return_marketing_marketing = $this->user->hasPermission('access','marketing/marketing');
        $p_return_voucher = $this->user->hasPermission('access','sale/voucher');
        $p_return_voucher_theme = $this->user->hasPermission('access','sale/voucher_theme');

        #Extensions permissions
        $p_return_installer = $this->user->hasPermission('access','extension/installer');
        $p_return_extension = $this->user->hasPermission('access','extension/extension');
        $p_return_modification = $this->user->hasPermission('access','extension/modification');
        $p_return_module = $this->user->hasPermission('access','extension/module');
        $p_return_shipping = $this->user->hasPermission('access','extension/shipping');
        $p_return_payment = $this->user->hasPermission('access','extension/payment');
        $p_return_total = $this->user->hasPermission('access','extension/total');
        $p_return_feed = $this->user->hasPermission('access','extension/feed');        
        
        #Marketplace permissions
        $p_return_marketplace = $this->user->hasPermission('access','extension/marketplace');
        
        #System permissions
        $p_return_setting = $this->user->hasPermission('access','setting/setting');
        $p_return_setting_store = $this->user->hasPermission('access','setting/store');
        $p_return_design_banner = $this->user->hasPermission('access','design/banner');
        $p_return_user = $this->user->hasPermission('access','user/user');
        $p_return_user_permission = $this->user->hasPermission('access','user/user_permission');
        $p_return_user_api = $this->user->hasPermission('access','user/api');
        $p_return_email_template = $this->user->hasPermission('access','system/email_template');
        $p_return_language_override = $this->user->hasPermission('access','system/language_override');
        $p_return_localisation = $this->user->hasPermission('access','localisation/localisation');
        $p_return_language = $this->user->hasPermission('access','localisation/language');
        $p_return_currency = $this->user->hasPermission('access','localisation/currency');
        $p_return_stock_status = $this->user->hasPermission('access','localisation/stock_status');
        $p_return_order_status = $this->user->hasPermission('access','localisation/order_status');
        $p_return_return_status = $this->user->hasPermission('access','localisation/return_status');
        $p_return_return_action = $this->user->hasPermission('access','localisation/return_action');
        $p_return_return_reason = $this->user->hasPermission('access','localisation/return_reason');
        $p_return_country = $this->user->hasPermission('access','localisation/country');
        $p_return_zone = $this->user->hasPermission('access','localisation/zone');
        $p_return_geo_zone = $this->user->hasPermission('access','localisation/geo_zone');
        $p_return_tax_class = $this->user->hasPermission('access','localisation/tax_class');
        $p_return_tax_rate = $this->user->hasPermission('access','localisation/tax_rate');
        $p_return_length_class = $this->user->hasPermission('access','localisation/length_class');
        $p_return_weight_class = $this->user->hasPermission('access','localisation/weight_class');

        #Tools permissions
        $p_return_backup = $this->user->hasPermission('access','tool/backup');
        $p_return_error_log = $this->user->hasPermission('access','tool/error_log');
        $p_return_upload = $this->user->hasPermission('access','tool/upload');
        $p_return_export_import = $this->user->hasPermission('access','tool/export_import');
        $p_return_file_manager = $this->user->hasPermission('access','tool/file_manager');
        $p_return_system_info = $this->user->hasPermission('access','tool/system_info');

        #Reports permissions
        $p_return_sale_order = $this->user->hasPermission('access','report/sale_order');
        $p_return_sale_tax = $this->user->hasPermission('access','report/sale_tax');
        $p_return_sale_shipping = $this->user->hasPermission('access','report/sale_shipping');
        $p_return_sale_return = $this->user->hasPermission('access','report/sale_return');
        $p_return_sale_coupon = $this->user->hasPermission('access','report/sale_coupon');
        $p_return_product_viewed = $this->user->hasPermission('access','report/product_viewed');
        $p_return_product_purchased = $this->user->hasPermission('access','report/product_purchased');
        $p_return_customer_online = $this->user->hasPermission('access','report/customer_online');
        $p_return_customer_activity = $this->user->hasPermission('access','report/customer_activity');
        $p_return_customer_order = $this->user->hasPermission('access','report/customer_order');
        $p_return_customer_reward = $this->user->hasPermission('access','report/customer_reward');
        $p_return_customer_credit = $this->user->hasPermission('access','report/customer_credit');
        $p_return_report_marketing = $this->user->hasPermission('access','report/marketing');
        $p_return_report_affiliate = $this->user->hasPermission('access','report/affiliate');
        $p_return_report_affiliate_activity = $this->user->hasPermission('access','report/affiliate_activity');

        #Appearance permissions
        $p_return_appearance_customizer = $this->user->hasPermission('access','appearance/customizer');
        $p_return_appearance_layout = $this->user->hasPermission('access','appearance/layout');
        $p_return_appearance_menu = $this->user->hasPermission('access','appearance/menu');
        $p_return_theme = $this->user->hasPermission('access','appearance/theme');

        #Top-level menu
        $this->menu = array(
            'dashboard' => array(
                'text' => $data['text_dashboard'],
                'icon' => 'fa-dashboard',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'position' => 'left'
            ),
            'catalog' => array(
                'text' => $data['text_catalog'],
                'icon' => 'fa-tags',
                'permission' => $p_return_category || $p_return_product || $p_return_recurring || $p_return_filter || $p_return_attribute || $p_return_attribute_group || $p_return_option || $p_return_manufacturer || $p_return_download || $p_return_review || $p_return_information,
                'sort_order' => 2,
                'position' => 'left'
            ),
            'sale' => array(
                'text' => $data['text_sale'],
                'icon' => 'fa-shopping-cart',
                'permission' => $p_return_order || $p_return_order_recurring || $p_return_return || $p_return_paypal,
                'sort_order' => 3,
                'position' => 'left'
            ),
            'customer' => array(
                'text' => $data['text_customers'],
                'icon' => 'fa-user',
                'permission' => $p_return_customer || $p_return_customer_group || $p_return_custom_field || $p_return_customer_ban_ip,
                'sort_order' => 4,
                'position' => 'left'
            ),
            'marketings' => array(
                'text' => $data['text_marketing'],
                'icon' => 'fa-share-alt',
                'permission' => $p_return_marketing_marketing || $p_return_marketing_affiliate || $p_return_coupon || $p_return_voucher || $p_return_voucher_theme || $p_return_contact,
                'sort_order' => 5,
                'position' => 'left'
            ),
            'reports' => array(
                'text' => $data['text_reports'],
                'icon' => 'fa-bar-chart-o',
                'permission' => $p_return_sale_order || $p_return_sale_tax || $p_return_sale_shipping || $p_return_sale_return || $p_return_sale_coupon || $p_return_product_viewed || $p_return_product_purchased || $p_return_customer_online || $p_return_customer_activity || $p_return_customer_order || $p_return_customer_reward || $p_return_customer_credit || $p_return_report_marketing || $p_return_report_affiliate || $p_return_report_affiliate_activity,
                'sort_order' => 6,
                'position' => 'left'
            ),
            'appearance' => array(
                'text' => $data['text_appearance'],
                'icon' => 'fa-desktop',
                'permission' => $p_return_appearance_customizer || $p_return_appearance_layout || $p_return_appearance_menu || $p_return_design_banner || $p_return_theme,
                'sort_order' => 7,
                'position' => 'right'
            ),
            'extension' => array(
                'text' => $data['text_extension'],
                'icon' => 'fa-puzzle-piece',
                'permission' => $p_return_modification || $p_return_extension || $p_return_payment || $p_return_shipping || $p_return_total,
                'sort_order' => 8,
                'position' => 'right'
            ),
            'marketplace' => array(
                'text' => $data['text_marketplace'],
                'icon' => 'fa-plug',
                'permission' => $p_return_marketplace,
                'sort_order' => 9,
                'position' => 'right'
            ),
            'localisation' => array(
                'text' => $data['text_localisation'],
                'icon' => 'fa-flag',
                'permission' => $p_return_localisation || $p_return_language || $p_return_currency || $p_return_stock_status || $p_return_order_status || $p_return_return_status || $p_return_return_action || $p_return_return_reason || $p_return_country || $p_return_zone || $p_return_geo_zone || $p_return_tax_class || $p_return_tax_rate || $p_return_length_class || $p_return_weight_class,
                'sort_order' => 10,
                'position' => 'right'
            ),
            'system' => array(
                'text' => $data['text_system'],
                'icon' => 'fa-cog',
                'class' => 'parent',
                'permission' => $p_return_user || $p_return_user_permission || $p_return_user_api || $p_return_email_template || $p_return_language_override,
                'sort_order' => 11,
                'position' => 'right'
            ),
            'tools' => array(
                'text' => $data['text_tools'],
                'icon' => 'fa-wrench',
                'permission' => $p_return_backup || $p_return_export_import || $p_return_file_manager || $p_return_upload || $p_return_error_log || $p_return_system_info,
                'sort_order' => 12,
                'position' => 'right'
            )
        );

        #Catalog
        $this->menu['catalog']['children'] = array(
            'category' => array(
                'text' => $data['text_category'],
                'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_category
            ),
            'product' => array(
                'text' => $data['text_product'],
                'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_product
            ),
            'recurring' => array(
                'text' => $data['text_recurring'],
                'href' => $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_recurring
            ),
            'filter' => array(
                'text' => $data['text_filter'],
                'href' => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_filter
            ),
            'attribute' => array(
                'text' => $data['text_attribute'],
                'sort_order' => 5,
                'permission' => $p_return_attribute || $p_return_attribute_group,
                'children' => array(
                    'attribute' => array(
                        'text' => $data['text_attribute'],
                        'href' => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 1,
                        'permission' => $p_return_attribute
                    ),
                    'attribute_group' => array(
                        'text' => $data['text_attribute_group'],
                        'href' => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 2,
                        'permission' => $p_return_attribute_group
                    ),
                )
            ),
            'option' => array(
                'text' => $data['text_option'],
                'href' => $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 6,
                'permission' => $p_return_option
            ),
            'manufacturer' => array(
                'text' => $data['text_manufacturer'],
                'href' => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 7,
                'permission' => $p_return_manufacturer
            ),
            'download' => array(
                'text' => $data['text_download'],
                'href' => $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 8,
                'permission' => $p_return_download
            ),
            'review' => array(
                'text' => $data['text_review'],
                'href' => $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 9,
                'permission' => $p_return_review
            ),
            'information' => array(
                'text' => $data['text_information'],
                'href' => $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 10,
                'permission' => $p_return_information
            )
        );

        #Sales
        $this->menu['sale']['children'] = array(
            'order' => array(
                'text' => $data['text_order'],
                'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_order
            ),
            'order_recurring' => array(
                'text' => $data['text_order_recurring'],
                'href' => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_order_recurring
            ),
            'invoice' => array(
                'text' => $data['text_invoice'],
                'href' => $this->url->link('sale/invoice', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_invoice
            ),            
            'return' => array(
                'text' => $data['text_return'],
                'href' => $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_return
            ),
            'paypal_search' => array(
                'text' => $data['text_paypal_search'],
                'href' => $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 5,
                'permission' => $p_return_paypal
            )
        );

        #Customers
        $this->menu['customer']['children'] = array(
            'customer' => array(
                'text' => $data['text_customer'],
                'href' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_customer
            ),
            'customer_group' => array(
                'text' => $data['text_customer_group'],
                'href' => $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_customer_group
            ),
            'custom_fields' => array(
                'text' => $data['text_custom_field'],
                'href' => $this->url->link('sale/custom_field', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_custom_field
            ),
            'customer_ban_ip' => array(
                'text' => $data['text_customer_ban_ip'],
                'href' => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_customer_ban_ip
            )
        );

        #Marketing
        $this->menu['marketings']['children'] = array(
            'campaigns' => array(
                'text' => $data['text_campaign'],
                'href' => $data['marketing'] = $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_marketing_marketing
            ),
            'affiliate' => array(
                'text' => $data['text_affiliate'],
                'href' => $data['affiliate'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_marketing_affiliate
            ),
            'coupon' => array(
                'text' => $data['text_coupon'],
                'href' => $data['coupon'] = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_coupon
            ),
            'gift_vouchers' => array(
                'text' => $data['text_voucher'],
                'sort_order' => 4,
                'permission' => $p_return_voucher || $p_return_voucher_theme,
                'children' => array(
                    'voucher' => array(
                        'text' => $data['text_voucher'],
                        'href' => $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL'),
                        'permission' => $p_return_voucher
                    ),
                    'voucher_theme' => array(
                        'text' => $data['text_voucher_theme'],
                        'href' => $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], 'SSL'),
                        'permission' => $p_return_voucher_theme
                    ),
                ),
            ),
            'contact' => array(
                'text' => $data['text_contact'],
                'href' => $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 5,
                'permission' => $p_return_contact
            )
        );

        #Reports
        $this->menu['reports']['children'] = array(
            'sales' => array(
                'text' => $data['text_sale'],
                'sort_order' => 1,
                'permission' => $p_return_sale_order || $p_return_sale_tax || $p_return_sale_shipping || $p_return_sale_return || $p_return_sale_coupon,
                'children' => array(
                    'orders' => array(
                        'text' => $data['text_report_sale_order'],
                        'href' => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 1,
                        'permission' => $p_return_sale_order
                    ),
                    'tax' => array(
                        'text' => $data['text_report_sale_tax'],
                        'href' => $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 2,
                        'permission' => $p_return_sale_tax
                    ),
                    'shipping' => array(
                        'text' => $data['text_report_sale_shipping'],
                        'href' => $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 3,
                        'permission' => $p_return_sale_shipping
                    ),
                    'returns' => array(
                        'text' => $data['text_report_sale_return'],
                        'href' => $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 4,
                        'permission' => $p_return_sale_return
                    ),
                    'coupons' => array(
                        'text' => $data['text_report_sale_coupon'],
                        'href' => $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 5,
                        'permission' => $p_return_sale_coupon
                    ),
                )
            ),
            'products' => array(
                'text' => $data['text_product'],
                'sort_order' => 2,
                'permission' => $p_return_product_viewed || $p_return_product_purchased,
                'children' => array(
                    'viewed' => array(
                        'text' => $data['text_report_product_viewed'],
                        'href' => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 1,
                        'permission' => $p_return_product_viewed
                    ),
                    'purchased' => array(
                        'text' => $data['text_report_product_purchased'],
                        'href' => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 2,
                        'permission' => $p_return_product_purchased
                    ),
                )
            ),
            'customers' => array(
                'text' => $data['text_customer'],
                'sort_order' => 3,
                'permission' => $p_return_customer_online || $p_return_customer_activity || $p_return_customer_order || $p_return_customer_reward || $p_return_customer_credit,
                'children' => array(
                    'customers_online' => array(
                        'text' => $data['text_report_customer_online'],
                        'href' => $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 1,
                        'permission' => $p_return_customer_online
                    ),
                    'customer_activity' => array(
                        'text' => $data['text_report_customer_activity'],
                        'href' => $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 2,
                        'permission' => $p_return_customer_activity
                    ),
                    'orders' => array(
                        'text' => $data['text_report_customer_order'],
                        'href' => $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 3,
                        'permission' => $p_return_customer_order
                    ),
                    'reward_points' => array(
                        'text' => $data['text_report_customer_reward'],
                        'href' => $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 4,
                        'permission' => $p_return_customer_reward
                    ),
                    'credit' => array(
                        'text' => $data['text_report_customer_credit'],
                        'href' => $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 5,
                        'permission' => $p_return_customer_credit
                    ),
                )
            ),
            'marketing' => array(
                'text' => $data['text_marketing'],
                'sort_order' => 4,
                'permission' => $p_return_report_marketing || $p_return_report_affiliate || $p_return_report_affiliate_activity,
                'children' => array(
                    'marketing' => array(
                        'text' => $data['text_marketing'],
                        'href' => $this->url->link('report/marketing', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 1,
                        'permission' => $p_return_report_marketing
                    ),
                    'affiliates' => array(
                        'text' => $data['text_report_affiliate'],
                        'href' => $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 2,
                        'permission' => $p_return_report_affiliate
                    ),
                    'affiliate_activity' => array(
                        'text' => $data['text_report_affiliate_activity'],
                        'href' => $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 3,
                        'permission' => $p_return_report_affiliate_activity
                    ),
                )
            )
        );

        #Appearance
        $this->menu['appearance']['children'] = array(
            'theme' => array(
                'text' => $data['text_theme'],
                'href' => $this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_theme
            ),
            'customizer' => array(
                'text' => $data['text_customizer'],
                'href' => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_appearance_customizer
            ),
            'layouts' => array(
                'text' => $data['text_layout'],
                'href' => $this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_appearance_layout
            ),
            'menus' => array(
                'text' => $data['text_menu'],
                'href' => $this->url->link('appearance/menu', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_appearance_menu
            ),
            'banners' => array(
                'text' => $data['text_banner'],
                'href' => $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 5,
                'permission' => $p_return_design_banner
            )
        );

        #Extensions
        $this->menu['extension']['children'] = array(
            'modification' => array(
                'text' => $data['text_modification'],
                'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_modification
            ),
            'payment' => array(
                'text' => $data['text_payment'],
                'href' => $this->url->link('extension/extension', 'filter_type=payment&token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_payment
            ),
            'shipping' => array(
                'text' => $data['text_shipping'],
                'href' => $this->url->link('extension/extension', 'filter_type=shipping&token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_shipping
            ),
            'order_total' => array(
                'text' => $data['text_total'],
                'href' => $this->url->link('extension/extension', 'filter_type=total&token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_total
            ),
            'all' => array(
                'text' => $data['text_all'],
                'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 5,
                'permission' => $p_return_extension
            )
        );

        #Marketplace
        $this->menu['marketplace']['children'] = array(
            'theme' => array(
                'text' => $data['text_theme'],
                'href' => $this->url->link('extension/marketplace', 'store=themes&token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_marketplace
            ),
            'extension' => array(
                'text' => $data['text_extension'],
                'href' => $this->url->link('extension/marketplace', 'store=extensions&token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_marketplace
            ),
            'translation' => array(
                'text' => $data['text_translation'],
                'href' => $this->url->link('extension/marketplace', 'store=translations&token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_marketplace
            ),
            'resource' => array(
                'text' => $data['text_resource'],
                'href' => $this->url->link('extension/marketplace', 'store=resources&token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_marketplace
            ),
            'all' => array(
                'text' => $data['text_all'],
                'href' => $this->url->link('extension/marketplace', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 5,
                'permission' => $p_return_marketplace
            )
        );

        #Localisation
        $this->menu['localisation']['children'] = array(
            'languages' => array(
                'text' => $data['text_language'],
                'href' => $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_language
            ),
            'currencies' => array(
                'text' => $data['text_currency'],
                'href' => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_currency
            ),
            'stock_statuses' => array(
                'text' => $data['text_stock_status'],
                'href' => $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_stock_status
            ),
            'order_statuses' => array(
                'text' => $data['text_order_status'],
                'href' => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_order_status
            ),
            'returns' => array(
                'text' => $data['text_return'],
                'sort_order' => 5,
                'permission' => $p_return_return_status || $p_return_return_action || $p_return_return_reason,
                'children' => array(
                    'return_statuses' => array(
                        'text' => $data['text_return_status'],
                        'href' => $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 1,
                        'permission' => $p_return_return_status
                    ),
                    'return_actions' => array(
                        'text' => $data['text_return_action'],
                        'href' => $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 2,
                        'permission' => $p_return_return_action
                    ),
                    'return_reasons' => array(
                        'text' => $data['text_return_reason'],
                        'href' => $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 3,
                        'permission' => $p_return_return_reason
                    ),
                )
            ),
            'countries' => array(
                'text' => $data['text_country'],
                'href' => $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 6,
                'permission' => $p_return_country
            ),
            'zones' => array(
                'text' => $data['text_zone'],
                'href' => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 7,
                'permission' => $p_return_zone
            ),
            'geo_zones' => array(
                'text' => $data['text_geo_zone'],
                'href' => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 8,
                'permission' => $p_return_geo_zone
            ),
            'taxes' => array(
                'text' => $data['text_tax'],
                'sort_order' => 9,
                'permission' => $p_return_tax_rate || $p_return_tax_class,
                'children' => array(
                    'tax_classes' => array(
                        'text' => $data['text_tax_class'],
                        'href' => $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 1,
                        'permission' => $p_return_tax_rate
                    ),
                    'tax_rates' => array(
                        'text' => $data['text_tax_rate'],
                        'href' => $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL'),
                        'sort_order' => 2,
                        'permission' => $p_return_tax_class
                    ),
                )
            ),
            'length_classes' => array(
                'text' => $data['text_length_class'],
                'href' => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 10,
                'permission' => $p_return_length_class
            ),
            'weight_classes' => array(
                'text' => $data['text_weight_class'],
                'href' => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 11,
                'permission' => $p_return_weight_class
            )
        );

        #System
        $this->menu['system']['children'] = array(
            'settings' => array(
                'text' => $data['text_setting'],
                'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_setting
            ),
            'stores' => array(
                'text' => $data['text_store'],
                'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_setting_store
            ),
            'users' => array(
                'text' => $data['text_users'],
                'sort_order' => 3,
                'permission' => $p_return_user || $p_return_user_permission,
                'children' => array(
                    'users' => array(
                        'text' => $data['text_user'],
                        'href' => $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL'),
                        'permission' => $p_return_user
                    ),
                    'user_groups' => array(
                        'text' => $data['text_user_group'],
                        'href' => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL'),
                        'permission' => $p_return_user_permission
                    ),
                )
            ),
            'api' => array(
                'text' => $data['text_api'],
                'href' => $this->url->link('user/api', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_user_api
            ),
            'email_templates' => array(
                'text' => $data['text_email_template'],
                'href' => $this->url->link('system/email_template', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 5,
                'permission' => $p_return_email_template
            ),
            'language_overrides' => array(
                'text' => $data['text_language_override'],
                'href' => $this->url->link('system/language_override', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 6,
                'permission' => $p_return_language_override
            )
        );

        #Tools
        $this->menu['tools']['children'] = array(
            'backup_restore' => array(
                'text' => $data['text_backup'],
                'href' => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 1,
                'permission' => $p_return_backup
            ),
            'export_import' => array(
                'text' => $data['text_export_import'],
                'href' => $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 2,
                'permission' => $p_return_export_import
            ),
            'file_manager' => array(
                'text' => $data['text_file_manager'],
                'href' => $this->url->link('tool/file_manager', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 3,
                'permission' => $p_return_file_manager
            ),
            'uploads' => array(
                'text' => $data['text_upload'],
                'href' => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 4,
                'permission' => $p_return_upload
            ),
            'error_logs' => array(
                'text' => $data['text_error_log'],
                'href' => $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 5,
                'permission' => $p_return_error_log
            ),
            'system_info' => array(
                'text' => $data['text_system_info'],
                'href' => $this->url->link('tool/system_info', 'token=' . $this->session->data['token'], 'SSL'),
                'sort_order' => 6,
                'permission' => $p_return_system_info
            )
        );

        $this->trigger->fire('pre.admin.menu.render', array(&$this));

        foreach ($this->menu as $id=>$item) {
            if (isset($item['children'])) {
                foreach ($item['children'] as $sub_id=>$sub_item) {
                    if (isset($sub_item['children'])) {
                        usort($this->menu[$id]['children'][$sub_id]['children'], array($this, 'compareMenuItems'));
                    }
                }
                usort($this->menu[$id]['children'], array($this, 'compareMenuItems'));
            }
        }
        usort($this->menu, array($this, 'compareMenuItems'));

        $data['menu_items'] = $this->menu;
        $data['left_menu'] = isset($this->session->data['left_menu']) ? $this->session->data['left_menu'] : 'show';
        return $this->load->view('common/menu.tpl', $data);
    }

    protected function compareMenuItems($item1, $item2) {
        if (!isset($item1['sort_order'])) {
            return 1;
        }

        if (!isset($item2['sort_order'])) {
            return -1;
        }

        return ($item1['sort_order'] < $item2['sort_order']) ? -1 : 1;
    }

    public function addMenuItem ($id, $text, $href = '', $parent_id = '', $permission = true, $icon = '', $sort_order = 0, $position = 'left') {
        $new_item = array(
            'text'       => $text,
            'href'       => $href,
            'permission' => $permission,
            'icon'       => $icon,
            'sort_order' => $sort_order,
            'position'   => $position
        );

        if ($parent_id) {
            if (isset($this->menu[$parent_id])) {
                $this->menu[$parent_id]['children'][$id] = $new_item;
            } else {
                foreach ($this->menu as $menu_id=>$item) {
                    if (isset($item['children']) && isset($item['children'][$parent_id])) {
                        $this->menu[$menu_id]['children'][$parent_id]['children'][$id] = $new_item;
                        break;
                    }
                }
            }
        } else {
            $this->menu[$id] = $new_item;
        }
    }
    
    public function removeMenuItem($id, $parent_id = '') {
        if ($parent_id) {
            if (isset($this->menu[$parent_id])) {
                unset($this->menu[$parent_id]['children'][$id]);
            } else {
                foreach ($this->menu as $menu_id=>$item) {
                    if (isset($item['children']) && isset($item['children'][$parent_id])) {
                        unset($this->menu[$menu_id]['children'][$parent_id]['children'][$id]);
                        break;
                    }
                }
            }
        } else {
            unset($this->menu[$id]);
        }
    }
    
    public function position() {
        $json = array();

        if (isset($this->request->get['menu'])) {
            $this->session->data['show_menu'] = ($this->request->get['menu'] == 'right') ? 'right' : '';
        }

        if (isset($this->request->get['position'])) {
            $this->session->data['show_menu_position'] = ($this->request->get['position'] == 'passive') ? 'passive' : '';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
