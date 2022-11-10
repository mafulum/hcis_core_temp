<?php

class global_m extends CI_Model {

    var $DATE_MYSQL = "DMySQL";
    var $ADMIN_USER_TYPE = 99;

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['base_url'] = $this->config->item('base_url');
        return $data;
    }

    public function get_array_data($var, $l1 = "", $type = "") {
        if (empty($type)) {
            if (empty($l1) || empty($var))
                return "-";
            if (!empty($l1) && empty($var[$l1]))
                return "-";
            if (!empty($l1) && !empty($var[$l1]) && !is_array($var[$l1]))
                return $var[$l1];
        }else if ($type == $this->DATE_MYSQL) {
            if (empty($l1) || empty($var))
                return $this->convert_yyyymmdd_ddmmyyyy("-");
            if (!empty($l1) && empty($var[$l1]))
                return $this->convert_yyyymmdd_ddmmyyyy("-");
            if (!empty($l1) && !empty($var[$l1]) && !is_array($var[$l1]))
                return $this->convert_yyyymmdd_ddmmyyyy($var[$l1]);
        }
    }

    public function gen_aOrg_maintain($pernr) {
        $sQuery = "SELECT * FROM tm_org_maintain where pernr='" . $pernr . "'  ";
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

    public function gen_apernr_maintain($pernr) {
        $sQuery = "SELECT me.pernr FROM tm_uorg_maintain uom
            JOIN tm_emp_org eo ON uom.pernr='" . $pernr . "' AND uom.org_unit=eo.ORGEH AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA AND PERSG NOT IN('X','T')
            JOIN tm_master_emp me ON eo.PERNR=me.pernr ";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            for ($i = 0; $i < count($aRes); $i++) {
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
        if (empty($aOrg))
            return null;
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

    function convert_ddmmyyyy_yyyymmdd($date) {
        if (strlen($date) == 10) {
            return substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
        }
        return "1900-01-01";
    }

    function convert_yyyymmdd_ddmmyyyy($date) {
        if (strlen($date) == 10) {
            return substr($date, 8, 2) . "." . substr($date, 5, 2) . "." . substr($date, 0, 4);
        }
        return "01.01.1900";
    }

    function get_master_abbrev($sSubty, $sAdWhere = "") {
        $sQuery = "select SHORT,STEXT from tm_master_abbrev where subty='$sSubty' $sAdWhere";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_count_master_emp($selected) {

        $sQuery = "select COUNT(*) n from tm_master_emp me
JOIN  tm_emp_org eo ON me.PERNR=eo.PERNR
JOIN tm_mapping_pernr mp ON me.PERNR=mp.PERNR
WHERE CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA
AND mp.ORGEH IN($selected)
AND CURDATE() BETWEEN me.BEGDA AND me.ENDDA
AND eo.PERSG <>'X'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['n'];
    }

    function get_count_all_position($selected = "-1") {
        $sQuery = "SELECT COUNT(*) n FROM (
SELECT mo.OBJID FROM (SELECT * FROM tm_master_org WHERE OTYPE='S'  AND CURDATE() BETWEEN BEGDA AND ENDDA) mo
JOIN tm_master_relation mr ON (mr.OTYPE='S' AND mo.OBJID=mr.OBJID AND ($selected)) 
WHERE CURDATE() BETWEEN mr.BEGDA AND mr.ENDDA
AND CURDATE() BETWEEN mo.BEGDA AND mo.ENDDA GROUP BY  mo.OBJID
) pos ;";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['n'];
    }

    function get_fill_registered_position($selected = "-1") {
        $sQuery = "SELECT COUNT(*) n FROM (
SELECT mo.OBJID FROM (SELECT * FROM tm_master_org WHERE OTYPE='S'  AND CURDATE() BETWEEN BEGDA AND ENDDA) mo
JOIN tm_master_relation mr ON (mr.OTYPE='S' AND mo.OBJID=mr.OBJID) OR (mr.SCLAS='S' AND mo.OBJID=mr.SOBID)
JOIN tm_emp_org eo ON mo.OBJID=eo.PLANS
JOIN tm_mapping_pernr mp ON eo.PERNR=mp.PERNR
WHERE CURDATE() BETWEEN mr.BEGDA AND mr.ENDDA
AND mp.ORGEH IN($selected)
AND CURDATE() BETWEEN mo.BEGDA AND mo.ENDDA
AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA
AND CURDATE() BETWEEN mp.BEGDA AND mp.ENDDA
GROUP BY  mo.OBJID
) pos ;";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['n'];
    }

    function get_count_employee($sPrefixUnit) {
        $sQuery = "SELECT * FROM (
SELECT * FROM tm_master_org WHERE OTYPE='O'
        AND CURDATE() BETWEEN BEGDA AND ENDDA
        AND LEFT(OBJID,3) like '$sPrefixUnit%') org
JOIN tm_emp_org eo ON org.OBJID=eo.ORGEH AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA
JOIN tm_master_emp me ON eo.PERNR=me.PERNR  AND CURDATE() BETWEEN me.BEGDA AND me.ENDDA;";
        $oRes = $this->db->query($sQuery);
        $n = $oRes->num_rows();
        $oRes->free_result();
        return $n;
    }

    function get_count_position($sPrefixUnit) {
        $sQuery = "SELECT COUNT(*) n FROM
( select OBJID from tm_master_relation WHERE OTYPE='S' AND SOBID like '$sPrefixUnit%' AND CURDATE() BETWEEN BEGDA AND ENDDA ) mr
JOIN (SELECT OBJID FROM tm_master_org WHERE OTYPE='S' AND CURDATE() BETWEEN BEGDA AND ENDDA) mp ON mr.OBJID=mp.OBJID";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['n'];
    }

    function get_count_registered_position($sPrefixUnit = "%") {
        $sQuery = "SELECT COUNT(*) n FROM
( select OBJID from tm_master_relation WHERE OTYPE='S' AND SOBID like '$sPrefixUnit%' AND CURDATE() BETWEEN BEGDA AND ENDDA ) mr
JOIN (SELECT OBJID FROM tm_master_org WHERE OTYPE='S' AND CURDATE() BETWEEN BEGDA AND ENDDA) mp ON mr.OBJID=mp.OBJID
JOIN tm_emp_org eo ON mp.OBJID=eo.PLANS AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA;";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['n'];
    }

    function get_company_profile() {
        $this->load->model('employee_m');
        $aCompany = $this->employee_m->get_anak_perusahaan();
        for ($i = 0; $i < count($aCompany); $i++) {
            $sPrefixUnit = substr($aCompany[$i]['OBJID'], 0, 3);
            $aCompany[$i]['nEmp'] = $this->get_count_employee($sPrefixUnit);
            $aCompany[$i]['nPos'] = $this->get_count_position($sPrefixUnit);
            $aCompany[$i]['nPosFil'] = $this->get_count_registered_position($sPrefixUnit);
        }
        return $aCompany;
    }

    function get_stat_gender($selected = "-1") {
        $sQuery = "select GESCH,COUNT(*) n from tm_master_emp me
JOIN tm_emp_org eo ON me.PERNR=eo.PERNR
JOIN tm_mapping_pernr mp ON me.PERNR=mp.PERNR
WHERE CURDATE() BETWEEN me.BEGDA AND me.ENDDA
AND mp.ORGEH IN ($selected)
AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA
AND eo.PERSG<>'X' GROUP BY GESCH";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array(array('Male', 0), array("Female", 0));
        for ($i = 0; $i < count($aRes); $i++) {
            if ($aRes[$i]['GESCH'] == '1') {
                $aRet[0][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['GESCH'] == '2') {
                $aRet[1][1]+=$aRes[$i]['n'];
            }
        }
        return $aRet;
    }

    function get_stat_usia($selected = "-1") {
        $sQuery = "select (YEAR(CURDATE())-YEAR(GBDAT)) USIA,COUNT(*) n from tm_master_emp me
JOIN tm_emp_org eo ON me.PERNR=eo.PERNR
JOIN tm_mapping_pernr mp ON me.PERNR=mp.PERNR
WHERE CURDATE() BETWEEN me.BEGDA AND me.ENDDA
AND mp.ORGEH IN ($selected)
AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA
AND eo.PERSG<>'X' GROUP BY (YEAR(CURDATE())-YEAR(GBDAT));";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array(array('<=20', 0), array("21-40", 0), array("41-56", 0), array(">56", 0));
        for ($i = 0; $i < count($aRes); $i++) {
            if ($aRes[$i]['USIA'] <= 20) {
                $aRet[0][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['USIA'] <= 40) {
                $aRet[1][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['USIA'] <= 56) {
                $aRet[2][1]+=$aRes[$i]['n'];
            } else {
                $aRet[3][1]+=$aRes[$i]['n'];
            }
        }
        return $aRet;
    }

    function get_stat_edu($selected = "-1") {
        $sQuery = "SELECT SLART,COUNT(*) n FROM (
SELECT me.PERNR,SLART FROM tm_master_emp me
JOIN tm_mapping_pernr mp ON me.PERNR=mp.PERNR
LEFT JOIN (SELECT * FROM tm_emp_educ WHERE AUSBI='Formal' ORDER BY SLART DESC) ee ON me.PERNR=ee.PERNR
WHERE CURDATE() BETWEEN mp.BEGDA AND mp.ENDDA
AND CURDATE() BETWEEN me.BEGDA AND me.ENDDA
AND mp.ORGEH IN($selected)
GROUP BY PERNR
) edu 
GROUP BY SLART";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array(array('Unknown', 0), array("SLTA", 0), array("Diploma", 0), array("S1", 0), array("S2", 0), array("S3", 0));
        for ($i = 0; $i < count($aRes); $i++) {
            if (empty($aRes[$i]['SLART']) || $aRes[$i]['SLART'] <= 0) {
                $aRet[0][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 3) {
                $aRet[0][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 6) {
                $aRet[1][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 8) {
                $aRet[2][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 9) {
                $aRet[3][1]+=$aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 10) {
                $aRet[4][1]+=$aRes[$i]['n'];
            } else {
                $aRet[5][1]+=$aRes[$i]['n'];
            }
        }
        return $aRet;
    }

    function get_user_type() {
        $sRet = $this->session->userdata('user_type');
        if (!empty($sRet)) {
            return $sRet;
        }
        return -1;
    }

    // Add by Andi S 20140503
    function get_abbrev($iTipe, $sTy = "") {
        $sQuerySty="";
        if(!empty($sTy)){
            $sQuerySty = " AND SHORT like '$sTy' ";
        }
        $sQuery = "SELECT SHORT as id, STEXT as text, STEXT as name FROM tm_master_abbrev WHERE SUBTY = " . $iTipe . " AND SHORT <> '0' $sQuerySty ORDER BY `SEQ`;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    // End - Andi S 20140503

    function get_stat_stell($selected = "-1") {
        $sQuery = "select STELL,COUNT(*) n from tm_master_emp me
JOIN tm_emp_org eo ON me.PERNR=eo.PERNR
JOIN tm_mapping_pernr mp ON me.PERNR=mp.PERNR
WHERE CURDATE() BETWEEN me.BEGDA AND me.ENDDA
AND mp.ORGEH IN ($selected)
AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA
AND eo.PERSG<>'X' GROUP BY STELL;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aStell = $this->get_abbrev("6");
        $aRet = array();
        for ($j = 0; $j < count($aStell); $j++) {
            $aRet[] = array($aStell[$j]['text'], 0, $aStell[$j]['id']);
        }
        for ($i = 0; $i < count($aRes); $i++) {
            for ($j = 0; $j < count($aStell); $j++) {
                if ($aRes[$i]['STELL'] == $aRet[$j][2]) {
                    $aRet[$i][1]+=$aRes[$i]['n'];
                }
            }
        }
        return $aRet;
    }
	
	// Add by Andi 20140705
	function get_user_module($iUid){
		$sQuery = "SELECT a.SHORT, u.id_user as UID ".
				  "FROM tm_master_abbrev a ".
				  "LEFT JOIN tm_user_module u ON u.id_module = a.SHORT AND u.id_user = ".$iUid." ".
				  "WHERE a.SUBTY = 13 AND a.SHORT <> 0 ".
				  "ORDER BY a.SEQ;"; 

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		$aRet = array();
		if($aRes){
			for($i=0;$i<count($aRes);$i++){
				$aRet[$aRes[$i]['SHORT']] = ($aRes[$i]["UID"]<>""?1:0);
			}
		}

        return $aRet;
	}
	// End - Andi 20140705
	
	// Add by Andi 20140707
	function get_a_org_auth($iUid){
		$sQuery = "SELECT org_unit as ORG ".
				  "FROM tm_org_maintain a ".
				  "WHERE id_user = ".$iUid." ;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		$aRtn = array();
		if($aRes){
			$aRtn = array_map(array($this,"convert_array"),$aRes);
		}

        return $aRtn;
	}
	
	private function convert_array($a){
		return($a = $a["ORG"]);
	}
	
	private function convert_array2($a){
		return($a = $a["PERNR"]);
	}
	
	function get_a_pernr_auth($iUid){
		$sQuery = "SELECT m.PERNR ".
				  "FROM tm_mapping_pernr m ".
				  "LEFT JOIN tm_org_maintain o ON o.org_unit = m.ORGEH  ".
				  "WHERE o.id_user = ".$iUid." ".
				  "AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		$aRtn = array();
		if($aRes){
			$aRtn = array_map(array($this,"convert_array2"),$aRes);
		}

        return $aRtn;
	}
	
	function get_mapping_pernr($sNopeg){
		$sQuery = "SELECT ORGEH ".
				  "FROM tm_mapping_pernr ".
				  "WHERE PERNR = ".$sNopeg." ".
				  "AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();

        return $aRes;
	}
	
	function cek_otorisasi($iUid,$sNopeg){
		$iUid = $this->security->xss_clean($iUid);
		$sNopeg = $this->security->xss_clean($sNopeg);
		
		$sQuery = "SELECT m.PERNR ".
				  "FROM tm_org_maintain o ".
				  "LEFT JOIN tm_mapping_pernr m ON o.org_unit = m.ORGEH AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				  "WHERE o.id_user = ".$iUid." AND m.PERNR = '".trim($sNopeg)."' ";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
		$bRet = FALSE;
		if($aRes){
			if($aRes["PERNR"] == trim($sNopeg)){
				$bRet = TRUE;
			}
		}

        return $bRet;
	}
	
	// End - Andi 20140707
	
	// Add by Andi 20140811
	function cek_pihc_access($iUid){
		$iRtn = 0;

		$sQuery = "SELECT id_user ".
				  "FROM tm_org_maintain ".
				  "WHERE org_unit = 10100000 AND id_user = ".$iUid." AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();

		if($aRes){
			if($aRes["id_user"] == $iUid){
				$iRtn = 1;
			}
		}

        return $iRtn;
	}
	// End - Andi 20140811
}