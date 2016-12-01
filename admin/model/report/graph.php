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

class ModelReportGraph extends Model
{
    protected $null_array = array();

    public function download($data, $title)
    {
        global $registry;

        $registry = $this->registry;

        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');

        $cwd = getcwd();

        chdir(DIR_SYSTEM . 'PHPExcel');

        require_once('Classes/PHPExcel.php');

        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_ExportImportValueBinder());

        chdir($cwd);

        if ($this->config->get('export_import_settings_use_export_cache')) {
            $cacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;

            $cacheSettings = array('memoryCacheSize' => '16MB');

            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        }

        try {
            set_time_limit(1800);

            $workbook = new PHPExcel();

            $workbook->getDefaultStyle()->getFont()->setName('Arial');
            $workbook->getDefaultStyle()->getFont()->setSize(10);
            $workbook->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $workbook->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $workbook->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);

            $worksheet_index = 0;

            $workbook->setActiveSheetIndex($worksheet_index++);

            $worksheet = $workbook->getActiveSheet();

            $worksheet->setTitle($title);

            $box_format = array(
                'fill' => array(
                    'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F0F0F0')
                )
            );

            $text_format = array(
                'numberformat' => array(
                    'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
                )
            );

            $this->worksheet($worksheet, $box_format, $text_format, $data);

            $worksheet->freezePaneByColumnAndRow(1, 2);

            $workbook->setActiveSheetIndex(0);

            $datetime = date('Y-m-d');

            $filename = str_replace(' ', '', $title) . '-' . $datetime . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

            $objWriter->setPreCalculateFormulas(false);

            $objWriter->save('php://output');

            $this->clearSpreadsheetCache();

            exit;
        } catch (Exception $e) {
            $errstr  = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();

            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    protected function worksheet(&$worksheet, &$box_format, &$text_format, $rows)
    {
        // Set the column widths
        $j = 0;

        foreach ($rows[0] as $key => $value) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen($key) + 1);
        }

        // The heading row and column styles
        $styles = array();
        $data   = array();

        $i = 1;
        $j = 0;

        foreach ($rows[0] as $key => $value) {
            $data[$j++] = $key;
        }

        $worksheet->getRowDimension($i)->setRowHeight(30);

        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual categories data
        $i += 1;
        $j = 0;

        foreach ($rows as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(26);

            $data = array();

            foreach ($row as $key => $value) {
                $data[$j++] = $value;
            }

            $this->setCellRow($worksheet, $i, $data, $this->null_array, $styles);

            $i += 1;
            $j = 0;
        }
    }

    protected function setCellRow($worksheet, $row, $data, &$default_style = null, &$styles = null)
    {
        if (!empty($default_style)) {
            $worksheet->getStyle("$row:$row")->applyFromArray($default_style, false);
        }

        if (!empty($styles)) {
            foreach ($styles as $col => $style) {
                $worksheet->getStyleByColumnAndRow($col, $row)->applyFromArray($style, false);
            }
        }

        $worksheet->fromArray($data, null, 'A' . $row, true);
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

}
