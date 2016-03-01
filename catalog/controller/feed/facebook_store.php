<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerFeedFacebookStore extends Controller
{
    public function index()
    {
        if ($this->config->get('facebook_store_status')) {
            $this->load->language('common/header');
            $this->load->language('product/search');
            $this->load->language('common/language');
            $this->load->language('common/currency');
            $this->load->language('product/category');

            $data = $this->language->all();

            $data['title'] = $this->document->getTitle();

            $data['base'] = $this->config->get('config_ssl');
            $data['description'] = $this->document->getDescription();
            $data['keywords'] = $this->document->getKeywords();
            $data['lang'] = $this->language->get('code');
            $data['direction'] = $this->language->get('direction');

            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');

            if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
                $data['icon'] = $this->config->get('config_ssl') . 'image/' . $this->config->get('config_icon');
            } else {
                $data['icon'] = '';
            }

            $data['name'] = $this->config->get('config_name');

            $data['facebook_store_app_id'] = $this->config->get('facebook_store_app_id');
            $data['facebook_store_charset'] = $this->config->get('facebook_store_charset');

            $data['home'] = $this->url->link('feed/facebook_store', '', 'SSL');
            $data['redirect'] = $this->url->link('feed/facebook_store', '', 'SSL');
            $data['redirect_language'] = 'route=feed/facebook_store';
            $data['action'] = $this->url->link('common/home', '', 'SSL');
            $data['action_currency'] = $this->url->link('common/currency/currency', '', $this->request->server['HTTPS']);
            $data['action_language'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

            if ($this->config->get('facebook_store_show_header_currency')) {
                $data = $this->getCurrency($data);
            } else {
                $data['currencies'] = array();
            }

            if ($this->config->get('facebook_store_show_header_language')) {
                $data = $this->getLangauge($data);
            } else {
                $data['languages'] = array();
            }

            if ($this->config->get('facebook_store_show_header_category')) {
                $data = $this->getCategories($data);
            } else {
                $data['categories'] = array();
            }

            $this->load->model('catalog/category');
            $this->load->model('catalog/product');
            $this->load->model('tool/image');

            if (isset($this->request->get['filter_category_id'])) {
                $filter_category_id = $this->request->get['filter_category_id'];
            } else {
                $filter_category_id = 0;
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = '';
            }

            if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
            } else {
                $order = '';
            }

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = $this->config->get('config_product_limit');
            }

            $url = '';

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
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

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', '', 'SSL'),
                'separator' => false
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('feed/facebook_store', $url, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $data['products'] = array();

            $filter_data = array(
                'filter_category_id' => $filter_category_id,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );

            $this->load->model('feed/facebook_store');

            $product_total = $this->model_feed_facebook_store->getTotalProducts($filter_data, $this->config->get('facebook_store_feed'));

            $results = $this->model_feed_facebook_store->getProducts($filter_data, $this->config->get('facebook_store_feed'));

            $data['products'] = array();

            if ($results) {
                foreach ($results as $result) {
                    $this->request->server['HTTPS'] = '1';

                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                    }

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }

                    if ((float) $result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
                    } else {
                        $tax = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = (int) $result['rating'];
                    } else {
                        $rating = false;
                    }

                    $this->trigger->fire('pre.product.display', array(&$result, 'facebook_store'));

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'       => $image,
                        'name'        => $result['name'],
                        'description' => $result['description'],
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'reviews'     => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
                        'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'], 'SSL')
                    );
                }
            }

            $url = '';

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $this->url->link('feed/facebook_store', 'sort=p.sort_order&order=ASC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $this->url->link('feed/facebook_store', 'sort=pd.name&order=ASC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $this->url->link('feed/facebook_store', 'sort=pd.name&order=DESC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $this->url->link('feed/facebook_store', 'sort=p.price&order=ASC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $this->url->link('feed/facebook_store', 'sort=p.price&order=DESC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_rating_desc'),
                'value' => 'rating-DESC',
                'href' => $this->url->link('feed/facebook_store', 'sort=rating&order=DESC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_rating_asc'),
                'value' => 'rating-ASC',
                'href' => $this->url->link('feed/facebook_store', 'sort=rating&order=ASC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href' => $this->url->link('feed/facebook_store', 'sort=p.model&order=ASC' . $url, 'SSL')
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href' => $this->url->link('feed/facebook_store', 'sort=p.model&order=DESC' . $url, 'SSL')
            );

            $url = '';

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = array(
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('feed/facebook_store', $url . '&limit=' . $value)
                );
            }

            $url = '';

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['app_id'] = $this->config->get('facebook_store_app_id');
            $data['show_header_currency'] = $this->config->get('facebook_store_show_header_currency');
            $data['show_header_language'] = $this->config->get('facebook_store_show_header_language');
            $data['show_header_category'] = $this->config->get('facebook_store_show_header_category');
            $data['show_header_search'] = $this->config->get('facebook_store_show_header_search');
            $data['show_footer'] = $this->config->get('facebook_store_show_footer');
            $data['show_product_description'] = $this->config->get('facebook_store_show_product_description');
            $data['show_product_price'] = $this->config->get('facebook_store_show_product_price');
            $data['show_product_rating'] = $this->config->get('facebook_store_show_product_rating');
            $data['show_addtocart'] = $this->config->get('facebook_store_show_addtocart');

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('feed/facebook_store', $url . '&page={page}');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;
            $data['filter_category_id'] = $filter_category_id;

            $data['not_activate'] = false;

            $data['continue'] = $this->url->link('common/home');

            if ($this->config->get('facebook_store_show_footer')) {
                $data = $this->getFooter($data);
            } else {
                $data['footer'] = false;
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/feed/facebook_store.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/feed/facebook_store.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/feed/facebook_store.tpl', $data));
            }
        } else {
            $data['not_activate'] = true;

            $data['message'] = $this->language->get('text_not_active_facebook_store');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/feed/facebook_store.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/feed/facebook_store.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/feed/facebook_store.tpl', $data));
            }
        }
    }

    protected function getCategories($data)
    {
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        // 3 Level Category Search
        $categories = array();

        $categories_1 = $this->model_catalog_category->getCategories(0);

        foreach ($categories_1 as $category_1) {
            $level_2_data = array();

            $categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

            foreach ($categories_2 as $category_2) {
                $level_3_data = array();

                $categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

                foreach ($categories_3 as $category_3) {
                    $level_3_data[] = array(
                        'category_id' => $category_3['category_id'],
                        'name'        => $category_3['name'],
                    );
                }

                $level_2_data[] = array(
                    'category_id' => $category_2['category_id'],
                    'name'        => $category_2['name'],
                    'children'    => $level_3_data
                );
            }

            $categories['categories'][] = array(
                'category_id' => $category_1['category_id'],
                'name'        => $category_1['name'],
                'children'    => $level_2_data
            );
        }


        foreach ($categories as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    protected function getLangauge($data)
    {
        $language = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
            $this->session->data['language'] = $this->request->post['language_code'];

            if (isset($this->request->post['redirect'])) {
                $this->response->redirect($this->request->post['redirect']);
            } else {
                $this->response->redirect($this->url->link('common/home', '', 'SSL'));
            }
        }

        $language['language_code'] = $this->session->data['language'];

        $this->load->model('localisation/language');

        $language['languages'] = array();

        $results = $this->model_localisation_language->getLanguages();

        foreach ($results as $result) {
            if ($result['status']) {

                $language['languages'][] = array(
                    'name'  => $result['name'],
                    'code'  => $result['code'],
                    'image' => $result['image']
                );
            }
        }

        foreach ($language as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    protected function getCurrency($data)
    {
        $currency = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
            $this->currency->set($this->request->post['currency_code']);

            unset($this->session->data['shipping_methods']);
            unset($this->session->data['shipping_method']);

            if (isset($this->request->post['redirect'])) {
                $this->response->redirect($this->request->post['redirect']);
            } else {
                $this->response->redirect($this->url->link('common/home', '', 'SSL'));
            }
        }

        $currency['currency_code'] = $this->currency->getCode();

        $this->load->model('localisation/currency');

        $currency['currencies'] = array();

        $results = $this->model_localisation_currency->getCurrencies();

        foreach ($results as $result) {
            if ($result['status']) {
                $currency['currencies'][] = array(
                    'title'        => $result['title'],
                    'code'         => $result['code'],
                    'symbol_left'  => $result['symbol_left'],
                    'symbol_right' => $result['symbol_right']
                );
            }
        }

        foreach ($currency as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    protected function getFooter($data)
    {
        $this->language->load('common/footer');

        $footer['text_information'] = $this->language->get('text_information');
        $footer['text_service'] = $this->language->get('text_service');
        $footer['text_extra'] = $this->language->get('text_extra');
        $footer['text_account'] = $this->language->get('text_account');
        $footer['text_contact'] = $this->language->get('text_contact');
        $footer['text_return'] = $this->language->get('text_return');
        $footer['text_sitemap'] = $this->language->get('text_sitemap');
        $footer['text_manufacturer'] = $this->language->get('text_manufacturer');
        $footer['text_voucher'] = $this->language->get('text_voucher');
        $footer['text_affiliate'] = $this->language->get('text_affiliate');
        $footer['text_special'] = $this->language->get('text_special');
        $footer['text_account'] = $this->language->get('text_account');
        $footer['text_order'] = $this->language->get('text_order');
        $footer['text_wishlist'] = $this->language->get('text_wishlist');
        $footer['text_newsletter'] = $this->language->get('text_newsletter');

        $this->load->model('catalog/information');

        $footer['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            $footer['informations'][] = array(
                'title' => $result['title'],
                'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'], 'SSL')
            );
        }

        $footer['contact'] = $this->url->link('information/contact', '', 'SSL');
        $footer['return'] = $this->url->link('account/return/insert', '', 'SSL');
        $footer['sitemap'] = $this->url->link('information/sitemap', '', 'SSL');
        $footer['manufacturer'] = $this->url->link('product/manufacturer', '', 'SSL');
        $footer['voucher'] = $this->url->link('checkout/voucher', '', 'SSL');
        $footer['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
        $footer['special'] = $this->url->link('product/special', '', 'SSL');
        $footer['account'] = $this->url->link('account/account', '', 'SSL');
        $footer['order'] = $this->url->link('account/order', '', 'SSL');
        $footer['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $footer['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');

        $footer['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

        foreach ($footer as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }
}
