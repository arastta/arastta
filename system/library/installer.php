<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class Installer extends Object
{

    public function __construct($registry)
    {
        $this->cache = $registry->get('cache');
        $this->config = $registry->get('config');
        $this->filesystem = $registry->get('filesystem');
    }

    public function unzip($file)
    {
        $dir = dirname($file);

        $zip = new ZipArchive();

        if (!$zip->open($file)) {
            return false;
        }

        $zip->extractTo($dir);
        $zip->close();

        return true;
    }
}
