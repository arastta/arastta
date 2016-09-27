<?php

namespace Command;

class Up extends Command
{

    protected $name = 'up';

    protected $description = 'Take the store out of maintenance mode';

    public function fire()
    {
        $this->loadAdminModel('setting/setting');

        $this->admin->model_setting_setting->editSettingValue('config', 'config_maintenance', 0);

        $this->info('Maintenance mode disabled');
    }
}
