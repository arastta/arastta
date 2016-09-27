<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

namespace Command;

use Symfony\Component\Console\Input\InputOption;

class Update extends Command
{

    protected $name = 'update';

    protected $description = 'Update Arastta and add-ons';

    public function fire()
    {
        $this->loadModel('common/update', 'admin');

        $this->admin->request->get['version'] = $this->option('a_version');
        $this->admin->request->get['product_id'] = $this->option('a_product_id');

        if ($this->admin->model_common_update->update()) {
            $this->info('Update succeeded');
        } else {
            $this->error('Update failed');
        }
    }

    protected function getOptions()
    {
        return array(
            array('a_version', null, InputOption::VALUE_OPTIONAL, 'Version to update', 'latest'),
            array('a_product_id', null, InputOption::VALUE_OPTIONAL, 'core or product_id available in xyz_addon table', 'core')
        );
    }
}
