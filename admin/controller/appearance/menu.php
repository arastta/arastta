<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerAppearanceMenu extends Controller
{
    
    private $error = array();

    public function index()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');

        $this->document->addStyle('view/javascript/jquery/layout/jquery-ui.css');
        $this->document->addStyle('view/stylesheet/menu.css');
        $this->document->addStyle('view/stylesheet/layout.css');
        
        $this->document->addScript('view/javascript/jquery/layout/jquery-ui.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery-lockfixed.js');
        $this->document->addScript('view/javascript/jquery/layout/jquery.ui.touch-punch.js');
        $this->document->addScript('view/javascript/menu/menu.js');
        
        $data['changeMenuPosition'] = $this->url->link('appearance/menu/changeMenuPosition', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['deleteMenu'] = $this->url->link('appearance/menu/deleteMenu', 'token=' . $this->session->data['token'], 'SSL');
        $data['deleteChildMenu'] = $this->url->link('appearance/menu/deleteChildMenu', 'token=' . $this->session->data['token'], 'SSL');
                
        $data['enableMenu'] = $this->url->link('appearance/menu/enableMenu', 'token=' . $this->session->data['token'], 'SSL');
        $data['enableChildMenu'] = $this->url->link('appearance/menu/enableChildMenu', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['disableMenu'] = $this->url->link('appearance/menu/disableMenu', 'token=' . $this->session->data['token'], 'SSL');
        $data['disableChildMenu'] = $this->url->link('appearance/menu/disableChildMenu', 'token=' . $this->session->data['token'], 'SSL');

        $data['refresch'] = $this->url->link('appearance/menu', 'token=' . $this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('appearance/menu/add', 'token=' . $this->session->data['token'], 'SSL');
        $data['save'] = $this->url->link('appearance/menu/save', 'token=' . $this->session->data['token'], 'SSL');

        $data['menu_child'] = array();

        $menus = $this->model_appearance_menu->getMenus();
        $menu_child = $this->model_appearance_menu->getChildMenus();

        $rent_menu = array();

        $this->load->model('catalog/product');
        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        foreach ($menus as $id => $menu) {
            $rent_menu[] = array(
                'name'          => $menu['name'] ,
                'menu_id'       => $menu['menu_id'],
                'menu_type'     => $menu['menu_type'],
                'status'        => $menu['status'],
                'store'         => $this->model_appearance_menu->getMenuStores($menu['menu_id']),
                'isSubMenu'     => ''
            );

            foreach ($menu_child as $child_id => $child_menu) {
                if (($menu['menu_id'] != $child_menu['menu_id']) or !is_numeric($child_id)) {
                    continue;
                }

                $rent_menu[] = array(
                    'name'          => $child_menu['name'],
                    'menu_id'       => $child_menu['menu_child_id'],
                    'menu_type'     => $child_menu['menu_type'],
                    'status'        => $child_menu['status'],
                    'store'         => $this->model_appearance_menu->getChildMenuStores($child_menu['menu_child_id']),
                    'isSubMenu'     => $menu['menu_id']
                );
            }
        }

        $data['menu_desc'] = $this->model_appearance_menu->getMenuDesc();
        $data['menu_child_desc'] = $this->model_appearance_menu->getMenuChildDesc();

        $data['menus'] = $rent_menu;

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();
        
        $data = $this->language->all($data);

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

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('appearance/menu_list.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'appearance/menu')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'appearance/menu')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function add()
    {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->load->model('appearance/menu');
            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            $menu = $this->model_appearance_menu->add($this->request->post, $languages);

            if (!empty($menu)) {
                echo $this->addHtml($menu, $languages);
            }
        }
    }

    protected function addHtml($menu, $languages)
    {
        $this->load->language('appearance/menu');

        $this->load->model('setting/store');

        $stores = $this->model_setting_store->getStores();

        $data = $this->language->all();

        $count = '0';

        $html  = '<li id="menu-item-' . $menu['menu_id'] . '" class="menu-item menu-item-depth-0 menu-item-page menu-item-edit-inactive pending">';
        $html .= '	<dl class="menu-item-bar">';
        $html .= '		<dt class="menu-item-handle">';
        $html .= '			<span class="item-title"><span class="menu-item-title">' . $menu['name'] .'</span> <span class="is-submenu" style="display: none;">' . $data['text_sub_item'] . '</span></span>';
        $html .= '          <span class="item-controls">';
        $html .= '			<span class="item-type">' . ucwords($menu['menu_type']) . '</span>';
        $html .= '				<a class="item-edit openMenuItem ' . $menu['menu_type'] . '" id="edit-' . $menu['menu_id'] . '" title="">';
        $html .= '                <i class="fa fa-caret-down"></i>';
        $html .= '              </a>';
        $html .= '			</span>';
        $html .= '      </dt>';
        $html .= ' </dl>';

        $menu_desc = $this->model_appearance_menu->getMenuDesc();

        $html .= '<div class="menu-item-settings" id="menu-item-settings-edit-' . $menu['menu_id'] . '">';
        $html .= $data['text_menu_name'] . '</br>';

        foreach ($languages as $language) {
            $html .= '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/' . $language["image"] . '" title="' . $language["name"] . '"/></span>';
            $html .= '   <input type="text" name="menu_name[' . $language['language_id'] . ']" value="' . $menu_desc[$menu['menu_id']][$language['language_id']]['name']. '" placeholder="' . $data['text_menu_name'] . '" class="form-control" />';
            $html .= '</div>';
        }

        $html .= '<br>';

        if ($menu['menu_type'] == 'custom') {
            $html .= $data['text_menu_link'] . '<br>';

            foreach ($languages as $language) {
                $html .= '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/' . $language['image'] . '" title="' . $language['name'] . '" /></span>';
                $html .= '   <input type="text" name="menu_link[' . $language['language_id'] . ']" value="' . $menu_desc[$menu['menu_id']][$language['language_id']]['link'] . '" placeholder="' . $data['text_menu_link'] . '" class="form-control" />';
                $html .= '</div>';
            }

            $html .= '<br>';
        } else {
            $html .= '<br>';

            foreach ($languages as $language) {
                $html .= '<input type="hidden" name="menu_link[' . $language['language_id'] . ']" value="' . isset($menu_desc[$menu['menu_id']][$language['language_id']]['link']) ? $menu_desc[$menu['menu_id']][$language['language_id']]['link'] : '' . '" />';
            }
        }

        $html .= $data['entry_columns'];
        $html .= '<div class="input-group">';
        $html .= '  <input type="text" name="menu_columns" value="1" placeholder="" id="input-columns" class="form-control" />';
        $html .= '</div>';
        $html .= '<br>';
        $html .= '<div class="pull-right">';
        $html .= ' <a id="disableMenu-'. $count . '" onclick="statusMenu(\'disable\', \'' . $menu['menu_id'] . '\', \'menu-item-' . $menu['menu_id'] . '\', \'disableMenu-' . $count .'\')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="' . $data['button_disable'] . '" class="btn btn-danger btn-xs btn-edit btn-group"><i class="fa fa-times-circle"></i></a>';
        $html .= ' <a onclick="saveMenu(\'menu-item-settings-edit-' . $menu['menu_id'] . '\', \'menu-item-' . $menu['menu_id'] . '\')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="' . $data['button_save'] . '" class="btn btn-success btn-xs btn-edit btn-group"><i class="fa fa-save"></i></a>';
        $html .= ' <button type="button" data-toggle="tooltip" title="" style="top:2px!important;font-size:1.2em !important;" class="btn btn-danger btn-xs btn-edit btn-group btn-loading" onclick="confirm(\'Are you sure?\') ? deleteMenu(\'' . $menu['menu_id'] . '\', \'menu-item-' . $menu['menu_id'] . '\') : false;" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>';
        $html .= '</div>';
        $html .= '<br><br>';

        if(count($stores) > 0) {
            $html .= $this->language->get('entry_store');
            $html .= '<br />';
            $html .= '<div class="well well-sm" style="height: 100%; max-height: 150px;  margin-right: 10px; overflow: auto;padding-right: 10px; margin-bottom: 5px;">';
            $html .= '  <div class="checkbox">';
            $html .= '    <label>';
            
            if (in_array(0, $menu['store'])) {
                $html .= '      <input type="checkbox" name="menu_store[]" value="0" checked="checked" />';
                $html .= $this->language->get('text_default');
            } else {
                $html .= '      <input type="checkbox" name="menu_store[]" value="0" />';
                $html .= $this->language->get('text_default');
            }
            
            $html .= '    </label>';
            $html .= '  </div>';
            
            foreach ($stores as $store) {
                $html .= '  <div class="checkbox">';
                $html .= '    <label>';
                
                if (in_array($store['store_id'], $menu['store'])) {
                    $html .= '      <input type="checkbox" name="menu_store[]" value="' . $store['store_id'] . '" checked="checked" />';
                    $html .= $store['name'];
                } else {
                    $html .= '      <input type="checkbox" name="menu_store[]" value="' . $store['store_id'] . '" />';
                    $html .= $store['name'];
                }
                
                $html .= '    </label>';
                $html .= '  </div>';
            }
            
            $html .= '</div>';
        }

        $html .= '<input class="menu-item-data-typeMenu" type="hidden" name="menu-item-typeMenu[MainMenu-' . $menu['menu_id'].']" value="MainMenu">';
        $html .= '<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[MainMenu-' . $menu['menu_id'] . ']" value="' . $menu['menu_id'] . '">';
        $html .= '<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[MainMenu-' . $menu['menu_id'] .']" value="0">';
        $html .= '<input class="menu-item-data-position" type="hidden" name="menu-item-position[MainMenu-' . $menu['menu_id'] . ']" value="' . $menu['menu_id'] . '">';
        $html .= '<input class="menu-item-data-type" type="hidden" name="menu-item-type[MainMenu-' . $menu['menu_id'] . ']" value="post_type">';

        $html .= '</div>';
        $html .= '<ul class="menu-item-transport"></ul>';
        $html .= '</li>';

        return $html;
    }

    public function save()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if ($this->request->get['type'] == 'child') {
                $this->model_appearance_menu->saveChild($this->request->post);
            } else {
                $this->model_appearance_menu->save($this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');
        }
    }

    public function deleteMenu()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');

        if (isset($this->request->post['menu_id']) && $this->validateDelete()) {
            $this->model_appearance_menu->deleteMenu($this->request->post['menu_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $json['success'] = $this->language->get('text_success');
            $json['error']   = $this->language->get('text_error');

        } else {
            $this->session->data['error'] = $this->language->get('text_error');

            $json['success'] = '';
            $json['error']   = $this->language->get('text_error');
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteChildMenu()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');

        if (isset($this->request->post['menu_id']) && $this->validateDelete()) {
            $this->model_appearance_menu->deleteChildMenu($this->request->post['menu_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $json['success'] = $this->language->get('text_success');
            $json['error']   = '';

        } else {
            $this->session->data['error'] = $this->language->get('text_error');

            $json['success'] = '';
            $json['error']   = $this->language->get('text_error');
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function enableMenu()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_appearance_menu->enableMenu($this->request->post['menu_id']);
            $this->session->data['success'] = $this->language->get('text_success');
             
        }
        
        $id = explode('-', $this->request->post['id']);

        $button = "<a id=\"disableMenu-" . $id[1] . "\" onclick=\"statusMenu('disable', '" . $this->request->post['menu_id'] . "', 'menu-item-" .  $this->request->post['menu_id'] . "', 'disableMenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-danger btn-xs btn-edit btn-group\"><i class=\"fa fa-times-circle\"></i></a>";
        
        echo $button;
        exit();
    }

    public function disableMenu()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_appearance_menu->disableMenu($this->request->post['menu_id']);
            $this->session->data['success'] = $this->language->get('text_success');
             
        }
        
        $id = explode('-', $this->request->post['id']);
        
        $button = "<a id=\"enableMenu-" . $id[1] . "\" onclick=\"statusMenu('enable', '" . $this->request->post['menu_id'] . "', 'menu-item-" .  $this->request->post['menu_id'] . "', 'enableMenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-success btn-xs btn-edit btn-group\"><i class=\"fa fa-check-circle\"></i></a>";

        echo $button;
        exit();
    }
    
    public function enableChildMenu()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_appearance_menu->enableChildMenu($this->request->post['menu_id']);
            $this->session->data['success'] = $this->language->get('text_success');
        }

        $id = explode('-', $this->request->post['id']);
        
        $button = "<a id=\"disableMenu-" . $id[1] . "\" onclick=\"statusMenu('disable', '" . $this->request->post['menu_id'] . "', 'menu-child-item-" .  $this->request->post['menu_id'] . "', 'disableMenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-danger btn-xs btn-edit btn-group\"><i class=\"fa fa-times-circle\"></i></a>";

        echo $button;
        exit();
    }

    public function disableChildMenu()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_appearance_menu->disableChildMenu($this->request->post['menu_id']);
            $this->session->data['success'] = $this->language->get('text_success');
        }
        
        $id = explode('-', $this->request->post['id']);

        $button = "<a id=\"enableMenu-" . $id[1] . "\" onclick=\"statusMenu('enable', '" . $this->request->post['menu_id'] . "', 'menu-child-item-" .  $this->request->post['menu_id'] . "', 'enableMenu-" . $id[1] . "')\" data-type=\"iframe\" data-toggle=\"tooltip\" style=\"top:2px!important;font-size:1.2em !important;\" title=\"\" class=\"btn btn-success btn-xs btn-edit btn-group\"><i class=\"fa fa-check-circle\"></i></a>";
        
        echo $button;
        exit();
    }

    public function changeMenuPosition()
    {
        $this->load->language('appearance/menu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('appearance/menu');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_appearance_menu->changeMenuPosition($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

        }
    }

    public function autocomplete()
    {
        $json = array();

        #Category
        if (isset($this->request->get['filter_category_name'])) {
            $this->load->model('catalog/category');
            
            if (isset($this->request->get['filter_category_name'])) {
                $filter_name = $this->request->get['filter_category_name'];
            } else {
                $filter_name = '';
            }
            
            $filter_data = array(
                'filter_name' => $filter_name,
                'sort'        => 'name',
                'order'       => 'ASC',
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_catalog_category->getCategories($filter_data);

            foreach ($results as $result) {
                
                $result['index'] = $result['name'];
                if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                    $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                    $result['name'] = end($result['name']);
                }
                
                $json[] = array(
                    'category_id' => $result['category_id'],
                    'index'       => $result['index'],
                    'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }
        
        #Product
        if (isset($this->request->get['filter_product_name'])) {
            $this->load->model('catalog/product');

            if (isset($this->request->get['filter_product_name'])) {
                $filter_name = $this->request->get['filter_product_name'];
            } else {
                $filter_name = '';
            }

            $filter_data = array(
                'filter_name'  => $filter_name,
                'start'        => 0,
                'limit'        => 5
            );

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'index'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }
        
        #Manufacturer
        if (isset($this->request->get['filter_manufacturer_name'])) {
            $this->load->model('catalog/manufacturer');
            
            if (isset($this->request->get['filter_manufacturer_name'])) {
                $filter_name = $this->request->get['filter_manufacturer_name'];
            } else {
                $filter_name = '';
            }
            
            $filter_data = array(
                'filter_name' => $filter_name,
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'manufacturer_id' => $result['manufacturer_id'],
                    'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'index'           => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        #Information
        if (isset($this->request->get['filter_information_name'])) {
            $this->load->model('catalog/information');
            
            if (isset($this->request->get['filter_information_name'])) {
                $filter_name = $this->request->get['filter_information_name'];
            } else {
                $filter_name = '';
            }
            
            $filter_data = array(
                'filter_name' => $filter_name,
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_catalog_information->getInformations($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'information_id' => $result['information_id'],
                    'name'            => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
                    'index'           => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
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
}
