<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonEdit extends Controller
{
    private $error = array();

    public function changeStatus()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $type = basename($this->request->post['route']);

            $is_extension = false;

            $url = '';

            if ($type == 'extension' || $type == 'feed' || $type == 'payment' || $type == 'shipping' || $type == 'total') {
                $is_extension = true;

                if (!empty($this->request->post['filter_type'])) {
                    $url =  'filter_type=' . $this->request->post['filter_type'] . '&';
                }
            }

            $this->language->load($this->request->post['route']);

            $this->load->model('common/edit');

            $this->model_common_edit->changeStatus($type, $this->request->post['selected'], (int)$this->request->post['status'], $is_extension);

            $this->cache->delete($type);

            $this->session->data['success'] = $this->language->get('text_success');

            $json['redirect'] = str_replace('&amp;', '&', $this->url->link($this->request->post['route'], $url . 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $json['warning'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if ($this->request->post['route'] != 'extension/extension' && !$this->user->hasPermission('modify', $this->request->post['route'])) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['selected'])) {
            $this->error['warning'] = $this->language->get('error_selected');

        }

        if (is_null($this->request->post['status'])) {
            $this->error['warning'] = $this->language->get('error_status');
        }

        return !$this->error;
    }
}
