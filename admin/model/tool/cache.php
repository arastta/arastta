<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
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
