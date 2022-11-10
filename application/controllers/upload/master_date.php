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
class master_date extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('upload_m');
    }

    function index() {
        $data = $this->upload_m->master_date();
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
                $filename = "m_date_" . date("Ymd_Hi") . '.xls';
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
            $data = $this->upload_m->master_date();
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
        $aStringHeader = array("PERNR", "BEGDA","ENDDA", "TanggalMasuk", "TanggalPegTetap","TanggalPensiun");
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $aHeader = array();
        while (true) {
            if ($col > 5) {
                break;
            }
            $text = $this->reader->sheets[0]['cells'][$header][$col];
            if ($col > 5 && !empty($text) || $text != $aStringHeader[$col - 1]) {
                return "<b>ERROR</b> : <br/><br/>" . "Header Mismatch";
            }
            $col++;
        }
//        if ($MAX_ROW == $col) {
//            return "<b>ERROR</b> : <br/><br/>" . "Does not have data";
//        }
        $sError = "";
        $aInput = array();
        $this->load->model('upload_m');
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            $aData['PERNR'] = $this->reader->sheets[0]['cells'][$i][1];
            $aData['BEGDA'] = $this->reader->sheets[0]['cells'][$i][2];
            $aData['ENDDA'] = $this->reader->sheets[0]['cells'][$i][3];
            $aData['TanggalMasuk']  = "";
            if(isset($this->reader->sheets[0]['cells'][$i][4])){
                $aData['TanggalMasuk'] = $this->reader->sheets[0]['cells'][$i][4];
            }
            $aData['TanggalPegTetap'] = "";
            if(isset($this->reader->sheets[0]['cells'][$i][5])){
                $aData['TanggalPegTetap'] = $this->reader->sheets[0]['cells'][$i][5];
            }
            $aData['TanggalPensiun']  = "";
            if(isset($this->reader->sheets[0]['cells'][$i][6])){
                $aData['TanggalPensiun'] = $this->reader->sheets[0]['cells'][$i][6];
            }
            $aMandatory = array("PERNR","BEGDA","ENDDA");
            foreach($aMandatory as $mandatory){
                if(empty($aData[$mandatory])){
                    $sError.=$mandatory." empty @ row $i<br/>";
                }
            }
            //pengecekan error 
//            if (empty($pernr))
//                $sError.="PERNR empty @ row $i<br/>";
//            if (empty($begda))
//                $sError.="BEGDA empty @ row $i<br/>";
//            if (empty($begda))
//                $sError.="ENDDA empty @ row $i<br/>";
//            if (empty($tglMasuk))
//                $sError.="TanggalMasuk empty @ row $i<br/>";
//            if (empty($tglPegTetap))
//                $sError.="TanggalPegTetap empty @ row $i<br/>";
//            if (empty($tglPensiun))
//                $sError.="TanggalPensiun empty @ row $i<br/>";
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        $this->load->model('employee_m');
        $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr><th>PERNR</th><th>BEGDA</th><th>ENDDA</th><th>TanggalMasuk</th><th>TanggalPegTetap</th><th>TanggalPensiun</th></tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $a = $aInput[$i];
            $this->employee_m->emp_date_new($a);
            $sReturn.="<tr><td>".$a['PERNR']."</td><td>".$a['BEGDA']."</td><td>".$a['ENDDA']."</td><td>".$a['TanggalMasuk']."</td><td>".$a['TanggalPegTetap']."</td><td>".$a['TanggalPensiun']."</td></tr>";
        }
        $sReturn.="</table>";
        return "Success.<br/>".$sReturn;
    }

}

?>
