<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerReportCustomerSearch extends Controller
{
    public function index()
    {
        $this->load->language('report/customer_search');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = '';
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }

        if (isset($this->request->get['filter_keyword'])) {
            $filter_keyword = $this->request->get['filter_keyword'];
        } else {
            $filter_keyword = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_ip'])) {
            $filter_ip = $this->request->get['filter_ip'];
        } else {
            $filter_ip = null;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->load->model('report/customer');
        $this->load->model('catalog/category');

        $data = $this->language->all();

        $data['searches'] = array();

        $filter_data = array(
            'filter_date_start' => $filter_date_start,
            'filter_date_end'   => $filter_date_end,
            'filter_keyword'    => $filter_keyword,
            'filter_customer'   => $filter_customer,
            'filter_ip'         => $filter_ip,
            'start'             => ($page - 1) * 20,
            'limit'             => 20
        );

        $search_total = $this->model_report_customer->getTotalCustomerSearches($filter_data);

        $results = $this->model_report_customer->getCustomerSearches($filter_data);

        foreach ($results as $result) {
            $category_info = $this->model_catalog_category->getCategory($result['category_id']);

            if (isset($category_info['category_id'])) {
                $category = !empty($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name'];
            } else {
                $category = '';
            }

            if ($result['customer_id'] > 0) {
                $customer = sprintf($this->language->get('text_customer'), $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true), $result['customer']);
            } else {
                $customer = $this->language->get('text_guest');
            }

            $data['searches'][] = array(
                'keyword'    => $result['keyword'],
                'products'   => $result['products'],
                'category'   => $category,
                'customer'   => $customer,
                'ip'         => $result['ip'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
            );
        }

        $data['token'] = $this->session->data['token'];

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_keyword'])) {
            $url .= '&filter_keyword=' . urlencode($this->request->get['filter_keyword']);
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $pagination        = new Pagination();
        $pagination->total = $search_total;
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url   = $this->url->link('report/customer_search', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($search_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($search_total - $this->config->get('config_limit_admin'))) ? $search_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $search_total, ceil($search_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end']   = $filter_date_end;
        $data['filter_keyword']    = $filter_keyword;
        $data['filter_customer']   = $filter_customer;
        $data['filter_ip']         = $filter_ip;

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/customer_search.tpl', $data));
    }
}
