<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Arastta\Component\Form\Form as AForm;

class ControllerModuleProducts extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('module/products');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('products', $this->request->post);
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

                $this->response->redirect($this->url->link('module/products', 'token=' . $this->session->data['token'] . $module_id, 'SSL'));
            }

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('module/products', 'token=' . $this->session->data['token'], 'SSL'));
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
            $data['action'] = $this->url->link('module/products', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('module/products', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
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

        if (isset($this->request->post['title'])) {
            $data['title'] = $this->request->post['title'];
        } elseif (!empty($module_info)) {
            $data['title'] = $module_info['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->request->post['show_title'])) {
            $data['show_title'] = $this->request->post['show_title'];
        } elseif (!empty($module_info)) {
            $data['show_title'] = $module_info['show_title'];
        } else {
            $data['show_title'] = '0';
        }

        if (isset($this->request->post['module_class'])) {
            $data['module_class'] = $this->request->post['module_class'];
        } elseif (!empty($module_info)) {
            $data['module_class'] = $module_info['module_class'];
        } else {
            $data['module_class'] = '';
        }

        if (isset($this->request->post['module_column'])) {
            $data['module_column'] = $this->request->post['module_column'];
        } elseif (!empty($module_info)) {
            $data['module_column'] = $module_info['module_column'];
        } else {
            $data['module_column'] = '4';
        }

        if (isset($this->request->post['type'])) {
            $data['type'] = $this->request->post['type'];
        } elseif (!empty($module_info)) {
            $data['type'] = $module_info['type'];
        } else {
            $data['type'] = '';
        }

        // Categories
        $this->load->model('catalog/category');

        if (isset($this->request->post['category'])) {
            $categories = $this->request->post['category'];
        } elseif (!empty($module_info['category'])) {
            $categories = $module_info['category'];
        } else {
            $categories = array();
        }

        $data['categories'] = array();

        if (!empty($categories)) {
            foreach ($categories as $category_id) {
                $category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {
                    $data['categories'][] = array(
                        'category_id' => $category_info['category_id'],
                        'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                    );
                }
            }
        }

        // Products
        $this->load->model('catalog/product');

        $data['products'] = array();

        if (!empty($this->request->post['product'])) {
            $products = $this->request->post['product'];
        } elseif (!empty($module_info['product'])) {
            $products = $module_info['product'];
        } else {
            $products = array();
        }

        if (!empty($products)) {
            foreach ($products as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    $data['products'][] = array(
                        'product_id' => $product_info['product_id'],
                        'name'       => $product_info['name']
                    );
                }
            }
        }

        if (isset($this->request->post['random_product'])) {
            $data['random_product'] = $this->request->post['random_product'];
        } elseif (!empty($module_info)) {
            $data['random_product'] = $module_info['random_product'];
        } else {
            $data['random_product'] = '0';
        }

        if (isset($this->request->post['limit'])) {
            $data['limit'] = $this->request->post['limit'];
        } elseif (!empty($module_info)) {
            $data['limit'] = $module_info['limit'];
        } else {
            $data['limit'] = '4';
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($module_info)) {
            $data['width'] = $module_info['width'];
        } else {
            $data['width'] = '200';
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($module_info)) {
            $data['height'] = $module_info['height'];
        } else {
            $data['height'] = '200';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = 1;
        }

        if (isset($this->request->post['product_image'])) {
            $data['product_image'] = $this->request->post['product_image'];
        } elseif (!empty($module_info)) {
            $data['product_image'] = $module_info['product_image'];
        } else {
            $data['product_image'] = '1';
        }

        if (isset($this->request->post['product_name'])) {
            $data['product_name'] = $this->request->post['product_name'];
        } elseif (!empty($module_info)) {
            $data['product_name'] = $module_info['product_name'];
        } else {
            $data['product_name'] = '1';
        }

        if (isset($this->request->post['product_description'])) {
            $data['product_description'] = $this->request->post['product_description'];
        } elseif (!empty($module_info)) {
            $data['product_description'] = $module_info['product_description'];
        } else {
            $data['product_description'] = '1';
        }

        if (isset($this->request->post['product_rating'])) {
            $data['product_rating'] = $this->request->post['product_rating'];
        } elseif (!empty($module_info)) {
            $data['product_rating'] = $module_info['product_rating'];
        } else {
            $data['product_rating'] = '1';
        }

        if (isset($this->request->post['product_price'])) {
            $data['product_price'] = $this->request->post['product_price'];
        } elseif (!empty($module_info)) {
            $data['product_price'] = $module_info['product_price'];
        } else {
            $data['product_price'] = '1';
        }

        if (isset($this->request->post['add_to_cart'])) {
            $data['add_to_cart'] = $this->request->post['add_to_cart'];
        } elseif (!empty($module_info)) {
            $data['add_to_cart'] = $module_info['add_to_cart'];
        } else {
            $data['add_to_cart'] = '1';
        }

        if (isset($this->request->post['product_image'])) {
            $data['wish_list'] = $this->request->post['wish_list'];
        } elseif (!empty($module_info)) {
            $data['wish_list'] = $module_info['wish_list'];
        } else {
            $data['wish_list'] = '1';
        }

        if (isset($this->request->post['product_image'])) {
            $data['compare'] = $this->request->post['compare'];
        } elseif (!empty($module_info)) {
            $data['compare'] = $module_info['compare'];
        } else {
            $data['compare'] = '1';
        }

        $data['form_fields'] = $this->getFormFields($data);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/products.tpl', $data));
    }

    protected function getFormFields($data)
    {
        $action = str_replace('amp;', '', $data['action']);

        $option_text = array('yes' => $this->language->get('text_enabled'), 'no' => $this->language->get('text_disabled'));

        $form = new AForm('form-cart', $action);

        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_general') . '</legend>'));

        $name = array('value' => $data['name'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_name'), 'name', $name));

        $title = array('value' => $data['title'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_title'), 'title', $title));

        $show_title = array('value' => $data['show_title'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_show_title'), 'show_title', $show_title, $option_text));

        $module_class = array('value' => $data['module_class']);
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_module_class'), 'module_class', $module_class));

        $module_column = array('value' => $data['module_column'], 'selected' => $data['module_column'], 'id' => 'input-module-column');
        $module_column_option  = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '6' => '6');
        $form->addElement(new Arastta\Component\Form\Element\Select($this->language->get('entry_module_column'), 'module_column', $module_column_option, $module_column, $option_text));

        $type        = array('value' => $data['type'], 'selected' => $data['type'], 'id' => 'input-type');
        $type_option = array('all' => $this->language->get('text_all'), 'bestsellers' => $this->language->get('text_bestsellers'), 'featured' => $this->language->get('text_featured'), 'latest' => $this->language->get('text_latest'), 'special' => $this->language->get('text_special'));
        $form->addElement(new Arastta\Component\Form\Element\Select($this->language->get('entry_type'), 'type', $type_option, $type));

        $html  = '<div class="form-group">';
        $html .= '    <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="' . $this->language->get('help_category') . '">' . $this->language->get('entry_category') . '</span></label>';
        $html .= '    <div class="col-sm-10">';
        $html .= '        <input type="text" name="category" value="" placeholder="' . $this->language->get('entry_category') . '" id="input-category" class="form-control" />';
        $html .= '        <div id="categories" class="well well-sm" style="height: 150px; overflow: auto;">';
                            foreach ($data['categories'] as $category) {
        $html .= '          <div id="categories' .  $category['category_id'] . '"><i class="fa fa-minus-circle"></i> '. $category['name'];
        $html .= '            <input type="hidden" name="category[]" value="' . $category['category_id'] . '" />';
        $html .= '          </div>';
                            }
        $html .= '        </div>';
        $html .= '    </div>';
        $html .= '</div>';

        $form->addElement(new Arastta\Component\Form\Element\HTML($html));

        $html  = '<div class="form-group">';
        $html .= '    <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="' . $this->language->get('help_product') . '">' . $this->language->get('entry_product') . '</span></label>';
        $html .= '    <div class="col-sm-10">';
        $html .= '        <input type="text" name="product" value="" placeholder="' . $this->language->get('entry_product') . '" id="input-product" class="form-control" />';
        $html .= '        <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">';
                            foreach ($data['products'] as $product) {
        $html .= '          <div id="featured-product' .  $product['product_id'] . '"><i class="fa fa-minus-circle"></i> '. $product['name'];
        $html .= '            <input type="hidden" name="product[]" value="' . $product['product_id'] . '" />';
        $html .= '          </div>';
                            }
        $html .= '        </div>';
        $html .= '    </div>';
        $html .= '</div>';

        $form->addElement(new Arastta\Component\Form\Element\HTML($html));

        $random_product = array('value' => $data['random_product'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_random_product'), 'random_product', $random_product, $option_text));

        $limit = array('value' => $data['limit'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_limit'), 'limit', $limit));

        $width = array('value' => $data['width'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_width'), 'width', $width));

        $height = array('value' => $data['height'], 'required' => 'required');
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_height'), 'height', $height));

        $status = array('value' => $data['status'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'status', $status, $option_text));

        $form->addElement(new Arastta\Component\Form\Element\HTML('<legend>' . $this->language->get('text_view') . '</legend>'));

        $product_image = array('value' => $data['product_image'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_image'), 'product_image', $product_image, $option_text));

        $product_name = array('value' => $data['product_name'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_pname'), 'product_name', $product_name, $option_text));

        $product_description = array('value' => $data['product_description'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_pdescription'), 'product_description', $product_description, $option_text));

        $product_rating = array('value' => $data['product_rating'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_rating'), 'product_rating', $product_rating, $option_text));

        $product_price = array('value' => $data['product_price'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_price'), 'product_price', $product_price, $option_text));

        $add_to_cart = array('value' => $data['add_to_cart'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_add_to_cart'), 'add_to_cart', $add_to_cart, $option_text));

        $wish_list = array('value' => $data['wish_list'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_wish_list'), 'wish_list', $wish_list, $option_text));

        $compare = array('value' => $data['compare'], 'labelclass' => 'radio-inline');
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_compare'), 'compare', $compare, $option_text));

        return $form->render(true);
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/products')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }
}
