<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerDashboardCharts extends Controller
{
    public function index()
    {
        $this->load->language('dashboard/charts');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->user->getUsername());
        $data['text_new_order'] = $this->language->get('text_new_order');
        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_total_sale'] = $this->language->get('text_total_sale');
        $data['text_marketing'] = $this->language->get('text_marketing');
        $data['text_analytics'] = $this->language->get('text_analytics');
        $data['text_online'] = $this->language->get('text_online');
        $data['text_activity'] = $this->language->get('text_activity');
        $data['text_last_order'] = $this->language->get('text_last_order');
        $data['text_day'] = $this->language->get('text_day_home');
        $data['text_week'] = $this->language->get('text_week_home');
        $data['text_month'] = $this->language->get('text_month_home');
        $data['text_year'] = $this->language->get('text_year_home');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_event_summary'] = $this->language->get('text_event_summary');
        $data['text_sale'] = $this->language->get('text_sale');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_affiliates'] = $this->language->get('text_affiliates');
        $data['text_reviews'] = $this->language->get('text_reviews');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_arastta_info'] = $this->language->get('text_arastta_info');
        $data['text_products_and_sales'] = $this->language->get('text_products_and_sales');
        $data['text_best_sellers'] = $this->language->get('text_best_sellers');
        $data['text_less_sellers'] = $this->language->get('text_less_sellers');
        $data['text_most_viewed'] = $this->language->get('text_most_viewed');

        $data['text_total_sale'] = $this->language->get('text_total_sale');
        $data['text_total_sale_year'] = $this->language->get('text_total_sale_year');
        $data['text_total_order'] = $this->language->get('text_total_order');
        $data['text_total_customer'] = $this->language->get('text_total_customer');
        $data['text_total_customer_approval'] = $this->language->get('text_total_customer_approval');
        $data['text_total_review_approval'] = $this->language->get('text_total_review_approval');
        $data['text_total_affiliate'] = $this->language->get('text_total_affiliate');
        $data['text_total_affiliate_approval'] = $this->language->get('text_total_affiliate_approval');

        $data['text_today'] = $this->language->get('text_today');
        $data['text_yesterday'] = $this->language->get('text_yesterday');
        $data['text_last_week'] = $this->language->get('text_last_week');
        $data['text_last_half_mount'] = $this->language->get('text_last_half_mount');
        $data['text_mount'] = $this->language->get('text_mount');
        $data['text_this_mount'] = $this->language->get('text_this_mount');
        $data['text_last_mount'] = $this->language->get('text_last_mount');
        $data['text_submit'] = $this->language->get('text_submit');
        $data['text_clear'] = $this->language->get('text_clear');
        $data['text_from'] = $this->language->get('text_from');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_custom'] = $this->language->get('text_custom');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_product_id'] = $this->language->get('column_product_id');

        $data['token'] = $this->session->data['token'];
        
        $this->load->model('localisation/currency');

        $currency = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
        $data['symbol_left'] = $currency['symbol_left'];
        $data['symbol_right'] = $currency['symbol_right'];

        # links
        $data['link_review_waiting'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.status&order=ASC', 'SSL');
        $data['link_customer_waiting'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0', 'SSL');
        $data['link_customers'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_sales'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_orders'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_affiliates'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_affiliate_waiting'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . '&filter_approved=0', 'SSL');


        return $this->load->view('dashboard/charts.tpl', $data);
    }


        # Ajax Functions
    /**********************************************************************************************************************/

    public function orders()
    {
        $this->load->language('dashboard/charts');

        $json = $this->getChartData('getOrders');
        $json['order']['label'] = $this->language->get('text_order');

        $this->response->setOutput(json_encode($json));
    }

    public function customers()
    {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getCustomers');
        $json['order']['label'] = $this->language->get('text_customer');

        $this->response->setOutput(json_encode($json));
    }

    public function sales()
    {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getSales', true);
        $json['order']['label'] = $this->language->get('text_sale');

        $this->response->setOutput(json_encode($json));
    }

    public function affiliates()
    {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getAffiliates');
        $json['order']['label'] = $this->language->get('text_affiliates');

        $this->response->setOutput(json_encode($json));
    }

    public function reviews()
    {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getReviews');
        $json['order']['label'] = $this->language->get('text_reviews');

        $this->response->setOutput(json_encode($json));
    }

    public function rewards()
    {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getRewards');
        $json['order']['label'] = $this->language->get('text_rewards');

        $this->response->setOutput(json_encode($json));
    }

    public function getChartData($modelFunction, $currency_format = false)
    {
        $this->load->model('dashboard/charts');

        $json   = array();

        if (isset($this->request->get['start'])) {
            $start  = $this->request->get['start'];
        } else {
            $start  = '';
        }

        if (!empty($this->request->get['end'])) {
            $end  = $this->request->get['end'];
        } else {
            $end  = '';
        }

        $date_start = date_create($start)->format('Y-m-d H:i:s');
        $date_end   = date_create($end)->format('Y-m-d H:i:s');

        $diff_str   = strtotime($end) - strtotime($start);
        $diff       = floor($diff_str/3600/24) + 1;

        $range = $this->getRange($diff);

        switch ($range) {
            case 'hour':
                $results    = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'HOUR');
                $order_data = array();

                for ($i = 0; $i < 24; $i++) {
                    $order_data[$i] = array(
                        'hour' => $i,
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $i . ':00');
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['hour']] = array(
                        'hour' => $result['hour'],
                        'total' => $result['total']
                    );
                }

                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($key, $value['total']);
                }

                break;
            default:
            case 'day':
                $results    = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'DAY');
                $str_date   = substr($date_start, 0, 10);
                $order_data = array();

                for ($i = 0; $i < $diff; $i++) {
                    $date = date_create($str_date)->modify('+'.$i.' day')->format('Y-m-d');

                    $order_data[$date] = array(
                        'day' => $date,
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $date);
                }

                foreach ($results->rows as $result) {
                    $total = $result['total'];
                    if ($currency_format) {
                        $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
                    }

                    $order_data[$result['date']] = array(
                        'day' => $result['date'],
                        'total' => $total
                    );
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($i++, $value['total']);
                }

                break;
            case 'month':
                $results    = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'MONTH');
                $months     = $this->getMonths($date_start, $date_end);
                $order_data = array();

                for ($i = 0; $i < count($months); $i++) {
                    $order_data[$months[$i]] = array(
                        'month' => $months[$i],
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $months[$i]);
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['month']] = array(
                        'month' => $result['month'],
                        'total' => $result['total']
                    );
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($i++, $value['total']);
                }
                break;
            case 'year':
                $results    = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'YEAR');
                $str_date   = substr($date_start, 0, 10);
                $order_data = array();
                $diff       = floor($diff/365)+1;

                for ($i = 0; $i < $diff; $i++) {
                    $date = date_create($str_date)->modify('+'.$i.' year')->format('Y');

                    $order_data[$date] = array(
                        'year' => $date,
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $date);
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['year']] = array(
                        'year' => $result['year'],
                        'total' => $result['total']
                    );
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($i++, $value['total']);
                }
                break;
        }

        $modelFunction  = str_replace('get', 'getTotal', $modelFunction);
        $result         = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end);

        $total = $result['total'];
        if ($currency_format) {
            $total = $this->currency->format($result['total'], $this->config->get('config_currency'));
        }

        $json['order']['total'] = $total;

        return $json;
    }

    # extra functions
    ###################################################################################################################
    public function getMonths($date1, $date2)
    {
        $time1  = strtotime($date1);
        $time2  = strtotime($date2);
        $my     = date('n-Y', $time2);
        $mesi = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
       //$mesi = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');

        $months = array();
        $f      = '';

        while ($time1 < $time2) {
            if (date('n-Y', $time1) != $f) {
                $f = date('n-Y', $time1);
                if (date('n-Y', $time1) != $my && ($time1 < $time2)) {
                    $str_mese=$mesi[(date('n', $time1)-1)];
                    $months[] = $str_mese." ".date('Y', $time1);
                }
            }
            $time1 = strtotime((date('Y-n-d', $time1).' +15days'));
        }

        $str_mese=$mesi[(date('n', $time2)-1)];
        $months[] = $str_mese." ".date('Y', $time2);
        return $months;
    }

    public function getRange($diff)
    {
        if (isset($this->request->get['range']) and !empty($this->request->get['range']) and $this->request->get['range'] != 'undefined') {
            $range = $this->request->get['range'];
        } else {
            $range = 'day';
        }

        if ($diff < 365 and $range == 'year') {
            $range = 'month';
        }

        if ($diff < 28) {
            $range = 'day';
        }

        if ($diff == 1) {
            $range = 'hour';
        }

        return $range;
    }
}
