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
        } else if ($type == $this->DATE_MYSQL) {
            if (empty($l1) || empty($var))
                return $this->convert_yyyymmdd_ddmmyyyy("-");
            if (!empty($l1) && empty($var[$l1]))
                return $this->convert_yyyymmdd_ddmmyyyy("-");
            if (!empty($l1) && !empty($var[$l1]) && !is_array($var[$l1]))
                return $this->convert_yyyymmdd_ddmmyyyy($var[$l1]);
        }
    }

    public function gen_aOrg_maintain($id_user) {
        $sQuery = "SELECT * FROM tm_org_maintain where id_user='" . $id_user . "' AND CURDATE() BETWEEN BEGDA AND ENDDA ";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $n = $oRes->num_rows();
            for ($i = 0; $i < $n; $i++) {
                $this->insert_uorg_maintain($id_user, $aRes[$i]['org_unit']);
                $this->gen_org_down($id_user, $aRes[$i]['org_unit']);
            }
            $oRes->free_result();
            return true;
        }
        return false;
    }

    public function gen_apernr_maintain($id_user) {
        $sQuery = "SELECT me.pernr FROM tm_uorg_maintain uom
            JOIN tm_emp_org eo ON uom.id_user='" . $id_user . "' AND uom.org_unit=eo.ORGEH AND CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA AND PERSG NOT IN('X','T')
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
        $a = array('pernr' => $pernr, 'pernr_m' => $pernr_m);
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_upernr_maintain', $a);
    }

    function insert_uorg_maintain($pernr, $org_unit) {
        $a = array('pernr' => $pernr, 'org_unit' => $org_unit);
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_uorg_maintain', $a);
    }

    function del_upernr_maintain($pernr) {
        $this->db->where('pernr', $pernr);
        $this->db->delete('tm_upernr_maintain');
        $this->global_m->insert_log_delete('tm_upernr_maintain',array('pernr'=> $pernr));
    }

    function del_uorg_maintain($pernr) {
        $this->db->where('pernr', $pernr);
        $this->db->delete('tm_uorg_maintain');
        $this->global_m->insert_log_delete('tm_uorg_maintain',array('pernr'=> $pernr));
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
            return substr($date, 8, 2) . "/" . substr($date, 5, 2) . "/" . substr($date, 0, 4);
        }
        return "01/01/1900";
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
        AND LEFT(OBJID,2) like '$sPrefixUnit%') org
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
            $sPrefixUnit = substr($aCompany[$i]['OBJID'], 0, 2);
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
        $aRet = array(array('Laki-Laki', 0), array("Perempuan", 0));
        for ($i = 0; $i < count($aRes); $i++) {
            if ($aRes[$i]['GESCH'] == '1') {
                $aRet[0][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['GESCH'] == '2') {
                $aRet[1][1] += $aRes[$i]['n'];
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
                $aRet[0][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['USIA'] <= 40) {
                $aRet[1][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['USIA'] <= 56) {
                $aRet[2][1] += $aRes[$i]['n'];
            } else {
                $aRet[3][1] += $aRes[$i]['n'];
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
                $aRet[0][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 3) {
                $aRet[0][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 6) {
                $aRet[1][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 8) {
                $aRet[2][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 9) {
                $aRet[3][1] += $aRes[$i]['n'];
            } else if ($aRes[$i]['SLART'] <= 10) {
                $aRet[4][1] += $aRes[$i]['n'];
            } else {
                $aRet[5][1] += $aRes[$i]['n'];
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
    function get_abbrev($iTipe) {
        $sQuery = "SELECT SHORT as id, STEXT as text FROM tm_master_abbrev WHERE SUBTY = " . $iTipe . " AND SHORT <> '0';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    // End - Andi S 20140503
}
