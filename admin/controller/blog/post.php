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

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/post');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('blog/post');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/post');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $post_id = $this->model_blog_post->addPost($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_author'])) {
                $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('blog/post/edit', 'post_id=' . $post_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('blog/post/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('blog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('blog/post');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/post');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_blog_post->editPost($this->request->get['post_id'], $this->request->post);

            if (isset($this->request->post['selected'])) {
                if ($this->request->post['set_as'] == 'delete') {
                    foreach ($this->request->post['selected'] as $blog_comment_id) {
                        $this->model_blog_post->deleteComments($blog_comment_id);
                    }
                } elseif ($this->request->post['set_as'] == 'disabled') {
                    foreach ($this->request->post['selected'] as $blog_comment_id) {
                        $this->model_blog_post->disableComments($blog_comment_id);
                    }
                } elseif ($this->request->post['set_as'] == 'enabled') {
                    foreach ($this->request->post['selected'] as $blog_comment_id) {
                        $this->model_blog_post->enableComments($blog_comment_id);
                    }
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_author'])) {
                $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('blog/post/edit', 'post_id=' . $this->request->get['post_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('blog/post/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('blog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('blog/post');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('blog/post');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $post_id) {
                $this->model_blog_post->deletePost($post_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_author'])) {
                $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->response->redirect($this->url->link('blog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_author'])) {
            $filter_author = $this->request->get['filter_author'];
        } else {
            $filter_author = null;
        }

        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_author'])) {
            $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        #Get All Language Text
        $data = $this->language->all();

        $data['add']    = $this->url->link('blog/post/add', 'token=' . $this->session->data['token'], 'SSL');
        $data['delete'] = $this->url->link('blog/post/delete', 'token=' . $this->session->data['token'], 'SSL');

        $data['posts'] = array();

        $filter_data = array(
            'filter_name'     => $filter_name,
            'filter_author'   => $filter_author,
            'filter_category' => $filter_category,
            'filter_status'   => $filter_status,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');

        $post_total = $this->model_blog_post->getTotalPosts();

        $results = $this->model_blog_post->getPosts($filter_data);

        $this->load->model('blog/category');

        $data['categories'] = $this->model_blog_category->getCategories(0);

        foreach ($results as $result) {
            $category = $this->model_blog_post->getPostCategories($result['post_id']);

            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $data['posts'][] = array(
                'post_id'       => $result['post_id'],
                'image'         => $image,
                'name'          => $result['name'],
                'category'      => $category,
                'comment_total' => $this->model_blog_post->getTotalCommentsByPostId($result['post_id']),
                'viewed'        => $result['viewed'],
                'date_added'    => $result['date_added'],
                'status'        => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'sort_order'    => $result['sort_order'],
                'edit'          => $this->url->link('blog/post/edit', 'token=' . $this->session->data['token'] . '&post_id=' . $result['post_id'] . $url, 'SSL')
            );
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_author'])) {
            $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name']       = $this->url->link('blog/post', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
        $data['sort_category']   = $this->url->link('blog/post', 'token=' . $this->session->data['token'] . '&sort=cd.name' . $url, 'SSL');
        $data['sort_status']     = $this->url->link('blog/post', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('blog/post', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('blog/post', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_author'])) {
            $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination        = new Pagination();
        $pagination->total = $post_total;
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url   = $this->url->link('blog/post', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($post_total - $this->config->get('config_limit_admin'))) ? $post_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $post_total, ceil($post_total / $this->config->get('config_limit_admin')));

        $data['filter_name']     = $filter_name;
        $data['filter_author']   = $filter_author;
        $data['filter_category'] = $filter_category;
        $data['filter_status']   = $filter_status;

        $data['sort']  = $sort;
        $data['order'] = $order;

        $data['sortable'] = (isset($this->request->get['sortable']) && $this->request->get['sortable'] == 'active') ? true : false;

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/post_list.tpl', $data));
    }

    protected function getForm()
    {
        #Get All Language Text
        $data = $this->language->all();

        $data['text_form'] = !isset($this->request->get['post_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['token'] = $this->session->data['token'];

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

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = array();
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_author'])) {
            $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (!isset($this->request->get['post_id'])) {
            $data['action'] = $this->url->link('blog/post/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('blog/post/edit', 'token=' . $this->session->data['token'] . '&post_id=' . $this->request->get['post_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('blog/post', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['post_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $post_info = $this->model_blog_post->getPost($this->request->get['post_id']);
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['post_description'])) {
            $data['post_description'] = $this->request->post['post_description'];
        } elseif (isset($this->request->get['post_id'])) {
            $data['post_description'] = $this->model_blog_post->getPostDescriptions($this->request->get['post_id']);
        } else {
            $data['post_description'] = array();
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($post_info)) {
            $data['status'] = $post_info['status'];
        } else {
            $data['status'] = 1;
        }

        $this->load->model('user/user');

        $user_info = $this->model_user_user->getUser($this->user->getId());

        if ($user_info) {
            $data['author'] = $user_info['firstname'];
        }

        if (isset($this->request->post['author'])) {
            $data['author'] = $this->request->post['author'];
        } elseif (isset($post_info)) {
            $data['author'] = $post_info['author'];
        } else {
            $data['author'] = $user_info['firstname'];
        }

        if (isset($this->request->post['allow_comment'])) {
            $data['allow_comment'] = $this->request->post['allow_comment'];
        } elseif (isset($post_info)) {
            $data['allow_comment'] = $post_info['allow_comment'];
        } else {
            $data['allow_comment'] = 1;
        }

        if (isset($this->request->post['featured'])) {
            $data['featured'] = $this->request->post['featured'];
        } elseif (isset($post_info)) {
            $data['featured'] = $post_info['featured'];
        } else {
            $data['featured'] = 0;
        }

        if (isset($this->request->post['date_available'])) {
            $data['date_available'] = $this->request->post['date_available'];
        } elseif (!empty($post_info)) {
            $data['date_available'] = ($post_info['date_available'] != '0000-00-00') ? $post_info['date_available'] : '';
        } else {
            $data['date_available'] = date('Y-m-d');
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['post_store'])) {
            $data['post_store'] = $this->request->post['post_store'];
        } elseif (isset($post_info)) {
            $data['post_store'] = $this->model_blog_post->getPostStores($this->request->get['post_id']);
        } else {
            $data['post_store'] = array(0);
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (isset($post_info)) {
            $data['image'] = $post_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($post_info) && is_file(DIR_IMAGE . $post_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($post_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $this->load->model('blog/category');

        if (isset($this->request->post['post_category'])) {
            $data['post_category'] = $this->request->post['post_category'];
        } elseif (isset($post_info)) {
            $data['post_category'] = $this->model_blog_post->getPostCategories($this->request->get['post_id']);
        } else {
            $data['post_category'] = array();
        }

        $data['post_categories'] = $this->model_blog_category->getCategories(0);

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (isset($post_info)) {
            $data['sort_order'] = $post_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        if (isset($this->request->post['post_layout'])) {
            $data['post_layout'] = $this->request->post['post_layout'];
        } elseif (isset($post_info)) {
            $data['post_layout'] = $this->model_blog_post->getPostLayouts($this->request->get['post_id']);
        } else {
            $data['post_layout'] = array();
        }

        $data['post_id'] = isset($this->request->get['post_id']) ? $this->request->get['post_id'] : 0;

        // Preview link
        foreach ($data['languages'] as $language) {
            $data['preview'][$language['language_id']] = $this->getSeoLink($data['post_id'], $language['code']);
        }

        // Show All
        foreach ($data['languages'] as $language) {
            $data['show_all'][$language['language_id']] = array(
                'category' => $this->url->link('catalog/product/viewAll', 'type=category&post_id=' . $data['post_id'] . '&language_id=' . $language['language_id'] . '&token=' . $this->session->data['token'], 'SSL'),
                'tag'      => $this->url->link('catalog/product/viewAll', 'type=tag&post_id=' . $data['post_id'] . '&language_id=' . $language['language_id'] . '&token=' . $this->session->data['token'], 'SSL')
            );
        }

        $data['language_code'] = isset($this->session->data['admin_language']) ? $this->session->data['admin_language'] : $this->config->get('config_admin_language');

        $this->load->model('appearance/layout');

        $data['layouts'] = $this->model_appearance_layout->getLayouts();

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('blog/post_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'blog/post')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['post_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'blog/post')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('blog/post');

            $results = $this->model_blog_post->getPosts(0);

            foreach ($results as $result) {
                $json[] = array(
                    'post_id' => $result['post_id'],
                    'name'    => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function inline()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateInline()) {
            $this->load->model('blog/post');

            if (isset($this->request->post['seo_url'])) {
                $this->load->model('catalog/url_alias');

                $this->model_catalog_url_alias->addAlias('blog_post', $this->request->get['post_id'], $this->request->post['seo_url'], $this->request->post['language_id']);

                $json['language_id'] = $this->request->post['language_id'];
            } else {
                foreach ($this->request->post as $key => $value) {
                    $this->model_blog_post->updatePost($this->request->get['post_id'], $key, $value);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateInline()
    {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['image']) && !isset($this->request->post['name']) && !isset($this->request->post['price']) && !isset($this->request->post['special']) && !isset($this->request->post['quantity']) && !isset($this->request->post['status']) && !isset($this->request->post['seo_url'])) {
            $this->error['warning'] = $this->language->get('error_inline_field');
        }

        return !$this->error;
    }

    public function getSeoLink($post_id, $language_code)
    {
        $old_session_code = isset($this->session->data['language']) ? $this->session->data['language'] : '';
        $old_config_code  = $this->config->get('config_language');

        $this->session->data['language'] = $language_code;
        $this->config->set('config_language', $language_code);

        $url = $this->config->get('config_url');

        if (empty($url)) {
            $url = HTTP_SERVER;

            $admin_folder = str_replace(DIR_ROOT, '', DIR_ADMIN);

            $url = str_replace($admin_folder, '', $url);
        }

        $route = new Route($this->registry);

        $url .= ltrim($route->rewrite('index.php?route=blog/post&post_id=' . $post_id), '/');

        if (!empty($old_session_code)) {
            $this->session->data['language'] = $old_session_code;
        }

        $this->config->set('config_language', $old_config_code);

        return $url;
    }

    public function viewAll()
    {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $data['text_applicable'] = $this->language->get('text_applicable');
        $data['text_applied']    = $this->language->get('text_applied');

        $type = $this->request->get['type'];

        $data['type'] = $type;

        switch ($type) {
            case 'manufacturer':
                $data['text_all'] = $this->language->get('entry_all_manufacturer');

                $this->load->model('catalog/manufacturer');

                $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

                $data['applied'][] = $product_info['manufacturer_id'];

                $data['all'] = $this->model_catalog_manufacturer->getManufacturers();
                break;
            case 'category':
                $data['text_all'] = $this->language->get('entry_all_category');

                $this->load->model('catalog/category');

                $categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);

                foreach ($categories as $category_id) {
                    $category_info = $this->model_catalog_category->getCategory($category_id);

                    if ($category_info) {
                        $data['applied'][] = $category_info['category_id'];
                    }
                }

                $data['all'] = $this->model_catalog_category->getCategories();
                break;
            case 'tag':
                $data['text_all'] = $this->language->get('entry_all_tags');

                $tags = $this->model_catalog_product->getProductTags($this->request->get['product_id']);

                $data['applied'] = (!empty($tags)) ? $tags : array();

                $data['all'] = $this->model_catalog_product->getTags();
                break;
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->response->setOutput($this->load->view('common/show_all.tpl', $data));
    }
}
