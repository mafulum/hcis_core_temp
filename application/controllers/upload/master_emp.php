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
class master_emp extends CI_Controller {

    //put your code herek
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('upload_m');
        $data = $this->upload_m->master_emp();
        $this->load->view('upload/main', $data);
    }

    function upload() {
        set_time_limit(0);
        ini_set('memory_limit','512M'); 
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
        

        $aStringHeader = array("PERNR","BEGDA","ENDDA","CNAME","GESCH","GBDAT","GBLND","RECRUIT_ID","EMPREF_ID","EMPREF_CODE","MARST","MARDT","RELG","GBCTY","GBNAT","IDENT","PERSG","PERSK","WERKS","BTRTL","ORGEH","PLANS","MASSN","ABKRS");
        $aGesch = $this->global_m->get_abbrev_keyval(2);
        $aPersg = $this->global_m->get_abbrev_keyval(3); 
        $aPersk = $this->global_m->get_abbrev_keyval(4); 
        $aWerks = $this->global_m->get_abbrev_keyobj(5); 
        $aMarst = $this->global_m->get_abbrev_keyval(23);
        $aRelgn = $this->global_m->get_abbrev_keyval(22);
        $aCty = $this->global_m->get_abbrev_keyval(17);
        $aNat = $this->global_m->get_abbrev_keyval(16);
        $aBtrtl = $this->global_m->get_abbrev_keyval(26);
        $aMassn = $this->global_m->get_abbrev_keyval(27);
        $aOrgMap = $this->global_m->get_OBJID_org_level();
        $aIDENT = array('WNI'=>"WNI",'WNA'=>'WNA');
        $aMapOrg = array();
        $aMapPos = array(); 
        $aOrgLevels=array();
        
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $aHeader = array();
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
        $sError = "";
        $aInput = array();
        $this->load->model('upload_m');        
        $this->load->model('employee_m');
        $this->load->model('orgchart_m');
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            $aData['PERNR'] = "";
            if(!empty($this->reader->sheets[0]['cells'][$i][1]) && !empty(trim($this->reader->sheets[0]['cells'][$i][1]))){
                $aData['PERNR'] = $this->reader->sheets[0]['cells'][$i][1];
            }
            if(empty($this->reader->sheets[0]['cells'][$i][2])){
                continue;
            }
            $aData['BEGDA'] = $begda = $this->reader->sheets[0]['cells'][$i][2];
            $aData['ENDDA'] = $endda = $this->reader->sheets[0]['cells'][$i][3];
            $aData['CNAME'] = $cname = addslashes($this->reader->sheets[0]['cells'][$i][4]);
            $aData['GESCH'] = $gesch = $this->reader->sheets[0]['cells'][$i][5];
            $aData['GBDAT'] = $gbdat = $this->reader->sheets[0]['cells'][$i][6];
            $aData['GBLND'] = $gblnd = "";
            if(isset($this->reader->sheets[0]['cells'][$i][7])){
                $aData['GBLND'] = $gblnd = addslashes($this->reader->sheets[0]['cells'][$i][7]);
            }
            
            $aData['RECRUIT_ID'] = $recruit_id = null;
            if(isset($this->reader->sheets[0]['cells'][$i][8])){
                $aData['RECRUIT_ID'] = $recruit_id = $this->reader->sheets[0]['cells'][$i][8];
            }
            $aData['EMPREF_ID'] = $empref_id = null;
            if(isset($this->reader->sheets[0]['cells'][$i][9])){
                $aData['EMPREF_ID'] = $empref_id = $this->reader->sheets[0]['cells'][$i][9];
            }
            $aData['EMPREF_CODE'] = $empref_code = null;
            if(isset($this->reader->sheets[0]['cells'][$i][10])){
                $aData['EMPREF_CODE'] = $empref_code = $this->reader->sheets[0]['cells'][$i][10];
            }
            
            $aData['MARST'] = $marst = $this->reader->sheets[0]['cells'][$i][11];
            $aData['MARDT'] = $mardt = null;
            if(isset($this->reader->sheets[0]['cells'][$i][12])){
                $aData['MARDT'] = $mardt = $this->reader->sheets[0]['cells'][$i][12];
            }
            $aData['RELG'] = $relg = $this->reader->sheets[0]['cells'][$i][13];
            $aData['GBCTY'] = $gbcty = $this->reader->sheets[0]['cells'][$i][14];
            $aData['GBNAT'] = $gbnat = $this->reader->sheets[0]['cells'][$i][15];
            $aData['IDENT'] = $ident = $this->reader->sheets[0]['cells'][$i][16];
            $aData['PERSG'] = $persg = $this->reader->sheets[0]['cells'][$i][17];
            $aData['PERSK'] = $persk = $this->reader->sheets[0]['cells'][$i][18];
            $aData['WERKS'] = $wersk = $this->reader->sheets[0]['cells'][$i][19];
            $aData['BTRTL'] = $btrtl = "";
            if(isset($this->reader->sheets[0]['cells'][$i][20])){
                $aData['BTRTL'] = $btrtl = $this->reader->sheets[0]['cells'][$i][20];
            }
            $aData['ORGEH'] = $orgeh = $this->reader->sheets[0]['cells'][$i][21];
            $aData['PLANS'] = $plans = $this->reader->sheets[0]['cells'][$i][22];
            $aData['MASSN'] = $massn = "";
            if(isset($this->reader->sheets[0]['cells'][$i][23])){
                $aData['MASSN'] = $massn = $this->reader->sheets[0]['cells'][$i][23];
            }
            $aData['ABKRS'] = $massn = $this->reader->sheets[0]['cells'][$i][24];
//            error_log("CHECK MANDATORY LOOP ".$i);
            $aMandatory = array('BEGDA','ENDDA','CNAME','GESCH','GBDAT','MARST','RELG','PERSG','PERSK','WERKS','ORGEH','PLANS');
            foreach($aMandatory as $mandatory){
                if(empty($aData[$mandatory])){
                    $sError.=$mandatory." empty @ row $i<br/>";
                }
            }
//            error_log("CHECK PERNR ".$i);
            //PERNR CHECK
            if(!empty($aData['PERNR'])){
                if ($this->upload_m->get_flag_mapping_pernr($aData['PERNR'])==FALSE) {
                    $sError.=$aData['PERNR'] . " Already Exist on application @row $i<br/>";
                }
            }
            
//            error_log("CHECK GESCH".$i); 
            //GESCH
            if(!empty($aData['GESCH']) && empty($aGesch[$aData['GESCH']])){
                $sError.=$aData['GESCH'] . " GESCH Unknown,please refer for RULES sheet @row $i<br/>";                
            }
            
//            error_log("CHECK MARST".$i);
            //MARST
            if(!empty($aData['MARST']) && empty($aMarst[$aData['MARST']])){
                $sError.=$aData['MARST'] . " MARST Unknown,please refer for RULES sheet @row $i<br/>";                
            }
            
//            error_log("CHECK RELG".$i);
            //RELG
            if(!empty($aData['RELG']) && empty($aRelgn[$aData['RELG']])){
                $sError.=$aData['RELG'] . " MARST Unknown,please refer for RULES sheet @row $i<br/>";                
            }
            
//            error_log("CHECK GBCTY".$i);
            //GBCTY
            if(!empty($aData['GBCTY']) && empty($aCty[$aData['GBCTY']])){
                $sError.=$aData['GBCTY'] . " GBCTY Unknown,please refer for RULES sheet @row $i<br/>";                
            }
            
//            error_log("CHECK GBNAT".$i);
            //GBNAT
            if(!empty($aData['GBNAT']) && empty($aNat[$aData['GBNAT']])){
                $sError.=$aData['GBNAT'] . " GBNAT Unknown,please refer for RULES sheet @row $i<br/>";                
            }
            
//            error_log("CHECK IDENT".$i);
            //IDENT
            if(!empty($aData['IDENT']) && empty($aIDENT[$aData['IDENT']])){
                $sError.=$aData['IDENT'] . " IDENT Unknown,please refer for RULES sheet @row $i<br/>";                
            }
//            error_log("CHECK PERSG".$i);
            //PERSG
            if(!empty($aData['PERSG']) && empty($aPersg[$aData['PERSG']])){
                $sError.=$aData['PERSG'] . " PERSG Unknown,please refer for RULES sheet @row $i<br/>";                
            }
//            error_log("CHECK PERSK".$i);
            //PERSK
            if(!empty($aData['PERSK']) && empty($aPersk[$aData['PERSK']])){
                $sError.=$aData['PERSK'] . " PERSK Unknown,please refer for RULES sheet @row $i<br/>";                
            }
//            error_log("CHECK WERKS".$i);
            //WERKS
            if(!empty($aData['WERKS']) && empty($aWerks[$aData['WERKS']])){
                $sError.=$aData['WERKS'] . " WERKS Unknown,please refer for RULES sheet @row $i<br/>";                
            }else if(!empty($aData['WERKS']) && !empty($aWerks[$aData['WERKS']]) && !empty($aWerks[$aData['WERKS']]['REF_OBJID'])){
                $aData['ORG_MAP']=$aWerks[$aData['WERKS']]['REF_OBJID'];
            }
//            echo $aData['ORG_MAP']."<br/>";
//            error_log("CHECK BTRTL".$i);
            //BTRTL
            if(!empty($aData['BTRTL']) && empty($aBtrtl[$aData['BTRTL']])){
                $sError.=$aData['BTRTL'] . " BTRTL Unknown,please refer for RULES sheet @row $i<br/>";                
            }
            //WERKS
            if(!empty($aData['ABKRS']) && empty($aWerks[$aData['ABKRS']])){
                $sError.=$aData['ABKRS'] . " ABKRS Unknown,please refer for RULES sheet @row $i<br/>";                
            }
                    
//            error_log("CHECK ORGEH".$i);
            //ORGEH
            if (!empty($aData['ORGEH'])) {
                if(!isset($aMapOrg[$aData['ORGEH']])){
                    $stext = $this->orgchart_m->check_org_id($aData['ORGEH']);
                    if(isset($stext)){
                        $aMapOrg[$aData['ORGEH']]=$stext;
                    }else{
                        $sError.= $aData['ORGEH']." ORGEH Unknown,please refer for application @row $i<br/>";
                    }
                }
            }
//            error_log("CHECK PLANS".$i);
            //PLANS
            if (!empty($aData['PLANS'])) {
                if(!isset($aMapPos[$aData['PLANS']])){
                    $stext = $this->orgchart_m->check_pos_id($aData['PLANS']);
                    if(isset($stext)){
                        $aMapPos[$aData['PLANS']]=$stext;
                    }else{
                        $sError.=" PLANS Unknown,please refer for application or template Exist @row $i<br/>";
                    }
                }
            }
//            error_log("CHECK ORG MAINTAIN".$i);
            $org_map="";
//            error_log("status on ".$aData['PERNR']." BERFORE ORG_MAP ".date("YmdHis"), 0);
            
            if(empty($aData['ORG_MAP']) && isset($aOrgMap[$aData['ORGEH']])){
                $org_map=$aOrgMap[$aData['ORGEH']];
            }else if(empty($aData['ORG_MAP']) &&isset($aOrgLevels[$aData['ORGEH']])){
                $org_map=$aOrgLevels[$aData['ORGEH']];
            }else if(empty($aData['ORG_MAP']) && !isset($aOrgLevels[$aData['ORGEH']])){
                $temp=array();
                $org = $aData['ORGEH'];
                $temp[]=$org;
                while(true){
//                    echo "1 ".$org."<br/>";
                    $org = $this->orgchart_m->get_org_up($org);
//                    echo "2 ".$org."<br/>";
//                    var_dump($aOrgMap);exit;
                    if(isset($aOrgMap[$org])){
                        $org_map=$org;
//                        var_dump($temp);exit;
                        foreach($temp as $t){
                            $aOrgLevels[$t]=$org_map;
                        }
                        break;
                    }else{
                        $temp[]=$org;
                    }
                }
            }
//            error_log("status on ".$aData['PERNR']"AFTER ORG_MAP ".date("YmdHis"), 0);
            if(empty($aData['ORG_MAP']) && $org_map==""){
                $sError.=" ORG MAINTAIN Unknown,please check ORGEH/ORG_ID, info admin error @row $i<br/>";
            }else if(empty($aData['ORG_MAP'])){
                $aData['ORG_MAP']=$org_map;
            }
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
//        "PERNR","BEGDA","ENDDA","CNAME","GESCH","GBDAT","GBLND","RECRUIT_ID","EMPREF_ID","EMPREF_CODE","MARST","MARDT","RELG","GBCTY","GBNAT","IDENT","PERSG","PERSK","WERKS","BTRTL","ORGEH","PLANS","MASSN"
        $sReturn="<table border='1' cellpadding='0' cellspacing='0'u><tr><th>PERNR</th><th>BEGDA</th><th>ENDDA</th><th>CNAME</th><th>GESCH</th><th>GBDAT</th><th>GBLND</th>"
                . "<th>RECRUIT_ID</th><th>EMPREF_ID</th><th>EMPREF_CODE</th><th>MARST</th><th>MARDT</th><th>RELG</th><th>GBCTY</th><th>GBNAT</th><th>IDENT</th><th>PERSG</th>"
                . "<th>PERSK</th><th>WERKS</th><th>BTRTL</th><th>ORGEH</th><th>PLANS</th><th>MASSN</th><th>ABKRS</th></tr>";
//        $aOrgMaintain
        for ($i = 0; $i < count($aInput); $i++) {
            //find org level/maintain nya
            //get pernr
//            $configShort = $aOrgeh[$aInput[$i]['ORGEH']];
            //insert mapping_pernr
            if(empty($aInput[$i]['PERNR']) && $this->orgchart_m->is_config_short_exist('PERNR',$aInput[$i]['WERKS'])){
                $aInput[$i]['PERNR']= $this->employee_m->add_new_employeeByWERKS($aInput[$i]['WERKS'],$aInput[$i]['ORG_MAP'], "-", $aInput[$i]['BEGDA']);
            }elseif(empty($aInput[$i]['PERNR'])){
                $aInput[$i]['PERNR']= $this->employee_m->add_new_employee($aInput[$i]['ORG_MAP'], "-", $aInput[$i]['BEGDA']);
            }else{
                $this->employee_m->saving_map_pernr($aInput[$i]['PERNR'], "-", $aInput[$i]['ORG_MAP'], $aInput[$i]['BEGDA']);
            }
            $aColMasterEmp=array("PERNR","CNAME","GESCH","GBDAT","GBLND","BEGDA","ENDDA","RECRUIT_ID","EMPREF_ID","EMPREF_CODE","MARST","MARDT","RELG","GBCTY","GBNAT","IDENT");
            $aForMasterEmp=array();
            foreach($aColMasterEmp as $colname){
                $aForMasterEmp[$colname]=$aInput[$i][$colname];
            }
            $this->employee_m->personal_data_new($aForMasterEmp);
            $aColEmpOrg=array("PERNR","BEGDA","ENDDA","PERSG","PERSK","WERKS","BTRTL","ORGEH","PLANS","MASSN","ABKRS");
            $aForEmpOrg=array();
            foreach($aColEmpOrg as $colname){
                $aForEmpOrg[$colname]=$aInput[$i][$colname];
            }
            $this->employee_m->organizational_assignment_new($aForEmpOrg);
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