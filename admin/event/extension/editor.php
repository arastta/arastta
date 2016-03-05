<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventExtensionEditor extends Event
{
    public function preAdminExtensionStatusEdit($data)
    {
        if ((isset($this->request->post['filter_type']) && $this->request->post['filter_type'] != 'editor') ||
                (!empty($data['status']) && $this->request->post['filter_type'] == 'editor')) {
            return;
        }

        $this->load->model('extension/editor');

        foreach ($data['selected'] as $editor) {
            $editor_code = basename($editor);

            $data = array(
                $editor_code . '_status' => $data['status']
            );

            $result = $this->model_extension_editor->check($editor_code, $data);
        }

        if (!$result) {
            if (!empty($data['return']) && $data['return'] == 'back') {
                return;
            }

            $json['redirect'] = str_replace('&amp;', '&', $this->url->link('extension/extension', 'filter_type=editor&token=' . $this->session->data['token'], 'SSL'));

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            $this->response->output();

            exit();
        }
    }
}
