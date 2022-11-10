<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tprofile_m
 *
 * @author Garuda
 */
class tprofile_m extends CI_Model {

    //put your code here
    function home() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'tprofile/home';
        $data["userid"] = $this->session->userdata('username');
        return $data;
    }

    function view($sNopeg = "") {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'tprofile/view';
        return $data;
    }

    function table($sNopeg = "") {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'tprofile/table';
        $data['search'] = $this->get_tprofile_data();
        $data['org'] = $this->get_all_org();
        $data['pos'] = $this->get_all_pos();
        $data['stell'] = $this->get_all_stell();
        return $data;
    }

    function get_all_stell() {
        $sQuery = "select SHORT,STEXT from tm_master_abbrev where SUBTY='6' and SHORT<>'0';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array();
        for ($i = 0; $i < count($aRes); $i++) {
            $aRet[$aRes[$i]['SHORT']] = $aRes[$i]['STEXT'];
        }
        $oRes->free_result();
        return $aRet;
    }

    function get_all_org() {
        $sQuery = "select * from tm_master_org where OTYPE='O' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array();
        for ($i = 0; $i < count($aRes); $i++) {
            $aRet[$aRes[$i]['OBJID']] = $aRes[$i]['STEXT'];
        }
        $oRes->free_result();
        return $aRet;
    }

    function get_all_pos() {
        $sQuery = "select * from tm_master_org where OTYPE='S' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array();
        for ($i = 0; $i < count($aRes); $i++) {
            $aRet[$aRes[$i]['OBJID']] = $aRes[$i]['STEXT'];
        }
        $oRes->free_result();
        return $aRet;
    }

    function getLastEduc($aEducs) {
        $aEducLast = null;
        $aMax = 99;
        if ($aEducs) {

            // add index & get last educ max
            foreach ($aEducs as $i => $aEduc) {
                switch ($aEduc['SLART']) {
                    case '3' : $aEducs[$i]["IDX"] = 8;
                        if ($aMax > 8)
                            $aMax = 8;
                        break;
                    case '4' : $aEducs[$i]["IDX"] = 7;
                        if ($aMax > 7)
                            $aMax = 7;break;
                    case '5' : $aEducs[$i]["IDX"] = 6;
                        if ($aMax > 7)
                            $aMax = 7;break;
                    case '6' : $aEducs[$i]["IDX"] = 5;
                        if ($aMax > 7)
                            $aMax = 7;break;
                    case '7' : $aEducs[$i]["IDX"] = 4;
                        if ($aMax > 7)
                            $aMax = 7;break;
                    case '8' : $aEducs[$i]["IDX"] = 3;
                        if ($aMax > 3)
                            $aMax = 3;break;
                    case '9' : $aEducs[$i]["IDX"] = 2;
                        if ($aMax > 3)
                            $aMax = 3;break;
                    case '10' : $aEducs[$i]["IDX"] = 1;
                        if ($aMax > 3)
                            $aMax = 3;break;
                    default : $aEducs[$i]["IDX"] = 9;
                        if ($aMax > 9)
                            $aMax = 9;break;
                }
            }

            //sorting
            foreach ($aEducs as $rec) {
                $idx[] = $rec["IDX"];
            }
            array_multisort($idx, SORT_ASC, SORT_NUMERIC, $aEducs);

            //pop educ yg lebih besar dari max
            foreach ($aEducs as $aEduc) {
                if ($aEduc["IDX"] > $aMax) {
                    array_pop($aEducs);
                }
            }

            //print_r($aEducs);  exit;
            $aEducLast = $aEducs;
        }

        return $aEducLast;
    }

    function get_tprofile_data() {
        $sQuery = "select me.PERNR,me.CNAME,mp.NIK,mp.ORGEH PERUS,eo.ORGEH,eo.PLANS,eo.STELL,DATE_FORMAT(GBDAT,'%d-%m-%Y') as GBDAT
        ,TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS age
        ,DATE_FORMAT(TanggalMasuk,'%d-%m-%Y') as TTanggalMasuk
        ,DATE_FORMAT(TanggalPegTetap,'%d-%m-%Y') as TTanggalPegTetap
        ,DATE_FORMAT(TanggalMPP,'%d-%m-%Y') as TTanggalMPP
        ,DATE_FORMAT(TanggalPensiun,'%d-%m-%Y') as TTanggalPensiun
        ,TIMESTAMPDIFF(YEAR, eo.BEGDA, CURDATE()) AS SVCY, (TIMESTAMPDIFF(MONTH, eo.BEGDA, CURDATE()) % 12) AS SVCM
        from tm_master_emp me
        JOIN  tm_emp_org eo ON me.PERNR=eo.PERNR
        JOIN tm_mapping_pernr mp ON me.PERNR=mp.PERNR
        LEFT JOIN tm_emp_date ed ON me.PERNR=ed.PERNR and CURDATE() BETWEEN ed.BEGDA and ed.ENDDA
        WHERE CURDATE() BETWEEN eo.BEGDA AND eo.ENDDA
        AND CURDATE() BETWEEN me.BEGDA AND me.ENDDA
        AND eo.PERSG <>'3'";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getEmp($sNopeg) {
        $sQuery = "SELECT DATE_FORMAT(GBDAT,'%d-%m-%Y') as GBDAT,TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS age FROM tm_master_emp WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function getEduc($sNopeg) {
        $sQuery = "SELECT e.*, a.STEXT, b.STEXT as BIAYA, DATE_FORMAT(ENDDA,'%Y') as LULUS FROM tm_emp_educ e " .
                "LEFT JOIN tm_master_abbrev a ON e.SLART = a.SHORT AND a.SUBTY = 1 " .
                "LEFT JOIN tm_master_abbrev b ON e.SLABS = b.SHORT AND b.SUBTY = 12 " .
                "WHERE PERNR='" . $sNopeg . "' AND AUSBI = 'Formal';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getAward($sNopeg) {
        $sQuery = "SELECT ea.*,DATE_FORMAT(BEGDA,'%d-%m-%Y') as TBEGDA,ma.STEXT FROM tm_emp_awards  ea
	LEFT JOIN tm_master_abbrev ma ON ea.AWDTP=ma.SHORT
	WHERE ea.PERNR='" . $sNopeg . "' AND ma.SUBTY='8' ORDER BY ea.BEGDA DESC;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getGrievances($sNopeg) {
        $sQuery = "SELECT *,DATE_FORMAT(BEGDA,'%d-%m-%Y') as TBEGDA,DATE_FORMAT(ENDDA,'%d-%m-%Y') as TENDDA FROM tm_emp_grievances WHERE PERNR='" . $sNopeg . "' ORDER BY BEGDA DESC;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getMedical($sNopeg) {
        $sQuery = "SELECT m.*,DATE_FORMAT(BEGDA,'%d-%m-%Y') as TBEGDA, a.STEXT " .
                "FROM tm_emp_medical m LEFT JOIN tm_master_abbrev a ON a.SHORT = m.SUBTY AND a.SUBTY = 11 " .
                "WHERE PERNR='" . $sNopeg . "' ORDER BY BEGDA DESC;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_a_emp_date($sNopeg) {
        $sQuery = "SELECT *,DATE_FORMAT(TanggalMasuk,'%d-%m-%Y') as TTanggalMasuk " .
                ",DATE_FORMAT(TanggalPegTetap,'%d-%m-%Y') as TTanggalPegTetap " .
                ",DATE_FORMAT(TanggalMPP,'%d-%m-%Y') as TTanggalMPP " .
                ",DATE_FORMAT(TanggalPensiun,'%d-%m-%Y') as TTanggalPensiun " .
                "FROM tm_emp_date WHERE PERNR='" . $sNopeg . "' ORDER BY BEGDA DESC;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function getInEduc($sNopeg) {
        $sQuery = "SELECT e.*,DATE_FORMAT(e.BEGDA,'%d-%m-%Y') as TBEGDA,DATE_FORMAT(e.ENDDA,'%d-%m-%Y') as TENDDA, a.STEXT " .
                "FROM tm_emp_educ e LEFT JOIN tm_master_abbrev a ON a.SHORT = e.SLART AND a.SUBTY = 10 " .
                "WHERE e.PERNR='" . $sNopeg . "' AND e.AUSBI = 'Non Formal' ORDER BY BEGDA DESC;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getPrior($sNopeg) {
        $sQuery = "SELECT e.PLANS, o.STEXT, DATE_FORMAT(e.BEGDA,'%d-%m-%Y') as TBEGDA, DATE_FORMAT(e.ENDDA,'%d-%m-%Y') as TENDDA, e.BEGDA " .
                "FROM tm_emp_org e JOIN tm_master_org o ON e.PLANS = o.OBJID " .
                "WHERE e.PERNR='" . $sNopeg . "' " .
                "AND o.OTYPE = 'S' AND e.BEGDA BETWEEN o.BEGDA AND o.ENDDA " .
                "ORDER BY BEGDA DESC ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function getFuture($sNopeg, $sPlans) {
        $sQuery = "SELECT e.PLANS, o.STEXT, DATE_FORMAT(e.BEGDA,'%d-%m-%Y') as TBEGDA, DATE_FORMAT(e.ENDDA,'%d-%m-%Y') as TENDDA " .
                "FROM tm_emp_org e JOIN tm_master_org o ON e.PLANS = o.OBJID " .
                "WHERE e.PERNR='" . $sNopeg . "' " .
                "AND o.OTYPE = 'S' AND e.BEGDA BETWEEN o.BEGDA AND o.ENDDA " .
                "ORDER BY e.BEGDA DESC;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
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
                "LEFT JOIN tm_emp_grade g ON e.PERNR = g.PERNR AND CURDATE() BETWEEN g.BEGDA AND g.ENDDA " .
                "JOIN tm_master_org r ON r.OBJID = m.ORGEH AND r.OTYPE = 'O' AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA " .
                "JOIN tm_master_org s ON s.OBJID = o.ORGEH AND s.OTYPE = 'O' AND CURDATE() BETWEEN s.BEGDA AND s.ENDDA " .
                ($sCond == "" ? "" : "WHERE " . $sCond) . " " .
                "ORDER BY e.PERNR ASC;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_talent_desc($sMap) {
        $sQuery = "SELECT * FROM tm_talent_desc WHERE SHORT = '" . $sMap . "';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_mdg($sNopeg) {
        $sQuery = "SELECT TRFGR, TRFST, DATE_FORMAT(BEGDA,'%d-%m-%Y') as BEGDA, TIMESTAMPDIFF(YEAR, BEGDA, CURDATE()) AS MDGY, (TIMESTAMPDIFF(MONTH, BEGDA, CURDATE()) % 12) AS MDGM " .
                "FROM tm_emp_grade " .
                "WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_svc_year($sNopeg) {
        $aRtn = null;
        $sQuery = "SELECT PLANS, BEGDA, TIMESTAMPDIFF(YEAR, BEGDA, CURDATE()) AS SVCY, (TIMESTAMPDIFF(MONTH, BEGDA, CURDATE()) % 12) AS SVCM " .
                "FROM tm_emp_org " .
                "WHERE PERNR = '" . $sNopeg . "' AND BEGDA <= CURDATE() " .
                "ORDER BY BEGDA DESC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

        if (count($aRes) > 0) {
            $sTmp = "";
            $sTmpBegda = "";
            for ($i = 0; $i < count($aRes); $i++) {
                if ($sTmp == $aRes[$i]["PLANS"] || $sTmp == "") {
                    $sTmp = $aRes[$i]["PLANS"];

                    $aRtn["SVCY"] = $aRes[$i]["SVCY"];
                    $aRtn["SVCM"] = $aRes[$i]["SVCM"];
                } else {
                    break;
                }
            }
        }

        return $aRtn;
    }

}

?>
