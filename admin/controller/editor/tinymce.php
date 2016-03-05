<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Arastta\Component\Form\Form as AForm;

class ControllerEditorTinymce extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('editor/tinymce');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('tinymce', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

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

        $data['action'] = $this->url->link('editor/tinymce', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/extension', 'filter_type=editor&token=' . $this->session->data['token'], 'SSL');

        $data['form_fields'] = $this->getFormFields($data['action']);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('editor/tinymce.tpl', $data));
    }

    protected function getFormFields($action)
    {
        $action = str_replace('amp;', '', $action);

        $option_text = array(
            'yes' => $this->language->get('text_enabled'),
            'no'  => $this->language->get('text_disabled')
        );

        $status['value'] = $this->config->get('tinymce_status', 1);
        $status['labelclass'] = 'radio-inline';

        $sort_order['value'] = $this->config->get('tinymce_sort_order');
        $sort_order['placeholder'] = $this->language->get('entry_sort_order');

        $height['value'] = $this->config->get('tinymce_height', 300);
        $height['placeholder'] = $this->language->get('entry_height');

        $menu_edit_undo['value'] = $this->config->get('tinymce_menu_edit_undo', 1);
        $menu_edit_undo['labelclass'] = 'radio-inline';

        $menu_edit_redo['value'] = $this->config->get('tinymce_menu_edit_redo', 1);
        $menu_edit_redo['labelclass'] = 'radio-inline';

        $menu_format_bold['value'] = $this->config->get('tinymce_menu_format_bold', 1);
        $menu_format_bold['labelclass'] = 'radio-inline';

        $menu_format_italic['value'] = $this->config->get('tinymce_menu_format_italic', 1);
        $menu_format_italic['labelclass'] = 'radio-inline';

        $menu_view_alignleft['value'] = $this->config->get('tinymce_menu_view_alignleft', 1);
        $menu_view_alignleft['labelclass'] = 'radio-inline';

        $menu_view_aligncenter['value'] = $this->config->get('tinymce_menu_view_aligncenter', 1);
        $menu_view_aligncenter['labelclass'] = 'radio-inline';

        $menu_view_alignright['value'] = $this->config->get('tinymce_menu_view_alignright', 1);
        $menu_view_alignright['labelclass'] = 'radio-inline';

        $menu_view_alignjustify['value'] = $this->config->get('tinymce_menu_view_alignjustify', 1);
        $menu_view_alignjustify['labelclass'] = 'radio-inline';

        $menu_file_bullist['value'] = $this->config->get('tinymce_menu_file_bullist', 1);
        $menu_file_bullist['labelclass'] = 'radio-inline';

        $menu_file_numlist['value'] = $this->config->get('tinymce_menu_file_numlist', 1);
        $menu_file_numlist['labelclass'] = 'radio-inline';

        $menu_file_outdent['value'] = $this->config->get('tinymce_menu_file_outdent', 1);
        $menu_file_outdent['labelclass'] = 'radio-inline';

        $menu_file_indent['value'] = $this->config->get('tinymce_menu_file_indent', 1);
        $menu_file_indent['labelclass'] = 'radio-inline';

        $menu_insert_link['value'] = $this->config->get('tinymce_menu_insert_link', 1);
        $menu_insert_link['labelclass'] = 'radio-inline';

        $menu_insert_image['value'] = $this->config->get('tinymce_menu_insert_image', 1);
        $menu_insert_image['labelclass'] = 'radio-inline';

        $menu_tools_imagetools['value'] = $this->config->get('tinymce_menu_tools_imagetools', 1);
        $menu_tools_imagetools['labelclass'] = 'radio-inline';

        $form = new AForm('form-tinymce', $action);

        $form->addElement(new Arastta\Component\Form\Element\HTML('<ul class="nav nav-tabs">'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<li class="active"><a href="#tab-general" data-toggle="tab">' . $this->language->get('tab_general') . '</a></li>'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<li><a href="#tab-advanced" data-toggle="tab">' . $this->language->get('tab_advanced') . '</a></li>'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</ul>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-content">'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-pane active" id="tab-general">'));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'tinymce_status', $status, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_height'), 'tinymce_height', $height));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_sort_order'), 'tinymce_sort_order', $sort_order));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-pane" id="tab-advanced">'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_tool') . '</legend>'));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_edit_undo'), 'tinymce_menu_edit_undo', $menu_edit_undo, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_edit_redo'), 'tinymce_menu_edit_redo', $menu_edit_redo, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_bold'), 'tinymce_menu_format_bold', $menu_format_bold, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_italic'), 'tinymce_menu_format_italic', $menu_format_italic, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_alignleft'), 'tinymce_menu_view_alignleft', $menu_view_alignleft, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_aligncenter'), 'tinymce_menu_view_aligncenter', $menu_view_aligncenter, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_alignright'), 'tinymce_menu_view_alignright', $menu_view_alignright, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_alignjustify'), 'tinymce_menu_view_alignjustify', $menu_view_alignjustify, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_bullist'), 'tinymce_menu_file_bullist', $menu_file_bullist, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_numlist'), 'tinymce_menu_file_numlist', $menu_file_numlist, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_outdent'), 'tinymce_menu_file_outdent', $menu_file_outdent, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_indent'), 'tinymce_menu_file_indent', $menu_file_indent, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_insert_link'), 'tinymce_menu_insert_link', $menu_insert_link, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_insert_image'), 'tinymce_menu_insert_image', $menu_insert_image, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_tools_imagetools'), 'tinymce_menu_tools_imagetools', $menu_tools_imagetools, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));

        return $form->render(true);
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'editor/tinymce')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!AForm::isValid('form-tinymce')) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        $this->load->model('extension/editor');

        $result = $this->model_extension_editor->check('summernote', $this->request->post);

        if (!$result) {
            $this->error['warning'] = $this->session->data['warning'];
        }

        return !$this->error;
    }
}
