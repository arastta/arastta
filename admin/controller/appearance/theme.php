<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

use Arastta\Component\Form\Form as AForm;
use Symfony\Component\Finder\Finder as SFinder;

class ControllerAppearanceTheme extends Controller
{
    private $error = array();

    public function index()
    {
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('appearance/theme');

        // This must be changed as button after initial 1.2 release
        $this->discover();

        $this->getList();
    }

    public function discover()
    {
        $this->load->model('setting/setting');
        $this->load->model('extension/marketplace');

        $DS = DIRECTORY_SEPARATOR;

        $themes = $this->model_appearance_theme->getDiscoverThemes();

        $finder = new SFinder();

        $finder->directories()->in(DIR_CATALOG . $DS . 'view' . $DS . 'theme')->depth('0');

        foreach ($finder as $file) {
            $code = $file->getRelativePathname();

            if (array_key_exists($code, $themes)) {
                continue;
            }

            // Add theme
            $theme = array();
            $theme['code'] = $code;
            $theme['params'] = array();

            $info = DIR_CATALOG . 'view' . $DS . 'theme' . $DS . $code . $DS . 'info.json';
            if (file_exists($info)) {
                $content = file_get_contents($info);

                $theme['info'] = json_decode($content, true);
            } else {
                $info = array(
                    'author' => array(
                        'name' => '',
                        'email' => '',
                        'website' => ''
                    ),
                    'theme' => array(
                        'name' => ucfirst($code),
                        'description' => '',
                        'version' => '',
                        'product_id' => ''
                    )
                );
                $theme['info'] = $info;
            }

            $theme_id = $this->model_appearance_theme->addTheme($theme);

            // Add addon
            $params = array();
            $params['theme_ids'] = array($theme_id);
            $params['extension_ids'] = array();

            $files = $this->getThemeFiles($code);

            $addon = array();
            $addon['params'] = $params;
            $addon['files'] = json_encode($files);
            $addon['product_id'] = '';
            $addon['product_name'] = '';
            $addon['product_type'] = '';
            $addon['product_version'] = '';

            $this->model_extension_marketplace->addAddon($addon);
        }
    }

    protected function getThemeFiles($code)
    {
        $files = array();

        $DS = DIRECTORY_SEPARATOR;

        $admin = str_replace(DIR_ROOT, '', DIR_ADMIN);
        $catalog = str_replace(DIR_ROOT, '', DIR_CATALOG);
        $image = str_replace(DIR_ROOT, '', DIR_IMAGE);

        // Theme files
        $theme_dir = $catalog . 'view' . $DS . 'theme' . $DS . $code;

        $finder = new SFinder();
        $finder->files()->in(DIR_ROOT . $theme_dir);

        foreach ($finder as $file) {
            $files[] = $theme_dir . $DS . $file->getRelativePathname();
        }

        // Thumbnail
        $thumbnail = 'templates' . $DS . $code . '.png';
        if (file_exists(DIR_IMAGE . $thumbnail)) {
            $files[] = $image . $thumbnail;
        }

        // Language
        $language = 'language' . $DS . 'en-GB' . $DS . 'theme' . $DS . $code . '.php';
        if (file_exists(DIR_ADMIN . $language)) {
            $files[] = $admin . $language;
        }

        return $files;
    }

    public function edit()
    {
        $this->load->language('appearance/theme');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $setting['theme_' . $this->request->get['theme']] = json_encode($this->request->post);

            $this->model_setting_setting->editSetting('theme', $setting, $this->config->get('config_store_id'));

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->load->model('appearance/theme');

        $this->getForm();
    }

    public function setDefault()
    {
        $this->load->language('appearance/theme');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (isset($this->request->get['theme']) && $this->validateForm()) {
            $this->model_setting_setting->editSettingValue('config', 'config_template', $this->request->get['theme'], $this->config->get('config_store_id'));

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function uninstall()
    {
        $this->load->language('appearance/theme');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['theme']) && $this->validateDelete() && file_exists(DIR_CATALOG . 'view/theme/' . $this->request->get['theme'])) {
            $this->load->model('appearance/theme');

            if ($this->request->get['theme'] == 'default') {
                $this->session->data['warning'] = $this->language->get('error_default_theme');

                $this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL'));
            }

            // Uninstall theme
            $this->uninstallTheme($this->request->get['theme']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    public function uninstallTheme($code)
    {
        $this->load->model('extension/extension');
        $this->load->model('extension/marketplace');

        // Get theme first
        $theme = $this->model_appearance_theme->getThemeByCode($code);

        // Delete theme
        $this->model_appearance_theme->deleteTheme($theme['theme_id']);

        // Get addons and delete if required
        $addons = $this->model_extension_marketplace->getAddons();

        if (empty($addons)) {
            return;
        }

        foreach ($addons as $addon) {
            $params = json_decode($addon['params'], true);

            if (!in_array($theme['theme_id'], $params['theme_ids'])) {
                continue;
            }

            if (count($params['theme_ids']) > 1) {
                // Remove the deleted extension ID from addon
                if (($key = array_search($theme['theme_id'], $params['theme_ids'])) !== false) {
                    unset($params['theme_ids'][$key]);

                    $addon['params'] = $params;

                    $this->model_extension_marketplace->editAddon($addon['addon_id'], $addon);

                    // Delete theme files, otherwise discover will add it again
                    $this->filesystem->remove(DIR_CATALOG . 'view/theme/' . $code);

                    if (is_file(DIR_IMAGE . 'templates/' . $code . '.png')) {
                        $this->filesystem->remove(DIR_IMAGE . 'templates/' . $code . '.png');
                    }
                }

                continue;
            }

            if (!empty($params['extension_ids'])) {
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

            // Delete theme files, otherwise discover will add it again
            $absolute_paths[] = DIR_CATALOG . 'view/theme/' . $code;

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

    protected function getList()
    {
        $data = $this->language->all();

        $data['add'] = $this->url->link('extension/marketplace', 'store=themes&token=' . $this->session->data['token'], 'SSL');
        $data['upload'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
        $data['uninstall'] = $this->url->link('appearance/theme/uninstall', 'token=' . $this->session->data['token'], 'SSL');

        $data['themes'] = array();

        $action = array();

        $results = $this->model_appearance_theme->getThemes();

        $this->load->model('tool/image');

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . 'templates/' . $result['code'] . '.png')) {
                $image = $this->model_tool_image->resize('templates/' . $result['code'] . '.png', 880, 660);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 880, 660);
            }

            if ($result['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $result['code'] . '/setting.json')) {
                $action['setting'] =  array(
                        'text' => $this->language->get('button_setting'),
                        'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $result['code'], 'SSL'),
                );

                $this->trigger->fire('pre.admin.theme.action', array(&$action));
            }

            $data['themes'][] = array(
                    'code'        => $result['code'],
                    'name'        => $result['name'],
                    'author'      => $result['author'],
                    'description' => html_entity_decode($result['description']),
                    'thumb'       => $image,
                    'action'      => $action,
                    'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                    'edit'        => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'], 'SSL'),
                    'default'     => $this->url->link('appearance/theme/setdefault', 'token=' . $this->session->data['token'] . '&theme=' . $result['code'], 'SSL'),
                    'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $result['code'], 'SSL')
            );
        }

        $data['active_theme'] = $this->config->get('config_template');

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

        $data['token'] = $this->session->data['token'];

        $this->document->addStyle('view/stylesheet/theme.css');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('appearance/theme_list.tpl', $data));
    }

    protected function getForm()
    {
        $this->load->language('theme/' . $this->request->get['theme']);

        $data = $this->language->all();

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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL')
        );

        if (!isset($this->request->get['theme'])) {
            $data['action'] = $this->url->link('appearance/theme/', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $this->request->get['theme'], 'SSL');
        }

        $data['cancel'] = $this->url->link('appearance/theme', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->get['theme']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $data['theme_info'] = $this->model_appearance_theme->getThemeSetting($this->request->get['theme'], $data['action']);
        } elseif (isset($this->request->get['theme'])) {
            $data['theme_info'] = $this->model_appearance_theme->getThemeSetting($this->request->get['theme'], $data['action']);
        }

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('appearance/theme_form.tpl', $data));
    }

    public function info()
    {
        $this->load->language('appearance/theme');
        $this->load->model('appearance/theme');

        $data = $this->language->all();

        if (isset($this->request->post['theme'])) {
            $theme = $this->request->post['theme'];
        } elseif (isset($this->request->get['theme'])) {
            $theme = $this->request->get['theme'];
        } else {
            $theme = 'default';
        }

        $theme_info = $this->model_appearance_theme->getThemeByCode($theme);

        $data['theme'] = array();

        $action = array();

        if ($theme_info) {
            $this->load->model('tool/image');

            if (is_file(DIR_IMAGE . 'templates/' . $theme_info['code'] . '.png')) {
                $image = $this->model_tool_image->resize('templates/' . $theme_info['code'] . '.png', 880, 660);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 880, 660);
            }

            $data['active_theme'] = $this->config->get('config_template');

            if ($theme_info['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $theme_info['code'] . '/setting.php')) {
                $action['setting'] = array(
                        'text' => $this->language->get('text_setting'),
                        'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
                );

                $this->trigger->fire('pre.admin.theme.action', array(&$action));
            }

            $data['theme'] = array(
                    'code'        => $theme_info['code'],
                    'name'        => $theme_info['name'],
                    'author'      => $theme_info['author'],
                    'description' => htmlspecialchars_decode(html_entity_decode($theme_info['description'])),
                    'thumb'       => $image,
                    'version'     => $theme_info['version'],
                    'action'      => $action,
                    'uninstall'   => $this->url->link('appearance/theme/uninstall', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
                    'default'     => $this->url->link('appearance/theme/setdefault', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
                    'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL')
            );
        }

        $this->response->setOutput($this->load->view('appearance/theme_info.tpl', $data));
    }

    public function next()
    {
        $this->load->language('appearance/theme');
        $this->load->model('appearance/theme');

        $data = $this->language->all();

        if (isset($this->request->post['theme'])) {
            $theme = $this->request->post['theme'];
        } elseif (isset($this->request->get['theme'])) {
            $theme = $this->request->get['theme'];
        } else {
            $theme = 'default';
        }

        $theme_info = $this->model_appearance_theme->getThemeByCode($theme);

        $data['theme'] = array();

        $action = array();

        if ($theme_info) {
            $this->load->model('tool/image');

            if (is_file(DIR_IMAGE . 'templates/' . $theme_info['theme'] . '.png')) {
                $image = $this->model_tool_image->resize('templates/' . $theme_info['theme'] . '.png', 880, 660);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 880, 660);
            }

            $data['active_theme'] = $this->config->get('config_template');

            if ($theme_info['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $theme_info['code'] . '/setting.php')) {
                $action['setting'] = array(
                        'text' => $this->language->get('text_setting'),
                        'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
                );

                $this->trigger->fire('pre.admin.theme.action', array(&$action));
            }

            $data['theme'] = array(
                    'theme'       => $theme_info['theme'],
                    'name'        => $theme_info['name'],
                    'author'      => $theme_info['author'],
                    'description' => html_entity_decode($theme_info['description']),
                    'thumb'       => $image,
                    'version'     => $theme_info['version'],
                    'edit'        => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'], 'SSL'),
                    'action'      => $action,
                    'uninstall'   => $this->url->link('appearance/theme/uninstall', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
                    'default'     => $this->url->link('appearance/theme/setdefault', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
                    'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL')
            );
        }

        $this->response->setOutput($this->load->view('appearance/theme_info.tpl', $data));
    }

    public function previous()
    {
        $this->load->language('appearance/theme');
        $this->load->model('appearance/theme');

        $data = $this->language->all();

        if (isset($this->request->post['theme'])) {
            $theme = $this->request->post['theme'];
        } elseif (isset($this->request->get['theme'])) {
            $theme = $this->request->get['theme'];
        } else {
            $theme = 'default';
        }

        $theme_info = $this->model_appearance_theme->getThemeByCode($theme);

        $data['theme'] = array();

        $action = array();

        if ($theme_info) {
            $this->load->model('tool/image');

            if (is_file(DIR_IMAGE . 'templates/' . $theme_info['code'] . '.png')) {
                $image = $this->model_tool_image->resize('templates/' . $theme_info['code'] . '.png', 880, 660);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 880, 660);
            }

            $data['active_theme'] = $this->config->get('config_template');

            if ($theme_info['code'] == $this->config->get('config_template') && file_exists(DIR_CATALOG . 'view/theme/' . $theme_info['code'] . '/setting.php')) {
                $action['setting'] = array(
                        'text' => $this->language->get('text_setting'),
                        'href' => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['code'], 'SSL'),
                );

                $this->trigger->fire('pre.admin.theme.action', array(&$action));
            }

            $data['theme'] = array(
                    'code'        => $theme_info['code'],
                    'name'        => $theme_info['name'],
                    'author'      => $theme_info['author'],
                    'description' => html_entity_decode($theme_info['description']),
                    'thumb'       => $image,
                    'version'     => $theme_info['version'],
                    'edit'        => $this->url->link('appearance/theme/edit', 'token=' . $this->session->data['token'], 'SSL'),
                    'action'      => $action,
                    'uninstall'   => $this->url->link('appearance/theme/uninstall', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
                    'default'     => $this->url->link('appearance/theme/setdefault', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL'),
                    'customizer'  => $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'] . '&theme=' . $theme_info['theme'], 'SSL')
            );
        }

        $this->response->setOutput($this->load->view('appearance/theme_info.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'appearance/theme')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->request->get['theme'] == $this->config->get('config_template') && !AForm::isValid('form-' . $this->request->get['theme'] . '-theme-elements')) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'appearance/theme')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
