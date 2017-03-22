<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerModuleBlogComment extends Controller
{
    public function index($setting)
    {
        $this->load->language('module/blog_comment');

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('blog/comment');

        $this->load->model('tool/image');

        $data['comments'] = array();

        $filter_data = array(
            'sort'  => 'c.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $setting['limit']
        );

        $results = $this->model_blog_comment->getComments($filter_data);

        if ($results) {
            foreach ($results as $result) {
                $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);

                $this->trigger->fire('pre.comment.display', array(&$result, 'module'));

                $data['comments'][] = array(
                    'post_id'     => $result['post_id'],
                    'customer_id' => $result['customer_id'],
                    'name'        => $result['name'],
                    'text'        => $result['text'],
                    'thumb'       => $image,
                    'author'      => $result['author'],
                    'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'href'        => $this->url->link('blog/post', 'post_id=' . $result['post_id'])
                );
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_comment.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/blog_comment.tpl', $data);
            } else {
                return $this->load->view('default/template/module/blog_comment.tpl', $data);
            }
        }
    }
}
