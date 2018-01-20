<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
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
