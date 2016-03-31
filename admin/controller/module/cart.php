<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Arastta\Component\Form\Form as AForm;

class ControllerModuleCart extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('module/cart');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('cart', $this->request->post);
            } else {
                $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'save') {
                $module_id = '';

                if (isset($this->request->get['module_id'])) {
                    $module_id = '&module_id=' . $this->request->get['module_id'];
                } elseif ($this->db->getLastId()) {
                    $module_id = '&module_id=' . $this->db->getLastId();
                }

                $this->response->redirect($this->url->link('module/cart', 'token=' . $this->session->data['token'] . $module_id, 'SSL'));
            }

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('module/cart', 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        #Get All Language Text
        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('module/cart', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('module/cart', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['popup'])) {
            $data['popup'] = $this->request->post['popup'];
        } elseif (!empty($module_info)) {
            $data['popup'] = $module_info['popup'];
        } else {
            $data['popup'] = 0;
        }

        if (isset($this->request->post['theme'])) {
            $data['theme'] = $this->request->post['theme'];
        } elseif (!empty($module_info)) {
            $data['theme'] = $module_info['theme'];
        } else {
            $data['theme'] = 'cart';
        }

        if (isset($this->request->post['product_image'])) {
            $data['product_image'] = $this->request->post['product_image'];
        } elseif (!empty($module_info)) {
            $data['product_image'] = $module_info['product_image'];
        } else {
            $data['product_image'] = 1;
        }

        if (isset($this->request->post['product_name'])) {
            $data['product_name'] = $this->request->post['product_name'];
        } elseif (!empty($module_info)) {
            $data['product_name'] = $module_info['product_name'];
        } else {
            $data['product_name'] = 1;
        }


        if (isset($this->request->post['product_model'])) {
            $data['product_model'] = $this->request->post['product_model'];
        } elseif (!empty($module_info)) {
            $data['product_model'] = $module_info['product_model'];
        } else {
            $data['product_model'] = 1;
        }

        if (isset($this->request->post['product_quantity'])) {
            $data['product_quantity'] = $this->request->post['product_quantity'];
        } elseif (!empty($module_info)) {
            $data['product_quantity'] = $module_info['product_quantity'];
        } else {
            $data['product_quantity'] = 1;
        }

        if (isset($this->request->post['product_price'])) {
            $data['product_price'] = $this->request->post['product_price'];
        } elseif (!empty($module_info)) {
            $data['product_price'] = $module_info['product_price'];
        } else {
            $data['product_price'] = 1;
        }

        if (isset($this->request->post['product_total'])) {
            $data['product_total'] = $this->request->post['product_total'];
        } elseif (!empty($module_info)) {
            $data['product_total'] = $module_info['product_total'];
        } else {
            $data['product_total'] = 1;
        }

        if (isset($this->request->post['button_continue'])) {
            $data['button_continue'] = $this->request->post['button_continue'];
        } elseif (!empty($module_info)) {
            $data['button_continue'] = $module_info['button_continue'];
        } else {
            $data['button_continue'] = 1;
        }

        if (isset($this->request->post['button_cart'])) {
            $data['button_cart'] = $this->request->post['button_cart'];
        } elseif (!empty($module_info)) {
            $data['button_cart'] = $module_info['button_cart'];
        } else {
            $data['button_cart'] = 1;
        }

        if (isset($this->request->post['button_checkout'])) {
            $data['button_checkout'] = $this->request->post['button_checkout'];
        } elseif (!empty($module_info)) {
            $data['button_checkout'] = $module_info['button_checkout'];
        } else {
            $data['button_checkout'] = 1;
        }

        if (isset($this->request->post['coupon'])) {
            $data['coupon'] = $this->request->post['coupon'];
        } elseif (!empty($module_info)) {
            $data['coupon'] = $module_info['coupon'];
        } else {
            $data['coupon'] = 1;
        }

        if (isset($this->request->post['message'])) {
            $data['message'] = $this->request->post['message'];
        } elseif (!empty($module_info)) {
            $data['message'] = $module_info['message'];
        } else {
            $data['message'] = 1;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = 1;
        }

        $data['form_fields'] = $this->getFormFields($data);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/cart.tpl', $data));
    }

    protected function getFormFields($data)
    {
        $action = str_replace('amp;', '', $data['action']);

        $option_text = array('yes' => $this->language->get('text_enabled'), 'no' => $this->language->get('text_disabled'));

        $form = new AForm('form-cart', $action);

        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_general') . '</legend>'));

        $name = array('value' => $data['name'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_name'), 'name', $name));

        $popup = array('value' => $data['popup'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_popup'), 'popup', $popup, $option_text));

        $theme        = array('value' => $data['theme'], 'selected' => $data['theme'], 'id' => 'input-theme');
        $theme_option = array('cart' => $this->language->get('text_cart'), 'mini_cart' => $this->language->get('text_mini_cart'));
        $form->addElement(new Arastta\Component\Form\Element\Select($this->language->get('entry_theme'), 'theme', $theme_option, $theme, $option_text));

        $status = array('value' => $data['status'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'status', $status, $option_text));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_product') . '</legend>'));

        $product_image = array('value' => $data['product_image'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_image'), 'product_image', $product_image, $option_text));

        $product_name = array('value' => $data['product_name'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_pname'), 'product_name', $product_name, $option_text));

        $product_model = array('value' => $data['product_model'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_model'), 'product_model', $product_model, $option_text));

        $product_quantity = array('value' => $data['product_quantity'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_quantity'), 'product_quantity', $product_quantity, $option_text));

        $product_price = array('value' => $data['product_price'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_price'), 'product_price', $product_price, $option_text));

        $product_total = array('value' => $data['product_total'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_total'), 'product_total', $product_total, $option_text));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_button') . '</legend>'));

        $button_continue = array('value' => $data['button_continue'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_continue'), 'button_continue', $button_continue, $option_text));

        $button_cart = array('value' => $data['button_cart'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_cart'), 'button_cart', $button_cart, $option_text));

        $button_checkout = array('value' => $data['button_cart'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_checkout'), 'button_checkout', $button_checkout, $option_text));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_other') . '</legend>'));

        $coupon = array('value' => $data['coupon'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_coupon'), 'coupon', $coupon, $option_text));

        $message = array('value' => $data['message'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_message'), 'message', $message, $option_text));

        return $form->render(true);
    }

    public function popup()
    {
        $json = array();

        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePopup()) {
            $modules = $this->model_extension_module->getModulesByCode('cart');

            foreach ($modules as $module) {
                $setting = unserialize($module['setting']);

                if ($setting['popup'] && $this->request->post['module_id'] != $module['module_id']) {
                    $json['warning'] = sprintf($this->language->get('error_popup'), $module['name']);

                    break;
                }
            }
        }

        if (isset($this->error['warning'])) {
            $json['warning'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/cart')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ($this->request->post['popup']) {
            $modules = $this->model_extension_module->getModulesByCode('cart');

            foreach ($modules as $module) {
                $setting = unserialize($module['setting']);

                if ($setting['popup'] && (!isset($this->request->get['module_id']) || $this->request->get['module_id'] != $module['module_id'])) {
                    $this->error['warning'] = sprintf($this->language->get('error_popup'), $module['name']);

                    break;
                }
            }
        }

        return !$this->error;
    }

    protected function validatePopup()
    {
        if (!$this->user->hasPermission('modify', 'module/cart')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
