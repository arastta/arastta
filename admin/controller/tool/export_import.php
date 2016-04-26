<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerToolExportImport extends Controller
{

    private $error = array();
    private $version = '2.27';
    private $update_date = '18 October 2015';
    
    public function index()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('tool/export_import');
        $this->getForm();
    }
    
    public function upload()
    {
        $this->load->language('tool/export_import');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateUploadForm())) {
            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];
                $incremental = ($this->request->post['incremental']) ? true : false;
                if ($this->model_tool_export_import->upload($file, $this->request->post['incremental'])===true) {
                    $this->session->data['success'] = $this->language->get('text_success');
                    $this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'));
                } else {
                    $this->error['warning']  = $this->language->get('error_upload');
                    $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                }
            }
        }

        $this->getForm();
    }

    protected function returnBytes($val)
    {
        $val = trim($val);
    
        switch (strtolower(substr($val, -1))) {
            case 'm':
                $val = (int)substr($val, 0, -1) * 1048576;
                break;
            case 'k':
                $val = (int)substr($val, 0, -1) * 1024;
                break;
            case 'g':
                $val = (int)substr($val, 0, -1) * 1073741824;
                break;
            case 'b':
                switch (strtolower(substr($val, -2, 1))) {
                    case 'm':
                        $val = (int)substr($val, 0, -2) * 1048576;
                        break;
                    case 'k':
                        $val = (int)substr($val, 0, -2) * 1024;
                        break;
                    case 'g':
                        $val = (int)substr($val, 0, -2) * 1073741824;
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }
        return $val;
    }

    public function download()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/export_import');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateDownloadForm()) {
            $export_type = $this->request->post['export_type'];
            switch ($export_type) {
                case 'c':
                case 'p':
                    $min = null;
                    if (isset($this->request->post['min']) && ($this->request->post['min']!='')) {
                        $min = $this->request->post['min'];
                    }
                    $max = null;
                    if (isset($this->request->post['max']) && ($this->request->post['max']!='')) {
                        $max = $this->request->post['max'];
                    }
                    if (($min==null) || ($max==null)) {
                        $this->model_tool_export_import->download($export_type, null, null, null, null);
                    } elseif ($this->request->post['range_type'] == 'id') {
                        $this->model_tool_export_import->download($export_type, null, null, $min, $max);
                    } else {
                        $this->model_tool_export_import->download($export_type, $min*($max-1-1), $min, null, null);
                    }
                    break;
                case 'o':
                    $this->model_tool_export_import->download('o', null, null, null, null);
                    break;
                case 'a':
                    $this->model_tool_export_import->download('a', null, null, null, null);
                    break;
                case 'f':
                    if ($this->model_tool_export_import->existFilter()) {
                        $this->model_tool_export_import->download('f', null, null, null, null);
                        break;
                    }
                    break;
                default:
                    break;
            }
            $this->response->redirect($this->url->link('tool/export_import', 'token='.$this->request->get['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function settings()
    {
        $this->load->language('tool/export_import');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('tool/export_import');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateSettingsForm())) {
            if (!isset($this->request->post['export_import_settings_use_export_cache'])) {
                $this->request->post['export_import_settings_use_export_cache'] = '0';
            }
            if (!isset($this->request->post['export_import_settings_use_import_cache'])) {
                $this->request->post['export_import_settings_use_import_cache'] = '0';
            }
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('export_import', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success_settings');
            $this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'));
        }
        $this->getForm();
    }

    protected function getForm()
    {
        $data = $this->language->all();

        $data['exist_filter'] = $this->model_tool_export_import->existFilter();

        $data['text_export_type_category'] = ($data['exist_filter']) ? $this->language->get('text_export_type_category') : $this->language->get('text_export_type_category_old');
        $data['text_export_type_product'] = ($data['exist_filter']) ? $this->language->get('text_export_type_product') : $this->language->get('text_export_type_product_old');
        
        $data['help_import'] = ($data['exist_filter']) ? $this->language->get('help_import') : $this->language->get('help_import_old');
        $data['error_post_max_size'] = str_replace('%1', ini_get('post_max_size'), $this->language->get('error_post_max_size'));
        $data['error_upload_max_filesize'] = str_replace('%1', ini_get('upload_max_filesize'), $this->language->get('error_upload_max_filesize'));

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
            if (!empty($this->session->data['export_import_nochange'])) {
                $data['error_warning'] .= $this->language->get('text_nochange');
            }
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        unset($this->session->data['export_import_error']);
        unset($this->session->data['export_import_nochange']);
        
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['back'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
        $data['button_back'] = $this->language->get('button_back');
        $data['import'] = $this->url->link('tool/export_import/upload', 'token=' . $this->session->data['token'], 'SSL');
        $data['export'] = $this->url->link('tool/export_import/download', 'token=' . $this->session->data['token'], 'SSL');
        $data['settings'] = $this->url->link('tool/export_import/settings', 'token=' . $this->session->data['token'], 'SSL');
        $data['post_max_size'] = $this->returnBytes(ini_get('post_max_size'));
        $data['upload_max_filesize'] = $this->returnBytes(ini_get('upload_max_filesize'));

        if (isset($this->request->post['export_type'])) {
            $data['export_type'] = $this->request->post['export_type'];
        } else {
            $data['export_type'] = 'p';
        }

        if (isset($this->request->post['range_type'])) {
            $data['range_type'] = $this->request->post['range_type'];
        } else {
            $data['range_type'] = 'id';
        }

        if (isset($this->request->post['min'])) {
            $data['min'] = $this->request->post['min'];
        } else {
            $data['min'] = '';
        }

        if (isset($this->request->post['max'])) {
            $data['max'] = $this->request->post['max'];
        } else {
            $data['max'] = '';
        }

        if (isset($this->request->post['incremental'])) {
            $data['incremental'] = $this->request->post['incremental'];
        } else {
            $data['incremental'] = '1';
        }

        if (isset($this->request->post['export_import_settings_use_option_id'])) {
            $data['settings_use_option_id'] = $this->request->post['export_import_settings_use_option_id'];
        } elseif ($this->config->get('export_import_settings_use_option_id')) {
            $data['settings_use_option_id'] = '1';
        } else {
            $data['settings_use_option_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_option_value_id'])) {
            $data['settings_use_option_value_id'] = $this->request->post['export_import_settings_use_option_value_id'];
        } elseif ($this->config->get('export_import_settings_use_option_value_id')) {
            $data['settings_use_option_value_id'] = '1';
        } else {
            $data['settings_use_option_value_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_attribute_group_id'])) {
            $data['settings_use_attribute_group_id'] = $this->request->post['export_import_settings_use_attribute_group_id'];
        } elseif ($this->config->get('export_import_settings_use_attribute_group_id')) {
            $data['settings_use_attribute_group_id'] = '1';
        } else {
            $data['settings_use_attribute_group_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_attribute_id'])) {
            $data['settings_use_attribute_id'] = $this->request->post['export_import_settings_use_attribute_id'];
        } elseif ($this->config->get('export_import_settings_use_attribute_id')) {
            $data['settings_use_attribute_id'] = '1';
        } else {
            $data['settings_use_attribute_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_filter_group_id'])) {
            $data['settings_use_filter_group_id'] = $this->request->post['export_import_settings_use_filter_group_id'];
        } elseif ($this->config->get('export_import_settings_use_filter_group_id')) {
            $data['settings_use_filter_group_id'] = '1';
        } else {
            $data['settings_use_filter_group_id'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_filter_id'])) {
            $data['settings_use_filter_id'] = $this->request->post['export_import_settings_use_filter_id'];
        } elseif ($this->config->get('export_import_settings_use_filter_id')) {
            $data['settings_use_filter_id'] = '1';
        } else {
            $data['settings_use_filter_id'] = '0';
        }
        
        if (isset($this->request->post['export_import_settings_use_export_cache'])) {
            $data['settings_use_export_cache'] = $this->request->post['export_import_settings_use_export_cache'];
        } elseif ($this->config->get('export_import_settings_use_export_cache')) {
            $data['settings_use_export_cache'] = '1';
        } else {
            $data['settings_use_export_cache'] = '0';
        }

        if (isset($this->request->post['export_import_settings_use_import_cache'])) {
            $data['settings_use_import_cache'] = $this->request->post['export_import_settings_use_import_cache'];
        } elseif ($this->config->get('export_import_settings_use_import_cache')) {
            $data['settings_use_import_cache'] = '1';
        } else {
            $data['settings_use_import_cache'] = '0';
        }

        $min_product_id = $this->model_tool_export_import->getMinProductId();
        $max_product_id = $this->model_tool_export_import->getMaxProductId();
        $count_product = $this->model_tool_export_import->getCountProduct();
        $min_category_id = $this->model_tool_export_import->getMinCategoryId();
        $max_category_id = $this->model_tool_export_import->getMaxCategoryId();
        $count_category = $this->model_tool_export_import->getCountCategory();
        
        $data['min_product_id'] = $min_product_id;
        $data['max_product_id'] = $max_product_id;
        $data['count_product'] = $count_product;
        $data['min_category_id'] = $min_category_id;
        $data['max_category_id'] = $max_category_id;
        $data['count_category'] = $count_category;

        $data['token'] = $this->session->data['token'];

        $this->document->addStyle('view/stylesheet/export_import.css');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('tool/export_import.tpl', $data));
    }

    protected function validateDownloadForm()
    {
        if (!$this->user->hasPermission('access', 'tool/export_import')) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        if (!$this->config->get('export_import_settings_use_option_id')) {
            $option_names = $this->model_tool_export_import->getOptionNameCounts();
            foreach ($option_names as $option_name) {
                if ($option_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $option_name['name'], $this->language->get('error_option_name'));
                    return false;
                }
            }
        }

        if (!$this->config->get('export_import_settings_use_option_value_id')) {
            $option_value_names = $this->model_tool_export_import->getOptionValueNameCounts();
            foreach ($option_value_names as $option_value_name) {
                if ($option_value_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $option_value_name['name'], $this->language->get('error_option_value_name'));
                    return false;
                }
            }
        }

        if (!$this->config->get('export_import_settings_use_attribute_group_id')) {
            $attribute_group_names = $this->model_tool_export_import->getAttributeGroupNameCounts();
            foreach ($attribute_group_names as $attribute_group_name) {
                if ($attribute_group_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $attribute_group_name['name'], $this->language->get('error_attribute_group_name'));
                    return false;
                }
            }
        }

        if (!$this->config->get('export_import_settings_use_attribute_id')) {
            $attribute_names = $this->model_tool_export_import->getAttributeNameCounts();
            foreach ($attribute_names as $attribute_name) {
                if ($attribute_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $attribute_name['name'], $this->language->get('error_attribute_name'));
                    return false;
                }
            }
        }

        if (!$this->config->get('export_import_settings_use_filter_group_id')) {
            $filter_group_names = $this->model_tool_export_import->getFilterGroupNameCounts();
            foreach ($filter_group_names as $filter_group_name) {
                if ($filter_group_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $filter_group_name['name'], $this->language->get('error_filter_group_name'));
                    return false;
                }
            }
        }

        if (!$this->config->get('export_import_settings_use_filter_id')) {
            $filter_names = $this->model_tool_export_import->getFilterNameCounts();
            foreach ($filter_names as $filter_name) {
                if ($filter_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $filter_name['name'], $this->language->get('error_filter_name'));
                    return false;
                }
            }
        }
        
        return true;
    }

    protected function validateUploadForm()
    {
        if (!$this->user->hasPermission('modify', 'tool/export_import')) {
            $this->error['warning'] = $this->language->get('error_permission');
        } elseif (!isset($this->request->post['incremental'])) {
            $this->error['warning'] = $this->language->get('error_incremental');
        } elseif ($this->request->post['incremental'] != '0') {
            if ($this->request->post['incremental'] != '1') {
                $this->error['warning'] = $this->language->get('error_incremental');
            }
        }

        if (!isset($this->request->files['upload']['name'])) {
            if (isset($this->error['warning'])) {
                $this->error['warning'] .= "<br /\n" . $this->language->get('error_upload_name');
            } else {
                $this->error['warning'] = $this->language->get('error_upload_name');
            }
        } else {
            $ext = strtolower(pathinfo($this->request->files['upload']['name'], PATHINFO_EXTENSION));
            if (($ext != 'xls') && ($ext != 'xlsx') && ($ext != 'ods')) {
                if (isset($this->error['warning'])) {
                    $this->error['warning'] .= "<br /\n" . $this->language->get('error_upload_ext');
                } else {
                    $this->error['warning'] = $this->language->get('error_upload_ext');
                }
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateSettingsForm()
    {
        if (!$this->user->hasPermission('access', 'tool/export_import')) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        if (empty($this->request->post['export_import_settings_use_option_id'])) {
            $option_names = $this->model_tool_export_import->getOptionNameCounts();
            foreach ($option_names as $option_name) {
                if ($option_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $option_name['name'], $this->language->get('error_option_name'));
                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_option_value_id'])) {
            $option_value_names = $this->model_tool_export_import->getOptionValueNameCounts();
            foreach ($option_value_names as $option_value_name) {
                if ($option_value_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $option_value_name['name'], $this->language->get('error_option_value_name'));
                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_attribute_group_id'])) {
            $attribute_group_names = $this->model_tool_export_import->getAttributeGroupNameCounts();
            foreach ($attribute_group_names as $attribute_group_name) {
                if ($attribute_group_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $attribute_group_name['name'], $this->language->get('error_attribute_group_name'));
                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_attribute_id'])) {
            $attribute_names = $this->model_tool_export_import->getAttributeNameCounts();
            foreach ($attribute_names as $attribute_name) {
                if ($attribute_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $attribute_name['name'], $this->language->get('error_attribute_name'));
                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_filter_group_id'])) {
            $filter_group_names = $this->model_tool_export_import->getFilterGroupNameCounts();
            foreach ($filter_group_names as $filter_group_name) {
                if ($filter_group_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $filter_group_name['name'], $this->language->get('error_filter_group_name'));
                    return false;
                }
            }
        }

        if (empty($this->request->post['export_import_settings_use_filter_id'])) {
            $filter_names = $this->model_tool_export_import->getFilterNameCounts();
            foreach ($filter_names as $filter_name) {
                if ($filter_name['count'] > 1) {
                    $this->error['warning'] = str_replace('%1', $filter_name['name'], $this->language->get('error_filter_name'));
                    return false;
                }
            }
        }

        return true;
    }
}
