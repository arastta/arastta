<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerLocalisationZone extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('localisation/zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/zone');

        $this->getList();
    }

    public function add() {
        $this->load->language('localisation/zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/zone');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $zone_id = $this->model_localisation_zone->addZone($this->request->post);

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
                $this->response->redirect($this->url->link('localisation/zone/edit', 'zone_id='.$zone_id.'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('localisation/zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }            

            $this->response->redirect($this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('localisation/zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/zone');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_localisation_zone->editZone($this->request->get['zone_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('localisation/zone/edit', 'zone_id='.$this->request->get['zone_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'new') {
                $this->response->redirect($this->url->link('localisation/zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
            
            $this->response->redirect($this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('localisation/zone');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/zone');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $zone_id) {
                $this->model_localisation_zone->deleteZone($zone_id);
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

            $this->response->redirect($this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_country'])) {
            $filter_country = $this->request->get['filter_country'];
        } else {
            $filter_country = null;
        }
        
        if (isset($this->request->get['filter_zone_name'])) {
            $filter_zone_name = $this->request->get['filter_zone_name'];
        } else {
            $filter_zone_name = null;
        }

        if (isset($this->request->get['filter_zone_code'])) {
            $filter_zone_code = $this->request->get['filter_zone_code'];
        } else {
            $filter_zone_code = null;
        }
        
        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }        
        
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'c.name';
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
        
        if (isset($this->request->get['filter_country'])) {
            $url .= '&filter_country=' . urlencode(html_entity_decode($this->request->get['filter_country'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_zone_name'])) {
            $url .= '&filter_zone_name=' . urlencode(html_entity_decode($this->request->get['filter_zone_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_zone_code'])) {
            $url .= '&filter_zone_code=' . $this->request->get['filter_zone_code'];
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['add'] = $this->url->link('localisation/zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('localisation/zone/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['zones'] = array();

        $filter_data = array(
            'filter_country' => $filter_country,
            'filter_zone_name' => $filter_zone_name,
            'filter_zone_code' => $filter_zone_code,
            'filter_status' => $filter_status,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $zone_status = $this->model_localisation_zone->getZoneStatus();

        if(!empty($filter_country) || !empty($filter_zone_name) || !empty($filter_zone_code) || !empty($filter_status)) {
            $zone_total = $this->model_localisation_zone->getTotalZonesFilter($filter_data);
        } else {
            $zone_total = $this->model_localisation_zone->getTotalZones();
        }        
        
        $results = $this->model_localisation_zone->getZones($filter_data);

        foreach ($results as $result) {
            $data['zones'][] = array(
                'zone_id' => $result['zone_id'],
                'country' => $result['country'],
                'name'    => $result['name'] . (($result['zone_id'] == $this->config->get('config_zone_id')) ? $this->language->get('text_default') : null),
                'status'  => ($zone_status[$result['zone_id']]['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'code'    => $result['code'],
                'edit'    => $this->url->link('localisation/zone/edit', 'token=' . $this->session->data['token'] . '&zone_id=' . $result['zone_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_status'] = $this->language->get('entry_status');
        
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_confirm_title'] = sprintf($this->language->get('text_confirm_title'), $this->language->get('heading_title'));
        $data['text_enabled']  = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_bulk_action'] = $this->language->get('text_bulk_action');

        $data['column_country'] = $this->language->get('column_country');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_code'] = $this->language->get('column_code');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_enable']  = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
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

        $data['sort_country'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url, 'SSL');
        $data['sort_name'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . '&sort=z.name' . $url, 'SSL');
        $data['sort_code'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . '&sort=z.code' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . '&sort=z.status' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $zone_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($zone_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($zone_total - $this->config->get('config_limit_admin'))) ? $zone_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $zone_total, ceil($zone_total / $this->config->get('config_limit_admin')));

        $data['filter_country'] = $filter_country;
        $data['filter_zone_name'] = $filter_zone_name;
        $data['filter_zone_code'] = $filter_zone_code;
        $data['filter_status'] = $filter_status;
        
        $data['sort'] = $sort;
        $data['order'] = $order;

        $data ['token'] = $this->session->data['token'];
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/zone_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_form'] = !isset($this->request->get['zone_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_publish'] = $this->language->get('text_publish');
        
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_country'] = $this->language->get('entry_country');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');         
        $data['button_cancel'] = $this->language->get('button_cancel');

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
            'href' => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['zone_id'])) {
            $data['action'] = $this->url->link('localisation/zone/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/zone/edit', 'token=' . $this->session->data['token'] . '&zone_id=' . $this->request->get['zone_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['zone_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $zone_info = $this->model_localisation_zone->getZone($this->request->get['zone_id']);
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($zone_info)) {
            $data['status'] = $zone_info['status'];
        } else {
            $data['status'] = '1';
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($zone_info)) {
            $data['name'] = $zone_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['code'])) {
            $data['code'] = $this->request->post['code'];
        } elseif (!empty($zone_info)) {
            $data['code'] = $zone_info['code'];
        } else {
            $data['code'] = '';
        }

        if (isset($this->request->post['country_id'])) {
            $data['country_id'] = (int)$this->request->post['country_id'];
        } elseif (!empty($zone_info)) {
            $data['country_id'] = $zone_info['country_id'];
        } else {
            $data['country_id'] = '';
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/zone_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'localisation/zone')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'localisation/zone')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('setting/store');
        $this->load->model('sale/customer');
        $this->load->model('marketing/affiliate');
        $this->load->model('localisation/geo_zone');

        foreach ($this->request->post['selected'] as $zone_id) {
            if ($this->config->get('config_zone_id') == $zone_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            $store_total = $this->model_setting_store->getTotalStoresByZoneId($zone_id);

            if ($store_total) {
                $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
            }

            $address_total = $this->model_sale_customer->getTotalAddressesByZoneId($zone_id);

            if ($address_total) {
                $this->error['warning'] = sprintf($this->language->get('error_address'), $address_total);
            }

            $affiliate_total = $this->model_marketing_affiliate->getTotalAffiliatesByZoneId($zone_id);

            if ($affiliate_total) {
                $this->error['warning'] = sprintf($this->language->get('error_affiliate'), $affiliate_total);
            }

            $zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);

            if ($zone_to_geo_zone_total) {
                $this->error['warning'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
            }
        }

        return !$this->error;
    }
    
    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_country']) || isset($this->request->get['filter_zone_name']) || isset($this->request->get['filter_zone_code'])) {
            $this->load->model('localisation/zone');
            $this->load->model('localisation/country');

            if (isset($this->request->get['filter_country'])) {
                $filter_country = $this->request->get['filter_country'];
            } else {
                $filter_country = '';
            }

            if (isset($this->request->get['filter_zone_name'])) {
                $filter_zone_name = $this->request->get['filter_zone_name'];
            } else {
                $filter_zone_name = '';
            }
            
            if (isset($this->request->get['filter_zone_code'])) {
                $filter_zone_code = $this->request->get['filter_zone_code'];
            } else {
                $filter_zone_code = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_country'  => $filter_country,
                'filter_zone_name' => $filter_zone_name,
                'filter_zone_code' => $filter_zone_code,
                'start'        => 0,
                'limit'        => $limit
            );

            if(!empty($filter_zone_name) || !empty($filter_zone_code)) {
                $results = $this->model_localisation_zone->getZones($filter_data);

                if(!empty ($results)) {
                    foreach ($results as $result) {
                        $json[] = array(
                                    'zone_id'    => $result['zone_id'],
                                    'name'       => $result['name'],
                                    'code'       => $result['code']
                                );
                    }
                }        
            }
            
            if(!empty($filter_country)) {
                $results = $this->model_localisation_country->getCountries($filter_data);

                if(!empty ($results)) {
                    foreach ($results as $result) {
                        $json[] = array(
                                    'country_id' => $result['country_id'],
                                    'country'    => $result['name']
                                );    
                    }
                }                        
            }
            
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }    
}
