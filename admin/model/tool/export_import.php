<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

static $registry = null;

// Error Handler
function error_handler_for_export_import($errno, $errstr, $errfile, $errline)
{
    global $registry;
    
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $errors = "Notice";
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $errors = "Warning";
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $errors = "Fatal Error";
            break;
        default:
            $errors = "Unknown";
            break;
    }
    
    $config = $registry->get('config');
    $url = $registry->get('url');
    $request = $registry->get('request');
    $session = $registry->get('session');
    $log = $registry->get('log');
    
    if ($config->get('config_error_log')) {
        $log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }

    if (($errors=='Warning') || ($errors=='Unknown')) {
        return true;
    }

    if (($errors != "Fatal Error") && isset($request->get['route']) && ($request->get['route']!='tool/export_import/download')) {
        if ($config->get('config_error_display')) {
            echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
        }
    } else {
        $session->data['export_import_error'] = array( 'errstr'=>$errstr, 'errno'=>$errno, 'errfile'=>$errfile, 'errline'=>$errline );
        $token = $request->get['token'];
        $link = $url->link('tool/export_import', 'token='.$token, 'SSL');
        header('Status: ' . 302);
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $link));
        exit();
    }

    return true;
}

function fatal_error_shutdown_handler_for_export_import()
{
    $last_error = error_get_last();
    if ($last_error['type'] === E_ERROR) {
        // fatal error
        error_handler_for_export_import(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

class ModelToolExportImport extends Model
{

    private $error = array();

    protected $null_array = array();

    protected function clean(&$str, $allowBlanks = false)
    {
        $result = "";
        $n = strlen($str);
        for ($m=0; $m<$n; $m++) {
            $ch = substr($str, $m, 1);
            if (($ch==" ") && (!$allowBlanks) || ($ch=="\n") || ($ch=="\r") || ($ch=="\t") || ($ch=="\0") || ($ch=="\x0B")) {
                continue;
            }
            $result .= $ch;
        }
        return $result;
    }

    protected function multiquery($sql)
    {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $this->db->query($sql);
            }
        }
    }

    protected function startsWith($haystack, $needle)
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }
        return (substr($haystack, 0, strlen($needle)) == $needle);
    }

    protected function endsWith($haystack, $needle)
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }
        return (substr($haystack, strlen($haystack)-strlen($needle), strlen($needle)) == $needle);
    }

    protected function getDefaultLanguageId()
    {
        $code = $this->config->get('config_language');
        $sql = "SELECT language_id FROM `".DB_PREFIX."language` WHERE code = '$code'";
        $result = $this->db->query($sql);
        $language_id = 1;
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $language_id = $row['language_id'];
                break;
            }
        }
        return $language_id;
    }

    protected function getLanguages()
    {
        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."language` WHERE `status`=1 ORDER BY `code`");
        return $query->rows;
    }

    protected function getDefaultWeightUnit()
    {
        $weight_class_id = $this->config->get('config_weight_class_id');
        $language_id = $this->getDefaultLanguageId();
        $sql = "SELECT unit FROM `".DB_PREFIX."weight_class_description` WHERE language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        if ($query->num_rows > 0) {
            return $query->row['unit'];
        }
        $sql = "SELECT language_id FROM `".DB_PREFIX."language` WHERE code = 'en'";
        $query = $this->db->query($sql);
        if ($query->num_rows > 0) {
            $language_id = $query->row['language_id'];
            $sql = "SELECT unit FROM `".DB_PREFIX."weight_class_description` WHERE language_id='".(int)$language_id."'";
            $query = $this->db->query($sql);
            if ($query->num_rows > 0) {
                return $query->row['unit'];
            }
        }
        return 'kg';
    }

    protected function getDefaultMeasurementUnit()
    {
        $length_class_id = $this->config->get('config_length_class_id');
        $language_id = $this->getDefaultLanguageId();
        $sql = "SELECT unit FROM `".DB_PREFIX."length_class_description` WHERE language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        if ($query->num_rows > 0) {
            return $query->row['unit'];
        }
        $sql = "SELECT language_id FROM `".DB_PREFIX."language` WHERE code = 'en'";
        $query = $this->db->query($sql);
        if ($query->num_rows > 0) {
            $language_id = $query->row['language_id'];
            $sql = "SELECT unit FROM `".DB_PREFIX."length_class_description` WHERE language_id='".(int)$language_id."'";
            $query = $this->db->query($sql);
            if ($query->num_rows > 0) {
                return $query->row['unit'];
            }
        }
        return 'cm';
    }

    protected function getManufacturers()
    {
        // find all manufacturers already stored in the database
        $default_language_id = $this->getDefaultLanguageId();
        $manufacturer_ids = array();
        $sql  = "SELECT ms.manufacturer_id, ms.store_id, md.`name` FROM `".DB_PREFIX."manufacturer_to_store` ms ";
        $sql .= "INNER JOIN `".DB_PREFIX."manufacturer_description` md ON md.manufacturer_id=ms.manufacturer_id ";
        $result = $this->db->query($sql);
        $manufacturers = array();
        foreach ($result->rows as $row) {
            $manufacturer_id = $row['manufacturer_id'];
            $store_id = $row['store_id'];
            $name = $row['name'];
            if (!isset($manufacturers[$name])) {
                $manufacturers[$name] = array();
            }
            if (!isset($manufacturers[$name]['manufacturer_id'])) {
                $manufacturers[$name]['manufacturer_id'] = $manufacturer_id;
            }
            if (!isset($manufacturers[$name]['store_ids'])) {
                $manufacturers[$name]['store_ids'] = array();
            }
            if (!in_array($store_id, $manufacturers[$name]['store_ids'])) {
                $manufacturers[$name]['store_ids'][] = $store_id;
            }
        }
        return $manufacturers;
    }

    protected function storeManufacturerIntoDatabase(&$manufacturers, $name, &$store_ids, &$available_store_ids)
    {
        foreach ($store_ids as $store_id) {
            if (!in_array($store_id, $available_store_ids)) {
                continue;
            }
            // find the installed languages
            $languages = $this->getLanguages();

            $old_manufacturer = false;
            foreach ($name as $manufacturer_name) {
                if (isset($manufacturers[$manufacturer_name]['manufacturer_id'])) {
                    $old_manufacturer = true;
                    $manufacturer_id = $manufacturers[$manufacturer_name]['manufacturer_id'];
                }
            }

            if (!$old_manufacturer) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET sort_order = '0', status = '1', date_modified = NOW(), date_added = NOW()");
                $manufacturer_id = $this->db->getLastId();

                foreach ($languages as $language) {
                    $language_code = $language['code'];
                    $language_id = $language['language_id'];
                    $manufacturer_name = isset($name[$language_code]) ? $this->db->escape($name[$language_code]) : '';
                    $this->db->query("INSERT INTO ".DB_PREFIX."manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', name = '".$this->db->escape($manufacturer_name)."', language_id = '" . $language_id . "'");

                    if (!isset($manufacturers[$name]['store_ids'])) {
                        $manufacturers[$name]['store_ids'] = array();
                    }
                }

                if (!in_array($store_id, $manufacturers[$name]['store_ids'])) {
                    $sql = "INSERT INTO `".DB_PREFIX."manufacturer_to_store` SET manufacturer_id='".(int)$manufacturer_id."', store_id='".(int)$store_id."'";
                    $this->db->query($sql);
                    $manufacturers[$name]['store_ids'][] = $store_id;
                }
            } else {
                foreach ($name as $key => $manufacturer_name) {
                    foreach ($languages as $language) {
                        if ($language['code'] == $key) {
                            $language_id = $language['language_id'];
                            break;
                        }
                    }
                    $sql = "UPDATE ".DB_PREFIX."manufacturer_description SET name='" . $this->db->escape($manufacturer_name) . "' WHERE manufacturer_id=". $manufacturer_id . " AND language_id=".$language_id;
                    $this->db->query($sql);
                }
            }
        }

        return $manufacturer_id;
    }

    protected function getWeightClassIds()
    {
        // find the default language id
        $language_id = $this->getDefaultLanguageId();
        
        // find all weight classes already stored in the database
        $weight_class_ids = array();
        $sql = "SELECT `weight_class_id`, `unit` FROM `".DB_PREFIX."weight_class_description` WHERE `language_id`=$language_id;";
        $result = $this->db->query($sql);
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $weight_class_id = $row['weight_class_id'];
                $unit = $row['unit'];
                if (!isset($weight_class_ids[$unit])) {
                    $weight_class_ids[$unit] = $weight_class_id;
                }
            }
        }

        return $weight_class_ids;
    }

    protected function getLengthClassIds()
    {
        // find the default language id
        $language_id = $this->getDefaultLanguageId();
        
        // find all length classes already stored in the database
        $length_class_ids = array();
        $sql = "SELECT `length_class_id`, `unit` FROM `".DB_PREFIX."length_class_description` WHERE `language_id`=$language_id;";
        $result = $this->db->query($sql);
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $length_class_id = $row['length_class_id'];
                $unit = $row['unit'];
                if (!isset($length_class_ids[$unit])) {
                    $length_class_ids[$unit] = $length_class_id;
                }
            }
        }

        return $length_class_ids;
    }

    protected function getLayoutIds()
    {
        $result = $this->db->query("SELECT * FROM `".DB_PREFIX."layout`");
        $layout_ids = array();
        foreach ($result->rows as $row) {
            $layout_ids[$row['name']] = $row['layout_id'];
        }
        return $layout_ids;
    }

    protected function getAvailableStoreIds()
    {
        $sql = "SELECT store_id FROM `".DB_PREFIX."store`;";
        $result = $this->db->query($sql);
        $store_ids = array(0);
        foreach ($result->rows as $row) {
            if (!in_array((int)$row['store_id'], $store_ids)) {
                $store_ids[] = (int)$row['store_id'];
            }
        }
        return $store_ids;
    }

    protected function getAvailableProductIds(&$data)
    {
        $available_product_ids = array();
        $k = $data->getHighestRow();
        for ($i=1; $i<$k; $i+=1) {
            $j = 1;
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=="") {
                continue;
            }
            $available_product_ids[$product_id] = $product_id;
        }
        return $available_product_ids;
    }

    protected function getAvailableCategoryIds()
    {
        $sql = "SELECT `category_id` FROM `".DB_PREFIX."category`;";
        $result = $this->db->query($sql);
        $category_ids = array();
        foreach ($result->rows as $row) {
            $category_ids[$row['category_id']] = $row['category_id'];
        }
        return $category_ids;
    }

    protected function getCustomerGroupIds()
    {
        $sql = "SHOW TABLES LIKE \"".DB_PREFIX."customer_group_description\"";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $language_id = $this->getDefaultLanguageId();
            $sql  = "SELECT `customer_group_id`, `name` FROM `".DB_PREFIX."customer_group_description` ";
            $sql .= "WHERE language_id=$language_id ";
            $sql .= "ORDER BY `customer_group_id` ASC";
            $query = $this->db->query($sql);
        } else {
            $sql  = "SELECT `customer_group_id`, `name` FROM `".DB_PREFIX."customer_group` ";
            $sql .= "ORDER BY `customer_group_id` ASC";
            $query = $this->db->query($sql);
        }
        $customer_group_ids = array();
        foreach ($query->rows as $row) {
            $customer_group_id = $row['customer_group_id'];
            $name = $row['name'];
            $customer_group_ids[$name] = $customer_group_id;
        }
        return $customer_group_ids;
    }

    protected function getCategoryUrlAliasIds()
    {
        $sql  = "SELECT url_alias_id, SUBSTRING( query, CHAR_LENGTH('category_id=')+1 ) AS category_id ";
        $sql .= "FROM `".DB_PREFIX."url_alias` ";
        $sql .= "WHERE query LIKE 'category_id=%'";
        $query = $this->db->query($sql);
        $url_alias_ids = array();
        foreach ($query->rows as $row) {
            $url_alias_id = $row['url_alias_id'];
            $category_id = $row['category_id'];
            $url_alias_ids[$category_id] = $url_alias_id;
        }
        return $url_alias_ids;
    }

    protected function storeCategoryIntoDatabase(&$category, &$languages, $exist_meta_title, &$layout_ids, &$available_store_ids, &$url_alias_ids)
    {
        // extract the category details
        $category_id = $category['category_id'];
        $image_name = $this->db->escape($category['image']);
        $parent_id = $category['parent_id'];
        $top = $category['top'];
        $top = ((strtoupper($top)=="TRUE") || (strtoupper($top)=="YES") || (strtoupper($top)=="ENABLED")) ? 1 : 0;
        $columns = $category['columns'];
        $sort_order = $category['sort_order'];
        $date_added = $category['date_added'];
        $date_modified = $category['date_modified'];
        $names = $category['names'];
        $descriptions = $category['descriptions'];
        if ($exist_meta_title) {
            $meta_titles = $category['meta_titles'];
        }
        $meta_descriptions = $category['meta_descriptions'];
        $meta_keywords = $category['meta_keywords'];
        $seo_keyword = $category['seo_keyword'];
        $store_ids = $category['store_ids'];
        $layout = $category['layout'];
        $status = $category['status'];
        $status = ((strtoupper($status)=="TRUE") || (strtoupper($status)=="YES") || (strtoupper($status)=="ENABLED")) ? 1 : 0;

        // generate and execute SQL for inserting the category
        $sql = "INSERT INTO `".DB_PREFIX."category` (`category_id`, `image`, `parent_id`, `top`, `column`, `sort_order`, `date_added`, `date_modified`, `status`) VALUES ";
        $sql .= "( $category_id, '$image_name', $parent_id, $top, $columns, $sort_order, ";
        $sql .= ($date_added=='NOW()') ? "$date_added," : "'$date_added',";
        $sql .= ($date_modified=='NOW()') ? "$date_modified," : "'$date_modified',";
        $sql .= " $status);";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $description = isset($descriptions[$language_code]) ? $this->db->escape($descriptions[$language_code]) : '';
            if ($exist_meta_title) {
                $meta_title = isset($meta_titles[$language_code]) ? $this->db->escape($meta_titles[$language_code]) : '';
            }
            $meta_description = isset($meta_descriptions[$language_code]) ? $this->db->escape($meta_descriptions[$language_code]) : '';
            $meta_keyword = isset($meta_keywords[$language_code]) ? $this->db->escape($meta_keywords[$language_code]) : '';
            if ($exist_meta_title) {
                $sql  = "INSERT INTO `".DB_PREFIX."category_description` (`category_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ";
                $sql .= "( $category_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword' );";
            } else {
                $sql  = "INSERT INTO `".DB_PREFIX."category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES ";
                $sql .= "( $category_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword' );";
            }
            $this->db->query($sql);
        }
        if ($seo_keyword) {
            if (isset($url_alias_ids[$category_id])) {
                $url_alias_id = $url_alias_ids[$category_id];
                $sql = "INSERT INTO `".DB_PREFIX."url_alias` (`url_alias_id`,`query`,`keyword`) VALUES ($url_alias_id,'category_id=$category_id','$seo_keyword');";
                unset($url_alias_ids[$category_id]);
            } else {
                $sql = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('category_id=$category_id','$seo_keyword');";
            }
            $this->db->query($sql);
        }
        foreach ($store_ids as $store_id) {
            if (in_array((int)$store_id, $available_store_ids)) {
                $sql = "INSERT INTO `".DB_PREFIX."category_to_store` (`category_id`,`store_id`) VALUES ($category_id,$store_id);";
                $this->db->query($sql);
            }
        }
        $layouts = array();
        foreach ($layout as $layout_part) {
            $next_layout = explode(':', $layout_part);
            if ($next_layout===false) {
                $next_layout = array( 0, $layout_part );
            } elseif (count($next_layout)==1) {
                $next_layout = array( 0, $layout_part );
            }
            if ((count($next_layout)==2) && (in_array((int)$next_layout[0], $available_store_ids)) && (is_string($next_layout[1]))) {
                $store_id = (int)$next_layout[0];
                $layout_name = $next_layout[1];
                if (isset($layout_ids[$layout_name])) {
                    $layout_id = (int)$layout_ids[$layout_name];
                    if (!isset($layouts[$store_id])) {
                        $layouts[$store_id] = $layout_id;
                    }
                }
            }
        }
        foreach ($layouts as $store_id => $layout_id) {
            $sql = "INSERT INTO `".DB_PREFIX."category_to_layout` (`category_id`,`store_id`,`layout_id`) VALUES ($category_id,$store_id,$layout_id);";
            $this->db->query($sql);
        }
    }

    protected function deleteCategory($category_id)
    {
        $sql  = "DELETE FROM `".DB_PREFIX."category` WHERE `category_id` = '".(int)$category_id."' ;\n";
        $sql .= "DELETE FROM `".DB_PREFIX."category_description` WHERE `category_id` = '".(int)$category_id."' ;\n";
        $sql .= "DELETE FROM `".DB_PREFIX."category_to_store` WHERE `category_id` = '".(int)$category_id."' ;\n";
        $sql .= "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query` LIKE 'category_id=".(int)$category_id."';\n";
        $sql .= "DELETE FROM `".DB_PREFIX."category_to_layout` WHERE `category_id` = '".(int)$category_id."' ;\n";
        $this->multiquery($sql);
        $sql = "SHOW TABLES LIKE \"".DB_PREFIX."category_path\"";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $sql = "DELETE FROM `".DB_PREFIX."category_path` WHERE `category_id` = '".(int)$category_id."'";
            $this->db->query($sql);
        }
    }

    protected function deleteCategories(&$url_alias_ids)
    {
        $sql  = "TRUNCATE TABLE `".DB_PREFIX."category`;\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."category_description`;\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."category_to_store`;\n";
        $sql .= "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query` LIKE 'category_id=%';\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."category_to_layout`;\n";
        $this->multiquery($sql);
        $sql = "SHOW TABLES LIKE \"".DB_PREFIX."category_path\"";
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $sql = "TRUNCATE TABLE `".DB_PREFIX."category_path`";
            $this->db->query($sql);
        }
        $sql = "SELECT (MAX(url_alias_id)+1) AS next_url_alias_id FROM `".DB_PREFIX."url_alias` LIMIT 1";
        $query = $this->db->query($sql);
        $next_url_alias_id = $query->row['next_url_alias_id'];
        $sql = "ALTER TABLE `".DB_PREFIX."url_alias` AUTO_INCREMENT = $next_url_alias_id";
        $this->db->query($sql);
        $remove = array();
        foreach ($url_alias_ids as $category_id => $url_alias_id) {
            if ($url_alias_id >= $next_url_alias_id) {
                $remove[$category_id] = $url_alias_id;
            }
        }
        foreach ($remove as $category_id => $url_alias_id) {
            unset($url_alias_ids[$category_id]);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreCategoryCells($i, &$j, &$worksheet, &$category)
    {
        return;
    }

    protected function uploadCategories(&$reader, $incremental, &$available_category_ids = array())
    {
        // get worksheet if there
        $data = $reader->getSheetByName('Categories');
        if ($data==null) {
            return;
        }

        // Opencart versions from 2.0 onwards also have category_description.meta_title
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."category_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // get old url_alias_ids
        $url_alias_ids = $this->getCategoryUrlAliasIds();

        // if incremental then find current category IDs else delete all old categories
        $available_category_ids = array();
        if ($incremental) {
            $available_category_ids = $this->getAvailableCategoryIds();
        } else {
            $this->deleteCategories($url_alias_ids);
        }

        // get pre-defined layouts
        $layout_ids = $this->getLayoutIds();

        // get pre-defined store_ids
        $available_store_ids = $this->getAvailableStoreIds();

        // find the installed languages
        $languages = $this->getLanguages();

        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();

        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $category_id = trim($this->getCell($data, $i, $j++));
            if ($category_id=="") {
                continue;
            }
            $parent_id = $this->getCell($data, $i, $j++, '0');
            $names = array();
            while ($this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $top = $this->getCell($data, $i, $j++, ($parent_id=='0')?'true':'false');
            $columns = $this->getCell($data, $i, $j++, ($parent_id=='0')?'1':'0');
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $image_name = trim($this->getCell($data, $i, $j++));
            $date_added = trim($this->getCell($data, $i, $j++));
            $date_added = ((is_string($date_added)) && (strlen($date_added)>0)) ? $date_added : "NOW()";
            $date_modified = trim($this->getCell($data, $i, $j++));
            $date_modified = ((is_string($date_modified)) && (strlen($date_modified)>0)) ? $date_modified : "NOW()";
            $seo_keyword = $this->getCell($data, $i, $j++);
            $descriptions = array();
            while ($this->startsWith($first_row[$j-1], "description(")) {
                $language_code = substr($first_row[$j-1], strlen("description("), strlen($first_row[$j-1])-strlen("description(")-1);
                $description = $this->getCell($data, $i, $j++);
                $description = htmlspecialchars($description);
                $descriptions[$language_code] = $description;
            }
            if ($exist_meta_title) {
                $meta_titles = array();
                while ($this->startsWith($first_row[$j-1], "meta_title(")) {
                    $language_code = substr($first_row[$j-1], strlen("meta_title("), strlen($first_row[$j-1])-strlen("meta_title(")-1);
                    $meta_title = $this->getCell($data, $i, $j++);
                    $meta_title = htmlspecialchars($meta_title);
                    $meta_titles[$language_code] = $meta_title;
                }
            }
            $meta_descriptions = array();
            while ($this->startsWith($first_row[$j-1], "meta_description(")) {
                $language_code = substr($first_row[$j-1], strlen("meta_description("), strlen($first_row[$j-1])-strlen("meta_description(")-1);
                $meta_description = $this->getCell($data, $i, $j++);
                $meta_description = htmlspecialchars($meta_description);
                $meta_descriptions[$language_code] = $meta_description;
            }
            $meta_keywords = array();
            while ($this->startsWith($first_row[$j-1], "meta_keywords(")) {
                $language_code = substr($first_row[$j-1], strlen("meta_keywords("), strlen($first_row[$j-1])-strlen("meta_keywords(")-1);
                $meta_keyword = $this->getCell($data, $i, $j++);
                $meta_keyword = htmlspecialchars($meta_keyword);
                $meta_keywords[$language_code] = $meta_keyword;
            }
            $store_ids = $this->getCell($data, $i, $j++);
            $layout = $this->getCell($data, $i, $j++, '');
            $status = $this->getCell($data, $i, $j++, 'true');
            $category = array();
            $category['category_id'] = $category_id;
            $category['image'] = $image_name;
            $category['parent_id'] = $parent_id;
            $category['sort_order'] = $sort_order;
            $category['date_added'] = $date_added;
            $category['date_modified'] = $date_modified;
            $category['names'] = $names;
            $category['top'] = $top;
            $category['columns'] = $columns;
            $category['descriptions'] = $descriptions;
            if ($exist_meta_title) {
                $category['meta_titles'] = $meta_titles;
            }
            $category['meta_descriptions'] = $meta_descriptions;
            $category['meta_keywords'] = $meta_keywords;
            $category['seo_keyword'] = $seo_keyword;
            $store_ids = trim($this->clean($store_ids, false));
            $category['store_ids'] = ($store_ids=="") ? array() : explode(",", $store_ids);
            if ($category['store_ids']===false) {
                $category['store_ids'] = array();
            }
            $category['layout'] = ($layout=="") ? array() : explode(",", $layout);
            if ($category['layout']===false) {
                $category['layout'] = array();
            }
            $category['status'] = $status;
            if ($incremental) {
                if ($available_category_ids) {
                    if (in_array((int)$category_id, $available_category_ids)) {
                        $this->deleteCategory($category_id);
                    }
                }
            }
            $this->moreCategoryCells($i, $j, $data, $category);
            $this->storeCategoryIntoDatabase($category, $languages, $exist_meta_title, $layout_ids, $available_store_ids, $url_alias_ids);
        }

        // restore category paths for faster lookups on the frontend (only for newer OpenCart versions)
        $this->load->model('catalog/category');
        if (method_exists($this->model_catalog_category, 'repairCategories')) {
            $this->model_catalog_category->repairCategories(0);
        }
    }

    protected function storeCategoryFilterIntoDatabase(&$category_filter, &$languages)
    {
        $category_id = $category_filter['category_id'];
        $filter_id = $category_filter['filter_id'];
        $sql  = "INSERT INTO `".DB_PREFIX."category_filter` (`category_id`, `filter_id`) VALUES ";
        $sql .= "( $category_id, $filter_id );";
        $this->db->query($sql);
    }

    protected function deleteCategoryFilters()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."category_filter`";
        $this->db->query($sql);
    }

    protected function deleteCategoryFilter($category_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."category_filter` WHERE category_id='".(int)$category_id."'";
        $this->db->query($sql);
    }

    protected function deleteUnlistedCategoryFilters(&$unlisted_category_ids)
    {
        foreach ($unlisted_category_ids as $category_id) {
            $sql = "DELETE FROM `".DB_PREFIX."category_filter` WHERE category_id='".(int)$category_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreCategoryFilterCells($i, &$j, &$worksheet, &$category_filter)
    {
        return;
    }

    protected function uploadCategoryFilters(&$reader, $incremental, &$available_category_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('CategoryFilters');
        if ($data==null) {
            return;
        }

        // if incremental then find current category IDs else delete all old category filters
        if ($incremental) {
            $unlisted_category_ids = $available_category_ids;
        } else {
            $this->deleteCategoryFilters();
        }

        if (!$this->config->get('export_import_settings_use_filter_group_id')) {
            $filter_group_ids = $this->getFilterGroupIds();
        }
        if (!$this->config->get('export_import_settings_use_filter_id')) {
            $filter_ids = $this->getFilterIds();
        }

        // load the worksheet cells and store them to the database
        $languages = $this->getLanguages();
        $previous_category_id = 0;
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $category_id = trim($this->getCell($data, $i, $j++));
            if ($category_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_filter_group_id')) {
                $filter_group_id = $this->getCell($data, $i, $j++, '');
            } else {
                $filter_group_name = $this->getCell($data, $i, $j++);
                $filter_group_id = isset($filter_group_ids[$filter_group_name]) ? $filter_group_ids[$filter_group_name] : '';
            }
            if ($filter_group_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $filter_id = $this->getCell($data, $i, $j++, '');
            } else {
                $filter_name = $this->getCell($data, $i, $j++);
                $filter_id = isset($filter_ids[$filter_group_id][$filter_name]) ? $filter_ids[$filter_group_id][$filter_name] : '';
            }
            if ($filter_id=='') {
                continue;
            }
            $category_filter = array();
            $category_filter['category_id'] = $category_id;
            $category_filter['filter_group_id'] = $filter_group_id;
            $category_filter['filter_id'] = $filter_id;
            if (($incremental) && ($category_id != $previous_category_id)) {
                $this->deleteCategoryFilter($category_id);
                if (isset($unlisted_category_ids[$category_id])) {
                    unset($unlisted_category_ids[$category_id]);
                }
            }
            $this->moreCategoryFilterCells($i, $j, $data, $category_filter);
            $this->storeCategoryFilterIntoDatabase($category_filter, $languages);
            $previous_category_id = $category_id;
        }
        if ($incremental) {
            $this->deleteUnlistedCategoryFilters($unlisted_category_ids);
        }
    }
    
    protected function getProductViewCounts()
    {
        $query = $this->db->query("SELECT product_id, viewed FROM `".DB_PREFIX."product`");
        $view_counts = array();
        foreach ($query->rows as $row) {
            $product_id = $row['product_id'];
            $viewed = $row['viewed'];
            $view_counts[$product_id] = $viewed;
        }
        return $view_counts;
    }

    protected function getProductUrlAliasIds()
    {
        $sql  = "SELECT url_alias_id, SUBSTRING( query, CHAR_LENGTH('product_id=')+1 ) AS product_id ";
        $sql .= "FROM `".DB_PREFIX."url_alias` ";
        $sql .= "WHERE query LIKE 'product_id=%'";
        $query = $this->db->query($sql);
        $url_alias_ids = array();
        foreach ($query->rows as $row) {
            $url_alias_id = $row['url_alias_id'];
            $product_id = $row['product_id'];
            $url_alias_ids[$product_id] = $url_alias_id;
        }
        return $url_alias_ids;
    }

    protected function storeProductIntoDatabase(&$product, &$languages, &$product_fields, $exist_table_product_tag, $exist_meta_title, &$layout_ids, &$available_store_ids, &$manufacturers, &$weight_class_ids, &$length_class_ids, &$url_alias_ids)
    {
        // extract the product details
        $product_id = $product['product_id'];
        $names = $product['names'];
        $categories = $product['categories'];
        $quantity = $product['quantity'];
        $model = $this->db->escape($product['model']);
        $manufacturer_name = $product['manufacturer_name'];
        $image = $this->db->escape($product['image']);
        $shipping = $product['shipping'];
        $shipping = ((strtoupper($shipping)=="YES") || (strtoupper($shipping)=="Y") || (strtoupper($shipping)=="TRUE")) ? 1 : 0;
        $price = trim($product['price']);
        $points = $product['points'];
        $date_added = $product['date_added'];
        $date_modified = $product['date_modified'];
        $date_available = $product['date_available'];
        $weight = ($product['weight']=="") ? 0 : $product['weight'];
        $weight_unit = $product['weight_unit'];
        $weight_class_id = (isset($weight_class_ids[$weight_unit])) ? $weight_class_ids[$weight_unit] : 0;
        $status = $product['status'];
        $status = ((strtoupper($status)=="TRUE") || (strtoupper($status)=="YES") || (strtoupper($status)=="ENABLED")) ? 1 : 0;
        $tax_class_id = $product['tax_class_id'];
        $viewed = $product['viewed'];
        $descriptions = $product['descriptions'];
        $stock_status_id = $product['stock_status_id'];
        if ($exist_meta_title) {
            $meta_titles = $product['meta_titles'];
        }
        $meta_descriptions = $product['meta_descriptions'];
        $length = $product['length'];
        $width = $product['width'];
        $height = $product['height'];
        $keyword = $this->db->escape($product['seo_keyword']);
        $length_unit = $product['measurement_unit'];
        $length_class_id = (isset($length_class_ids[$length_unit])) ? $length_class_ids[$length_unit] : 0;
        $sku = $this->db->escape($product['sku']);
        $upc = $this->db->escape($product['upc']);
        if (in_array('ean', $product_fields)) {
            $ean = $this->db->escape($product['ean']);
        }
        if (in_array('jan', $product_fields)) {
            $jan = $this->db->escape($product['jan']);
        }
        if (in_array('isbn', $product_fields)) {
            $isbn = $this->db->escape($product['isbn']);
        }
        if (in_array('mpn', $product_fields)) {
            $mpn = $this->db->escape($product['mpn']);
        }
        $location = $this->db->escape($product['location']);
        $store_ids = $product['store_ids'];
        $layout = $product['layout'];
        $related_ids = $product['related_ids'];
        $subtract = $product['subtract'];
        $subtract = ((strtoupper($subtract)=="TRUE") || (strtoupper($subtract)=="YES") || (strtoupper($subtract)=="ENABLED")) ? 1 : 0;
        $minimum = $product['minimum'];
        $meta_keywords = $product['meta_keywords'];
        $tags = $product['tags'];
        $sort_order = $product['sort_order'];
        foreach ($manufacturers as $key => $value) {
            $manufacturer_id = $this->storeManufacturerIntoDatabase($manufacturers, $manufacturer_name, $store_ids, $available_store_ids);
            break;
        }

        $manufacturer_id = empty($manufacturer_id) ? 0 : $manufacturer_id;

        // generate and execute SQL for inserting the product
        $sql  = "INSERT INTO `".DB_PREFIX."product` (`product_id`,`quantity`,`sku`,`upc`,";
        $sql .= in_array('ean', $product_fields) ? "`ean`," : "";
        $sql .= in_array('jan', $product_fields) ? "`jan`," : "";
        $sql .= in_array('isbn', $product_fields) ? "`isbn`," : "";
        $sql .= in_array('mpn', $product_fields) ? "`mpn`," : "";
        $sql .= "`location`,`stock_status_id`,`model`,`manufacturer_id`,`image`,`shipping`,`price`,`points`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
        $sql .= "`tax_class_id`,`viewed`,`length`,`width`,`height`,`length_class_id`,`sort_order`,`subtract`,`minimum`) VALUES ";
        $sql .= "($product_id,$quantity,'$sku','$upc',";
        $sql .= in_array('ean', $product_fields) ? "'$ean'," : "";
        $sql .= in_array('jan', $product_fields) ? "'$jan'," : "";
        $sql .= in_array('isbn', $product_fields) ? "'$isbn'," : "";
        $sql .= in_array('mpn', $product_fields) ? "'$mpn'," : "";
        $sql .= "'$location',$stock_status_id,'$model',$manufacturer_id,'$image',$shipping,$price,$points,";
        $sql .= ($date_added=='NOW()') ? "$date_added," : "'$date_added',";
        $sql .= ($date_modified=='NOW()') ? "$date_modified," : "'$date_modified',";
        $sql .= ($date_available=='NOW()') ? "$date_available," : "'$date_available',";
        $sql .= "$weight,$weight_class_id,$status,";
        $sql .= "$tax_class_id,$viewed,$length,$width,$height,'$length_class_id','$sort_order','$subtract','$minimum');";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $description = isset($descriptions[$language_code]) ? $this->db->escape($descriptions[$language_code]) : '';
            if ($exist_meta_title) {
                $meta_title = isset($meta_titles[$language_code]) ? $this->db->escape($meta_titles[$language_code]) : '';
            }
            $meta_description = isset($meta_descriptions[$language_code]) ? $this->db->escape($meta_descriptions[$language_code]) : '';
            $meta_keyword = isset($meta_keywords[$language_code]) ? $this->db->escape($meta_keywords[$language_code]) : '';
            $tag = isset($tags[$language_code]) ? $this->db->escape($tags[$language_code]) : '';
            if ($exist_table_product_tag) {
                if ($exist_meta_title) {
                    $sql  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ";
                    $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword' );";
                } else {
                    $sql  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES ";
                    $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword' );";
                }
                $this->db->query($sql);
                $sql  = "INSERT INTO `".DB_PREFIX."product_tag` (`product_id`,`language_id`,`tag`) VALUES ";
                $sql .= "($product_id, $language_id, '$tag')";
                $this->db->query($sql);
            } else {
                if ($exist_meta_title) {
                    $sql  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`, `tag`) VALUES ";
                    $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword', '$tag' );";
                } else {
                    $sql  = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `tag`) VALUES ";
                    $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword', '$tag' );";
                }
                $this->db->query($sql);
            }
        }
        if (count($categories) > 0) {
            $sql = "INSERT INTO `".DB_PREFIX."product_to_category` (`product_id`,`category_id`) VALUES ";
            $first = true;
            foreach ($categories as $category_id) {
                $sql .= ($first) ? "\n" : ",\n";
                $first = false;
                $sql .= "($product_id,$category_id)";
            }
            $sql .= ";";
            $this->db->query($sql);
        }
        if ($keyword) {
            if (isset($url_alias_ids[$product_id])) {
                $url_alias_id = $url_alias_ids[$product_id];
                $sql = "INSERT INTO `".DB_PREFIX."url_alias` (`url_alias_id`,`query`,`keyword`) VALUES ($url_alias_id,'product_id=$product_id','$keyword');";
                unset($url_alias_ids[$product_id]);
            } else {
                $sql = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('product_id=$product_id','$keyword');";
            }
            $this->db->query($sql);
        }
        foreach ($store_ids as $store_id) {
            if (in_array((int)$store_id, $available_store_ids)) {
                $sql = "INSERT INTO `".DB_PREFIX."product_to_store` (`product_id`,`store_id`) VALUES ($product_id,$store_id);";
                $this->db->query($sql);
            }
        }
        $layouts = array();
        foreach ($layout as $layout_part) {
            $next_layout = explode(':', $layout_part);
            if ($next_layout===false) {
                $next_layout = array( 0, $layout_part );
            } elseif (count($next_layout)==1) {
                $next_layout = array( 0, $layout_part );
            }
            if ((count($next_layout)==2) && (in_array((int)$next_layout[0], $available_store_ids)) && (is_string($next_layout[1]))) {
                $store_id = (int)$next_layout[0];
                $layout_name = $next_layout[1];
                if (isset($layout_ids[$layout_name])) {
                    $layout_id = (int)$layout_ids[$layout_name];
                    if (!isset($layouts[$store_id])) {
                        $layouts[$store_id] = $layout_id;
                    }
                }
            }
        }
        foreach ($layouts as $store_id => $layout_id) {
            $sql = "INSERT INTO `".DB_PREFIX."product_to_layout` (`product_id`,`store_id`,`layout_id`) VALUES ($product_id,$store_id,$layout_id);";
            $this->db->query($sql);
        }
        if (count($related_ids) > 0) {
            $sql = "INSERT INTO `".DB_PREFIX."product_related` (`product_id`,`related_id`) VALUES ";
            $first = true;
            foreach ($related_ids as $related_id) {
                $sql .= ($first) ? "\n" : ",\n";
                $first = false;
                $sql .= "($product_id,$related_id)";
            }
            $sql .= ";";
            $this->db->query($sql);
        }
    }

    protected function deleteProducts($exist_table_product_tag, &$url_alias_ids)
    {
        $sql  = "TRUNCATE TABLE `".DB_PREFIX."product`;\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."product_description`;\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."product_to_category`;\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."product_to_store`;\n";
        $sql .= "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query` LIKE 'product_id=%';\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."product_related`;\n";
        $sql .= "TRUNCATE TABLE `".DB_PREFIX."product_to_layout`;\n";
        if ($exist_table_product_tag) {
            $sql .= "TRUNCATE TABLE `".DB_PREFIX."product_tag`;\n";
        }
        $this->multiquery($sql);
        $sql = "SELECT (MAX(url_alias_id)+1) AS next_url_alias_id FROM `".DB_PREFIX."url_alias` LIMIT 1";
        $query = $this->db->query($sql);
        $next_url_alias_id = $query->row['next_url_alias_id'];
        $sql = "ALTER TABLE `".DB_PREFIX."url_alias` AUTO_INCREMENT = $next_url_alias_id";
        $this->db->query($sql);
        $remove = array();
        foreach ($url_alias_ids as $product_id => $url_alias_id) {
            if ($url_alias_id >= $next_url_alias_id) {
                $remove[$product_id] = $url_alias_id;
            }
        }
        foreach ($remove as $product_id => $url_alias_id) {
            unset($url_alias_ids[$product_id]);
        }
    }

    protected function deleteProduct($product_id, $exist_table_product_tag)
    {
        $sql  = "DELETE FROM `".DB_PREFIX."product` WHERE `product_id` = '$product_id';\n";
        $sql .= "DELETE FROM `".DB_PREFIX."product_description` WHERE `product_id` = '$product_id';\n";
        $sql .= "DELETE FROM `".DB_PREFIX."product_to_category` WHERE `product_id` = '$product_id';\n";
        $sql .= "DELETE FROM `".DB_PREFIX."product_to_store` WHERE `product_id` = '$product_id';\n";
        $sql .= "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query` LIKE 'product_id=$product_id';\n";
        $sql .= "DELETE FROM `".DB_PREFIX."product_related` WHERE `product_id` = '$product_id';\n";
        $sql .= "DELETE FROM `".DB_PREFIX."product_to_layout` WHERE `product_id` = '$product_id';\n";
        if ($exist_table_product_tag) {
            $sql .= "DELETE FROM `".DB_PREFIX."product_tag` WHERE `product_id` = '$product_id';\n";
        }
        $this->multiquery($sql);
    }

    // function for reading additional cells in class extensions
    protected function moreProductCells($i, &$j, &$worksheet, &$product)
    {
        return;
    }

    protected function uploadProducts(&$reader, $incremental, &$available_product_ids = array())
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Products');
        if ($data==null) {
            return;
        }

        // save product view counts
        $view_counts = $this->getProductViewCounts();

        // save old url_alias_ids
        $url_alias_ids = $this->getProductUrlAliasIds();

        // some older versions of OpenCart use the 'product_tag' table
        $exist_table_product_tag = false;
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."product_tag'");
        $exist_table_product_tag = ($query->num_rows > 0);

        // Opencart versions from 2.0 onwards also have product_description.meta_title
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // if incremental then find current product IDs else delete all old products
        $available_product_ids = array();
        if ($incremental) {
            $available_product_ids = $this->getAvailableProductIds($data);
        } else {
            $this->deleteProducts($exist_table_product_tag, $url_alias_ids);
        }

        // get pre-defined layouts
        $layout_ids = $this->getLayoutIds();

        // get pre-defined store_ids
        $available_store_ids = $this->getAvailableStoreIds();

        // find the installed languages
        $languages = $this->getLanguages();

        // find the default units
        $default_weight_unit = $this->getDefaultWeightUnit();
        $default_measurement_unit = $this->getDefaultMeasurementUnit();
        $default_stock_status_id = $this->config->get('config_stock_status_id');

        // find existing manufacturers, only newly specified manufacturers will be added
        $manufacturers = $this->getManufacturers();

        // get weight classes
        $weight_class_ids = $this->getWeightClassIds();

        // get length classes
        $length_class_ids = $this->getLengthClassIds();

        // get list of the field names, some are only available for certain OpenCart versions
        $query = $this->db->query("DESCRIBE `".DB_PREFIX."product`");
        $product_fields = array();
        foreach ($query->rows as $row) {
            $product_fields[] = $row['Field'];
        }

        // load the worksheet cells and store them to the database
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=="") {
                continue;
            }
            $names = array();
            while ($this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $categories = $this->getCell($data, $i, $j++);
            $sku = $this->getCell($data, $i, $j++, '');
            $upc = $this->getCell($data, $i, $j++, '');
            if (in_array('ean', $product_fields)) {
                $ean = $this->getCell($data, $i, $j++, '');
            }
            if (in_array('jan', $product_fields)) {
                $jan = $this->getCell($data, $i, $j++, '');
            }
            if (in_array('isbn', $product_fields)) {
                $isbn = $this->getCell($data, $i, $j++, '');
            }
            if (in_array('mpn', $product_fields)) {
                $mpn = $this->getCell($data, $i, $j++, '');
            }
            $location = $this->getCell($data, $i, $j++, '');
            $quantity = $this->getCell($data, $i, $j++, '0');
            $model = $this->getCell($data, $i, $j++, '   ');
            while ($this->startsWith($first_row[$j-1], "manufacturer(")) {
                $language_code = substr($first_row[$j-1], strlen("manufacturer("), strlen($first_row[$j-1])-strlen("manufacturer(")-1);
                $manufacturer_name = $this->getCell($data, $i, $j++);
                $manufacturer_name = htmlspecialchars($manufacturer_name);
                $manufacturer_names[$language_code] = $manufacturer_name;
            }
            $image_name = $this->getCell($data, $i, $j++);
            $shipping = $this->getCell($data, $i, $j++, 'yes');
            $price = $this->getCell($data, $i, $j++, '0.00');
            $points = $this->getCell($data, $i, $j++, '0');
            $date_added = $this->getCell($data, $i, $j++);
            $date_added = ((is_string($date_added)) && (strlen($date_added)>0)) ? $date_added : "NOW()";
            $date_modified = $this->getCell($data, $i, $j++);
            $date_modified = ((is_string($date_modified)) && (strlen($date_modified)>0)) ? $date_modified : "NOW()";
            $date_available = $this->getCell($data, $i, $j++);
            $date_available = ((is_string($date_available)) && (strlen($date_available)>0)) ? $date_available : "NOW()";
            $weight = $this->getCell($data, $i, $j++, '0');
            $weight_unit = $this->getCell($data, $i, $j++, $default_weight_unit);
            $length = $this->getCell($data, $i, $j++, '0');
            $width = $this->getCell($data, $i, $j++, '0');
            $height = $this->getCell($data, $i, $j++, '0');
            $measurement_unit = $this->getCell($data, $i, $j++, $default_measurement_unit);
            $status = $this->getCell($data, $i, $j++, 'true');
            $tax_class_id = $this->getCell($data, $i, $j++, '0');
            $keyword = $this->getCell($data, $i, $j++);
            $descriptions = array();
            while ($this->startsWith($first_row[$j-1], "description(")) {
                $language_code = substr($first_row[$j-1], strlen("description("), strlen($first_row[$j-1])-strlen("description(")-1);
                $description = $this->getCell($data, $i, $j++);
                $description = htmlspecialchars($description);
                $descriptions[$language_code] = $description;
            }
            if ($exist_meta_title) {
                $meta_titles = array();
                while ($this->startsWith($first_row[$j-1], "meta_title(")) {
                    $language_code = substr($first_row[$j-1], strlen("meta_title("), strlen($first_row[$j-1])-strlen("meta_title(")-1);
                    $meta_title = $this->getCell($data, $i, $j++);
                    $meta_title = htmlspecialchars($meta_title);
                    $meta_titles[$language_code] = $meta_title;
                }
            }
            $meta_descriptions = array();
            while ($this->startsWith($first_row[$j-1], "meta_description(")) {
                $language_code = substr($first_row[$j-1], strlen("meta_description("), strlen($first_row[$j-1])-strlen("meta_description(")-1);
                $meta_description = $this->getCell($data, $i, $j++);
                $meta_description = htmlspecialchars($meta_description);
                $meta_descriptions[$language_code] = $meta_description;
            }
            $meta_keywords = array();
            while ($this->startsWith($first_row[$j-1], "meta_keywords(")) {
                $language_code = substr($first_row[$j-1], strlen("meta_keywords("), strlen($first_row[$j-1])-strlen("meta_keywords(")-1);
                $meta_keyword = $this->getCell($data, $i, $j++);
                $meta_keyword = htmlspecialchars($meta_keyword);
                $meta_keywords[$language_code] = $meta_keyword;
            }
            $stock_status_id = $this->getCell($data, $i, $j++, $default_stock_status_id);
            $store_ids = $this->getCell($data, $i, $j++);
            $layout = $this->getCell($data, $i, $j++);
            $related = $this->getCell($data, $i, $j++);
            $tags = array();
            while ($this->startsWith($first_row[$j-1], "tags(")) {
                $language_code = substr($first_row[$j-1], strlen("tags("), strlen($first_row[$j-1])-strlen("tags(")-1);
                $tag = $this->getCell($data, $i, $j++);
                $tag = htmlspecialchars($tag);
                $tags[$language_code] = $tag;
            }
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $subtract = $this->getCell($data, $i, $j++, 'true');
            $minimum = $this->getCell($data, $i, $j++, '1');
            $product = array();
            $product['product_id'] = $product_id;
            $product['names'] = $names;
            $categories = trim($this->clean($categories, false));
            $product['categories'] = ($categories=="") ? array() : explode(",", $categories);
            if ($product['categories']===false) {
                $product['categories'] = array();
            }
            $product['quantity'] = $quantity;
            $product['model'] = $model;
            $product['manufacturer_name'] = $manufacturer_names;
            $product['image'] = $image_name;
            $product['shipping'] = $shipping;
            $product['price'] = $price;
            $product['points'] = $points;
            $product['date_added'] = $date_added;
            $product['date_modified'] = $date_modified;
            $product['date_available'] = $date_available;
            $product['weight'] = $weight;
            $product['weight_unit'] = $weight_unit;
            $product['status'] = $status;
            $product['tax_class_id'] = $tax_class_id;
            $product['viewed'] = isset($view_counts[$product_id]) ? $view_counts[$product_id] : 0;
            $product['descriptions'] = $descriptions;
            $product['stock_status_id'] = $stock_status_id;
            if ($exist_meta_title) {
                $product['meta_titles'] = $meta_titles;
            }
            $product['meta_descriptions'] = $meta_descriptions;
            $product['length'] = $length;
            $product['width'] = $width;
            $product['height'] = $height;
            $product['seo_keyword'] = $keyword;
            $product['measurement_unit'] = $measurement_unit;
            $product['sku'] = $sku;
            $product['upc'] = $upc;
            if (in_array('ean', $product_fields)) {
                $product['ean'] = $ean;
            }
            if (in_array('jan', $product_fields)) {
                $product['jan'] = $jan;
            }
            if (in_array('isbn', $product_fields)) {
                $product['isbn'] = $isbn;
            }
            if (in_array('mpn', $product_fields)) {
                $product['mpn'] = $mpn;
            }
            $product['location'] = $location;
            $store_ids = trim($this->clean($store_ids, false));
            $product['store_ids'] = ($store_ids=="") ? array() : explode(",", $store_ids);
            if ($product['store_ids']===false) {
                $product['store_ids'] = array();
            }
            $product['related_ids'] = ($related=="") ? array() : explode(",", $related);
            if ($product['related_ids']===false) {
                $product['related_ids'] = array();
            }
            $product['layout'] = ($layout=="") ? array() : explode(",", $layout);
            if ($product['layout']===false) {
                $product['layout'] = array();
            }
            $product['subtract'] = $subtract;
            $product['minimum'] = $minimum;
            $product['meta_keywords'] = $meta_keywords;
            $product['tags'] = $tags;
            $product['sort_order'] = $sort_order;
            if ($incremental) {
                $this->deleteProduct($product_id, $exist_table_product_tag);
            }
            $this->moreProductCells($i, $j, $data, $product);
            $this->storeProductIntoDatabase($product, $languages, $product_fields, $exist_table_product_tag, $exist_meta_title, $layout_ids, $available_store_ids, $manufacturers, $weight_class_ids, $length_class_ids, $url_alias_ids);
        }
    }

    protected function storeAdditionalImageIntoDatabase(&$image, &$old_product_image_ids, $exist_sort_order = true)
    {
        $product_id = $image['product_id'];
        $image_name = $image['image_name'];
        if ($exist_sort_order) {
            $sort_order = $image['sort_order'];
        }
        if (isset($old_product_image_ids[$product_id][$image_name])) {
            $product_image_id = $old_product_image_ids[$product_id][$image_name];
            if ($exist_sort_order) {
                $sql  = "INSERT INTO `".DB_PREFIX."product_image` (`product_image_id`,`product_id`,`image`,`sort_order` ) VALUES ";
                $sql .= "($product_image_id,$product_id,'".$this->db->escape($image_name)."',$sort_order)";
            } else {
                $sql  = "INSERT INTO `".DB_PREFIX."product_image` (`product_image_id`,`product_id`,`image` ) VALUES ";
                $sql .= "($product_image_id,$product_id,'".$this->db->escape($image_name)."')";
            }
            $this->db->query($sql);
            unset($old_product_image_ids[$product_id][$image_name]);
        } else {
            if ($exist_sort_order) {
                $sql  = "INSERT INTO `".DB_PREFIX."product_image` (`product_id`,`image`,`sort_order` ) VALUES ";
                $sql .= "($product_id,'".$this->db->escape($image_name)."',$sort_order)";
            } else {
                $sql  = "INSERT INTO `".DB_PREFIX."product_image` (`product_id`,`image` ) VALUES ";
                $sql .= "($product_id,'".$this->db->escape($image_name)."')";
            }
            $this->db->query($sql);
        }
    }

    protected function deleteAdditionalImages()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_image`";
        $this->db->query($sql);
    }

    protected function deleteAdditionalImage($product_id)
    {
        $sql = "SELECT product_image_id, product_id, image FROM `".DB_PREFIX."product_image` WHERE product_id='".(int)$product_id."'";
        $query = $this->db->query($sql);
        $old_product_image_ids = array();
        foreach ($query->rows as $row) {
            $product_image_id = $row['product_image_id'];
            $product_id = $row['product_id'];
            $image_name = $row['image'];
            $old_product_image_ids[$product_id][$image_name] = $product_image_id;
        }
        if ($old_product_image_ids) {
            $sql = "DELETE FROM `".DB_PREFIX."product_image` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
        return $old_product_image_ids;
    }

    protected function deleteUnlistedAdditionalImages(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_image` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreAdditionalImageCells($i, &$j, &$worksheet, &$image)
    {
        return;
    }

    protected function uploadAdditionalImages(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('AdditionalImages');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old additional images
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteAdditionalImages();
        }

        // check for the existence of product_image.sort_order field
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_image` LIKE 'sort_order'";
        $query = $this->db->query($sql);
        $exist_sort_order = ($query->num_rows > 0) ? true : false;

        // load the worksheet cells and store them to the database
        $old_product_image_ids = array();
        $previous_product_id = 0;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            $j= 1;
            if ($i==0) {
                continue;
            }
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=="") {
                continue;
            }
            $image_name = $this->getCell($data, $i, $j++, '');
            if ($exist_sort_order) {
                $sort_order = $this->getCell($data, $i, $j++, '0');
            }
            $image = array();
            $image['product_id'] = $product_id;
            $image['image_name'] = $image_name;
            if ($exist_sort_order) {
                $image['sort_order'] = $sort_order;
            }
            if (($incremental) && ($product_id != $previous_product_id)) {
                $old_product_image_ids = $this->deleteAdditionalImage($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreAdditionalImageCells($i, $j, $data, $image);
            $this->storeAdditionalImageIntoDatabase($image, $old_product_image_ids, $exist_sort_order);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedAdditionalImages($unlisted_product_ids);
        }
    }

    protected function storeSpecialIntoDatabase(&$special, &$old_product_special_ids, &$customer_group_ids)
    {
        $product_id = $special['product_id'];
        $name = $special['customer_group'];
        $customer_group_id = isset($customer_group_ids[$name]) ? $customer_group_ids[$name] : $this->config->get('config_customer_group_id');
        $priority = $special['priority'];
        $price = $special['price'];
        $date_start = $special['date_start'];
        $date_end = $special['date_end'];
        if (isset($old_product_special_ids[$product_id][$customer_group_id])) {
            $product_special_id = $old_product_special_ids[$product_id][$customer_group_id];
            $sql  = "INSERT INTO `".DB_PREFIX."product_special` (`product_special_id`,`product_id`,`customer_group_id`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
            $sql .= "($product_special_id,$product_id,$customer_group_id,$priority,$price,'$date_start','$date_end')";
            $this->db->query($sql);
            unset($old_product_special_ids[$product_id][$customer_group_id]);
        } else {
            $sql  = "INSERT INTO `".DB_PREFIX."product_special` (`product_id`,`customer_group_id`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
            $sql .= "($product_id,$customer_group_id,$priority,$price,'$date_start','$date_end')";
            $this->db->query($sql);
        }
    }

    protected function deleteSpecials()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_special`";
        $this->db->query($sql);
    }

    protected function deleteSpecial($product_id)
    {
        $sql = "SELECT product_special_id, product_id, customer_group_id FROM `".DB_PREFIX."product_special` WHERE product_id='".(int)$product_id."'";
        $query = $this->db->query($sql);
        $old_product_special_ids = array();
        foreach ($query->rows as $row) {
            $product_special_id = $row['product_special_id'];
            $product_id = $row['product_id'];
            $customer_group_id = $row['customer_group_id'];
            $old_product_special_ids[$product_id][$customer_group_id] = $product_special_id;
        }
        if ($old_product_special_ids) {
            $sql = "DELETE FROM `".DB_PREFIX."product_special` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
        return $old_product_special_ids;
    }

    protected function deleteUnlistedSpecials(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_special` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreSpecialCells($i, &$j, &$worksheet, &$special)
    {
        return;
    }

    protected function uploadSpecials(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Specials');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old specials
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteSpecials();
        }

        // get existing customer groups
        $customer_group_ids = $this->getCustomerGroupIds();

        // load the worksheet cells and store them to the database
        $old_product_special_ids = array();
        $previous_product_id = 0;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            $j = 1;
            if ($i==0) {
                continue;
            }
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=="") {
                continue;
            }
            $customer_group = trim($this->getCell($data, $i, $j++));
            if ($customer_group=="") {
                continue;
            }
            $priority = $this->getCell($data, $i, $j++, '0');
            $price = $this->getCell($data, $i, $j++, '0');
            $date_start = $this->getCell($data, $i, $j++, '0000-00-00');
            $date_end = $this->getCell($data, $i, $j++, '0000-00-00');
            $special = array();
            $special['product_id'] = $product_id;
            $special['customer_group'] = $customer_group;
            $special['priority'] = $priority;
            $special['price'] = $price;
            $special['date_start'] = $date_start;
            $special['date_end'] = $date_end;
            if (($incremental) && ($product_id != $previous_product_id)) {
                $old_product_special_ids = $this->deleteSpecial($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreSpecialCells($i, $j, $data, $special);
            $this->storeSpecialIntoDatabase($special, $old_product_special_ids, $customer_group_ids);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedSpecials($unlisted_product_ids);
        }
    }

    protected function storeDiscountIntoDatabase(&$discount, &$old_product_discount_ids, &$customer_group_ids)
    {
        $product_id = $discount['product_id'];
        $name = $discount['customer_group'];
        $customer_group_id = isset($customer_group_ids[$name]) ? $customer_group_ids[$name] : $this->config->get('config_customer_group_id');
        $quantity = $discount['quantity'];
        $priority = $discount['priority'];
        $price = $discount['price'];
        $date_start = $discount['date_start'];
        $date_end = $discount['date_end'];
        if (isset($old_product_discount_ids[$product_id][$customer_group_id][$quantity])) {
            $product_discount_id = $old_product_discount_ids[$product_id][$customer_group_id][$quantity];
            $sql  = "INSERT INTO `".DB_PREFIX."product_discount` (`product_discount_id`,`product_id`,`customer_group_id`,`quantity`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
            $sql .= "($product_discount_id,$product_id,$customer_group_id,$quantity,$priority,$price,'$date_start','$date_end')";
            $this->db->query($sql);
            unset($old_product_discount_ids[$product_id][$customer_group_id][$quantity]);
        } else {
            $sql  = "INSERT INTO `".DB_PREFIX."product_discount` (`product_id`,`customer_group_id`,`quantity`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
            $sql .= "($product_id,$customer_group_id,$quantity,$priority,$price,'$date_start','$date_end')";
            $this->db->query($sql);
        }
    }

    protected function deleteDiscounts()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_discount`";
        $this->db->query($sql);
    }

    protected function deleteDiscount($product_id)
    {
        $sql = "SELECT product_discount_id, product_id, customer_group_id, quantity FROM `".DB_PREFIX."product_discount` WHERE product_id='".(int)$product_id."' ORDER BY product_id ASC, customer_group_id ASC, quantity ASC;";
        $query = $this->db->query($sql);
        $old_product_discount_ids = array();
        foreach ($query->rows as $row) {
            $product_discount_id = $row['product_discount_id'];
            $product_id = $row['product_id'];
            $customer_group_id = $row['customer_group_id'];
            $quantity = $row['quantity'];
            $old_product_discount_ids[$product_id][$customer_group_id][$quantity] = $product_discount_id;
        }
        if ($old_product_discount_ids) {
            $sql = "DELETE FROM `".DB_PREFIX."product_discount` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
        return $old_product_discount_ids;
    }

    protected function deleteUnlistedDiscounts(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_discount` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreDiscountCells($i, &$j, &$worksheet, &$discount)
    {
        return;
    }

    protected function uploadDiscounts(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Discounts');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old discounts
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteDiscounts();
        }

        // get existing customer groups
        $customer_group_ids = $this->getCustomerGroupIds();

        // load the worksheet cells and store them to the database
        $old_product_discount_ids = array();
        $previous_product_id = 0;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            $j = 1;
            if ($i==0) {
                continue;
            }
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=="") {
                continue;
            }
            $customer_group = trim($this->getCell($data, $i, $j++));
            if ($customer_group=="") {
                continue;
            }
            $quantity = $this->getCell($data, $i, $j++, '0');
            $priority = $this->getCell($data, $i, $j++, '0');
            $price = $this->getCell($data, $i, $j++, '0');
            $date_start = $this->getCell($data, $i, $j++, '0000-00-00');
            $date_end = $this->getCell($data, $i, $j++, '0000-00-00');
            $discount = array();
            $discount['product_id'] = $product_id;
            $discount['customer_group'] = $customer_group;
            $discount['quantity'] = $quantity;
            $discount['priority'] = $priority;
            $discount['price'] = $price;
            $discount['date_start'] = $date_start;
            $discount['date_end'] = $date_end;
            if (($incremental) && ($product_id != $previous_product_id)) {
                $old_product_discount_ids = $this->deleteDiscount($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreDiscountCells($i, $j, $data, $discount);
            $this->storeDiscountIntoDatabase($discount, $old_product_discount_ids, $customer_group_ids);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedDiscounts($unlisted_product_ids);
        }
    }

    protected function storeRewardIntoDatabase(&$reward, &$old_product_reward_ids, &$customer_group_ids)
    {
        $product_id = $reward['product_id'];
        $name = $reward['customer_group'];
        $customer_group_id = isset($customer_group_ids[$name]) ? $customer_group_ids[$name] : $this->config->get('config_customer_group_id');
        $points = $reward['points'];
        if (isset($old_product_reward_ids[$product_id][$customer_group_id])) {
            $product_reward_id = $old_product_reward_ids[$product_id][$customer_group_id];
            $sql  = "INSERT INTO `".DB_PREFIX."product_reward` (`product_reward_id`,`product_id`,`customer_group_id`,`points` ) VALUES ";
            $sql .= "($product_reward_id,$product_id,$customer_group_id,$points)";
            $this->db->query($sql);
            unset($old_product_reward_ids[$product_id][$customer_group_id]);
        } else {
            $sql  = "INSERT INTO `".DB_PREFIX."product_reward` (`product_id`,`customer_group_id`,`points` ) VALUES ";
            $sql .= "($product_id,$customer_group_id,$points)";
            $this->db->query($sql);
        }
    }

    protected function deleteRewards()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_reward`";
        $this->db->query($sql);
    }

    protected function deleteReward($product_id)
    {
        $sql = "SELECT product_reward_id, product_id, customer_group_id FROM `".DB_PREFIX."product_reward` WHERE product_id='".(int)$product_id."'";
        $query = $this->db->query($sql);
        $old_product_reward_ids = array();
        foreach ($query->rows as $row) {
            $product_reward_id = $row['product_reward_id'];
            $product_id = $row['product_id'];
            $customer_group_id = $row['customer_group_id'];
            $old_product_reward_ids[$product_id][$customer_group_id] = $product_reward_id;
        }
        if ($old_product_reward_ids) {
            $sql = "DELETE FROM `".DB_PREFIX."product_reward` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
        return $old_product_reward_ids;
    }

    protected function deleteUnlistedRewards(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_reward` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreRewardCells($i, &$j, &$worksheet, &$reward)
    {
        return;
    }

    protected function uploadRewards(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Rewards');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old rewards
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteRewards();
        }

        // get existing customer groups
        $customer_group_ids = $this->getCustomerGroupIds();

        // load the worksheet cells and store them to the database
        $old_product_reward_ids = array();
        $previous_product_id = 0;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            $j = 1;
            if ($i==0) {
                continue;
            }
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=="") {
                continue;
            }
            $customer_group = trim($this->getCell($data, $i, $j++));
            if ($customer_group=="") {
                continue;
            }
            $points = $this->getCell($data, $i, $j++, '0');
            $reward = array();
            $reward['product_id'] = $product_id;
            $reward['customer_group'] = $customer_group;
            $reward['points'] = $points;
            if (($incremental) && ($product_id != $previous_product_id)) {
                $old_product_reward_ids = $this->deleteReward($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreRewardCells($i, $j, $data, $reward);
            $this->storeRewardIntoDatabase($reward, $old_product_reward_ids, $customer_group_ids);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedRewards($unlisted_product_ids);
        }
    }

    protected function getOptionIds()
    {
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT option_id, name FROM `".DB_PREFIX."option_description` WHERE language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $option_ids = array();
        foreach ($query->rows as $row) {
            $option_id = $row['option_id'];
            $name = htmlspecialchars_decode($row['name']);
            $option_ids[$name] = $option_id;
        }
        return $option_ids;
    }

    protected function storeProductOptionIntoDatabase(&$product_option, &$old_product_option_ids)
    {
        // Opencart versions from 2.0 onwards use product_option.value instead of the older product_option.option_value
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_option` LIKE 'value'";
        $query = $this->db->query($sql);
        $exist_po_value = ($query->num_rows > 0) ? true : false;

        // DB query for storing the product option
        $product_id = $product_option['product_id'];
        $option_id = $product_option['option_id'];
        $option_value = $product_option['option_value'];
        $required = $product_option['required'];
        $required = ((strtoupper($required)=="TRUE") || (strtoupper($required)=="YES") || (strtoupper($required)=="ENABLED")) ? 1 : 0;
        if (isset($old_product_option_ids[$product_id][$option_id])) {
            $product_option_id = $old_product_option_ids[$product_id][$option_id];
            if ($exist_po_value) {
                $sql  = "INSERT INTO `".DB_PREFIX."product_option` (`product_option_id`,`product_id`,`option_id`,`value`,`required` ) VALUES ";
            } else {
                $sql  = "INSERT INTO `".DB_PREFIX."product_option` (`product_option_id`,`product_id`,`option_id`,`option_value`,`required` ) VALUES ";
            }
            $sql .= "($product_option_id,$product_id,$option_id,'".$this->db->escape($option_value)."',$required)";
            $this->db->query($sql);
            unset($old_product_option_ids[$product_id][$option_id]);
        } else {
            if ($exist_po_value) {
                $sql  = "INSERT INTO `".DB_PREFIX."product_option` (`product_id`,`option_id`,`value`,`required` ) VALUES ";
            } else {
                $sql  = "INSERT INTO `".DB_PREFIX."product_option` (`product_id`,`option_id`,`option_value`,`required` ) VALUES ";
            }
            $sql .= "($product_id,$option_id,'".$this->db->escape($option_value)."',$required)";
            $this->db->query($sql);
        }
    }

    protected function deleteProductOptions()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_option`";
        $this->db->query($sql);
    }

    protected function deleteProductOption($product_id)
    {
        $sql = "SELECT product_option_id, product_id, option_id FROM `".DB_PREFIX."product_option` WHERE product_id='".(int)$product_id."'";
        $query = $this->db->query($sql);
        $old_product_option_ids = array();
        foreach ($query->rows as $row) {
            $product_option_id = $row['product_option_id'];
            $product_id = $row['product_id'];
            $option_id = $row['option_id'];
            $old_product_option_ids[$product_id][$option_id] = $product_option_id;
        }
        if ($old_product_option_ids) {
            $sql = "DELETE FROM `".DB_PREFIX."product_option` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
        return $old_product_option_ids;
    }

    protected function deleteUnlistedProductOptions(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_option` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreProductOptionCells($i, &$j, &$worksheet, &$product_option)
    {
        return;
    }

    protected function uploadProductOptions(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('ProductOptions');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old product options
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteProductOptions();
        }

        if (!$this->config->get('export_import_settings_use_option_id')) {
            $option_ids = $this->getOptionIds();
        }

        // load the worksheet cells and store them to the database
        $old_product_option_ids = array();
        $previous_product_id = 0;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            $j = 1;
            if ($i==0) {
                continue;
            }
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_option_id')) {
                $option_id = $this->getCell($data, $i, $j++, '');
            } else {
                $option_name = $this->getCell($data, $i, $j++);
                $option_id = isset($option_ids[$option_name]) ? $option_ids[$option_name] : '';
            }
            if ($option_id=='') {
                continue;
            }
            $option_value = $this->getCell($data, $i, $j++, '');
            $required = $this->getCell($data, $i, $j++, '0');
            $product_option = array();
            $product_option['product_id'] = $product_id;
            $product_option['option_id'] = $option_id;
            $product_option['option_value'] = $option_value;
            $product_option['required'] = $required;
            if (($incremental) && ($product_id != $previous_product_id)) {
                $old_product_option_ids = $this->deleteProductOption($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreProductOptionCells($i, $j, $data, $product_option);
            $this->storeProductOptionIntoDatabase($product_option, $old_product_option_ids);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedProductOptions($unlisted_product_ids);
        }
    }

    protected function getOptionValueIds()
    {
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT option_id, option_value_id, name FROM `".DB_PREFIX."option_value_description` ";
        $sql .= "WHERE language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $option_value_ids = array();
        foreach ($query->rows as $row) {
            $option_id = $row['option_id'];
            $option_value_id = $row['option_value_id'];
            $name = $row['name'];
            $option_value_ids[$option_id][$name] = $option_value_id;
        }
        return $option_value_ids;
    }

    protected function getProductOptionIds($product_id)
    {
        $sql  = "SELECT product_option_id, option_id FROM `".DB_PREFIX."product_option` ";
        $sql .= "WHERE product_id='".(int)$product_id."'";
        $query = $this->db->query($sql);
        $product_option_ids = array();
        foreach ($query->rows as $row) {
            $product_option_id = $row['product_option_id'];
            $option_id = $row['option_id'];
            $product_option_ids[$option_id] = $product_option_id;
        }
        return $product_option_ids;
    }

    protected function storeProductOptionValueIntoDatabase(&$product_option_value, &$old_product_option_value_ids)
    {
        $product_id = $product_option_value['product_id'];
        $option_id = $product_option_value['option_id'];
        $option_value_id = $product_option_value['option_value_id'];
        $quantity = $product_option_value['quantity'];
        $subtract = $product_option_value['subtract'];
        $subtract = ((strtoupper($subtract)=="TRUE") || (strtoupper($subtract)=="YES") || (strtoupper($subtract)=="ENABLED")) ? 1 : 0;
        $price = $product_option_value['price'];
        $price_prefix = $product_option_value['price_prefix'];
        $points = $product_option_value['points'];
        $points_prefix = $product_option_value['points_prefix'];
        $weight = $product_option_value['weight'];
        $weight_prefix = $product_option_value['weight_prefix'];
        $product_option_id = $product_option_value['product_option_id'];
        if (isset($old_product_option_value_ids[$product_id][$option_id][$option_value_id])) {
            $product_option_value_id = $old_product_option_value_ids[$product_id][$option_id][$option_value_id];
            $sql  = "INSERT INTO `".DB_PREFIX."product_option_value` ";
            $sql .= "(`product_option_value_id`,`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`,`subtract`,`price`,`price_prefix`,`points`,`points_prefix`,`weight`,`weight_prefix` ) VALUES ";
            $sql .= "($product_option_value_id,$product_option_id,$product_id,$option_id,$option_value_id,$quantity,$subtract,$price,'$price_prefix',$points,'$points_prefix',$weight,'$weight_prefix')";
            $this->db->query($sql);
            unset($old_product_option_value_ids[$product_id][$option_id][$option_value_id]);
        } else {
            $sql  = "INSERT INTO `".DB_PREFIX."product_option_value` ";
            $sql .= "(`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`,`subtract`,`price`,`price_prefix`,`points`,`points_prefix`,`weight`,`weight_prefix` ) VALUES ";
            $sql .= "($product_option_id,$product_id,$option_id,$option_value_id,$quantity,$subtract,$price,'$price_prefix',$points,'$points_prefix',$weight,'$weight_prefix')";
            $this->db->query($sql);
        }
    }

    protected function deleteProductOptionValues()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_option_value`";
        $this->db->query($sql);
    }

    protected function deleteProductOptionValue($product_id)
    {
        $sql = "SELECT product_option_value_id, product_id, option_id, option_value_id FROM `".DB_PREFIX."product_option_value` WHERE product_id='".(int)$product_id."'";
        $query = $this->db->query($sql);
        $old_product_option_value_ids = array();
        foreach ($query->rows as $row) {
            $product_option_value_id = $row['product_option_value_id'];
            $product_id = $row['product_id'];
            $option_id = $row['option_id'];
            $option_value_id = $row['option_value_id'];
            $old_product_option_value_ids[$product_id][$option_id][$option_value_id] = $product_option_value_id;
        }
        if ($old_product_option_value_ids) {
            $sql = "DELETE FROM `".DB_PREFIX."product_option_value` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
        return $old_product_option_value_ids;
    }

    protected function deleteUnlistedProductOptionValues(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_option_value` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreProductOptionValueCells($i, &$j, &$worksheet, &$product_option_value)
    {
        return;
    }

    protected function uploadProductOptionValues(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('ProductOptionValues');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old product option values
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteProductOptionValues();
        }

        if (!$this->config->get('export_import_settings_use_option_id')) {
            $option_ids = $this->getOptionIds();
        }
        if (!$this->config->get('export_import_settings_use_option_value_id')) {
            $option_value_ids = $this->getOptionValueIds();
        }

        // load the worksheet cells and store them to the database
        $old_product_option_ids = array();
        $previous_product_id = 0;
        $product_option_id = 0;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            $j = 1;
            if ($i==0) {
                continue;
            }
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_option_id')) {
                $option_id = $this->getCell($data, $i, $j++, '');
            } else {
                $option_name = $this->getCell($data, $i, $j++);
                $option_id = isset($option_ids[$option_name]) ? $option_ids[$option_name] : '';
            }
            if ($option_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_option_value_id')) {
                $option_value_id = $this->getCell($data, $i, $j++, '');
            } else {
                $option_value_name = $this->getCell($data, $i, $j++);
                $option_value_id = isset($option_value_ids[$option_id][$option_value_name]) ? $option_value_ids[$option_id][$option_value_name] : '';
            }
            if ($option_value_id=='') {
                continue;
            }
            $quantity = $this->getCell($data, $i, $j++, '0');
            $subtract = $this->getCell($data, $i, $j++, 'false');
            $price = $this->getCell($data, $i, $j++, '0');
            $price_prefix = $this->getCell($data, $i, $j++, '+');
            $points = $this->getCell($data, $i, $j++, '0');
            $points_prefix = $this->getCell($data, $i, $j++, '+');
            $weight = $this->getCell($data, $i, $j++, '0.00');
            $weight_prefix = $this->getCell($data, $i, $j++, '+');
            if ($product_id != $previous_product_id) {
                $product_option_ids = $this->getProductOptionIds($product_id);
            }
            $product_option_value = array();
            $product_option_value['product_id'] = $product_id;
            $product_option_value['option_id'] = $option_id;
            $product_option_value['option_value_id'] = $option_value_id;
            $product_option_value['quantity'] = $quantity;
            $product_option_value['subtract'] = $subtract;
            $product_option_value['price'] = $price;
            $product_option_value['price_prefix'] = $price_prefix;
            $product_option_value['points'] = $points;
            $product_option_value['points_prefix'] = $points_prefix;
            $product_option_value['weight'] = $weight;
            $product_option_value['weight_prefix'] = $weight_prefix;
            $product_option_value['product_option_id'] = isset($product_option_ids[$option_id]) ? $product_option_ids[$option_id] : 0;
            if (($incremental) && ($product_id != $previous_product_id)) {
                $old_product_option_value_ids = $this->deleteProductOptionValue($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreProductOptionValueCells($i, $j, $data, $product_option_value);
            $this->storeProductOptionValueIntoDatabase($product_option_value, $old_product_option_value_ids);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedProductOptionValues($unlisted_product_ids);
        }
    }

    protected function getAttributeGroupIds()
    {
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT attribute_group_id, name FROM `".DB_PREFIX."attribute_group_description` ";
        $sql .= "WHERE language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $attribute_group_ids = array();
        foreach ($query->rows as $row) {
            $attribute_group_id = $row['attribute_group_id'];
            $name = $row['name'];
            $attribute_group_ids[$name] = $attribute_group_id;
        }
        return $attribute_group_ids;
    }

    protected function getAttributeIds()
    {
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT a.attribute_group_id, ad.attribute_id, ad.name FROM `".DB_PREFIX."attribute_description` ad ";
        $sql .= "INNER JOIN `".DB_PREFIX."attribute` a ON a.attribute_id=ad.attribute_id ";
        $sql .= "WHERE ad.language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $attribute_ids = array();
        foreach ($query->rows as $row) {
            $attribute_group_id = $row['attribute_group_id'];
            $attribute_id = $row['attribute_id'];
            $name = $row['name'];
            $attribute_ids[$attribute_group_id][$name] = $attribute_id;
        }
        return $attribute_ids;
    }

    protected function storeProductAttributeIntoDatabase(&$product_attribute, &$languages)
    {
        $product_id = $product_attribute['product_id'];
        $attribute_id = $product_attribute['attribute_id'];
        $texts = $product_attribute['texts'];
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $text = isset($texts[$language_code]) ? $this->db->escape($texts[$language_code]) : '';
            $sql  = "INSERT INTO `".DB_PREFIX."product_attribute` (`product_id`, `attribute_id`, `language_id`, `text`) VALUES ";
            $sql .= "( $product_id, $attribute_id, $language_id, '$text' );";
            $this->db->query($sql);
        }
    }

    protected function deleteProductAttributes()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_attribute`";
        $this->db->query($sql);
    }

    protected function deleteProductAttribute($product_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."product_attribute` WHERE product_id='".(int)$product_id."'";
        $this->db->query($sql);
    }

    protected function deleteUnlistedProductAttributes(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_attribute` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreProductAttributeCells($i, &$j, &$worksheet, &$product_attribute)
    {
        return;
    }

    protected function uploadProductAttributes(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('ProductAttributes');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old product attributes
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteProductAttributes();
        }

        if (!$this->config->get('export_import_settings_use_attribute_group_id')) {
            $attribute_group_ids = $this->getAttributeGroupIds();
        }
        if (!$this->config->get('export_import_settings_use_attribute_id')) {
            $attribute_ids = $this->getAttributeIds();
        }

        // load the worksheet cells and store them to the database
        $languages = $this->getLanguages();
        $previous_product_id = 0;
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_attribute_group_id')) {
                $attribute_group_id = $this->getCell($data, $i, $j++, '');
            } else {
                $attribute_group_name = $this->getCell($data, $i, $j++);
                $attribute_group_id = isset($attribute_group_ids[$attribute_group_name]) ? $attribute_group_ids[$attribute_group_name] : '';
            }
            if ($attribute_group_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_attribute_id')) {
                $attribute_id = $this->getCell($data, $i, $j++, '');
            } else {
                $attribute_name = $this->getCell($data, $i, $j++);
                $attribute_id = isset($attribute_ids[$attribute_group_id][$attribute_name]) ? $attribute_ids[$attribute_group_id][$attribute_name] : '';
            }
            if ($attribute_id=='') {
                continue;
            }
            $texts = array();
            while (($j<=$max_col) && $this->startsWith($first_row[$j-1], "text(")) {
                $language_code = substr($first_row[$j-1], strlen("text("), strlen($first_row[$j-1])-strlen("text(")-1);
                $text = $this->getCell($data, $i, $j++);
                $text = htmlspecialchars($text);
                $texts[$language_code] = $text;
            }
            $product_attribute = array();
            $product_attribute['product_id'] = $product_id;
            $product_attribute['attribute_group_id'] = $attribute_group_id;
            $product_attribute['attribute_id'] = $attribute_id;
            $product_attribute['texts'] = $texts;
            if (($incremental) && ($product_id != $previous_product_id)) {
                $this->deleteProductAttribute($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreProductAttributeCells($i, $j, $data, $product_attribute);
            $this->storeProductAttributeIntoDatabase($product_attribute, $languages);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedProductAttributes($unlisted_product_ids);
        }
    }

    protected function getFilterGroupIds()
    {
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT filter_group_id, name FROM `".DB_PREFIX."filter_group_description` ";
        $sql .= "WHERE language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $filter_group_ids = array();
        foreach ($query->rows as $row) {
            $filter_group_id = $row['filter_group_id'];
            $name = $row['name'];
            $filter_group_ids[$name] = $filter_group_id;
        }
        return $filter_group_ids;
    }

    protected function getFilterIds()
    {
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT f.filter_group_id, fd.filter_id, fd.name FROM `".DB_PREFIX."filter_description` fd ";
        $sql .= "INNER JOIN `".DB_PREFIX."filter` f ON f.filter_id=fd.filter_id ";
        $sql .= "WHERE fd.language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $filter_ids = array();
        foreach ($query->rows as $row) {
            $filter_group_id = $row['filter_group_id'];
            $filter_id = $row['filter_id'];
            $name = $row['name'];
            $filter_ids[$filter_group_id][$name] = $filter_id;
        }
        return $filter_ids;
    }

    protected function storeProductFilterIntoDatabase(&$product_filter, &$languages)
    {
        $product_id = $product_filter['product_id'];
        $filter_id = $product_filter['filter_id'];
        $sql  = "INSERT INTO `".DB_PREFIX."product_filter` (`product_id`, `filter_id`) VALUES ";
        $sql .= "( $product_id, $filter_id );";
        $this->db->query($sql);
    }

    protected function deleteProductFilters()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."product_filter`";
        $this->db->query($sql);
    }

    protected function deleteProductFilter($product_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."product_filter` WHERE product_id='".(int)$product_id."'";
        $this->db->query($sql);
    }

    protected function deleteUnlistedProductFilters(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = "DELETE FROM `".DB_PREFIX."product_filter` WHERE product_id='".(int)$product_id."'";
            $this->db->query($sql);
        }
    }

    // function for reading additional cells in class extensions
    protected function moreProductFilterCells($i, &$j, &$worksheet, &$product_filter)
    {
        return;
    }

    protected function uploadProductFilters(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('ProductFilters');
        if ($data==null) {
            return;
        }

        // if incremental then find current product IDs else delete all old product filters
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteProductFilters();
        }

        if (!$this->config->get('export_import_settings_use_filter_group_id')) {
            $filter_group_ids = $this->getFilterGroupIds();
        }
        if (!$this->config->get('export_import_settings_use_filter_id')) {
            $filter_ids = $this->getFilterIds();
        }

        // load the worksheet cells and store them to the database
        $languages = $this->getLanguages();
        $previous_product_id = 0;
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $product_id = trim($this->getCell($data, $i, $j++));
            if ($product_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_filter_group_id')) {
                $filter_group_id = $this->getCell($data, $i, $j++, '');
            } else {
                $filter_group_name = $this->getCell($data, $i, $j++);
                $filter_group_id = isset($filter_group_ids[$filter_group_name]) ? $filter_group_ids[$filter_group_name] : '';
            }
            if ($filter_group_id=='') {
                continue;
            }
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $filter_id = $this->getCell($data, $i, $j++, '');
            } else {
                $filter_name = $this->getCell($data, $i, $j++);
                $filter_id = isset($filter_ids[$filter_group_id][$filter_name]) ? $filter_ids[$filter_group_id][$filter_name] : '';
            }
            if ($filter_id=='') {
                continue;
            }
            $product_filter = array();
            $product_filter['product_id'] = $product_id;
            $product_filter['filter_group_id'] = $filter_group_id;
            $product_filter['filter_id'] = $filter_id;
            if (($incremental) && ($product_id != $previous_product_id)) {
                $this->deleteProductFilter($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreProductFilterCells($i, $j, $data, $product_filter);
            $this->storeProductFilterIntoDatabase($product_filter, $languages);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedProductFilters($unlisted_product_ids);
        }
    }

    protected function storeOptionIntoDatabase(&$option, &$languages)
    {
        $option_id = $option['option_id'];
        $type = $option['type'];
        $sort_order = $option['sort_order'];
        $names = $option['names'];
        $sql  = "INSERT INTO `".DB_PREFIX."option` (`option_id`,`type`,`sort_order`) VALUES ";
        $sql .= "( $option_id, '".$this->db->escape($type)."', $sort_order );";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $sql  = "INSERT INTO `".DB_PREFIX."option_description` (`option_id`, `language_id`, `name`) VALUES ";
            $sql .= "( $option_id, $language_id, '$name' );";
            $this->db->query($sql);
        }
    }

    protected function deleteOptions()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."option`";
        $this->db->query($sql);
        $sql = "TRUNCATE TABLE `".DB_PREFIX."option_description`";
        $this->db->query($sql);
    }

    protected function deleteOption($option_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."option` WHERE option_id='".(int)$option_id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM `".DB_PREFIX."option_description` WHERE option_id='".(int)$option_id."'";
        $this->db->query($sql);
    }

    // function for reading additional cells in class extensions
    protected function moreOptionCells($i, &$j, &$worksheet, &$option)
    {
        return;
    }

    protected function uploadOptions(&$reader, $incremental)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Options');
        if ($data==null) {
            return;
        }

        // find the installed languages
        $languages = $this->getLanguages();

        // if not incremental then delete all old options
        if (!$incremental) {
            $this->deleteOptions();
        }

        // load the worksheet cells and store them to the database
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $option_id = trim($this->getCell($data, $i, $j++));
            if ($option_id=='') {
                continue;
            }
            $type = $this->getCell($data, $i, $j++, '');
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $names = array();
            while (($j<=$max_col) && $this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $option = array();
            $option['option_id'] = $option_id;
            $option['type'] = $type;
            $option['sort_order'] = $sort_order;
            $option['names'] = $names;
            if ($incremental) {
                $this->deleteOption($option_id);
            }
            $this->moreOptionCells($i, $j, $data, $option);
            $this->storeOptionIntoDatabase($option, $languages);
        }
    }

    protected function storeOptionValueIntoDatabase(&$option_value, &$languages, $exist_image = true)
    {
        $option_value_id = $option_value['option_value_id'];
        $option_id = $option_value['option_id'];
        if ($exist_image) {
            $image = $option_value['image'];
        }
        $sort_order = $option_value['sort_order'];
        $names = $option_value['names'];
        if ($exist_image) {
            $sql  = "INSERT INTO `".DB_PREFIX."option_value` (`option_value_id`,`option_id`,`image`,`sort_order`) VALUES ";
            $sql .= "( $option_value_id, $option_id, '".$this->db->escape($image)."', $sort_order );";
        } else {
            $sql  = "INSERT INTO `".DB_PREFIX."option_value` (`option_value_id`,`option_id`,`sort_order`) VALUES ";
            $sql .= "( $option_value_id, $option_id, $sort_order );";
        }
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $sql  = "INSERT INTO `".DB_PREFIX."option_value_description` (`option_value_id`, `language_id`, `option_id`, `name`) ";
            $sql .= "VALUES ( $option_value_id, $language_id, $option_id, '$name' );";
            $this->db->query($sql);
        }
    }

    protected function deleteOptionValues()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."option_value`";
        $this->db->query($sql);
        $sql = "TRUNCATE TABLE `".DB_PREFIX."option_value_description`";
        $this->db->query($sql);
    }

    protected function deleteOptionValue($option_value_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."option_value` WHERE option_value_id='".(int)$option_value_id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM `".DB_PREFIX."option_value_description` WHERE option_value_id='".(int)$option_value_id."'";
        $this->db->query($sql);
    }

    // function for reading additional cells in class extensions
    protected function moreOptionValueCells($i, &$j, &$worksheet, &$option)
    {
        return;
    }

    protected function uploadOptionValues(&$reader, $incremental)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('OptionValues');
        if ($data==null) {
            return;
        }

        // check for the existence of option_value.image field
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."option_value` LIKE 'image'";
        $query = $this->db->query($sql);
        $exist_image = ($query->num_rows > 0) ? true : false;

        // find the installed languages
        $languages = $this->getLanguages();

        // if not incremental then delete all old option values
        if (!$incremental) {
            $this->deleteOptionValues();
        }

        // load the worksheet cells and store them to the database
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $option_value_id = trim($this->getCell($data, $i, $j++));
            if ($option_value_id=='') {
                continue;
            }
            $option_id = trim($this->getCell($data, $i, $j++));
            if ($option_id=='') {
                continue;
            }
            if ($exist_image) {
                $image = $this->getCell($data, $i, $j++, '');
            }
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $names = array();
            while (($j<=$max_col) && $this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $option_value = array();
            $option_value['option_value_id'] = $option_value_id;
            $option_value['option_id'] = $option_id;
            if ($exist_image) {
                $option_value['image'] = $image;
            }
            $option_value['sort_order'] = $sort_order;
            $option_value['names'] = $names;
            if ($incremental) {
                $this->deleteOptionValue($option_value_id);
            }
            $this->moreOptionValueCells($i, $j, $data, $option_value);
            $this->storeOptionValueIntoDatabase($option_value, $languages, $exist_image);
        }
    }

    protected function storeAttributeGroupIntoDatabase(&$attribute_group, &$languages)
    {
        $attribute_group_id = $attribute_group['attribute_group_id'];
        $sort_order = $attribute_group['sort_order'];
        $names = $attribute_group['names'];
        $sql  = "INSERT INTO `".DB_PREFIX."attribute_group` (`attribute_group_id`,`sort_order`) VALUES ";
        $sql .= "( $attribute_group_id, $sort_order );";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $sql  = "INSERT INTO `".DB_PREFIX."attribute_group_description` (`attribute_group_id`, `language_id`, `name`) VALUES ";
            $sql .= "( $attribute_group_id, $language_id, '$name' );";
            $this->db->query($sql);
        }
    }

    protected function deleteAttributeGroups()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."attribute_group`";
        $this->db->query($sql);
        $sql = "TRUNCATE TABLE `".DB_PREFIX."attribute_group_description`";
        $this->db->query($sql);
    }

    protected function deleteAttributeGroup($attribute_group_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."attribute_group` WHERE attribute_group_id='".(int)$attribute_group_id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM `".DB_PREFIX."attribute_group_description` WHERE attribute_group_id='".(int)$attribute_group_id."'";
        $this->db->query($sql);
    }

    // function for reading additional cells in class extensions
    protected function moreAttributeGroupCells($i, &$j, &$worksheet, &$attribute_group)
    {
        return;
    }

    protected function uploadAttributeGroups(&$reader, $incremental)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('AttributeGroups');
        if ($data==null) {
            return;
        }

        // find the installed languages
        $languages = $this->getLanguages();

        // if not incremental then delete all old attribute groups
        if (!$incremental) {
            $this->deleteAttributeGroups();
        }

        // load the worksheet cells and store them to the database
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $attribute_group_id = trim($this->getCell($data, $i, $j++));
            if ($attribute_group_id=='') {
                continue;
            }
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $names = array();
            while (($j<=$max_col) && $this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $attribute_group = array();
            $attribute_group['attribute_group_id'] = $attribute_group_id;
            $attribute_group['sort_order'] = $sort_order;
            $attribute_group['names'] = $names;
            if ($incremental) {
                $this->deleteAttributeGroup($attribute_group_id);
            }
            $this->moreAttributeGroupCells($i, $j, $data, $attribute_group);
            $this->storeAttributeGroupIntoDatabase($attribute_group, $languages);
        }
    }

    protected function storeAttributeIntoDatabase(&$attribute, &$languages)
    {
        $attribute_id = $attribute['attribute_id'];
        $attribute_group_id = $attribute['attribute_group_id'];
        $sort_order = $attribute['sort_order'];
        $names = $attribute['names'];
        $sql  = "INSERT INTO `".DB_PREFIX."attribute` (`attribute_id`,`attribute_group_id`,`sort_order`) VALUES ";
        $sql .= "( $attribute_id, $attribute_group_id, $sort_order );";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $sql  = "INSERT INTO `".DB_PREFIX."attribute_description` (`attribute_id`, `language_id`, `name`) ";
            $sql .= "VALUES ( $attribute_id, $language_id, '$name' );";
            $this->db->query($sql);
        }
    }

    protected function deleteAttributes()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."attribute`";
        $this->db->query($sql);
        $sql = "TRUNCATE TABLE `".DB_PREFIX."attribute_description`";
        $this->db->query($sql);
    }

    protected function deleteAttribute($attribute_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."attribute` WHERE attribute_id='".(int)$attribute_id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM `".DB_PREFIX."attribute_description` WHERE attribute_id='".(int)$attribute_id."'";
        $this->db->query($sql);
    }

    // function for reading additional cells in class extensions
    protected function moreAttributeCells($i, &$j, &$worksheet, &$option)
    {
        return;
    }

    protected function uploadAttributes(&$reader, $incremental)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Attributes');
        if ($data==null) {
            return;
        }

        // find the installed languages
        $languages = $this->getLanguages();

        // if not incremental then delete all old attributes
        if (!$incremental) {
            $this->deleteAttributes();
        }

        // load the worksheet cells and store them to the database
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $attribute_id = trim($this->getCell($data, $i, $j++));
            if ($attribute_id=='') {
                continue;
            }
            $attribute_group_id = trim($this->getCell($data, $i, $j++));
            if ($attribute_group_id=='') {
                continue;
            }
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $names = array();
            while (($j<=$max_col) && $this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $attribute = array();
            $attribute['attribute_id'] = $attribute_id;
            $attribute['attribute_group_id'] = $attribute_group_id;
            $attribute['sort_order'] = $sort_order;
            $attribute['names'] = $names;
            if ($incremental) {
                $this->deleteAttribute($attribute_id);
            }
            $this->moreAttributeCells($i, $j, $data, $attribute);
            $this->storeAttributeIntoDatabase($attribute, $languages);
        }
    }

    protected function storeFilterGroupIntoDatabase(&$filter_group, &$languages)
    {
        $filter_group_id = $filter_group['filter_group_id'];
        $sort_order = $filter_group['sort_order'];
        $names = $filter_group['names'];
        $sql  = "INSERT INTO `".DB_PREFIX."filter_group` (`filter_group_id`,`sort_order`) VALUES ";
        $sql .= "( $filter_group_id, $sort_order );";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $sql  = "INSERT INTO `".DB_PREFIX."filter_group_description` (`filter_group_id`, `language_id`, `name`) VALUES ";
            $sql .= "( $filter_group_id, $language_id, '$name' );";
            $this->db->query($sql);
        }
    }

    protected function deleteFilterGroups()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."filter_group`";
        $this->db->query($sql);
        $sql = "TRUNCATE TABLE `".DB_PREFIX."filter_group_description`";
        $this->db->query($sql);
    }

    protected function deleteFilterGroup($filter_group_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."filter_group` WHERE filter_group_id='".(int)$filter_group_id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM `".DB_PREFIX."filter_group_description` WHERE filter_group_id='".(int)$filter_group_id."'";
        $this->db->query($sql);
    }

    // function for reading additional cells in class extensions
    protected function moreFilterGroupCells($i, &$j, &$worksheet, &$filter_group)
    {
        return;
    }

    protected function uploadFilterGroups(&$reader, $incremental)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('FilterGroups');
        if ($data==null) {
            return;
        }

        // find the installed languages
        $languages = $this->getLanguages();

        // if not incremental then delete all old filter groups
        if (!$incremental) {
            $this->deleteFilterGroups();
        }

        // load the worksheet cells and store them to the database
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $filter_group_id = trim($this->getCell($data, $i, $j++));
            if ($filter_group_id=='') {
                continue;
            }
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $names = array();
            while (($j<=$max_col) && $this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $filter_group = array();
            $filter_group['filter_group_id'] = $filter_group_id;
            $filter_group['sort_order'] = $sort_order;
            $filter_group['names'] = $names;
            if ($incremental) {
                $this->deleteFilterGroup($filter_group_id);
            }
            $this->moreFilterGroupCells($i, $j, $data, $filter_group);
            $this->storeFilterGroupIntoDatabase($filter_group, $languages);
        }
    }

    protected function storeFilterIntoDatabase(&$filter, &$languages)
    {
        $filter_id = $filter['filter_id'];
        $filter_group_id = $filter['filter_group_id'];
        $sort_order = $filter['sort_order'];
        $names = $filter['names'];
        $sql  = "INSERT INTO `".DB_PREFIX."filter` (`filter_id`,`filter_group_id`,`sort_order`) VALUES ";
        $sql .= "( $filter_id, $filter_group_id, $sort_order );";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $sql  = "INSERT INTO `".DB_PREFIX."filter_description` (`filter_id`, `language_id`, `filter_group_id`, `name`) ";
            $sql .= "VALUES ( $filter_id, $language_id, $filter_group_id, '$name' );";
            $this->db->query($sql);
        }
    }

    protected function deleteFilters()
    {
        $sql = "TRUNCATE TABLE `".DB_PREFIX."filter`";
        $this->db->query($sql);
        $sql = "TRUNCATE TABLE `".DB_PREFIX."filter_description`";
        $this->db->query($sql);
    }

    protected function deleteFilter($filter_id)
    {
        $sql = "DELETE FROM `".DB_PREFIX."filter` WHERE filter_id='".(int)$filter_id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM `".DB_PREFIX."filter_description` WHERE filter_id='".(int)$filter_id."'";
        $this->db->query($sql);
    }
    
    // function for reading additional cells in class extensions
    protected function moreFilterCells($i, &$j, &$worksheet, &$option)
    {
        return;
    }

    protected function uploadFilters(&$reader, $incremental)
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Filters');
        if ($data==null) {
            return;
        }

        // find the installed languages
        $languages = $this->getLanguages();

        // if not incremental then delete all old filters
        if (!$incremental) {
            $this->deleteFilters();
        }

        // load the worksheet cells and store them to the database
        $first_row = array();
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=0; $i<$k; $i+=1) {
            if ($i==0) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j=1; $j<=$max_col; $j+=1) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $filter_id = trim($this->getCell($data, $i, $j++));
            if ($filter_id=='') {
                continue;
            }
            $filter_group_id = trim($this->getCell($data, $i, $j++));
            if ($filter_group_id=='') {
                continue;
            }
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $names = array();
            while (($j<=$max_col) && $this->startsWith($first_row[$j-1], "name(")) {
                $language_code = substr($first_row[$j-1], strlen("name("), strlen($first_row[$j-1])-strlen("name(")-1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $filter = array();
            $filter['filter_id'] = $filter_id;
            $filter['filter_group_id'] = $filter_group_id;
            $filter['sort_order'] = $sort_order;
            $filter['names'] = $names;
            if ($incremental) {
                $this->deleteFilter($filter_id);
            }
            $this->moreFilterCells($i, $j, $data, $filter);
            $this->storeFilterIntoDatabase($filter, $languages);
        }
    }

    function getCell(&$worksheet, $row, $col, $default_val = '')
    {
        $col -= 1; // we use 1-based, PHPExcel uses 0-based column index
        $row += 1; // we use 0-based, PHPExcel uses 1-based row index
        $val = ($worksheet->cellExistsByColumnAndRow($col, $row)) ? $worksheet->getCellByColumnAndRow($col, $row)->getValue() : $default_val;
        if ($val===null) {
            $val = $default_val;
        }
        return $val;
    }

    function validateHeading(&$data, &$expected, &$multilingual)
    {
        $default_language_code = $this->config->get('config_language');
        $heading = array();
        $k = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
        $i = 0;
        for ($j=1; $j <= $k; $j+=1) {
            $entry = $this->getCell($data, $i, $j);
            $bracket_start = strripos($entry, '(', 0);
            if ($bracket_start === false) {
                if (in_array($entry, $multilingual)) {
                    return false;
                }
                $heading[] = strtolower($entry);
            } else {
                $name = strtolower(substr($entry, 0, $bracket_start));
                if (!in_array($name, $multilingual)) {
                    return false;
                }
                $bracket_end = strripos($entry, ')', $bracket_start);
                if ($bracket_end <= $bracket_start) {
                    return false;
                }
                if ($bracket_end+1 != strlen($entry)) {
                    return false;
                }
                $language_code = strtolower(substr($entry, $bracket_start+1, $bracket_end-$bracket_start-1));
                if (count($heading) <= 0) {
                    return false;
                }
                if ($heading[count($heading)-1] != $name) {
                    $heading[] = $name;
                }
            }
        }
        for ($i=0; $i < count($expected); $i+=1) {
            if (!isset($heading[$i])) {
                return false;
            }
            if ($heading[$i] != $expected[$i]) {
                return false;
            }
        }
        return true;
    }

    protected function validateCategories(&$reader)
    {
        $data = $reader->getSheetByName('Categories');
        if ($data==null) {
            return true;
        }

        // Opencart versions from 2.0 onwards also have category_description.meta_title
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."category_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        if ($exist_meta_title) {
            $expected_heading = array
            ( "category_id", "parent_id", "name", "top", "columns", "sort_order", "image_name", "date_added", "date_modified", "seo_keyword", "description", "meta_title", "meta_description", "meta_keywords", "store_ids", "layout", "status" );
            $expected_multilingual = array( "name", "description", "meta_title", "meta_description", "meta_keywords" );
        } else {
            $expected_heading = array
            ( "category_id", "parent_id", "name", "top", "columns", "sort_order", "image_name", "date_added", "date_modified", "seo_keyword", "description", "meta_description", "meta_keywords", "store_ids", "layout", "status" );
            $expected_multilingual = array( "name", "description", "meta_description", "meta_keywords" );
        }
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateCategoryFilters(&$reader)
    {
        $data = $reader->getSheetByName('CategoryFilters');
        if ($data==null) {
            return true;
        }
        if (!$this->existFilter()) {
            throw new Exception($this->language->get('error_filter_not_supported'));
        }
        if ($this->config->get('export_import_settings_use_filter_group_id')) {
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $expected_heading = array( "category_id", "filter_group_id", "filter_id" );
            } else {
                $expected_heading = array( "category_id", "filter_group_id", "filter" );
            }
        } else {
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $expected_heading = array( "category_id", "filter_group", "filter_id" );
            } else {
                $expected_heading = array( "category_id", "filter_group", "filter" );
            }
        }
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProducts(&$reader)
    {
        $data = $reader->getSheetByName('Products');
        if ($data==null) {
            return true;
        }

        // get list of the field names, some are only available for certain OpenCart versions
        $query = $this->db->query("DESCRIBE `".DB_PREFIX."product`");
        $product_fields = array();
        foreach ($query->rows as $row) {
            $product_fields[] = $row['Field'];
        }

        // Opencart versions from 2.0 onwards also have product_description.meta_title
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        $expected_heading = array
        ( "product_id", "name", "categories", "sku", "upc" );
        if (in_array("ean", $product_fields)) {
            $expected_heading[] = "ean";
        }
        if (in_array("jan", $product_fields)) {
            $expected_heading[] = "jan";
        }
        if (in_array("isbn", $product_fields)) {
            $expected_heading[] = "isbn";
        }
        if (in_array("mpn", $product_fields)) {
            $expected_heading[] = "mpn";
        }
        $expected_heading = array_merge($expected_heading, array( "location", "quantity", "model", "manufacturer", "image_name", "shipping", "price", "points", "date_added", "date_modified", "date_available", "weight", "weight_unit", "length", "width", "height", "length_unit", "status", "tax_class_id", "seo_keyword", "description"));
        if ($exist_meta_title) {
            $expected_heading[] = "meta_title";
        }
        $expected_heading = array_merge($expected_heading, array( "meta_description", "meta_keywords", "stock_status_id", "store_ids", "layout", "related_ids", "tags", "sort_order", "subtract", "minimum" ));
        if ($exist_meta_title) {
            $expected_multilingual = array( "name", "description", "meta_title", "meta_description", "meta_keywords", "tags", "manufacturer" );
        } else {
            $expected_multilingual = array( "name", "description", "meta_description", "meta_keywords", "tags", "manufacturer" );
        }
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateAdditionalImages(&$reader)
    {
        $data = $reader->getSheetByName('AdditionalImages');
        if ($data==null) {
            return true;
        }
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_image` LIKE 'sort_order'";
        $query = $this->db->query($sql);
        $exist_sort_order = ($query->num_rows > 0) ? true : false;
        if ($exist_sort_order) {
            $expected_heading = array( "product_id", "image", "sort_order" );
        } else {
            $expected_heading = array( "product_id", "image" );
        }
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateSpecials(&$reader)
    {
        $data = $reader->getSheetByName('Specials');
        if ($data==null) {
            return true;
        }
        $expected_heading = array( "product_id", "customer_group", "priority", "price", "date_start", "date_end" );
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateDiscounts(&$reader)
    {
        $data = $reader->getSheetByName('Discounts');
        if ($data==null) {
            return true;
        }
        $expected_heading = array( "product_id", "customer_group", "quantity", "priority", "price", "date_start", "date_end" );
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateRewards(&$reader)
    {
        $data = $reader->getSheetByName('Rewards');
        if ($data==null) {
            return true;
        }
        $expected_heading = array( "product_id", "customer_group", "points" );
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductOptions(&$reader)
    {
        $data = $reader->getSheetByName('ProductOptions');
        if ($data==null) {
            return true;
        }
        if ($this->config->get('export_import_settings_use_option_id')) {
            $expected_heading = array( "product_id", "option_id", "default_option_value", "required" );
        } else {
            $expected_heading = array( "product_id", "option", "default_option_value", "required" );
        }
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductOptionValues(&$reader)
    {
        $data = $reader->getSheetByName('ProductOptionValues');
        if ($data==null) {
            return true;
        }
        if ($this->config->get('export_import_settings_use_option_id')) {
            if ($this->config->get('export_import_settings_use_option_value_id')) {
                $expected_heading = array( "product_id", "option_id", "option_value_id", "quantity", "subtract", "price", "price_prefix", "points", "points_prefix", "weight", "weight_prefix" );
            } else {
                $expected_heading = array( "product_id", "option_id", "option_value", "quantity", "subtract", "price", "price_prefix", "points", "points_prefix", "weight", "weight_prefix" );
            }
        } else {
            if ($this->config->get('export_import_settings_use_option_value_id')) {
                $expected_heading = array( "product_id", "option", "option_value_id", "quantity", "subtract", "price", "price_prefix", "points", "points_prefix", "weight", "weight_prefix" );
            } else {
                $expected_heading = array( "product_id", "option", "option_value", "quantity", "subtract", "price", "price_prefix", "points", "points_prefix", "weight", "weight_prefix" );
            }
        }
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductAttributes(&$reader)
    {
        $data = $reader->getSheetByName('ProductAttributes');
        if ($data==null) {
            return true;
        }
        if ($this->config->get('export_import_settings_use_attribute_group_id')) {
            if ($this->config->get('export_import_settings_use_attribute_id')) {
                $expected_heading = array( "product_id", "attribute_group_id", "attribute_id", "text" );
            } else {
                $expected_heading = array( "product_id", "attribute_group_id", "attribute", "text" );
            }
        } else {
            if ($this->config->get('export_import_settings_use_attribute_id')) {
                $expected_heading = array( "product_id", "attribute_group", "attribute_id", "text" );
            } else {
                $expected_heading = array( "product_id", "attribute_group", "attribute", "text" );
            }
        }
        $expected_multilingual = array( "text" );
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductFilters(&$reader)
    {
        $data = $reader->getSheetByName('ProductFilters');
        if ($data==null) {
            return true;
        }
        if (!$this->existFilter()) {
            throw new Exception($this->language->get('error_filter_not_supported'));
        }
        if ($this->config->get('export_import_settings_use_filter_group_id')) {
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $expected_heading = array( "product_id", "filter_group_id", "filter_id" );
            } else {
                $expected_heading = array( "product_id", "filter_group_id", "filter" );
            }
        } else {
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $expected_heading = array( "product_id", "filter_group", "filter_id" );
            } else {
                $expected_heading = array( "product_id", "filter_group", "filter" );
            }
        }
        $expected_multilingual = array();
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateOptions(&$reader)
    {
        $data = $reader->getSheetByName('Options');
        if ($data==null) {
            return true;
        }
        $expected_heading = array( "option_id", "type", "sort_order", "name" );
        $expected_multilingual = array( "name" );
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateOptionValues(&$reader)
    {
        $data = $reader->getSheetByName('OptionValues');
        if ($data==null) {
            return true;
        }
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."option_value` LIKE 'image'";
        $query = $this->db->query($sql);
        $exist_image = ($query->num_rows > 0) ? true : false;
        if ($exist_image) {
            $expected_heading = array( "option_value_id", "option_id", "image", "sort_order", "name" );
        } else {
            $expected_heading = array( "option_value_id", "option_id", "sort_order", "name" );
        }
        $expected_multilingual = array( "name" );
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateAttributeGroups(&$reader)
    {
        $data = $reader->getSheetByName('AttributeGroups');
        if ($data==null) {
            return true;
        }
        $expected_heading = array( "attribute_group_id", "sort_order", "name" );
        $expected_multilingual = array( "name" );
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateAttributes(&$reader)
    {
        $data = $reader->getSheetByName('Attributes');
        if ($data==null) {
            return true;
        }
        $expected_heading = array( "attribute_id", "attribute_group_id", "sort_order", "name" );
        $expected_multilingual = array( "name" );
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateFilterGroups(&$reader)
    {
        $data = $reader->getSheetByName('FilterGroups');
        if ($data==null) {
            return true;
        }
        if (!$this->existFilter()) {
            throw new Exception($this->language->get('error_filter_not_supported'));
        }
        $expected_heading = array( "filter_group_id", "sort_order", "name" );
        $expected_multilingual = array( "name" );
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateFilters(&$reader)
    {
        $data = $reader->getSheetByName('Filters');
        if ($data==null) {
            return true;
        }
        if (!$this->existFilter()) {
            throw new Exception($this->language->get('error_filter_not_supported'));
        }
        $expected_heading = array( "filter_id", "filter_group_id", "sort_order", "name" );
        $expected_multilingual = array( "name" );
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductIdColumns(&$reader)
    {
        $data = $reader->getSheetByName('Products');
        if ($data==null) {
            return true;
        }
        $ok = true;
        
        // only unique numeric product_ids can be used in worksheet 'Products'
        $has_missing_product_ids = false;
        $product_ids = array();
        $k = $data->getHighestRow();
        for ($i=1; $i<$k; $i+=1) {
            $product_id = trim($this->getCell($data, $i, 1));
            if ($product_id=="") {
                if (!$has_missing_product_ids) {
                    $msg = str_replace('%1', 'Products', $this->language->get('error_missing_product_id'));
                    $this->log->write($msg);
                    $has_missing_product_ids = true;
                }
                $ok = false;
                continue;
            }
            if (!ctype_digit($product_id)) {
                $msg = str_replace('%2', $product_id, str_replace('%1', 'Products', $this->language->get('error_invalid_product_id')));
                $this->log->write($msg);
                $ok = false;
                continue;
            }
            if (in_array($product_id, $product_ids)) {
                $msg = str_replace('%2', $product_id, str_replace('%1', 'Products', $this->language->get('error_duplicate_product_id')));
                $this->log->write($msg);
                $ok = false;
                continue;
            }
            $product_ids[] = $product_id;
        }
        
        // make sure product_ids are numeric entries and are also mentioned in worksheet 'Products'
        $worksheets = array( 'AdditionalImages', 'Specials', 'Discounts', 'Rewards', 'ProductOptions', 'ProductOptionValues', 'ProductAttributes' );
        foreach ($worksheets as $worksheet) {
            $data = $reader->getSheetByName($worksheet);
            if ($data==null) {
                continue;
            }
            $has_missing_product_ids = false;
            $unlisted_product_ids = array();
            $k = $data->getHighestRow();
            for ($i=1; $i<$k; $i+=1) {
                $product_id = trim($this->getCell($data, $i, 1));
                if ($product_id=="") {
                    if (!$has_missing_product_ids) {
                        $msg = str_replace('%1', $worksheet, $this->language->get('error_missing_product_id'));
                        $this->log->write($msg);
                        $has_missing_product_ids = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!ctype_digit($product_id)) {
                    $msg = str_replace('%2', $product_id, str_replace('%1', $worksheet, $this->language->get('error_invalid_product_id')));
                    $this->log->write($msg);
                    $ok = false;
                    continue;
                }
                if (!in_array($product_id, $product_ids)) {
                    if (!in_array($product_id, $unlisted_product_ids)) {
                        $unlisted_product_ids[] = $product_id;
                        $msg = str_replace('%2', $product_id, str_replace('%1', $worksheet, $this->language->get('error_unlisted_product_id')));
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                }
            }
        }
        
        return $ok;
    }

    protected function validateCustomerGroupColumns(&$reader)
    {
        // all customer_groups mentioned in the worksheets must be defined
        $worksheets = array( 'Specials', 'Discounts', 'Rewards' );
        $ok = true;
        $customer_groups = array();
        $customer_group_ids = $this->getCustomerGroupIds();
        foreach ($worksheets as $worksheet) {
            $data = $reader->getSheetByName($worksheet);
            if ($data==null) {
                continue;
            }
            $has_missing_customer_groups = false;
            $k = $data->getHighestRow();
            for ($i=1; $i<$k; $i+=1) {
                $customer_group = trim($this->getCell($data, $i, 2));
                if ($customer_group=="") {
                    if (!$has_missing_customer_groups) {
                        $msg = $this->language->get('error_missing_customer_group');
                        $msg = str_replace('%1', $worksheet, $msg);
                        $this->log->write($msg);
                        $has_missing_customer_groups = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!in_array($customer_group, $customer_groups)) {
                    if (!isset($customer_group_ids[$customer_group])) {
                        $msg = $this->language->get('error_invalid_customer_group');
                        $msg = str_replace('%1', $worksheet, str_replace('%2', $customer_group, $msg));
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                    $customer_groups[] = $customer_group;
                }
            }
        }
        return $ok;
    }

    protected function validateOptionColumns(&$reader)
    {
        // get all existing options and option values
        $ok = true;
        $export_import_settings_use_option_id = $this->config->get('export_import_settings_use_option_id');
        $export_import_settings_use_option_value_id = $this->config->get('export_import_settings_use_option_value_id');
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT od.option_id, od.name AS option_name, ovd.option_value_id, ovd.name AS option_value_name ";
        $sql .= "FROM `".DB_PREFIX."option_description` od ";
        $sql .= "LEFT JOIN `".DB_PREFIX."option_value_description` ovd ON ovd.option_id=od.option_id AND ovd.language_id='".(int)$language_id."' ";
        $sql .= "WHERE od.language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $options = array();
        foreach ($query->rows as $row) {
            if ($export_import_settings_use_option_id) {
                $option_id = $row['option_id'];
                if (!isset($options[$option_id])) {
                    $options[$option_id] = array();
                }
                if ($export_import_settings_use_option_value_id) {
                    $option_value_id = $row['option_value_id'];
                    if (!is_null($option_value_id)) {
                        $options[$option_id][$option_value_id] = true;
                    }
                } else {
                    $option_value_name = htmlspecialchars_decode($row['option_value_name']);
                    if (!is_null($option_value_name)) {
                        $options[$option_id][$option_value_name] = true;
                    }
                }
            } else {
                $option_name = htmlspecialchars_decode($row['option_name']);
                if (!isset($options[$option_name])) {
                    $options[$option_name] = array();
                }
                if ($export_import_settings_use_option_value_id) {
                    $option_value_id = $row['option_value_id'];
                    if (!is_null($option_value_id)) {
                        $options[$option_name][$option_value_id] = true;
                    }
                } else {
                    $option_value_name = htmlspecialchars_decode($row['option_value_name']);
                    if (!is_null($option_value_name)) {
                        $options[$option_name][$option_value_name] = true;
                    }
                }
            }
        }
        
        // only existing options can be used in 'ProductOptions' worksheet
        $product_options = array();
        $data = $reader->getSheetByName('ProductOptions');
        if ($data==null) {
            return $ok;
        }
        $has_missing_options = false;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=1; $i<$k; $i+=1) {
            $product_id = trim($this->getCell($data, $i, 1));
            if ($product_id=="") {
                continue;
            }
            if ($export_import_settings_use_option_id) {
                $option_id = trim($this->getCell($data, $i, 2));
                if ($option_id=="") {
                    if (!$has_missing_options) {
                        $msg = str_replace('%1', 'ProductOptions', $this->language->get('error_missing_option_id'));
                        $this->log->write($msg);
                        $has_missing_options = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!isset($options[$option_id])) {
                    $msg = $this->language->get('error_invalid_option_id');
                    $msg = str_replace('%1', 'ProductOptions', $msg);
                    $msg = str_replace('%2', $option_id, $msg);
                    $this->log->write($msg);
                    $ok = false;
                    continue;
                }
                $product_options[$product_id][$option_id] = true;
            } else {
                $option_name = trim($this->getCell($data, $i, 2));
                if ($option_name=="") {
                    if (!$has_missing_options) {
                        $msg = str_replace('%1', 'ProductOptions', $this->language->get('error_missing_option_name'));
                        $this->log->write($msg);
                        $has_missing_options = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!isset($options[$option_name])) {
                    $msg = $this->language->get('error_invalid_option_name');
                    $msg = str_replace('%1', 'ProductOptions', $msg);
                    $msg = str_replace('%2', $option_name, $msg);
                    $this->log->write($msg);
                    $ok= false;
                    continue;
                }
                $product_options[$product_id][$option_name] = true;
            }
        }
        
        // only existing options and option values can be used in 'ProductOptionValues' worksheet
        $data = $reader->getSheetByName('ProductOptionValues');
        if ($data==null) {
            return $ok;
        }
        $has_missing_options = false;
        $has_missing_option_values = false;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=1; $i<$k; $i+=1) {
            $product_id = trim($this->getCell($data, $i, 1));
            if ($product_id=="") {
                continue;
            }
            if ($export_import_settings_use_option_id) {
                $option_id = trim($this->getCell($data, $i, 2));
                if ($option_id=="") {
                    if (!$has_missing_options) {
                        $msg = str_replace('%1', 'ProductOptionValues', $this->language->get('error_missing_option_id'));
                        $this->log->write($msg);
                        $has_missing_options = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!isset($options[$option_id])) {
                    $msg = $this->language->get('error_invalid_option_id');
                    $msg = str_replace('%1', 'ProductOptionValues', $msg);
                    $msg = str_replace('%2', $option_id, $msg);
                    $this->log->write($msg);
                    $ok = false;
                    continue;
                }
                if (!isset($product_options[$product_id][$option_id])) {
                    $msg = $this->language->get('error_invalid_product_id_option_id');
                    $msg = str_replace('%1', 'ProductOptionValues', $msg);
                    $msg = str_replace('%2', $product_id, $msg);
                    $msg = str_replace('%3', $option_id, $msg);
                    $msg = str_replace('%4', 'ProductOptions', $msg);
                    $this->log->write($msg);
                    $ok = false;
                    continue;
                }
                if ($export_import_settings_use_option_value_id) {
                    $option_value_id = trim($this->getCell($data, $i, 3));
                    if ($option_value_id=="") {
                        if (!$has_missing_option_values) {
                            $msg = str_replace('%1', 'ProductOptionValues', $this->language->get('error_missing_option_value_id'));
                            $this->log->write($msg);
                            $has_missing_option_values = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($options[$option_id][$option_value_id])) {
                        $msg = $this->language->get('error_invalid_option_id_option_value_id');
                        $msg = str_replace('%1', 'ProductOptionValues', $msg);
                        $msg = str_replace('%2', $option_id, $msg);
                        $msg = str_replace('%3', $option_value_id, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                } else {
                    $option_value_name = trim($this->getCell($data, $i, 3));
                    if ($option_value_name=="") {
                        if (!$has_missing_option_values) {
                            $msg = str_replace('%1', 'ProductOptionValues', $this->language->get('error_missing_option_value_name'));
                            $this->log->write($msg);
                            $has_missing_option_values = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($options[$option_id][$option_value_name])) {
                        $msg = $this->language->get('error_invalid_option_id_option_value_name');
                        $msg = str_replace('%1', 'ProductOptionValues', $msg);
                        $msg = str_replace('%2', $option_id, $msg);
                        $msg = str_replace('%3', $option_value_name, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                }
            } else {
                $option_name = trim($this->getCell($data, $i, 2));
                if ($option_name=="") {
                    if (!$has_missing_options) {
                        $msg = str_replace('%1', 'ProductOptionValues', $this->language->get('error_missing_option_name'));
                        $this->log->write($msg);
                        $has_missing_options = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!isset($options[$option_name])) {
                    $msg = $this->language->get('error_invalid_option_name');
                    $msg = str_replace('%1', 'ProductOptionValues', $msg);
                    $msg = str_replace('%2', $option_name, $msg);
                    $this->log->write($msg);
                    $ok= false;
                    continue;
                }
                if (!isset($product_options[$product_id][$option_name])) {
                    $msg = $this->language->get('error_invalid_product_id_option_name');
                    $msg = str_replace('%1', 'ProductOptionValues', $msg);
                    $msg = str_replace('%2', $product_id, $msg);
                    $msg = str_replace('%3', $option_name, $msg);
                    $msg = str_replace('%4', 'ProductOptions', $msg);
                    $this->log->write($msg);
                    $ok = false;
                    continue;
                }
                if ($export_import_settings_use_option_value_id) {
                    $option_value_id = trim($this->getCell($data, $i, 3));
                    if ($option_value_id=="") {
                        if (!$has_missing_option_values) {
                            $msg = str_replace('%1', 'ProductOptionValues', $this->language->get('error_missing_option_value_id'));
                            $this->log->write($msg);
                            $has_missing_option_values = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($options[$option_name][$option_value_id])) {
                        $msg = $this->language->get('error_invalid_option_name_option_value_id');
                        $msg = str_replace('%1', 'ProductOptionValues', $msg);
                        $msg = str_replace('%2', $option_name, $msg);
                        $msg = str_replace('%3', $option_value_id, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                } else {
                    $option_value_name = trim($this->getCell($data, $i, 3));
                    if ($option_value_name=="") {
                        if (!$has_missing_option_values) {
                            $msg = str_replace('%1', 'ProductOptionValues', $this->language->get('error_missing_option_value_name'));
                            $this->log->write($msg);
                            $has_missing_option_values = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($options[$option_name][$option_value_name])) {
                        $msg = $this->language->get('error_invalid_option_name_option_value_name');
                        $msg = str_replace('%1', 'ProductOptionValues', $msg);
                        $msg = str_replace('%2', $option_name, $msg);
                        $msg = str_replace('%3', $option_value_name, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                }
            }
        }
        
        return $ok;
    }

    protected function validateAttributeColumns(&$reader)
    {
        // get all existing attribute_groups and attributes
        $ok = true;
        $export_import_settings_use_attribute_group_id = $this->config->get('export_import_settings_use_attribute_group_id');
        $export_import_settings_use_attribute_id = $this->config->get('export_import_settings_use_attribute_id');
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT agd.attribute_group_id, agd.name AS attribute_group_name, ad.attribute_id, ad.name AS attribute_name ";
        $sql .= "FROM `".DB_PREFIX."attribute_group_description` agd ";
        $sql .= "LEFT JOIN `".DB_PREFIX."attribute` a ON a.attribute_group_id=agd.attribute_group_id ";
        $sql .= "LEFT JOIN `".DB_PREFIX."attribute_description` ad ON ad.attribute_id=a.attribute_id AND ad.language_id='".(int)$language_id."' ";
        $sql .= "WHERE agd.language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $attribute_groups = array();
        foreach ($query->rows as $row) {
            if ($export_import_settings_use_attribute_group_id) {
                $attribute_group_id = $row['attribute_group_id'];
                if (!isset($attribute_groups[$attribute_group_id])) {
                    $attribute_groups[$attribute_group_id] = array();
                }
                if ($export_import_settings_use_attribute_id) {
                    $attribute_id = $row['attribute_id'];
                    if (!is_null($attribute_id)) {
                        $attribute_groups[$attribute_group_id][$attribute_id] = true;
                    }
                } else {
                    $attribute_name = htmlspecialchars_decode($row['attribute_name']);
                    if (!is_null($attribute_name)) {
                        $attribute_groups[$attribute_group_id][$attribute_name] = true;
                    }
                }
            } else {
                $attribute_group_name = htmlspecialchars_decode($row['attribute_group_name']);
                if (!isset($attribute_groups[$attribute_group_name])) {
                    $attribute_groups[$attribute_group_name] = array();
                }
                if ($export_import_settings_use_attribute_id) {
                    $attribute_id = $row['attribute_id'];
                    if (!is_null($attribute_id)) {
                        $attribute_groups[$attribute_group_name][$attribute_id] = true;
                    }
                } else {
                    $attribute_name = htmlspecialchars_decode($row['attribute_name']);
                    if (!is_null($attribute_name)) {
                        $attribute_groups[$attribute_group_name][$attribute_name] = true;
                    }
                }
            }
        }
        
        // only existing attribute_groups and attributes can be used in 'ProductAttributes' worksheet
        $data = $reader->getSheetByName('ProductAttributes');
        if ($data==null) {
            return $ok;
        }
        $has_missing_attribute_groups = false;
        $has_missing_attributes = false;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i=1; $i<$k; $i+=1) {
            $product_id = trim($this->getCell($data, $i, 1));
            if ($product_id=="") {
                continue;
            }
            if ($export_import_settings_use_attribute_group_id) {
                $attribute_group_id = trim($this->getCell($data, $i, 2));
                if ($attribute_group_id=="") {
                    if (!$has_missing_attribute_groups) {
                        $msg = str_replace('%1', 'ProductAttributes', $this->language->get('error_missing_attribute_group_id'));
                        $this->log->write($msg);
                        $has_missing_attribute_groups = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!isset($attribute_groups[$attribute_group_id])) {
                    $msg = $this->language->get('error_invalid_attribute_group_id');
                    $msg = str_replace('%1', 'ProductAttributes', $msg);
                    $msg = str_replace('%2', $attribute_group_id, $msg);
                    $this->log->write($msg);
                    $ok = false;
                    continue;
                }
                if ($export_import_settings_use_attribute_id) {
                    $attribute_id = trim($this->getCell($data, $i, 3));
                    if ($attribute_id=="") {
                        if (!$has_missing_attributes) {
                            $msg = str_replace('%1', 'ProductAttributes', $this->language->get('error_missing_attribute_id'));
                            $this->log->write($msg);
                            $has_missing_attributes = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($attribute_groups[$attribute_group_id][$attribute_id])) {
                        $msg = $this->language->get('error_invalid_attribute_group_id_attribute_id');
                        $msg = str_replace('%1', 'ProductAttributes', $msg);
                        $msg = str_replace('%2', $attribute_group_id, $msg);
                        $msg = str_replace('%3', $attribute_id, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                } else {
                    $attribute_name = trim($this->getCell($data, $i, 3));
                    if ($attribute_name=="") {
                        if (!$has_missing_attributes) {
                            $msg = str_replace('%1', 'ProductAttributes', $this->language->get('error_missing_attribute_name'));
                            $this->log->write($msg);
                            $has_missing_attributes = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($attribute_groups[$attribute_group_id][$attribute_name])) {
                        $msg = $this->language->get('error_invalid_attribute_group_id_attribute_name');
                        $msg = str_replace('%1', 'ProductAttributes', $msg);
                        $msg = str_replace('%2', $attribute_group_id, $msg);
                        $msg = str_replace('%3', $attribute_name, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                }
            } else {
                $attribute_group_name = trim($this->getCell($data, $i, 2));
                if ($attribute_group_name=="") {
                    if (!$has_missing_attribute_groups) {
                        $msg = str_replace('%1', 'ProductAttributes', $this->language->get('error_missing_attribute_group_name'));
                        $this->log->write($msg);
                        $has_missing_attribute_groups = true;
                    }
                    $ok = false;
                    continue;
                }
                if (!isset($attribute_groups[$attribute_group_name])) {
                    $msg = $this->language->get('error_invalid_attribute_group_name');
                    $msg = str_replace('%1', 'ProductAttributes', $msg);
                    $msg = str_replace('%2', $attribute_group_name, $msg);
                    $this->log->write($msg);
                    $ok= false;
                    continue;
                }
                if ($export_import_settings_use_attribute_id) {
                    $attribute_id = trim($this->getCell($data, $i, 3));
                    if ($attribute_id=="") {
                        if (!$has_missing_attributes) {
                            $msg = str_replace('%1', 'ProductAttributes', $this->language->get('error_missing_attribute_id'));
                            $this->log->write($msg);
                            $has_missing_attributes = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($attribute_groups[$attribute_group_name][$attribute_id])) {
                        $msg = $this->language->get('error_invalid_attribute_group_name_attribute_id');
                        $msg = str_replace('%1', 'ProductAttributes', $msg);
                        $msg = str_replace('%2', $attribute_group_name, $msg);
                        $msg = str_replace('%3', $attribute_id, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                } else {
                    $attribute_name = trim($this->getCell($data, $i, 3));
                    if ($attribute_name=="") {
                        if (!$has_missing_attributes) {
                            $msg = str_replace('%1', 'ProductAttributes', $this->language->get('error_missing_attribute_name'));
                            $this->log->write($msg);
                            $has_missing_attributes = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($attribute_groups[$attribute_group_name][$attribute_name])) {
                        $msg = $this->language->get('error_invalid_attribute_group_name_attribute_name');
                        $msg = str_replace('%1', 'ProductAttributes', $msg);
                        $msg = str_replace('%2', $attribute_group_name, $msg);
                        $msg = str_replace('%3', $attribute_name, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                }
            }
        }
        
        return $ok;
    }

    protected function validateFilterColumns(&$reader)
    {
        // get all existing filter_groups and filters
        $ok = true;
        $export_import_settings_use_filter_group_id = $this->config->get('export_import_settings_use_filter_group_id');
        $export_import_settings_use_filter_id = $this->config->get('export_import_settings_use_filter_id');
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT fgd.filter_group_id, fgd.name AS filter_group_name, fd.filter_id, fd.name AS filter_name ";
        $sql .= "FROM `".DB_PREFIX."filter_group_description` fgd ";
        $sql .= "LEFT JOIN `".DB_PREFIX."filter` f ON f.filter_group_id=fgd.filter_group_id ";
        $sql .= "LEFT JOIN `".DB_PREFIX."filter_description` fd ON fd.filter_id=f.filter_id AND fd.language_id='".(int)$language_id."' ";
        $sql .= "WHERE fgd.language_id='".(int)$language_id."'";
        $query = $this->db->query($sql);
        $filter_groups = array();
        foreach ($query->rows as $row) {
            if ($export_import_settings_use_filter_group_id) {
                $filter_group_id = $row['filter_group_id'];
                if (!isset($filter_groups[$filter_group_id])) {
                    $filter_groups[$filter_group_id] = array();
                }
                if ($export_import_settings_use_filter_id) {
                    $filter_id = $row['filter_id'];
                    if (!is_null($filter_id)) {
                        $filter_groups[$filter_group_id][$filter_id] = true;
                    }
                } else {
                    $filter_name = htmlspecialchars_decode($row['filter_name']);
                    if (!is_null($filter_name)) {
                        $filter_groups[$filter_group_id][$filter_name] = true;
                    }
                }
            } else {
                $filter_group_name = htmlspecialchars_decode($row['filter_group_name']);
                if (!isset($filter_groups[$filter_group_name])) {
                    $filter_groups[$filter_group_name] = array();
                }
                if ($export_import_settings_use_filter_id) {
                    $filter_id = $row['filter_id'];
                    if (!is_null($filter_id)) {
                        $filter_groups[$filter_group_name][$filter_id] = true;
                    }
                } else {
                    $filter_name = htmlspecialchars_decode($row['filter_name']);
                    if (!is_null($filter_name)) {
                        $filter_groups[$filter_group_name][$filter_name] = true;
                    }
                }
            }
        }

        // only existing filter_groups and filters can be used in the 'ProductFilters' and 'CategoryFilters' worksheets
        $worksheet_names = array('ProductFilters','CategoryFilters');
        foreach ($worksheet_names as $worksheet_name) {
            $data = $reader->getSheetByName('ProductFilters');
            if ($data==null) {
                return $ok;
            }
            $has_missing_filter_groups = false;
            $has_missing_filters = false;
            $i = 0;
            $k = $data->getHighestRow();
            for ($i=1; $i<$k; $i+=1) {
                $id = trim($this->getCell($data, $i, 1));
                if ($id=="") {
                    continue;
                }
                if ($export_import_settings_use_filter_group_id) {
                    $filter_group_id = trim($this->getCell($data, $i, 2));
                    if ($filter_group_id=="") {
                        if (!$has_missing_filter_groups) {
                            $msg = str_replace('%1', $worksheet_name, $this->language->get('error_missing_filter_group_id'));
                            $this->log->write($msg);
                            $has_missing_filter_groups = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($filter_groups[$filter_group_id])) {
                        $msg = $this->language->get('error_invalid_filter_group_id');
                        $msg = str_replace('%1', $worksheet_name, $msg);
                        $msg = str_replace('%2', $filter_group_id, $msg);
                        $this->log->write($msg);
                        $ok = false;
                        continue;
                    }
                    if ($export_import_settings_use_filter_id) {
                        $filter_id = trim($this->getCell($data, $i, 3));
                        if ($filter_id=="") {
                            if (!$has_missing_filters) {
                                $msg = str_replace('%1', $worksheet_name, $this->language->get('error_missing_filter_id'));
                                $this->log->write($msg);
                                $has_missing_filters = true;
                            }
                            $ok = false;
                            continue;
                        }
                        if (!isset($filter_groups[$filter_group_id][$filter_id])) {
                            $msg = $this->language->get('error_invalid_filter_group_id_filter_id');
                            $msg = str_replace('%1', $worksheet_name, $msg);
                            $msg = str_replace('%2', $filter_group_id, $msg);
                            $msg = str_replace('%3', $filter_id, $msg);
                            $this->log->write($msg);
                            $ok = false;
                            continue;
                        }
                    } else {
                        $filter_name = trim($this->getCell($data, $i, 3));
                        if ($filter_name=="") {
                            if (!$has_missing_filters) {
                                $msg = str_replace('%1', $worksheet_name, $this->language->get('error_missing_filter_name'));
                                $this->log->write($msg);
                                $has_missing_filters = true;
                            }
                            $ok = false;
                            continue;
                        }
                        if (!isset($filter_groups[$filter_group_id][$filter_name])) {
                            $msg = $this->language->get('error_invalid_filter_group_id_filter_name');
                            $msg = str_replace('%1', $worksheet_name, $msg);
                            $msg = str_replace('%2', $filter_group_id, $msg);
                            $msg = str_replace('%3', $filter_name, $msg);
                            $this->log->write($msg);
                            $ok = false;
                            continue;
                        }
                    }
                } else {
                    $filter_group_name = trim($this->getCell($data, $i, 2));
                    if ($filter_group_name=="") {
                        if (!$has_missing_filter_groups) {
                            $msg = str_replace('%1', $worksheet_name, $this->language->get('error_missing_filter_group_name'));
                            $this->log->write($msg);
                            $has_missing_filter_groups = true;
                        }
                        $ok = false;
                        continue;
                    }
                    if (!isset($filter_groups[$filter_group_name])) {
                        $msg = $this->language->get('error_invalid_filter_group_name');
                        $msg = str_replace('%1', $worksheet_name, $msg);
                        $msg = str_replace('%2', $filter_group_name, $msg);
                        $this->log->write($msg);
                        $ok= false;
                        continue;
                    }
                    if ($export_import_settings_use_filter_id) {
                        $filter_id = trim($this->getCell($data, $i, 3));
                        if ($filter_id=="") {
                            if (!$has_missing_filters) {
                                $msg = str_replace('%1', $worksheet_name, $this->language->get('error_missing_filter_id'));
                                $this->log->write($msg);
                                $has_missing_filters = true;
                            }
                            $ok = false;
                            continue;
                        }
                        if (!isset($filter_groups[$filter_group_name][$filter_id])) {
                            $msg = $this->language->get('error_invalid_filter_group_name_filter_id');
                            $msg = str_replace('%1', $worksheet_name, $msg);
                            $msg = str_replace('%2', $filter_group_name, $msg);
                            $msg = str_replace('%3', $filter_id, $msg);
                            $this->log->write($msg);
                            $ok = false;
                            continue;
                        }
                    } else {
                        $filter_name = trim($this->getCell($data, $i, 3));
                        if ($filter_name=="") {
                            if (!$has_missing_filters) {
                                $msg = str_replace('%1', $worksheet_name, $this->language->get('error_missing_filter_name'));
                                $this->log->write($msg);
                                $has_missing_filters = true;
                            }
                            $ok = false;
                            continue;
                        }
                        if (!isset($filter_groups[$filter_group_name][$filter_name])) {
                            $msg = $this->language->get('error_invalid_filter_group_name_filter_name');
                            $msg = str_replace('%1', $worksheet_name, $msg);
                            $msg = str_replace('%2', $filter_group_name, $msg);
                            $msg = str_replace('%3', $filter_name, $msg);
                            $this->log->write($msg);
                            $ok = false;
                            continue;
                        }
                    }
                }
            }
        }

        return $ok;
    }
    
    protected function validateUpload(&$reader)
    {
        $ok = true;

        // worksheets must have correct heading rows
        if (!$this->validateCategories($reader)) {
            $this->log->write($this->language->get('error_categories_header'));
            $ok = false;
        }
        if (!$this->validateCategoryFilters($reader)) {
            $this->log->write($this->language->get('error_category_filters_header'));
            $ok = false;
        }
        if (!$this->validateProducts($reader)) {
            $this->log->write($this->language->get('error_products_header'));
            $ok = false;
        }
        if (!$this->validateAdditionalImages($reader)) {
            $this->log->write($this->language->get('error_additional_images_header'));
            $ok = false;
        }
        if (!$this->validateSpecials($reader)) {
            $this->log->write($this->language->get('error_specials_header'));
            $ok = false;
        }
        if (!$this->validateDiscounts($reader)) {
            $this->log->write($this->language->get('error_discounts_header'));
            $ok = false;
        }
        if (!$this->validateRewards($reader)) {
            $this->log->write($this->language->get('error_rewards_header'));
            $ok = false;
        }
        if (!$this->validateProductOptions($reader)) {
            $this->log->write($this->language->get('error_product_options_header'));
            $ok = false;
        }
        if (!$this->validateProductOptionValues($reader)) {
            $this->log->write($this->language->get('error_product_option_values_header'));
            $ok = false;
        }
        if (!$this->validateProductAttributes($reader)) {
            $this->log->write($this->language->get('error_product_attributes_header'));
            $ok = false;
        }
        if (!$this->validateProductFilters($reader)) {
            $this->log->write($this->language->get('error_product_filters_header'));
            $ok = false;
        }
        if (!$this->validateOptions($reader)) {
            $this->log->write($this->language->get('error_options_header'));
            $ok = false;
        }
        if (!$this->validateOptionValues($reader)) {
            $this->log->write($this->language->get('error_option_values_header'));
            $ok = false;
        }
        if (!$this->validateAttributeGroups($reader)) {
            $this->log->write($this->language->get('error_attribute_groups_header'));
            $ok = false;
        }
        if (!$this->validateAttributes($reader)) {
            $this->log->write($this->language->get('error_attributes_header'));
            $ok = false;
        }
        if (!$this->validateFilterGroups($reader)) {
            $this->log->write($this->language->get('error_filter_groups_header'));
            $ok = false;
        }
        if (!$this->validateFilters($reader)) {
            $this->log->write($this->language->get('error_filters_header'));
            $ok = false;
        }

        // certain worksheets rely on the existence of other worksheets
        $names = $reader->getSheetNames();
        $exist_categories = false;
        $exist_category_filters = false;
        $exist_product_options = false;
        $exist_product_option_values = false;
        $exist_products = false;
        $exist_additional_images = false;
        $exist_specials = false;
        $exist_discounts = false;
        $exist_rewards = false;
        $exist_product_attributes = false;
        $exist_product_filters = false;
        $exist_attribute_groups = false;
        $exist_filters = false;
        $exist_filter_groups = false;
        $exist_attributes = false;
        $exist_options = false;
        $exist_option_values = false;
        foreach ($names as $name) {
            if ($name=='Categories') {
                $exist_categories = true;
                continue;
            }
            if ($name=='CategoryFilters') {
                if (!$exist_categories) {
                    // Missing Categories worksheet, or Categories worksheet not listed before CategoryFilters
                    $this->log->write($this->language->get('error_category_filters'));
                    $ok = false;
                }
                $exist_category_filters = true;
                continue;
            }
            if ($name=='Products') {
                $exist_products = true;
                continue;
            }
            if ($name=='ProductOptions') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before ProductOptions
                    $this->log->write($this->language->get('error_product_options'));
                    $ok = false;
                }
                $exist_product_options = true;
                continue;
            }
            if ($name=='ProductOptionValues') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before ProductOptionValues
                    $this->log->write($this->language->get('error_product_options'));
                    $ok = false;
                }
                if (!$exist_product_options) {
                    // Missing ProductOptions worksheet, or ProductOptions worksheet not listed before ProductOptionValues
                    $this->log->write($this->language->get('error_product_option_values_2'));
                    $ok = false;
                }
                $exist_product_option_values = true;
                continue;
            }
            if ($name=='AdditionalImages') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before AdditionalImages
                    $this->log->write($this->language->get('error_additional_images'));
                    $ok = false;
                }
                $exist_additional_images = true;
                continue;
            }
            if ($name=='Specials') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before Specials
                    $this->log->write($this->language->get('error_specials'));
                    $ok = false;
                }
                $exist_specials = true;
                continue;
            }
            if ($name=='Discounts') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before Discounts
                    $this->log->write($this->language->get('error_discounts'));
                    $ok = false;
                }
                $exist_discounts = true;
                continue;
            }
            if ($name=='Rewards') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before Rewards
                    $this->log->write($this->language->get('error_rewards'));
                    $ok = false;
                }
                $exist_rewards = true;
                continue;
            }
            if ($name=='ProductAttributes') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before ProductAttributes
                    $this->log->write($this->language->get('error_product_attributes'));
                    $ok = false;
                }
                $exist_product_attributes = true;
                continue;
            }
            if ($name=='AttributeGroups') {
                $exist_attribute_groups = true;
                continue;
            }
            if ($name=='Attributes') {
                if (!$exist_attribute_groups) {
                    // Missing AttributeGroups worksheet, or AttributeGroups worksheet not listed before Attributes
                    $this->log->write($this->language->get('error_attributes'));
                    $ok = false;
                }
                $exist_attributes = true;
                continue;
            }
            if ($name=='ProductFilters') {
                if (!$exist_products) {
                    // Missing Products worksheet, or Products worksheet not listed before ProductFilters
                    $this->log->write($this->language->get('error_product_filters'));
                    $ok = false;
                }
                $exist_product_filters = true;
                continue;
            }
            if ($name=='FilterGroups') {
                $exist_filter_groups = true;
                continue;
            }
            if ($name=='Filters') {
                if (!$exist_filter_groups) {
                    // Missing FilterGroups worksheet, or FilterGroups worksheet not listed before Filters
                    $this->log->write($this->language->get('error_filters'));
                    $ok = false;
                }
                $exist_filters = true;
                continue;
            }
            if ($name=='Options') {
                $exist_options = true;
                continue;
            }
            if ($name=='OptionValues') {
                if (!$exist_options) {
                    // Missing Options worksheet, or Options worksheet not listed before OptionValues
                    $this->log->write($this->language->get('error_option_values'));
                    $ok = false;
                }
                $exist_option_values = true;
                continue;
            }
        }
        if ($exist_product_options) {
            if (!$exist_product_option_values) {
                // ProductOptionValues worksheet also expected after a ProductOptions worksheet
                $this->log->write($this->language->get('error_product_option_values_3'));
                $ok = false;
            }
        }
        if ($exist_attribute_groups) {
            if (!$exist_attributes) {
                // Attributes worksheet also expected after an AttributeGroups worksheet
                $this->log->write($this->language->get('error_attributes_2'));
                $ok = false;
            }
        }
        if ($exist_filter_groups) {
            if (!$exist_filters) {
                // Filters worksheet also expected after an FilterGroups worksheet
                $this->log->write($this->language->get('error_filters_2'));
                $ok = false;
            }
        }
        if ($exist_options) {
            if (!$exist_option_values) {
                // OptionValues worksheet also expected after an Options worksheet
                $this->log->write($this->language->get('error_option_values_2'));
                $ok = false;
            }
        }

        if (!$ok) {
            return false;
        }
        
        if (!$this->validateProductIdColumns($reader)) {
            return false;
        }
        
        if (!$this->validateCustomerGroupColumns($reader)) {
            $ok = false;
        }
        
        if (!$this->validateOptionColumns($reader)) {
            $ok = false;
        }
        
        if (!$this->validateAttributeColumns($reader)) {
            $ok = false;
        }
        if ($this->existFilter()) {
            if (!$this->validateFilterColumns($reader)) {
                $ok = false;
            }
        }
        
        return $ok;
    }

    protected function clearCache()
    {
        $this->cache->delete('*');
    }

    public function upload($filename, $incremental = false)
    {
        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');

        try {
            $this->session->data['export_import_nochange'] = 1;

            // we use the PHPExcel package from http://phpexcel.codeplex.com/
            $cwd = getcwd();
            chdir(DIR_SYSTEM.'PHPExcel');
            require_once('Classes/PHPExcel.php');
            chdir($cwd);
            
            // Memory Optimization
            if ($this->config->get('export_import_settings_use_import_cache')) {
                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                $cacheSettings = array( ' memoryCacheSize '  => '16MB'  );
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            }

            // parse uploaded spreadsheet file
            $inputFileType = PHPExcel_IOFactory::identify($filename);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $reader = $objReader->load($filename);

            // read the various worksheets and load them to the database
            if (!$this->validateUpload($reader)) {
                return false;
            }
            $this->clearCache();
            $this->session->data['export_import_nochange'] = 0;
            $available_product_ids = array();
            $available_category_ids = array();
            $this->uploadCategories($reader, $incremental, $available_category_ids);
            $this->uploadCategoryFilters($reader, $incremental, $available_category_ids);
            $this->uploadProducts($reader, $incremental, $available_product_ids);
            $this->uploadAdditionalImages($reader, $incremental, $available_product_ids);
            $this->uploadSpecials($reader, $incremental, $available_product_ids);
            $this->uploadDiscounts($reader, $incremental, $available_product_ids);
            $this->uploadRewards($reader, $incremental, $available_product_ids);
            $this->uploadProductOptions($reader, $incremental, $available_product_ids);
            $this->uploadProductOptionValues($reader, $incremental, $available_product_ids);
            $this->uploadProductAttributes($reader, $incremental, $available_product_ids);
            $this->uploadProductFilters($reader, $incremental, $available_product_ids);
            $this->uploadOptions($reader, $incremental);
            $this->uploadOptionValues($reader, $incremental);
            $this->uploadAttributeGroups($reader, $incremental);
            $this->uploadAttributes($reader, $incremental);
            $this->uploadFilterGroups($reader, $incremental);
            $this->uploadFilters($reader, $incremental);
            return true;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = array( 'errstr'=>$errstr, 'errno'=>$errno, 'errfile'=>$errfile, 'errline'=>$errline );
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }
            return false;
        }
    }

    public function getStoreIdsForCategories()
    {
        $sql =  "SELECT category_id, store_id FROM `".DB_PREFIX."category_to_store` cs;";
        $store_ids = array();
        $result = $this->db->query($sql);
        foreach ($result->rows as $row) {
            $categoryId = $row['category_id'];
            $store_id = $row['store_id'];
            if (!isset($store_ids[$categoryId])) {
                $store_ids[$categoryId] = array();
            }
            if (!in_array($store_id, $store_ids[$categoryId])) {
                $store_ids[$categoryId][] = $store_id;
            }
        }
        return $store_ids;
    }

    public function getLayoutsForCategories()
    {
        $sql  = "SELECT cl.*, l.name FROM `".DB_PREFIX."category_to_layout` cl ";
        $sql .= "LEFT JOIN `".DB_PREFIX."layout` l ON cl.layout_id = l.layout_id ";
        $sql .= "ORDER BY cl.category_id, cl.store_id;";
        $result = $this->db->query($sql);
        $layouts = array();
        foreach ($result->rows as $row) {
            $categoryId = $row['category_id'];
            $store_id = $row['store_id'];
            $name = $row['name'];
            if (!isset($layouts[$categoryId])) {
                $layouts[$categoryId] = array();
            }
            $layouts[$categoryId][$store_id] = $name;
        }
        return $layouts;
    }

    protected function setColumnStyles(&$worksheet, &$styles, $min_row, $max_row)
    {
        if ($max_row < $min_row) {
            return;
        }
        foreach ($styles as $col => $style) {
            $from = PHPExcel_Cell::stringFromColumnIndex($col).$min_row;
            $to = PHPExcel_Cell::stringFromColumnIndex($col).$max_row;
            $range = $from.':'.$to;
            $worksheet->getStyle($range)->applyFromArray($style, false);
        }
    }

    protected function setCellRow($worksheet, $row/*1-based*/, $data, &$default_style = null, &$styles = null)
    {
        if (!empty($default_style)) {
            $worksheet->getStyle("$row:$row")->applyFromArray($default_style, false);
        }
        if (!empty($styles)) {
            foreach ($styles as $col => $style) {
                $worksheet->getStyleByColumnAndRow($col, $row)->applyFromArray($style, false);
            }
        }
        $worksheet->fromArray($data, null, 'A'.$row, true);
//      foreach ($data as $col=>$val) {
//          $worksheet->setCellValueExplicitByColumnAndRow( $col, $row-1, $val );
//      }
//      foreach ($data as $col=>$val) {
//          $worksheet->setCellValueByColumnAndRow( $col, $row, $val );
//      }
    }

    protected function setCell(&$worksheet, $row/*1-based*/, $col/*0-based*/, $val, &$style = null)
    {
        $worksheet->setCellValueByColumnAndRow($col, $row, $val);
        if (!empty($style)) {
            $worksheet->getStyleByColumnAndRow($col, $row)->applyFromArray($style, false);
        }
    }

    protected function getCategoryDescriptions(&$languages, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        // query the category_description table for each language
        $category_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT c.category_id, cd.* ";
            $sql .= "FROM `".DB_PREFIX."category` c ";
            $sql .= "LEFT JOIN `".DB_PREFIX."category_description` cd ON cd.category_id=c.category_id AND cd.language_id='".(int)$language_id."' ";
            if (isset($min_id) && isset($max_id)) {
                $sql .= "WHERE c.category_id BETWEEN $min_id AND $max_id ";
            }
            $sql .= "GROUP BY c.`category_id` ";
            $sql .= "ORDER BY c.`category_id` ASC ";
            if (isset($offset) && isset($rows)) {
                $sql .= "LIMIT $offset,$rows; ";
            } else {
                $sql .= "; ";
            }
            $query = $this->db->query($sql);
            $category_descriptions[$language_code] = $query->rows;
        }
        return $category_descriptions;
    }

    protected function getCategories(&$languages, $exist_meta_title, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        $sql  = "SELECT c.*, ua.keyword FROM `".DB_PREFIX."category` c ";
        $sql .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON ua.query=CONCAT('category_id=',c.category_id) ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE c.category_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "GROUP BY c.`category_id` ";
        $sql .= "ORDER BY c.`category_id` ASC ";
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= "; ";
        }
        $results = $this->db->query($sql);
        $category_descriptions = $this->getCategoryDescriptions($languages, $offset, $rows, $min_id, $max_id);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($category_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $category_descriptions[$language_code][$key]['name'];
                    $results->rows[$key]['description'][$language_code] = $category_descriptions[$language_code][$key]['description'];
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = $category_descriptions[$language_code][$key]['meta_title'];
                    }
                    $results->rows[$key]['meta_description'][$language_code] = $category_descriptions[$language_code][$key]['meta_description'];
                    $results->rows[$key]['meta_keyword'][$language_code] = $category_descriptions[$language_code][$key]['meta_keyword'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                    $results->rows[$key]['description'][$language_code] = '';
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = '';
                    }
                    $results->rows[$key]['meta_description'][$language_code] = '';
                    $results->rows[$key]['meta_keyword'][$language_code] = '';
                }
            }
        }
        return $results->rows;
    }

    protected function populateCategoriesWorksheet(&$worksheet, &$languages, &$box_format, &$text_format, $offset = null, $rows = null, &$min_id = null, &$max_id = null)
    {
        // Opencart versions from 2.0 onwards also have category_description.meta_title
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."category_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('category_id')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('parent_id')+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('top'), 5)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('columns')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('sort_order')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('image_name'), 12)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_added'), 19)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_modified'), 19)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('seo_keyword'), 16)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('description'), 32)+1);
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_title'), 20)+1);
            }
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_description'), 32)+1);
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_keywords'), 32)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('store_ids'), 16)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('layout'), 16)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('status'), 5)+1);
        
        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'category_id';
        $data[$j++] = 'parent_id';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        $data[$j++] = 'top';
        $data[$j++] = 'columns';
        $data[$j++] = 'sort_order';
        $styles[$j] = &$text_format;
        $data[$j++] = 'image_name';
        $styles[$j] = &$text_format;
        $data[$j++] = 'date_added';
        $styles[$j] = &$text_format;
        $data[$j++] = 'date_modified';
        $styles[$j] = &$text_format;
        $data[$j++] = 'seo_keyword';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'description('.$language['code'].')';
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $styles[$j] = &$text_format;
                $data[$j++] = 'meta_title('.$language['code'].')';
            }
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_description('.$language['code'].')';
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_keywords('.$language['code'].')';
        }
        $styles[$j] = &$text_format;
        $data[$j++] = 'store_ids';
        $styles[$j] = &$text_format;
        $data[$j++] = 'layout';
        $data[$j++] = 'status';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual categories data
        $i += 1;
        $j = 0;
        $store_ids = $this->getStoreIdsForCategories();
        $layouts = $this->getLayoutsForCategories();
        $categories = $this->getCategories($languages, $exist_meta_title, $offset, $rows, $min_id, $max_id);
        $len = count($categories);
        $min_id = $categories[0]['category_id'];
        $max_id = $categories[$len-1]['category_id'];
        foreach ($categories as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(26);
            $data = array();
            $data[$j++] = $row['category_id'];
            $data[$j++] = $row['parent_id'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $data[$j++] = ($row['top']==0) ? "false" : "true";
            $data[$j++] = $row['column'];
            $data[$j++] = $row['sort_order'];
            $data[$j++] = $row['image'];
            $data[$j++] = $row['date_added'];
            $data[$j++] = $row['date_modified'];
            $data[$j++] = $row['keyword'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            if ($exist_meta_title) {
                foreach ($languages as $language) {
                    $data[$j++] = html_entity_decode($row['meta_title'][$language['code']], ENT_QUOTES, 'UTF-8');
                }
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_keyword'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $store_id_list = '';
            $category_id = $row['category_id'];
            if (isset($store_ids[$category_id])) {
                foreach ($store_ids[$category_id] as $store_id) {
                    $store_id_list .= ($store_id_list=='') ? $store_id : ','.$store_id;
                }
            }
            $data[$j++] = $store_id_list;
            $layout_list = '';
            if (isset($layouts[$category_id])) {
                foreach ($layouts[$category_id] as $store_id => $name) {
                    $layout_list .= ($layout_list=='') ? $store_id.':'.$name : ','.$store_id.':'.$name;
                }
            }
            $data[$j++] = $layout_list;
            $data[$j++] = ($row['status']==0) ? 'false' : 'true';
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getFilterGroupNames($language_id)
    {
        $sql  = "SELECT filter_group_id, name ";
        $sql .= "FROM `".DB_PREFIX."filter_group_description` ";
        $sql .= "WHERE language_id='".(int)$language_id."' ";
        $sql .= "ORDER BY filter_group_id ASC";
        $query = $this->db->query($sql);
        $filter_group_names = array();
        foreach ($query->rows as $row) {
            $filter_group_id = $row['filter_group_id'];
            $name = $row['name'];
            $filter_group_names[$filter_group_id] = $name;
        }
        return $filter_group_names;
    }

    protected function getFilterNames($language_id)
    {
        $sql  = "SELECT filter_id, name ";
        $sql .= "FROM `".DB_PREFIX."filter_description` ";
        $sql .= "WHERE language_id='".(int)$language_id."' ";
        $sql .= "ORDER BY filter_id ASC";
        $query = $this->db->query($sql);
        $filter_names = array();
        foreach ($query->rows as $row) {
            $filter_id = $row['filter_id'];
            $filter_name = $row['name'];
            $filter_names[$filter_id] = $filter_name;
        }
        return $filter_names;
    }

    protected function getCategoryFilters($min_id, $max_id)
    {
        $sql  = "SELECT cf.category_id, fg.filter_group_id, cf.filter_id ";
        $sql .= "FROM `".DB_PREFIX."category_filter` cf ";
        $sql .= "INNER JOIN `".DB_PREFIX."filter` f ON f.filter_id=cf.filter_id ";
        $sql .= "INNER JOIN `".DB_PREFIX."filter_group` fg ON fg.filter_group_id=f.filter_group_id ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE category_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "ORDER BY cf.category_id ASC, fg.filter_group_id ASC, cf.filter_id ASC";
        $query = $this->db->query($sql);
        $category_filters = array();
        foreach ($query->rows as $row) {
            $category_filter = array();
            $category_filter['category_id'] = $row['category_id'];
            $category_filter['filter_group_id'] = $row['filter_group_id'];
            $category_filter['filter_id'] = $row['filter_id'];
            $category_filters[] = $category_filter;
        }
        return $category_filters;
    }

    protected function populateCategoryFiltersWorksheet(&$worksheet, &$languages, $default_language_id, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('category_id')+1);
        if ($this->config->get('export_import_settings_use_filter_group_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('filter_group_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('filter_group'), 30)+1);
        }
        if ($this->config->get('export_import_settings_use_filter_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('filter_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('filter'), 30)+1);
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('text')+4, 30)+1);
        }

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'category_id';
        if ($this->config->get('export_import_settings_use_filter_group_id')) {
            $data[$j++] = 'filter_group_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'filter_group';
        }
        if ($this->config->get('export_import_settings_use_filter_id')) {
            $data[$j++] = 'filter_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'filter';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual category filters data
        if (!$this->config->get('export_import_settings_use_filter_group_id')) {
            $filter_group_names = $this->getFilterGroupNames($default_language_id);
        }
        if (!$this->config->get('export_import_settings_use_filter_id')) {
            $filter_names = $this->getFilterNames($default_language_id);
        }
        $i += 1;
        $j = 0;
        $category_filters = $this->getCategoryFilters($min_id, $max_id);
        foreach ($category_filters as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['category_id'];
            if ($this->config->get('export_import_settings_use_filter_group_id')) {
                $data[$j++] = $row['filter_group_id'];
            } else {
                $data[$j++] = html_entity_decode($filter_group_names[$row['filter_group_id']], ENT_QUOTES, 'UTF-8');
            }
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $data[$j++] = $row['filter_id'];
            } else {
                $data[$j++] = html_entity_decode($filter_names[$row['filter_id']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }

    }
    
    protected function getStoreIdsForProducts()
    {
        $sql =  "SELECT product_id, store_id FROM `".DB_PREFIX."product_to_store` ps;";
        $store_ids = array();
        $result = $this->db->query($sql);
        foreach ($result->rows as $row) {
            $productId = $row['product_id'];
            $store_id = $row['store_id'];
            if (!isset($store_ids[$productId])) {
                $store_ids[$productId] = array();
            }
            if (!in_array($store_id, $store_ids[$productId])) {
                $store_ids[$productId][] = $store_id;
            }
        }
        return $store_ids;
    }

    protected function getLayoutsForProducts()
    {
        $sql  = "SELECT pl.*, l.name FROM `".DB_PREFIX."product_to_layout` pl ";
        $sql .= "LEFT JOIN `".DB_PREFIX."layout` l ON pl.layout_id = l.layout_id ";
        $sql .= "ORDER BY pl.product_id, pl.store_id;";
        $result = $this->db->query($sql);
        $layouts = array();
        foreach ($result->rows as $row) {
            $productId = $row['product_id'];
            $store_id = $row['store_id'];
            $name = $row['name'];
            if (!isset($layouts[$productId])) {
                $layouts[$productId] = array();
            }
            $layouts[$productId][$store_id] = $name;
        }
        return $layouts;
    }

    protected function getProductDescriptions(&$languages, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        // some older versions of OpenCart use the 'product_tag' table
        $exist_table_product_tag = false;
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."product_tag'");
        $exist_table_product_tag = ($query->num_rows > 0);

        // query the product_description table for each language
        $product_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT p.product_id, ".(($exist_table_product_tag) ? "GROUP_CONCAT(pt.tag SEPARATOR \",\") AS tag, " : "")."pd.* ";
            $sql .= "FROM `".DB_PREFIX."product` p ";
            $sql .= "LEFT JOIN `".DB_PREFIX."product_description` pd ON pd.product_id=p.product_id AND pd.language_id='".(int)$language_id."' ";
            if ($exist_table_product_tag) {
                $sql .= "LEFT JOIN `".DB_PREFIX."product_tag` pt ON pt.product_id=p.product_id AND pt.language_id='".(int)$language_id."' ";
            }
            if (isset($min_id) && isset($max_id)) {
                $sql .= "WHERE p.product_id BETWEEN $min_id AND $max_id ";
            }
            $sql .= "GROUP BY p.product_id ";
            $sql .= "ORDER BY p.product_id ";
            if (isset($offset) && isset($rows)) {
                $sql .= "LIMIT $offset,$rows; ";
            } else {
                $sql .= "; ";
            }
            $query = $this->db->query($sql);
            $product_descriptions[$language_code] = $query->rows;
        }
        return $product_descriptions;
    }

    protected function getManufacturerDescriptions(&$languages, $manufacturer_id)
    {
        $manufacturer_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT * FROM `".DB_PREFIX."manufacturer_description` md ";
            $sql .= "WHERE md.manufacturer_id = " . $manufacturer_id . " AND language_id=" . $language_id;
            $query = $this->db->query($sql);
            $manufacturer_descriptions[$language_code] = $query->rows;
        }
        return $manufacturer_descriptions;
    }

    protected function getProducts(&$languages, $default_language_id, $product_fields, $exist_meta_title, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        $sql  = "SELECT ";
        $sql .= "  p.product_id,";
        $sql .= "  GROUP_CONCAT( DISTINCT CAST(pc.category_id AS CHAR(11)) SEPARATOR \",\" ) AS categories,";
        $sql .= "  p.sku,";
        $sql .= "  p.upc,";
        if (in_array('ean', $product_fields)) {
            $sql .= "  p.ean,";
        }
        if (in_array('jan', $product_fields)) {
            $sql .= "  p.jan,";
        }
        if (in_array('isbn', $product_fields)) {
            $sql .= "  p.isbn,";
        }
        if (in_array('mpn', $product_fields)) {
            $sql .= "  p.mpn,";
        }
        $sql .= "  p.location,";
        $sql .= "  p.quantity,";
        $sql .= "  p.model,";
        $sql .= "  p.image AS image_name,";
        $sql .= "  p.manufacturer_id,";
        $sql .= "  p.shipping,";
        $sql .= "  p.price,";
        $sql .= "  p.points,";
        $sql .= "  p.date_added,";
        $sql .= "  p.date_modified,";
        $sql .= "  p.date_available,";
        $sql .= "  p.weight,";
        $sql .= "  wc.unit AS weight_unit,";
        $sql .= "  p.length,";
        $sql .= "  p.width,";
        $sql .= "  p.height,";
        $sql .= "  p.status,";
        $sql .= "  p.tax_class_id,";
        $sql .= "  p.sort_order,";
        $sql .= "  ua.keyword,";
        $sql .= "  p.stock_status_id, ";
        $sql .= "  mc.unit AS length_unit, ";
        $sql .= "  p.subtract, ";
        $sql .= "  p.minimum, ";
        $sql .= "  GROUP_CONCAT( DISTINCT CAST(pr.related_id AS CHAR(11)) SEPARATOR \",\" ) AS related ";
        $sql .= "FROM `".DB_PREFIX."product` p ";
        $sql .= "LEFT JOIN `".DB_PREFIX."product_to_category` pc ON p.product_id=pc.product_id ";
        $sql .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON ua.query=CONCAT('product_id=',p.product_id) ";
        $sql .= "LEFT JOIN `".DB_PREFIX."weight_class_description` wc ON wc.weight_class_id = p.weight_class_id ";
        $sql .= "  AND wc.language_id=$default_language_id ";
        $sql .= "LEFT JOIN `".DB_PREFIX."length_class_description` mc ON mc.length_class_id=p.length_class_id ";
        $sql .= "  AND mc.language_id=$default_language_id ";
        $sql .= "LEFT JOIN `".DB_PREFIX."product_related` pr ON pr.product_id=p.product_id ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE p.product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "GROUP BY p.product_id ";
        $sql .= "ORDER BY p.product_id ";
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= "; ";
        }
        $results = $this->db->query($sql);
        $product_descriptions = $this->getProductDescriptions($languages, $offset, $rows, $min_id, $max_id);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                $product_manufacturer = $this->getManufacturerDescriptions($languages, $row['manufacturer_id']);
                if (isset($product_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $product_descriptions[$language_code][$key]['name'];
                    $results->rows[$key]['description'][$language_code] = $product_descriptions[$language_code][$key]['description'];
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = $product_descriptions[$language_code][$key]['meta_title'];
                    }
                    $results->rows[$key]['meta_description'][$language_code] = $product_descriptions[$language_code][$key]['meta_description'];
                    $results->rows[$key]['meta_keyword'][$language_code] = $product_descriptions[$language_code][$key]['meta_keyword'];
                    $results->rows[$key]['tag'][$language_code] = $product_descriptions[$language_code][$key]['tag'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                    $results->rows[$key]['description'][$language_code] = '';
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = '';
                    }
                    $results->rows[$key]['meta_description'][$language_code] = '';
                    $results->rows[$key]['meta_keyword'][$language_code] = '';
                    $results->rows[$key]['tag'][$language_code] = '';
                }
                if (!empty($product_manufacturer[$language_code][0]['name'])) {
                    $results->rows[$key]['manufacturer'][$language_code] = $product_manufacturer[$language_code][0]['name'];
                } else {
                    $results->rows[$key]['manufacturer'][$language_code] = '';
                }
            }
        }

        return $results->rows;
    }

    public function populateProductsWorksheet(&$worksheet, &$languages, $default_language_id, &$price_format, &$box_format, &$weight_format, &$text_format, $offset = null, $rows = null, &$min_id = null, &$max_id = null)
    {
        // get list of the field names, some are only available for certain OpenCart versions
        $query = $this->db->query("DESCRIBE `".DB_PREFIX."product`");
        $product_fields = array();
        foreach ($query->rows as $row) {
            $product_fields[] = $row['Field'];
        }

        // Opencart versions from 2.0 onwards also have product_description.meta_title
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_id'), 4)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('categories'), 12)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sku'), 10)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('upc'), 12)+1);
        if (in_array('ean', $product_fields)) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('ean'), 14)+1);
        }
        if (in_array('jan', $product_fields)) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('jan'), 13)+1);
        }
        if (in_array('isbn', $product_fields)) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('isbn'), 13)+1);
        }
        if (in_array('mpn', $product_fields)) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('mpn'), 15)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('location'), 10)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('quantity'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('model'), 8)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('manufacturer')+4, 10)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('image_name'), 12)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('shipping'), 5)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('price'), 10)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('points'), 5)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_added'), 19)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_modified'), 19)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_available'), 10)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('weight'), 6)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('weight_unit'), 3)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('length'), 8)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('width'), 8)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('height'), 8)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('length_unit'), 3)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('status'), 5)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('tax_class_id'), 2)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('seo_keyword'), 16)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('description')+4, 32)+1);
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_title')+4, 20)+1);
            }
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_description')+4, 32)+1);
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_keywords')+4, 32)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('stock_status_id'), 3)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('store_ids'), 16)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('layout'), 16)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('related_ids'), 16)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('tags')+4, 32)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 8)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('subtract'), 5)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('minimum'), 8)+1);

        // The product headings row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        $styles[$j] = &$text_format;
        $data[$j++] = 'categories';
        $styles[$j] = &$text_format;
        $data[$j++] = 'sku';
        $styles[$j] = &$text_format;
        $data[$j++] = 'upc';
        if (in_array('ean', $product_fields)) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'ean';
        }
        if (in_array('jan', $product_fields)) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'jan';
        }
        if (in_array('isbn', $product_fields)) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'isbn';
        }
        if (in_array('mpn', $product_fields)) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'mpn';
        }
        $styles[$j] = &$text_format;
        $data[$j++] = 'location';
        $data[$j++] = 'quantity';
        $styles[$j] = &$text_format;
        $data[$j++] = 'model';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'manufacturer('.$language['code'].')';
        }
        $styles[$j] = &$text_format;
        $data[$j++] = 'image_name';
        $data[$j++] = 'shipping';
        $styles[$j] = &$price_format;
        $data[$j++] = 'price';
        $data[$j++] = 'points';
        $data[$j++] = 'date_added';
        $data[$j++] = 'date_modified';
        $data[$j++] = 'date_available';
        $styles[$j] = &$weight_format;
        $data[$j++] = 'weight';
        $data[$j++] = 'weight_unit';
        $data[$j++] = 'length';
        $data[$j++] = 'width';
        $data[$j++] = 'height';
        $data[$j++] = 'length_unit';
        $data[$j++] = 'status';
        $data[$j++] = 'tax_class_id';
        $styles[$j] = &$text_format;
        $data[$j++] = 'seo_keyword';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'description('.$language['code'].')';
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $styles[$j] = &$text_format;
                $data[$j++] = 'meta_title('.$language['code'].')';
            }
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_description('.$language['code'].')';
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_keywords('.$language['code'].')';
        }
        $data[$j++] = 'stock_status_id';
        $data[$j++] = 'store_ids';
        $styles[$j] = &$text_format;
        $data[$j++] = 'layout';
        $data[$j++] = 'related_ids';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'tags('.$language['code'].')';
        }
        $data[$j++] = 'sort_order';
        $data[$j++] = 'subtract';
        $data[$j++] = 'minimum';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual products data
        $i += 1;
        $j = 0;
        $store_ids = $this->getStoreIdsForProducts();
        $layouts = $this->getLayoutsForProducts();
        $products = $this->getProducts($languages, $default_language_id, $product_fields, $exist_meta_title, $offset, $rows, $min_id, $max_id);
        $len = count($products);
        $min_id = $products[0]['product_id'];
        $max_id = $products[$len-1]['product_id'];
        foreach ($products as $row) {
            $data = array();
            $worksheet->getRowDimension($i)->setRowHeight(26);
            $product_id = $row['product_id'];
            $data[$j++] = $product_id;
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $data[$j++] = $row['categories'];
            $data[$j++] = $row['sku'];
            $data[$j++] = $row['upc'];
            if (in_array('ean', $product_fields)) {
                $data[$j++] = $row['ean'];
            }
            if (in_array('jan', $product_fields)) {
                $data[$j++] = $row['jan'];
            }
            if (in_array('isbn', $product_fields)) {
                $data[$j++] = $row['isbn'];
            }
            if (in_array('mpn', $product_fields)) {
                $data[$j++] = $row['mpn'];
            }
            $data[$j++] = $row['location'];
            $data[$j++] = $row['quantity'];
            $data[$j++] = $row['model'];
            foreach ($languages as $language) {
                $data[$j++] = $row['manufacturer'][$language['code']];
            }
            $data[$j++] = $row['image_name'];
            $data[$j++] = ($row['shipping']==0) ? 'no' : 'yes';
            $data[$j++] = $row['price'];
            $data[$j++] = $row['points'];
            $data[$j++] = $row['date_added'];
            $data[$j++] = $row['date_modified'];
            $data[$j++] = $row['date_available'];
            $data[$j++] = $row['weight'];
            $data[$j++] = $row['weight_unit'];
            $data[$j++] = $row['length'];
            $data[$j++] = $row['width'];
            $data[$j++] = $row['height'];
            $data[$j++] = $row['length_unit'];
            $data[$j++] = ($row['status']==0) ? 'false' : 'true';
            $data[$j++] = $row['tax_class_id'];
            $data[$j++] = ($row['keyword']) ? $row['keyword'] : '';
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            if ($exist_meta_title) {
                foreach ($languages as $language) {
                    $data[$j++] = html_entity_decode($row['meta_title'][$language['code']], ENT_QUOTES, 'UTF-8');
                }
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_keyword'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $data[$j++] = $row['stock_status_id'];
            $store_id_list = '';
            if (isset($store_ids[$product_id])) {
                foreach ($store_ids[$product_id] as $store_id) {
                    $store_id_list .= ($store_id_list=='') ? $store_id : ','.$store_id;
                }
            }
            $data[$j++] = $store_id_list;
            $layout_list = '';
            if (isset($layouts[$product_id])) {
                foreach ($layouts[$product_id] as $store_id => $name) {
                    $layout_list .= ($layout_list=='') ? $store_id.':'.$name : ','.$store_id.':'.$name;
                }
            }
            $data[$j++] = $layout_list;
            $data[$j++] = $row['related'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['tag'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $data[$j++] = $row['sort_order'];
            $data[$j++] = ($row['subtract']==0) ? 'false' : 'true';
            $data[$j++] = $row['minimum'];
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getAdditionalImages($min_id = null, $max_id = null, $exist_sort_order = true)
    {
        if ($exist_sort_order) {
            $sql  = "SELECT product_id, image, sort_order ";
        } else {
            $sql  = "SELECT product_id, image ";
        }
        $sql .= "FROM `".DB_PREFIX."product_image` ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE product_id BETWEEN $min_id AND $max_id ";
        }
        if ($exist_sort_order) {
            $sql .= "ORDER BY product_id, sort_order, image;";
        } else {
            $sql .= "ORDER BY product_id, image;";
        }
        $result = $this->db->query($sql);
        return $result->rows;
    }

    protected function populateAdditionalImagesWorksheet(&$worksheet, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // check for the existence of product_image.sort_order field
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_image` LIKE 'sort_order'";
        $query = $this->db->query($sql);
        $exist_sort_order = ($query->num_rows > 0) ? true : false;

        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_id'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('image'), 30)+1);
        if ($exist_sort_order) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5)+1);
        }

        // The additional images headings row and colum styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        $styles[$j] = &$text_format;
        $data[$j++] = 'image';
        if ($exist_sort_order) {
            $data[$j++] = 'sort_order';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual additional images data
        $styles = array();
        $i += 1;
        $j = 0;
        $additional_images = $this->getAdditionalImages($min_id, $max_id, $exist_sort_order);
        foreach ($additional_images as $row) {
            $data = array();
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data[$j++] = $row['product_id'];
            $data[$j++] = $row['image'];
            if ($exist_sort_order) {
                $data[$j++] = $row['sort_order'];
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getSpecials($language_id, $min_id = null, $max_id = null)
    {
        // Newer OC versions use the 'customer_group_description' instead of 'customer_group' table for the 'name' field
        $exist_table_customer_group_description = false;
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."customer_group_description'");
        $exist_table_customer_group_description = ($query->num_rows > 0);

        // get the product specials
        $sql  = "SELECT ps.*, ";
        $sql .= ($exist_table_customer_group_description) ? "cgd.name " : "cg.name ";
        $sql .= "FROM `".DB_PREFIX."product_special` ps ";
        if ($exist_table_customer_group_description) {
            $sql .= "LEFT JOIN `".DB_PREFIX."customer_group_description` cgd ON cgd.customer_group_id=ps.customer_group_id ";
            $sql .= "  AND cgd.language_id=$language_id ";
        } else {
            $sql .= "LEFT JOIN `".DB_PREFIX."customer_group` cg ON cg.customer_group_id=ps.customer_group_id ";
        }
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE ps.product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "ORDER BY ps.product_id, name, ps.priority";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    protected function populateSpecialsWorksheet(&$worksheet, $language_id, &$price_format, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('customer_group')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('priority')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('price'), 10)+1, $price_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_start'), 19)+1, $text_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_end'), 19)+1, $text_format);

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        $styles[$j] = &$text_format;
        $data[$j++] = 'customer_group';
        $data[$j++] = 'priority';
        $styles[$j] = &$price_format;
        $data[$j++] = 'price';
        $data[$j++] = 'date_start';
        $data[$j++] = 'date_end';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual product specials data
        $i += 1;
        $j = 0;
        $specials = $this->getSpecials($language_id, $min_id, $max_id);
        foreach ($specials as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['product_id'];
            $data[$j++] = $row['name'];
            $data[$j++] = $row['priority'];
            $data[$j++] = $row['price'];
            $data[$j++] = $row['date_start'];
            $data[$j++] = $row['date_end'];
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getDiscounts($language_id, $min_id = null, $max_id = null)
    {
        // Newer OC versions use the 'customer_group_description' instead of 'customer_group' table for the 'name' field
        $exist_table_customer_group_description = false;
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."customer_group_description'");
        $exist_table_customer_group_description = ($query->num_rows > 0);

        // get the product discounts
        $sql  = "SELECT pd.*, ";
        $sql .= ($exist_table_customer_group_description) ? "cgd.name " : "cg.name ";
        $sql .= "FROM `".DB_PREFIX."product_discount` pd ";
        if ($exist_table_customer_group_description) {
            $sql .= "LEFT JOIN `".DB_PREFIX."customer_group_description` cgd ON cgd.customer_group_id=pd.customer_group_id ";
            $sql .= "  AND cgd.language_id=$language_id ";
        } else {
            $sql .= "LEFT JOIN `".DB_PREFIX."customer_group` cg ON cg.customer_group_id=pd.customer_group_id ";
        }
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE pd.product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "ORDER BY pd.product_id ASC, name ASC, pd.quantity ASC";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    protected function populateDiscountsWorksheet(&$worksheet, $language_id, &$price_format, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('customer_group')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('quantity')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('priority')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('price'), 10)+1, $price_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_start'), 19)+1, $text_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_end'), 19)+1, $text_format);

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] =  'product_id';
        $styles[$j] = &$text_format;
        $data[$j++] =  'customer_group';
        $data[$j++] =  'quantity';
        $data[$j++] =  'priority';
        $styles[$j] = &$price_format;
        $data[$j++] =  'price';
        $data[$j++] =  'date_start';
        $data[$j++] =  'date_end';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual product discounts data
        $i += 1;
        $j = 0;
        $discounts = $this->getDiscounts($language_id, $min_id, $max_id);
        foreach ($discounts as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] =$row['product_id'];
            $data[$j++] =$row['name'];
            $data[$j++] =$row['quantity'];
            $data[$j++] =$row['priority'];
            $data[$j++] =$row['price'];
            $data[$j++] =$row['date_start'];
            $data[$j++] =$row['date_end'];
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getRewards($language_id, $min_id = null, $max_id = null)
    {
        // Newer OC versions use the 'customer_group_description' instead of 'customer_group' table for the 'name' field
        $exist_table_customer_group_description = false;
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."customer_group_description'");
        $exist_table_customer_group_description = ($query->num_rows > 0);

        // get the product rewards
        $sql  = "SELECT pr.*, ";
        $sql .= ($exist_table_customer_group_description) ? "cgd.name " : "cg.name ";
        $sql .= "FROM `".DB_PREFIX."product_reward` pr ";
        if ($exist_table_customer_group_description) {
            $sql .= "LEFT JOIN `".DB_PREFIX."customer_group_description` cgd ON cgd.customer_group_id=pr.customer_group_id ";
            $sql .= "  AND cgd.language_id=$language_id ";
        } else {
            $sql .= "LEFT JOIN `".DB_PREFIX."customer_group` cg ON cg.customer_group_id=pr.customer_group_id ";
        }
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE pr.product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "ORDER BY pr.product_id, name";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    protected function populateRewardsWorksheet(&$worksheet, $language_id, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('customer_group')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('points')+1);

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        $styles[$j] = &$text_format;
        $data[$j++] = 'customer_group';
        $data[$j++] = 'points';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual product rewards data
        $i += 1;
        $j = 0;
        $rewards = $this->getRewards($language_id, $min_id, $max_id);
        foreach ($rewards as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['product_id'];
            $data[$j++] = $row['name'];
            $data[$j++] = $row['points'];
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getProductOptions($min_id, $max_id)
    {
        // get default language id
        $language_id = $this->getDefaultLanguageId();
        
        // Opencart versions from 2.0 onwards use product_option.value instead of the older product_option.option_value
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."product_option` LIKE 'value'";
        $query = $this->db->query($sql);
        $exist_po_value = ($query->num_rows > 0) ? true : false;

        // DB query for getting the product options
        if ($exist_po_value) {
            $sql  = "SELECT p.product_id, po.option_id, po.value AS option_value, po.required, od.name AS `option` FROM ";
        } else {
            $sql  = "SELECT p.product_id, po.option_id, po.option_value, po.required, od.name AS `option` FROM ";
        }
        $sql .= "( SELECT product_id ";
        $sql .= "  FROM `".DB_PREFIX."product` ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "  WHERE product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "  ORDER BY product_id ASC ";
        $sql .= ") AS p ";
        $sql .= "INNER JOIN `".DB_PREFIX."product_option` po ON po.product_id=p.product_id ";
        $sql .= "INNER JOIN `".DB_PREFIX."option_description` od ON od.option_id=po.option_id AND od.language_id='".(int)$language_id."' ";
        $sql .= "ORDER BY p.product_id ASC, po.option_id ASC";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    protected function populateProductOptionsWorksheet(&$worksheet, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id')+1);
        if ($this->config->get('export_import_settings_use_option_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('option_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('option'), 30)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('default_option_value')+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('required'), 5)+1);

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        if ($this->config->get('export_import_settings_use_option_id')) {
            $data[$j++] = 'option_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'option';
        }
        $styles[$j] = &$text_format;
        $data[$j++] = 'default_option_value';
        $data[$j++] = 'required';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual product options data
        $i += 1;
        $j = 0;
        $product_options = $this->getProductOptions($min_id, $max_id);
        foreach ($product_options as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['product_id'];
            if ($this->config->get('export_import_settings_use_option_id')) {
                $data[$j++] = $row['option_id'];
            } else {
                $data[$j++] = html_entity_decode($row['option'], ENT_QUOTES, 'UTF-8');
            }
            $data[$j++] = html_entity_decode($row['option_value'], ENT_QUOTES, 'UTF-8');
            $data[$j++] = ($row['required']==0) ? 'false' : 'true';
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getProductOptionValues($min_id, $max_id)
    {
        $language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT ";
        $sql .= "  p.product_id, pov.option_id, pov.option_value_id, pov.quantity, pov.subtract, od.name AS `option`, ovd.name AS option_value, ";
        $sql .= "  pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix ";
        $sql .= "FROM ";
        $sql .= "( SELECT product_id ";
        $sql .= "  FROM `".DB_PREFIX."product` ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "  WHERE product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "  ORDER BY product_id ASC ";
        $sql .= ") AS p ";
        $sql .= "INNER JOIN `".DB_PREFIX."product_option_value` pov ON pov.product_id=p.product_id ";
        $sql .= "INNER JOIN `".DB_PREFIX."option_value_description` ovd ON ovd.option_value_id=pov.option_value_id AND ovd.language_id='".(int)$language_id."' ";
        $sql .= "INNER JOIN `".DB_PREFIX."option_description` od ON od.option_id=ovd.option_id AND od.language_id='".(int)$language_id."' ";
        $sql .= "ORDER BY p.product_id ASC, pov.option_id ASC, pov.option_value_id";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    protected function populateProductOptionValuesWorksheet(&$worksheet, &$price_format, &$box_format, &$weight_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id')+1);
        if ($this->config->get('export_import_settings_use_option_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('option_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('option'), 30)+1);
        }
        if ($this->config->get('export_import_settings_use_option_value_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('option_value_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('option_value'), 30)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('quantity'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('subtract'), 5)+1, $text_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('price'), 10)+1, $price_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('price_prefix'), 5)+1, $text_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('points'), 10)+1, $price_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('points_prefix'), 5)+1, $text_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('weight'), 10)+1, $price_format);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('weight_prefix'), 5)+1, $text_format);

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        if ($this->config->get('export_import_settings_use_option_id')) {
            $data[$j++] = 'option_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'option';
        }
        if ($this->config->get('export_import_settings_use_option_value_id')) {
            $data[$j++] = 'option_value_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'option_value';
        }
        $data[$j++] = 'quantity';
        $data[$j++] = 'subtract';
        $styles[$j] = &$price_format;
        $data[$j++] = 'price';
        $data[$j++] = "price_prefix";
        $data[$j++] = 'points';
        $data[$j++] = "points_prefix";
        $styles[$j] = &$weight_format;
        $data[$j++] = 'weight';
        $data[$j++] = 'weight_prefix';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual product option values data
        $i += 1;
        $j = 0;
        $product_option_values = $this->getProductOptionValues($min_id, $max_id);
        foreach ($product_option_values as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['product_id'];
            if ($this->config->get('export_import_settings_use_option_id')) {
                $data[$j++] = $row['option_id'];
            } else {
                $data[$j++] = html_entity_decode($row['option'], ENT_QUOTES, 'UTF-8');
            }
            if ($this->config->get('export_import_settings_use_option_value_id')) {
                $data[$j++] = $row['option_value_id'];
            } else {
                $data[$j++] = html_entity_decode($row['option_value'], ENT_QUOTES, 'UTF-8');
            }
            $data[$j++] = $row['quantity'];
            $data[$j++] = ($row['subtract']==0) ? 'false' : 'true';
            $data[$j++] = $row['price'];
            $data[$j++] = $row['price_prefix'];
            $data[$j++] = $row['points'];
            $data[$j++] = $row['points_prefix'];
            $data[$j++] = $row['weight'];
            $data[$j++] = $row['weight_prefix'];
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getAttributeGroupNames($language_id)
    {
        $sql  = "SELECT attribute_group_id, name ";
        $sql .= "FROM `".DB_PREFIX."attribute_group_description` ";
        $sql .= "WHERE language_id='".(int)$language_id."' ";
        $sql .= "ORDER BY attribute_group_id ASC";
        $query = $this->db->query($sql);
        $attribute_group_names = array();
        foreach ($query->rows as $row) {
            $attribute_group_id = $row['attribute_group_id'];
            $name = $row['name'];
            $attribute_group_names[$attribute_group_id] = $name;
        }
        return $attribute_group_names;
    }

    protected function getAttributeNames($language_id)
    {
        $sql  = "SELECT attribute_id, name ";
        $sql .= "FROM `".DB_PREFIX."attribute_description` ";
        $sql .= "WHERE language_id='".(int)$language_id."' ";
        $sql .= "ORDER BY attribute_id ASC";
        $query = $this->db->query($sql);
        $attribute_names = array();
        foreach ($query->rows as $row) {
            $attribute_id = $row['attribute_id'];
            $attribute_name = $row['name'];
            $attribute_names[$attribute_id] = $attribute_name;
        }
        return $attribute_names;
    }

    protected function getProductAttributes(&$languages, $min_id, $max_id)
    {
        $sql  = "SELECT pa.product_id, ag.attribute_group_id, pa.attribute_id, pa.language_id, pa.text ";
        $sql .= "FROM `".DB_PREFIX."product_attribute` pa ";
        $sql .= "INNER JOIN `".DB_PREFIX."attribute` a ON a.attribute_id=pa.attribute_id ";
        $sql .= "INNER JOIN `".DB_PREFIX."attribute_group` ag ON ag.attribute_group_id=a.attribute_group_id ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "ORDER BY pa.product_id ASC, ag.attribute_group_id ASC, pa.attribute_id ASC";
        $query = $this->db->query($sql);
        $texts = array();
        foreach ($query->rows as $row) {
            $product_id = $row['product_id'];
            $attribute_group_id = $row['attribute_group_id'];
            $attribute_id = $row['attribute_id'];
            $language_id = $row['language_id'];
            $text = $row['text'];
            $texts[$product_id][$attribute_group_id][$attribute_id][$language_id] = $text;
        }
        $product_attributes = array();
        foreach ($texts as $product_id => $level1) {
            foreach ($level1 as $attribute_group_id => $level2) {
                foreach ($level2 as $attribute_id => $text) {
                    $product_attribute = array();
                    $product_attribute['product_id'] = $product_id;
                    $product_attribute['attribute_group_id'] = $attribute_group_id;
                    $product_attribute['attribute_id'] = $attribute_id;
                    $product_attribute['text'] = array();
                    foreach ($languages as $language) {
                        $language_id = $language['language_id'];
                        $code = $language['code'];
                        if (isset($text[$language_id])) {
                            $product_attribute['text'][$code] = $text[$language_id];
                        } else {
                            $product_attribute['text'][$code] = '';
                        }
                    }
                    $product_attributes[] = $product_attribute;
                }
            }
        }
        return $product_attributes;
    }

    protected function populateProductAttributesWorksheet(&$worksheet, &$languages, $default_language_id, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id')+1);
        if ($this->config->get('export_import_settings_use_attribute_group_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('attribute_group_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('attribute_group'), 30)+1);
        }
        if ($this->config->get('export_import_settings_use_attribute_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('attribute_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('attribute'), 30)+1);
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('text')+4, 30)+1);
        }

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        if ($this->config->get('export_import_settings_use_attribute_group_id')) {
            $data[$j++] = 'attribute_group_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'attribute_group';
        }
        if ($this->config->get('export_import_settings_use_attribute_id')) {
            $data[$j++] = 'attribute_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'attribute';
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'text('.$language['code'].')';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual product attributes data
        if (!$this->config->get('export_import_settings_use_attribute_group_id')) {
            $attribute_group_names = $this->getAttributeGroupNames($default_language_id);
        }
        if (!$this->config->get('export_import_settings_use_attribute_id')) {
            $attribute_names = $this->getAttributeNames($default_language_id);
        }
        $i += 1;
        $j = 0;
        $product_attributes = $this->getProductAttributes($languages, $min_id, $max_id);
        foreach ($product_attributes as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['product_id'];
            if ($this->config->get('export_import_settings_use_attribute_group_id')) {
                $data[$j++] = $row['attribute_group_id'];
            } else {
                $data[$j++] = html_entity_decode($attribute_group_names[$row['attribute_group_id']], ENT_QUOTES, 'UTF-8');
            }
            if ($this->config->get('export_import_settings_use_attribute_id')) {
                $data[$j++] = $row['attribute_id'];
            } else {
                $data[$j++] = html_entity_decode($attribute_names[$row['attribute_id']], ENT_QUOTES, 'UTF-8');
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['text'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getProductFilters($min_id, $max_id)
    {
        $sql  = "SELECT pf.product_id, fg.filter_group_id, pf.filter_id ";
        $sql .= "FROM `".DB_PREFIX."product_filter` pf ";
        $sql .= "INNER JOIN `".DB_PREFIX."filter` f ON f.filter_id=pf.filter_id ";
        $sql .= "INNER JOIN `".DB_PREFIX."filter_group` fg ON fg.filter_group_id=f.filter_group_id ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE product_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= "ORDER BY pf.product_id ASC, fg.filter_group_id ASC, pf.filter_id ASC";
        $query = $this->db->query($sql);
        $product_filters = array();
        foreach ($query->rows as $row) {
            $product_filter = array();
            $product_filter['product_id'] = $row['product_id'];
            $product_filter['filter_group_id'] = $row['filter_group_id'];
            $product_filter['filter_id'] = $row['filter_id'];
            $product_filters[] = $product_filter;
        }
        return $product_filters;
    }

    protected function populateProductFiltersWorksheet(&$worksheet, &$languages, $default_language_id, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id')+1);
        if ($this->config->get('export_import_settings_use_filter_group_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('filter_group_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('filter_group'), 30)+1);
        }
        if ($this->config->get('export_import_settings_use_filter_id')) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('filter_id')+1);
        } else {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('filter'), 30)+1);
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('text')+4, 30)+1);
        }

        // The heading row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        if ($this->config->get('export_import_settings_use_filter_group_id')) {
            $data[$j++] = 'filter_group_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'filter_group';
        }
        if ($this->config->get('export_import_settings_use_filter_id')) {
            $data[$j++] = 'filter_id';
        } else {
            $styles[$j] = &$text_format;
            $data[$j++] = 'filter';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual product filters data
        if (!$this->config->get('export_import_settings_use_filter_group_id')) {
            $filter_group_names = $this->getFilterGroupNames($default_language_id);
        }
        if (!$this->config->get('export_import_settings_use_filter_id')) {
            $filter_names = $this->getFilterNames($default_language_id);
        }
        $i += 1;
        $j = 0;
        $product_filters = $this->getProductFilters($min_id, $max_id);
        foreach ($product_filters as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['product_id'];
            if ($this->config->get('export_import_settings_use_filter_group_id')) {
                $data[$j++] = $row['filter_group_id'];
            } else {
                $data[$j++] = html_entity_decode($filter_group_names[$row['filter_group_id']], ENT_QUOTES, 'UTF-8');
            }
            if ($this->config->get('export_import_settings_use_filter_id')) {
                $data[$j++] = $row['filter_id'];
            } else {
                $data[$j++] = html_entity_decode($filter_names[$row['filter_id']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }

    }
    
    protected function getOptionDescriptions(&$languages)
    {
        // query the option_description table for each language
        $option_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT o.option_id, od.* ";
            $sql .= "FROM `".DB_PREFIX."option` o ";
            $sql .= "LEFT JOIN `".DB_PREFIX."option_description` od ON od.option_id=o.option_id AND od.language_id='".(int)$language_id."' ";
            $sql .= "GROUP BY o.option_id ";
            $sql .= "ORDER BY o.option_id ASC ";
            $query = $this->db->query($sql);
            $option_descriptions[$language_code] = $query->rows;
        }
        return $option_descriptions;
    }

    protected function getOptions(&$languages)
    {
        $results = $this->db->query("SELECT * FROM `".DB_PREFIX."option` ORDER BY option_id ASC");
        $option_descriptions = $this->getOptionDescriptions($languages);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($option_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $option_descriptions[$language_code][$key]['name'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                }
            }
        }
        return $results->rows;
    }

    protected function populateOptionsWorksheet(&$worksheet, &$languages, &$box_format, &$text_format)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('option_id'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('type'), 10)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }

        // The options headings row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'option_id';
        $data[$j++] = 'type';
        $data[$j++] = 'sort_order';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual options data
        $i += 1;
        $j = 0;
        $options = $this->getOptions($languages);
        foreach ($options as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['option_id'];
            $data[$j++] = $row['type'];
            $data[$j++] = $row['sort_order'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getOptionValueDescriptions(&$languages)
    {
        // query the option_description table for each language
        $option_value_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT ov.option_id, ov.option_value_id, ovd.* ";
            $sql .= "FROM `".DB_PREFIX."option_value` ov ";
            $sql .= "LEFT JOIN `".DB_PREFIX."option_value_description` ovd ON ovd.option_value_id=ov.option_value_id AND ovd.language_id='".(int)$language_id."' ";
            $sql .= "GROUP BY ov.option_id, ov.option_value_id ";
            $sql .= "ORDER BY ov.option_id ASC, ov.option_value_id ASC ";
            $query = $this->db->query($sql);
            $option_value_descriptions[$language_code] = $query->rows;
        }
        return $option_value_descriptions;
    }

    protected function getOptionValues(&$languages)
    {
        $results = $this->db->query("SELECT * FROM `".DB_PREFIX."option_value` ORDER BY option_id ASC, option_value_id ASC");
        $option_value_descriptions = $this->getOptionValueDescriptions($languages);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($option_value_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $option_value_descriptions[$language_code][$key]['name'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                }
            }
        }
        return $results->rows;
    }

    protected function populateOptionValuesWorksheet(&$worksheet, $languages, &$box_format, &$text_format)
    {
        // check for the existence of option_value.image field
        $sql = "SHOW COLUMNS FROM `".DB_PREFIX."option_value` LIKE 'image'";
        $query = $this->db->query($sql);
        $exist_image = ($query->num_rows > 0) ? true : false;

        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('option_value_id'), 2)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('option_id'), 4)+1);
        if ($exist_image) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('image'), 12)+1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }

        // The option values headings row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'option_value_id';
        $data[$j++] = 'option_id';
        if ($exist_image) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'image';
        }
        $data[$j++] = 'sort_order';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual option values data
        $i += 1;
        $j = 0;
        $options = $this->getOptionValues($languages);
        foreach ($options as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['option_value_id'];
            $data[$j++] = $row['option_id'];
            if ($exist_image) {
                $data[$j++] = $row['image'];
            }
            $data[$j++] = $row['sort_order'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getAttributeGroupDescriptions(&$languages)
    {
        // query the attribute_group_description table for each language
        $attribute_group_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT ag.attribute_group_id, agd.* ";
            $sql .= "FROM `".DB_PREFIX."attribute_group` ag ";
            $sql .= "LEFT JOIN `".DB_PREFIX."attribute_group_description` agd ON agd.attribute_group_id=ag.attribute_group_id AND agd.language_id='".(int)$language_id."' ";
            $sql .= "GROUP BY ag.attribute_group_id ";
            $sql .= "ORDER BY ag.attribute_group_id ASC ";
            $query = $this->db->query($sql);
            $attribute_group_descriptions[$language_code] = $query->rows;
        }
        return $attribute_group_descriptions;
    }

    protected function getAttributeGroups(&$languages)
    {
        $results = $this->db->query("SELECT * FROM `".DB_PREFIX."attribute_group` ORDER BY attribute_group_id ASC");
        $attribute_group_descriptions = $this->getAttributeGroupDescriptions($languages);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($attribute_group_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $attribute_group_descriptions[$language_code][$key]['name'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                }
            }
        }
        return $results->rows;
    }

    protected function populateAttributeGroupsWorksheet(&$worksheet, $languages, &$box_format, &$text_format)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('attribute_group_id'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }
        
        // The attribute groups headings row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'attribute_group_id';
        $data[$j++] = 'sort_order';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] ='name('.$language['code'].')';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual attribute groups data
        $i += 1;
        $j = 0;
        $attributes = $this->getAttributeGroups($languages);
        foreach ($attributes as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['attribute_group_id'];
            $data[$j++] = $row['sort_order'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getAttributeDescriptions(&$languages)
    {
        // query the attribute_description table for each language
        $attribute_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT a.attribute_group_id, a.attribute_id, ad.* ";
            $sql .= "FROM `".DB_PREFIX."attribute` a ";
            $sql .= "LEFT JOIN `".DB_PREFIX."attribute_description` ad ON ad.attribute_id=a.attribute_id AND ad.language_id='".(int)$language_id."' ";
            $sql .= "GROUP BY a.attribute_group_id, a.attribute_id ";
            $sql .= "ORDER BY a.attribute_group_id ASC, a.attribute_id ASC ";
            $query = $this->db->query($sql);
            $attribute_descriptions[$language_code] = $query->rows;
        }
        return $attribute_descriptions;
    }

    protected function getAttributes(&$languages)
    {
        $results = $this->db->query("SELECT * FROM `".DB_PREFIX."attribute` ORDER BY attribute_group_id ASC, attribute_id ASC");
        $attribute_descriptions = $this->getAttributeDescriptions($languages);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($attribute_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $attribute_descriptions[$language_code][$key]['name'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                }
            }
        }
        return $results->rows;
    }

    protected function populateAttributesWorksheet(&$worksheet, $languages, &$box_format, &$text_format)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('attribute_id'), 2)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('attribute_group_id'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }

        // The attributes headings row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'attribute_id';
        $data[$j++] = 'attribute_group_id';
        $data[$j++] = 'sort_order';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);
        
        // The actual attributes values data
        $i += 1;
        $j = 0;
        $options = $this->getAttributes($languages);
        foreach ($options as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['attribute_id'];
            $data[$j++] = $row['attribute_group_id'];
            $data[$j++] = $row['sort_order'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getFilterGroupDescriptions(&$languages)
    {
        // query the filter_group_description table for each language
        $filter_group_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT ag.filter_group_id, agd.* ";
            $sql .= "FROM `".DB_PREFIX."filter_group` ag ";
            $sql .= "LEFT JOIN `".DB_PREFIX."filter_group_description` agd ON agd.filter_group_id=ag.filter_group_id AND agd.language_id='".(int)$language_id."' ";
            $sql .= "GROUP BY ag.filter_group_id ";
            $sql .= "ORDER BY ag.filter_group_id ASC ";
            $query = $this->db->query($sql);
            $filter_group_descriptions[$language_code] = $query->rows;
        }
        return $filter_group_descriptions;
    }

    protected function getFilterGroups(&$languages)
    {
        $results = $this->db->query("SELECT * FROM `".DB_PREFIX."filter_group` ORDER BY filter_group_id ASC");
        $filter_group_descriptions = $this->getFilterGroupDescriptions($languages);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($filter_group_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $filter_group_descriptions[$language_code][$key]['name'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                }
            }
        }
        return $results->rows;
    }

    protected function populateFilterGroupsWorksheet(&$worksheet, $languages, &$box_format, &$text_format)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('filter_group_id'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }
        
        // The filter groups headings row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'filter_group_id';
        $data[$j++] = 'sort_order';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] ='name('.$language['code'].')';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual filter groups data
        $i += 1;
        $j = 0;
        $filters = $this->getFilterGroups($languages);
        foreach ($filters as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['filter_group_id'];
            $data[$j++] = $row['sort_order'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }
    }

    protected function getFilterDescriptions(&$languages)
    {
        // query the filter_description table for each language
        $filter_descriptions = array();
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql  = "SELECT a.filter_group_id, a.filter_id, ad.* ";
            $sql .= "FROM `".DB_PREFIX."filter` a ";
            $sql .= "LEFT JOIN `".DB_PREFIX."filter_description` ad ON ad.filter_id=a.filter_id AND ad.language_id='".(int)$language_id."' ";
            $sql .= "GROUP BY a.filter_group_id, a.filter_id ";
            $sql .= "ORDER BY a.filter_group_id ASC, a.filter_id ASC ";
            $query = $this->db->query($sql);
            $filter_descriptions[$language_code] = $query->rows;
        }
        return $filter_descriptions;
    }

    protected function getFilters(&$languages)
    {
        $results = $this->db->query("SELECT * FROM `".DB_PREFIX."filter` ORDER BY filter_group_id ASC, filter_id ASC");
        $filter_descriptions = $this->getFilterDescriptions($languages);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($filter_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $filter_descriptions[$language_code][$key]['name'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                }
            }
        }
        return $results->rows;
    }

    protected function populateFiltersWorksheet(&$worksheet, $languages, &$box_format, &$text_format)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('filter_id'), 2)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('filter_group_id'), 4)+1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5)+1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name')+4, 30)+1);
        }

        // The filters headings row and column styles
        $styles = array();
        $data = array();
        $i = 1;
        $j = 0;
        $data[$j++] = 'filter_id';
        $data[$j++] = 'filter_group_id';
        $data[$j++] = 'sort_order';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);
        
        // The actual filters values data
        $i += 1;
        $j = 0;
        $options = $this->getFilters($languages);
        foreach ($options as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = array();
            $data[$j++] = $row['filter_id'];
            $data[$j++] = $row['filter_group_id'];
            $data[$j++] = $row['sort_order'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);
            $i += 1;
            $j = 0;
        }

    }
    
    protected function clearSpreadsheetCache()
    {
        $files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
        
        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    @unlink($file);
                    clearstatcache();
                }
            }
        }
    }

    public function getMaxProductId()
    {
        $query = $this->db->query("SELECT MAX(product_id) as max_product_id FROM `".DB_PREFIX."product`");
        if (isset($query->row['max_product_id'])) {
            $max_id = $query->row['max_product_id'];
        } else {
            $max_id = 0;
        }
        return $max_id;
    }

    public function getMinProductId()
    {
        $query = $this->db->query("SELECT MIN(product_id) as min_product_id FROM `".DB_PREFIX."product`");
        if (isset($query->row['min_product_id'])) {
            $min_id = $query->row['min_product_id'];
        } else {
            $min_id = 0;
        }
        return $min_id;
    }

    public function getCountProduct()
    {
        $query = $this->db->query("SELECT COUNT(product_id) as count_product FROM `".DB_PREFIX."product`");
        if (isset($query->row['count_product'])) {
            $count = $query->row['count_product'];
        } else {
            $count = 0;
        }
        return $count;
    }
 
    public function getMaxCategoryId()
    {
        $query = $this->db->query("SELECT MAX(category_id) as max_category_id FROM `".DB_PREFIX."category`");
        if (isset($query->row['max_category_id'])) {
            $max_id = $query->row['max_category_id'];
        } else {
            $max_id = 0;
        }
        return $max_id;
    }

    public function getMinCategoryId()
    {
        $query = $this->db->query("SELECT MIN(category_id) as min_category_id FROM `".DB_PREFIX."category`");
        if (isset($query->row['min_category_id'])) {
            $min_id = $query->row['min_category_id'];
        } else {
            $min_id = 0;
        }
        return $min_id;
    }

    public function getCountCategory()
    {
        $query = $this->db->query("SELECT COUNT(category_id) as count_category FROM `".DB_PREFIX."category`");
        if (isset($query->row['count_category'])) {
            $count = $query->row['count_category'];
        } else {
            $count = 0;
        }
        return $count;
    }

    public function download($export_type, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');

        // Use the PHPExcel package from http://phpexcel.codeplex.com/
        $cwd = getcwd();
        chdir(DIR_SYSTEM.'PHPExcel');
        require_once('Classes/PHPExcel.php');
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_ExportImportValueBinder());
        chdir($cwd);

        // find out whether all data is to be downloaded
        $all = !isset($offset) && !isset($rows) && !isset($min_id) && !isset($max_id);

        // Memory Optimization
        if ($this->config->get('export_import_settings_use_export_cache')) {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array( 'memoryCacheSize'  => '16MB' );
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        }

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $languages = $this->getLanguages();
            $default_language_id = $this->getDefaultLanguageId();

            // create a new workbook
            $workbook = new PHPExcel();

            // set some default styles
            $workbook->getDefaultStyle()->getFont()->setName('Arial');
            $workbook->getDefaultStyle()->getFont()->setSize(10);
            //$workbook->getDefaultStyle()->getAlignment()->setIndent(0.5);
            $workbook->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $workbook->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $workbook->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);

            // pre-define some commonly used styles
            $box_format = array(
                'fill' => array(
                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                    'color'     => array( 'rgb' => 'F0F0F0')
                ),
                /*
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'       => false,
                    'indent'     => 0
                )
				*/
            );
            $text_format = array(
                'numberformat' => array(
                    'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
                ),
                /*
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'       => false,
                    'indent'     => 0
                )
				*/
            );
            $price_format = array(
                'numberformat' => array(
                    'code' => '######0.00'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    /*
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'       => false,
                    'indent'     => 0
					*/
                )
            );
            $weight_format = array(
                'numberformat' => array(
                    'code' => '##0.00'
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    /*
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'       => false,
                    'indent'     => 0
					*/
                )
            );
            
            // create the worksheets
            $worksheet_index = 0;
            switch ($export_type) {
                case 'c':
                    // creating the Categories worksheet
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Categories');
                    $this->populateCategoriesWorksheet($worksheet, $languages, $box_format, $text_format, $offset, $rows, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);
                    // creating the CategoryFilters worksheet
                    if ($this->existFilter()) {
                        $workbook->createSheet();
                        $workbook->setActiveSheetIndex($worksheet_index++);
                        $worksheet = $workbook->getActiveSheet();
                        $worksheet->setTitle('CategoryFilters');
                        $this->populateCategoryFiltersWorksheet($worksheet, $languages, $default_language_id, $box_format, $text_format, $min_id, $max_id);
                        $worksheet->freezePaneByColumnAndRow(1, 2);
                    }
                    break;

                case 'p':
                    // creating the Products worksheet
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Products');
                    $this->populateProductsWorksheet($worksheet, $languages, $default_language_id, $price_format, $box_format, $weight_format, $text_format, $offset, $rows, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the AdditionalImages worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('AdditionalImages');
                    $this->populateAdditionalImagesWorksheet($worksheet, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the Specials worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Specials');
                    $this->populateSpecialsWorksheet($worksheet, $default_language_id, $price_format, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the Discounts worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Discounts');
                    $this->populateDiscountsWorksheet($worksheet, $default_language_id, $price_format, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the Rewards worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Rewards');
                    $this->populateRewardsWorksheet($worksheet, $default_language_id, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the ProductOptions worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('ProductOptions');
                    $this->populateProductOptionsWorksheet($worksheet, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the ProductOptionValues worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('ProductOptionValues');
                    $this->populateProductOptionValuesWorksheet($worksheet, $price_format, $box_format, $weight_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the ProductAttributes worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('ProductAttributes');
                    $this->populateProductAttributesWorksheet($worksheet, $languages, $default_language_id, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);
                    // creating the ProductFilters worksheet
                    if ($this->existFilter()) {
                        $workbook->createSheet();
                        $workbook->setActiveSheetIndex($worksheet_index++);
                        $worksheet = $workbook->getActiveSheet();
                        $worksheet->setTitle('ProductFilters');
                        $this->populateProductFiltersWorksheet($worksheet, $languages, $default_language_id, $box_format, $text_format, $min_id, $max_id);
                        $worksheet->freezePaneByColumnAndRow(1, 2);
                    }
                    break;

                case 'o':
                    // creating the Options worksheet
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Options');
                    $this->populateOptionsWorksheet($worksheet, $languages, $box_format, $text_format);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the OptionValues worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('OptionValues');
                    $this->populateOptionValuesWorksheet($worksheet, $languages, $box_format, $text_format);
                    $worksheet->freezePaneByColumnAndRow(1, 2);
                    break;

                case 'a':
                    // creating the AttributeGroups worksheet
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('AttributeGroups');
                    $this->populateAttributeGroupsWorksheet($worksheet, $languages, $box_format, $text_format);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the Attributes worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Attributes');
                    $this->populateAttributesWorksheet($worksheet, $languages, $box_format, $text_format);
                    $worksheet->freezePaneByColumnAndRow(1, 2);
                    break;

                case 'f':
                    if (!$this->existFilter()) {
                        throw new Exception($this->language->get('error_filter_not_supported'));
                        break;
                    }

                    // creating the FilterGroups worksheet
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('FilterGroups');
                    $this->populateFilterGroupsWorksheet($worksheet, $languages, $box_format, $text_format);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                    // creating the Filters worksheet
                    $workbook->createSheet();
                    $workbook->setActiveSheetIndex($worksheet_index++);
                    $worksheet = $workbook->getActiveSheet();
                    $worksheet->setTitle('Filters');
                    $this->populateFiltersWorksheet($worksheet, $languages, $box_format, $text_format);
                    $worksheet->freezePaneByColumnAndRow(1, 2);
                    break;
                default:
                    break;
            }

            $workbook->setActiveSheetIndex(0);

            // redirect output to client browser
            $datetime = date('Y-m-d');
            switch ($export_type) {
                case 'c':
                    $filename = 'categories-'.$datetime;
                    if (!$all) {
                        if (isset($offset)) {
                            $filename .= "-offset-$offset";
                        } elseif (isset($min_id)) {
                            $filename .= "-start-$min_id";
                        }
                        if (isset($rows)) {
                            $filename .= "-rows-$rows";
                        } elseif (isset($max_id)) {
                            $filename .= "-end-$max_id";
                        }
                    }
                    $filename .= '.xlsx';
                    break;
                case 'p':
                    $filename = 'products-'.$datetime;
                    if (!$all) {
                        if (isset($offset)) {
                            $filename .= "-offset-$offset";
                        } elseif (isset($min_id)) {
                            $filename .= "-start-$min_id";
                        }
                        if (isset($rows)) {
                            $filename .= "-rows-$rows";
                        } elseif (isset($max_id)) {
                            $filename .= "-end-$max_id";
                        }
                    }
                    $filename .= '.xlsx';
                    break;
                case 'o':
                    $filename = 'options-'.$datetime.'.xlsx';
                    break;
                case 'a':
                    $filename = 'attributes-'.$datetime.'.xlsx';
                    break;
                case 'f':
                    if (!$this->existFilter()) {
                        throw new Exception($this->language->get('error_filter_not_supported'));
                        break;
                    }
                    $filename = 'filters-'.$datetime.'.xlsx';
                    break;
                default:
                    $filename = $datetime.'.xlsx';
                    break;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
            $objWriter->setPreCalculateFormulas(false);
            $objWriter->save('php://output');

            // Clear the spreadsheet caches
            $this->clearSpreadsheetCache();
            exit;

        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = array( 'errstr'=>$errstr, 'errno'=>$errno, 'errfile'=>$errfile, 'errline'=>$errline );
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }
            return;
        }
    }

    protected function curl_get_contents($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function getOptionNameCounts()
    {
        $default_language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT `name`, COUNT(option_id) AS `count` FROM `".DB_PREFIX."option_description` ";
        $sql .= "WHERE language_id='".(int)$default_language_id."' ";
        $sql .= "GROUP BY `name`";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getOptionValueNameCounts()
    {
        $default_language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT option_id, `name`, COUNT(option_value_id) AS `count` FROM `".DB_PREFIX."option_value_description` ";
        $sql .= "WHERE language_id='".(int)$default_language_id."' ";
        $sql .= "GROUP BY option_id, `name`";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getAttributeGroupNameCounts()
    {
        $default_language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT `name`, COUNT(attribute_group_id) AS `count` FROM `".DB_PREFIX."attribute_group_description` ";
        $sql .= "WHERE language_id='".(int)$default_language_id."' ";
        $sql .= "GROUP BY `name`";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getAttributeNameCounts()
    {
        $default_language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT ag.attribute_group_id, ad.`name`, COUNT(ad.attribute_id) AS `count` FROM `".DB_PREFIX."attribute_description` ad ";
        $sql .= "INNER JOIN `".DB_PREFIX."attribute` a ON a.attribute_id=ad.attribute_id ";
        $sql .= "INNER JOIN `".DB_PREFIX."attribute_group` ag ON ag.attribute_group_id=a.attribute_group_id ";
        $sql .= "WHERE ad.language_id='".(int)$default_language_id."' ";
        $sql .= "GROUP BY ag.attribute_group_id, ad.`name`";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getFilterGroupNameCounts()
    {
        $default_language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT `name`, COUNT(filter_group_id) AS `count` FROM `".DB_PREFIX."filter_group_description` ";
        $sql .= "WHERE language_id='".(int)$default_language_id."' ";
        $sql .= "GROUP BY `name`";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getFilterNameCounts()
    {
        $default_language_id = $this->getDefaultLanguageId();
        $sql  = "SELECT fg.filter_group_id, fd.`name`, COUNT(fd.filter_id) AS `count` FROM `".DB_PREFIX."filter_description` fd ";
        $sql .= "INNER JOIN `".DB_PREFIX."filter` f ON f.filter_id=fd.filter_id ";
        $sql .= "INNER JOIN `".DB_PREFIX."filter_group` fg ON fg.filter_group_id=f.filter_group_id ";
        $sql .= "WHERE fd.language_id='".(int)$default_language_id."' ";
        $sql .= "GROUP BY fg.filter_group_id, fd.`name`";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function existFilter()
    {
        // only newer OpenCart versions support filters
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."filter'");
        $exist_table_filter = ($query->num_rows > 0);
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."filter_group'");
        $exist_table_filter_group = ($query->num_rows > 0);
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."product_filter'");
        $exist_table_product_filter = ($query->num_rows > 0);
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."category_filter'");
        $exist_table_category_filter = ($query->num_rows > 0);

        if (!$exist_table_filter) {
            return false;
        }
        if (!$exist_table_filter_group) {
            return false;
        }
        if (!$exist_table_product_filter) {
            return false;
        }
        if (!$exist_table_category_filter) {
            return false;
        }
        return true;
    }
}
