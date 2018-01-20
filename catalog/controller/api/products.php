<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerApiProducts extends Controller
{

    public function getProduct($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('catalog/product');

            $product = $this->model_catalog_product->getProduct($args['id']);

            $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');
            $product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');

            $currency_value = false;

            if (isset($args['currency_code'])) {
                $currency_code = $args['currency_code'];
            } else {
                $currency_code = $this->config->get('config_currency');
            }

            $product['nice_price'] = $this->currency->format($product['price'], $currency_code, $currency_value);

            $images = array();
            $product['images'] = array();

            $thumb_width = $this->config->get('config_image_thumb_width', 300);
            $thumb_height = $this->config->get('config_image_thumb_height', 300);

            if (!empty($product['image'])) {
                $images[] = $this->model_tool_image->resize($product['image'], $thumb_width, $thumb_height);
            } else {
                $images[] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);
            }
            unset($product['image']);

            $extra_images = $this->model_catalog_product->getProductImages($product['product_id']);
            if (!empty($extra_images)) {
                foreach ($extra_images as $extra_image) {
                    $images[] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);
                }
            }

            foreach ($images as $image) {
                if ($this->request->server['HTTPS']) {
                    $product['images'][] = str_replace($this->config->get('config_ssl'), '', $image);
                } else {
                    $product['images'][] = str_replace($this->config->get('config_url'), '', $image);
                }
            }

            $json = $product;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function getProducts($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $product_data = array();

            $results = $this->model_api_products->getProducts($args);

            if (!empty($results)) {
                $this->load->model('tool/image');
                $this->load->model('catalog/product');

                foreach ($results as $result) {
                    $product = $this->model_catalog_product->getProduct($result['product_id']);

                    $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');
                    $product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');

                    $currency_value = false;

                    if (isset($args['currency_code'])) {
                        $currency_code = $args['currency_code'];
                    } else {
                        $currency_code = $this->config->get('config_currency');
                    }

                    $product['nice_price'] = $this->currency->format($product['price'], $currency_code, $currency_value);

                    $images = array();
                    $product['images'] = array();

                    $thumb_width = $this->config->get('config_image_thumb_width', 300);
                    $thumb_height = $this->config->get('config_image_thumb_height', 300);

                    if (!empty($product['image'])) {
                        $images[] = $this->model_tool_image->resize($product['image'], $thumb_width, $thumb_height);
                    } else {
                        $images[] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);
                    }
                    unset($product['image']);

                    $extra_images = $this->model_catalog_product->getProductImages($result['product_id']);
                    if (!empty($extra_images)) {
                        foreach ($extra_images as $extra_image) {
                            $images[] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);
                        }
                    }

                    foreach ($images as $image) {
                        if ($this->request->server['HTTPS']) {
                            $product['images'][] = str_replace($this->config->get('config_ssl'), '', $image);
                        } else {
                            $product['images'][] = str_replace($this->config->get('config_url'), '', $image);
                        }
                    }

                    $product_data[] = $product;
                }
            }

            $json = $product_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/products');

            $json = $this->model_api_products->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
