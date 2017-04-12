<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

defined('AREXE') or die;

/**
 * Version information class for the Arastta eCommerce based on http://semver.org
 *
 * @since  1.0
 */
final class Version extends Object
{

    protected $name = 'Arastta';

    protected $code = 'Tianjin';

    protected $major = '1';

    protected $minor = '6';

    protected $patch = '0';

    protected $build = '';

    protected $status = 'RC 2';

    protected $date = '06-April-2017';

    protected $time = '20:00';

    protected $zone = 'GMT +3';

    /**
     * Compares two a "PHP standardized" version number against the current Arastta version.
     *
     * @param   string  $minimum  The minimum version of the Arastta which is compatible.
     *
     * @return  bool    True if the version is compatible.
     *
     * @see     http://www.php.net/version_compare
     * @since   1.0
     */
    public function isCompatible($minimum)
    {
        return version_compare($this->getShortVersion(), $minimum, 'ge');
    }

    /**
     * Gets a "PHP standardized" version string for the current Arastta.
     *
     * @return  string  Version string.
     *
     * @since   1.0
     */
    public function getReleaseVersion()
    {
        return $this->get('major') . '.' . $this->get('minor');
    }

    /**
     * Gets a "PHP standardized" version string for the current Arastta.
     *
     * @return  string  Version string.
     *
     * @since   1.0
     */
    public function getShortVersion()
    {
        return $this->getReleaseVersion() . '.' . $this->get('patch');
    }

    /**
     * Gets a version string for the current Arastta with all release information.
     *
     * @return  string  Complete version string.
     *
     * @since   1.0
     */
    public function getLongVersion()
    {
        return $this->get('name') . ' ' . $this->getShortVersion() . ' '
        . $this->get('status') . ' [ ' . $this->get('code') . ' ] ' . $this->get('date') . ' '
        . $this->get('time') . ' ' . $this->get('zone');
    }
}
