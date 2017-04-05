<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Arastta\Component\Form\Form as AForm;

class ControllerModuleBlogComment extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('module/blog_comment');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('blog_comment', $this->request->post);
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

                $this->response->redirect($this->url->link('module/blog_comment', 'token=' . $this->session->data['token'] . $module_id, 'SSL'));
            }

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('module/blog_comment', 'token=' . $this->session->data['token'], 'SSL'));
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

        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }

        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('module/blog_comment', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('module/blog_comment', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
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

        if (isset($this->request->post['limit'])) {
            $data['limit'] = $this->request->post['limit'];
        } elseif (!empty($module_info)) {
            $data['limit'] = $module_info['limit'];
        } else {
            $data['limit'] = 5;
        }

        if (isset($this->request->post['description_length'])) {
            $data['description_length'] = $this->request->post['description_length'];
        } elseif (!empty($module_info)) {
            $data['description_length'] = $module_info['description_length'];
        } else {
            $data['description_length'] = 300;
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($module_info)) {
            $data['image'] = $module_info['image'];
        } else {
            $data['image'] = 0;
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($module_info)) {
            $data['width'] = $module_info['width'];
        } else {
            $data['width'] = 40;
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($module_info)) {
            $data['height'] = $module_info['height'];
        } else {
            $data['height'] = 40;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        $data['form_fields'] = $this->getFormFields($data);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/blog_comment.tpl', $data));
    }

    protected function getFormFields($data)
    {
        $action = str_replace('amp;', '', $data['action']);

        $option_text = array('yes' => $this->language->get('text_enabled'), 'no' => $this->language->get('text_disabled'));

        $form = new AForm('form-blog-latest', $action);

        $name = array('value' => $data['name'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_name'), 'name', $name));

        $limit = array('value' => $data['limit'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_limit'), 'limit', $limit));

        $description_length = array('value' => $data['description_length'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_description_length'), 'description_length', $description_length));

        $image = array('value' => $data['image'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_image'), 'image', $image, $option_text));

        $width = array('value' => $data['width'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_width'), 'width', $width));

        $height = array('value' => $data['height'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_height'), 'height', $height));

        $status = array('value' => $data['status'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'status', $status, $option_text));

        return $form->render(true);
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/blog_comment')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->post['width']) {
            $this->error['width'] = $this->language->get('error_width');
        }

        if (!$this->request->post['height']) {
            $this->error['height'] = $this->language->get('error_height');
        }

        return !$this->error;
    }
}
