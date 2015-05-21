<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class ControllerCommonEdit extends Controller {

    public function changeStatus() {
        $type   = $this->request->get['type'];
        $select = $this->request->get['selected'];
        $status = $this->request->get['status'];

        if ((count($select) == 0) or is_null($status) or !$this->validate($type)) {
            exit();
        }

        $this->load->model('common/edit');

        switch($type) {
            case 'review':
            case 'product':
            case 'category':
            case 'information':
                $this->language->load('catalog/'.$type);
                $this->model_common_edit->changeStatus($type, $select, (int)$status);
                break;
            case 'payment':
            case 'feed':
            case 'shipping':
            case 'total':
                $this->language->load('extension/'.$type);
                $this->model_common_edit->changeStatus($type, $select, (int)$status, true);
                break;
            case 'zone':
            case 'country':
                $this->language->load('localisation/'.$type);
                $this->model_common_edit->changeStatus($type, $select, (int)$status);
                break;
            case 'coupon' :
                $this->language->load('sale/'.$type);
                $this->model_common_edit->changeStatus($type, $select, (int)$status);
            default:
                break;
        }

        $this->session->data['success'] = $this->language->get('text_success');
        echo 0;
        exit();
    }

    /** changeStatus **/
    private function validate($type) {
        if($type = 'extension'){
            return true;
        }

        if (!$this->user->hasPermission('modify', 'catalog/'.$type)) {
            $error['warning'] = $this->language->get('error_permission');
            echo json_encode($error);
        }

        if (empty($error['warning'])) {
            return true;
        } else {
            return false;
        }
    }
    /**end changeStatus **/

}