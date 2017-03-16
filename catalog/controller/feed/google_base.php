<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerFeedGoogleBase extends Controller {
    public function index() {
        if ($this->config->get('google_base_status')) {
            $store_url = ($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER;

            $output  = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
            $output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . PHP_EOL;
            $output .= '  <channel>' . PHP_EOL;
            $output .= '    <title><![CDATA[' . $this->config->get('config_name') . ']]></title>' . PHP_EOL;
            $output .= '    <description><![CDATA[' . $this->config->get('config_meta_description') . ']]></description>' . PHP_EOL;
            $output .= '    <link><![CDATA[' . $store_url . ']]></link>' . PHP_EOL;

            $this->load->model('catalog/category');

            $this->load->model('catalog/product');

            $this->load->model('tool/image');

            $products = $this->model_catalog_product->getProducts();

            foreach ($products as $product) {
                if ($product['description']) {
                    $output .= '    <item>' . PHP_EOL;
                    $output .= '      <title><![CDATA[' . html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8') . ']]></title>' . PHP_EOL;
                    $output .= '      <link><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></link>' . PHP_EOL;
                    $output .= '      <description><![CDATA[' . strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')) . ']]></description>' . PHP_EOL;
                    $output .= '      <g:brand><![CDATA[' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . ']]></g:brand>' . PHP_EOL;
                    $output .= '      <g:condition>new</g:condition>' . PHP_EOL;
                    $output .= '      <g:id>' . $product['product_id'] . '</g:id>' . PHP_EOL;

                    if ($product['image']) {
                        $output .= '      <g:image_link><![CDATA[' . $this->model_tool_image->resize($product['image'], 500, 500) . ']]></g:image_link>' . PHP_EOL;
                    } else {
                        $output .= '      <g:image_link></g:image_link>' . PHP_EOL;
                    }

                    $output .= '      <g:model_number><![CDATA[' . $product['model'] . ']]></g:model_number>' . PHP_EOL;

                    if ($product['mpn']) {
                        $output .= '      <g:mpn><![CDATA[' . $product['mpn'] . ']]></g:mpn>' . PHP_EOL;
                    } else {
                        $output .= '      <g:identifier_exists>false</g:identifier_exists>' . PHP_EOL;
                    }

                    if ($product['upc']) {
                        $output .= '      <g:upc>' . $product['upc'] . '</g:upc>' . PHP_EOL;
                    }

                    if ($product['ean']) {
                        $output .= '      <g:ean>' . $product['ean'] . '</g:ean>' . PHP_EOL;
                    }

                    $currencies = array(
                        'USD',
                        'EUR',
                        'GBP'
                    );

                    if (in_array($this->currency->getCode(), $currencies)) {
                        $currency_code = $this->currency->getCode();
                        $currency_value = $this->currency->getValue();
                    } else {
                        $currency_code = 'USD';
                        $currency_value = $this->currency->getValue('USD');
                    }

                    if ((float)$product['special']) {
                        $output .= '      <g:price>' .  $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency_code, $currency_value, false) . '</g:price>' . PHP_EOL;
                    } else {
                        $output .= '      <g:price>' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false) . '</g:price>' . PHP_EOL;
                    }

                    $categories = $this->model_catalog_product->getCategories($product['product_id']);

                    foreach ($categories as $category) {
                        $path = $this->getPath($category['category_id']);

                        if ($path) {
                            $string = '';

                            foreach (explode('_', $path) as $path_id) {
                                $category_info = $this->model_catalog_category->getCategory($path_id);

                                if ($category_info) {
                                    if (!$string) {
                                        $string = $category_info['name'];
                                    } else {
                                        $string .= ' &gt; ' . $category_info['name'];
                                    }
                                }
                            }

                            $output .= '      <g:product_type><![CDATA[' . html_entity_decode($string, ENT_QUOTES, 'UTF-8') . ']]></g:product_type>' . PHP_EOL;
                        }
                    }

                    $output .= '      <g:quantity>' . $product['quantity'] . '</g:quantity>' . PHP_EOL;
                    $output .= '      <g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>' . PHP_EOL;
                    $output .= '      <g:availability><![CDATA[' . ($product['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>' . PHP_EOL;
                    $output .= '    </item>' . PHP_EOL;
                }
            }

            $output .= '  </channel>' . PHP_EOL;
            $output .= '</rss>' . PHP_EOL;

            $this->response->addHeader('Content-Type: application/rss+xml');
            $this->response->setOutput($output);
        }
    }

    protected function getPath($parent_id, $current_path = '') {
        $category_info = $this->model_catalog_category->getCategory($parent_id);

        if ($category_info) {
            if (!$current_path) {
                $new_path = $category_info['category_id'];
            } else {
                $new_path = $category_info['category_id'] . '_' . $current_path;
            }

            $path = $this->getPath($category_info['parent_id'], $new_path);

            if ($path) {
                return $path;
            } else {
                return $new_path;
            }
        }
    }
}
