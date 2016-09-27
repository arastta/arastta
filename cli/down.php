<?php

namespace Command;

class Down extends Command
{

    protected $name = 'down';

    protected $description = 'Put the store into maintenance mode';

    public function fire()
    {
        $this->loadAdminModel('setting/setting');

        $this->admin->model_setting_setting->editSettingValue('config', 'config_maintenance', 1);

        $this->info('Maintenance mode enabled');
    }
}
