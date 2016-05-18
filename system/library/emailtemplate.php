<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class Emailtemplate
{

    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    // Mail Subject
    public function getSubject($type, $template_id, $data)
    {
        $template = $this->getEmailTemplate($template_id);

        $findFunctionName = 'get' . ucwords($type) . 'Find';
        $replaceFunctionName = 'get' . ucwords($type) . 'Replace';

        $find = array();

        if (method_exists($this, $findFunctionName)) {
            $find = $this->$findFunctionName();
        }

        $this->trigger->fire('post.emailtemplate.subject.shortcode', array(&$find));

        $replace = array();

        if (method_exists($this, $replaceFunctionName)) {
            $replace = $this->$replaceFunctionName($data);
        }

        $this->trigger->fire('post.emailtemplate.subject.replace', array(&$replace, &$data));

        if (!empty($template['name'])) {
            $subject = trim(str_replace($find, $replace, $template['name']));
        } else {
            $subject = $this->getDefaultSubject($type, $template_id, $data);
        }

        return $subject;
    }

    // Mail Message
    public function getMessage($type, $template_id, $data)
    {
        $template = $this->getEmailTemplate($template_id);
        
        $findFunctionName = 'get' . ucwords($type) . 'Find';
        $replaceFunctionName = 'get' . ucwords($type) . 'Replace';

        $find = array();

        if (method_exists($this, $findFunctionName)) {
            $find = $this->$findFunctionName();
        }

        $this->trigger->fire('post.emailtemplate.message.shortcode', array(&$find));

        $replace = array();

        if (method_exists($this, $replaceFunctionName)) {
            $replace = $this->$replaceFunctionName($data);
        }

        $this->trigger->fire('post.emailtemplate.message.replace', array(&$replace, &$data));

        if (!empty($template['description'])) {
            if (ucwords($type) == 'OrderAll') {

                preg_match('/{product:start}(.*){product:stop}/Uis', $template['description'], $template_product);
                if (!empty($template_product[1])) {
                    $template['description'] = str_replace($template_product[1], '', $template['description']);
                }

                preg_match('/{voucher:start}(.*){voucher:stop}/Uis', $template['description'], $template_voucher);
                if (!empty($template_voucher[1])) {
                    $template['description'] = str_replace($template_voucher[1], '', $template['description']);
                }

                preg_match('/{comment:start}(.*){comment:stop}/Uis', $template['description'], $template_comment);
                if (!empty($template_comment[1])) {
                    $template['description'] = str_replace($template_comment[1], '', $template['description']);
                }

                preg_match('/{tax:start}(.*){tax:stop}/Uis', $template['description'], $template_tax);
                if (!empty($template_tax[1])) {
                    $template['description'] = str_replace($template_tax[1], '', $template['description']);
                }

                preg_match('/{total:start}(.*){total:stop}/Uis', $template['description'], $template_total);
                if (!empty($template_total[1])) {
                    $template['description'] = str_replace($template_total[1], '', $template['description']);
                }
            }

            $message = trim(str_replace($find, $replace, $template['description']));
        } else {
            $message = $this->getDefaultMessage($type, $template_id, $data);
        }

        $data['title'] = $this->getSubject($type, $template_id, $data);
        $data['message'] = $message;
        $data['site_url'] = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;

        if (Client::isCatalog()) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/default.tpl')) {
                $message = $this->load->view($this->config->get('config_template') . '/template/mail/default.tpl', $data);
            } else {
                $message = $this->load->view('default/template/mail/default.tpl', $data);
            }
        } else {
            $message = $this->load->view('mail/default.tpl', $data);
        }
        
        return $message;
    }

    //Mail Text
    public function getText($type, $template_id, $data)
    {
        $findName = 'get' . ucwords($type) . 'Text';

        return $this->$findName($template_id, $data);
    }

    // Mail Template
    public function getEmailTemplate($email_template)
    {
        $item = explode("_", $email_template);

        $query  = $this->db->query("SELECT * FROM " . DB_PREFIX . "email AS e LEFT JOIN " . DB_PREFIX . "email_description AS ed ON ed.email_id = e.id WHERE e.type = '{$item[0]}' AND e.text_id = '{$item[1]}' AND ed.language_id = '{$this->config->get('config_language_id')}'");

        if (!$query->num_rows) {
            $query  = $this->db->query("SELECT * FROM " . DB_PREFIX . "email AS e LEFT JOIN " . DB_PREFIX . "email_description AS ed ON ed.email_id = e.id WHERE e.type = '{$item[0]}' AND e.text_id = '{$item[1]}'");
        }

        foreach ($query->rows as $result) {
            $email_template_data = array(
                'text'         => $result['text'],
                'text_id'      => $result['text_id'],
                'type'         => $result['type'],
                'context'      => $result['context'],
                'name'         => $result['name'],
                'description'  => $result['description'],
                'status'       => $result['status']
            );
        }

        return $email_template_data;
    }

    // Store Logo
    public function storeLogo()
    {
        $store_url = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;
        $store_name = $this->config->get('config_name');
        $store_logo = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');

        return '<a href="' . $store_url . '" title="' . $store_name . '"><img src="' . $store_logo . '"></a>';
    }

    // Admin Login
    public function getLoginFind()
    {
        $result = array( '{store_logo}', '{username}', '{store_name}', '{ip_address}' );
        
        return $result;
    }

    public function getLoginReplace($data)
    {
        $result = array(
            'store_logo' => $this->storeLogo(),
            'username'   => $data['username'],
            'store_name' => $data['store_name'],
            'ip_address' => $data['ip_address']
        );

        return $result;
    }
    
    // Affilate
    public function getAffiliateFind()
    {
        $result = array( '{store_logo}', '{firstname}', '{lastname}', '{date}', '{store_name}', '{description}', '{order_id}', '{amount}', '{total}', '{email}','{password}', '{affiliate_code}', '{account_href}' );
        
        return $result;
    }
    
    public function getAffiliateReplace($data)
    {
        $result = array(
            'store_logo'     => $this->storeLogo(),
            'firstname'      => (!empty($data['firstname'])) ? $data['firstname'] : '',
            'lastname'       => (!empty($data['lastname'])) ? $data['lastname'] : '',
            'date'           => date($this->language->get('date_format_short'), strtotime(date("Y-m-d H:i:s"))),
            'store_name'     => $this->config->get('config_name'),
            'description'    => (!empty($data['description'])) ? nl2br($data['description']) : '',
            'order_id'       => (!empty($data['order_id'])) ? $data['order_id'] : '',
            'amount'         => (!empty($data['amount'])) ? $data['amount'] : '',
            'total'          => (!empty($data['total'])) ? $data['total'] : '',
            'email'          => (!empty($data['email'])) ? $data['email'] : '',
            'password'       => (!empty($data['password'])) ? $data['password'] : '',
            'affiliate_code' => (!empty($data['code'])) ? $data['code'] : '',
            'account_href'   => $this->url->link('affiliate/login', '', 'SSL')
        );

        return $result;
    }
    
    // Customer
    public function getCustomerFind()
    {
        $result = array( '{store_logo}', '{firstname}', '{lastname}', '{credit}', '{received_credit}', '{total_credit}', '{date}', '{store_name}', '{email}', '{password}', '{account_href}', '{activate_href}' );

        return $result;
    }

    public function getCustomerReplace($data)
    {
        $result = array(
            'store_logo'     => $this->storeLogo(),
            'firstname'      => $data['firstname'],
            'lastname'       => $data['lastname'],
            'credit'         => isset($data['credit']) ? $data['credit'] : '0',
            'received_credit'=> isset($data['received_credit']) ? $data['received_credit'] : '0',
            'total_credit'   => isset($data['total_credit']) ? $data['total_credit'] : '0',
            'date'           => date($this->language->get('date_format_short'), strtotime(date("Y-m-d H:i:s"))),
            'store_name'     => $this->config->get('config_name'),
            'email'          => $data['email'],
            'password'       => $data['password'],
            'account_href'   => $this->url->link('account/login', '', 'SSL'),
            'activate_href'  => (!empty($data['confirm_code'])) ? $this->url->link('account/activate', 'passkey=' . $data['confirm_code'], 'SSL') : ''
        );

        return $result;
    }
    
    // Contact ( Information )
    public function getContactFind()
    {
        $result = array( '{store_logo}', '{name}', '{email}', '{store_name}', '{enquiry}' );
        
        return $result;
    }

    public function getContactReplace($data)
    {
        $result = array(
            'store_logo' => $this->storeLogo(),
            'name'       => (!empty($data['name'])) ? $data['name'] : '',
            'email'      => (!empty($data['email'])) ? $data['email'] : '',
            'store_name' => $this->config->get('config_name'),
            'enquiry'    => (!empty($data['enquiry'])) ? $data['enquiry'] : ''
        );

        return $result;
    }

    // Order
    public function getOrderAllFind()
    {
        $result = array (
            '{store_logo}', '{firstname}', '{lastname}', '{delivery_address}', '{shipping_address}', '{payment_address}', '{order_date}', '{product:start}', '{product:stop}',
            '{total:start}', '{total:stop}', '{voucher:start}', '{voucher:stop}', '{special}', '{date}', '{payment}', '{shipment}', '{order_id}', '{total}', '{invoice_number}',
            '{order_href}', '{store_url}', '{status_name}', '{store_name}', '{ip}', '{comment:start}', '{comment:stop}', '{comment}', '{sub_total}', '{shipping_cost}',
            '{client_comment}', '{tax:start}', '{tax:stop}', '{tax_amount}', '{email}', '{telephone}'
        );

        return $result;
    }

    public function getOrderAllReplace($data)
    {
        $emailTemplate = $this->getEmailTemplate($data['template_id']);

        foreach ($data as $dataKey => $dataValue) {
            $$dataKey = $dataValue;
        }

        // Special
       /* $special = array();

        if (sizeof($email_template['special']) <> 0) {
         //   $special = $this->_prepareProductSpecial((int)$order_info['customer_group_id'], $email_template['special']);
        }
        */

        // Products
        preg_match('/{product:start}(.*){product:stop}/Uis', $emailTemplate['description'], $template_product);


        if (sizeof($template_product) > 0) {
            $getProducts = $this->getOrderProducts($order_info['order_id']);

            $products = $this->getProductsTemplate($order_info, $getProducts, $template_product);
            $emailTemplate['description'] = str_replace($template_product[1], '', $emailTemplate['description']);
        } else {
            $products = array();
        }

        // Vouchers
        preg_match('/{voucher:start}(.*){voucher:stop}/Uis', $emailTemplate['description'], $template_voucher);

        if (sizeof($template_voucher) > 0) {
            $getVouchers = $this->getOrderVouchers($order_info['order_id']);

            $vouchers = $this->getVoucherTemplate($order_info, $getVouchers, $template_voucher);
            $emailTemplate['description'] = str_replace($template_voucher[1], '', $emailTemplate['description']);
        } else {
            $vouchers = array();
        }

        // Comment
        preg_match('/{comment:start}(.*){comment:stop}/Uis', $emailTemplate['description'], $template_comment);

        if (sizeof($template_comment) > 0) {
            if (empty($comment)) {
                $comment[0] = '';
            } else {
                $comment = $this->getCommentTemplate($comment, $template_comment);
            }
            $emailTemplate['description'] = str_replace($template_comment[1], '', $emailTemplate['description']);
        } else {
            $comment[0] = '';
        }

        // Tax
        preg_match('/{tax:start}(.*){tax:stop}/Uis', $emailTemplate['description'], $template_tax);

        if (sizeof($template_tax) > 0) {
            $taxes = $this->getTaxTemplate($totals, $template_tax);
            $emailTemplate['description'] = str_replace($template_tax[1], '', $emailTemplate['description']);
        } else {
            $taxes = array();
        }

        // Total
        preg_match('/{total:start}(.*){total:stop}/Uis', $emailTemplate['description'], $template_total);

        $order_total = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_info['order_id'] . "'");
        $getTotal = $order_total->rows;

        if (sizeof($template_total) > 0) {
            $tempTotals = $this->getTotalTemplate($getTotal, $template_total, $order_info);
            $emailTemplate['description'] = str_replace($template_total[1], '', $emailTemplate['description']);
        } else {
            $tempTotals = array();
        }

        $result = array(
            'store_logo'      => $this->storeLogo(),
            'firstname'       => $order_info['firstname'],
            'lastname'        => $order_info['lastname'],
            'delivery_address'=> $address,
            'shipping_address'=> $address,
            'payment_address' => $payment_address,
            'order_date'      => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
            'product:start'   => implode("", $products),
            'product:stop'    => '',
            'total:start'     => implode("", $tempTotals),
            'total:stop'      => '',
            'voucher:start'   => implode("", $vouchers),
            'voucher:stop'    => '',
            'special'         => (sizeof($special) <> 0) ? implode("<br />", $special) : '',
            'date'            => date($this->language->get('date_format_short'), strtotime(date("Y-m-d H:i:s"))),
            'payment'         => $order_info['payment_method'],
            'shipment'        => $order_info['shipping_method'],
            'order_id'        => $order_info['order_id'],
            'total'           => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']),
            'invoice_number'  => $order_info['invoice_prefix'] . $invoice_no,
            'order_href'      => '<a href="' . $order_href . '" target="_blank">' . $order_href . '</a>',
            'store_url'       => $order_info['store_url'],
            'status_name'     => $order_status,
            'store_name'      => $order_info['store_name'],
            'ip'              => $order_info['ip'],
            'comment:start'   => implode("", $comment),
            'comment:stop'    => '',
            'comment'         => implode("", $comment),
            'sub_total'       => $totals['sub_total'][0]['text'],
            'shipping_cost'   => (isset($totals['shipping'][0]['text'])) ? $totals['shipping'][0]['text'] : '',
            'client_comment'  => $order_info['comment'],
            'tax:start'       => implode("", $taxes),
            'tax:stop'        => '',
            'tax_amount'      => $this->currency->format($tax_amount, $order_info['currency_code'], $order_info['currency_value']),
            'email'           => $order_info['email'],
            'telephone'       => $order_info['telephone']
        );

        return $result;
    }

    // Invoice
    public function getInvoiceFind()
    {
        $result = array(
            '{store_logo}','{invoice_id}', '{invoice_date}', '{invoice_no}', '{invoice_prefix}', '{order_id}',
            '{store_name}', '{customer}', '{email}', '{telephone}', '{fax}', '{comment}', '{ip}',
            '{date_added}', '{date_modified}'
        );

        return $result;
    }

    public function getInvoiceReplace($data)
    {
        $result = array(
            'store_logo' => $this->storeLogo(),
            'invoice_id' => $data['invoice_id'],
            'invoice_date' => $data['invoice_date'],
            'invoice_no' => $data['invoice_no'],
            'invoice_prefix' => $data['invoice_prefix'],
            'order_id' => $data['order_id'],
            'store_name' => $data['store_name'],
            'customer' => $data['customer'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'fax' => $data['fax'],
            'comment' => $data['comment'],
            'ip' => $data['ip'],
            'date_added' => $data['date_added'],
            'date_modified' => $data['date_modified']
        );

        return $result;
    }

    //Return
    public function getReturnFind()
    {
        $result = array( '{store_logo}', '{store_name}', '{order_id}', '{date_ordered}', '{firstname}', '{lastname}', '{email}', '{telephone}', '{product}', '{model}', '{quantity}', '{return_reason}', '{opened}', '{comment}' );
        
        return $result;
    }
  
    public function getReturnReplace($data)
    {
        $result = array(
            'store_logo' => $this->storeLogo(),
            'store_name' => $this->config->get('config_name'),
            'order_id' => $data['order_id'],
            'date_ordered' => $data['date_ordered'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'product' => $data['product'],
            'model' => $data['model'],
            'quantity' => $data['quantity'],
            'return_reason' => $data['return_reason'],
            'opened' => $data['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
            'comment' => nl2br($data['comment'])
        );
    
        return $result;
    }

    // Review
    public function getReviewFind()
    {
        $result = array( '{store_logo}', '{author}', '{review}', '{date}', '{rating}', '{product}' );
        
        return $result;
    }

    public function getReviewReplace($data)
    {
        $result = array(
            'store_logo' => $this->storeLogo(),
            'author'   => $data['name'],
            'review'   => $data['text'],
            'date'     => date($this->language->get('date_format_short'), time()),
            'rating'   => $data['rating'],
            'product'  => $data['product']
        );

        return $result;
    }
    
    // Stock
    public function getStockFind()
    {
        $result = array( '{store_logo}', '{store_name}', '{total_products}' );
        
        return $result;
    }
    
    public function getStockReplace($data)
    {
        $result = array(
            'store_logo'     => $this->storeLogo(),
            'store_name'     => $this->config->get('config_name'),
            'total_products' => $data['outofstock']
        );
        
        return $result;
    }
    
    // Voucher
    public function getVoucherFind()
    {
        $result = array( '{store_logo}', '{recip_name}', '{recip_email}', '{date}', '{store_name}', '{name}', '{amount}', '{message}', '{store_href}', '{image}', '{code}' );
        
        return $result;
    }

    public function getVoucherReplace($data)
    {
        $result = array(
            'store_logo'  => $this->storeLogo(),
            'recip_name'  => $data['recip_name'],
            'recip_email' => $data['recip_email'],
            'date'        => date($this->language->get('date_format_short'), strtotime(date("Y-m-d H:i:s"))),
            'store_name'  => $data['store_name'],
            'name'        => $data['name'],
            'amount'      => $data['amount'],
            'message'     => $data['message'],
            'store_href'  => $data['store_href'],
            'image'       => (file_exists(DIR_IMAGE . $data['image'])) ? 'cid:' . md5(basename($data['image'])) : '', 'code' => $data['code']
        );
        
        return $result;
    }

    // Order Text
    public function getOrderText($template_id, $data)
    {

        foreach ($data as $dataKey => $dataValue) {
            $$dataKey = $dataValue;
        }

         // Load the language for any mails that might be required to be sent out
        $language = new Language($order_info['language_directory'], $this->registry);
        $language->load('mail/order');

        $text  = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
        $text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
        $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
        $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

        if ($comment && $notify) {
            $text .= $language->get('text_new_instruction') . "\n\n";
            $text .= $comment . "\n\n";
        }

        // Products
        $text .= $language->get('text_new_products') . "\n";

        foreach ($getProducts as $product) {
            $text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

            $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

            foreach ($order_option_query->rows as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
            }
        }


        foreach ($getVouchers as $voucher) {
            $text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
        }

        $text .= "\n";

        $text .= $language->get('text_new_order_total') . "\n";

        foreach ($getTotal as $total) {
            $text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
        }


        $text .= "\n";

        if ($order_info['customer_id']) {
            $text .= $language->get('text_new_link') . "\n";
            $text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
        }

        /*
         if ($download_status) {
             $text .= $language->get('text_new_download') . "\n";
             $text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
         }
        */
        // Comment
        if ($order_info['comment']) {
            $text .= $language->get('text_new_comment') . "\n\n";
            $text .= $order_info['comment'] . "\n\n";
        }

        $text .= $language->get('text_new_footer') . "\n\n";

        return $text;
    }

    // Language
    public function getLanguage()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "language WHERE language_id = '" . $this->config->get('config_language_id') . "'";
        $query = $this->db->query($sql);

        return $query->row;
    }

    // Order Special

    // Order Product
    public function getOrderProducts($order_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

        return $query->rows;
    }

    public function getProductsTemplate($order_info, $getProducts, $template_product)
    {
        $result = array();

        foreach ($getProducts as $product) {
            $option = array();
            $attribute = array();

             // Product Option Order
            if (stripos($template_product[1], '{product_option}') !== false) {
                $product_option = $this->getOrderOptions($order_info['order_id'], $product['product_id']);

                foreach ($product_option as $option) {
                    if ($option['type'] != 'file') {
                        $option[] = '<i>' . $option['name'] . '</i>: ' . $option['value'];
                    } else {
                        $filename = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                        $option[] = '<i>' . $option['name'] . '</i>: ' . (utf8_strlen($filename) > 20 ? utf8_substr($filename, 0, 20) . '..' : $filename);
                    }
                }
            }

            // Product Attribute Order
            if (stripos($template_product[1], '{product_attribute}') !== false) {
                $product_attributes = $this->getProductAttributes($product['product_id'], $order_info['language_id']);

                foreach ($product_attributes as $attribute_group) {
                    $attribute_sub_data = '';

                    foreach ($attribute_group['attribute'] as $attribute) {
                        $attribute_sub_data .= '<br />' . $attribute['name'] . ': ' . $attribute['text'];
                    }

                    $attribute[] = '<u>' . $attribute_group['name'] . '</u>' . $attribute_sub_data;
                }
            }

            $getProduct = $this->getProduct($product['product_id']);

            // Product Image Order
            if ($getProduct['image']) {
                if ($this->config->get('template_email_product_thumbnail_width') && $this->config->get('template_email_product_thumbnail_height')) {
                    $image = $this->imageResize($getProduct['image'], $this->config->get('template_email_product_thumbnail_width'), $this->config->get('template_email_product_thumbnail_height'));
                } else {
                    $image = $this->imageResize($getProduct['image'], 80, 80);
                }
            } else {
                $image = '';
            }

            #Replace Product Short Code to Values
            $product_replace = $this->getProductReplace($image, $product, $order_info, $attribute, $option);

            $product_find = $this->getProductFind();

            $result[] = trim(str_replace($product_find, $product_replace, $template_product[1]));
        }

        return $result;
    }

    public function getProductFind()
    {
        $result = array(
            '{product_image}', '{product_name}', '{product_model}', '{product_quantity}', '{product_price}', '{product_price_gross}', '{product_attribute}',
            '{product_option}', '{product_sku}', '{product_upc}', '{product_tax}', '{product_total}', '{product_total_gross}'
        );

        return $result;
    }

    public function getProductReplace($image, $product, $order_info, $attribute, $option)
    {
        $getProduct = $this->getProduct($product['product_id']);

        $result = array(
            'product_image'       => '<img src="' . $image . '" style="width: 50px;height: 50px;padding: auto;">',
            'product_name'        => $product['name'],
            'product_model'       => $product['model'],
            'product_quantity'    => $product['quantity'],
            'product_price'       => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
            'product_price_gross' => $this->currency->format(($product['price'] + $product['tax']), $order_info['currency_code'], $order_info['currency_value']),
            'product_attribute'   => implode('<br />', $attribute),
            'product_option'      => implode('<br />', $option),
            'product_sku'         => $getProduct['sku'],
            'product_upc'         => $getProduct['upc'],
            'product_tax'         => $this->currency->format($product['tax'], $order_info['currency_code'], $order_info['currency_value']),
            'product_total'       => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
            'product_total_gross' => $this->currency->format($product['total'] + ($product['tax'] * $product['quantity']), $order_info['currency_code'], $order_info['currency_value'])
        );

        return $result;
    }

    public function getOrderOptions($order_id, $order_product_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

        return $query->rows;
    }

    public function getProductAttributes($product_id, $language_id)
    {
        $product_attribute_group_data = array();

        $product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$language_id . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

        foreach ($product_attribute_group_query->rows as $product_attribute_group) {
            $product_attribute_data = array();

            $product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$language_id . "' AND pa.language_id = '" . (int)$language_id . "' ORDER BY a.sort_order, ad.name");

            foreach ($product_attribute_query->rows as $product_attribute) {
                $product_attribute_data[] = array(
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name'         => $product_attribute['name'],
                    'text'         => $product_attribute['text']
                );
            }

            $product_attribute_group_data[] = array(
                'attribute_group_id' => $product_attribute_group['attribute_group_id'],
                'name'               => $product_attribute_group['name'],
                'attribute'          => $product_attribute_data
            );
        }

        return $product_attribute_group_data;
    }

    // Order Voucher
    public function getOrderVouchers($order_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

        return $query->rows;
    }

    public function getVoucherTemplate($order_info, $getVouchers, $template_voucher)
    {
        $result = array();


        foreach ($getVouchers as $voucher) {
            // Replace Product Short Code to Values
            $voucher_find = $this->getOrderVoucherFind();
            $voucher_replace = $this->getOrderVoucherReplace($voucher, $order_info);

            $result[] = trim(str_replace($voucher_find, $voucher_replace, $template_voucher[1]));
        }

        return $result;
    }

    public function getOrderVoucherFind()
    {
        $result = array( '{voucher_description}', '{voucher_amount}' );

        return $result;
    }

    public function getOrderVoucherReplace($voucher, $order_info)
    {
        $result = array(
            'voucher_description'  => $voucher['description'],
            'voucher_amount'       => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
        );

        return $result;
    }

    // Order Comment
    public function getCommentTemplate($comment, $template_comment)
    {
        $result = array();

        // Replace Product Short Code to Values
        $comment_find = $this->getCommentFind();
        $comment_replace = $this->getCommentReplace($comment);

        $result[] = trim(str_replace($comment_find, $comment_replace, $template_comment[1]));

        return $result;
    }

    public function getCommentFind()
    {
        $result = array( '{comment}' );

        return $result;
    }

    public function getCommentReplace($comment)
    {
        $result = array(
            'comment'  => $comment,
        );

        return $result;
    }

    // Order Tax
    public function getTaxTemplate($totals, $template_tax)
    {
        $result = array();

        if (isset($totals['tax'])) {
            foreach ($totals['tax'] as $tax) {
                // Replace Product Short Code to Values
                $tax_find = $this->getTaxFind();
                $tax_replace = $this->getTaxReplace($tax);

                $result[] = trim(str_replace($tax_find, $tax_replace, $template_tax[1]));
            }
        }

        return $result;
    }

    public function getTaxFind()
    {
        $result = array( '{tax_title}', '{tax_value}' );

        return $result;
    }

    public function getTaxReplace($tax)
    {
        $result = array(
            'tax_title'     => $tax['title'],
            'tax_value'     => $tax['text']
        );

        return $result;
    }

    // Order Total
    public function getTotalTemplate($getTotal, $template_total, $order_info)
    {
        $result = array();

        foreach ($getTotal as $total) {
            // Replace Product Short Code to Values
            $total_find = $this->getTotalFind();
            $total_replace = $this->getTotalReplace($total, $order_info);

            $result[] = trim(str_replace($total_find, $total_replace, $template_total[1]));
        }

        return $result;
    }

    public function getTotalFind()
    {
        $result = array( '{total_title}', '{total_value}' );

        return $result;
    }

    public function getTotalReplace($total, $order_info)
    {
        $result = array(
            'total_title'     => $total['title'],
            'total_value'     => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
        );

        return $result;
    }

    // Default Mail Subject & Message
    public function getDefaultSubject($type, $template_id, $data)
    {
        switch (ucwords($type)) {
            case 'Login':
                $subject = $this->getDefaultLoginSubject($template_id, $data);
                break;
            case 'Affilate':
                $subject = $this->getDefaultAffilateSubject($template_id, $data);
                break;
            case 'Customer':
                $subject = $this->getDefaultCustomerSubject($template_id, $data);
                break;
            case 'Contact':
                $subject = $this->getDefaultContactSubject($template_id, $data);
                break;
            case 'Order':
                $subject = $this->getDefaultOrderSubject($template_id, $data);
                break;
            case 'OrderAll':
                $subject = $this->getDefaultOrderSubject($template_id, $data);
                break;
            case 'Return':
                $subject = $this->getDefaultReturnSubject($template_id, $data);
                break;
            case 'Review':
                $subject = $this->getDefaultReviewSubject($template_id, $data);
                break;
            case 'Stock':
                $subject = $this->getDefaultStockSubject($template_id, $data);
                break;
            case 'Voucher':
                $subject = $this->getDefaultVoucherSubject($template_id, $data);
                break;
        }

        return $subject;
    }

    public function getDefaultMessage($type, $template_id, $data)
    {
        switch (ucwords($type)) {
            case 'Login':
                $subject = $this->getDefaultLoginMessage($template_id, $data);
                break;
            case 'Affilate':
                $subject = $this->getDefaultAffilateMessage($template_id, $data);
                break;
            case 'Customer':
                $subject = $this->getDefaultCustomerMessage($template_id, $data);
                break;
            case 'Contact':
                $subject = $this->getDefaultContactMessage($template_id, $data);
                break;
            case 'Order':
                $subject = $this->getDefaultOrderMessage($template_id, $data);
                break;
            case 'OrderAll':
                $subject = $this->getDefaultOrderMessage($template_id, $data);
                break;
            case 'Return':
                $subject = $this->getDefaultReturnMessage($template_id, $data);
                break;
            case 'Review':
                $subject = $this->getDefaultReviewMessage($template_id, $data);
                break;
            case 'Stock':
                $subject = $this->getDefaultStockMessage($template_id, $data);
                break;
            case 'Voucher':
                $subject = $this->getDefaultVoucherMessage($template_id, $data);
                break;
        }

        return $subject;
    }

    public function getDefaultLoginSubject($type_id, $data)
    {
        $username = $data['username'];

        $subject = 'User '.$username.' logged in on '.$this->config->get('config_name').' admin panel';

        return $subject;
    }

    public function getDefaultLoginMessage($type_id, $data)
    {
        $message = 'Hello,<br/><br/>';
        $message .= 'We would like to notify you that user ' . $data['username'] . ' has just logged in to the admin panel of your store, ' . $data['store_name'] . ', using IP address ' . $data['ip_address'].'.<br/><br/>';
        $message .= 'If this is expected you need to do nothing about it. If you suspect a hacking attempt, please log in to your store\'s admin panel immediately and change your password at once.<br/><br/>';
        $message .= 'Best Regards,<br/><br/>';
        $message .= 'The ' . $data['store_name'] . ' team<br/><br/>';

        return $message;
    }

    public function getDefaultAffilateSubject($type_id, $data)
    {
        $this->load->language('mail/affiliate');

        if ($type_id == 'affiliate_4') {
            $subject = sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'affiliate_5') {
            $subject = sprintf($this->language->get('text_commission_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'affiliate_1') {
            $subject = sprintf($this->language->get('text_register_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'affiliate_3') {
            $subject = sprintf($this->language->get('text_register_approve_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'affiliate_2') {
            // Reset Password Null
            $subject = '';
        } else {
            $subject = $this->config->get('config_name') . ' - Affilate Mail';
        }

        return $subject;
    }

    public function getDefaultAffilateMessage($type_id, $data)
    {
        $this->load->language('mail/affiliate');

        if ($type_id == 'affiliate_4') {
            $message  = sprintf($this->language->get('text_approve_welcome'), $this->config->get('config_name')) . "\n\n";
            $message .= $this->language->get('text_approve_login') . "\n";
            $message .= ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG . 'index.php?route=affiliate/login' . "\n\n";
            $message .= $this->language->get('text_approve_services') . "\n\n";
            $message .= $this->language->get('text_approve_thanks') . "\n";
            $message .= $this->config->get('config_name');
        } elseif ($type_id == 'affiliate_5') {
            $message  = sprintf($this->language->get('text_commission_received'), $this->currency->format($data['amount'], $this->config->get('config_currency'))) . "\n\n";
            $message .= sprintf($this->language->get('text_commission_total'), $this->currency->format($this->getCommissionTotal($data['affiliate_id']), $this->config->get('config_currency')));
        } elseif ($type_id == 'affiliate_1') {
            $message = sprintf($this->language->get('text_register_message'), $data['firstname'] . ' ' . $data['lastname'], $this->config->get('config_name'));
        } elseif ($type_id == 'affiliate_3') {
            $message = sprintf($this->language->get('text_register_approve_message'), $data['firstname'] . ' ' . $data['lastname'], $this->config->get('config_name'));
        } elseif ($type_id == 'affiliate_2') {
            // Reset Password Null
            $message = sprintf($this->language->get('text_register_approve_subject'), $this->config->get('config_name'), $data['firstname'] . ' ' . $data['lastname']);
        } else {
            $message = 'Hi!' . "\n" . 'Welcome ' . $data['firstname'] . ' ' . $data['lastname'];
        }

        return $message;
    }

    // Affilate getComissionTotal Frontend & Backend
    public function getCommissionTotal($affiliate_id)
    {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "affiliate_commission WHERE affiliate_id = '" . (int)$affiliate_id . "'");

        return $query->row['total'];
    }

    public function getDefaultCustomerSubject($type_id, $data)
    {
        $this->load->language('mail/customer');

        if ($type_id == 'customer_4') {
            $subject = sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'customer_1') {
            // Register
            $subject = sprintf($this->language->get('text_register_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'customer_2') {
            // Aprove
            $subject = sprintf($this->language->get('text_approve_wait_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'customer_3') {
            // Reset
            $subject = sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name'));
        } elseif ($type_id == 'customer_5') {
            $subject = $this->getDefaultVoucherSubject($type_id, $data);
        } else {
            $subject = $this->config->get('config_name') . ' - Customer Mail';
        }

        return $subject;
    }

    public function getDefaultCustomerMessage($type_id, $data)
    {
        $this->load->language('mail/customer');

        if ($type_id == 'customer_4') {
            $store_name = $this->config->get('config_name');
            $store_url = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG . 'index.php?route=account/login';

            $message = sprintf($this->language->get('text_approve_welcome'), $store_name) . "\n\n";
            $message .= $this->language->get('text_approve_login') . "\n";
            $message .= $store_url . "\n\n";
            $message .= $this->language->get('text_approve_services') . "\n\n";
            $message .= $this->language->get('text_approve_thanks') . "\n";
            $message .= $store_name;
        } elseif ($type_id == 'customer_1') {
            // Register
            $message = sprintf($this->language->get('text_register_message'), $this->config->get('config_name'));
        } elseif ($type_id == 'customer_2') {
            // Aprove
            $message = sprintf($this->language->get('text_register_message'), $this->config->get('config_name'));
        } elseif ($type_id == 'customer_3') {
            $message = ' --- ';
        } elseif ($type_id == 'customer_5') {
            $message = $this->getDefaultVoucherMessage($type_id, $data);
        } else {
            $message = 'Customer Mail Description';
        }

        return $message;
    }

    public function getDefaultContactSubject($type_id, $data)
    {
        $this->load->language('information/contact');

        $subject = sprintf($this->language->get('email_subject'), $data['name']);

        return $subject;
    }

    public function getDefaultContactMessage($type_id, $data)
    {
        return strip_tags($data['enquiry']);
    }

    public function getDefaultOrderSubject($type_id, $data)
    {
        $this->load->language('mail/order');

        $subject = sprintf($this->language->get('text_new_subject'), $data['order_info']['store_name'], $data['order_info']['order_id']);

        return $subject;
    }

    public function getDefaultOrderMessage($type_id, $data)
    {
        $this->load->language('mail/order');

        foreach ($data as $dataKey => $dataValue) {
            $$dataKey = $dataValue;
        }

        // HTML Mail
        $html_data = array();

        $html_data['title'] = sprintf($this->language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_info['order_id']);

        $html_data['text_greeting'] = sprintf($this->language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
        $html_data['text_link'] = $this->language->get('text_new_link');
        $html_data['text_download'] = $this->language->get('text_new_download');
        $html_data['text_order_detail'] = $this->language->get('text_new_order_detail');
        $html_data['text_instruction'] = $this->language->get('text_new_instruction');
        $html_data['text_order_id'] = $this->language->get('text_new_order_id');
        $html_data['text_date_added'] = $this->language->get('text_new_date_added');
        $html_data['text_payment_method'] = $this->language->get('text_new_payment_method');
        $html_data['text_shipping_method'] = $this->language->get('text_new_shipping_method');
        $html_data['text_email'] = $this->language->get('text_new_email');
        $html_data['text_telephone'] = $this->language->get('text_new_telephone');
        $html_data['text_ip'] = $this->language->get('text_new_ip');
        $html_data['text_order_status'] = $this->language->get('text_new_order_status');
        $html_data['text_payment_address'] = $this->language->get('text_new_payment_address');
        $html_data['text_shipping_address'] = $this->language->get('text_new_shipping_address');
        $html_data['text_product'] = $this->language->get('text_new_product');
        $html_data['text_model'] = $this->language->get('text_new_model');
        $html_data['text_quantity'] = $this->language->get('text_new_quantity');
        $html_data['text_price'] = $this->language->get('text_new_price');
        $html_data['text_total'] = $this->language->get('text_new_total');
        $html_data['text_footer'] = $this->language->get('text_new_footer');

        $html_data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
        $html_data['store_name'] = $order_info['store_name'];
        $html_data['store_url'] = $order_info['store_url'];
        $html_data['customer_id'] = $order_info['customer_id'];
        $html_data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_info['order_id'];

        if (isset($download_status) && $download_status) {
            $html_data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
        } else {
            $html_data['download'] = '';
        }

        $html_data['order_id'] = $order_info['order_id'];
        $html_data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
        $html_data['payment_method'] = $order_info['payment_method'];
        $html_data['shipping_method'] = $order_info['shipping_method'];
        $html_data['email'] = $order_info['email'];
        $html_data['telephone'] = $order_info['telephone'];
        $html_data['ip'] = $order_info['ip'];
        $html_data['order_status'] = $order_status;

        if ($comment && $notify) {
            $html_data['comment'] = nl2br($comment);
        } else {
            $html_data['comment'] = '';
        }

        if ($order_info['payment_address_format']) {
            $format = $order_info['payment_address_format'];
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}'
        );

        $replace = array(
            'firstname' => $order_info['payment_firstname'],
            'lastname'  => $order_info['payment_lastname'],
            'company'   => $order_info['payment_company'],
            'address_1' => $order_info['payment_address_1'],
            'address_2' => $order_info['payment_address_2'],
            'city'      => $order_info['payment_city'],
            'postcode'  => $order_info['payment_postcode'],
            'zone'      => $order_info['payment_zone'],
            'zone_code' => $order_info['payment_zone_code'],
            'country'   => $order_info['payment_country']
        );

        $html_data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        if ($order_info['shipping_address_format']) {
            $format = $order_info['shipping_address_format'];
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}'
        );

        $replace = array(
            'firstname' => $order_info['shipping_firstname'],
            'lastname'  => $order_info['shipping_lastname'],
            'company'   => $order_info['shipping_company'],
            'address_1' => $order_info['shipping_address_1'],
            'address_2' => $order_info['shipping_address_2'],
            'city'      => $order_info['shipping_city'],
            'postcode'  => $order_info['shipping_postcode'],
            'zone'      => $order_info['shipping_zone'],
            'zone_code' => $order_info['shipping_zone_code'],
            'country'   => $order_info['shipping_country']
        );

        $html_data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));


        $this->load->model('tool/upload');

        // Products
        $html_data['products'] = array();

        $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_info['order_id'] . "'");

        foreach ($order_product_query->rows as $product) {
            $option_data = array();

            $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_info['order_id'] . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

            foreach ($order_option_query->rows as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $html_data['products'][] = array(
                'name'     => $product['name'],
                'model'    => $product['model'],
                'option'   => $option_data,
                'quantity' => $product['quantity'],
                'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
            );
        }

        // Vouchers
        $html_data['vouchers'] = array();


        $order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_info['order_id'] . "'");

        foreach ($order_voucher_query->rows as $voucher) {
            $html_data['vouchers'][] = array(
                'description' => $voucher['description'],
                'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
            );
        }

        // Order Totals
        $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_info['order_id'] . "' ORDER BY sort_order ASC");

        foreach ($order_total_query->rows as $total) {
            $html_data['totals'][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
            );
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
            $html = $this->load->view($this->config->get('config_template') . '/template/mail/order.tpl', $html_data);
        } else {
            $html = $this->load->view('default/template/mail/order.tpl', $html_data);
        }

        return $html;
    }
    
    public function getDefaultReturnSubject($type_id, $data)
    {
        $this->load->language('mail/return');

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

        return $subject;
    }

    public function getDefaultReturnMessage($type_id, $data)
    {
        $this->load->language('mail/return');

        $message  = $this->language->get('text_request') . "\n";
        $message .= "\n";
        $message .= sprintf($this->language->get('text_order_id'), $data['order_id']) . "\n";
        $message .= sprintf($this->language->get('text_date_ordered'), $this->db->escape(strip_tags($data['date_ordered']))) . "\n";
        $message .= sprintf($this->language->get('text_customer'), $this->db->escape(strip_tags($data['firstname'])), $this->db->escape(strip_tags($data['lastname']))) . "\n";
        $message .= sprintf($this->language->get('text_email'), $this->db->escape(strip_tags($data['email']))) . "\n";
        $message .= sprintf($this->language->get('text_telephone'), $this->db->escape(strip_tags($data['telephone']))) . "\n";
        $message .= "\n";
        $message .= sprintf($this->language->get('text_product'), $this->db->escape(strip_tags($data['product']))) . "\n";
        $message .= sprintf($this->language->get('text_model'), $this->db->escape(strip_tags($data['model']))) . "\n";
        $message .= sprintf($this->language->get('text_quantity'), $data['quantity']) . "\n";
        $message .= "\n";
        $message .= sprintf($this->language->get('text_return_reason'), $data['return_reason']) . "\n";
        $message .= sprintf($this->language->get('text_opened'), ($data['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no'))) . "\n";
        $message .= "\n";
        $message .= strip_tags($data['comment']);

        return nl2br($message);
    }

    public function getDefaultReviewSubject($type_id, $data)
    {
        $this->load->language('mail/review');

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

        return $subject;
    }

    public function getDefaultReviewMessage($type_id, $data)
    {
        $this->load->language('mail/review');
        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($data['product_id']);

        $message  = $this->language->get('text_waiting') . "\n";
        $message .= sprintf($this->language->get('text_product'), $this->db->escape(strip_tags($product_info['name']))) . "\n";
        $message .= sprintf($this->language->get('text_reviewer'), $this->db->escape(strip_tags($data['name']))) . "\n";
        $message .= sprintf($this->language->get('text_rating'), $this->db->escape(strip_tags($data['rating']))) . "\n";
        $message .= $this->language->get('text_review') . "\n";
        $message .= $this->db->escape(strip_tags($data['text'])) . "\n\n";

        return $message;
    }
    
    public function getDefaultStockSubject($type_id, $data)
    {
        $this->load->language('mail/stock');

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $data['outofstock']);

        return $subject;
    }

    public function getDefaultStockMessage($type_id, $data)
    {
        $this->load->language('mail/stock');

        $message  = sprintf($this->language->get('text_notify'), $data['outofstock'], $this->config->get('config_name')) . "\n";
        $message .= $this->language->get('text_view') . "\n";
        $message .= sprintf($this->language->get('text_signature'), $this->config->get('config_name'));

        return nl2br($message);
    }

    public function getDefaultVoucherSubject($type_id, $data)
    {
        $this->load->language('mail/review');

        $subject = sprintf($this->language->get('text_subject'), $data['name']);

        return $subject;
    }


    public function getDefaultVoucherMessage($type_id, $data)
    {
        $this->load->language('mail/voucher');

        $voucher_data = array();

        $voucher_data['title'] = sprintf($this->language->get('text_subject'), $data['name']);

        $voucher_data['text_greeting']  = sprintf($this->language->get('text_greeting'), $data['amount']);
        $voucher_data['text_from']      = sprintf($this->language->get('text_from'), $data['name']);
        $voucher_data['text_message']   = $this->language->get('text_message');
        $voucher_data['text_redeem']    = sprintf($this->language->get('text_redeem'), $data['code']);
        $voucher_data['text_footer']    = $this->language->get('text_footer');

        if (is_file(DIR_IMAGE . $data['image'])) {
            $voucher_data['image'] = $this->config->get('config_url') . 'image/' . $data['image'];
        } else {
            $voucher_data['image'] = '';
        }

        $voucher_data['store_name'] = $data['store_name'];
        $voucher_data['store_url']  = $data['store_href'];
        $voucher_data['message']    = $data['message'];

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/voucher.tpl')) {
            $message = $this->load->view($this->config->get('config_template') . '/template/mail/voucher.tpl', $voucher_data);
        } else {
            $message = $this->load->view('default/template/mail/voucher.tpl', $voucher_data);
        }

        return $message;
    }

    public function imageResize($filename, $width, $height)
    {
        if (!is_file(DIR_IMAGE . $filename)) {
            return;
        }

         $extension = pathinfo($filename, PATHINFO_EXTENSION);

         $old_image = $filename;
         $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        if (!is_file(DIR_IMAGE . $new_image) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $new_image))) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_IMAGE . $path)) {
                    $this->filesystem->mkdir(DIR_IMAGE . $path);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);


            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }
        }

        if ($_SERVER['HTTPS']) {
            return $this->config->get('config_ssl') . 'image/' . $new_image;
        } else {
            return $this->config->get('config_url') . 'image/' . $new_image;
        }
    }

    public function getUploadByCode($code)
    {
         $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");

         return $query->row;
    }

    public function getProduct($product_id)
    {
         $query = $this->db->query("SELECT DISTINCT p.*, pd.*, md.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON (p.manufacturer_id = md.manufacturer_id AND md.language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return array(
                'product_id'       => $query->row['product_id'],
                'name'             => $query->row['name'],
                'description'      => $query->row['description'],
                'meta_title'       => $query->row['meta_title'],
                'meta_description' => $query->row['meta_description'],
                'meta_keyword'     => $query->row['meta_keyword'],
                'tag'              => $query->row['tag'],
                'model'            => $query->row['model'],
                'sku'              => $query->row['sku'],
                'upc'              => $query->row['upc'],
                'ean'              => $query->row['ean'],
                'jan'              => $query->row['jan'],
                'isbn'             => $query->row['isbn'],
                'mpn'              => $query->row['mpn'],
                'location'         => $query->row['location'],
                'quantity'         => $query->row['quantity'],
                'stock_status'     => $query->row['stock_status'],
                'image'            => $query->row['image'],
                'manufacturer_id'  => $query->row['manufacturer_id'],
                'manufacturer'     => $query->row['manufacturer'],
                'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
                'special'          => $query->row['special'],
                'reward'           => $query->row['reward'],
                'points'           => $query->row['points'],
                'tax_class_id'     => $query->row['tax_class_id'],
                'date_available'   => $query->row['date_available'],
                'weight'           => $query->row['weight'],
                'weight_class_id'  => $query->row['weight_class_id'],
                'length'           => $query->row['length'],
                'width'            => $query->row['width'],
                'height'           => $query->row['height'],
                'length_class_id'  => $query->row['length_class_id'],
                'subtract'         => $query->row['subtract'],
                'rating'           => round($query->row['rating']),
                'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
                'minimum'          => $query->row['minimum'],
                'sort_order'       => $query->row['sort_order'],
                'status'           => $query->row['status'],
                'date_added'       => $query->row['date_added'],
                'date_modified'    => $query->row['date_modified'],
                'viewed'           => $query->row['viewed']
            );
        } else {
            return false;
        }
    }
}
