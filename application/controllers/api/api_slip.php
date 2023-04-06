<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin
 *
 * @author Garuda
 */
class api_slip extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }
    
    public function list_year($nopeg){
        $dir    = getcwd().'/payslip/'.$nopeg;
        if(is_dir($dir)==false){
            echo json_encode([date('Y')]);
        }else{
            $files = scandir($dir);
            $aRes = [];
            foreach($files as $name){
                if(strlen($name)==4 && is_numeric($name)){
                    $aRes[]=$name;
                }
            }
            echo json_encode($aRes);
        }        
    }
    
    public function list_name($nopeg,$year){
        $dir    = getcwd().'/payslip/'.$nopeg."/".$year;
        $files = scandir($dir);
        $aRes = [];
        foreach($files as $name){
            if(strlen($name)>2){
                $aRes[]= str_replace(".pdf","", $name);
            }
        }
        echo json_encode($aRes);
        
    }
    
    public function get_object_slip($nopeg,$year,$name){
        $filename    = getcwd().'/payslip/'.$nopeg."/".$year."/".htmlspecialchars_decode($name).".pdf";
        $obj="";
        if(is_file($filename)){
            $file_contents = file_get_contents($filename); 
            $obj= base64_encode($file_contents);
        }else{
            die($filename);
        }
        echo json_encode(["obj"=>$obj]);
    }

}

?>
