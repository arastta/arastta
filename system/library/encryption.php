<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

final class Encryption {
    public function encrypt($key, $value) {
        return strtr(base64_encode(openssl_encrypt($value, 'aes-128-cbc', hash('sha256', $key, true))), '+/=', '-_,');
    }

    public function decrypt($key, $value) {
        return trim(openssl_decrypt(base64_decode(strtr($value, '-_,', '+/=')), 'aes-128-cbc', hash('sha256', $key, true)));
    }
}
