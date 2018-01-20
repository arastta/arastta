<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Symfony\Component\Filesystem\Filesystem as SFilesystem;

class Filesystem extends SFilesystem
{

    public function __construct()
    {
        // extend later
    }

    public function mkdir($dirs, $mode = 0755)
    {
        parent::mkdir($dirs, $mode);
    }

    public function remove($files)
    {
        parent::remove($files);
    }

    public function dumpFile($filename, $content, $mode = 0644)
    {
        parent::dumpFile($filename, $content, $mode);
    }
}
