<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class ModelExtensionEvent extends Model {

    public function addEvent($code, $trigger, $action) {

        $exTrigger = explode('.', $trigger);
        $funcitonName = '';

        foreach($exTrigger as $value){
            if(!empty($check)){
                $funcitonName .= ucwords($value);
            } else {
                $check = 1;
                $funcitonName = $value;
            }
        }

        $replaceArray = array(
          '_', '-', '.'
        );
        
        if (is_file(Client::getDir() . 'event/app/' . $code . '.php')) {
            $file = file_get_contents(Client::getDir() . 'event/app/' . $code . '.php', FILE_USE_INCLUDE_PATH);

            $searchString = 'class EventApp' . ucwords(str_replace($replaceArray, "", $code)) . ' extends Event {';

            $string = 'public function ' . $funcitonName . '(&$data) {';

            $function = '

    public function ' . $funcitonName . '(&$data) {
        $this->load->controller("' . $action . '", $data);
    }
        ';
            if (strpos($file, $string) === false) {

                $index = str_replace($searchString, $searchString.$function , $file);

                $content = $index;
            } else{
                $content = $file;
            }
        } else {
            $content = '<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

class EventApp' . ucwords(str_replace($replaceArray, "", $code)) . ' extends Event {

    public function ' . $funcitonName . '(&$data) {
        $this->load->controller("' . $action . '", $data);
    }
}';
        }

        $this->filesystem->dumpFile(Client::getDir() . 'event/app/' . $code . '.php', $content);
    }

    public function deleteEvent($code) {
        $this->filesystem->remove(Client::getDir() . 'event/app/' . $code . '.php');
    }
}
