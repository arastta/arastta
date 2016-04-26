<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventEditorSummernote extends Event
{

    public $toolbar = array(
        'style'     => array('style'),
        'font'      => array('bold', 'italic', 'underline', 'clear'),
        'fontname'  => array('fontname'),
        'fontsize'  => array('fontsize'),
        'color'     => array('color'),
        'para'      => array('ol', 'ul', 'paragraph'),
        'height'    => array('height'),
        'table'     => array('table'),
        'insert'    => array('link', 'picture', 'hr'),
        'view'      => array('fullscreen', 'codeview'),
        'help'      => array('help')
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

        $setting = $this->model_setting_setting->getSetting('summernote');

        if ($editor != 'summernote') {
            return;
        }

        $editor_language = 'en-US';

        if (is_file(DIR_ADMIN . 'view/javascript/summernote/lang/summernote-' . $this->config->get('config_language_dir') . '.js')) {
            $editor_language = $this->config->get('config_language_dir');
        }

        $this->trigger->addFolder('editor-xtd');
        $this->trigger->fire('pre.admin.editor.toolbar.add', array(&$this->toolbar));
        $this->trigger->fire('pre.admin.editor.height.edit', array(&$this->height));
        $this->trigger->fire('pre.admin.editor.other.edit', array(&$this->other_options));

        $this->document->addStyle('view/javascript/summernote/summernote.css');
        $this->document->addScript('view/javascript/summernote/summernote.js');
        $this->document->addScript('view/javascript/summernote/summernote-xtd.js');

        if ($editor_language != 'en-US') {
            $this->document->addScript('view/javascript/summernote/lang/summernote-' . $editor_language . '.js');
        }

        $toolbars = '';

        $tool_prefix = 'summernote_tool_';

        foreach ($this->toolbar as $key => $value) {
            if (isset($setting[$tool_prefix . $key]) && !$setting[$tool_prefix . $key]) {
                continue;
            }

            $toolbars .= "[['" . $key . "'], [";

            foreach ($value as $item) {
                if (isset($setting[$tool_prefix . $key . '_' . $item]) && !$setting[$tool_prefix . $key . '_' . $item]) {
                    continue;
                }

                $toolbars .= "'" . $item . "',";
            }

            $toolbars .= "]],". chr(13) . chr(9) . chr(9). chr(9) . chr(9);
        }

        $toolbars = str_replace(',]]', ']]', $toolbars);

        $other_options = null;

        if (!empty($this->other_options)) {
            foreach ($this->other_options as $key => $value) {
                $other_options = '';
            }
        }

        if (isset($setting['summernote_height']) && $setting['summernote_height'] > 0) {
            $this->height = $setting['summernote_height'];
        }

        $script  = "function textEditor(text_id) {" . chr(13). chr(9) . chr(9);
        $script .= "  $(text_id).summernote({" . chr(13) . chr(9) . chr(9);
        $script .= "      toolbar: [" . chr(13) . chr(9) . chr(9);
        $script .= "        " . $toolbars;
        $script .= "      ]," . chr(13) . chr(9) . chr(9);
        if (!empty($other_options)) {
            $script .= "      " . $other_options . chr(13) . chr(9) . chr(9);
        }
        $script .= "      height: " . $this->height . "," . chr(13) . chr(9) . chr(9);
        $script .= "      lang: '" . $editor_language . "'" . chr(13) . chr(9) . chr(9);
        $script .= "   });" . chr(13) . chr(9) . chr(9);
        $script .= "   imageDialog();";
        $script .= "}" . chr(9) . chr(9);
    
        $this->document->addScriptDeclarations($script);
    }
}
