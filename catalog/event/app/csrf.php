<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Volnix\CSRF\CSRF;

class EventAppCsrf extends Event
{

    private $token_name = 'csrf_token';

    public function postLoadView(&$output, $template)
    {
        // Check if the output is POST and route is not empty
        if (empty($output) || empty($this->request->get['route'])) {
            return;
        }

        // Check if route is in the the check list
        $route = $this->request->get['route'];

        if (!in_array($route, $this->config->get('config_sec_csrf')) || !strstr($template, $route.'.tpl')) {
            return;
        }

        // Add CSRF token
        $input = CSRF::getHiddenInputString($this->token_name);

        $output = str_replace('</form>', $input.'</form>', $output);
    }

    public function postAppRoute()
    {
        // Check if the request is POST and route is not empty
        if (!$this->request->isPost() || empty($this->request->get['route'])) {
            return;
        }

        // Check if route is in the the check list
        if (!in_array($this->request->get['route'], $this->config->get('config_sec_csrf'))) {
            return;
        }

        // Validate CSRF token
        if (CSRF::validate($this->request->post, $this->token_name)) {
            return;
        }

        die('Invalid CSRF token.');
    }
}
