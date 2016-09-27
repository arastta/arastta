<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

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
