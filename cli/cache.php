<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

namespace Command;

use SplFileInfo;
use Symfony\Component\Console\Input\InputOption;
use Exception;

class Cache extends Command
{

    protected $name = 'cache';

    protected $description = 'Remove data that has been cached.';

    public function fire()
    {
        // Clear images cache
        if ($this->option('images')) {
            try {
                $this->app->filesystem->remove(DIR_IMAGE . 'cache/');

                //the file system removed the whole cache folder so we recreate it with the index.html file
                $this->app->filesystem->mkdir(DIR_IMAGE . 'cache', 0755);
                $this->app->filesystem->touch(DIR_IMAGE . 'cache/index.html', 0644);
            } catch (Exception $e) {
                if ($e->getPath() instanceof SplFileInfo) {
                    $filename = $e->getPath()->getPathname();
                } else {
                    $filename = $e->getPath();
                }

                $this->error('Errors removing files. Might be worth checking file permissions.');
                $this->info('');
                $this->info($filename);
                $this->info('');
            }

            $this->info('Image cache files should now be removed');
        }

        // Clear/Refresh modifications cache
        $modification = $this->option('modification');
        if ($modification) {
            if ($modification === 'refresh') {
                $this->loadModel('extension/modification', 'admin');
                $this->app->filesystem->remove(DIR_MODIFICATION);
                
                // Clear Log
                $handle = fopen(DIR_LOG . 'modification.log', 'w+');
                fclose($handle);

                // Apply vQmods and OCmods
                $this->admin->model_extension_modification->applyMod();

                $this->info('Modification refreshed');
            } else {
                try {
                    $this->app->filesystem->remove(DIR_MODIFICATION);

                    $this->app->filesystem->mkdir(DIR_MODIFICATION, 0755);
                    $this->app->filesystem->touch(DIR_MODIFICATION . 'index.html', 0644);
                } catch (Exception $e) {
                    if ($e->getPath() instanceof SplFileInfo) {
                        $filename = $e->getPath()->getPathname();
                    } else {
                        $filename = $e->getPath();
                    }

                    $this->error('Errors removing files. Might be worth checking file permissions.');
                    $this->info('');
                    $this->info($filename);
                    $this->info('');
                }
            }
        }

        // If no options were passed just remove the application cache
        $filtered_options = array_filter($this->option());
        if (empty($filtered_options) || $this->option('application')) {
            $this->app->cache->clear();
            $this->info('Cache Cleared');
        }
    }

    protected function getOptions()
    {
        return array(
            array('images', null, InputOption::VALUE_NONE, 'Flag to clear the cached images'),
            array('modification', null, InputOption::VALUE_OPTIONAL, 'Clear/Refresh the modifications cache'),
            array('application', null, InputOption::VALUE_NONE, 'Clear the application cache')
        );
    }
}
