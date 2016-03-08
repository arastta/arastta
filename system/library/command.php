<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

namespace Command;

use Admin;
use Catalog;
use Install;
use Cli;
use Client;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

abstract class Command extends SymfonyCommand
{

    protected $app;

    protected $name;

    protected $description;

    protected $admin;

    protected $catalog;

    protected $install;

    protected $other_apps;

    protected $input;

    protected $output;

    public function __construct(Cli $app = null, Admin $admin = null, Catalog $catalog = null, Install $install = null, $other_apps = array())
    {
        parent::__construct($this->name);

        $this->app = $app; // cli app
        $this->admin = $admin; // admin app
        $this->catalog = $catalog; // catalog app
        $this->install = $install; // install app
        $this->other_apps = $other_apps; // other apps

        $this->setDescription($this->description);

        $this->specifyParameters();
    }

    //set the arguments and options
    protected function specifyParameters()
    {
        foreach ($this->getArguments() as $arguments) {
            call_user_func_array(array($this, 'addArgument'), $arguments);
        }

        foreach ($this->getOptions() as $options) {
            call_user_func_array(array($this, 'addOption'), $options);
        }
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;

        $this->output = new ConsoleOutput();

        return parent::run($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $method = method_exists($this, 'handle') ? 'handle' : 'fire';

        return $this->app->call(array($this, $method));
    }

    public function argument($key = null)
    {
        if (is_null($key)) {
            return $this->input->getArguments();
        }

        return $this->input->getArgument($key);
    }

    public function option($key = null)
    {
        if (is_null($key)) {
            return $this->input->getOptions();
        }

        return $this->input->getOption($key);
    }

    public function confirm($question, $default = false)
    {
        return $this->output->confirm($question, $default);
    }

    public function ask($question, $default = null)
    {
        return $this->output->ask($question, $default);
    }

    public function anticipate($question, array $choices, $default = null)
    {
        return $this->askWithCompletion($question, $choices, $default);
    }

    public function askWithCompletion($question, array $choices, $default = null)
    {
        $question = new Question($question, $default);
        $question->setAutocompleterValues($choices);

        return $this->output->askQuestion($question);
    }

    public function secret($question, $fallback = true)
    {
        $question = new Question($question);
        $question->setHidden(true)->setHiddenFallback($fallback);

        return $this->output->askQuestion($question);
    }

    public function choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
    {
        $question = new ChoiceQuestion($question, $choices, $default);
        $question->setMaxAttempts($attempts)->setMultiselect($multiple);

        return $this->output->askQuestion($question);
    }

    public function table(array $headers, array $rows, $style = 'default')
    {
        $table = new Table($this->output);

        $table->setHeaders($headers)->setRows($rows)->setStyle($style)->render();
    }

    public function info($string)
    {
        $this->output->writeln("<info>$string</info>");
    }

    public function line($string)
    {
        $this->output->writeln($string);
    }

    public function comment($string)
    {
        $this->output->writeln("<comment>$string</comment>");
    }

    public function question($string)
    {
        $this->output->writeln("<question>$string</question>");
    }

    public function error($string)
    {
        $this->output->writeln("<error>$string</error>");
    }

    public function warn($string)
    {
        if (!$this->output->getFormatter()->hasStyle('warning')) {
            $style = new OutputFormatterStyle('yellow');
            $this->output->getFormatter()->setStyle('warning', $style);
        }

        $this->output->writeln("<warning>$string</warning>");
    }

    protected function getArguments()
    {
        return array();
    }

    protected function getOptions()
    {
        return array();
    }

    public function getOutput()
    {
        return $this->output;
    }

    protected function loadAdminModel($model)
    {
        $this->loadModel($model, 'admin');
    }

    protected function loadCatalogModel($model)
    {
        $this->loadModel($model, 'catalog');
    }

    protected function loadModel($model, $client)
    {
        Client::setName($client);
        $this->$client->load->model($model);
        Client::setName('cli');
    }

    protected function loadController($controller, $client)
    {
        Client::setName($client);
        $this->$client->load->controller($controller);
        Client::setName('cli');
    }
}
