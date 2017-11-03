<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerBlogCategory extends Controller
{

    public function index()
    {
        $this->load->language('blog/category');

        $this->load->model('blog/category');

        $this->load->model('blog/post');
        $this->load->model('blog/comment');

        $this->load->model('tool/image');

        #Get All Language Text
        $data = $this->language->all();

        $filter = '';

        if (isset($this->request->get['filter'])) {
            $filter = urldecode($this->request->get['filter']);
        }

        $default_post_sort_order = explode('-', $this->config->get('config_blog_post_list_sort_order'));

        if (count($default_post_sort_order) > 1) {
            $sort  = $default_post_sort_order[0];
            $order = $default_post_sort_order[1];
        } else {
            $sort  = $default_post_sort_order[0];
            $order = 'ASC';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        }

        $page = 1;

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        }

        $limit = $this->config->get('config_product_limit');

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        }

        $data['breadcrumbs'] = array();

        $menu_home = $this->model_blog_post->checkMenu('home');

        $text_home = ($menu_home) ? $menu_home : $this->language->get('text_blog') ;

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $text_home,
            'href' => $this->url->link('blog/home')
        );

        if (isset($this->request->get['path'])) {
            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $path = '';

            $parts = explode('_', (string)$this->request->get['path']);

            $category_id = (int)array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int)$path_id;
                } else {
                    $path .= '_' . (int)$path_id;
                }

                $category_info = $this->model_blog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('blog/category', 'path=' . $path . $url)
                    );
                }
            }
        } else {
            $category_id = 0;
        }

        $category_info = $this->model_blog_category->getCategory($category_id);

        if ($category_info) {
            $title = empty($category_info['meta_title']) ? $category_info['name'] : $category_info['meta_title'];

            $this->document->setTitle($title);
            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);
            if (!$this->config->get('config_seo_url')) {
                $this->document->addLink($this->url->link('blog/category', 'path=' . $this->request->get['path']), 'canonical');
            }

            if (is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')) {
                $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css');
            } else {
                $this->document->addStyle('catalog/view/theme/default/stylesheet/blog.css');
            }

            $data['heading_title'] = $category_info['name'];

            // Set the last category breadcrumb
            $data['breadcrumbs'][] = array(
                'text' => $category_info['name'],
                'href' => $this->url->link('blog/category', 'path=' . $this->request->get['path'])
            );

            $data['thumb'] = '';

            if ($category_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
            }

            $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

            $data['author'] = $this->config->get('config_blog_post_list_author');
            $data['category'] = $this->config->get('config_blog_post_list_category', 1);
            $data['date_added'] = $this->config->get('config_blog_post_list_date');
            $data['viewed'] = $this->config->get('config_blog_post_list_read');

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . urldecode($this->request->get['filter']);
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['categories'] = array();

            $results = $this->model_blog_category->getCategories($category_id);

            foreach ($results as $result) {
                $filter_data = array(
                    'filter_category_id'  => $result['category_id'],
                    'filter_sub_category' => true
                );

                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));

                $data['categories'][] = array(
                    'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_blog_post->getTotalPosts($filter_data) . ')' : ''),
                    'href'  => $this->url->link('blog/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
                    'thumb' => $image
                );
            }

            $data['posts'] = array();

            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_filter'      => $filter,
                'sort'               => $sort,
                'order'              => $order,
                'start'              => ($page - 1) * $limit,
                'limit'              => $limit
            );

            $post_total = $this->model_blog_post->getTotalPosts($filter_data);

            $results = $this->model_blog_post->getPosts($filter_data);

            foreach ($results as $result) {
                $image = '';

                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_blog_post_list_width'), $this->config->get('config_blog_post_list_height'));
                }

                $category = $category_info['name'];

                $comment_total = $this->model_blog_comment->getTotalCommentsByPostId($result['post_id']);

                $this->trigger->fire('pre.post.display', array(&$result, 'category'));

                $data['posts'][] = array(
                    'post_id'       => $result['post_id'],
                    'thumb'         => $image,
                    'name'          => $result['name'],
                    'description'   => substr($result['description'], 0, $this->config->get('config_blog_post_list_description_length')) . '...',
                    'category'      => $category,
                    'category_href' => $this->url->link('blog/category', 'path=' . $category_id),
                    'viewed'        => $result['viewed'],
                    'comment_total' => sprintf((($comment_total > 1) ? $this->language->get('text_comment_totals') : $this->language->get('text_comment_total')), $comment_total),
                    'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
                    'author'        => $result['author'],
                    'author_href'   => $this->url->link('blog/home', 'filter_author=' . $result['author']),
                    'href'          => $this->url->link('blog/post', 'path=' . $this->request->get['path'] . '&post_id=' . $result['post_id'])
                );
            }

            $data['author'] = $this->config->get('config_blog_post_list_author');
            $data['date_added'] = $this->config->get('config_blog_post_list_date');
            $data['viewed'] = $this->config->get('config_blog_post_list_read');

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . urldecode($this->request->get['filter']);
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
            );

            if ($this->config->get('config_review_status')) {
                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
                );
            }

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href'  => $this->url->link('post/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
            );

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . urldecode($this->request->get['filter']);
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('blog/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
                );
            }

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . urldecode($this->request->get['filter']);
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $post_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('post/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($post_total - $limit)) ? $post_total : ((($page - 1) * $limit) + $limit), $post_total, ceil($post_total / $limit));

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;

            $data['continue'] = $this->url->link('common/home');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/category.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/category.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/blog/category.tpl', $data));
            }
        } else {
            $this->load->language('error/not_found');

            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . urldecode($this->request->get['filter']);
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            if (isset($this->request->get['blogpath'])) {
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_error'),
                    'href' => $this->url->link('blog/category', $url)
                );
            }

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left']    = $this->load->controller('common/column_left');
            $data['column_right']   = $this->load->controller('common/column_right');
            $data['content_top']    = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer']         = $this->load->controller('common/footer');
            $data['header']         = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }
}
