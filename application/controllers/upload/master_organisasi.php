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
class master_organisasi extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('upload_m');
        $data = $this->upload_m->master_organisasi();
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
                $filename = "m_emp_" . date("Ymd_Hi") . '.xls';
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
            $data = $this->upload_m->master_emp();
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
        $aStringHeader = array("NIK", "CNAME", "ORGEH", "GESCH", "GBDAT", "GBLND");
        $aOrgeh = array("10100000" => "PIHC", "10200000" => "PKG", "10300000" => "PKT", "10400000" => "PKC", "10500000" => "PSP", "10600000" => "PIM", "10700000" => "REKIND", "10800000" => "ME");
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $aHeader = array();
        while (true) {
            if ($col > 6) {
                break;
            }
            $text = $this->reader->sheets[0]['cells'][$header][$col];
            if ($col > 6 && !empty($text) || $text != $aStringHeader[$col - 1]) {
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
            $aData['NIK'] = $nik = $this->reader->sheets[0]['cells'][$i][1];
            $aData['CNAME'] = $cname = addslashes($this->reader->sheets[0]['cells'][$i][2]);
            $aData['ORGEH'] = $orgeh = $this->reader->sheets[0]['cells'][$i][3];
            $aData['GESCH'] = $gesch = $this->reader->sheets[0]['cells'][$i][4];
            $aData['GBDAT'] = $gbdat = $this->reader->sheets[0]['cells'][$i][5];
            $aData['GBLND'] = $gblnd = addslashes($this->reader->sheets[0]['cells'][$i][6]);
            //pengecekan error 
            if (empty($nik))
                $sError.="NIK empty @ row $i<br/>";
            if (empty($cname))
                $sError.="CNAME empty @ row $i<br/>";
            if (empty($orgeh))
                $sError.="ORGEH empty @ row $i<br/>";
            if (empty($gesch))
                $sError.="GESCH empty @ row $i<br/>";
            if (empty($gbdat))
                $sError.="GBDAT empty @ row $i<br/>";
            if (empty($gblnd))
                $sError.="GBLND empty @ row $i<br/>";
            //NIK
            $fNIK = $this->upload_m->get_flag_mapping_pernr($nik);
            if (!$fNIK) {
                $sError.=$nik . " Already Exist @row $i<br/>";
            }
            //gesch
            if (empty($aOrgeh[$orgeh])) {
                $sError.=$fNIK . " ORGEH Unknown,please refer for master datasheet or template Exist @row $i<br/>";
            }
            //gesch
            if (!($gesch == 1 || $gesch == 2)) {
                $sError.=$fNIK . " GESCH Unknown,GESCH are 1 for male or 2 for female  Exist @row $i<br/>";
            }
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        $this->load->model('employee_m');
        $this->load->model('orgchart_m');
        $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr><th>PERNR</th><th>NIK</th><th>CNAME</th><th>ORGEH</th><th>GESCH</th><th>GBDAT</th><th>GBLND</th></tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            //get pernr
            $configShort = $aOrgeh[$aInput[$i]['ORGEH']];
            //insert mapping_pernr
            $n_pernr = $this->employee_m->add_new_employee($aInput[$i]['ORGEH'], $aInput[$i]['NIK'], $aInput[$i]['GBDAT'], '9999-12-31');
            //insert master_emp
            $a = $aInput[$i];
            $nik=$a['NIK'];
            unset($a['NIK']);
            unset($a['ORGEH']);
            $a['PERNR']=$n_pernr;
            $a['BEGDA'] = $a['GBDAT'];
            $a['ENDDA'] = '9999-12-31';
            $this->employee_m->personal_data_new($a);
            $sReturn.="<tr><td>".$n_pernr."</td><td>".$nik."</td><td>".$a['CNAME']."</td><td>".$configShort."</td><td>".$a['GESCH']."</td><td>".$a['GBDAT']."</td><td>".$a['GBLND']."</td></tr>";
        }
        $sReturn.="</table>";
        return "Success.<br/>".$sReturn;
    }

}

?>
