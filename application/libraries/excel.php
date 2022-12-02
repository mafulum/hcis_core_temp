<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/**
 * Description of excel
 *
 * @author wonk
 */
class Excel {
    //put your code here
    // public variable to store an instance of the PHPExcel object
    var $workbook = '';

    // class constructor
    function __construct()
    {
        $file_url = __FILE__;
        $library_dir = str_replace('\\','/', $file_url);
        $library_dir = substr($library_dir, 0, strrpos($library_dir, '/'));
        set_include_path(get_include_path() . PATH_SEPARATOR . $library_dir . '/phpexcel/');
        require_once($library_dir . '/phpexcel/PHPExcel.php');
        require_once('PHPExcel/IOFactory.php');
        return $this->new_workbook();

    } // end constructor

    // new workbook function
    function new_workbook() {

        // reset the workbook to a new object
        unset($this->workbook);
        $this->workbook = new PHPExcel();

        if(isset($this->workbook)) {
            return TRUE;
        } else {
            return FALSE;
        }

    } // end function to start a new workbook

    // function to output the file
    function send_to_browser($filename = "workbook.xls", $format = 'Excel5'){

        // load the appropriate IO Factory writer
        $objWriter = PHPExcel_IOFactory::createWriter($this->workbook, $format);

        // output the appropriate headers
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header("Content-Disposition: attachment;filename={$filename}");
        header('Content-Transfer-Encoding: binary');

        // output the file
        $objWriter->save('php://output');

        return; // end processing
    }  
}
?>
