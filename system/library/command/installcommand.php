<?php

namespace Command;

use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command
{

    protected $name = 'install';

    protected $description = 'Install Arastta';

    public function fire()
    {

        if (is_installed()) {
            $this->error('Arastta is already installed');
            exit;
        }

        if ($requirements = $this->checkRequirements()) {
            $this->error('You do not meet the requirements to install Arastta');
            foreach ($requirements as $requirement) {
                $this->info($requirement);
            }
            exit;
        }
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

        $this->app->load->model('database');
        if (!$this->app->model_database->saveConfig($options)) {
            $this->comment('There was an issue saving the config file, you may have to create it manually');
        }

        //we can now load the config file to get the constants
        if (file_exists(DIR_ROOT . 'config.php') && filesize(DIR_ROOT . 'config.php') > 0) {
            require_once(DIR_ROOT . 'config.php');
        }

        $this->app->load->model('setting');

        //before we create the database tables we need to add language data to the session
        $this->app->session->data['lang_name']      = 'English';
        $this->app->session->data['lang_code']      = 'en';
        $this->app->session->data['lang_image']     = 'gb.png';
        $this->app->session->data['lang_directory'] = 'en-GB';

        $this->app->model_setting->createDatabaseTables($options);

        //now clean up the installation directory
        $this->app->load->model('finish');
        $this->app->model_finish->removeInstall();

        $this->info('Success, Arastta should now be installed');
    }

    private function checkRequirements()
    {
        $this->app->load->model('main');

        return $this->app->model_main->checkRequirements();
    }

    private function validateInputs()
    {
        $errors = array();

        $this->app->load->model('database');
        $this->app->load->model('setting');

        $options = $this->option();

        if (!$this->app->model_database->validateConnection($options)) {
            $errors[] = 'Could not connect or create the database';
        }

        return empty($errors) ? '' : $errors;
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
            array('admin_username', null, InputOption::VALUE_OPTIONAL, 'Username to access the store admin', 'admin'),
            array('admin_email', null, InputOption::VALUE_OPTIONAL, 'Email address for the admin user', ''),
            array('admin_first_name', null, InputOption::VALUE_OPTIONAL, 'First name of the admin user', ''),
            array('admin_last_name', null, InputOption::VALUE_OPTIONAL, 'Surname of the admin user', 'admin'),
            array('admin_password', null, InputOption::VALUE_OPTIONAL, 'Password to login to the store admin', 'admin'),
            array('install_demo_data', null, InputOption::VALUE_NONE, 'Flag to install demo data')
        );
    }
}