<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerModuleBlogLatest extends Controller
{
    public function index($setting)
    {
        $this->load->language('module/blog_latest');

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('blog/post');
        $this->load->model('blog/comment');

        $this->load->model('tool/image');

        $data['posts'] = array();

        $filter_data = array(
            'sort'  => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $setting['limit']
        );

        $results = $this->model_blog_post->getPosts($filter_data);

        if ($results) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                }

                $comment_total = $this->model_blog_comment->getTotalCommentsByPostId($result['post_id']);

                $this->trigger->fire('pre.post.display', array(&$result, 'featured'));

                $data['posts'][] = array(
                    'post_id'       => $result['post_id'],
                    'thumb'         => $image,
                    'name'          => $result['name'],
                    'description'   => $result['description'],
                    'viewed'        => $result['viewed'],
                    'comment_total' => $comment_total,
                    'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
                    'author'        => $result['author'],
                    'href'          => $this->url->link('blog/post', 'post_id=' . $result['post_id'])
                );
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_latest.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/blog_latest.tpl', $data);
            } else {
                return $this->load->view('default/template/module/blog_latest.tpl', $data);
            }
        }
    }
}
