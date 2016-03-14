<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerLocalisationGeoZone extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('localisation/geo_zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/geo_zone');

        $this->getList();
    }

    public function add() {
        $this->load->language('localisation/geo_zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/geo_zone');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $geo_zone_id = $this->model_localisation_geo_zone->addGeoZone($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

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

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('localisation/geo_zone/edit', 'geo_zone_id='.$geo_zone_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('localisation/geo_zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }            
            
            $this->response->redirect($this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('localisation/geo_zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/geo_zone');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_localisation_geo_zone->editGeoZone($this->request->get['geo_zone_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

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

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link('localisation/geo_zone/edit', 'geo_zone_id='.$this->request->get['geo_zone_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('localisation/geo_zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }            
            
            $this->response->redirect($this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('localisation/geo_zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/geo_zone');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $geo_zone_id) {
                $this->model_localisation_geo_zone->deleteGeoZone($geo_zone_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_description'])) {
            $filter_description = $this->request->get['filter_description'];
        } else {
            $filter_description = null;
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

        if (isset($this->request->get['filter_description'])) {
            $url .= '&filter_description=' . $this->request->get['filter_description'];
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['add'] = $this->url->link('localisation/geo_zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('localisation/geo_zone/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['geo_zones'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_description' => $filter_description,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        if(!empty($filter_name) || !empty($filter_description)) {
            $geo_zone_total = $this->model_localisation_geo_zone->getTotalGeoZonesFilter($filter_data);
        } else {
            $geo_zone_total = $this->model_localisation_geo_zone->getTotalGeoZones();
        }                
        
        $results = $this->model_localisation_geo_zone->getGeoZones($filter_data);

        foreach ($results as $result) {
            $data['geo_zones'][] = array(
                'geo_zone_id' => $result['geo_zone_id'],
                'name'        => $result['name'],
                'description' => $result['description'],
                'edit'        => $this->url->link('localisation/geo_zone/edit', 'token=' . $this->session->data['token'] . '&geo_zone_id=' . $result['geo_zone_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_confirm_title'] = sprintf($this->language->get('text_confirm_title'), $this->language->get('heading_title'));
        $data['text_bulk_action'] = $this->language->get('text_bulk_action');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

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

        $data['sort_name'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_description'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . '&sort=description' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $geo_zone_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($geo_zone_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($geo_zone_total - $this->config->get('config_limit_admin'))) ? $geo_zone_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $geo_zone_total, ceil($geo_zone_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_description'] = $filter_description;
        
        $data['sort'] = $sort;
        $data['order'] = $order;
        
        $data ['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/geo_zone_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_form'] = !isset($this->request->get['geo_zone_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_publish'] = $this->language->get('text_publish');
        $data['text_items'] = $this->language->get('text_items');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');        
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_geo_zone_add'] = $this->language->get('button_geo_zone_add');
        $data['button_remove'] = $this->language->get('button_remove');

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

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['geo_zone_id'])) {
            $data['action'] = $this->url->link('localisation/geo_zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/geo_zone/edit', 'token=' . $this->session->data['token'] . '&geo_zone_id=' . $this->request->get['geo_zone_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['geo_zone_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($this->request->get['geo_zone_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($geo_zone_info)) {
            $data['name'] = $geo_zone_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($geo_zone_info)) {
            $data['description'] = $geo_zone_info['description'];
        } else {
            $data['description'] = '';
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        if (isset($this->request->post['zone_to_geo_zone'])) {
            $data['zone_to_geo_zones'] = $this->request->post['zone_to_geo_zone'];
        } elseif (isset($this->request->get['geo_zone_id'])) {
            $data['zone_to_geo_zones'] = $this->model_localisation_geo_zone->getZoneToGeoZones($this->request->get['geo_zone_id']);
        } else {
            $data['zone_to_geo_zones'] = array();
        }

        if(!empty($data['zone_to_geo_zones'])){
            $this->load->model('localisation/zone');

            foreach($data['zone_to_geo_zones'] as $id => $geo_zone){
                $results = $this->model_localisation_zone->getZonesByCountryId($geo_zone['country_id']);

                foreach ($results as $result) {
                    $data['zone_to_geo_zones'][$id]['zone'][] = array(
                        'zone_id' => $result['zone_id'],
                        'zone_name' => $result['name']
                    );
                }
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/geo_zone_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen($this->request->post['description']) > 255)) {
            $this->error['description'] = $this->language->get('error_description');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('localisation/tax_rate');

        foreach ($this->request->post['selected'] as $geo_zone_id) {
            $tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRatesByGeoZoneId($geo_zone_id);

            if ($tax_rate_total) {
                $this->error['warning'] = sprintf($this->language->get('error_tax_rate'), $tax_rate_total);
            }
        }

        return !$this->error;
    }

    public function zone() {
        $output = '<option value="0">' . $this->language->get('text_all_zones') . '</option>' . "\r\n";

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if ($this->request->get['zone_id'] == $result['zone_id']) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>' . "\r\n";
        }

        $this->response->setOutput($output);
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_description'])) {
            $this->load->model('localisation/geo_zone');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }
            
            if (isset($this->request->get['filter_description'])) {
                $filter_description = $this->request->get['filter_description'];
            } else {
                $filter_description = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_zone_name' => $filter_name,
                'filter_zone_code' => $filter_description,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_localisation_geo_zone->getGeoZones($filter_data);

            if(!empty ($results)) {
                foreach ($results as $result) {
                    $json[] = array(
                        'geo_zone_id' => $result['geo_zone_id'],
                        'name'    => $result['name'],
                        'description'    => $result['description']
                    );
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }    
}
