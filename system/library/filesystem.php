<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
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
