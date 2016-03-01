<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerFeedGoogleSitemap extends Controller {

    public function index() {
        if (!$this->config->get('google_sitemap_status')) {
            return;
        }

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/information');

        $results = array_merge($this->getProducts(), $this->getCategories(0), $this->getManufacturers(), $this->getInformations(), $this->getOthers());

        $this->loadOutput($results);
    }

    public function products() {
        $this->load->model('catalog/product');

        $this->loadOutput($this->getProducts());
    }

    public function categories() {
        $this->load->model('catalog/category');

        $this->loadOutput($this->getCategories(0));
    }

    public function manufacturers() {
        $this->load->model('catalog/manufacturer');

        $this->loadOutput($this->getManufacturers());
    }

    public function informations() {
        $this->load->model('catalog/information');

        $this->loadOutput($this->getInformations());
    }

    public function others() {
        $this->loadOutput($this->getOthers());
    }

    private function loadOutput($results) {
        $data['results'] = $results;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/feed/google_sitemap.tpl')) {
            $output = $this->load->view($this->config->get('config_template') . '/template/feed/google_sitemap.tpl', $data);
        } else {
            $output = $this->load->view('default/template/feed/google_sitemap.tpl', $data);
        }

        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($output);
    }

    private function getProducts() {
        $this->load->model('tool/image');

        $products = array();

        foreach ($this->model_catalog_product->getProducts() as $result) {
            if ($result['image']) {
                $products[] = array(
                    'name'  => $result['name'],
                    'date'  => $result['date_modified'],
                    'prior' => '1.0',
                    'url'   => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    'img'   => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'))
                );
            }
        }

        return $products;
    }

    private function getCategories($parent_id, $current_path = '') {
        $categories = array();

        $results = $this->model_catalog_category->getCategories($parent_id);

        foreach ($results as $result) {
            if (!$current_path) {
                $new_path = $result['category_id'];
            } else {
                $new_path = $current_path . '_' . $result['category_id'];
            }

            $categories[] = array(
                'date'  => $result['date_modified'],
                'prior' => '0.7',
                'url'   => $this->url->link('product/category', 'path=' . $new_path)
            );

            $categories = array_merge($categories, $this->getCategories($result['category_id'], $new_path));
        }

        return $categories;
    }

    private function getManufacturers() {
        $manufacturers = array();

        foreach ($this->model_catalog_manufacturer->getManufacturers() as $result) {
            $manufacturers[] = array(
                'prior' => '0.7',
                'url'   => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
            );
        }

        return $manufacturers;
    }

    private function getInformations() {
        $informations = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            $informations[] = array(
                'prior' => '0.5',
                'url'   => $this->url->link('information/information', 'information_id=' . $result['information_id'])
            );
        }

        return $informations;
    }

    private function getOthers() {
        $others = array();

        $others[] = array('prior' => '1.0', 'url' => $this->url->link('common/home'));
        $others[] = array('url' => $this->url->link('product/special'));
        $others[] = array('url' => $this->url->link('account/account'));
        $others[] = array('url' => $this->url->link('account/edit'));
        $others[] = array('url' => $this->url->link('account/password'));
        $others[] = array('url' => $this->url->link('account/address'));
        $others[] = array('url' => $this->url->link('account/order'));
        $others[] = array('url' => $this->url->link('account/download'));
        $others[] = array('url' => $this->url->link('checkout/cart'));
        $others[] = array('url' => $this->url->link('checkout/checkout'));
        $others[] = array('url' => $this->url->link('product/search'));
        $others[] = array('url' => $this->url->link('information/contact'));

        return $others;
    }
}
