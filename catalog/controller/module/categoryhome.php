<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ControllerModuleCategoryhome extends Controller
{

    public function index($setting)
    {
        $this->load->language('module/categoryhome');

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('catalog/category');

        $this->load->model('tool/image');

        $data['categories'] = $this->getCategories($setting['category_id']);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/categoryhome.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/categoryhome.tpl', $data);
        } else {
            return $this->load->view('default/template/module/categoryhome.tpl', $data);
        }
    }

    protected function getCategories($parent_cat_id)
    {
        $categories = array();
        
        $results = $this->model_catalog_category->getCategories($parent_cat_id);

        if (empty($results)) {
            return $categories;
        }
        
        $i = 0;
        foreach ($results as $result) {
            $categories[$i]['href'] = $this->url->link('product/category', 'path=' . $result['category_id']);

            if ($result['image']) {
                $image = $result['image'];
            } else {
                $image = 'placeholder.png';
            }

            $categories[$i]['thumb'] = $this->model_tool_image->resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
            $categories[$i]['name'] = $result['name'];
            
            $i++;
        }
        
        return $categories;
    }
}
