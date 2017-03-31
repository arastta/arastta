<?php

class ControllerBlogHome extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('blog/home');

        $this->load->model('blog/post');
        $this->load->model('blog/comment');

        $this->load->model('tool/image');

        #Get All Language Text
        $data = $this->language->all();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_blog'),
            'href' => $this->url->link('blog/home')
        );

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $limit = $this->config->get('config_blog_post_list_limit');

        $data['posts'] = array();
        $data['featured_posts'] = array();

        $results = $this->model_blog_post->getFeaturedPosts();

        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_blog_post_list_width'), $this->config->get('config_blog_post_list_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_blog_post_list_width'), $this->config->get('config_blog_post_list_height'));
            }

            $category = 'Test';

            $comment_total = $this->model_blog_comment->getTotalCommentsByPostId($result['post_id']);

            $this->trigger->fire('pre.blog.display', array(&$result, 'home'));

            $data['featured_posts'][] = array(
                'post_id'       => $result['post_id'],
                'thumb'         => $image,
                'name'          => $result['name'],
                'description'   => $result['description'],
                'category'      => $category,
                'viewed'        => $result['viewed'],
                'comment_total' => $comment_total,
                'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
                'author'        => $result['author'],
                'href'          => $this->url->link('blog/post', 'post_id=' . $result['post_id'])
            );
        }

        $filter_data = array(
            'start'      => ($page - 1) * $limit,
            'limit'      => $limit
        );

        $post_total = $this->model_blog_post->getTotalPosts($filter_data);

        $results = $this->model_blog_post->getPosts($filter_data);

        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_blog_post_list_width'), $this->config->get('config_blog_post_list_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_blog_post_list_width'), $this->config->get('config_blog_post_list_height'));
            }

            $category = 'Test';

            $comment_total = $this->model_blog_comment->getTotalCommentsByPostId($result['post_id']);

            $this->trigger->fire('pre.blog.display', array(&$result, 'home'));

            $data['posts'][] = array(
                'post_id'       => $result['post_id'],
                'thumb'         => $image,
                'name'          => $result['name'],
                'description'   => $result['description'],
                'category'      => $category,
                'viewed'        => $result['viewed'],
                'comment_total' => $comment_total,
                'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
                'author'        => $result['author'],
                'href'          => $this->url->link('blog/post', 'post_id=' . $result['post_id'])
            );
        }

        $data['author'] = $this->config->get('config_blog_post_list_author');
        $data['date_added'] = $this->config->get('config_blog_post_list_date');
        $data['viewed'] = $this->config->get('config_blog_post_list_read');

        $data['heading_title'] = $this->config->get('config_blog_name');

        $title = ($this->config->get('config_blog_meta_title')) ? $this->config->get('config_blog_meta_title') : $this->config->get('config_blog_name');

        $data['description'] = html_entity_decode($this->config->get('config_blog_description'), ENT_QUOTES, 'UTF-8');

        $this->document->setTitle($title);
        $this->document->setDescription($this->config->get('config_blog_meta_description'));
        $this->document->setKeywords($this->config->get('config_blog_meta_keyword'));
        if (!$this->config->get('config_seo_url')) {
            $this->document->addLink($this->url->link('blog/home'), 'canonical');
        }

        $this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
        $this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.transitions.css');
        $this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

        if (is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')) {
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/blog.css');
        }

        $data['description'] = html_entity_decode($this->config->get('config_blog_description'), ENT_QUOTES, 'UTF-8');

        $data['date_added_status']     = $this->config->get('blogsetting_date_added');
        $data['comments_count_status'] = $this->config->get('blogsetting_comments_count');
        $data['page_view_status']      = $this->config->get('blogsetting_page_view');
        $data['author_status']         = $this->config->get('blogsetting_author');
        $data['list_columns']          = $this->config->get('blogsetting_layout');

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $pagination        = new Pagination();
        $pagination->total = $post_total;
        $pagination->page  = $page;
        $pagination->limit = $limit;
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = $this->url->link('blog/home', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($post_total - $limit)) ? $post_total : ((($page - 1) * $limit) + $limit), $post_total, ceil($post_total / $limit));

        $data['column_left']    = $this->load->controller('common/column_left');
        $data['column_right']   = $this->load->controller('common/column_right');
        $data['content_top']    = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer']         = $this->load->controller('common/footer');
        $data['header']         = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/home.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/blog/home.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/blog/home.tpl', $data));
        }
    }
}
