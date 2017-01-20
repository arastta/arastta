<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerToolCache extends Controller
{

    public function index()
    {
        $this->load->language('tool/cache');

        $this->load->model('tool/cache');

        $data = $this->language->all();

        $data['text_confirm_title'] = sprintf($this->language->get('text_confirm_title'), $this->language->get('heading_title'));

        $this->document->setTitle($data['heading_title']);

        $data['delete'] = $this->url->link('tool/cache/delete', 'token=' . $this->session->data['token'], 'SSL');
        $data['delete_all'] = $this->url->link('tool/cache/deleteAll', 'token=' . $this->session->data['token'], 'SSL');

        $data['caches'] = array();

        $this->trigger->fire('pre.admin.cache.get', array(&$data['caches']));

        $system_cache = $this->model_tool_cache->getCache(DIR_CACHE, '/\.data$/i');
        $image_cache = $this->model_tool_cache->getCache(DIR_IMAGE . 'cache', '/\.(gif|jpg|jpeg|tiff|png)$/i');
        $modification_cache = $this->model_tool_cache->getCache(DIR_MODIFICATION);

        $data['caches']['system'] = array('name' => $this->language->get('text_group_system'), 'files' => $system_cache[0], 'size' => $system_cache[1]);
        $data['caches']['image'] = array('name' => $this->language->get('text_group_image'), 'files' => $image_cache[0], 'size' => $image_cache[1]);
        $data['caches']['modification'] = array('name' => $this->language->get('text_group_modification'), 'files' => $modification_cache[0], 'size' => $modification_cache[1]);

        $this->trigger->fire('post.admin.cache.get', array(&$data['caches']));

        $cache_total = count($data['caches']);

        $data['token'] = $this->session->data['token'];

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } elseif (isset($this->error['warning'])) {
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
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $pagination = new Pagination();
        $pagination->total = $cache_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('tool/cache', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cache_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cache_total - $this->config->get('config_limit_admin'))) ? $cache_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cache_total, ceil($cache_total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/cache.tpl', $data));
    }

    public function delete()
    {
        $this->load->language('tool/cache');

        $this->load->model('tool/cache');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $group) {
                switch ($group) {
                    case 'system':
                        if ($this->cache->clear()) {
                            $this->session->data['success'] = $this->language->get('text_success');
                        } else {
                            $this->session->data['warning'] = $this->language->get('error_fail');
                        }
                        break;
                    case 'image':
                        if ($this->model_tool_cache->deleteCache(DIR_IMAGE . 'cache', '/\.(gif|jpg|jpeg|tiff|png)$/i')) {
                            $this->session->data['success'] = $this->language->get('text_success');
                        } else {
                            $this->session->data['warning'] = $this->language->get('error_fail');
                        }
                        break;
                    case 'modification':
                        $this->request->get['extensionInstaller'] = 1;
                        $this->load->controller('extension/modification/refresh');
                        unset($this->request->get['extensionInstaller']);

                        $this->session->data['success'] = $this->language->get('text_success');
                        break;
                    default:
                        $path = $this->trigger->fire('pre.admin.cache.getpath', array(&$group));

                        if ($this->model_tool_cache->deleteCache($path)) {
                            $this->session->data['success'] = $this->language->get('text_success');
                        } else {
                            $this->session->data['warning'] = $this->language->get('error_fail');
                        }
                        break;
                }
            }
        }

        $this->response->redirect($this->url->link('tool/cache', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function deleteAll()
    {
        $this->load->language('tool/cache');

        $this->load->model('tool/cache');

        if ($this->validateDelete()) {
            $status = true;

            // System cache
            if (!$this->cache->clear()) {
                $status = false;
            }

            // Image cache
            if (!$this->model_tool_cache->deleteCache(DIR_IMAGE . 'cache', '/\.(gif|jpg|jpeg|tiff|png)$/i')) {
                $status = false;
            }

            // Modification cache
            $this->request->get['extensionInstaller'] = 1;
            $this->load->controller('extension/modification/refresh');

            unset($this->request->get['extensionInstaller']);

            if ($status) {
                $this->session->data['success'] = $this->language->get('text_success');
            } else {
                $this->session->data['warning'] = $this->language->get('error_fail');
            }
        }

        $this->response->redirect($this->url->link('tool/cache', 'token=' . $this->session->data['token'], 'SSL'));
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'tool/cache')) {
            $this->session->data['warning'] = $this->language->get('error_permission');
        }

        return empty($this->session->data['warning']);
    }
}
