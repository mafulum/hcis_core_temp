<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of orgchart_m
 *
 * @author Garuda
 */
class orgchart_m extends CI_Model {

    //put your code here
    function home($sNopeg = "") {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'orgchart/home';
        return $data;
    }
    
    function get_recursive_org($sOBJID,$sDate){
        $aRet = [];
        $sql="SELECT OBJID FROM tm_master_relation WHERE '$sDate' BETWEEN BEGDA AND ENDDA AND SUBTY='A002' AND SOBID IN($sOBJID) AND SCLAS='O' AND OTYPE='O'";
        $oRes = $this->db->query($sql);
        if(!empty($oRes) && $oRes->num_rows()>0){
            $aRes = $oRes->result_array();
            $oRes->free_result();
            $aSearch=[];
            foreach($aRes as $k => $v){
                $aRet[]=$v['OBJID'];
                $aSearch[]="'".$v['OBJID']."'";
            }
            $aTempRet = $this->get_recursive_org(implode(",", $aSearch), $sDate);
            if(!empty($aTempRet)){
                return array_merge($aRet,$aTempRet);
            }
        }
        return $aRet;
    }
    function getMasterOrg($q,$aOrg,$sDate){
        if(empty($aOrg)){
            return null;
        }
        $sql = "SELECT OBJID,SHORT,STEXT FROM tm_master_org where OTYPE='O' AND '$sDate' BETWEEN BEGDA AND ENDDA AND OBJID IN(".implode(",",$aOrg).") AND (SHORT like '%$q%' OR STEXT like '%$q%')";
        $oRes = $this->db->query($sql);
        if(!empty($oRes) && $oRes->num_rows()>0){
            return $oRes->result_array();
        }
        return null;
        
    }
    
    function getMasterPositionByOrgJob($q,$aOrg,$aJob,$sDate){
        if(empty($aOrg)){
            return null;
        }
        $sql = "SELECT pos.OBJID,pos.SHORT,pos.STEXT FROM (SELECT OBJID,SHORT,STEXT FROM tm_master_org WHERE OTYPE='S' AND '$sDate' BETWEEN BEGDA AND ENDDA and (SHORT like '%$q%' OR STEXT like '%$q%')) pos "
                . "JOIN(SELECT OBJID FROM tm_master_relation where SUBTY='A003' AND SOBID IN(".implode(",",$aOrg).") AND '$sDate' BETWEEN BEGDA AND ENDDA AND SCLAS='O' AND OTYPE='S') mrPos ON pos.OBJID=mrPos.OBJID ";
        if(!empty($aJob)){
            $sql .= "JOIN(SELECT SOBID FROM tm_master_relation where SUBTY='A007' AND OBJID IN(".implode(",",$aJob).") AND '$sDate' BETWEEN BEGDA AND ENDDA AND SCLAS='S' AND OTYPE='C') mrJobPos ON pos.OBJID=mrJobPos.SOBID ";
        }
//        echo $sql;exit;
        $oRes = $this->db->query($sql);
        if(!empty($oRes) && $oRes->num_rows()>0){
            return $oRes->result_array();
        }
        return null;
    }

    function get_sub_org($sOBJID, $sDate, $iFlag=0) {
		$aOrg = $this->common->get_a_org_auth();
		$sOrg = implode(",",$aOrg);
		
        $aRes = null;
        $sql = "SELECT r.OBJID, o.SHORT, o.STEXT,o.BEGDA,o.ENDDA,r.PRIOX " .
                "FROM tm_master_relation r JOIN tm_master_org o " .
                "WHERE r.SOBID = ? AND r.SUBTY = ? AND ? BETWEEN r.BEGDA AND r.ENDDA " .
                "AND o.OBJID = r.OBJID AND ? BETWEEN o.BEGDA AND o.ENDDA AND o.OTYPE = ? " .
				($iFlag==1?" AND o.OBJID IN(".$sOrg.") ":"").
                "ORDER BY PRIOX, r.OBJID;";

        $oRes = $this->db->query($sql, array($sOBJID, 'A002', $sDate, $sDate, 'O'));

        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
        }
        return $aRes;
    }

    function get_sub_pos($sOBJID, $sDate) {
        $aRes = null;
        $sql = "SELECT r.OBJID, o.SHORT, o.STEXT,o.BEGDA,o.ENDDA,r.PRIOX,pd.REFF_KONTRAK,pd.EMAIL_SUP,pd.NAME_SUP " .
                "FROM tm_master_relation r JOIN tm_master_org o ON o.OBJID = r.OBJID " .
                "LEFT JOIN tm_pos_doctad pd ON o.OBJID = pd.PLANS AND ? BETWEEN pd.BEGDA AND pd.ENDDA " .
                "WHERE r.SOBID = ? AND r.SUBTY = ? AND ? BETWEEN r.BEGDA AND r.ENDDA " .
                "AND o.OBJID = r.OBJID AND ? BETWEEN o.BEGDA AND o.ENDDA AND o.OTYPE = ? " .
                "ORDER BY PRIOX, r.OBJID;";

        $oRes = $this->db->query($sql, array($sDate,$sOBJID, 'A003', $sDate, $sDate, 'S'));
//        echo $this->db->last_query();exit;
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
        }
        return $aRes;
    }
    
    function get_org_up($objid){
        $sQuery="SELECT SOBID FROM tm_master_relation WHERE SUBTY='A002' and OBJID='".$objid."' AND OTYPE='O' and SCLAS='O' AND CURDATE() BETWEEN BEGDA AND ENDDA";
//        $this->db->where('SUBTY','A002');
//        $this->db->where('CURDATE() BETWEEN BEGDA AND ENDDA');
//        $this->db->where('OBJID',$objid);
//        $this->db->where('OTYPE','O');
//        $this->db->where('SCLAS','O');
//        $this->db->select('SOBID');
//        $oRes = $this->db->get('tm_master_relation');
        $oRes = $this->db->query($sQuery);
        if($oRes->num_rows()==0){
            echo $objid;exit;
        }
        $row = $oRes->row_array();
        return $row['SOBID'];
    }

    function get_sub_emp($sOBJID, $sDate) {
        $aRes = null;
        $sql = "SELECT e.PERNR, e.CNAME " .
                "FROM (SELECT PERNR,CNAME FROM tm_master_emp WHERE ? BETWEEN BEGDA AND ENDDA) e ".
                "JOIN (SELECT PERNR FROM tm_emp_org WHERE PLANS = ? AND ? BETWEEN BEGDA AND ENDDA AND PERSG NOT IN('Z','X')) o " .
                "ON e.PERNR = o.PERNR ";
        $oRes = $this->db->query($sql, array($sDate,$sOBJID,$sDate));

        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
        }
        return $aRes;
    }

    function get_sub_job($sOBJID, $sDate) {
        $aRes = null;
        $sql = "SELECT r.OBJID, o.SHORT, o.STEXT,o.BEGDA,o.ENDDA,r.PRIOX " .
                "FROM (SELECT * FROM tm_master_relation WHERE '$sDate' BETWEEN BEGDA AND ENDDA AND OTYPE='C' AND SCLAS='S' AND SUBTY='A007' AND SOBID='$sOBJID') r ". 
                "JOIN (SELECT * FROM tm_master_org WHERE OTYPE='C' AND '$sDate' BETWEEN BEGDA AND ENDDA) o ON o.OBJID = r.OBJID " .
                "ORDER BY PRIOX, r.OBJID";
        $oRes = $this->db->query($sql);
        if (!empty($oRes) && $oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
        }
        return $aRes;
    }

    function get_config_by_short($cType,$sShort) {
        $sQuery = "SELECT seq+1 seq FROM tm_config where ctype='$cType' and tm_config.short='$sShort'";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->row_array();
            return $aRes['seq'];
        }
        return 0;
    }

    function get_config_by_prefix($cType, $sPrefix) {
        $sQuery = "SELECT seq+1 seq FROM tm_config where ctype='$cType' and LEFT(seq,3)='$sPrefix'";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->row_array();
            return $aRes['seq'];
        }
        return 0;
    }
    
    function get_short_by_prefix($cType,$sPrefix){
        $sQuery = "SELECT short FROM tm_config where ctype='$cType' and LEFT(seq,3)='$sPrefix'";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->row_array();
            return $aRes['short'];
        }
        return 0;
    }
    
    function is_config_short_exist($cType,$short){
        $sQuery = "SELECT short FROM tm_config where ctype='$cType' and short='$short'";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            return true;
        }
        return false;
    }

    function add_config_by_prefix($cType, $sPrefix,$newSeq) {
        $sQuery = "UPDATE tm_config SET seq='$newSeq',updated_by = '".$this->session->userdata('username')."' where ctype='$cType' and LEFT(seq,3)='$sPrefix'";
        $this->db->query($sQuery);
    }

    function add_config_by_short($cType, $sShort,$newSeq) {
        $sQuery = "UPDATE tm_config SET seq='$newSeq',updated_by = '".$this->session->userdata('username')."' where ctype='$cType' and tm_config.short='$sShort'";
        $this->db->query($sQuery);
    }

    function org_upd($iOrg, $iParentOrg, $a) {
        if ($iOrg == -1) {//NEW
            $sPrefix = substr($iParentOrg, 0, 3);
            $org_id = $this->get_config_by_prefix('ORG', $sPrefix);

            //saving master
            $m['OTYPE'] = 'O';
            $m['OBJID'] = $org_id;
            $m['BEGDA'] = $a['BEGDA'];
            $m['ENDDA'] = $a['ENDDA'];
            $m['SHORT'] = $a['SHORT'];
            $m['STEXT'] = $a['STEXT'];
            $m['created_by'] = $this->session->userdata('username');
            $this->db->insert('tm_master_org', $m);
            $oSeq = $this->db->insert_id();
            if (!empty($oSeq)) {
                $this->add_config_by_prefix("ORG", $sPrefix, $org_id);
                //saving master_relation
                $mr['OTYPE'] = 'O';
                $mr['OBJID'] = $org_id;
                $mr['SUBTY'] = 'A002';
                $mr['BEGDA'] = $a['BEGDA'];
                $mr['ENDDA'] = $a['ENDDA'];
                $mr['SCLAS'] = 'O';
                $mr['SOBID'] = $iParentOrg;
                $mr['PRIOX'] = $a['PRIOX'];
                $mr['created_by'] = $this->session->userdata('username');
                $this->db->insert('tm_master_relation', $mr);
            }
        } else {//UPDATE
            $m['BEGDA'] = $a['BEGDA'];
            $m['ENDDA'] = $a['ENDDA'];
            $m['SHORT'] = $a['SHORT'];
            $m['STEXT'] = $a['STEXT'];
            $m['updated_by'] = $this->session->userdata('username');
            $this->db->where('OBJID', $iOrg);
            $this->db->where('OTYPE', 'O');
            $this->db->update('tm_master_org', $m);
            //saving master_relation
            $mr['BEGDA'] = $a['BEGDA'];
            $mr['ENDDA'] = $a['ENDDA'];
            $mr['PRIOX'] = $a['PRIOX'];
            $mr['updated_by'] = $this->session->userdata('username');
            $this->db->where('OBJID', $iOrg);
            $this->db->where('OTYPE', 'O');
            $this->db->where('SUBTY', 'A002');
            $this->db->update('tm_master_relation', $mr);
        }
    }
    
    function get_unit_active_kv(){
        $sQuery="SELECT OBJID,STEXT FROM tm_master_org WHERE OTYPE='O' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array();
        foreach ($aRes as $row){
            $aRet[$row['OBJID']]=$row['STEXT'];
        }
        return $aRet;
    }
    
    function check_org_id($org_id){
        $this->db->where('OBJID',$org_id);
        $this->db->where('OTYPE','O');
        $this->db->where('CURDATE() BETWEEN BEGDA AND ENDDA');
        $this->db->select('STEXT','OBJID');
        $oRes = $this->db->get('tm_master_org');
        if($oRes->num_rows()>0){
            $row=$oRes->row_array();
            return $row['STEXT'];
//            return $org_id;
        }
        return null;
    }
    
    function check_pos_id($pos_id){
        $this->db->where('OBJID',$pos_id);
        $this->db->where('OTYPE','S');
        $this->db->where('CURDATE() BETWEEN BEGDA AND ENDDA');
        $this->db->select('STEXT','OBJID');
        $oRes = $this->db->get('tm_master_org');
        if($oRes->num_rows()>0){
            $row=$oRes->row_array();
            return $row['STEXT'];
        }
        return null;
    }
    
    function insert_master_org($param){
        $param['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_master_org', $param);
        return $this->db->insert_id();
    }
    
    function pos_upd($iPosition, $iParentPos, $a) {
        if ($iPosition == -1) {//NEW
            $sPrefix = substr($iParentPos, 0, 3);
            $sShort=$this->get_short_by_prefix('ORG', $sPrefix); 
            $pos_id = $this->get_config_by_short('POSITION', $sShort);
            //saving master
//            var_dump($a);exit;
            $m['OTYPE'] = 'S';
            $m['OBJID'] = $pos_id;
            $m['BEGDA'] = $a['BEGDA'];
            $m['ENDDA'] = $a['ENDDA'];
            $m['SHORT'] = $a['SHORT'];
            $m['STEXT'] = $a['STEXT'];
            $oSeq = $this->insert_master_org($m);
            if (!empty($oSeq)) {
                $this->add_config_by_short("POSITION", $sShort, $pos_id);
                //saving master_relation
                $mr['OTYPE'] = 'S';
                $mr['OBJID'] = $pos_id;
                $mr['SUBTY'] = 'A003';
                $mr['BEGDA'] = $a['BEGDA'];
                $mr['ENDDA'] = $a['ENDDA'];
                $mr['SCLAS'] = 'O';
                $mr['SOBID'] = $iParentPos;
                $mr['PRIOX'] = $a['PRIOX'];
                $mr['created_by'] = $this->session->userdata('username');
                $this->db->insert('tm_master_relation', $mr);
            }
            return $pos_id;
        } else {//UPDATE
            $m['BEGDA'] = $a['BEGDA'];
            $m['ENDDA'] = $a['ENDDA'];
            $m['SHORT'] = $a['SHORT'];
            $m['STEXT'] = $a['STEXT'];
            $m['updated_by'] = $this->session->userdata('username');
            $this->db->where('OBJID', $iPosition);
            $this->db->where('OTYPE', 'S');
            $this->db->update('tm_master_org', $m);
            //saving master_relation
            $mr['BEGDA'] = $a['BEGDA'];
            $mr['ENDDA'] = $a['ENDDA'];
            $mr['PRIOX'] = $a['PRIOX'];
            $mr['updated_by'] = $this->session->userdata('username');
            $this->db->where('OBJID', $iPosition);
            $this->db->where('OTYPE', 'S');
            $this->db->where('SUBTY', 'A003');
            $this->db->update('tm_master_relation', $mr);
            return $iPosition;
        }
    }
   
    function get_OrgByPos($pos){
        $sRet="-1";
        $sQuery="select SOBID FROM tm_master_relation where OBJID='$pos' and OTYPE='S' AND SCLAS='O' AND SUBTY='A003' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        if($oRes->num_rows()>0){
            $aRow = $oRes->row_array();
            $sRet=  $aRow['SOBID'];
        }
        $oRes->free_result();
        return $sRet;
        
    }
    
    function get_stell(){
        $sQuery="select SHORT,STEXT from tm_master_abbrev where subty=6 and SHORT<>'0';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    function get_job_family(){
        $sQuery="select OBJID,SHORT,STEXT from tm_master_compt where OTYPE='JF' and CURDATE() BETWEEN BEGDA AND ENDDA;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    
    function posd_upd($iPosition,$aPos,$aPosD){
        $sQuery="DELETE FROM tm_pos_detail WHERE PLANS='$iPosition'";
        $this->db->query($sQuery);
        $this->global_m->insert_log_delete('tm_pos_detail',$sQuery);
        $sQuery="INSERT INTO tm_pos_detail (`PLANS`,`BEGDA`,`ENDDA`,`SCORE`,`STELL`,`FAMILY`,`created_by`)
            VALUES ('".$iPosition."','".$aPos['BEGDA']."','".$aPos['ENDDA']."','".$aPosD['SCORE']."','".$aPosD['STELL']."','".$aPosD['FAMILY']."','".$this->session->userdata('username')."')";
//        $this->db->where('PLANS',$iPosition);
//        $this->db->update('tm_pos_detail',array('BEGDA'=>$aPos['BEGDA'],'ENDDA'=>$aPos['ENDDA'],'SCORE'=>$aPosD['SCORE'],'STELL'=>$aPosD['STELL'],'FAMILY'=>$aPosD['FAMILY']));
        
        $this->db->query($sQuery);        
//        $this->load->model('gen_machine');
//        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PLANS,$iPosition);   
    }
    
    function postad_upd($iPosition,$aPos,$aPosTad){
        $sQuery="DELETE FROM tm_pos_doctad WHERE PLANS='$iPosition'";
        $this->db->query($sQuery);
        $this->global_m->insert_log_delete('tm_pos_doctad',$sQuery);
        $sQuery="INSERT INTO tm_pos_doctad (`PLANS`,`BEGDA`,`ENDDA`,`REFF_KONTRAK`,`EMAIL_SUP`,`NAME_SUP`,`created_by`)
            VALUES ('".$iPosition."','".$aPos['BEGDA']."','".$aPos['ENDDA']."','".$aPosTad['REFF_KONTRAK']."','".$aPosTad['EMAIL_SUP']."','".$aPosTad['NAME_SUP']."','".$this->session->userdata('username')."')";
        $this->db->query($sQuery);          
    }
    function get_apos_compt($iPosition,$id_pcom){
        $this->db->where("id_pcom",$id_pcom);
        $this->db->where("PLANS",$iPosition);
        $oRes = $this->db->get('tm_pos_compt');
        if($oRes->num_rows()>0){
            $aRes = $oRes->row_array();
            $oRes->free_result();
            return $aRes;
        }
        return null;
    }
    function get_pos_compt($iPosition){
        $this->db->where("PLANS",$iPosition);
        $oRes = $this->db->get('tm_pos_compt');
        if($oRes->num_rows()>0){
            $aRes = $oRes->result_array();
            $oRes->free_result();
            return $aRes;
        }
        return null;
    }
    
    function getAllJob(){
        $this->db->where('OTYPE','C');
        $this->db->where('CURDATE() BETWEEN BEGDA AND ENDDA');
        $oRes = $this->db->get('tm_master_org');
        if(!empty($oRes) && $oRes->num_rows()>0){
            $aRes = $oRes->result_array();
            $oRes->free_result();
            return $aRes;
        }
        return null;
    }
    
    function get_competency($iPosition=""){
        $sPrefixPos=  substr($iPosition, 0,3);
        $configShort = $this->orgchart_m->get_short_by_prefix("POSITION", $sPrefixPos);
        $new_id = $this->orgchart_m->get_config_by_short("KOMPETENSI", $configShort);
        $sPrefixComp = substr($new_id,0,3);        
        $oRes = $this->db->query("select OBJID,SHORT,STEXT,OTYPE from tm_master_compt where OBJID like '400%' ;");
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function check_time_constraint_competency($plans,$begda,$endda,$compID,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_pos_compt where PLANS='$plans' AND COMPT='$compID' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else if($nRows==1){
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begda ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", do you want overwrite ?";
            }else{
                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
            }
        }else if($type=="UPDATE"){
            //
            $sQuery="SELECT * from tm_pos_compt where  PLANS='$plans' AND COMPT='$compID' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_pcom<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_pos_compt where  PLANS='$plans' AND COMPT='$compID'  AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_pcom<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }
    
    

    function get_master_relation($iObjid,$cType="O") {
        $oRes = $this->db->query("SELECT r.* FROM tm_master_relation r  WHERE OBJID = '" . $iObjid. "' AND OTYPE='".$cType."';");
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
            return $aRes;
        }
        return [];
    }
    function get_master_relation_sobid($iObjid,$cType="O") {
        $oRes = $this->db->query("SELECT r.* FROM tm_master_relation r  WHERE SOBID = '" . $iObjid. "' AND SCLAS='".$cType."';");
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
            return $aRes;
        }
        return [];
    }
    
    function get_master_org_stext($iObjid,$cType) {
        $sQuery = "SELECT OBJID as id, STEXT as text FROM tm_master_org WHERE OBJID = '" . $iObjid . "' AND OTYPE = '".$cType."' AND CURDATE() BETWEEN BEGDA AND ENDDA;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes['text'];
    }
    
    function get_master_relation_id_rel($iObjid,$id_rel){
        $oRes = $this->db->query("SELECT r.* FROM tm_master_relation r  WHERE OBJID = '" . $iObjid. "' AND id_rel='".$id_rel."';");
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->row_array();
            $oRes->free_result();
            return $aRes;
        }
        return null;
    }
    
    function get_master_org_cb($prefix,$sSearch=""){
        $oRes = $this->db->query("SELECT * FROM tm_master_org r  WHERE OBJID like '".$prefix."%' AND OTYPE='O' AND CURDATE() BETWEEN BEGDA AND ENDDA AND ( SHORT LIKE '%" . $sSearch. "%' OR STEXT like '%".$sSearch."%' ) LIMIT 10;");
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
            return $aRes;
        }
        return null;        
    }
    
    
    function check_time_constraint_orel($objid,$begda,$endda,$subty,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_master_relation where OBJID='$objid' AND SUBTY='$subty' AND OTYPE='O' ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else if($nRows==1){
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begda ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", do you want overwrite ?";
            }else{
                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
            }
        }else if($type=="UPDATE"){
            //
            $sQuery="SELECT * from tm_master_relation where  OBJID='$objid' AND SUBTY='$subty'   AND OTYPE='O' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_rel<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_master_relation where  OBJID='$objid' AND SUBTY='$subty'  AND OTYPE='O'  AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_rel<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }
    
    function get_master_pos_stext($iObjid,$cType) {
        $sQuery = "SELECT OBJID as id, STEXT as text FROM tm_master_org WHERE OBJID = '" . $iObjid . "' AND OTYPE = '".$cType."' AND CURDATE() BETWEEN BEGDA AND ENDDA;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes['text'];
    }
    function check_time_constraint_prel($objid,$begda,$endda,$subty,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_master_relation where OBJID='$objid' AND SUBTY='$subty' AND OTYPE='S' ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else if($nRows==1){
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begda ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", do you want overwrite ?";
            }else{
                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
            }
        }else if($type=="UPDATE"){
            //
            $sQuery="SELECT * from tm_master_relation where  OBJID='$objid' AND SUBTY='$subty'   AND OTYPE='S' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_rel<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_master_relation where  OBJID='$objid' AND SUBTY='$subty'  AND OTYPE='S'  AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_rel<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }
    
    public function get_master_org($objid,$otype){
        $this->db->from('tm_master_org');
        $this->db->where('OBJID',$objid);
        $this->db->where('OTYPE',$otype);
        $this->db->where('CURdATE() BETWEEN BEGDA AND ENDDA');
        $temp = $this->db->get();
        if(empty($temp)){
            return null;
        }
        return $temp->row_array();
    }

    
    function delete_org($id,$objid,$otype){
        $this->db->where('id_org', $id);
        $this->db->where('OBJID', $objid);
        $this->db->where('OTYPE', $otype);
        $this->db->delete('tm_master_org');
        $this->global_m->insert_log_delete('tm_master_org',array('id_org'=> $id,'OBJID'=>$objid,'OTYPE'=>$otype));
    }
}

?>
