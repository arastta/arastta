<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

class Filesystem extends Symfony\Component\Filesystem\Filesystem {

	public function __construct() {
		// extend later
	}

	public function mkdir($dirs, $mode = 0755) {
		parent::mkdir($dirs, $mode);
	}

	public function remove($files) {
		parent::remove($files);
	}
}