<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Arastta\Component\Form\Form as AForm;

class ControllerAntifraudMaxmind extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('antifraud/maxmind');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('maxmind', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/extension', 'filter_type=antifraud&token=' . $this->session->data['token'], 'SSL'));
        }

        // Add all language text
        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('antifraud/maxmind', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/extension', 'filter_type=antifraud&token=' . $this->session->data['token'], 'SSL');

        $data['form_fields'] = $this->getFormFields($data['action']);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->output('antifraud/maxmind', $data));
    }

    protected function getFormFields($action)
    {
        $action = str_replace('amp;', '', $action);

        $key['value'] = $this->config->get('maxmind_antifraud_key', '');
        $key['placeholder'] = $this->language->get('entry_key');
        $key['required'] = $this->language->get('required');

        $score['value'] = $this->config->get('maxmind_antifraud_score', '');
        $score['placeholder'] = $this->language->get('entry_score');
        $score['required'] = $this->language->get('required');

        $this->load->model('localisation/order_status');

        $order_option = array();
        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();

        foreach ($order_statuses as $order_status) {
            $order_option[$order_status['order_status_id']] = $order_status['name'];
        }

        $order_text = array(
            'value' => $this->config->get('maxmind_antifraud_order_status_id', 0),
            'selected'  => $this->config->get('maxmind_antifraud_order_status_id', 0)
        );

        $status_text = array(
            'yes' => $this->language->get('text_enabled'),
            'no'  => $this->language->get('text_disabled')
        );

        $status['value'] = $this->config->get('maxmind_antifraud_status', 0);
        $status['labelclass'] = 'radio-inline';

        $form = new AForm('form-maxmind-antifraud', $action);

        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_key'), 'maxmind_antifraud_key', $key));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_score'), 'maxmind_antifraud_score', $score));
        $form->addElement(new Arastta\Component\Form\Element\Select($this->language->get('entry_order'), 'maxmind_antifraud_order_status_id', $order_option, $order_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'maxmind_antifraud_status', $status, $status_text));

        return $form->render(true);
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'antifraud/maxmind')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['maxmind_antifraud_key'])) {
            $this->error['warning'] = $this->language->get('error_key');
        }

        if (empty($this->request->post['maxmind_antifraud_score'])) {
            $this->error['warning'] = $this->language->get('error_score');
        }

        return !$this->error;
    }
}
