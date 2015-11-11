<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class EventEditorTinymce extends Event {
    public $menu = array(
        'edit'          => array('insertfile', 'undo', 'redo'),
        'styleselect'   => array('styleselect'),
        'format'        => array('bold', 'italic'),
        'view'          => array('alignleft', 'aligncenter', 'alignright', 'alignjustify'),
        'file'          => array('bullist', 'numlist', 'outdent', 'indent'),
        'insert'        => array('link', 'image'),
        'tools'         => array('emoticons', 'autoresize', 'imagetools')
    );

    public $toolbar = array(
        'insert'     => array('advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'print', 'preview', 'anchor'),
        'view'       => array('searchreplace', 'visualblocks', 'code', 'fullscreen'),
        'table'      => array('insertdatetime', 'media', 'table', 'contextmenu', 'paste', 'imagetools'),
        'tools'      => array('emoticons', 'autoresize', 'textcolor', 'template' )
    );

    public $height = 300;

    public $other_options = array();

    public function preAdminEditor() {
        $editor = $this->config->get('config_text_editor');

        if ($editor != 'tinymce') {
            return;
        }

        $editor_language = '';

        $lang_code = $this->language->get('code');
        $lang_tag = str_replace('-', '_', $this->config->get('config_language_dir'));

        if (is_file(DIR_ADMIN . 'view/javascript/tinymce/langs/' . $this->config->get('config_language_dir') . '.js')) {
            $editor_language = $this->config->get('config_language_dir');
        } else if (is_file(DIR_ADMIN . 'view/javascript/tinymce/langs/' . $lang_code . '.js')) {
            $editor_language = $lang_code;
        } else if (is_file(DIR_ADMIN . 'view/javascript/tinymce/langs/' . $lang_tag . '.js')) {
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

        foreach ($this->menu as $key => $value) {
            foreach ($value as $item) {
                $menus .=  $item . " ";
            }
            $menus .= "| " ;
        }

        $menus .= "";

        $toolbars = "";

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

        $script  = "function textEditor(text_id) {" . chr(13). chr(9) . chr(9);
        $script .= "  tinymce.init({" . chr(13) . chr(9) . chr(9);
        $script .= "      selector: text_id," . chr(13) . chr(9) . chr(9);
        $script .= "      height: " . $this->height . "," . chr(13) . chr(9) . chr(9);
        $script .= "      plugins: [" . chr(13) . chr(9) . chr(9);
        $script .= "        "   . $toolbars;
        $script .= "      ]," . chr(13) . chr(9) . chr(9);
        if (!empty($other_options)) {
            $script .= "      " . $other_options . chr(13) . chr(9) . chr(9);
        }
        $script .= "      toolbar: '" . $menus . "'," . chr(13) . chr(9) . chr(9);
        $script .= "      language: '" . $editor_language . "'" . chr(13) . chr(9) . chr(9);
        $script .= "   });" . chr(13) . chr(9) . chr(9);
        $script .= "}" . chr(9) . chr(9);
    
        $this->document->addScriptDeclarations($script);
    }
}