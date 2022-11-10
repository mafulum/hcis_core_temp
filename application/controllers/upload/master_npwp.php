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
class master_npwp extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('upload_m');
        $data = $this->upload_m->master_emp_npwp();
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
                $filename = "m_emp_npwp_" . date("Ymd_Hi") . '.xls';
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
            $this->load->model('upload_m');
            $data = $this->upload_m->master_emp_npwp();
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
        $aStringHeader = array("PERNR","BEGDA","ENDDA", "TAXID", "RDATE","MARRD","SPBEN","DEPND","NOTE");
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $aDEPND = $this->global_m->get_abbrev_keyval(21);
        $aFlag=array("1","0");
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
            $aData['TAXID'] = $this->reader->sheets[0]['cells'][$i][4];
            $aData['RDATE'] = null;
            if(isset($this->reader->sheets[0]['cells'][$i][5])){
                $aData['RDATE'] = $this->reader->sheets[0]['cells'][$i][5];
            }
            $aData['MARRD'] = null;
            if(isset($this->reader->sheets[0]['cells'][$i][6])){
                $aData['MARRD'] = $this->reader->sheets[0]['cells'][$i][6];
            }
            $aData['SPBEN'] = null;
            if(isset($this->reader->sheets[0]['cells'][$i][7])){
                $aData['SPBEN'] = $this->reader->sheets[0]['cells'][$i][7];
            }
            $aData['DEPND'] = $this->reader->sheets[0]['cells'][$i][8];
            $aData['NOTE'] = null;
            if(isset($this->reader->sheets[0]['cells'][$i][9])){
                $aData['NOTE'] = addslashes($this->reader->sheets[0]['cells'][$i][9]);
            }
            //pengecekan error 
            $aMandatory = array("PERNR","BEGDA","ENDDA","DEPND");
            foreach($aMandatory as $mandatory){
                if(empty($aData[$mandatory])){
                    $sError.=$mandatory." empty @ row $i<br/>";
                }
            }
            //SUBTY
            if(!empty($aData['DEPND']) && empty($aDEPND[$aData['DEPND']])){
                $sError.=$aData['DEPND'] . " DEPND Unknown,please refer for RULES sheet @row $i<br/>";                
            }

            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        
        $this->load->model('pa_payroll/npwp_m');
        $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr>";
        foreach($aStringHeader as $header){
            $sReturn.="<td>".$header."</td>";
        }
        $sReturn.="</tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $this->npwp_m->personal_npwp_new($aInput[$i]);
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
