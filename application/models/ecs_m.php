<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ecs_m
 *
 * @author Garuda
 */
class ecs_m extends CI_Model {

    //put your code here
    function home() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'ecs/home';
        return $data;
    }

    function view($sNopeg = "") {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'ecs/view';
        return $data;
    }

    function all($sNopeg = "") {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'ecs/all';
        return $data;
    }

    function getEmp($sNopeg) {
        $sQuery = "SELECT DATE_FORMAT(GBDAT,'%d.%m.%Y') as GBDAT,TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS age FROM tm_master_emp WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function getEmpOrg($sNopeg) {
        $sQuery = "SELECT ORGEH, PLANS, STELL " .
                "FROM tm_emp_org WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function getPosition($sPos) {
        $sQuery = "SELECT STEXT " .
                "FROM tm_master_org " .
                "WHERE OBJID = '" . $sPos . "' AND OTYPE = 'S' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function getEmpCompt($sNopeg) {
        $sQuery = "SELECT m.SHORT, COVAL, m.STEXT, m.OTYPE " .
                "FROM (SELECT * FROM tm_emp_compt WHERE PERNR='".$sNopeg."' AND CURDATE() BETWEEN BEGDA AND ENDDA) c ". 
                "JOIN (SELECT * FROM tm_master_compt WHERE CURDATE() BETWEEN BEGDA AND ENDDA) m ON m.OBJID = c.COMPT " .
                "ORDER BY m.OTYPE; ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getPosCompt($sPos) {
        $sQuery = "SELECT c.id_pcom,c.PLANS,c.bobot,c.COMPT,mp.STEXT as PARENT_STEXT,m.SHORT, m.STEXT, m.OTYPE, REQV,c.BEGDA,c.ENDDA " .
                "FROM (SELECT * FROM tm_pos_compt WHERE CURDATE() BETWEEN BEGDA AND ENDDA AND PLANS='".$sPos."') c ".
                "JOIN (SELECT * FROM tm_master_compt WHERE OTYPE<>'KC' AND CURDATE() BETWEEN BEGDA AND ENDDA) m ON m.OBJID = c.COMPT " .
                "LEFT JOIN (SELECT * FROM tm_master_compt WHERE OTYPE='KC' AND CURDATE() BETWEEN BEGDA AND ENDDA) mp ON m.OTYPE= mp.SHORT " .
                "ORDER BY OTYPE;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
//        $sQuery = "SELECT m.SHORT, m.STEXT, m.OTYPE, REQV " .
//                "FROM tm_pos_compt c JOIN tm_master_compt m ON m.OBJID = c.COMPT " .
//                "WHERE PLANS = '" . $sPos . "' AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
//                "ORDER BY OTYPE;";
//        $oRes = $this->db->query($sQuery);
//        $aRes = $oRes->result_array();
//        $oRes->free_result();
//        return $aRes;
    }

    function getCompt() {
        $sQuery = "SELECT OTYPE, SHORT, STEXT " .
                "FROM tm_master_compt " .
                "WHERE  CURDATE() BETWEEN BEGDA AND ENDDA ORDER BY SHORT ";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getMasterCompt($sFilter="") {
        $sQuery = "SELECT OBJID, OTYPE, SHORT, STEXT " .
                "FROM tm_master_compt " .
                "WHERE ".$sFilter." CURDATE() BETWEEN BEGDA AND ENDDA ORDER BY SHORT ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    public function insert_job_compt_req($a){
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_job_compt', $a);
        return $this->db->insert_id();
    }
    
    function getMasterComptAndKC(){
        $kc = $this->getMasterCompt(" OTYPE='KC' AND ");
        $kvComptKC = $this->common->getKVArr($kc, "SHORT");
//        var_dump($kvComptKC);exit;
        $compts = $this->getMasterCompt(" OTYPE<>'KC' AND ");
        for($i=0;$i<count($compts);$i++){
            if(!empty($compts[$i]['OTYPE']) && !empty($kvComptKC[$compts[$i]['OTYPE']])){
                $compts[$i]['PARENT']=$kvComptKC[$compts[$i]['OTYPE']];
            }
        }
        return $compts;
    }

    function getEmpJson($sSearch) {
        $aEmp = $this->common->get_a_pernr_auth();
        $sEmp = implode(",", $aEmp);

        $sQuery = "SELECT e.PERNR as id, e.CNAME as name " .
                "FROM tm_master_emp e JOIN tm_mapping_pernr m ON e.PERNR = m.PERNR " .
                "WHERE (e.PERNR LIKE '%" . $sSearch . "%' OR e.CNAME LIKE '%" . $sSearch . "%') " .
                "AND CURDATE() BETWEEN e.BEGDA AND e.ENDDA " .
                "AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
                "AND e.PERNR IN(" . $sEmp . ") " .
                "LIMIT 20;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getUnitJson($sSearch) {
        $aOrg = $this->common->get_a_org_auth();
        $sWhere = "";
        foreach ($aOrg as $sOrg) {
            $sPrefix = "1" . substr($sOrg, 1, 2);
            $sWhere .= ($sWhere == "" ? "" : " OR ") . "OBJID LIKE '" . $sPrefix . "%' ";
        }

        $sQuery = "SELECT o.OBJID as id, o.STEXT as name " .
                "FROM tm_master_org o " .
                "WHERE o.STEXT LIKE '%" . $sSearch . "%' " .
                "AND o.OTYPE = 'O' " .
                "AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA " .
                "AND (" . $sWhere . ") " .
                "LIMIT 20;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getResultSearch($sCond) {
        $sQuery = "SELECT e.PERNR, e.CNAME, o.STELL, CONCAT(g.TRFGR,g.TRFST) as GRADE, r.SHORT, s.STEXT " .
                "FROM tm_master_emp e " .
                "JOIN tm_emp_org o ON o.PERNR = e.PERNR AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA " .
                "JOIN tm_mapping_pernr m ON m.PERNR = e.PERNR AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
                "JOIN tm_emp_grade g ON e.PERNR = g.PERNR AND CURDATE() BETWEEN g.BEGDA AND g.ENDDA " .
                "JOIN tm_master_org r ON r.OBJID = m.ORGEH AND r.OTYPE = 'O' AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA " .
                "JOIN tm_master_org s ON s.OBJID = o.ORGEH AND s.OTYPE = 'O' AND CURDATE() BETWEEN s.BEGDA AND s.ENDDA " .
                ($sCond == "" ? "" : "WHERE " . $sCond) . " " .
                "ORDER BY e.PERNR ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getPosDetail($sPos) {
        $sQuery = "SELECT STELL, FAMILY, a.STEXT as JOB_LEVEL, c.STEXT as FAMILY_TXT " .
                "FROM tm_pos_detail p " .
                "LEFT JOIN tm_master_abbrev a ON p.STELL = a.SHORT AND a.SUBTY = 6 " .
                "LEFT JOIN tm_master_compt c ON p.FAMILY = c.SHORT AND c.OTYPE = 'JF' AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA " .
                "WHERE PLANS = '" . $sPos . "' AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function getJobFamConfig($sJob) {
        $sQuery = "SELECT IS_FAM " .
                "FROM tm_job_fam_config " .
                "WHERE STELL = '" . $sJob . "';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function deleteJobCompt($id,$stell,$compt){
        $this->db->where('id_jobcompt', $id);
        $this->db->where('STELL', $stell);
        $this->db->where('COMPT', $compt);
        $this->db->delete('tm_job_compt');
        $this->global_m->insert_log_delete('tm_job_compt',array('id_jobcompt'=> $id,'STELL'=>$stell,'COMPT'=>$compt));
    }
    
    function getSTELLComptBasic($stell){
        if(empty($stell)){
            return [];
        }
        $this->db->where('STELL', $stell);
        $this->db->where('CURDATE() BETWEEN BEGDA AND ENDDA');
        return $this->db->get('tm_job_compt')->result_array();
    }
    
    function getPLANSComptBasic($plans){
        if(empty($plans)){
            return [];
        }
        $this->db->where('PLANS', $stell);
        $this->db->where('CURDATE() BETWEEN BEGDA AND ENDDA');
        return $this->db->get('tm_pos_compt')->result_array();
    }

    function getJobCompt($sJob, $sFam="", $iIsFam='0') {
        $sQuery = "SELECT c.id_jobcompt,c.STELL,c.bobot,c.COMPT,mp.STEXT as PARENT_STEXT,m.SHORT, m.STEXT, m.OTYPE, REQV,c.BEGDA,c.ENDDA " .
                "FROM (SELECT * FROM tm_job_compt WHERE CURDATE() BETWEEN BEGDA AND ENDDA AND STELL='".$sJob."') c ". 
                "JOIN (SELECT * FROM tm_master_compt WHERE OTYPE<>'KC' AND CURDATE() BETWEEN BEGDA AND ENDDA) m ON m.OBJID = c.COMPT " .
                "LEFT JOIN (SELECT * FROM tm_master_compt WHERE OTYPE='KC' AND CURDATE() BETWEEN BEGDA AND ENDDA) mp ON m.OTYPE= mp.SHORT " .
                ($iIsFam == '0' ? "AND (FAMILY IS NULL OR FAMILY = '') " : "AND FAMILY = '" . $sFam . "' ") .
                "ORDER BY OTYPE;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    
    function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }
    function saveDataDL($aContent) {
        unset($aContent['externalCSS']);
        unset($aContent['externalJS']);
        unset($aContent['scriptJS']);
//        var_dump($aContent);
//        $aKey=  array_keys($aContent);
//        print_r($aKey);
//        var_dump($aContent['training']);
//        echo"<br/>";
//        echo"<br/>";
//        echo"<br/>";
        
//        unset($aContent[$aKey[18]]);
        $sContent = json_encode($aContent);
        $sContent=  $this->escapeJsonString($sContent);
        $sContent = str_replace("'", "\'", $sContent);
        $sHash = md5(rand() . date("YmdHms"));
        $sQuery = "INSERT INTO tm_download(id_hash,content,create_date,created_by) " .
                "VALUES('" . $sHash . "','" . $sContent . "',NOW(),'".$this->session->userdata('username')."');";
        $this->db->query($sQuery);
//        echo $sHash;exit;


        return $sHash;
    }

}

?>