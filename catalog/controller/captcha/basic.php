<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Gregwar\Captcha\CaptchaBuilder;

class ControllerCaptchaBasic extends Controller
{

    public function index($error = array())
    {
        $this->load->language('captcha/basic');

        $data = $this->language->all();

        if (isset($error['captcha'])) {
            $data['error_captcha'] = $error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        $captcha = new CaptchaBuilder;
        //$builder->setBackgroundColor('245', '245', '245');
        $captcha->setMaxFrontLines(0);
        $captcha->build();

        $this->session->data['basic_captcha_phrase'] = $captcha->getPhrase();

        $data['captcha'] = $captcha->inline();

        return $this->load->output('captcha/basic', $data);
    }

    public function validate()
    {
        $status = false;

        if (empty($this->request->post['basic_captcha_phrase']) || empty($this->session->data['basic_captcha_phrase'])) {
            return $status;
        }

        $captcha = new CaptchaBuilder($this->session->data['basic_captcha_phrase']);

        if ($captcha->testPhrase($this->request->post['basic_captcha_phrase'])) {
            $status = true;
        }

        return $status;
    }
}
