<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class Log {
    private $handle;

    public function __construct($filename) {
        $this->handle = fopen(DIR_LOG . $filename, 'a');
    }

    public function write($message) {
        fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    }
    
    public function __destruct() {
        fclose($this->handle);
    }
}
