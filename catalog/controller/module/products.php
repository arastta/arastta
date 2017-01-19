<?php

/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */
class ControllerModuleProducts extends Controller
{
    public function index($setting)
    {
        $this->load->language('module/products');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_tax'] = $this->language->get('text_tax');

        $data['button_cart']     = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare']  = $this->language->get('button_compare');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data['products'] = array();

        switch ($setting['type']) {
            case 'bestsellers':
                $data['products'] = $this->bestseller($setting);
                break;
            case 'featured':
                $data['products'] = $this->featured($setting);
                break;
            case 'latest':
                $data['products'] = $this->latest($setting);
                break;
            case 'special':
                $data['products'] = $this->special($setting);
                break;
        }

        $data['type']                = $setting['type'];
        $data['title']               = $setting['title'];
        $data['show_title']          = $setting['show_title'];
        $data['module_class']        = $setting['module_class'];
        $data['module_column']       = $setting['module_column'];
        $data['product_image']       = $setting['product_image'];
        $data['product_name']        = $setting['product_name'];
        $data['product_description'] = $setting['product_description'];
        $data['product_rating']      = $setting['product_rating'];
        $data['product_price']       = $setting['product_price'];
        $data['add_to_cart']         = $setting['add_to_cart'];
        $data['wish_list']           = $setting['wish_list'];
        $data['compare']             = $setting['compare'];

        $data['bootstrap_module_column'] = $this->bootstrapModuleColumn($setting['module_column']);

        if ($data['products']) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/products.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/products.tpl', $data);
            } else {
                return $this->load->view('default/template/module/products.tpl', $data);
            }
        }
    }

    protected function bestseller($setting)
    {
        $products = array();

        $results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

        if ($results) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }

                $this->trigger->fire("pre.product.display", array(&$result, 'bestseller'));

                $products[] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'description' => $result['description'],
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }
        }

        return $products;
    }

    protected function featured($setting)
    {
        $products = array();

        if (!$setting['limit']) {
            $setting['limit'] = 4;
        }

        if (!empty($setting['product'])) {
            if (isset($setting['random_product']) && !empty($setting['random_product'])) {
                shuffle($setting['product']);
            }

            $results = array_slice($setting['product'], 0, (int) $setting['limit']);

            foreach ($results as $result) {
                $product_info = $this->model_catalog_product->getProduct($result);

                if ($product_info) {
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                    }

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }

                    if ((float) $product_info['special']) {
                        $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $special = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['price']);
                    } else {
                        $tax = false;
                    }

                    if ($this->config->get('config_review_status')) {
                        $rating = $product_info['rating'];
                    } else {
                        $rating = false;
                    }

                    $this->trigger->fire("pre.product.display", array(&$product_info, 'featured'));

                    $products[] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => $product_info['description'],
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );
                }
            }
        }

        return $products;
    }

    protected function latest($setting)
    {
        $products = array();

        $filter_data = array(
            'sort'  => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $setting['limit']
        );

        $results = $this->model_catalog_product->getProducts($filter_data);

        if ($results) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }

                $this->trigger->fire("pre.product.display", array(&$result, 'latest'));

                $products[] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'description' => $result['description'],
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }
        }

        return $products;
    }

    protected function special($setting)
    {
        $products = array();

        $filter_data = array(
            'sort'  => 'pd.name',
            'order' => 'ASC',
            'start' => 0,
            'limit' => $setting['limit']
        );

        $results = $this->model_catalog_product->getProductSpecials($filter_data);

        if ($results) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }

                $this->trigger->fire("pre.product.display", array(&$result, 'special'));

                $products[] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'description' => $result['description'],
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }
        }

        return $products;
    }

    protected function bootstrapModuleColumn($column)
    {
        $columns = array(
            '1' => '12',
            '2' => '6',
            '3' => '4',
            '4' => '3',
            '6' => '2',
            '12' => '1',
        );

        return $columns[$column];
    }
}
