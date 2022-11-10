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
class master_ach extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('upload_m');
        $data = $this->upload_m->master_ach();
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
                $filename = "m_ach_" . date("Ymd_Hi") . '.xls';
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
            $data = $this->upload_m->master_ach();
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
        $aStringHeader = array("PERNR", "BEGDA", "AWDTP", "TEXT1");
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $aHeader = array();
        while (true) {
            if ($col > 4) {
                break;
            }
            $text = $this->reader->sheets[0]['cells'][$header][$col];
            if ($col > 4 && !empty($text) || $text != $aStringHeader[$col - 1]) {
                return "<b>ERROR</b> : <br/><br/>" . "Header Mismatch";
            }
            $col++;
        }
        if ($MAX_ROW == $col) {
            return "<b>ERROR</b> : <br/><br/>" . "Does not have data";
        }
        $sError = "";
        $aInput = array();
        $this->load->model('upload_m');
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            $aData['PERNR'] = $pernr = $this->reader->sheets[0]['cells'][$i][1];
            $aData['BEGDA'] = $begda = $this->reader->sheets[0]['cells'][$i][2];
            $aData['AWDTP'] = $awdtp = $this->reader->sheets[0]['cells'][$i][3];
            $aData['TEXT1'] = $text1 = addslashes($this->reader->sheets[0]['cells'][$i][4]);
            //pengecekan error 
            if (empty($pernr))
                $sError.="PERNR empty @ row $i<br/>";
            if (empty($begda))
                $sError.="BEGDA empty @ row $i<br/>";
            if (empty($awdtp))
                $sError.="AWDTP empty @ row $i<br/>";
            if (empty($text1))
                $sError.="TEXT1 empty @ row $i<br/>";
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        $this->load->model('employee_m');
        $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr><th>PERNR</th><th>BEGDA</th><th>AWDTP</th><th>TEXT1</th></tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $a = $aInput[$i];
            $this->employee_m->emp_awards_new($a);
            $sReturn.="<tr><td>".$a['PERNR']."</td><td>".$a['BEGDA']."</td><td>".$a['AWDTP']."</td><td>".$a['TEXT1']."</td></tr>";
        }
        $sReturn.="</table>";
        return "Success.<br/>".$sReturn;
    }

}

?>
