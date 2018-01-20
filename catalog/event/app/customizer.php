<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventAppCustomizer extends Event
{

    public function preLoadHeader(&$data)
    {
        if (!is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css')) {
            return;
        }

        $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');

        // Custom CSS
        if (is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css')) {
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css');
        }

        // Custom JS
        if (is_file(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/javascript/custom.js')) {
            $this->document->addScript('catalog/view/theme/' . $this->config->get('config_template') . '/javascript/custom.js');
        }

        // Custom Font
        $this->load->model('appearance/customizer');

        $customizer = $this->model_appearance_customizer->getDefaultData('customizer');

        if (!empty($customizer['font']) && $customizer['font'] != 'inherit' && $customizer['font'] != 'Georgia, serif' && $customizer['font'] != 'Helvetica, sans-serif') {
            $this->document->addStyle('//fonts.googleapis.com/css?family=' . str_replace(' ', '+', $customizer['font']), 'stylesheet', '');
        }
    }
}
