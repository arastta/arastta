<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelFinish extends Model
{

    public function removeInstall()
    {
        $dirs = array(
            DIR_ROOT . 'image/',
            DIR_ROOT . 'download/',
            DIR_SYSTEM . 'cache/',
            DIR_SYSTEM . 'log/',
        );

        try {
            $this->filesystem->chmod($dirs, 0755, 0000, true);
        } catch (Exception $e) {
            // Discard chmod failure, some systems may not support it
        }

        // Try to rename the robots.txt file
        try {
            $this->filesystem->rename(DIR_ROOT . 'robots.txt.dist', DIR_ROOT . 'robots.txt');
        } catch (Exception $e) {
            // Do nothing
        }

        // Try to remove the install folder
        try {
            $this->filesystem->remove(DIR_ROOT . 'install');
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
