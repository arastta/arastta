<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerAppearanceCustomizer extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('appearance/customizer');

        $this->load->model('appearance/customizer');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_appearance_customizer->saveCustomizer('customizer', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $data['title'] = $this->document->getTitle();

        #Get All Language Text
        $data = $this->language->all($data);

        $data['base'] = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;

        $this->installStylesheet();
        $this->installJavascript();

        $data['sections'] = $this->getCustomizerItem();

        $data['fonts'] = $this ->getFonts();

        $this->load->model('tool/image');

        $data['image_fullpath'] = ($this->request->server['HTTPS']) ? HTTPS_IMAGE : HTTP_IMAGE;
        $data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG . 'image/' . $this->config->get('config_icon');
        } else {
            $data['icon'] = '';
        }

        $data['direction'] = $this->language->get('direction');
        $data['language'] = $this->session->data['admin_language'];

        $data['action'] = $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'], 'SSL');
        $data['reset'] = $this->url->link('appearance/customizer/reset', 'token=' . $this->session->data['token'], 'SSL');
        $data['changeTheme'] = $this->url->link('appearance/customizer/changeTheme', 'token=' . $this->session->data['token'], 'SSL');

        $data['frontend'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG;
        $data['backend']  = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;

        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();

        $data['default_data'] = $this->model_appearance_customizer->getDefaultData('customizer');

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

        if (isset($this->request->post['button_back'])) {
            $data['button_back'] = $this->request->post['button_back'];
        } elseif (isset($this->request->get['button_back'])) {
            $data['button_back'] = base64_decode($this->request->get['button_back']);
        } else {
            $data['button_back'] = $this->request->server['HTTP_REFERER'];
        }

        if (isset($this->request->post['config_template'])) {
            $data['config_template'] = $this->request->post['config_template'];
        } else {
            $data['config_template'] = $this->config->get('config_template');
        }

        if (isset($this->request->get['theme'])) {
            $data['theme'] = $this->request->get['theme'];
        } elseif (isset($this->request->post['config_template'])) {
            $data['theme'] = $this->request->post['config_template'];
        } else {
            $data['theme'] = $this->config->get('config_template');
        }

        $data['templates'] = array();

        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $data['templates'][] = basename($directory);
        }

        $data['token'] = $this->session->data['token'];

        $this->response->setOutput($this->load->view('appearance/customizer.tpl', $data));
    }

    public function reset()
    {
        $this->load->model('appearance/customizer');

        if ($this->validate()) {
            $this->model_appearance_customizer->resetCustomizer('customizer');
        }

        $json['succes'] = true;

        $this->session->data['success'] = $this->language->get('text_remove');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function installStylesheet()
    {
        $this->document->addStyle('view/javascript/bootstrap/arastta/arastta.css');
        $this->document->addStyle('view/javascript/font-awesome/css/font-awesome.min.css');
        $this->document->addStyle('view/stylesheet/customizer.css');
        $this->document->addStyle('view/stylesheet/dashicons.css');
        $this->document->addStyle('view/stylesheet/color-picker.css');
    }

    public function installJavascript()
    {
        $this->document->addScript('view/javascript/jquery/jquery-2.1.1.min.js');
        $this->document->addScript('view/javascript/bootstrap/js/bootstrap.min.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery-ui.js');
        $this->document->addScript('view/javascript/colorpicker/color-picker.js');
        $this->document->addScript('view/javascript/colorpicker/iris.min.js');

        if (is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/javascript/customizer.js')) {
            $this->document->addScript('../catalog/view/theme/' . $this->config->get('config_template') . '/javascript/customizer.js');
        }
    }

    public function getCustomizerItem()
    {
        $use_template = $this->config->get('config_template');

        $data['general'] = array(
            "title" => "text_general_title",
            "description" => "text_general_description",
            "control" => array(
                "sitename" => array(
                    "type" => "text",
                    "label" => "text_general_sitename_label",
                    "default" => $this->config->get('config_name'),
                    "selector" => "#logo a"
                ),
                "font" => array(
                    "type" => "font",
                    "label" => "text_general_font_label",
                    "default" => "",
                    "selector" => "body"
                ),
                "layout_width" => array(
                    "type" => "select",
                    "choices" => array( "1170px" => "text_general_layout_width", "100%" => "text_general_layout_full_width"),
                    "label" => "text_general_layout_label",
                    "default" => "100%",
                    "selector" => "body"
                )
            )
        );

        $data['colors'] = array(
            "title" => "text_colors_title",
            "control" => array(
                "container_background-color" => array(
                    "type" => "color",
                    "label" => "text_colors_container_background_label",
                    "default" => "#fff",
                    "selector" => "body"
                ),
                "container-color_color" => array(
                    "type" => "color",
                    "label" => "text_colors_container_color_label",
                    "default" => "#666",
                    "selector" => "body"
                )
            )
        );

        $this->load->model('tool/image');

        $default_image = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);

        $data['images'] = array(
            "title" => "text_images_title",
            "description" => "text_images_description",
            "control" => array(
                "logo" => array(
                    "type" => "image",
                    "label" => "text_images_logo_label",
                    "default" => $default_image,
                    "default_raw" => $this->config->get('config_logo'),
                    "selector" => "#logo a img"
                ),
                "container_background-image" => array(
                    "type" => "image",
                    "label" => "text_images_container_background_label",
                    "default" => "",
                    "selector" => "body"
                )
            )
        );

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            unset($data['general']['control']['sitename']);
        } else {
            unset($data['images']['control']['logo']);
        }

        if (is_file(DIR_CATALOG . 'view/theme/' . $use_template . '/customizer.json')) {
            $json = file_get_contents(DIR_CATALOG . 'view/theme/' . $use_template . '/customizer.json');
            $items = json_decode($json, true);

            if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
                unset($items['general']['control']['sitename']);
            } else {
                unset($items['images']['control']['logo']);
            }

            foreach ($items as $item_name => $item_value) {
                if ($item_name == 'general' || $item_name == 'colors' || $item_name == 'images') {
                    if (!empty($item_value['title'])) {
                        $data[$item_name]['title'] = $item_value['title'];
                    }

                    if (!empty($item_value['description'])) {
                        $data[$item_name]['description'] = $item_value['description'];
                    }

                    if (!empty($item_value['control'])) {
                        foreach ($item_value['control'] as $control => $value) {
                            foreach ($value as $key => $val) {
                                $data[$item_name]['control'][$control][$key] = $val;
                            }
                        }
                    }
                } else {
                    $data[$item_name] = $item_value;
                }
            }
        }

        $data['custom'] = array(
            "title" => "text_custom_title",
            "description" => "text_custom_description",
            "control" => array(
                "custom-css" => array(
                    "type" => "textarea",
                    "label" => "text_custom_css",
                    "default" => "",
                    "selector" => "head"
                ),
                "custom-js" => array(
                    "type" => "textarea",
                    "label" => "text_custom_js",
                    "default" => "",
                    "selector" => "head"
                ),
            )
        );

        $result = $this->getLanguageText($data, $use_template);

        return $result;
    }
    
    public function getFonts()
    {
        $json =  file_get_contents(DIR_SYSTEM . 'helper/fonts.json');
        
        $data = json_decode($json, true);
        
        $fonts['system'] = array(
            array(
                'family' => 'Georgia, serif'
            ),
            array(
                'family' => 'Helvetica, sans-serif'
            )
        );

        foreach ($data['items'] as $font_item) {
            $fonts['google'][] = array(
                'family' => $font_item['family']
            );
        }
        
        return $fonts;
    }

    public function changeTheme()
    {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('appearance/customizer');

            $this->model_appearance_customizer->changeTheme($this->request->post['template']);

            $json['success'] = true;

            //$json['html'] = $this->load->controller('appearance/customizer/menu');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function menu()
    {
        $this->load->language('appearance/customizer');

        $this->load->model('appearance/customizer');

        $this->config->set('config_template', $this->request->post['template']);
        #Get All Language Text
        $data = $this->language->all();

        $data['base'] = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;

        $data['sections'] = $this->getCustomizerItem();

        $data['fonts'] = $this ->getFonts();

        $this->load->model('tool/image');

        $data['image_fullpath'] = ($this->request->server['HTTPS']) ? HTTPS_IMAGE : HTTP_IMAGE;
        $data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG  . 'image/' . $this->config->get('config_icon');
        } else {
            $data['icon'] = '';
        }

        $data['direction'] = $this->language->get('direction');
        $data['language'] = $this->session->data['admin_language'];

        $data['action'] = $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'], 'SSL');
        $data['reset'] = $this->url->link('appearance/customizer/reset', 'token=' . $this->session->data['token'], 'SSL');
        $data['back'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
        $data['changeTheme'] = $this->url->link('appearance/customizer/changeTheme', 'token=' . $this->session->data['token'], 'SSL');

        $data['frontend'] = ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG;
        $data['backend']  = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;

        $data['default_data'] = $this->model_appearance_customizer->getDefaultData('customizer');

        if (isset($this->request->post['config_template'])) {
            $data['config_template'] = $this->request->post['config_template'];
        } else {
            $data['config_template'] = $this->config->get('config_template');
        }

        $data['templates'] = array();

        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $data['templates'][] = basename($directory);
        }

        $data['token'] = $this->session->data['token'];

        return $this->load->view('appearance/customizer_menu.tpl', $data);
    }

    public function getLanguageText($data, $use_template)
    {
        $this->load->language('theme/default');
        $this->load->language('theme/' . $use_template);

        $language = $this->language->all();

        foreach ($data as $key => $value) {
            if (isset($data[$key]['title']) && isset($language[$value['title']])) {
                $data[$key]['title'] = $language[$value['title']];
            }

            if (isset($data[$key]['description']) && isset($language[$value['description']])) {
                $data[$key]['description'] = $language[$value['description']];
            }

            foreach ($value['control'] as $cont_key => $cont_val) {
                if (isset($data[$key]['control'][$cont_key]['label']) && isset($language[$cont_val['label']])) {
                    $data[$key]['control'][$cont_key]['label'] = $language[$cont_val['label']];
                }

                if (isset($data[$key]['control'][$cont_key]['description']) && isset($language[$cont_val['description']])) {
                    $data[$key]['control'][$cont_key]['description'] = $language[$cont_val['description']];
                }

                if (isset($data[$key]['control'][$cont_key]['choices'])) {
                    foreach ($data[$key]['control'][$cont_key]['choices'] as $choices_key => $choices_val) {
                        if (isset($language[$choices_val])) {
                            $data[$key]['control'][$cont_key]['choices'][$choices_key] = $language[$choices_val];
                        }
                    }
                }
            }
        }

        return $data;
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'appearance/customizer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
