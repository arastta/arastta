<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Finder\Finder as SFinder;

class ControllerExtensionExtension extends Controller
{

    private $error = array();

    public function index()
    {
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/extension');

        // This must be changed as button after initial 1.2 release
        $this->discover();

        $this->getList();
    }

    public function discover()
    {
        $this->load->model('setting/setting');
        $this->load->model('extension/marketplace');

        $extension_types = array('captcha', 'editor', 'feed', 'module', 'other', 'payment', 'shipping', 'total', 'twofactorauth');

        $addons = $this->model_extension_marketplace->getAddons();
        $extensions = $this->model_extension_extension->getDiscoverExtensions();

        $finder = new SFinder();

        $finder->files()->in(DIR_ADMIN . 'controller')->name('/\.php$/');

        foreach ($finder as $file) {
            $type = $file->getRelativePath();

            if (!in_array($type, $extension_types)) {
                continue;
            }

            $code = str_replace('.php', '', $file->getFilename());

            if (isset($extensions[$type]) && array_key_exists($code, $extensions[$type])) {
                $extension_id = $extensions[$type][$code]['extension_id'];

                // Check if extension is available as addon
                foreach ($addons as $addon) {
                    $params = json_decode($addon['params'], true);

                    if (empty($params['extension_ids']) || !in_array($extension_id, $params['extension_ids'])) {
                        continue;
                    }

                    // Available as addon, nothing else required
                    continue 2;
                }
            } else {
                // Add extension
                $extension = array();
                $extension['type'] = $type;
                $extension['code'] = $code;
                $extension['info'] = array();
                $extension['params'] = array();

                $extension_id = $this->model_extension_extension->addExtension($extension);

                // Add setting
                $this->model_setting_setting->editSetting($code, array($code . '_status' => 0));

                // Add permission
                $this->load->model('user/user_group');
                $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $type . '/' . $code);
                $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $type . '/' . $code);

                // Call install method, if exists
                $this->load->controller($type . '/' . $code . '/install');
            }

            // Add addon
            $params = array();
            $params['theme_ids'] = array();
            $params['extension_ids'] = array($extension_id);

            $files = $this->getExtensionFiles($type, $code);

            $addon = array();
            $addon['params'] = $params;
            $addon['files'] = json_encode($files);
            $addon['product_id'] = '';
            $addon['product_name'] = '';
            $addon['product_type'] = '';
            $addon['product_version'] = '';

            $this->model_extension_marketplace->addAddon($addon);
        }

        $this->cache->remove('addon');
        $this->cache->remove('update');
        $this->cache->remove('version');
    }

    protected function getExtensionFiles($type, $code)
    {
        $files = array();

        $DS = DIRECTORY_SEPARATOR;

        $list = array();
        $list[] = 'controller' . $DS . $type . $DS . $code . '.php';
        $list[] = 'event' . $DS . 'app' . $DS . $code . '.php';
        $list[] = 'language' . $DS . 'en-GB' . $DS . $type . $DS . $code . '.php';
        $list[] = 'model' . $DS . $type . $DS . $code . '.php';
        $list[] = 'view' . $DS . 'template' . $DS . $type . $DS . $code . '.tpl';
        $list[] = 'view' . $DS . 'theme' . $DS . 'basic' . $DS . 'template' . $DS . $type . $DS . $code . '.tpl';
        $list[] = 'view' . $DS . 'theme' . $DS . 'default' . $DS . 'template' . $DS . $type . $DS . $code . '.tpl';

        $admin = str_replace(DIR_ROOT, '', DIR_ADMIN);
        $catalog = str_replace(DIR_ROOT, '', DIR_CATALOG);

        foreach ($list as $l) {
            if (file_exists(DIR_ADMIN . $l)) {
                $files[] = $admin . $l;
            }

            if (file_exists(DIR_CATALOG . $l)) {
                $files[] = $catalog . $l;
            }
        }

        return $files;
    }

    public function getList()
    {
        $data = $this->language->all();

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

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];

            $data['heading_title'] = $this->language->get('text_'.$filter_type);

            $this->document->setTitle($data['heading_title']);
        } else {
            $filter_type = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_author'])) {
            $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->request->get['filter_type'];
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

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['filter_type'])) {
            switch ($this->request->get['filter_type']) {
                case 'payment':
                    $cat_id = 59;
                    break;
                case 'shipping':
                    $cat_id = 60;
                    break;
                case 'module':
                    $cat_id = 62;
                    break;
                case 'total':
                    $cat_id = 61;
                    break;
                case 'feed':
                    $cat_id = 63;
                    break;
                case 'editor':
                    $cat_id = 93;
                    break;
                case 'captcha':
                    $cat_id = 94;
                    break;
                case 'twofactorauth':
                    $cat_id = 95;
                    break;
                case 'other':
                    $cat_id = 75;
                    break;
            }

            $add_link = $this->url->link('extension/marketplace', 'store=extensions&api=api/category&category_id='.$cat_id.'&token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $add_link = $this->url->link('extension/marketplace', 'token=' . $this->session->data['token'] . $url, 'SSL');
        }

        $data['add'] = $add_link;
        $data['upload'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_author' => $filter_author,
            'filter_type' => $filter_type,
            'filter_status' => $filter_status,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $data['extensions'] = array();

        if (!empty($filter_name) || !empty($filter_author) || !empty($filter_type) || !empty($filter_status)) {
            $extension_total = $this->model_extension_extension->getTotalExtensions($filter_data);
        } else {
            $extension_total = $this->model_extension_extension->getTotalExtensions();
        }

        $results = $this->model_extension_extension->getExtensions($filter_data);

        foreach ($results as $result) {
            $status = $this->config->get($result['code'] . '_status');

            // Happens when there is db record
            if (is_null($status)) {
                $status = 0;
            }

            $info = json_decode($result['info'], true);

            $this->language->load($result['type'] . '/' . $result['code']);

            if (!empty($info)) {
                $name = !empty($info['extension']['name']) ? $info['extension']['name'] : $this->language->get('heading_title');
                $author = $info['author']['name'];
                $version = $info['extension']['version'];
            } else {
                $this->load->language($result['type'] . '/' . $result['code']);

                $name = $this->language->get('heading_title');
                $author = '';
                $version = '';
            }

            // Name search
            if ($filter_name && !strstr(strtolower($name), strtolower($filter_name))) {
                continue;
            }

            // Author search
            if ($filter_author && !strstr(strtolower($author), strtolower($filter_author))) {
                continue;
            }

            // Status filter
            if (!is_null($filter_status) && ($status != $filter_status)) {
                continue;
            }

            $instances = $this->getInstances($result['type'], $result['code']);

            $data['extensions'][] = array(
                'extension_id'      => $result['extension_id'],
                'name'              => $name,
                'author'            => $author,
                'type'              => $result['type'],
                'type_name'         => $this->language->get('text_' . $result['type']),
                'code'              => $result['code'],
                'version'           => $version,
                'instances'         => $instances,
                'sort_order'        => $this->config->get($result['code'] . '_sort_order'),
                'status'            => $status ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit'              => $this->url->link($result['type'] . '/' . $result['code'], 'token=' . $this->session->data['token'] . $url, 'SSL'),
                'uninstall'         => $this->url->link('extension/extension/uninstall', 'type=' . $result['type'] . '&code=' . $result['code'] . '&token=' . $this->session->data['token'] . $url, 'SSL')
            );
        }

        // Sort extensions
        $a_names = array();
        foreach ($data['extensions'] as $key => $row) {
            $a_names[$key] = $row[$sort];
        }
        $a_sort = ($order == 'ASC') ? SORT_ASC : SORT_DESC;
        array_multisort($a_names, $a_sort, $data['extensions']);

        // Success & Warning
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

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_author'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
        $data['sort_type'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&sort=type' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_author'])) {
            $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->request->get['filter_type'];
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

        $pagination = new Pagination();
        $pagination->total = $extension_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($extension_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($extension_total - $this->config->get('config_limit_admin'))) ? $extension_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $extension_total, ceil($extension_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_author'] = $filter_author;
        $data['filter_type'] = $filter_type;
        $data['filter_status'] = $filter_status;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/extension.tpl', $data));
    }

    public function uninstall()
    {
        $this->document->setTitle($this->language->get('heading_title'));

        $type = $this->request->get['type'];
        $code = $this->request->get['code'];

        if ($this->validate() && !empty($type) && !empty($code)) {
            $this->load->model('setting/setting');

            // Call uninstall method, if exists
            $this->load->controller($type . '/' . $code . '/uninstall');

            // Uninstall extension
            $this->uninstallExtension($type, $code);

            // Delete setting
            $this->model_setting_setting->deleteSetting($code);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_author'])) {
                $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_type'])) {
                $url .= '&filter_type=' . $this->request->get['filter_type'];
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

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function uninstallExtension($type, $code)
    {
        $this->load->model('extension/extension');
        $this->load->model('extension/marketplace');

        // Get extension first
        $extension = $this->model_extension_extension->getExtensionByCode($type, $code);

        // Delete extension
        $this->model_extension_extension->deleteExtension($extension['extension_id']);

        // Get addons and delete if required
        $addons = $this->model_extension_marketplace->getAddons();

        if (empty($addons)) {
            return;
        }

        foreach ($addons as $addon) {
            $params = json_decode($addon['params'], true);

            if (!in_array($extension['extension_id'], $params['extension_ids'])) {
                continue;
            }

            if (count($params['extension_ids']) > 1) {
                // Remove the deleted extension ID from addon
                if (($key = array_search($extension['extension_id'], $params['extension_ids'])) !== false) {
                    unset($params['extension_ids'][$key]);

                    $addon['params'] = $params;

                    $this->model_extension_marketplace->editAddon($addon['addon_id'], $addon);

                    // Delete controller file, otherwise discover will add it again
                    $this->filesystem->remove(DIR_ADMIN . 'controller/' . $type . '/' . $code . '.php');
                }

                continue;
            }

            if (!empty($params['theme_ids'])) {
                continue;
            }

            // Delete addon
            $this->model_extension_marketplace->deleteAddon($addon['addon_id']);

            // No files to delete
            if (empty($addon['files'])) {
                continue;
            }

            $absolute_paths = array();

            $files = json_decode($addon['files'], true);

            foreach ($files as $file) {
                $absolute_paths[] = DIR_ROOT . $file;
            }

            // Remove files
            $this->filesystem->remove($absolute_paths);
        }

        // Refresh modifications
        $this->request->get['extensionInstaller'] = 1;
        $this->load->controller('extension/modification/refresh');
        unset($this->request->get['extensionInstaller']);

        // Clear cache
        $this->cache->remove('addon');
        $this->cache->remove('update');
        $this->cache->remove('version');
    }

    public function getInstances($type, $code)
    {
        $instances = array();
        $instances_types = array('module');

        if (!in_array($type, $instances_types)) {
            return $instances;
        }

        $instances = $this->model_extension_extension->getInstances($type, $code);

        foreach ($instances as $id => $row) {
            $var = $type.'_id';

            $instances[$id]['edit'] = $this->url->link($type . '/' . $code, 'token=' . $this->session->data['token'] . '&' . $var . '=' . $row[$var], 'SSL');
            $instances[$id]['delete'] = $this->url->link('extension/' . $type . '/delete', 'token=' . $this->session->data['token'] . '&' . $var . '=' . $row[$var], 'SSL');
        }

        return $instances;
    }

    protected function validate($route = 'extension/extension')
    {
        if (!$this->user->hasPermission('modify', $route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
