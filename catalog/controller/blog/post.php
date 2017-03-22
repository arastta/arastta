<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerBlogPost extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('blog/post');

        #Get All Language Text
        $data = $this->language->all();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->load->model('blog/category');

        if (isset($this->request->get['path'])) {
            $path = '';

            $parts = explode('_', (string) $this->request->get['path']);

            $category_id = (int) array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = $path_id;
                } else {
                    $path .= '_' . $path_id;
                }

                $category_info = $this->model_blog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('blog/category', 'path=' . $path)
                    );
                }
            }

            // Set the last category breadcrumb
            $category_info = $this->model_blog_category->getCategory($category_id);

            if ($category_info) {
                $url = '';

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

                $data['breadcrumbs'][] = array(
                    'text' => $category_info['name'],
                    'href' => $this->url->link('blog/category', 'path=' . $this->request->get['path'] . $url)
                );
            }
        }

        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
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

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_search'),
                'href' => $this->url->link('blog/search', $url)
            );
        }

        if (isset($this->request->get['post_id'])) {
            $post_id = $this->request->get['post_id'];
        } else {
            $post_id = 0;
        }

        $this->load->model('blog/post');

        $post_info = $this->model_blog_post->getPost($post_id);

        if ($post_info) {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . urldecode($this->request->get['filter']);
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
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

            $data['breadcrumbs'][] = array(
                'text' => $post_info['name'],
                'href' => $this->url->link('blog/post', $url . '&post_id=' . $this->request->get['post_id'])
            );

            $title = empty($post_info['meta_title']) ? $post_info['name'] : $post_info['meta_title'];

            $this->document->setTitle($title);
            $this->document->setDescription($post_info['meta_description']);
            $this->document->setKeywords($post_info['meta_keyword']);
            if (!$this->config->get('config_seo_url')) {
                $this->document->addLink($this->url->link('blog/post', 'post_id=' . $this->request->get['post_id']), 'canonical');
            }

            if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')) {
                $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css');
            } else {
                $this->document->addStyle('catalog/view/theme/default/stylesheet/blog.css');
            }

            $data['heading_title'] = $post_info['name'];

            $data['post_id'] = (int)$this->request->get['post_id'];

            $this->load->model('blog/comment');

            $data['comment_total'] = $this->model_blog_comment->getTotalCommentsByPostId($this->request->get['post_id']);

            $this->load->model('tool/image');

            if ($post_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($post_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $data['thumb'] = '';
            }

            $data['date_added'] = false;

            if ($this->config->get('config_blog_post_form_date')) {
                $data['date_added'] = date($this->language->get('date_format_short'), strtotime($post_info['date_added']));
            }

            $data['author'] = false;

            if ($this->config->get('config_blog_post_form_author')) {
                $data['author'] = $post_info['author'];
            }

            $data['viewed'] = false;

            if ($this->config->get('config_blog_post_form_read')) {
                $data['viewed'] = $post_info['viewed'] + 1;
            }

            $data['post_id'] = (int) $this->request->get['post_id'];

            $data['comment_status'] = $this->config->get('config_blog_comment_enable');

            if ($this->config->get('config_comment_guest') || $this->customer->isLogged()) {
                $data['comment_guest'] = true;
            } else {
                $data['comment_guest'] = false;
            }

            if ($this->customer->isLogged()) {
                $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            } else {
                $data['customer_name'] = '';
            }

            $data['comments'] = sprintf($this->language->get('text_comments'), (int)$post_info['comments']);

            $data['description'] = html_entity_decode($post_info['description'], ENT_QUOTES, 'UTF-8');

            $this->trigger->fire('pre.post.display', array(&$data, 'post'));

            $data['tags'] = array();

            if ($post_info['tag']) {
                $tags = explode(',', $post_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'tag'  => trim($tag),
                        'href' => $this->url->link('blog/search', 'tag=' . trim($tag))
                    );
                }
            }

            $this->model_blog_post->updateViewed($this->request->get['post_id']);

            if ($this->config->get($this->config->get('config_captcha') . '_captcha_status')) {
                $data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $this->error);
            } else {
                $data['captcha'] = '';
            }

            # BC
            $data['site_key'] = '';

            $data['share'] = $this->url->link('blog/post', 'post_id=' . (int)$this->request->get['post_id']);

            $data['column_left']    = $this->load->controller('common/column_left');
            $data['column_right']   = $this->load->controller('common/column_right');
            $data['content_top']    = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer']         = $this->load->controller('common/footer');
            $data['header']         = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/post.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/post.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/blog/post.tpl', $data));
            }
        } else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . urldecode($this->request->get['filter']);
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
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

            $data['breadcrumbs'] [] = array(
                'href' => $this->url->link('blog/post', $url . '&post_id=' . $this->request->get['post_id']),
                'text' => $this->language->get('text_error')
            );

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

    public function comment()
    {
        $this->load->language('blog/post');

        $this->load->model('blog/comment');

        $data['text_comments'] = $this->language->get('text_comments');

        $data['text_no_comment'] = $this->language->get('text_no_comment');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['comments'] = array();

        $comment_total = $this->model_blog_comment->getTotalCommentsByPostId($this->request->get['post_id']);

        $limit = $this->config->get('config_blog_comment_limit');

        $results = $this->model_blog_comment->getCommentsByBlogId($this->request->get['post_id'], ($page - 1) * $limit, $limit);

        foreach ($results as $result) {
            $data['comments'][] = array(
                'author' => $result['author'],
                'email'  => $result['email'],
                'text'   => strip_tags($result['text']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination        = new Pagination();
        $pagination->total = $comment_total;
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_blog_comment_limit');
        $pagination->url   = $this->url->link('blog/post/comment', 'post_id=' . $this->request->get['post_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($comment_total - $limit)) ? $comment_total : ((($page - 1) * $limit) + $limit), $comment_total, ceil($comment_total / $limit));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/comment.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/comment.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/blog/comment.tpl', $data));
        }
    }

    public function write()
    {
        $this->load->language('blog/post');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 100)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['email']) < 3) || (utf8_strlen($this->request->post['email']) > 100)) {
                $json['error'] = $this->language->get('error_email');
            }

            if ((utf8_strlen($this->request->post['comment']) < 5) || (utf8_strlen($this->request->post['comment']) > 3000)) {
                $json['error'] = $this->language->get('error_comment');
            }

            if (($this->config->get($this->config->get('config_captcha') . '_captcha_status')) && empty($json['error'])) {
                $captcha_status = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

                if ($captcha_status == false) {
                    $json['captcha'] = $this->language->get('error_captcha');
                    $json['error'] = $this->language->get('error_captcha');
                }

                $json['captcha_extension'] = $this->config->get('config_captcha');
                $json['captcha_content'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $json);
            }

            if (!isset($json['error'])) {
                $this->load->model('blog/comment');

                $this->model_blog_comment->addComment($this->request->get['post_id'], $this->request->post);

                if ($this->config->get('blogsetting_comment_approve')) {
                    $json['success'] = $this->language->get('text_success_approve');
                } else {
                    $json['success'] = $this->language->get('text_success');
                }

                if ($this->config->get('blogsetting_comment_notification')) {
                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($this->config->get('config_email'));
                    $mail->setFrom($this->config->get('config_email'));
                    $mail->setSender($this->request->post['name']);
                    $mail->setSubject(sprintf($this->language->get('email_notification'), $this->request->post['name']));
                    $mail->setText(strip_tags($this->request->post['comment']));
                    $mail->send();
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
