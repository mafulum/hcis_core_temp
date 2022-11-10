<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of FPDF
 *
 * @author mm
 */
class FPDFlib {
    //put your code here
    function __construct() {
        $file_url = __FILE__;
        $library_dir = str_replace('\\','/', $file_url);
        $library_dir = substr($library_dir, 0, strrpos($library_dir, '/'));
        set_include_path(get_include_path() . PATH_SEPARATOR . $library_dir . '/fpdf/');
        require_once($library_dir . '/fpdf/fpdf.php');
    }
}
