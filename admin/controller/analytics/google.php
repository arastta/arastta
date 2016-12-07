<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Arastta\Component\Form\Form as AForm;

class ControllerAnalyticsGoogle extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('analytics/google');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('google', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/extension', 'filter_type=analytics&token=' . $this->session->data['token'], 'SSL'));
        }

        // Add all language text
        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('analytics/google', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/extension', 'filter_type=analytics&token=' . $this->session->data['token'], 'SSL');

        $data['form_fields'] = $this->getFormFields($data['action']);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->output('analytics/google', $data));
    }

    protected function getFormFields($action)
    {
        $action = str_replace('amp;', '', $action);

        $public['value'] = $this->config->get('google_analytics', '');
        $public['placeholder'] = $this->language->get('entry_code');
        $public['required'] = $this->language->get('required');

        $option_text = array(
            'yes' => $this->language->get('text_enabled'),
            'no'  => $this->language->get('text_disabled')
        );

        $status['value'] = $this->config->get('google_analytics_status', 0);
        $status['labelclass'] = 'radio-inline';

        $form = new AForm('form-google-analytics', $action);

        $form->addElement(new Arastta\Component\Form\Element\Textarea($this->language->get('entry_code'), 'google_analytics', $public));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'google_analytics_status', $status, $option_text));

        return $form->render(true);
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'analytics/google')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['google_analytics'])) {
            $this->error['warning'] = $this->language->get('error_code');
        }

        return !$this->error;
    }
}
