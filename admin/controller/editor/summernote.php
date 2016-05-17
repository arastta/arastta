<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Arastta\Component\Form\Form as AForm;

class ControllerEditorSummernote extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('editor/summernote');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('summernote', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->cache->delete('editor');

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/extension', 'filter_type=editor&token=' . $this->session->data['token'], 'SSL'));
        }
        
        // Add all language text
        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('editor/summernote', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/extension', 'filter_type=editor&token=' . $this->session->data['token'], 'SSL');

        $data['form_fields'] = $this->getFormFields($data['action']);
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('editor/summernote.tpl', $data));
    }
    
    protected function getFormFields($action)
    {
        $action = str_replace('amp;', '', $action);

        $option_text = array(
            'yes' => $this->language->get('text_enabled'),
            'no'  => $this->language->get('text_disabled')
        );

        $status['value'] = $this->config->get('summernote_status', 1);
        $status['labelclass'] = 'radio-inline';

        $sort_order['value'] = $this->config->get('summernote_sort_order');
        $sort_order['placeholder'] = $this->language->get('entry_sort_order');

        $height['value'] = $this->config->get('summernote_height', 300);
        $height['placeholder'] = $this->language->get('entry_height');

        $tool_style['value'] = $this->config->get('summernote_tool_style', 1);
        $tool_style['labelclass'] = 'radio-inline';

        $tool_font_bold['value'] = $this->config->get('summernote_tool_font_bold', 1);
        $tool_font_bold['labelclass'] = 'radio-inline';

        $tool_font_italic['value'] = $this->config->get('summernote_tool_font_italic', 1);
        $tool_font_italic['labelclass'] = 'radio-inline';

        $tool_font_underline['value'] = $this->config->get('summernote_tool_font_underline', 1);
        $tool_font_underline['labelclass'] = 'radio-inline';

        $tool_font_clear['value'] = $this->config->get('summernote_tool_font_clear', 1);
        $tool_font_clear['labelclass'] = 'radio-inline';

        $tool_fontname['value'] = $this->config->get('summernote_tool_fontname', 1);
        $tool_fontname['labelclass'] = 'radio-inline';

        $tool_fontsize['value'] = $this->config->get('summernote_tool_fontsize', 1);
        $tool_fontsize['labelclass'] = 'radio-inline';

        $tool_color['value'] = $this->config->get('summernote_tool_color', 1);
        $tool_color['labelclass'] = 'radio-inline';

        $tool_para_ol['value'] = $this->config->get('summernote_tool_para_ol', 1);
        $tool_para_ol['labelclass'] = 'radio-inline';

        $tool_para_ul['value'] = $this->config->get('summernote_tool_para_ul', 1);
        $tool_para_ul['labelclass'] = 'radio-inline';

        $tool_para_paragraph['value'] = $this->config->get('summernote_tool_para_paragraph', 1);
        $tool_para_paragraph['labelclass'] = 'radio-inline';

        $tool_height['value'] = $this->config->get('summernote_tool_height', 1);
        $tool_height['labelclass'] = 'radio-inline';

        $tool_table['value'] = $this->config->get('summernote_tool_table', 1);
        $tool_table['labelclass'] = 'radio-inline';

        $tool_insert_link['value'] = $this->config->get('summernote_tool_insert_link', 1);
        $tool_insert_link['labelclass'] = 'radio-inline';

        $tool_insert_picture['value'] = $this->config->get('summernote_tool_insert_picture', 1);
        $tool_insert_picture['labelclass'] = 'radio-inline';

        $tool_insert_hr['value'] = $this->config->get('summernote_tool_insert_hr', 1);
        $tool_insert_hr['labelclass'] = 'radio-inline';

        $tool_view_fullscreen['value'] = $this->config->get('summernote_tool_view_fullscreen', 1);
        $tool_view_fullscreen['labelclass'] = 'radio-inline';

        $tool_view_codeview['value'] = $this->config->get('summernote_tool_view_codeview', 1);
        $tool_view_codeview['labelclass'] = 'radio-inline';

        $tool_help['value'] = $this->config->get('summernote_tool_help', 1);
        $tool_help['labelclass'] = 'radio-inline';

        $form = new AForm('form-summernote', $action);

        $form->addElement(new Arastta\Component\Form\Element\HTML('<ul class="nav nav-tabs">'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<li class="active"><a href="#tab-general" data-toggle="tab">' . $this->language->get('tab_general') . '</a></li>'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<li><a href="#tab-advanced" data-toggle="tab">' . $this->language->get('tab_advanced') . '</a></li>'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</ul>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-content">'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-pane active" id="tab-general">'));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'summernote_status', $status, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_height'), 'summernote_height', $height));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_sort_order'), 'summernote_sort_order', $sort_order));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-pane" id="tab-advanced">'));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_style'), 'summernote_tool_style', $tool_style, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_font_bold'), 'summernote_tool_font_bold', $tool_font_bold, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_font_italic'), 'summernote_tool_font_italic', $tool_font_italic, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_font_underline'), 'summernote_tool_font_underline', $tool_font_underline, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_font_clear'), 'summernote_tool_font_clear', $tool_font_clear, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_fontname'), 'summernote_tool_fontname', $tool_fontname, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_fontsize'), 'summernote_tool_fontsize', $tool_fontsize, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_color'), 'summernote_tool_color', $tool_color, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_para_ol'), 'summernote_tool_para_ol', $tool_para_ol, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_para_ul'), 'summernote_tool_para_ul', $tool_para_ul, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_para_paragraph'), 'summernote_tool_para_paragraph', $tool_para_paragraph, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_height'), 'summernote_tool_height', $tool_height, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_table'), 'summernote_tool_table', $tool_table, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_insert_link'), 'summernote_tool_insert_link', $tool_insert_link, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_insert_picture'), 'summernote_tool_insert_picture', $tool_insert_picture, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_insert_hr'), 'summernote_tool_insert_hr', $tool_insert_hr, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_view_fullscreen'), 'summernote_tool_view_fullscreen', $tool_view_fullscreen, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_view_codeview'), 'summernote_tool_view_codeview', $tool_view_codeview, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_tool_help'), 'summernote_tool_help', $tool_help, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));

        return $form->render(true);
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'editor/summernote')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        $this->load->model('extension/editor');

        $result = $this->model_extension_editor->check('summernote', $this->request->post);

        if (!$result) {
            $this->error['warning'] = $this->session->data['warning'];
        }
        
        return !$this->error;
    }
}
