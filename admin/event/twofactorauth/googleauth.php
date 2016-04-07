<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventTwofactorauthGoogleauth extends Event
{

    public function postAdminLogin()
    {
        // Check if extension is enabled
        $this->load->model('setting/setting');

        $setting = $this->model_setting_setting->getSetting('googleauth');

        if (!isset($setting['googleauth_status']) || !$setting['googleauth_status']) {
            return;
        }

        // Check if user has enabled it
        $params = $this->user->getParams();

        if (!isset($params['twofactorauth']) || ($params['twofactorauth']['method'] != 'googleauth')) {
            return;
        }

        // Get and verify the code entered by user
        $secretcode = $this->request->post['secretcode'];

        if (!empty($secretcode) && (strlen($secretcode) == 6)) {
            $key = $this->encryption->decrypt($params['twofactorauth']['googleauth']['key']);

            $g = new \GAuth\Auth($key);
            $verify = $g->validateCode($secretcode);

            // If verified, just return
            if ($verify == true) {
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

    public function preAdminUserEdit(&$data)
    {
        $method = $data['params']['twofactorauth']['method'];

        if ($method != 'googleauth') {
            return;
        }

        $key = $data['params']['twofactorauth'][$method]['key'];

        $data['params']['twofactorauth'][$method]['key'] = $this->encryption->encrypt($key);
    }

    public function preAdminTwofactorauthDisplay(&$method, &$data, &$user_info)
    {
        if ($method != 'googleauth') {
            return;
        }

        $params = json_decode($user_info['params'], true);

        if (isset($params['twofactorauth']) && ($params['twofactorauth']['method'] == 'googleauth')) {
            $data['key'] = $this->encryption->decrypt($params['twofactorauth'][$method]['key']);
            $data['new'] = 0;
        } else {
            // Generate new key
            $g = new \GAuth\Auth();
            $data['key'] = $g->generateCode();

            $data['new'] = 1;
        }

        $g = new \GAuth\Auth($data['key']);
        $data['qrcode'] = $g->generateQrImage($user_info['email'], $this->config->get('config_name'), 150);
    }

    public function preAdminTwofactorauthValidate(&$method)
    {
        if ($method != 'googleauth') {
            return true;
        }

        // Validate new set up
        $key = $this->request->post['params']['twofactorauth'][$method]['key'];
        $code = $this->request->post['params']['twofactorauth'][$method]['code'];

        unset($this->request->post['params']['twofactorauth'][$method]['code']);

        if (empty($key) || empty($code) || (strlen($code) != 6)) {
            return false;
        }

        $g = new \GAuth\Auth($key);

        return $g->validateCode($code);
    }
}
