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
class master_posisi extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('upload_m');
        $data = $this->upload_m->master_posisi();
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
//            echo $len;
//            echo "<br/>";
//            echo $ext;exit;
            //pengecekan extension
            if ($ext != "xls") {
                $sError = "<b>ERROR</b> : <br/><br/>" . "Extension file must be xls";
            }
            if (empty($sError)) {
                $filename = "org_pos_" . date("Ymd_His") . '.xls';
                //copy file
                $this->load->library('upload', array('upload_path' => $dir, 'overwrite' => true, 'allowed_types' => 'xls', 'remove_spaces' => true, 'file_name' => $filename));
                $resUpload = $this->upload->do_upload('userfile');
                if ($resUpload == false) {
//                    var_dump($_FILES);exit;
                    $sError = $this->upload->display_errors();
                }
            }
            if (empty($sError)) {
                $sError = $this->load($dir . $filename);
            }
            $this->load->model('upload_m');
            $data = $this->upload_m->master_posisi();
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
        $aStringHeader = array("PARENT_ORG_ID", "SHORT", "STEXT","BEGDA","ENDDA");
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
        $this->load->model('orgchart_m');
        $aMap = array();
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            if(empty($this->reader->sheets[0]['cells'][$i][1])){
                continue;;
            }
            $aData['PARENT_ORG_ID'] = $parent_org_id = $this->reader->sheets[0]['cells'][$i][1];
            $aData['SHORT'] = $short = "";
            if(isset($this->reader->sheets[0]['cells'][$i][2])){
                $aData['SHORT'] = $short = $this->reader->sheets[0]['cells'][$i][2];
            }
            $aData['STEXT'] = $stext = addslashes($this->reader->sheets[0]['cells'][$i][3]);
            $aData['BEGDA'] = $short = $this->reader->sheets[0]['cells'][$i][4];
            $aData['ENDDA'] = $short = $this->reader->sheets[0]['cells'][$i][5];
            $aData['PRIOX'] = "";
            //pengecekan error 
            if (empty($parent_org_id))
                $sError.="Parent Organisation ID empty @ row $i<br/>";
            if (empty($stext))
                $sError.="STEXT empty @ row $i<br/>";
            if( isset($aMap[$parent_org_id]) || $this->orgchart_m->check_org_id($parent_org_id)){
                $aMap[$parent_org_id]=1;
            }else{
                $aMap[$parent_org_id]=2;
                $sError.="Parent Organisation not found @ row $i<br/>";
            }
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        $this->load->model('orgchart_m');
        $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr><th>PARENT ORG_ID</th><th>Position ID</th><th>SHORT</th><th>STEXT</th><th>BEGDA</th><th>ENDDA</th></tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $id = $this->orgchart_m->pos_upd(-1,$aInput[$i]['PARENT_ORG_ID'], $aInput[$i]);
            $sReturn.="<tr><td>".$aInput[$i]['PARENT_ORG_ID']."</td><td>".$id."</td><td>".$aInput[$i]['SHORT']."</td><td>".$aInput[$i]['STEXT']."</td><td>".$aInput[$i]['BEGDA']."</td><td>".$aInput[$i]['ENDDA']."</td></tr>";
        }
        $sReturn.="</table>";
        return "Success.<br/>".$sReturn;
    }

}

?>
