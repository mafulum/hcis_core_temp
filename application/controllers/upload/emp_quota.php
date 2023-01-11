<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author Garuda
 */
class emp_quota extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('pa/emp_quota_m');
    }

    function index() {
        $data = $this->emp_quota_m->reff_upload_page();
        $this->load->view('upload/main', $data);
    }

    function upload() {
        $sError = "<b>ERROR</b> : <br/><br/>" . "Empty File";
        if (!empty($_FILES['userfile']) && isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])) {
            $sError = "";
            $filename = "";
            $dir = "mass_upload/";
            $len = strlen($_FILES['userfile']['name']);
            $ext = substr($_FILES['userfile']['name'], $len - 3, 3);
            //pengecekan extension
            if ($ext != "xls") {
                $sError = "<b>ERROR</b> : <br/><br/>" . "Extension file must be xls";
            }
            if (empty($sError)) {
                $filename = "m_emp_quota_" . date("Ymd_Hi") . '.xls';
                //copy file
                $this->load->library('upload', array('upload_path' => $dir, 'overwrite' => true, 'allowed_types' => 'xls', 'remove_spaces' => true, 'file_name' => $filename));
                $resUpload = $this->upload->do_upload('userfile');
                if ($resUpload == false) {
                    $sError = $this->upload->display_errors();
                }
            }
            if (empty($sError)) {
                $sError = $this->load($dir . $filename);
            }
            $data = $this->emp_quota_m->reff_upload_page();
            $data['sError'] = $sError;
            $this->load->view('upload/main', $data);
        }
    }

    function load($filename) {
        if (!empty($this->reader)) {
            return "<b>ERROR</b> : <br/><br/>" . "Library Error";
        }
        if (!file_exists($filename)) {
            return "<b>ERROR</b> : <br/><br/>" . "file does not exist";
        }
        $this->load->library('reader');
        $this->reader->setOutputEncoding('CP1251');
        $this->reader->read($filename);
        //HEADER
        $header = 1;
        $col = 1;
        $aStringHeader = array("PERNR","BEGDA","ENDDA","SUBTY", "QUOTA","NOTE");
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        foreach($aStringHeader as $headerName){
            $text = $this->reader->sheets[0]['cells'][$header][$col];
            if (!empty($text) && $text != $headerName) {
                return "<b>ERROR</b> : <br/><br/>" . "Header Mismatch ".$headerName ."|".$text;
            }
            $col++;            
        }
//        if ($MAX_ROW == $col) {
//            return "<b>ERROR</b> : <br/><br/>" . "Does not have data";
//        }

        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $sError = "";
        $aInput = array();
        $aMap = array();
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            $aData['PERNR'] = $this->reader->sheets[0]['cells'][$i][1];
            $aData['BEGDA'] = $this->reader->sheets[0]['cells'][$i][2];
            $aData['ENDDA'] = $this->reader->sheets[0]['cells'][$i][3];
            $aData['SUBTY'] = $this->reader->sheets[0]['cells'][$i][4];
            $aData['QUOTA'] = $this->reader->sheets[0]['cells'][$i][5];
            $aData['NOTE'] = null;
            if(isset($this->reader->sheets[0]['cells'][$i][6])){
                $aData['NOTE'] = $this->reader->sheets[0]['cells'][$i][6];
            }
            //pengecekan error 
            $aMandatory = array("PERNR","BEGDA","ENDDA","SUBTY","QUOTA");
            foreach($aMandatory as $mandatory){
                if(empty($aData[$mandatory])){
                    $sError.=$mandatory." empty @ row $i<br/>";
                }
            }
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        
         $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr>";
        foreach($aStringHeader as $header){
            $sReturn.="<td>".$header."</td>";
        }
        $sReturn.="</tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $this->emp_quota_m->emp_quota_new($aInput[$i]);
            $sReturn.="<tr>";
            foreach($aStringHeader as $header){
                $sReturn.="<td>".$aInput[$i][$header]."</td>";
            }
            $sReturn.="</tr>";
        }
        $sReturn.="</table>";
        return "Success.<br/>".$sReturn;
    }

}

?>
