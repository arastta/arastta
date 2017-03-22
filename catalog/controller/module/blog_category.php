<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerModuleBlogCategory extends Controller
{
    public function index()
    {
        $this->load->language('module/blog_category');

        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);
        } else {
            $parts = array();
        }

        if (isset($parts[0])) {
            $data['category_id'] = $parts[0];
        } else {
            $data['category_id'] = 0;
        }

        if (isset($parts[1])) {
            $data['child_id'] = $parts[1];
        } else {
            $data['child_id'] = 0;
        }

        $this->load->model('blog/category');

        $this->load->model('blog/post');

        $data['categories'] = array();

        $categories = $this->model_blog_category->getCategories(0);

        foreach ($categories as $category) {
            $children_data = array();

            if ($category['category_id'] == $data['category_id']) {
                $children = $this->model_blog_category->getCategories($category['category_id']);

                foreach ($children as $child) {
                    $filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

                    $children_data[] = array(
                        'category_id' => $child['category_id'],
                        'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_blog_post->getTotalPosts($filter_data) . ')' : ''),
                        'href'        => $this->url->link('blog/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
                }
            }

            $filter_data = array(
                'filter_category_id'  => $category['category_id'],
                'filter_sub_category' => true
            );

            $data['categories'][] = array(
                'category_id' => $category['category_id'],
                'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_blog_post->getTotalPosts($filter_data) . ')' : ''),
                'children'    => $children_data,
                'href'        => $this->url->link('blog/category', 'path=' . $category['category_id'])
            );
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_category.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/blog_category.tpl', $data);
        } else {
            return $this->load->view('default/template/module/blog_category.tpl', $data);
        }
    }
}
