<?php
/**
 * @package		Arastta eCommerce
 * @copyright	Copyright (C) 2015 Arastta Association. All rights reserved. (arastta.org)
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

use Symfony\Component\Finder\Finder;

class ModelSystemLanguageoverride extends Model {

	public function getLanguages($filter_data = array()) {
        if (isset($filter_data['filter_client']) and ($filter_data['filter_client'] != 'admin')) {
            $lang_dir = DIR_CATALOG.'language/';
        } else {
            $lang_dir = DIR_LANGUAGE;
        }

        $languages = new Finder();
        $languages->directories()->in($lang_dir)->exclude('override')->depth('== 0');

        $data = array();

        foreach ($languages as $language) {
            $data[] = $language->getRelativePathname();
        }

        return $data;
    }

	public function getStrings($filter_data = array()) {
        $temp_data		= array();
        $folders		= array();
        $paths			= array();
        $lang_dir		= DIR_LANGUAGE;
        $filter_path	= ( !empty( $filter_data['filter_path'] ) ? $filter_data['filter_path'] : false );
        $filter_folder	= ( !empty( $filter_data['filter_folder'] ) ? $filter_data['filter_folder'] : false );
        $dirs			= array();

        if ( empty( $filter_data['filter_client'] ) ) {
        	$dirs[] = DIR_CATALOG . 'language/';
        } else {
        	switch( $filter_data['filter_client'] )
        	{
        		case '*':
        			$dirs = array(
	        		 	DIR_CATALOG . 'language/',
					 	DIR_LANGUAGE
					);
					break;

				case 'catalog':
					$dirs[] = DIR_CATALOG . 'language/';
					break;

				case 'admin':
					$dirs[] = DIR_LANGUAGE;
					break;
        	}
        }

        if (isset($filter_data['filter_client']) and ($filter_data['filter_client'] != 'admin')) {
            $lang_dir = DIR_CATALOG . 'language/';
        }

        if( $filter_path == '..' ) {
    		$filter_path = 'default.php';
    	}

    	if( $filter_folder == '..' ) {
    		$filter_folder = 'default.php';
    	}

        $files = new Finder();

        foreach( $dirs as $dir ) {
        	$files->files()->in($dir)->exclude('override');

			foreach ($files as $file) {
	            // example: english/catalog/attribute.php
	            $path_name = str_replace('\\', '/', $file->getRelativePathname());

	            $temp = explode('/', $path_name);

	            $folders[]	= ( ( $temp[1] != 'default.php' ) ? $temp[1] : '..' );
				$paths[]	= ( ( $temp[1] != 'default.php' ) ? $temp[1] : '..' ) . ( !empty( $temp[2] ) ? '/' . $temp[2] : '' );


	            if (isset($filter_data['filter_language']) and isset($temp[0]) and ($filter_data['filter_language'] != $temp[0])) {
	                continue;
	            }

	            if ( $filter_folder && !empty( $temp[1] ) ) {
	            	if( $filter_folder != $temp[1] ) {
	            		continue;
	            	}
	            }

	            if ( $filter_path && !empty( $temp[1] ) ) {
	            	$path = $temp[1] . ( !empty( $temp[2] ) ? '/' . $temp[2] : '' );

					if ( $filter_path != $path ) {
	            		continue;
	            	}
	            }

				if ( file_exists( $dir . $path_name ) ) {
		            require( $dir . $path_name );

		            $override_file = $dir . 'override/' . $path_name;
		            if ( file_exists( $override_file ) ) {
		                require( $override_file );
		            }

		            if ( empty( $_ ) ) {
		                continue;
		            }

		            // Prepare the array key
		            $key = str_replace( '/', '_', $path_name );
		            $key = str_replace( '.php', '', $key );
		            $key = str_replace( array( DIR_ROOT, 'language', '/', '\\' ), '', $dir ) . '_' . $key;

		            if (isset($filter_data['filter_text'])) {
		                $_temp = array();

		                foreach ($_ as $var => $val) {
		                    if (!stristr($val, $filter_data['filter_text']) && !stristr($var, $filter_data['filter_text']) ) {
		                        continue;
		                    }

		                    $_temp[$var] = $val;
		                }

		                if (empty($_temp)) {
		                    continue;
		                }

		                $temp_data[$key] = $_temp;
		            } else {
		                $temp_data[$key] = $_;
		            }

		            unset($_);
				}
	        }
		}

        // Substract the first dimension count
        $total		= count($temp_data, COUNT_RECURSIVE) - count($temp_data);
        $data		= array();
        $counter	= 0;

        foreach ($temp_data as $key => $strings) {
            $data[$key] = array();

            foreach ($strings as $var => $val) {
                ++$counter;

                if ($counter < $filter_data['start']) {
                    continue;
                }

                if ($counter > ($filter_data['start'] + $filter_data['limit'])) {
                    break 2;
                }

                $data[$key][$var] = htmlspecialchars($val);
            }
        }

		$folders = array_unique( $folders );
		asort( $folders );

		$paths = array_unique( $paths );
		asort( $paths );

        return array($data, $total, $folders, $paths);
	}

	/**
	 * save edited language strings in new file (folder override)
	 * @param array		$files
	 * @return int
	 */
    public function saveStrings($files) {
        if (empty($files)) {
            return;
        }

        // lstrings[catalog_en-GB_catalog_attribute][heading_title]
        foreach ($files as $file => $strings) {
            if (empty($strings)) {
                continue;
            }

            // Lets build the path
            $temp		= explode( '_', $file );
			$lang_dir	= DIR_ROOT . array_shift( $temp ) . '/language/';
            $path		= $temp[0] . '/' . $temp[1] . ( !empty( $temp[2] ) ? '/' . $temp[2] : '' );

            if ( !empty( $temp[3] ) ) {
                $path .= '_' . $temp[3];
            }

            if ( !empty( $temp[4] ) ) {
                $path .= '_' . $temp[4];
            }

            $path .= '.php';

            // Make sure the original file exists
            $org_file = $lang_dir . $path;

            if (!file_exists($org_file)) {
                continue;
            }

            require($org_file);

            $org_strings = $_;
            unset($_);

            // Load the override if available, to allow return back to the original
            $ovr_file = $lang_dir . 'override/' . $path;

            if (file_exists($ovr_file)) {
                require($ovr_file);

                $ovr_strings = $_;
                unset($_);
            }

            $new_strings = array();

            foreach ($strings as $key => $value) {
                if (!isset($org_strings[$key])) {
                    continue;
                }

                if (($org_strings[$key] == html_entity_decode($value))) {
                    // Remove from overrides if it's the same as original
                    if (isset($ovr_strings[$key])) {
                        unset($ovr_strings[$key]);
                    }

                    continue;
                }

                $new_strings[$key] = html_entity_decode($value);
            }

            if (!empty($ovr_strings)) {
                $new_strings = array_merge($ovr_strings, $new_strings);
            }

            // Do we have new strings?
            if (empty($new_strings)) {
                // Delete the override file if the changes have been reverted to original
                if (file_exists($ovr_file)) {
                    $this->filesystem->remove($ovr_file);
                }

                continue;
            }

            // Prepare the content
            $content = '<?php' . "\n";
            foreach ($new_strings as $key => $value) {
                $content .= '$_[\'' . $key . '\'] = \'' . $value . '\';' . "\n";
            }

            // Write into the file
            $this->filesystem->dumpFile($ovr_file, rtrim( $content, "\n" ), 0644);

            return count( $new_strings );
        }
    }
}
