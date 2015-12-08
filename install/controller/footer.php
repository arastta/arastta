<?php
/**
 * @package         Arastta eCommerce
 * @copyright       Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits         See CREDITS.txt for credits and other copyright notices.
 * @license         GNU General Public License version 3; see LICENSE.txt
 */
class ControllerFooter extends Controller
{

    public function index()
    {
        $data = array();

        return $this->load->view('footer.tpl', $data);
    }
}
