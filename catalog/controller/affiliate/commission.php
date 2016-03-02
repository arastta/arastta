<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerAffiliateCommission extends Controller
{
    public function index()
    {
        if (!$this->affiliate->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('affiliate/commission', '', 'SSL');

            $this->response->redirect($this->url->link('affiliate/login', '', 'SSL'));
        }

        $this->load->language('affiliate/commission');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('affiliate/account', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_commission'),
            'href' => $this->url->link('affiliate/commission', '', 'SSL')
        );

        $this->load->model('affiliate/commission');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

        $data['text_balance'] = $this->language->get('text_balance');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['commissions'] = array();

        $filter_data = array(
            'sort'  => 't.date_added',
            'order' => 'DESC',
            'start' => ($page - 1) * 10,
            'limit' => 10
        );

        $commission_total = $this->model_affiliate_commission->getTotalCommissions();

        $results = $this->model_affiliate_commission->getCommissions($filter_data);

        foreach ($results as $result) {
            $data['commissions'][] = array(
                'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'description' => $result['description'],
                'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $commission_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('affiliate/commission', 'page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($commission_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($commission_total - 10)) ? $commission_total : ((($page - 1) * 10) + 10), $commission_total, ceil($commission_total / 10));

        $data['balance'] = $this->currency->format($this->model_affiliate_commission->getBalance());

        $data['continue'] = $this->url->link('affiliate/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/commission.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/affiliate/commission.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/affiliate/commission.tpl', $data));
        }
    }
}
