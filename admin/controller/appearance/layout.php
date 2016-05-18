<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerAppearanceLayout extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('appearance/layout');
        $this->load->model('appearance/layout');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!empty($this->request->post['layout_id'])) {
                $this->model_appearance_layout->editLayout($this->request->post['layout_id'], $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('appearance/layout', 'token=' . $this->session->data['token'] . '&layout_id=' . $this->request->post['layout_id'], 'SSL'));
            }
        }

        $this->installScriptStyleFile();

        $data = $this->language->all();

        $heading_title = $this->language->get('heading_title');
        $text_edit = $this->language->get('text_edit');

        $this->document->setTitle($data['heading_title']);

        $this->load->model('setting/setting');
        $this->load->model('setting/store');

        $store = $this->model_setting_setting->getSetting('config', 0);

        $data['store'] = array(
          'store_id'    => 0,
          'store_name'  => $store['config_name'],
          'store_theme' => ucfirst(strtolower($store['config_template']))
        );

        $data['store_id'] = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : $this->config->get('config_store_id');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['responsive_module'] = $this->url->link('appearance/layout/module', 'token=' . $this->session->data['token'], 'SSL');

        $data['action'] = $this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL');
        $data['edit'] = $this->url->link('appearance/layout/edit', 'token=' . $this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('appearance/layout/add', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL');

        $data['extension_module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $data['removeModule'] = $this->url->link('appearance/layout/removeModule', 'token=' . $this->session->data['token'], 'SSL');

        $data['layouts'] = $this->getLayouts();
        $data['module_list_html'] = $this->getModuleListHTML();

        $theme = $this->model_appearance_layout->getTheme($data['store_id']);
        
        $this->load->language('theme/' . $theme);
        
        $data = $this->language->all($data);

        $data['heading_title'] = $heading_title;
        $data['text_edit'] = $text_edit;

        $data['text_theme'] = sprintf($this->language->get('text_theme'), ucfirst(strtolower($theme)));

        $data['positions'] = $this->model_appearance_layout->getPositions($theme);

        $data['change_layouts'] = !empty($this->request->get['layout_id']) ? $this->request->get['layout_id'] : '1';
        $data['layout_id']      = !empty($this->request->get['layout_id']) ? $this->request->get['layout_id'] : '1';

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = false;
        }

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('appearance/layout.tpl', $data));
    }

    public function add()
    {
        $this->load->language('appearance/layout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('appearance/layout');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_appearance_layout->addLayout($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('appearance/layout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('appearance/layout');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_appearance_layout->editLayout($this->request->get['layout_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    protected function getForm()
    {
        $data = $this->language->all();

        if (isset($this->request->get['layout_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $layout_info = $this->model_appearance_layout->getLayout($this->request->get['layout_id']);
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (!isset($this->request->get['layout_id'])) {
            $data['text_form'] = $this->language->get('text_add');
            $data['action'] = $this->url->link('appearance/layout/add', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['text_form'] = $this->language->get('text_edit');
            $data['action'] = $this->url->link('appearance/layout/edit', 'token=' . $this->session->data['token'] . '&layout_id=' . $this->request->get['layout_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($layout_info)) {
            $data['name'] = $layout_info['name'];
        } else {
            $data['name'] = '';
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['layout_route'])) {
            $data['layout_routes'] = $this->request->post['layout_route'];
        } elseif (isset($this->request->get['layout_id'])) {
            $data['layout_routes'] = $this->model_appearance_layout->getLayoutRoutes($this->request->get['layout_id']);
        } else {
            $data['layout_routes'] = array();
        }

        if (isset($this->request->post['layout_module'])) {
            $data['layout_modules'] = $this->request->post['layout_module'];
        } elseif (isset($this->request->get['layout_id'])) {
            $data['layout_modules'] = $this->model_appearance_layout->getLayoutModules($this->request->get['layout_id']);
        } else {
            $data['layout_modules'] = array();
        }

        $data['extensions'] = $this->getModule('justCode');

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('appearance/layout_form.tpl', $data));
    }

    public function getModuleList()
    {
        $this->response->setOutput($this->getModuleListHTML());
    }

    public function getLayoutList()
    {
        $this->load->model('appearance/layout');

        $layouts = $this->model_appearance_layout->getLayouts();

        $html = '<select type="text" name="change_layouts" id="change_layouts" class="form-control with-nav">';

        foreach ($layouts as $layout) {
            ($this->request->post['change_layouts'] != $layout['layout_id']) ? $selected = '' : $selected = 'selected=selected';
            $html += '	<option value="' + $layout['layout_id'] + '" ' + $selected +' >' + $layout['name'] + '</option>';
        }

        $html += '</select>';

        $this->response->setOutput($html);
    }

    public function saveModule()
    {
        $json = array(
            'success' => '1',
            'link'    => $this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL')
        );

        if ($this->validate()) {
            $this->load->model('appearance/layout');

            $data = array(
                'layout_id' => $this->request->post['layout_id'],
                'position' => $this->request->post['layout_position'],
                'code' => $this->request->post['module_code'],
                'sort_order' => '0',
            );

            $this->model_appearance_layout->addLayoutModule($data);
        } else {
            $json['success'] = '0';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeModule()
    {
        $json = array(
            'success' => '1'
        );

        if ($this->validate()) {
            $this->load->model('appearance/layout');

            $this->model_appearance_layout->removeModule($this->request->post['module_id']);
        } else {
            $json['success'] = '0';
            $json['error_warning'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeLayoutModule()
    {
        $json = array(
            'success' => '1'
        );

        if ($this->validate()) {
            $this->load->model('appearance/layout');

            $this->model_appearance_layout->removeLayoutModule($this->request->post);
        } else {
            $json['success'] = '0';
            $json['error_warning'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeLayout()
    {
        $json = array(
            'success' => '1'
        );

        if ($this->validate()) {
            $this->load->model('appearance/layout');

            $this->model_appearance_layout->removeLayout($this->request->post['layout_id']);
        } else {
            $json['success'] = '0';
            $json['error_warning'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getLayouts()
    {
        $this->load->model('appearance/layout');

        $layouts = $this->model_appearance_layout->getLayouts();

        foreach ($layouts as $layout) {
            $layout_modules = $this->model_appearance_layout->getLayoutModules($layout['layout_id']);

            $result[$layout['layout_id']] = array(
                'layout_id' => $layout['layout_id'],
                'name' => $layout['name'],
                'modules' => $layout_modules
            );
        }

        return $result;
    }

    public function module()
    {
        $this->load->language('appearance/layout');

        $this->load->model('appearance/layout');

        $data = $this->language->all();

        $this->installScriptStyleFile();

        $data['extensions'] = $this->getModule();

        $data['layout_position'] = $this->request->get['position'];
        $data['layout_id'] = $this->request->get['layout_id'];

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('appearance/layout_module.tpl', $data));
    }
    
    public function getModule($type = 'all')
    {
        $this->load->model('extension/extension');
        $this->load->model('extension/module');

        $data['extensions'] = array();

        $extensions = $this->model_extension_extension->getInstalled('module');

        if ($type == 'all') {
            foreach ($extensions as $key => $value) {
                if (!file_exists(DIR_ADMIN . 'controller/module/' . $value . '.php')) {
                    $this->model_extension_extension->uninstall('module', $value);

                    unset($extensions[$key]);

                    $this->model_extension_module->deleteModulesByCode($value);
                }
            }

            $files = glob(DIR_ADMIN . 'controller/module/*.php');

            if ($files) {
                foreach ($files as $file) {
                    $extension = basename($file, '.php');

                    $this->load->language('module/' . $extension);

                    if (!in_array($extension, $extensions)) {
                        $this->checkModuleInstalled($extension);
                        $extensions[] = $extension;
                    }

                    $instance = false;
                    if (strpos(file_get_contents($file), 'this->model_extension_module->addModule(') !== false) {
                        $instance = true;
                    }

                    $module_data = array();

                    $modules = $this->model_extension_module->getModulesByCode($extension);

                    foreach ($modules as $module) {
                        $status = unserialize($module['setting']);
                        $module_data[] = array(
                            'module_id' => $module['module_id'],
                            'code'      => $extension . '.' .  $module['module_id'],
                            'name'      => $this->language->get('heading_title') . ' &gt; ' . $module['name'],
                            'status'    => !empty($status['status']) ? (bool)$status['status'] : false,
                            'edit'      => $this->url->link('module/' . $extension, 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], 'SSL'),
                            'delete'    => $this->url->link('extension/module/delete', 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], 'SSL')
                        );
                    }

                    $data['extensions'][] = array(
                        'name'      => $this->language->get('heading_title'),
                        'module'    => $module_data,
                        'install'   => $this->url->link('extension/module/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL'),
                        'uninstall' => $this->url->link('extension/module/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL'),
                        'installed' => in_array($extension, $extensions),
                        'status'    => !empty($status['status']) ? $status['status'] : false,
                        'code'      => $extension,
                        'extension'     => $extension,
                        'link'      => $this->url->link('module/' . $extension, 'token=' . $this->session->data['token'], 'SSL'),
                        'instance'  => $instance
                    );
                }
            }
        } else {
            foreach ($extensions as $code) {
                $this->load->language('module/' . $code);

                $module_data = array();

                $modules = $this->model_extension_module->getModulesByCode($code);

                foreach ($modules as $module) {
                    $module_data[] = array(
                        'name' => $this->language->get('heading_title') . ' &gt; ' . $module['name'],
                        'code' => $code . '.' .  $module['module_id']
                    );
                }

                if ($this->config->has($code . '_status') || $module_data) {
                    $data['extensions'][] = array(
                        'name'   => $this->language->get('heading_title'),
                        'code'   => $code,
                        'module' => $module_data
                    );
                }
            }
        }

        return  $data['extensions'];
    }

    public function checkModuleInstalled($extension)
    {
        $this->load->model('extension/extension');
        $this->model_extension_extension->install('module', $extension);

        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'module/' . $extension);
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'module/' . $extension);

        $this->load->controller('module/' . $extension . '/install');
    }

    private function getModuleListHTML()
    {
        $this->load->language('appearance/layout');

        $data['button_new'] = $this->language->get('button_new');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_remove'] = $this->language->get('button_remove');

        $data['extensions'] = $this->getModule();
        
        return $this->load->view('appearance/layout_module_list.tpl', $data);
    }

    public function installScriptStyleFile()
    {
        $this->document->addStyle('view/stylesheet/layout.css');
        $this->document->addStyle('view/javascript/jquery/layout/jquery-ui.css');

        $this->document->addScript('view/javascript/layout/layout.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery-ui.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery-lockfixed.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery.ui.touch-punch.js');
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'appearance/layout')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
