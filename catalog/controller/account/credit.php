<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerAccountCredit extends Controller
{
    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/credit', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/credit');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_credit'),
            'href' => $this->url->link('account/credit', '', 'SSL')
        );

        $this->load->model('account/credit');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

        $data['text_total'] = $this->language->get('text_total');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = array();

        $filter_data = array(
            'sort'  => 'date_added',
            'order' => 'DESC',
            'start' => ($page - 1) * 10,
            'limit' => 10
        );

        $credit_total = $this->model_account_credit->getTotalCredits();

        $results = $this->model_account_credit->getCredits($filter_data);

        foreach ($results as $result) {
            $data['credits'][] = array(
                'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'description' => $result['description'],
                'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $credit_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('account/credit', 'page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));

        $data['total'] = $this->currency->format($this->customer->getBalance());

        $data['continue'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/credit.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/credit.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/credit.tpl', $data));
        }
    }
}
