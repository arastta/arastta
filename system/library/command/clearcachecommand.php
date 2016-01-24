<?php

namespace Command;

use SplFileInfo;
use Symfony\Component\Console\Input\InputOption;
use Exception;

class ClearCacheCommand extends Command
{

    protected $name = 'cache:clear';

    protected $description = 'Remove data that has been cached.';

    public function fire()
    {

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

        if ($this->option('modification')) {

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

        //if no options were passed just remove the application cache
        $filtered_options = array_filter($this->option());
        if (empty($filtered_options) || $this->option('cache')) {
            $this->app->cache->clear();
            $this->info('Cache Cleared');
        }

    }

    protected function getOptions()
    {
        return array(
            array('images', null, InputOption::VALUE_NONE, 'Flag to clear the cached images'),
            array('modification', null, InputOption::VALUE_NONE, 'Clear the OCmod file modification cache'),
            array('cache', null, InputOption::VALUE_NONE, 'Clear the application cache'),
        );
    }

}