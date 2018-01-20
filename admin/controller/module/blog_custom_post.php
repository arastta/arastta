<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Arastta\Component\Form\Form as AForm;

class ControllerModuleBlogCustomPost extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('module/blog_custom_post');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('blog_custom_post', $this->request->post);
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

                $this->response->redirect($this->url->link('module/blog_custom_post', 'token=' . $this->session->data['token'] . $module_id, 'SSL'));
            }

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('module/blog_custom_post', 'token=' . $this->session->data['token'], 'SSL'));
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
            $data['action'] = $this->url->link('module/blog_custom_post', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('module/blog_custom_post', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
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

        $this->load->model('blog/post');

        $data['posts'] = array();

        if (!empty($this->request->post['post'])) {
            $posts = $this->request->post['post'];
        } elseif (!empty($module_info['post'])) {
            $posts = $module_info['post'];
        } else {
            $posts = array();
        }

        if (!empty($posts)) {
            foreach ($posts as $post_id) {
                $post_info = $this->model_blog_post->getPost($post_id);

                if ($post_info) {
                    $data['posts'][] = array(
                        'post_id' => $post_info['post_id'],
                        'name'    => $post_info['name']
                    );
                }
            }
        }

        if (isset($this->request->post['random_post'])) {
            $data['random_post'] = $this->request->post['random_post'];
        } else {
            if (!empty($module_info) && isset($module_info['random_post'])) {
                $data['random_post'] = $module_info['random_post'];
            } else {
                $data['random_post'] = '0';
            }
        }

        if (isset($this->request->post['limit'])) {
            $data['limit'] = $this->request->post['limit'];
        } elseif (!empty($module_info)) {
            $data['limit'] = $module_info['limit'];
        } else {
            $data['limit'] = 5;
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

        $this->response->setOutput($this->load->view('module/blog_custom_post.tpl', $data));
    }

    protected function getFormFields($data)
    {
        $action = str_replace('amp;', '', $data['action']);

        $option_text = array('yes' => $this->language->get('text_enabled'), 'no' => $this->language->get('text_disabled'));

        $form = new AForm('form-blog-feature', $action);

        $name = array('value' => $data['name'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_name'), 'name', $name));

        $html  = '<div class="form-group">';
        $html .= '    <label class="col-sm-2 control-label" for="input-post"><span data-toggle="tooltip" title="' . $this->language->get('help_post') . '">' . $this->language->get('entry_post') . '</span></label>';
        $html .= '    <div class="col-sm-10">';
        $html .= '        <input type="text" name="post" value="" placeholder="' . $this->language->get('entry_post') . '" id="input-post" class="form-control" />';
        $html .= '        <div id="featured-post" class="well well-sm" style="height: 150px; overflow: auto;">';
        foreach ($data['posts'] as $post) {
            $html .= '          <div id="featured-post' .  $post['post_id'] . '"><i class="fa fa-minus-circle"></i> '. $post['name'];
            $html .= '            <input type="hidden" name="post[]" value="' . $post['post_id'] . '" />';
            $html .= '          </div>';
        }
        $html .= '        </div>';
        $html .= '    </div>';
        $html .= '</div>';

        $form->addElement(new Arastta\Component\Form\Element\HTML($html));

        $random_post = array('value' => $data['random_post'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_random_post'), 'random_post', $random_post, $option_text));

        $limit = array('value' => $data['limit'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_limit'), 'limit', $limit));

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
        if (!$this->user->hasPermission('modify', 'module/blog_custom_post')) {
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
