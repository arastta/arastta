<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

require_once(DIR_SYSTEM . 'elfinder/elFinderConnector.class.php');
require_once(DIR_SYSTEM . 'elfinder/elFinder.class.php');
require_once(DIR_SYSTEM . 'elfinder/elFinderVolumeDriver.class.php');
require_once(DIR_SYSTEM . 'elfinder/elFinderVolumeLocalFileSystem.class.php');

class ControllerToolFilemanager extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('tool/file_manager');

        $data = $this->language->all();

        $this->document->setTitle($data['heading_title']);

        $this->document->breadcrumbs = array();

        $this->validate();
        
        $data['fileSystem'] = $this->url->link('tool/file_manager/runFileSystem', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->document->addStyle('view/stylesheet/elfinder.min.css', 'stylesheet', '');
        $this->document->addStyle('view/stylesheet/theme.css', 'stylesheet', '');
        
        $this->document->addScript('view/javascript/jquery/layout/jquery-ui.js');
        $this->document->addScript('view/javascript/elfinder/jquery.browser.js');
        $this->document->addScript('view/javascript/elfinder/elfinder.min.js');
        $this->document->addScript('view/javascript/elfinder/proxy/elFinderSupportVer1.js');

        if (is_file(DIR_ADMIN . 'view/javascript/elfinder/i18n/elfinder.' . $this->language->get('code') . '.js')) {
            $this->document->addScript('view/javascript/elfinder/i18n/elfinder.' . $this->language->get('code') . '.js');
        } else {
            $this->document->addScript('view/javascript/elfinder/i18n/elfinder.en.js');
        }

        $data['lang'] = $this->language->get('code');

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
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('tool/file_manager.tpl', $data));
    }
    
    public function runFileSystem()
    {
        $opts = array(
            // 'debug' => true,
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',
                    'path'          => DIR_ROOT,     #Arastta Root
                    'URL'           => ($this->request->server['HTTPS']) ? HTTPS_CATALOG : HTTP_CATALOG, #Arastta Root
                    'accessControl' => 'access'      //disable and hide dot starting files (OPTIONAL)
                )
            )
        );

        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }

    public function access($attr, $path, $data, $volume)
    {
        return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
            ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
            :  null;                                    // else elFinder decide it itself
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'tool/file_manager')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!extension_loaded('zip')) {
            $this->error['warning'] = $this->language->get('error_zip');
        }

        return !$this->error;
    }
}
