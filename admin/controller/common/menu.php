<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		$data = $this->language->all();
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_recurring'] = $this->language->get('text_recurring');
		
		$data['dashboard'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		$data['affiliate'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], 'SSL');
		$data['api'] = $this->url->link('user/api', 'token=' . $this->session->data['token'], 'SSL');
		$data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
		$data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL');
		$data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
		$data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL');
		$data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
		$data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL');
		$data['contact'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], 'SSL');
		$data['coupon'] = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL');
		$data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL');
		$data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
		$data['customer_fields'] = $this->url->link('sale/customer_field', 'token=' . $this->session->data['token'], 'SSL');
		$data['customer_group'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL');
		$data['customer_ban_ip'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], 'SSL');
		$data['custom_field'] = $this->url->link('sale/custom_field', 'token=' . $this->session->data['token'], 'SSL');
		$data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL');
		$data['email_template'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'], 'SSL');		
		$data['language_override'] = $this->url->link('system/language_override', 'token=' . $this->session->data['token'], 'SSL');
		$data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
		$data['export_import'] = $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL');
        $data['file_manager'] = $this->url->link('tool/file_manager', 'token=' . $this->session->data['token'], 'SSL');
		$data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
		$data['filter'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
		$data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL');
		$data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
		$data['installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
		$data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL');
		$data['location'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'], 'SSL');
		$data['modification'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');
		$data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL');
		$data['marketing'] = $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], 'SSL');
		$data['marketplace'] = $this->url->link('extension/marketplace', 'token=' . $this->session->data['token'], 'SSL');
		$data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
		$data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
		$data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		$data['paypal_search'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], 'SSL');
		$data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_order'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_tax'] = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_shipping'] = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_return'] = $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_sale_coupon'] = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_product_viewed'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_product_purchased'] = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_activity'] = $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_order'] = $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_reward'] = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_customer_credit'] = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_marketing'] = $this->url->link('report/marketing', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_affiliate'] = $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], 'SSL');
		$data['report_affiliate_activity'] = $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], 'SSL');
		$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
		$data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_action'] = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_reason'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL');
		$data['return_status'] = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		$data['setting'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
		$data['store'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
		$data['stock_status'] = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL');
		$data['tax_class'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['tax_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL');
		$data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
		$data['upload'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL');
		$data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
		$data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL');
		$data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL');
		$data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], 'SSL');
		$data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL');
		$data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], 'SSL');
		$data['recurring'] = $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], 'SSL');
		$data['order_recurring'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], 'SSL');

		#Appearance
		$data['customizer'] = $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'], 'SSL');
		$data['layout'] = $this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL');		
		$data['menu'] = $this->url->link('appearance/menu', 'token=' . $this->session->data['token'], 'SSL');
		
		#Catalogs
		$data['preturn_category'] = $this->user->hasPermission('access','catalog/category');
		$data['preturn_product'] = $this->user->hasPermission('access','catalog/product');
		$data['preturn_recurring'] = $this->user->hasPermission('access','catalog/recurring');
		$data['preturn_filter']	= $this->user->hasPermission('access','catalog/filter');
		$data['preturn_attribute'] = $this->user->hasPermission('access','catalog/attribute');
		$data['preturn_attribute_group'] = $this->user->hasPermission('access','catalog/attribute_group');
		$data['preturn_option']	= $this->user->hasPermission('access','catalog/option');
		$data['preturn_manufacturer'] = $this->user->hasPermission('access','catalog/manufacturer');
		$data['preturn_download'] = $this->user->hasPermission('access','catalog/download');
		$data['preturn_review']	= $this->user->hasPermission('access','catalog/review');
		$data['preturn_information'] = $this->user->hasPermission('access','catalog/information');
		
		#Extensions
		$data['preturn_installer'] = $this->user->hasPermission('access','extension/installer');
		$data['preturn_modification'] = $this->user->hasPermission('access','extension/modification');
		$data['preturn_module'] = $this->user->hasPermission('access','extension/module');
		$data['preturn_shipping'] = $this->user->hasPermission('access','extension/shipping');
		$data['preturn_payment'] = $this->user->hasPermission('access','extension/payment');
		$data['preturn_total'] = $this->user->hasPermission('access','extension/total');
		$data['preturn_feed'] = $this->user->hasPermission('access','extension/feed');
		$data['preturn_marketplace'] = $this->user->hasPermission('access','extension/marketplace');

		#Sales
		$data['preturn_order'] = $this->user->hasPermission('access','sale/order');
		$data['preturn_order_recurring'] = $this->user->hasPermission('access','sale/recurring');
		$data['preturn_return'] = $this->user->hasPermission('access','sale/return');
		$data['preturn_customer'] = $this->user->hasPermission('access','sale/customer');
		$data['preturn_customer_group'] = $this->user->hasPermission('access','sale/customer_group');
		$data['preturn_customer_ban_ip'] = $this->user->hasPermission('access','sale/customer_ban_ip');
		$data['preturn_custom_field'] = $this->user->hasPermission('access','sale/custom_field');
		$data['preturn_voucher'] = $this->user->hasPermission('access','sale/voucher');
		$data['preturn_voucher_theme'] = $this->user->hasPermission('access','sale/voucher_theme');
		$data['preturn_paypal'] = $this->user->hasPermission('access','payment/pp_express');

		#Marketting
		$data['preturn_affiliate'] = $this->user->hasPermission('access','marketing/affiliate');
		$data['preturn_contact'] = $this->user->hasPermission('access','marketing/contact');
		$data['preturn_coupon'] = $this->user->hasPermission('access','marketing/coupon');
		$data['preturn_marketing'] = $this->user->hasPermission('access','marketing/marketing');

		#System
		$data['preturn_setting'] = $this->user->hasPermission('access','setting/store');
		$data['preturn_design_banner'] = $this->user->hasPermission('access','design/banner');
		$data['preturn_user'] = $this->user->hasPermission('access','user/user');
		$data['preturn_user_permission'] = $this->user->hasPermission('access','user/user_permission');
		$data['preturn_user_api'] = $this->user->hasPermission('access','user/api');
		$data['preturn_email_template'] = $this->user->hasPermission('access','system/email_template');		
		$data['preturn_language_override'] = $this->user->hasPermission('access','system/language_override');
		$data['preturn_localisation'] = $this->user->hasPermission('access','localisation/localisation');
		$data['preturn_language'] = $this->user->hasPermission('access','localisation/language');
		$data['preturn_currency'] = $this->user->hasPermission('access','localisation/currency');
		$data['preturn_stock_status'] = $this->user->hasPermission('access','localisation/stock_status');
		$data['preturn_order_status'] = $this->user->hasPermission('access','localisation/order_status');
		$data['preturn_return_status'] = $this->user->hasPermission('access','localisation/return_status');
		$data['preturn_return_action'] = $this->user->hasPermission('access','localisation/return_action');
		$data['preturn_return_reason'] = $this->user->hasPermission('access','localisation/return_reason');
		$data['preturn_country'] = $this->user->hasPermission('access','localisation/country');
		$data['preturn_zone'] = $this->user->hasPermission('access','localisation/zone');
		$data['preturn_geo_zone'] = $this->user->hasPermission('access','localisation/geo_zone');
		$data['preturn_tax_class'] = $this->user->hasPermission('access','localisation/tax_class');
		$data['preturn_tax_rate'] = $this->user->hasPermission('access','localisation/tax_rate');
		$data['preturn_length_class'] = $this->user->hasPermission('access','localisation/length_class');
		$data['preturn_weight_class'] = $this->user->hasPermission('access','localisation/weight_class');

		#Tools
		$data['preturn_backup'] = $this->user->hasPermission('access','tool/backup');
		$data['preturn_error_log'] = $this->user->hasPermission('access','tool/error_log');
		$data['preturn_upload'] = $this->user->hasPermission('access','tool/upload');
        $data['preturn_export_import'] = $this->user->hasPermission('access','tool/export_import');
        $data['preturn_file_manager'] = $this->user->hasPermission('access','tool/file_manager');
		
		#Reports
		$data['preturn_sale_order'] = $this->user->hasPermission('access','report/sale_order');
		$data['preturn_sale_tax'] = $this->user->hasPermission('access','report/sale_tax');
		$data['preturn_sale_shipping'] = $this->user->hasPermission('access','report/sale_shipping');
		$data['preturn_sale_return'] = $this->user->hasPermission('access','report/sale_return');
		$data['preturn_sale_coupon'] = $this->user->hasPermission('access','report/sale_coupon');
		$data['preturn_product_viewed'] = $this->user->hasPermission('access','report/product_viewed');
		$data['preturn_product_purchased'] = $this->user->hasPermission('access','report/product_purchased');
		$data['preturn_customer_online'] = $this->user->hasPermission('access','report/customer_online');
		$data['preturn_customer_activity'] = $this->user->hasPermission('access','report/customer_activity');
		$data['preturn_customer_order'] = $this->user->hasPermission('access','report/customer_order');
		$data['preturn_customer_reward'] = $this->user->hasPermission('access','report/customer_reward');
		$data['preturn_customer_credit'] = $this->user->hasPermission('access','report/customer_credit');
		$data['preturn_marketing'] = $this->user->hasPermission('access','report/marketing');
		$data['preturn_affiliate'] = $this->user->hasPermission('access','report/affiliate');
		$data['preturn_affiliate_activity'] = $this->user->hasPermission('access','report/affiliate_activity');
		
		#Appearance
		$data['preturn_appearance_customizer'] = $this->user->hasPermission('access','appearance/customizer');
		$data['preturn_appearance_layout'] = $this->user->hasPermission('access','appearance/layout');
		$data['preturn_appearance_menu'] = $this->user->hasPermission('access','appearance/menu');

		return $this->load->view('common/menu.tpl', $data);
	}
}