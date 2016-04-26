<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerToolSysteminfo extends Controller
{

    public function index()
    {
        $this->load->language('tool/system_info');
        
        $data = $this->language->all();

        $this->document->setTitle($data['heading_title']);

        $this->load->model('tool/system_info');

        $data['general'] = $this->model_tool_system_info->getGeneral();
        $data['permissions'] = $this->model_tool_system_info->getPermissions();
        $data['php_settings'] = $this->model_tool_system_info->getPhpSettings();
        $data['php_info'] = $this->model_tool_system_info->getPhpInfo();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('tool/system_info.tpl', $data));
    }
}
