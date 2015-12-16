<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonEdit extends Controller
{

    public function changeStatus()
    {
        $type   = $this->request->get['type'];
        $select = $this->request->get['selected'];
        $status = $this->request->get['status'];

        if ((count($select) == 0) or is_null($status)) {
            exit();
        }

        $route = '';
        $is_extension = false;

        switch ($type) {
            case 'review':
            case 'product':
            case 'category':
            case 'information':
                $route = 'catalog/'.$type;
                break;
            case 'payment':
            case 'feed':
            case 'shipping':
            case 'total':
                $is_extension = true;
                $route = 'extension/'.$type;
                break;
            case 'language':
            case 'zone':
            case 'country':
                $route = 'localisation/'.$type;
                break;
            case 'coupon':
                $route = 'sale/'.$type;
                break;
            default:
                break;
        }

        if (empty($route)) {
            exit();
        }

        $this->language->load($route);

        if (!$this->validate($route)) {
            exit();
        }

        $this->load->model('common/edit');
        $this->model_common_edit->changeStatus($type, $select, (int)$status, $is_extension);

        $this->session->data['success'] = $this->language->get('text_success');

        echo 0;

        exit();
    }

    private function validate($route)
    {
        if ($route == 'extension/extension') {
            return true;
        }

        if (!$this->user->hasPermission('modify', $route)) {
            $error['warning'] = $this->language->get('error_permission');
            echo json_encode($error);
        }

        if (empty($error['warning'])) {
            return true;
        } else {
            return false;
        }
    }
}
