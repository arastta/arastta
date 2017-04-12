<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventBlogPreviousNext extends Event
{
    public function prePostDisplay(&$data, $type)
    {
        if (isset($this->request->get['post_id'])) {
            $post_id = (int)$this->request->get['post_id'];
        } else {
            $post_id = 0;
        }

        if ($type != 'post' || !$post_id) {
            return;
        }

        $post_info = $this->model_blog_post->getPost($post_id);

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string)$this->request->get['path']);

            $category = array_pop($parts);
            $category_id = (int)$category;
        } else {
            $categories = $this->model_blog_post->getCategories($post_id);

            if (!$categories) {
                return;
            }

            $category = array_shift($categories);
            $category_id = (int)$category['category_id'];
        }

        $filter_data = array(
            'filter_category_id' => $category_id,
            'sort'               => 'pd.name',
            'order'              => 'ASC',
            'start'              => 0,
            'limit'              => 1000
        );

        $results = $this->model_blog_post->getPosts($filter_data);

        $post = false;

        $previous_post = $next_post = null;

        foreach ($results as $result) {
            if ($result['name'] == $post_info['name']) {
                $post = true;
            } elseif (!$post) {
                $previous_post = $result;
            } else {
                $next_post = $result;
                break;
            }
        }

        $data['next'] = array();
        $data['previous'] = array();

        if ($next_post) {
            $data['next'] = array(
                'name'  => $next_post['name'],
                'image' => ($next_post['image']) ? $this->model_tool_image->resize($next_post['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')) : '',
                'href'  => $this->url->link('blog/post', 'post_id=' .  $next_post['post_id'])
            );
        }

        if ($previous_post) {
            $data['previous'] = array(
                'name'  => $previous_post['name'],
                'image' => ($next_post['image']) ? $this->model_tool_image->resize($previous_post['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')) : '',
                'href'  => $this->url->link('blog/post', 'post_id=' . $previous_post['post_id'])
            );
        }
    }
}
