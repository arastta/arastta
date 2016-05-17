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

        $menubar['value'] = $this->config->get('tinymce_menubar', 0);
        $menubar['labelclass'] = 'radio-inline';

        $menu_edit_insertfile['value'] = $this->config->get('tinymce_menu_edit_insertfile', 0);
        $menu_edit_insertfile['labelclass'] = 'radio-inline';

        $menu_edit_undo['value'] = $this->config->get('tinymce_menu_edit_undo', 0);
        $menu_edit_undo['labelclass'] = 'radio-inline';

        $menu_edit_redo['value'] = $this->config->get('tinymce_menu_edit_redo', 0);
        $menu_edit_redo['labelclass'] = 'radio-inline';

        $menu_styleselect_styleselect['value'] = $this->config->get('tinymce_menu_styleselect_styleselect', 0);
        $menu_styleselect_styleselect['labelclass'] = 'radio-inline';

        $menu_format_bold['value'] = $this->config->get('tinymce_menu_format_bold', 1);
        $menu_format_bold['labelclass'] = 'radio-inline';

        $menu_format_italic['value'] = $this->config->get('tinymce_menu_format_italic', 1);
        $menu_format_italic['labelclass'] = 'radio-inline';

        $menu_format_underline['value'] = $this->config->get('tinymce_menu_format_underline', 1);
        $menu_format_underline['labelclass'] = 'radio-inline';

        $menu_format_visualchars['value'] = $this->config->get('tinymce_menu_format_visualchars', 0);
        $menu_format_visualchars['labelclass'] = 'radio-inline';

        $menu_format_visualblocks['value'] = $this->config->get('tinymce_menu_format_visualblocks', 0);
        $menu_format_visualblocks['labelclass'] = 'radio-inline';

        $menu_format_ltr['value'] = $this->config->get('tinymce_menu_format_ltr', 0);
        $menu_format_ltr['labelclass'] = 'radio-inline';

        $menu_format_rtl['value'] = $this->config->get('tinymce_menu_format_rtl', 0);
        $menu_format_rtl['labelclass'] = 'radio-inline';

        $menu_format_fontselect['value'] = $this->config->get('tinymce_menu_format_fontselect', 1);
        $menu_format_fontselect['labelclass'] = 'radio-inline';

        $menu_format_fontsizeselect['value'] = $this->config->get('tinymce_menu_format_fontsizeselect', 1);
        $menu_format_fontsizeselect['labelclass'] = 'radio-inline';

        $menu_format_charmap['value'] = $this->config->get('tinymce_menu_format_charmap', 0);
        $menu_format_charmap['labelclass'] = 'radio-inline';

        $menu_format_forecolor['value'] = $this->config->get('tinymce_menu_format_forecolor', 1);
        $menu_format_forecolor['labelclass'] = 'radio-inline';

        $menu_format_backcolor['value'] = $this->config->get('tinymce_menu_format_backcolor', 1);
        $menu_format_backcolor['labelclass'] = 'radio-inline';

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

        $menu_file_table['value'] = $this->config->get('tinymce_menu_file_table', 1);
        $menu_file_table['labelclass'] = 'radio-inline';

        $menu_insert_link['value'] = $this->config->get('tinymce_menu_insert_link', 1);
        $menu_insert_link['labelclass'] = 'radio-inline';

        $menu_insert_image['value'] = $this->config->get('tinymce_menu_insert_image', 1);
        $menu_insert_image['labelclass'] = 'radio-inline';

        $menu_insert_media['value'] = $this->config->get('tinymce_menu_insert_media', 1);
        $menu_insert_media['labelclass'] = 'radio-inline';

        $menu_insert_hr['value'] = $this->config->get('tinymce_menu_insert_hr', 1);
        $menu_insert_hr['labelclass'] = 'radio-inline';

        $menu_tools_fullscreen['value'] = $this->config->get('tinymce_menu_tools_fullscreen', 1);
        $menu_tools_fullscreen['labelclass'] = 'radio-inline';

        $menu_tools_code['value'] = $this->config->get('tinymce_menu_tools_code', 1);
        $menu_tools_code['labelclass'] = 'radio-inline';

        $menu_tools_searchreplace['value'] = $this->config->get('tinymce_menu_tools_searchreplace', 0);
        $menu_tools_searchreplace['labelclass'] = 'radio-inline';

        $menu_tools_pagebreak['value'] = $this->config->get('tinymce_menu_tools_pagebreak', 0);
        $menu_tools_pagebreak['labelclass'] = 'radio-inline';

        $menu_tools_nonbreaking['value'] = $this->config->get('tinymce_menu_tools_nonbreaking', 0);
        $menu_tools_nonbreaking['labelclass'] = 'radio-inline';

        $menu_tools_emoticons['value'] = $this->config->get('tinymce_menu_tools_emoticons', 0);
        $menu_tools_emoticons['labelclass'] = 'radio-inline';

        $form = new AForm('form-tinymce', $action);

        $form->addElement(new Arastta\Component\Form\Element\HTML('<ul class="nav nav-tabs">'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<li class="active"><a href="#tab-general" data-toggle="tab">' . $this->language->get('tab_general') . '</a></li>'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<li><a href="#tab-advanced" data-toggle="tab">' . $this->language->get('tab_advanced') . '</a></li>'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</ul>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-content">'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-pane active" id="tab-general">'));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'tinymce_status', $status, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_height'), 'tinymce_height', $height));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menubar'), 'tinymce_menubar', $menubar, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_sort_order'), 'tinymce_sort_order', $sort_order));
        $form->addElement(new Arastta\Component\Form\Element\HTML('</div>'));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<div class="tab-pane" id="tab-advanced">'));
        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_tool') . '</legend>'));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_edit_insertfile'), 'tinymce_menu_edit_insertfile', $menu_edit_insertfile, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_edit_undo'), 'tinymce_menu_edit_undo', $menu_edit_undo, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_edit_redo'), 'tinymce_menu_edit_redo', $menu_edit_redo, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_styleselect_styleselect'), 'tinymce_menu_styleselect_styleselect', $menu_styleselect_styleselect, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_bold'), 'tinymce_menu_format_bold', $menu_format_bold, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_italic'), 'tinymce_menu_format_italic', $menu_format_italic, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_underline'), 'tinymce_menu_format_underline', $menu_format_underline, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_visualchars'), 'tinymce_menu_format_visualchars', $menu_format_visualchars, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_visualblocks'), 'tinymce_menu_format_visualblocks', $menu_format_visualblocks, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_ltr'), 'tinymce_menu_format_ltr', $menu_format_ltr, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_rtl'), 'tinymce_menu_format_rtl', $menu_format_rtl, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_fontselect'), 'tinymce_menu_format_fontselect', $menu_format_fontselect, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_fontsizeselect'), 'tinymce_menu_format_fontsizeselect', $menu_format_fontsizeselect, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_charmap'), 'tinymce_menu_format_charmap', $menu_format_charmap, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_forecolor'), 'tinymce_menu_format_forecolor', $menu_format_forecolor, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_format_backcolor'), 'tinymce_menu_format_backcolor', $menu_format_backcolor, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_alignleft'), 'tinymce_menu_view_alignleft', $menu_view_alignleft, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_aligncenter'), 'tinymce_menu_view_aligncenter', $menu_view_aligncenter, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_alignright'), 'tinymce_menu_view_alignright', $menu_view_alignright, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_view_alignjustify'), 'tinymce_menu_view_alignjustify', $menu_view_alignjustify, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_bullist'), 'tinymce_menu_file_bullist', $menu_file_bullist, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_numlist'), 'tinymce_menu_file_numlist', $menu_file_numlist, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_outdent'), 'tinymce_menu_file_outdent', $menu_file_outdent, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_indent'), 'tinymce_menu_file_indent', $menu_file_indent, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_file_table'), 'tinymce_menu_file_table', $menu_file_table, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_insert_link'), 'tinymce_menu_insert_link', $menu_insert_link, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_insert_image'), 'tinymce_menu_insert_image_manager', $menu_insert_image, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_insert_media'), 'tinymce_menu_insert_media', $menu_insert_media, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_insert_hr'), 'tinymce_menu_insert_hr', $menu_insert_hr, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_tools_fullscreen'), 'tinymce_menu_tools_fullscreen', $menu_tools_fullscreen, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_tools_code'), 'tinymce_menu_tools_code', $menu_tools_code, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_tools_searchreplace'), 'tinymce_menu_tools_searchreplace', $menu_tools_searchreplace, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_tools_pagebreak'), 'tinymce_menu_tools_pagebreak', $menu_tools_pagebreak, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_tools_nonbreaking'), 'tinymce_menu_tools_nonbreaking', $menu_tools_nonbreaking, $option_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_menu_tools_emoticons'), 'tinymce_menu_tools_emoticons', $menu_tools_emoticons, $option_text));
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

        $result = $this->model_extension_editor->check('tinymce', $this->request->post);

        if (!$result) {
            $this->error['warning'] = $this->session->data['warning'];
        }

        return !$this->error;
    }
}
