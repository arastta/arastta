<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventTwofactorauthYubikey extends Event
{

    public function postAdminLogin()
    {
        // Check if extension is enabled
        $this->load->model('setting/setting');

        $setting = $this->model_setting_setting->getSetting('yubikey');

        if (!isset($setting['yubikey_status']) || !$setting['yubikey_status']) {
            return;
        }

        // Check if user has enabled it
        $params = $this->user->getParams();

        if (!isset($params['twofactorauth']) || ($params['twofactorauth']['method'] != 'yubikey')) {
            return;
        }

        // Get and verify the code entered by user
        $secretcode = $this->request->post['secretcode'];

        if (!empty($secretcode)) {
            $y = new \Yubikey\Validate($setting['yubikey_apikey'], $setting['yubikey_clientid']);
            $response = $y->check($secretcode);

            // If verified, just return
            if ($response->success() === true) {
                return;
            }
        }

        // Logout the user as 2FA not verified
        $this->user->logout();

        // Set the error message
        $this->session->data['warning'] = $this->language->get('error_twofactorauth');

        // Redirect to login page again
        $this->response->redirect($this->url->link('common/login', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function preAdminTwofactorauthDisplay(&$method, &$data, &$user_info)
    {
        if ($method != 'yubikey') {
            return;
        }

        $params = json_decode($user_info['params'], true);

        if (isset($params['twofactorauth']) && ($params['twofactorauth']['method'] == 'yubikey')) {
            $data['new'] = 0;
        } else {
            $data['new'] = 1;
        }
    }

    public function preAdminTwofactorauthValidate(&$method)
    {
        if ($method != 'yubikey') {
            return true;
        }

        // Validate new set up
        $code = $this->request->post['params']['twofactorauth'][$method]['code'];

        unset($this->request->post['params']['twofactorauth'][$method]['code']);

        if (empty($code)) {
            return false;
        }

        $this->load->model('setting/setting');

        $setting = $this->model_setting_setting->getSetting('yubikey');

        $y = new \Yubikey\Validate($setting['yubikey_apikey'], $setting['yubikey_clientid']);
        $response = $y->check($code);

        return $response->success();
    }
}
