<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerSaleInvoice extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('sale/order');
        $this->load->language('sale/invoice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/order');
        $this->load->model('sale/invoice');

        $this->getList();
    }

    protected function getList()
    {
        $data = $this->language->all();

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_invoice_number'])) {
            $filter_invoice_number = $this->request->get['filter_invoice_number'];
        } else {
            $filter_invoice_number = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }
		
        if (isset($this->request->get['filter_order_date'])) {
            $filter_order_date = $this->request->get['filter_order_date'];
        } else {
            $filter_order_date = null;
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $filter_invoice_date = $this->request->get['filter_invoice_date'];
        } else {
            $filter_invoice_date = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'invoice_number';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_invoice_number'])) {
            $url .= '&filter_invoice_number=' . urlencode(html_entity_decode($this->request->get['filter_invoice_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }
		
        if (isset($this->request->get['filter_order_date'])) {
            $url .= '&filter_order_date=' . $this->request->get['filter_order_date'];
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['generate'] = $this->url->link('sale/invoice/generate', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['invoices'] = array();

        $filter_data = array(
            'filter_order_id'      => $filter_order_id,
            'filter_invoice_number'=> $filter_invoice_number,
            'filter_order_status'  => $filter_order_status,
            'filter_customer'      => $filter_customer,
            'filter_total'         => $filter_total,
            'filter_order_date'    => $filter_order_date,
            'filter_invoice_date'  => $filter_invoice_date,
            'sort'                 => $sort,
            'order'                => $order,
            'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                => $this->config->get('config_limit_admin')
        );

        $order_total = $this->model_sale_invoice->getTotalInvoices($filter_data);

        $results = $this->model_sale_invoice->getInvoices($filter_data);

        foreach ($results as $result) {
            $data['invoices'][] = array(
                'order_id'      => $result['order_id'],
                'invoice_number'=> $result['invoice_number'],
                'customer'      => $result['customer'],
                'status'        => $result['status'],
                'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'order_date'    => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
                'invoice_date'  => date($this->language->get('date_format_short'), strtotime($result['invoice_date'])),
                'info'          => $this->url->link('sale/invoice/info', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL'),
                'pdf'           => $this->url->link('sale/invoice/pdf', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL'),
                'email'         => $this->url->link('sale/invoice/email', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL')
            );
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_invoice_number'])) {
            $url .= '&filter_invoice_number=' . urlencode(html_entity_decode($this->request->get['filter_invoice_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }
		
        if (isset($this->request->get['filter_order_date'])) {
            $url .= '&filter_order_date=' . $this->request->get['filter_order_date'];
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order_id'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $data['sort_invoice_number'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=invoice_number' . $url, 'SSL');
        $data['sort_customer'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
        $data['sort_order_date'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=order_date' . $url, 'SSL');
        $data['sort_invoice_date'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=i.invoice_date' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_invoice_number'])) {
            $url .= '&filter_invoice_number=' . urlencode(html_entity_decode($this->request->get['filter_invoice_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }
		
        if (isset($this->request->get['filter_order_date'])) {
            $url .= '&filter_order_date=' . $this->request->get['filter_order_date'];
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_invoice_number'] = $filter_invoice_number;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_customer'] = $filter_customer;
        $data['filter_total'] = $filter_total;
        $data['filter_order_date'] = $filter_order_date;
        $data['filter_invoice_date'] = $filter_invoice_date;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/invoice_list.tpl', $data));
    }

    public function generate()
    {
        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }
		
        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_order_date'])) {
            $filter_order_date = $this->request->get['filter_order_date'];
        } else {
            $filter_order_date = null;
        }

        $url = '';

        if (isset($this->request->get['filter_invoice_number'])) {
            $url .= '&filter_invoice_number=' . urlencode(html_entity_decode($this->request->get['filter_invoice_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (!$this->validate()) {
            $this->response->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->load->model('sale/order');
        $this->load->model('sale/invoice');

        $filter_data = array(
            'filter_order_id'      => $filter_order_id,
            'filter_customer'      => $filter_customer,
            'filter_total'         => $filter_total,
            'filter_order_status'  => $filter_order_status,
            'filter_order_date'    => $filter_order_date
        );

        // Get orders
        $orders = $this->model_sale_invoice->getOrders($filter_data);

        if (empty($orders)) {
            $this->session->data['error'] = $this->language->get('error_generate_empty');
        } else {
            $this->session->data['success'] = $this->language->get('text_generate_success');

            foreach ($orders as $order) {
                $this->model_sale_order->createInvoiceNo($order['order_id']);
            }
        }

        $this->response->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function info()
    {
        $this->load->language('sale/order');
        $this->load->language('sale/invoice');

        $data = $this->language->all();

        $this->document->setTitle($data['text_invoice']);

        $data['token'] = $this->session->data['token'];

        $url = '';

        if (isset($this->request->get['filter_invoice_number'])) {
            $url .= '&filter_invoice_number=' . urlencode(html_entity_decode($this->request->get['filter_invoice_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['pdf'] = $this->url->link('sale/invoice/pdf', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'], 'SSL');
        $data['email'] = $this->url->link('sale/invoice/email', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'], 'SSL');
        $data['cancel'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['invoice_id'])) {
            $invoice_id = $this->request->get['invoice_id'];
        } else {
            $invoice_id = 0;
        }

        $this->load->model('sale/invoice');

        $data['invoice'] = array();

        $invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

        if ($invoice_info) {
            $this->load->model('sale/order');
            $this->load->model('setting/setting');

            $order_id = (int) $invoice_info['order_id'];

            $order_info = $this->model_sale_order->getOrder($order_id);

            $data['order'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL');

            $invoice_number = $order_info['invoice_prefix'] . $order_info['invoice_no'];

            $data['invoice_id'] = $invoice_info['invoice_id'];

            $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

            if ($store_info) {
                $store_address = $store_info['config_address'];
                $store_email = $store_info['config_email'];
                $store_telephone = $store_info['config_telephone'];
                $store_fax = $store_info['config_fax'];
            } else {
                $store_address = $this->config->get('config_address');
                $store_email = $this->config->get('config_email');
                $store_telephone = $this->config->get('config_telephone');
                $store_fax = $this->config->get('config_fax');
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

            $payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

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

            $shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->load->model('tool/upload');

            $product_data = array();

            $products = $this->model_sale_order->getOrderProducts($order_id);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                foreach ($options as $option) {
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
                        'value' => $value
                    );
                }

                $product_data[] = array(
                    'name'     => $product['name'],
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $voucher_data = array();

            $vouchers = $this->model_sale_order->getOrderVouchers($order_id);

            foreach ($vouchers as $voucher) {
                $voucher_data[] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $total_data = array();

            $totals = $this->model_sale_order->getOrderTotals($order_id);

            foreach ($totals as $total) {
                $total_data[] = array(
                    'title' => $total['title'],
                    'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                );
            }

            $data['invoice'] = array(
                'order_id'           => $order_id,
                'invoice_number'     => $invoice_number,
                'order_date'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                'invoice_date'       => date($this->language->get('date_format_short'), strtotime($invoice_info['invoice_date'])),
                'store_name'         => $order_info['store_name'],
                'store_url'          => rtrim($order_info['store_url'], '/'),
                'store_address'      => nl2br($store_address),
                'store_email'        => $store_email,
                'store_telephone'    => $store_telephone,
                'store_fax'          => $store_fax,
                'email'              => $order_info['email'],
                'telephone'          => $order_info['telephone'],
                'shipping_address'   => $shipping_address,
                'shipping_method'    => $order_info['shipping_method'],
                'payment_address'    => $payment_address,
                'payment_method'     => $order_info['payment_method'],
                'product'            => $product_data,
                'voucher'            => $voucher_data,
                'total'              => $total_data,
                'comment'            => nl2br($order_info['comment'])
            );
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/invoice_info.tpl', $data));
    }

    public function getHistories()
    {
        $this->load->language('sale/order');
        $this->load->language('sale/invoice');

        $this->load->model('sale/invoice');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_notify'] = $this->language->get('column_notify');
        $data['column_comment'] = $this->language->get('column_comment');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['histories'] = array();

        $results = $this->model_sale_invoice->getHistories($this->request->get['invoice_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['histories'][] = array(
                'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
                'comment'    => nl2br($result['comment']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $history_total = $this->model_sale_invoice->getTotalHistories($this->request->get['invoice_id']);

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/invoice/gethistories', 'token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

        $this->response->setOutput($this->load->view('sale/invoice_history.tpl', $data));
    }

    public function addHistory()
    {
        $this->load->language('sale/invoice');

        $this->load->model('sale/order');
        $this->load->model('sale/invoice');

        $json = array();

        if (isset($this->request->get['invoice_id'])) {
            $invoice_id = $this->request->get['invoice_id'];
        } else {
            $invoice_id = 0;
        }

        if (!$invoice_id) {
            $json['error'] = $this->language->get('error_invoice_not_found');
        } else {
            $this->load->model('sale/invoice');

            $invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

            if ($invoice_info) {
                $data = array(
                    'invoice_id' => $invoice_id,
                    'comment' => $this->request->post['comment'],
                    'notify' => $this->request->post['notify']
                );

                $this->model_sale_invoice->addHistory($data);

                // Send Email
                if ($data['notify']) {
                    $invoice_data = $this->model_sale_order->getOrder($invoice_info['order_id']);

                    $invoice_data['invoice_id'] = $invoice_info['invoice_id'];
                    $invoice_data['invoice_date'] = $invoice_info['invoice_date'];
                    $invoice_data['comment'] = $data['comment'];

                    $subject = $this->emailtemplate->getSubject('Invoice', 'invoice_2', $invoice_data);
                    $message = $this->emailtemplate->getMessage('Invoice', 'invoice_2', $invoice_data);

                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($invoice_data['email']);
                    $mail->setFrom($this->config->get('config_email'));
                    $mail->setSender($invoice_data['store_name']);
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                    $mail->send();
                }

                $json['success'] = $this->language->get('text_history_success');
            } else {
                $json['error'] = $this->language->get('error_invoice_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function email()
    {
        $url = '';

        if (isset($this->request->get['filter_invoice_number'])) {
            $url .= '&filter_invoice_number=' . urlencode(html_entity_decode($this->request->get['filter_invoice_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (!$this->validate()) {
            $this->response->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->load->language('sale/order');
        $this->load->language('sale/invoice');

        $this->load->model('sale/order');
        $this->load->model('sale/invoice');

        if (isset($this->request->get['invoice_id'])) {
            $invoice_id = $this->request->get['invoice_id'];
        } else {
            $invoice_id = 0;
        }

        $invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

        if (!$invoice_info) {
            $this->session->data['error'] = $this->language->get('error_invoice_not_found');

            $this->response->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        // Generate the invoice file, temporary
        $this->pdf('F');

        // Send Email
        $invoice_data = $this->model_sale_order->getOrder($invoice_info['order_id']);

        $invoice_file = DIR_UPLOAD . $invoice_data['invoice_prefix'] . $invoice_data['invoice_no'] . '.pdf';
        
        if (is_file($invoice_file)) {
            $invoice_data['invoice_id'] = $invoice_info['invoice_id'];
            $invoice_data['invoice_date'] = $invoice_info['invoice_date'];
        
            $subject = $this->emailtemplate->getSubject('Invoice', 'invoice_1', $invoice_data);
            $message = $this->emailtemplate->getMessage('Invoice', 'invoice_1', $invoice_data);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($invoice_data['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($invoice_data['store_name']);
            $mail->setSubject($subject);
            $mail->setHtml($message);
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->addAttachment($invoice_file);
            $mail->send();

            // Delete the invoice file after it has been sent
            $this->filesystem->remove($invoice_file);

            $this->session->data['success'] = $this->language->get('text_email_success');
        } else {
            $this->session->data['error'] = $this->language->get('error_invoice_no_file');
        }

        $this->response->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function pdf($dest = 'D')
    {
        $url = '';

        if (isset($this->request->get['filter_invoice_number'])) {
            $url .= '&filter_invoice_number=' . urlencode(html_entity_decode($this->request->get['filter_invoice_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_invoice_date'])) {
            $url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (!$this->validate()) {
            $this->response->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->load->language('sale/order');
        $this->load->language('sale/invoice');

        $this->load->model('sale/order');
        $this->load->model('sale/invoice');

        if (isset($this->request->get['invoice_id'])) {
            $invoice_id = $this->request->get['invoice_id'];
        } else {
            $invoice_id = 0;
        }

        $invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

        if (!$invoice_info) {
            $this->session->data['error'] = $this->language->get('error_invoice_not_found');

            $this->response->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        Client::setName('catalog');

        $app = new Catalog();

        $app->initialise();

        $app->request->get['route'] = 'account/order/invoice';
        $app->request->get['order_id'] = $invoice_info['order_id'];
        $app->request->get['dest'] = $dest;
        $app->request->post = array();

        $app->ecommerce();
        $app->route();
        $app->dispatch();

        unset($app);

        // Return back to admin
        Client::setName('admin');
    }

    protected function validate($route = 'sale/invoice')
    {
        if (!$this->user->hasPermission('modify', $route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
