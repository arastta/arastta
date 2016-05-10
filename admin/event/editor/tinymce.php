<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventEditorTinymce extends Event
{
    public $menu = array(
        'edit'          => array('insertfile', 'undo', 'redo'),
        'styleselect'   => array('styleselect'),
        'format'        => array('bold', 'italic', 'underline', 'visualchars', 'visualblocks', 'ltr', 'rtl', '|',  'fontselect', 'fontsizeselect', 'charmap', '|', 'forecolor', 'backcolor'),
        'file'          => array('bullist', 'numlist', 'outdent', 'indent'),
        'view'          => array('alignleft', 'aligncenter', 'alignright', 'alignjustify', '|', 'table'),
        'insert'        => array('link', 'image_manager', 'media', 'hr'),
        'tools'         => array('fullscreen', 'code', 'searchreplace', 'pagebreak', 'nonbreaking', 'emoticons')
    );

    public $toolbar = array(
        'insert'     => array('advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'print', 'preview', 'hr', 'anchor', 'pagebreak'),
        'view'       => array('searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen'),
        'table'      => array('insertdatetime', 'media', 'nonbreaking', 'save', 'table', 'contextmenu', 'directionality', 'paste', 'image_manager'),
        'tools'      => array('emoticons', 'colorpicker', 'textpattern', 'autoresize', 'textcolor', 'template')
    );

    public $height = 300;

    public $other_options = array();

    public function preAdminEditor()
    {
        $editor = $this->config->get('config_text_editor');

        if ($this->user->isLogged()) {
            $user = $this->user->getParams();

            if (!empty($user['editor'])) {
                $editor = $user['editor'];
            }
        }

        $this->load->model('setting/setting');

        $setting = $this->model_setting_setting->getSetting('tinymce');

        if ($editor != 'tinymce') {
            return;
        }

        $editor_language = '';

        $lang_code = $this->language->get('code');
        $lang_tag = str_replace('-', '_', $this->config->get('config_language_dir'));

        if (is_file(DIR_ADMIN . 'view/javascript/tinymce/langs/' . $this->config->get('config_language_dir') . '.js')) {
            $editor_language = $this->config->get('config_language_dir');
        } elseif (is_file(DIR_ADMIN . 'view/javascript/tinymce/langs/' . $lang_code . '.js')) {
            $editor_language = $lang_code;
        } elseif (is_file(DIR_ADMIN . 'view/javascript/tinymce/langs/' . $lang_tag . '.js')) {
            $editor_language = $lang_tag;
        }

        $this->trigger->addFolder('editor-xtd');
        $this->trigger->fire('pre.admin.editor.button.add');
        $this->trigger->fire('pre.admin.editor.menu.add', array(&$this->menu));
        $this->trigger->fire('pre.admin.editor.toolbar.add', array(&$this->toolbar));
        $this->trigger->fire('pre.admin.editor.height.edit', array(&$this->height));
        $this->trigger->fire('pre.admin.editor.other.edit', array(&$this->other_options));

        $this->document->addScript('view/javascript/tinymce/tinymce.min.js');

        $menus = "";

        $menu_prefix = 'tinymce_menu_';

        foreach ($this->menu as $key => $value) {
            foreach ($value as $item) {
                if (isset($setting[$menu_prefix . $key . '_' . $item]) && !$setting[$menu_prefix . $key . '_' . $item]) {
                    continue;
                }

                $menus .=  $item . " ";
            }
            $menus .= "| " ;
        }

        $menus .= "";

        $toolbars = "";

        $tool_prefix = 'tinymce_tool_';

        foreach ($this->toolbar as $key => $value) {
            $toolbars .= "'";

            foreach ($value as $item) {
                $toolbars .=  $item . " ";
            }

            $toolbars = rtrim($toolbars);
            $toolbars .= "'," . chr(13) . chr(9) . chr(9) . chr(9) . chr(9);
        }

        if (!empty($this->other_options)) {
            foreach ($this->other_options as $key => $value) {
                $other_options = '';
            }
        }

        if (isset($setting['tinymce_height']) && $setting['tinymce_height'] > 0) {
            $this->height = (int)$setting['tinymce_height'];
        }

        $script  = "function textEditor(text_id) {" . chr(13). chr(9) . chr(9);
        $script .= "  tinymce.init({" . chr(13) . chr(9) . chr(9);
        $script .= "      selector: text_id," . chr(13) . chr(9) . chr(9);
        $script .= "      height: " . $this->height . " ," . chr(13) . chr(9) . chr(9);
        $script .= "      plugins: [" . chr(13) . chr(9) . chr(9);
        $script .= "        "   . $toolbars;
        $script .= "      ]," . chr(13) . chr(9) . chr(9);
        if (!empty($other_options)) {
            $script .= "      " . $other_options . chr(13) . chr(9) . chr(9);
        }
        if (empty($setting['tinymce_menubar'])) {
            $script .= "      menubar: 'false'," . chr(13) . chr(9) . chr(9);
        }
        $script .= "      toolbar: '" . $menus . "'," . chr(13) . chr(9) . chr(9);
        $script .= "      language: '" . $editor_language . "'" . chr(13) . chr(9) . chr(9);
        $script .= "   });" . chr(13) . chr(9) . chr(9);
        $script .= "}" . chr(9) . chr(9);
    
        $this->document->addScriptDeclarations($script);
    }
}
