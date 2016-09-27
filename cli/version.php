<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

namespace Command;

class Version extends Command
{

    protected $name = 'version';

    protected $description = 'Arastta version';

    public function fire()
    {
        /* @var $version \Version */
        $version = new \Version();
        $this->info($version->getShortVersion());
    }
}
