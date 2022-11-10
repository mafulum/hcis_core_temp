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
class master_eduf extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('upload_m');
    }

    function index() {
        $data = $this->upload_m->master_eduf();
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
                $filename = "m_eduf_" . date("Ymd_Hi") . '.xls';
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
            $data = $this->upload_m->master_eduf();
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
        $aStringHeader = array("PERNR", "BEGDA", "ENDDA", "SLART", "INSTI", "SLTP1", "SLABS", "SLAND", "EMARK");
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $aHeader = array();
        while (true) {
            if ($col > 9) {
                break;
            }
            $text = $this->reader->sheets[0]['cells'][$header][$col];
            if ($col > 9 && !empty($text) || $text != $aStringHeader[$col - 1]) {
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
            $aData['PERNR'] = $pernr = $this->reader->sheets[0]['cells'][$i][1];
            $aData['BEGDA'] = $begda = $this->reader->sheets[0]['cells'][$i][2];
            $aData['ENDDA'] = $endda = $this->reader->sheets[0]['cells'][$i][3];
            $aData['SLART'] = $slart = $this->reader->sheets[0]['cells'][$i][4];
            $aData['AUSBI'] = $ausbi = 'FORMAL';
            $aData['INSTI'] = $insti = addslashes($this->reader->sheets[0]['cells'][$i][5]);
            $aData['SLTP1'] = $sltp1 = addslashes($this->reader->sheets[0]['cells'][$i][6]);
            $aData['SLABS'] = $slabs = $this->reader->sheets[0]['cells'][$i][7];
            $aData['SLAND'] = $sland = addslashes($this->reader->sheets[0]['cells'][$i][8]);
            $aData['EMARK'] = $emark = addslashes($this->reader->sheets[0]['cells'][$i][9]);
            //pengecekan error 
            if (empty($pernr))
                $sError.="PERNR empty @ row $i<br/>";
            if (empty($begda))
                $sError.="BEGDA empty @ row $i<br/>";
            if (empty($endda))
                $sError.="ENDDA empty @ row $i<br/>";
            if (empty($slart))
                $sError.="SLART empty @ row $i<br/>";
            if (empty($insti))
                $sError.="INSTI empty @ row $i<br/>";
            if (empty($sltp1))
                $sError.="SLTP1 empty @ row $i<br/>";
            if (empty($slabs))
                $sError.="SLABS empty @ row $i<br/>";
            if (empty($sland))
                $sError.="SLAND empty @ row $i<br/>";
            if (empty($emark))
                $sError.="EMARK empty @ row $i<br/>";
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        $this->load->model('employee_m');
        $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr><th>PERNR</th><th>BEGDA</th><th>ENDDA</th>
            <th>SLART</th><th>AUSBI</th><th>INSTI</th><th>SLTP1</th><th>SLABS</th><th>SLAND</th><th>EMARK</th></tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $a = $aInput[$i];
            $this->employee_m->emp_eduf_new($a);
            $sReturn.="<tr><td>".$a['PERNR']."</td><td>".$a['BEGDA']."</td><td>".$a['ENDDA']."</td><td>".$a['SLART']."</td><td>".$a['AUSBI']."</td>
                <td>".$a['INSTI']."</td><td>".$a['SLTP1']."</td><td>".$a['SLABS']."</td><td>".$a['SLAND']."</td><td>".$a['EMARK']."</td></tr>";
        }
        $sReturn.="</table>";
        return "Success.<br/>".$sReturn;
    }

}

?>
