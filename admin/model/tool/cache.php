<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Symfony\Component\Finder\Finder as SFinder;

class ModelToolCache extends Model
{

    public function getCache($path, $name = '')
    {
        $finder = new SFinder();

        if (!empty($name)) {
            $finder->files()->in($path)->name($name);
        } else {
            $finder->files()->in($path);
        }

        $count = 0;
        $size = 0;

        foreach ($finder as $file) {
            $count++;

            $size += number_format(filesize($file->getRealPath()) / 1024);
        }

        return array($count, $size);
    }

    public function deleteCache($path, $name = '')
    {
        $status = true;

        $finder = new SFinder();

        if (!empty($name)) {
            $finder->files()->in($path)->name($name);
        } else {
            $finder->files()->in($path);
        }

        foreach ($finder as $file) {
            try {
                $this->filesystem->remove($file->getRealPath());
            } catch (Exception $e) {
                $status = false;
            }
        }

        return $status;
    }
}
