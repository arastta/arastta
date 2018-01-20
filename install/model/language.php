<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelLanguage extends Model
{

    public function getLanguages()
    {
        static $data = array();

        if (empty($data)) {
            $link = 'https://arastta.io/translation/1.0/installer/translated';

            $json = \Httpful\Request::get($link)
                ->send()
                ->raw_body;

            if (!empty($json)) {
                $data = json_decode($json, true);
            }
        }

        return $data;
    }

    public function downloadLanguage($data)
    {
        $code = $data['lang_code'];

        if ($code == 'en-GB') {
            $this->session->data['lang_name'] = 'English';
            $this->session->data['lang_code'] = 'en';
            $this->session->data['lang_image'] = 'gb.png';
            $this->session->data['lang_directory'] = 'en-GB';
            $this->session->data['lang_product_id'] = '225';
            $this->session->data['lang_version'] = '';

            // Workaround to mutual session ids
            $this->session->data['config_language'] = 'en';
            $this->session->data['config_admin_language'] = 'en';

            return $code;
        }

        $link = 'https://crowdin.com/download/project/arastta/'.$code.'.zip';

        $data = \Httpful\Request::get($link)
	        ->followRedirects()
            ->send()
            ->raw_body;

        if (empty($data)) {
            return false;
        }

        $path = 'temp-' . md5(mt_rand());
        $file = DIR_UPLOAD . $path . '/upload.zip';

        if (!is_dir(DIR_UPLOAD . $path)) {
            $this->filesystem->mkdir(DIR_UPLOAD . $path);
        }

        $uploaded = is_int(file_put_contents($file, $data)) ? true : false;

        if (!$uploaded) {
            return false;
        }

        $installer = new Installer($this->registry);

        if (!$installer->unzip($file)) {
            return false;
        }

        // Remove Zip
        $this->filesystem->remove($file);

        $temp_path = DIR_UPLOAD . $path;

        $json = json_decode(file_get_contents($temp_path . '/install.json'), true);

        $this->session->data['lang_name'] = $json['translation']['name'];
        $this->session->data['lang_code'] = $json['translation']['code'];
        $this->session->data['lang_image'] = $json['translation']['image'];
        $this->session->data['lang_directory'] = $json['translation']['directory'];
        $this->session->data['lang_product_id'] = $json['translation']['product_id'];
        $this->session->data['lang_version'] = $json['translation']['version'];

        // Workaround to mutual session ids
        $this->session->data['config_language'] = $json['translation']['code'];
        $this->session->data['config_admin_language'] = $json['translation']['code'];

        $lang_dir = $json['translation']['directory'];

        // Move all files/folders from temp path
        $this->filesystem->mirror($temp_path . '/admin', DIR_ADMIN . 'language/' . $lang_dir, null, array('override' => true));
        $this->filesystem->mirror($temp_path . '/catalog', DIR_CATALOG . 'language/' . $lang_dir, null, array('override' => true));
        $this->filesystem->mirror($temp_path . '/install', DIR_INSTALL . 'language/' . $lang_dir, null, array('override' => true));

        // Delete the temp path
        $this->filesystem->remove($temp_path);

        return $lang_dir;
    }
}
