<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

namespace Command;

use Symfony\Component\Console\Input\InputOption;

class Install extends Command
{

    protected $name = 'install';

    protected $description = 'Install Arastta';

    public function fire()
    {
        if (is_installed()) {
            $this->error('Arastta is already installed');
            exit;
        }

        $this->loadModel('main', 'install');

        if ($requirements = $this->install->model_main->checkRequirements()) {
            $this->error('You do not meet the requirements to install Arastta');

            foreach ($requirements as $requirement) {
                $this->info($requirement);
            }

            exit;
        }

        $this->loadModel('database', 'install');
        $this->loadModel('setting', 'install');

        if ($errors = $this->validateInputs()) {
            $this->error('Please ensure the input is correct:');

            foreach ($errors as $error) {
                $this->info($error);
            }

            exit;
        }

        $options = $this->option();

        if ($this->option('install_demo_data')) {
            $options['install_demo_data'] = true;
        } else {
            unset($options['install_demo_data']); //the model checks if it isset
        }

        if (!$this->install->model_database->saveConfig($options)) {
            $this->comment('There was an issue saving the config file, you may have to create it manually');
        }

        //we can now load the config file to get the constants
        if (file_exists(DIR_ROOT . 'config.php') && filesize(DIR_ROOT . 'config.php') > 0) {
            require_once(DIR_ROOT . 'config.php');
        }

        //before we create the database tables we need to add language data to the session
        $this->install->session->data['lang_name']      = 'English';
        $this->install->session->data['lang_code']      = 'en';
        $this->install->session->data['lang_image']     = 'gb.png';
        $this->install->session->data['lang_directory'] = 'en-GB';

        $this->install->model_setting->createDatabaseTables($options);

        //now clean up the installation directory
        $this->loadModel('finish', 'install');
        $this->install->model_finish->removeInstall();

        $this->info('Success, Arastta should now be installed');
    }

    private function validateInputs()
    {
        $errors = array();

        $options = $this->option();

        if (!$this->install->model_database->validateConnection($options)) {
            $errors[] = 'Could not connect or create the database';
        }

        $status = empty($errors) ? '' : $errors;

        return $status;
    }

    protected function getOptions()
    {
        return array(
            array('db_hostname', null, InputOption::VALUE_OPTIONAL, 'Host of the Database', 'localhost'),
            array('db_username', null, InputOption::VALUE_REQUIRED, 'Username to access the database'),
            array('db_password', null, InputOption::VALUE_OPTIONAL, 'Password to access the database', ''),
            array('db_database', null, InputOption::VALUE_REQUIRED, 'The name of the database being accessed'),
            array('db_driver', null, InputOption::VALUE_OPTIONAL, 'Which driver to use', 'mysqli'),
            array('db_port', null, InputOption::VALUE_OPTIONAL, 'Port to connect to the database.', '3306'),
            array('db_prefix', null, InputOption::VALUE_OPTIONAL, 'Prefix for the database tables', ''),
            array('store_name', null, InputOption::VALUE_OPTIONAL, 'Store Name', 'Arastta'),
            array('store_email', null, InputOption::VALUE_REQUIRED, 'Email associated with the store'),
            array('http_server', null, InputOption::VALUE_REQUIRED, 'Website server required'),
            array('admin_email', null, InputOption::VALUE_REQUIRED, 'Email address for the admin user'),
            array('admin_password', null, InputOption::VALUE_OPTIONAL, 'Password to login to the store admin', 'admin'),
            array('install_demo_data', null, InputOption::VALUE_NONE, 'Flag to install demo data')
        );
    }
}
