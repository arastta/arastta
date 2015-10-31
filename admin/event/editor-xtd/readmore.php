<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits		See CREDITS.txt for credits and other copyright notices.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class EventEditorXtdReadmore extends Event {

    public function preAdminEditorToolbarAdd(&$toolbar) {
        $editor = $this->config->get('config_text_editor');

        switch ($editor) {
            case 'summernote':
                $toolbar['readmore'] = array('readmore');
                $this->document->addScript('view/javascript/summernote/plugins/readmore/plugin.js');
                break;
            case 'tinymce':
                $toolbar['readmore'] = array('readmore');
                $this->document->addScript('view/javascript/tinymce/plugins/readmore/plugin.js');
                break;
            #new-editor-add
        }
    }

    public function preAdminEditorMenuAdd(&$menu) {
        $editor = $this->config->get('config_text_editor');

        switch ($editor) {
            case 'tinymce':
                $menu['readmore'] = array('readmore');
                break;
            #new-editor-add
        }
    }
}