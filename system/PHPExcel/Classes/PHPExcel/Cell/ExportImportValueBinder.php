<?php
/**
 * PHPExcel
 *
 * This file is Copyright (c) 2015 J.Neuhoff - mhccorp.com
 * PHPExcel is  Copyright (c) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Cell
 * @copyright  Copyright (c) 2015 J.Neuhoff - mhccorp.com
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    PHPExcel version 1.8.0, 2014-03-02
 */


/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
    /**
     * @ignore
     */
    define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}


/**
 * PHPExcel_Cell_ExportImportValueBinder
 *
 * @category   PHPExcel
 * @package    PHPExcel_Cell
 * @copyright  Copyright (c) 2015 J.Neuhoff - mhccorp.com
 */
class PHPExcel_Cell_ExportImportValueBinder extends PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder 
{ 

	/**
	* Bind value to a cell, preserving possible leading zeros
	* See http://stackoverflow.com/questions/12457610/reading-numbers-as-text-format-with-phpexcel
	*
	* @param  PHPExcel_Cell  $cell   Cell to bind value to
	* @param  mixed          $value  Value to bind in cell
	* @return boolean
	*/
	public function bindValue(PHPExcel_Cell $cell, $value = null) { 
		// sanitize UTF-8 strings 
		if (is_string($value)) { 
			$value = PHPExcel_Shared_String::SanitizeUTF8($value); 
		} 

		// Preserve numeric string, including leading zeros, if it is a text format
		$format = $cell->getStyle()->getNumberFormat()->getFormatCode();
		if ($format == PHPExcel_Style_NumberFormat::FORMAT_TEXT) {
			$cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING); 
			return true; 
		}

		// Not bound yet? Use default value parent... 
		return parent::bindValue($cell, $value); 
	} 
}
