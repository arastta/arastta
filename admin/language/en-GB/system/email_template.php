<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

// Heading
$_['heading_title']      = 'Email Templates';

// Text
$_['text_success']       = 'Success: You have modified email tempaltes!';
$_['text_list']          = 'Email Templates List';
$_['text_add']           = 'Add Email Template';
$_['text_edit']          = 'Edit Email Template';
$_['text_default']       = 'Default';
$_['text_percent']       = 'Percentage';
$_['text_amount']        = 'Fixed Amount';
$_['text_all']			= '* All *';
	// shortcodes
$_['text_shortcodes']								= 'Templates Placeholder';
$_['text_shortcode_admin_forgotten']				= 'Email {email}<br />IP-Address {ip_address}<br />Storename {store_name}';
$_['text_shortcode_admin_login']					= 'Username {username}<br />Storename {store_name}<br />IP-Address {ip_address}<br />Passwort {password}';
	// affiliate
$_['text_shortcode_affiliate_affiliate_approve']	= 'Storename {store_name}<br />Account-URL {account_href}';
$_['text_shortcode_affiliate_order']				= 'Storename {store_name}<br />Amount {amount}';
$_['text_shortcode_affiliate_add_commission']		= 'Storename {store_name}<br />Commission {commission}<br />Firstname {firstname}<br />Lastname {lastname}';
$_['text_shortcode_affiliate_register']				= 'Storename {store_name}<br />Account-URL {account_href}';
$_['text_shortcode_affiliate_approve']				= 'Storename {store_name}<br />Account-URL {account_href}';
$_['text_shortcode_affiliate_password_reset']		= 'Firstname {firstname}<br />Lastname {lastname}<br />Email {email}<br />Password {password}';
	// contact
$_['text_shortcode_contact_confirmation']			= 'Storename {store_name}<br />Email {email}<br />Message {enquiry}';
	// customer
$_['text_shortcode_customer_credit']				= 'Storename {store_name}';
$_['text_shortcode_customer_voucher']				= 'Storename {store_name}<br />Recievername {name}<br />Amount {amount}<br />Sendername {recip_name}<br />Message {message}<br />Vouchercode {code}';
$_['text_shortcode_customer_approve']				= 'Storename {store_name}<br />Firstname {firstname}<br />Lastname {lastname}<br />Activation-URL {activate_href}';
$_['text_shortcode_customer_password_reset']		= 'Storename {store_name}<br />Firstname {firstname}<br />Lastname {lastname}<br />Email {email}<br />Password {password}';
$_['text_shortcode_customer_register_approval']		= 'Storename {store_name}<br />Firstname {firstname}<br />Lastname {lastname}<br />Account-URL {activate_href}';
$_['text_shortcode_customer_register']				= 'Storename {store_name}<br />Firstname {firstname}<br />Lastname {lastname}<br />Account-URL {account_href}';
	// order
$_['text_shortcode_order_status_voided']			= 'Storename {store_name}<br />Ordernumber {order_id}<br />Link to order {order_href}<br />Orderdate {date}<br />Paymenttype {payment}<br />Shippingtype {shipment}<br />Email {email}<br />Telephone {telephone}<br />IP-Address {ip}<br />Payment Address {payment_address}<br />Shipping Address {shipping_address}<hr /><em>Groups</em>:<br /><b>Comment</b><br />{comment:start} {comment} {comment:stop}<br /><b>Products</b><br />{product:start} {product_name} {product_model} {product_quantity} {product_price} {product_total} {product:stop}<br /><b>Totals</b><br />{total:start} {total_title} {total_value} {total:stop}';

// Column
$_['column_text']        = 'Name';
$_['column_type']        = 'Type';
$_['column_context'] 	 = 'Context';
$_['column_status']      = 'Status';
$_['column_action']      = 'Action';

// Entry
$_['entry_text']         = 'Name';
$_['entry_context']      = 'Context';
$_['entry_name']         = 'Subject';
$_['entry_status']       = 'Status';
$_['entry_type']         = 'Type';
$_['entry_description']  = 'Description';

// Help

// Error
$_['error_permission']   = 'Warning: You do not have permission to modify manufacturers!';
