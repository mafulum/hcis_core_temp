<?php

class global_m extends CI_Model {

    var $DATE_MYSQL="DMySQL";
	var $ADMIN_USER_TYPE = 99;
	
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['base_url'] = $this->config->item('base_url');
        return $data;
    }
    public function get_array_data($var,$l1="",$type=""){
        if(empty($type)){
            if(empty($l1) || empty($var))return "-";
            if(!empty($l1) && empty($var[$l1]))return "-";
            if(!empty($l1) && !empty($var[$l1]) && !is_array($var[$l1]))return $var[$l1];
        }else if($type==$this->DATE_MYSQL){
            if(empty($l1) || empty($var))return $this->convert_yyyymmdd_ddmmyyyy("-");
            if(!empty($l1) && empty($var[$l1]))return $this->convert_yyyymmdd_ddmmyyyy("-");
            if(!empty($l1) && !empty($var[$l1]) && !is_array($var[$l1]))return $this->convert_yyyymmdd_ddmmyyyy($var[$l1]);
        }
    }
    
    
    public function gen_aOrg_maintain($pernr) {
        $sQuery = "SELECT * FROM tm_org_maintain where pernr='" . $pernr . "' AND CURDATE() BETWEEN BEGDA AND ENDDA ";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $n = $oRes->num_rows();
            for ($i = 0; $i < $n; $i++) {
                $this->insert_uorg_maintain($pernr, $aRes[$i]['org_unit']);
                $this->gen_org_down($pernr, $aRes[$i]['org_unit']);
            }
            $oRes->free_result();
            return true;
        }
        return false;
    }
    
    public function gen_apernr_maintain($pernr){
        $sQuery="SELECT me.pernr FROM tm_uorg_maintain uom
            JOIN tm_emp_org eo ON uom.pernr='".$pernr."' AND uom.org_unit=eo.ORGEH AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA AND PERSG NOT IN('X','T')
            JOIN tm_master_emp me ON eo.PERNR=me.pernr ";
        $oRes = $this->db->query($sQuery);
        if($oRes->num_rows()>0){
            $aRes = $oRes->result_array();
            for($i=0;$i<count($aRes);$i++){
                $this->insert_upernr_maintain($pernr, $aRes[$i]['pernr']);
            }
            $oRes->free_result();
        }
    }
    
    function insert_upernr_maintain($pernr, $pernr_m) {
        $this->db->insert('tm_upernr_maintain', array('pernr' => $pernr, 'pernr_m' => $pernr_m));
    }

    function insert_uorg_maintain($pernr, $org_unit) {
        $this->db->insert('tm_uorg_maintain', array('pernr' => $pernr, 'org_unit' => $org_unit));
    }

    function del_upernr_maintain($pernr) {
        $this->db->where('pernr', $pernr);
        $this->db->delete('tm_upernr_maintain');
    }

    function del_uorg_maintain($pernr) {
        $this->db->where('pernr', $pernr);
        $this->db->delete('tm_uorg_maintain');
    }

    function gen_org_down($pernr, $org_unit) {
        $aOrg = $this->get_org_down($org_unit);
        if(empty($aOrg))return null;
        for ($i = 0; $i < count($aOrg); $i++) {
            $this->insert_uorg_maintain($pernr, $aOrg[$i]['SOBID']);
            $this->gen_org_down($pernr, $aOrg[$i]['SOBID']);
        }
    }

    function get_org_down($sOrg) {
        $sql = "SELECT SOBID " .
                "FROM tm_master_relation R " .
                "WHERE OBJID='" . $sOrg . "' AND OTYPE='O' " .
                "AND CURDATE() BETWEEN BEGDA AND ENDDA " .
                "AND SUBTY='B002' AND SCLAS='O';";

        $oRes = $this->db->query($sql);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
            return $aRes;
        }
    }
    
    function convert_ddmmyyyy_yyyymmdd($date){
        if(strlen($date)==10){
            return substr($date,6,4)."-".substr($date,3,2)."-".substr($date, 0,2);
        }
        return "1900-01-01";
    }
    
    function convert_yyyymmdd_ddmmyyyy($date){
        if(strlen($date)==10){
            return substr($date, 8,2)."/".substr($date,5,2)."/".substr($date,0,4);
        }
        return "01/01/1900";
    }
//    public function cekMaintain($sNopeg, $sMaint, $iInit = 1) {
//        $bRst = FALSE;
//
//        if ($iInit == 1) {
//            $bRst = $this->cekMaintain($sNopeg, '999999', 0);
//        }
//
//        if (!$bRst) {
//            $sQuery = "SELECT nopeg_maintain " .
//                    "FROM tms_nopeg_maintain WHERE nopeg = '" . $sNopeg . "' AND nopeg_maintain = '" . $sMaint . "';";
//
//            $oRun = $this->db->query($sQuery);
//            if ($oRun->num_rows() > 0) {
//                $bRst = TRUE;
//            }
//
//            $oRun->free_result();
//        }
//        return $bRst;
//    }
//
//    public function getMaint($sNopeg) {
//        $bCheck = $this->cekMaintain($this->session->userdata('s_nopeg'), $sNopeg);
//        if (!$bCheck) {
//            echo "Not Authorize";
//            exit;
//        }
//    }
//
//    public function cekSelf($sNopeg) {
//        if ($sNopeg <> $this->session->userdata('s_nopeg')) {
//            echo "Not Authorize";
//            exit;
//        }
//    }
//
//    public function getAllMaint($sMntType = 'SORD') {
//        $sQuery = "SELECT CONCAT(nopeg_maintain, ' | ', nama) as gabungan, nama, n.nopeg_maintain " .
//                "FROM tms_nopeg_maintain n " .
//                "LEFT JOIN tms_master_emp m ON n.nopeg_maintain = m.nopeg " .
//                "WHERE n.nopeg = '" . $this->session->userdata('s_nopeg') . "' AND isEmp = 1 and n.mnt_type like '" . $sMntType . "'" .
//                "UNION " .
//                "SELECT CONCAT(nopeg_maintain, ' | ', nama) as gabungan, nama, n.nopeg_maintain " .
//                "FROM tms_nopeg_maintain n " .
//                "LEFT JOIN tms_master_outsource m ON n.nopeg_maintain = m.nopeg " .
//                "WHERE n.nopeg = '" . $this->session->userdata('s_nopeg') . "' AND isEmp = 0 and n.mnt_type like '" . $sMntType . "'" .
//                "ORDER BY nama;";
//
//        $aRun = $this->db->query($sQuery);
//        $aRst = $aRun->result();
//
//        $aRun->free_result();
//        return $aRst;
//    }
//
//    function get_org_up($sOrgUnit) {
//        $this->load->library('Curl');
//        $sOrg = $this->curl->simple_get(URL_CURL . '/employee/get_org_up/' . $sOrgUnit);
//        return json_decode($sOrg);
//    }
//
//    function get_emp_org($sNopeg) {
//        if ($sNopeg == "")
//            return "";
//        $this->load->library('Curl');
//        $sOrg = $this->curl->simple_get(URL_CURL . '/employee/get_arr_org/' . $sNopeg);
//        return json_decode($sOrg);
//    }
//
//    public function getEmpInfo($sNopeg) {
//        $bOsrc = $this->isOutsource($sNopeg);
//        if (!$bOsrc) {
//            // Employee GA
//            $this->load->library('Curl');
//            $sEmp = $this->curl->simple_get(URL_CURL . '/employee/get_all_master_emp/' . $sNopeg);
//            $oEmp = json_decode($sEmp);
//
//            $sPos = $this->curl->simple_get(URL_CURL . '/employee/get_arr_position/' . $sNopeg);
//            $oPos = json_decode($sPos);
//
//            $sOrg = $this->curl->simple_get(URL_CURL . '/employee/get_arr_org/' . $sNopeg);
//            $oOrg = json_decode($sOrg);
//            $oRst->nopeg = $sNopeg;
//            $oRst->nama = ucwords(strtolower($oEmp->nama));
//            $oRst->jabatan = $oPos->position_name;
//            $oRst->unit = $oOrg->short;
//            $oRst->org_unit = $oEmp->org_unit;
//            $oRst->job_key = $oEmp->job_key;
//            $oRst->ps_group = $oEmp->PS_group;
//            $oRst->ee_subgrp = $oEmp->EE_subgrp;
//        } else {
//            // Outsource
//            $oEmp = null;
//            $sQuery = "SELECT m.org_unit, nama, jabatan, short " .
//                    "FROM tms_master_outsource m " .
//                    "LEFT JOIN tms_master_orgunit o ON o.org_unit = m.org_unit " .
//                    "WHERE nopeg = '" . $sNopeg . "'";
//
//            $aRun2 = $this->db->query($sQuery);
//            $oEmp = $aRun2->row();
//            $aRun2->free_result();
//
//            $oRst->nopeg = $sNopeg;
//            $oRst->nama = ucwords(strtolower($oEmp->nama));
//            $oRst->jabatan = $oEmp->jabatan;
//            $oRst->unit = $oEmp->short;
//            $oRst->org_unit = $oEmp->org_unit;
//            $oRst->job_key = '99999999';
//            $oRst->ps_group = 'D';
//        }
//
//        return $oRst;
//    }
//
//    public function cekPejabat($sNopeg) {
//        $sOrg = $this->curl->simple_get(URL_CURL . '/employee/is_manage_org/' . $sNopeg);
//        $oOrg = json_decode($sOrg);
//        return $oOrg;
//    }
//
//    public function cekLakhar($sNopeg) {
//        $sQuery = "SELECT * from tms_user_detail where nopeg=$sNopeg AND isLakhar=1 AND isActive=1 AND sdate<=CURDATE() AND edate>=CURDATE()";
//
//        $aRun2 = $this->db->query($sQuery);
//        if ($aRun2->num_rows() > 0)
//            return TRUE;
//        return FALSE;
//    }
//
//    public function get_org_all($sOrg) {
//        $sOrg = $this->curl->simple_get(URL_CURL . '/employee/get_all_org/' . $sOrg);
//        $oOrg = json_decode($sOrg);
//
//        return $oOrg;
//    }
//
//    public function update_nopeg_maintain($sNopeg, $sOrg = '', $sSub = '', $sMntType = 'SORD') {
//        // inputan : organisasi dari tms_nopeg_maintain plus klo dia atasan masukin nomer pegawai bawahannya
//        $this->db->trans_begin();
//
//        $sQuery = "DELETE FROM tms_nopeg_maintain " .
//                "WHERE nopeg = '" . $sNopeg . "' AND mnt_type='" . $sMntType . "';";
//        $this->db->query($sQuery);
//
//        if ($sOrg <> '') {
//
//            if ($sNopeg == "531583") {
//                $sQuery = "INSERT INTO tms_nopeg_maintain(nopeg,nopeg_maintain,last_change,isEmp) " .
//                        "SELECT '" . $sNopeg . "', nopeg, NOW(), 1 FROM tms_master_emp " .
//                        "WHERE nopeg IN(535288,529154,527784,522548,530235,710046,709187,532248,535616) ; ";
//                $this->db->query($sQuery);
//            } else {
//                $sTmp = ($sSub <> "" ? " UNION SELECT '" . $sNopeg . "', nopeg, NOW(), 1,'" . $sMntType . "' FROM tms_master_emp WHERE nopeg IN(" . $sSub . ") AND EE_subgrp NOT IN('6A','6J','5A','5H','5L','5R','5S','5T') AND EE_grp <> 'X' " : "");
//
//                // karyawan garuda
//                $sQuery = "INSERT INTO tms_nopeg_maintain(nopeg,nopeg_maintain,last_change,isEmp,mnt_type) " .
//                        "SELECT '" . $sNopeg . "', nopeg, NOW(), 1,'" . $sMntType . "' FROM tms_master_emp " .
//                        "WHERE org_unit IN(" . $sOrg . ") AND EE_subgrp NOT IN('6A','6J','5A','5H','5L','5R','5S','5T') AND EE_grp <> 'X'  " . $sTmp . "; ";
//                $this->db->query($sQuery);
////            $sQuery = "INSERT INTO tms_nopeg_maintain(nopeg,nopeg_maintain,last_change,isEmp,mnt_type) " .
////                    "SELECT '" . $sNopeg . "', nopeg, NOW(), 1,'".$sMntType."' FROM tms_master_emp " .
////                    "WHERE org_unit IN(" . $sOrg . ") AND EE_grp <> 'X' AND nopeg <> '" . $sNopeg . "' " . $sTmp . "; ";
////            $this->db->query($sQuery);
//                // karyawan outsourcing
//                $sQuery = "INSERT INTO tms_nopeg_maintain(nopeg,nopeg_maintain,last_change,isEmp,mnt_type) " .
//                        "SELECT '" . $sNopeg . "', nopeg, NOW(), 0,'" . $sMntType . "' FROM tms_master_outsource " .
//                        "WHERE org_unit IN(" . $sOrg . ") AND nopeg <> '" . $sNopeg . "' AND CURDATE() BETWEEN start_date AND end_date; ";
//                $this->db->query($sQuery);
//
//                // karyawan fast moving
//                $sQuery = "INSERT INTO tms_nopeg_maintain(nopeg,nopeg_maintain,last_change,isEmp,mnt_type) " .
//                        "SELECT '" . $sNopeg . "', nopeg, NOW(), isEmp,'" . $sMntType . "' FROM tms_emp_fast " .
//                        "WHERE org_unit IN(" . $sOrg . ") AND nopeg <> '" . $sNopeg . "' AND CURDATE() BETWEEN start_date AND end_date " .
//                        "AND nopeg NOT IN(SELECT nopeg_maintain FROM tms_nopeg_maintain WHERE nopeg = '" . $sNopeg . "');";
//                $this->db->query($sQuery);
//
//                // delete from tms_nopeg maintain jika ada karyawan sudah pindah di fast moving
//                $sQuery = "DELETE FROM tms_nopeg_maintain " .
//                        "WHERE nopeg = '" . $sNopeg . "' " .
//                        " AND nopeg_maintain IN(SELECT nopeg FROM tms_emp_fast " .
//                        "WHERE org_unit NOT IN(" . $sOrg . ") AND CURDATE() BETWEEN start_date AND end_date); ";
//                $this->db->query($sQuery);
//            }
//        } elseif ($sSub <> "") { // kayana ga bakalan masuk kesini deh :)
//            // hanya sebagai atasan saja, tidak maintain org_unit manapun
//            // karyawan garuda
//            $sQuery = "INSERT INTO tms_nopeg_maintain(nopeg,nopeg_maintain,last_change,isEmp,mnt_type) " .
//                    "SELECT '" . $sNopeg . "', nopeg, NOW(), 1," . $sMntType . " FROM tms_master_emp WHERE nopeg IN(" . $sSub . "); ";
//            $this->db->query($sQuery);
//        }
//
//        if ($this->db->trans_status() === FALSE) {
//            $this->db->trans_rollback();
//            return FALSE;
//        } else {
//            $this->db->trans_commit();
//            return TRUE;
//        }
//    }
//
//    public function getSubordinate($sNopeg) {
////        echo URL_CURL . '/employee/get_subordinates/' . $sNopeg;exit;
//        $sOrg = $this->curl->simple_get(URL_CURL . '/employee/get_subordinates/' . $sNopeg);
//        $oOrg = json_decode($sOrg);
//
//        return $oOrg;
//    }
//
//    public function getOrgMaintain($sNopeg, $mnt_type = "SORD") {
//        if ($mnt_type == "SORD") {
//            $sQuery = "SELECT org_maint " .
//                    "FROM tms_user_detail d " .
//                    "WHERE nopeg = '" . $sNopeg . "' and isLakhar=1 AND isActive=1 AND CURDATE() BETWEEN sdate AND edate";
//        } else if ($mnt_type == "TADM") {
//            $sQuery = "SELECT org_maint " .
//                    "FROM tms_user_detail d " .
//                    "WHERE nopeg = '" . $sNopeg . "' and isLakhar=0 AND isActive=1 AND CURDATE() BETWEEN sdate AND edate";
//        } else if ($mnt_type == "ALL") {
//            $sQuery = "SELECT org_maint " .
//                    "FROM tms_user_detail d " .
//                    "WHERE nopeg = '" . $sNopeg . "' AND isActive=1 AND CURDATE() BETWEEN sdate AND edate";
//        }
//        if ($sNopeg == "528368" && $mnt_type == 'TADM') {
////echo $sQuery;
////exit;
//        }
//        $aRun = $this->db->query($sQuery);
//        $aRst = $aRun->result();
//
//        $aRun->free_result();
//        return $aRst;
//    }
//
//    public function del_nopeg_maintain($sNopeg) {
//        $sQuery = "DELETE FROM tms_nopeg_maintain " .
//                "WHERE nopeg = '" . $sNopeg . "';";
//        $this->db->query($sQuery);
//    }
//
//    public function get_struct_addon($nopeg) {
//        $sQuery = "SELECT org_unit FROM tms_struct_addon WHERE nopeg='$nopeg' AND CURDATE() BETWEEN begda AND endda";
//        $oRes = $this->db->query($sQuery);
//        $aRes = $oRes->result_array();
//        $oRes->free_result();
//        return $aRes;
//    }
//
////	public function getAllOrgMaintain($sNopeg,$isMan=0,$sSupOrg=""){
//    public function getAllOrgMaintain($sNopeg, $sMntType = "SORD") {
//        $aOrg = null;
//
//        $oMan = $this->cekPejabat($sNopeg);
//        if ($oMan) {
//            $isMan = 1;
//        } else {
//            $isMan = 0;
//        }
//        if ($sNopeg == "528368" && $sMntType == 'TADM') {
////echo $sMntType;
////var_dump($aOrg);
////exit;
//        }
//        // diambil dari table user_detail
//        $aOrg = $this->getOrgMaintain($sNopeg, $sMntType);
//        if ($sNopeg == "528368" && $sMntType == 'TADM') {
////var_dump($aOrg);
////exit;
//        }
//        // jika pejabat ambil dari org_unit ybs
//        if ($isMan == 1) {
//            $oEmp = $this->getEmpInfo($sNopeg);
//            $aSupOrg->org_maint = $oEmp->org_unit;
//            array_push($aOrg, $aSupOrg);
//            $aOrgAddOn = $this->get_struct_addon($sNopeg);
//            for ($i = 0; $i < count($aOrgAddOn); $i++) {
//                array_push($aOrg, $aOrgAddOn[$i]['org_unit']);
//            }
//        }
//
//        $sOrg = '';
//        if ($aOrg) {
//            foreach ($aOrg as $oOrg) {
//                $sTmp = '';
//                if (!empty($oOrg->org_maint)) {
//                    $aTmp = $this->get_org_all($oOrg->org_maint);
//                    $sTmp = implode(",", $aTmp);
//                    $sOrg .= ($sOrg == "" ? "" : ",") . $sTmp;
//                }
//            }
//        }
//
//        return $sOrg;
//    }
//
//    public function getAllDetailOrgMaintain($sNopeg) {
//        $aOrg = null;
//
//        $oMan = $this->cekPejabat($sNopeg);
//        if ($oMan) {
//            $isMan = 1;
//        } else {
//            $isMan = 0;
//        }
//        // diambil dari table user_detail
//        $aOrg = $this->getOrgMaintain($sNopeg, "ALL");
//        // jika pejabat ambil dari org_unit ybs
//        if ($isMan == 1) {
//            $oEmp = $this->getEmpInfo($sNopeg);
//            $aSupOrg->org_maint = $oEmp->org_unit;
//            array_push($aOrg, $aSupOrg);
//            $aOrgAddOn = $this->get_struct_addon($sNopeg);
//            for ($i = 0; $i < count($aOrgAddOn); $i++) {
//                array_push($aOrg, $aOrgAddOn[$i]['org_unit']);
//            }
//        }
//        $sOrg = '';
//        if ($aOrg) {
//            foreach ($aOrg as $oOrg) {
//                $sTmp = '';
//                if (!empty($oOrg->org_maint)) {
//                    $aTmp = $this->get_org_all($oOrg->org_maint);
//                    $sTmp = implode(",", $aTmp);
//                    $sOrg .= ($sOrg == "" ? "" : ",") . $sTmp;
//                }
//            }
//        }
//
//        return $sOrg;
//    }
//
//    function get_superior($sNopeg) {
//        $sOrg = "";
//
//        $bOsrc = $this->isOutsource($sNopeg);
//
//        // cek di emp_fast dulu baru
//        $sQuery = "SELECT org_unit " .
//                "FROM tms_emp_fast " .
//                "WHERE nopeg = '" . $sNopeg . "' AND CURDATE() BETWEEN start_date AND end_date;";
//        $aRun = $this->db->query($sQuery);
//        if ($aRun->num_rows() > 0) {
//            $row = $aRun->row();
//            $sOrg = $row->org_unit;
//        } else if ($bOsrc) {
//            $sQuery = "SELECT org_unit FROM tms_master_outsource where nopeg='" . $sNopeg . "'";
//            $aRun = $this->db->query($sQuery);
//            if ($aRun->num_rows() > 0) {
//                $row = $aRun->row();
//                $sOrg = $row->org_unit;
//            }
//        }
//        $aRun->free_result();
//
//        if ($sOrg <> "" || $bOsrc) {
//            // cari siapa yang memaintain org_unit
//            $sCurl = $this->curl->simple_get(URL_CURL . '/employee/get_who_manage/' . $sNopeg . '/' . $sOrg);
//            $oOrg = json_decode($sCurl);
//        } else {
////     echo URL_CURL . '/employee/get_superior/' . $sNopeg;exit;       
//            $sCurl = $this->curl->simple_get(URL_CURL . '/employee/get_superior/' . $sNopeg);
//            $oOrg = json_decode($sCurl);
//        }
//        //$nopegLakhar = $this->getLakhar($oOrg->nopeg);
//        //if($nopegLakhar)return $this->getEmpInfo($nopegLakhar);
//        return $oOrg;
//    }
//
//    public function getLakhar($sNopeg) {
//        $oRes = $this->db->query("SELECT nopeg FROM tms_user_detail WHERE isLakhar=1 and isActive=1 and NOW() BETWEEN sdate and edate AND nopeg_sup='$sNopeg'");
//        if ($oRes->num_rows() == 0)
//            return null;
//        $row = $oRes->row_array();
//        return $row['nopeg'];
//    }
//
//    public function isOutsource($sNopeg) {
//        $bRtn = FALSE;
//        $sQuery = "SELECT nopeg " .
//                "FROM tms_master_outsource " .
//                "WHERE nopeg = '" . $sNopeg . "';";
//
//        $aRun = $this->db->query($sQuery);
//        if ($aRun->num_rows() > 0) {
//            $bRtn = TRUE;
//        }
//        $aRun->free_result();
//
//        return $bRtn;
//    }
//
//    public function convert_tgl($dTgl) {
//        // 01.06.2012
//        $dTgl = trim($dTgl);
//        $dRtn = substr($dTgl, 6, 4) . "-" . substr($dTgl, 3, 2) . "-" . substr($dTgl, 0, 2);
//        return $dRtn;
//    }
//
//    public function update_read_objid($objid) {
//        $this->db->where('objid', $objid);
//        $now = date('y-m-d h:m:s');
//        $this->db->update('tms_msg', array('t_read' => $now));
//    }
//
//    public function update_read_msgid($msg_id) {
//        $this->db->where('msg_id', $msg_id);
//        $now = date('y-m-d h:m:s');
//        $this->db->update('tms_msg', array('t_read' => $now));
//    }
//
//    public function insert_msg($aMsg, $fEmail = true) {
//        $this->db->insert('tms_msg', $aMsg);
//        $this->load->library('sendemail');
//        $aMsg['nopeg'] = $this->get_nopeg_divert($aMsg['nopeg']);
//        $sMail = $this->get_email($aMsg['nopeg']);
//        if ($fEmail) {
//            $nopeg_lakhar = $this->get_email($this->get_nopeg_lakhar($aMsg['nopeg']));
//            $this->sendemail->send_mail('hconline', 'jktid', $sMail, $nopeg_lakhar, $aMsg['subject'], $aMsg['msg']);
//        }
//        //sent email
//    }
//
//    public function get_email($sNopeg) {
//        $sRtn = '';
//        $sAddr = '';
//        $sCurl = $this->curl->simple_get(URL_CURL . '/hcis/get_email/' . $sNopeg);
//        if (!empty($sCurl))
//            return $sCurl . "@garuda-indonesia.com";
//        return "";
//    }
//
//    public function get_master_dws($dws) {
//        $this->db->where('dws', $dws);
//        $oRes = $this->db->get('tms_master_dws');
//        $aRes = $oRes->result();
//        $oRes->free_result();
//        return $aRes;
//    }
//
//    public function getConfig($key) {
//        $oRes = $this->db->query("SELECT config_val FROM tms_config WHERE config_kode='$key'");
//        $aRes = $oRes->result();
//        $oRes->free_result();
//        return $aRes;
//    }
//
//    public function check_user($nopeg) {
//        $this->db->where('nopeg', $nopeg);
//        $oRes = $this->db->get('tms_user');
//        if ($oRes->num_rows() == 0) {
//            $this->db->insert('tms_user', array('nopeg' => $nopeg, 'password' => '123'));
//        }
//    }
//
//    public function join_lakhar($sNopeg) {
//        $ar = array();
//        $ar[] = $sNopeg;
//        $oRes = $this->db->query("SELECT nopeg_sup FROM tms_user_detail WHERE nopeg=$sNopeg and NOW() between sdate and edate and isActive=1 and isLakhar=1");
//        if ($oRes->num_rows() > 0) {
//            $aRes = $oRes->result_array();
//            for ($i = 0; $i < count($aRes); $i++) {
//                $ar[] = $aRes[$i]['nopeg_sup'];
//            }
//        }
//        return implode(",", $ar);
//    }
//
//    public function insert_logsys($sGrp, $sText, $sVar) {
//        $data['actGrp'] = $sGrp;
//        $data['actText'] = $sText;
//        $data['actVar'] = $sVar;
//    }
//
//    public function isVP($sNopeg) {
//        $q = $this->db->query("SELECT * FROM tms_master_vp WHERE nopeg =$sNopeg ");
//        if ($q->num_rows() > 0) {
//            $q->free_result();
//            return TRUE;
//        }
//        return FALSE;
//    }
//
//    public function get_nopeg_divert($sNopeg) {
//        $q = $this->db->query("SELECT nopeg_dest FROM tms_divert_email WHERE nopeg_ori =$sNopeg and begda<=date(NOW()) AND endda>=date(NOW())");
//        if ($q->num_rows() > 0) {
//            $aRow = $q->row_array();
//            $q->free_result();
//            return $aRow['nopeg_dest'];
//        }
//        return $sNopeg;
//    }
//
//    public function get_nopeg_lakhar($sNopeg) {
//        $ar = array();
//        $ar[] = $sNopeg;
//        $oRes = $this->db->query("SELECT nopeg FROM tms_user_detail WHERE nopeg_sup=$sNopeg and sdate<=date(NOW()) AND edate=>date(now()) and isActive=1 and isLakhar=1");
//        if ($oRes->num_rows() > 0) {
//            $aRes = $oRes->row_array();
//            $oRes->free_result();
//            return $aRes['nopeg'];
//        }
//        return "";
//    }
//
//    public function comparison_date($sDate, $sMinus = "") {
//        $p_limit = $this->get_overtime_plimit();
//        $todays_date = $p_limit[0]->tanggal_min; //date("Y-m-d");
//        if ($sMinus == "") {
//            $today = strtotime($todays_date);
//        } else {
//            $today = strtotime($todays_date . ' ' . $sMinus);
//        }
//        $expiration_date = strtotime($sDate);
//        if ($expiration_date < $today) {
//            return 1;
//        } else if ($expiration_date == $today) {
//            return 2;
//        } else {
//            return 3;
//        }
//    }
//
//    public function comparison_two_date($begda, $endda) {
//        $tBegda = strtotime($begda);
//        $tEndda = strtotime($endda);
//        if ($tEndda < $tBegda) {
//            return 1;
//        } else if ($tEndda == $tBegda) {
//            return 2;
//        } else if ($tEndda == $tBegda) {
//            return 3;
//        }
//    }
//
//    function get_overtime_plimit($sNopeg = "") {
//        if (empty($sNopeg)) {
//            $sNopeg = $this->session->userdata('s_nopeg');
//        }
//        $sQuery = "CALL sp_date_min_overtime2('" . $sNopeg . "',NOW(),0);";
//        $aRun = $this->db->query($sQuery);
//        $aRst = $aRun->result();
//        $aRun->free_result();
//        return $aRst;
//    }

	// Add by Andi S 20140503
	function get_abbrev($iTipe){
		$sQuery = "SELECT SHORT as id, STEXT as text FROM tm_master_abbrev WHERE SUBTY = ".$iTipe." AND SHORT <> '0' ORDER BY `SEQ`;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}
	
	// End - Andi S 20140503

}