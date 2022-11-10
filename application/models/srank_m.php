<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of srank_m
 *
 * @author Garuda
 */
class srank_m extends CI_Model {

    //put your code here
    function home() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'srank/home';
        $data["userid"] = $this->session->userdata('username');
        return $data;
    }

	function view() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'srank/view';
        return $data;
    }

	function getEmp($sNopeg) {
        $sQuery = "SELECT DATE_FORMAT(GBDAT,'%d-%m-%Y') as GBDAT,TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS age FROM tm_master_emp WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

	function getEmpOrg($sNopeg){
		$sQuery = "SELECT ORGEH, PLANS, STELL ".
				  "FROM tm_emp_org WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}

	function getPosition($sPos){
		$sQuery = "SELECT STEXT ".
				  "FROM tm_master_org ".
				  "WHERE OBJID = '" . $sPos . "' AND OTYPE = 'S' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}

	function getEmpCompt($sNopeg){
		$sQuery = "SELECT m.SHORT, COVAL ".
				  "FROM tm_emp_compt c JOIN tm_master_compt m ON m.OBJID = c.COMPT ".
				  "WHERE PERNR = '".$sNopeg."' AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function getPosCompt($sPos){
		$sQuery = "SELECT m.SHORT, m.STEXT, m.OTYPE, REQV ".
				  "FROM tm_pos_compt c JOIN tm_master_compt m ON m.OBJID = c.COMPT ".
				  "WHERE PLANS = '".$sPos."' AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				  "ORDER BY OTYPE;";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function getCompt($sPersh='41'){
		$sQuery = "SELECT OTYPE, SHORT, STEXT ".
				  "FROM tm_master_compt ".
				  "WHERE OBJID LIKE '".$sPersh."%' AND CURDATE() BETWEEN BEGDA AND ENDDA ORDER BY SHORT ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function getEmpJson($sSearch){
		$sQuery = "SELECT e.PERNR as id, e.CNAME as name ".
				  "FROM tm_master_emp e JOIN tm_mapping_pernr m ON e.PERNR = m.PERNR ".
				  "WHERE (e.PERNR LIKE '%" . $sSearch . "%' OR e.CNAME LIKE '%". $sSearch ."%') ".
				  "AND CURDATE() BETWEEN e.BEGDA AND e.ENDDA ".
				  "AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				  "LIMIT 20;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function getUnitJson($sSearch){
		$sQuery = "SELECT o.OBJID as id, o.STEXT as name ".
				  "FROM tm_master_org o ".
				  "WHERE o.STEXT LIKE '%" . $sSearch . "%' ".
				  "AND o.OTYPE = 'O' ".
				  "AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				  "LIMIT 20;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function getResultSearch($sCond){
		$sQuery = "SELECT e.PERNR, e.CNAME, o.STELL, CONCAT(g.TRFGR,g.TRFST) as GRADE, r.SHORT, s.STEXT ".
				  "FROM tm_master_emp e ".
				  "JOIN tm_emp_org o ON o.PERNR = e.PERNR AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				  "JOIN tm_mapping_pernr m ON m.PERNR = e.PERNR AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				  "JOIN tm_emp_grade g ON e.PERNR = g.PERNR AND CURDATE() BETWEEN g.BEGDA AND g.ENDDA ".
				  "JOIN tm_master_org r ON r.OBJID = m.ORGEH AND r.OTYPE = 'O' AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				  "JOIN tm_master_org s ON s.OBJID = o.ORGEH AND s.OTYPE = 'O' AND CURDATE() BETWEEN s.BEGDA AND s.ENDDA ".
				  ($sCond == ""?"":"WHERE " . $sCond) . " ".
				  "ORDER BY e.PERNR ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function get_org_level(){
		$aOrg = $this->common->get_a_org_auth();
		$sOrg = implode(",",$aOrg);

		$sQuery = "SELECT l.OBJID as id, l.LEVEL, o.SHORT as text, o.STEXT ".
				  "FROM tm_org_level l ".
				  "JOIN tm_master_org o ON o.OBJID = l.OBJID AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				  "WHERE l.OBJID IN(".$sOrg.") ".
				  "ORDER BY l.LEVEL ASC, l.SEQ ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function get_unit($q,$prefix){
		$sQuery="SELECT o.OBJID as id,o.STEXT as text
				FROM tm_master_org o
				JOIN tm_master_relation r ON r.OBJID=o.OBJID and o.OTYPE='O' AND SUBTY='A002'
				WHERE r.SCLAS='O' and r.OBJID like '$prefix%' AND r.OTYPE='O'
				AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA
				AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA
				AND (o.STEXT like '%$q%')";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

		return $aRes;
	}

	function get_position($q,$prefix){
		$sQuery="SELECT o.OBJID as id,o.STEXT as text
				FROM tm_master_org o
				JOIN tm_master_relation r ON r.OBJID=o.OBJID and o.OTYPE='S' AND SUBTY='A003'
				WHERE r.SCLAS='O' and r.SOBID LIKE '$prefix%' AND r.OTYPE='S'
				AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA
				AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA
				AND (o.STEXT like '%$q%')";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

		return $aRes;
	}

	function get_mapping_pernr($sPrsh){
		$sQuery="SELECT p.PERNR as id, m.CNAME as text ".
				"FROM tm_mapping_pernr p ".
				"JOIN tm_master_emp m ON m.PERNR = p.PERNR AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				"WHERE ORGEH = '".$sPrsh."' AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA ".
				"ORDER BY CNAME ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

		return $aRes;
	}
	
	function get_emp_selection($sNopeg="",$sGrade="",$sJob="",$sPrsh="",$sEmpFam=""){
		$sQuery="SELECT o.PERNR ".
				"FROM tm_emp_org o ".
				"JOIN tm_mapping_pernr m ON o.PERNR = m.PERNR AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				"JOIN tm_emp_grade g ON o.PERNR = g.PERNR AND CURDATE() BETWEEN g.BEGDA AND g.ENDDA ".
				"WHERE CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				($sGrade==""?"":"AND CONCAT(g.TRFGR,g.TRFST) IN (".$sGrade.") ").
				($sJob==""?"":"AND o.STELL IN(".$sJob.") ").
				($sPrsh==""?"":"AND m.ORGEH IN(".$sPrsh.") ").
				($sNopeg==""?"":"AND o.PERNR IN(".$sNopeg.") ").
				($sEmpFam==""?"":"AND o.PERNR IN(".$sEmpFam.") ");
//                echo $sQuery;exit;

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

		return $aRes;
	}

	function getPositionDesc($sPos){
		$aRtn = null;

		$aRtn['pos'] = $sPos;

		// get description
		$sQuery="SELECT STEXT ".
				"FROM tm_master_org o ".
				"WHERE OBJID = '".$sPos."' AND OTYPE = 'S' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();

		$aRtn['desc'] = $aRes["STEXT"];

		$aRes = null;
		// get unit
		$sQuery="SELECT o.STEXT, o.OBJID ".
				"FROM tm_master_org o ".
				"JOIN tm_master_relation r ON r.SOBID = o.OBJID AND r.OTYPE = 'S' AND r.SUBTY = 'A003' AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA ".
				"WHERE r.OBJID = '".$sPos."' AND o.OTYPE = 'O' AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA;";
//echo $sQuery;exit;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
		$sUnit = $aRes["OBJID"];
		$aRtn['unit'] = $aRes["STEXT"];
		$aRtn['unitid'] = $aRes["OBJID"];

		$aRes = null;
		//get persh
		$sQuery="SELECT o.SHORT, o.OBJID ".
				"FROM tm_master_org o ".
				"JOIN tm_org_level l ON l.OBJID = o.OBJID AND CURDATE() BETWEEN l.BEGDA AND l.ENDDA ".
				"WHERE l.OBJID LIKE '".substr($sUnit,0,3)."%' AND o.OTYPE = 'O' AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();

		$aRtn['prsh'] = $aRes["SHORT"];
		$aRtn['prshid'] = $aRes["OBJID"];

		$aRes = null;
		//get job Family
		$sQuery="SELECT m.STEXT as LEVEL, m.SHORT as SLEVEL, m.SEQ, c.STEXT as FAMILY ".
				"FROM tm_pos_detail p ".
				"JOIN tm_master_abbrev m ON m.SHORT = p.STELL ".
				"LEFT JOIN tm_master_compt c ON p.FAMILY = c.OBJID AND OTYPE = 'JF' AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA ".
				"WHERE p.PLANS = '".$sPos."' AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();

		if($aRes){
			$aRtn['job'] = $aRes["LEVEL"];
			$aRtn['jobshort'] = $aRes["SLEVEL"];
			$aRtn['jobseq'] = $aRes["SEQ"];
			$aRtn['fam'] = $aRes["FAMILY"];
		}else{
			$aRtn['job'] = "";
		}

		return $aRtn;
	}

	function get_emp_readiness($sPos,$iType,$sNik=""){
		$sQuery="SELECT PERNR, PERCT ".
				"FROM tm_emp_readiness ".
				"WHERE PLANS = '".$sPos."' AND SUBTY = ".$iType." ".
				($sNik==""?"":"AND PERNR IN(".$sNik.") ").
				"ORDER BY PERCT DESC LIMIT 10;";
                

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

		return $aRes;
	}

	function get_detail_emp($sNopeg){
        $sQuery = "SELECT m.CNAME, DATE_FORMAT(m.GBDAT,'%d-%m-%Y') as GBDAT, TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS AGE, o.PLANS, r.STEXT as POS, g.TRFGR as GRADE ".
				  "FROM tm_master_emp m ".
				  "JOIN tm_emp_org o ON m.PERNR = o.PERNR AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				  "JOIN tm_master_org r ON r.OBJID = o.PLANS AND r.OTYPE = 'S' AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA ".
				  "LEFT JOIN tm_emp_grade g ON g.PERNR = m.PERNR AND CURDATE() BETWEEN g.BEGDA AND g.ENDDA ".
				  "WHERE m.PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA";
//echo $sQuery."<br/>";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}
	
	function get_medical($sNopeg){
		$sQuery = "SELECT DATE_FORMAT(m.BEGDA,'%d-%m-%Y') as BEGDA, a.STEXT ".
				  "FROM tm_emp_medical m ".
				  "LEFT JOIN tm_master_abbrev a ON m.SUBTY = a.SHORT AND a.SUBTY = 11 ".
				  "WHERE m.PERNR='" . $sNopeg . "' ".
				  "ORDER BY m.BEGDA DESC ".
				  "LIMIT 1;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}

	function get_master_readiness($iReady){
		$sQuery = "SELECT DESC ".
				  "FROM tm_master_readiness m ".
				  "WHERE m.MIN <= ".$iReady." AND m.MAX >= ".$iReady.";";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}

	function get_talent_desc($sMap){
		$sQuery = "SELECT STEXT ".
				  "FROM tm_talent_desc ".
				  "WHERE SHORT = '".$sMap."';";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}

	function get_emp_plans($sPos,$iType=1,$sNik=""){
		$sQuery="SELECT PERNR, PERCENTAGE ".
				"FROM tm_emp_plans ".
				"WHERE PLANS = '".$sPos."' AND SUBTY = ".$iType." ".
				($sNik==""?"":"AND PERNR IN(".$sNik.") ").
				"ORDER BY PERNR;";

		$oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		$aRtn = null;
		$sTmp = "";
		if($aRes){
			foreach($aRes as $aEmp){
				$aRtn[$aEmp["PERNR"]] = $aEmp["PERCENTAGE"];
			}
		}

		return $aRtn;
	}

	function get_emp_criteria($sNik){
		$sQuery="SELECT PERNR, id_criteria, PERCT ".
				"FROM tm_emp_criteria ".
				($sNik==""?"":"WHERE PERNR IN(".$sNik.") ").
				"ORDER BY PERNR, id_criteria;";
//echo $sQuery;exit;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		$aRtn = null;
		$sTmp = "";
		if($aRes){
			foreach($aRes as $aCri){
				if($sTmp<>$aCri['PERNR']){
					$aRtn[$aCri['PERNR']][2] = "";
					$aRtn[$aCri['PERNR']][3] = "";
					$aRtn[$aCri['PERNR']][4] = "";
					$aRtn[$aCri['PERNR']][5] = "";
				}
				$aRtn[$aCri['PERNR']][$aCri['id_criteria']] = $aCri['PERCT'];
				$sTmp = $aCri['PERNR'];
			}
		}

		return $aRtn;
	}

	function get_ready($iReady){
		$sQuery="SELECT `DESC` ".
				"FROM tm_master_readiness ".
				"WHERE `MIN` <= ".$iReady." AND `MAX` >= ".$iReady." ";

		$oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}
	
	function get_level_org_persh($sORG){
		$sQuery="SELECT LEVEL, SEQ ".
				"FROM tm_org_level ".
				"WHERE OBJID = '".$sORG."' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

		$oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}
	
	function get_score_matrix_job($sOrg, $sJob){
		$sQuery="SELECT SCORE ".
				"FROM tm_matrix_job_score ".
				"WHERE ORGID = '".$sOrg."' AND STELL = '".$sJob."';";

		$oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
	}
	
	function get_org_stell($iLevel=1, $iJobLevel=1, $iScoreMax=1){
		if(empty($iLevel))$iLevel=1;
		if(empty($iJobLevel))$iJobLevel=1;
		if(empty($iScoreMax))$iScoreMax=1;
		$sQuery="SELECT t.ORGID, t.STELL, t.SCORE, l.`LEVEL`, a.SEQ ".
				"FROM tm_matrix_job_score t ".
				"JOIN tm_org_level l ON t.ORGID = l.OBJID AND CURDATE() BETWEEN l.BEGDA AND ENDDA ".
				"JOIN tm_master_abbrev a ON t.STELL = a.SHORT ".
				"WHERE `LEVEL` BETWEEN ".$iLevel." AND ". ($iLevel + 2) . " ".
				"AND a.SEQ = ".$iJobLevel." AND SCORE <= ".$iScoreMax." ".
				"UNION ".
				"SELECT t.ORGID, t.STELL, t.SCORE, l.`LEVEL`, a.SEQ ".
				"FROM tm_matrix_job_score t ".
				"JOIN tm_org_level l ON t.ORGID = l.OBJID AND CURDATE() BETWEEN l.BEGDA AND ENDDA ".
				"JOIN tm_master_abbrev a ON t.STELL = a.SHORT ".
		//		"WHERE `LEVEL` BETWEEN ". ($iLevel - 1) ." AND ".$iLevel." ".
				"WHERE `LEVEL` BETWEEN 0 AND ".$iLevel." ".
				"AND a.SEQ = ". ($iJobLevel + 1) ." AND SCORE <= ".$iScoreMax.";";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

		return $aRes;
	}
	
	function get_pernr($sOrg, $sJob){
		$sQuery="SELECT t.PERNR ".
				"FROM tm_emp_org t ".
				"JOIN tm_mapping_pernr m ON t.PERNR = m.PERNR AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				"WHERE m.ORGEH = '".$sOrg."' AND STELL = '".$sJob."' AND CURDATE() BETWEEN t.BEGDA AND t.ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		
		return $aRes;
	}
	
	function get_perf_config(){
		$aRtn = array();

		$sQuery="SELECT * ".
				"FROM tm_master_performance ".
				"WHERE CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

		if($aRes){
			foreach($aRes as $aOrg){
				$aRtn[$aOrg['ORGID']] = $aOrg;
			}
		}

		return $aRtn;
	}
	
	function get_mapping_pernr2($sNopeg){
		$sQuery="SELECT * ".
				"FROM tm_mapping_pernr ".
				"WHERE PERNR = '".$sNopeg."' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
		
		return $aRes;
	}

	function get_emp_perf($sNopeg){
		$sQuery="SELECT NILAI, DATE_FORMAT(BEGDA,'%Y%m%d') as BEGDA, DATE_FORMAT(ENDDA,'%Y%m%d') as ENDDA ".
				"FROM tm_emp_perf ".
				"WHERE PERNR = '".$sNopeg."' ".
				"ORDER BY ENDDA DESC ";
				//"LIMIT 1;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		
		return $aRes;		
	}

	function get_emp_pot($iPCT){
		$sQuery="SELECT LEVEL ".
				"FROM tm_master_potential ".
				"WHERE ".$iPCT." BETWEEN MIN AND MAX;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
		
		return $aRes;
	}
	
	function get_stell_nons($sStell){
		$sQuery="SELECT a.SHORT ".
				"FROM tm_master_abbrev a ".
				"WHERE a.SHORT <> '".$sStell."' AND a.SUBTY = 6 ".
				"AND a.SSTYP = (SELECT m.SSTYP FROM tm_master_abbrev m WHERE m.SHORT = '".$sStell."' AND m.SUBTY = 6);";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		
		return $aRes;
	}
	
	function get_avg_compt($sNopeg){
		$iRtn = 0;
		$sQuery="SELECT COMPT, COVAL ".
				"FROM tm_emp_compt ".
				"WHERE PERNR = '".$sNopeg."' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		
		if(count($aRes)>0){
			for($i=0;$i<count($aRes);$i++){
				$iRtn += $aRes[$i]["COVAL"];
			}
			$iRtn = $iRtn / count($aRes);
		}
		
		return number_format($iRtn, 2, ',', '.');
	}
	
	function get_mdg($sNopeg) {
        $sQuery = "SELECT TRFGR, TRFST, DATE_FORMAT(BEGDA,'%d-%m-%Y') as BEGDA, TIMESTAMPDIFF(YEAR, BEGDA, CURDATE()) AS MDGY, (TIMESTAMPDIFF(MONTH, BEGDA, CURDATE()) % 12) AS MDGM ".
				  "FROM tm_emp_grade ".
				  "WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }
	
	function get_jobfam(){
		$sQuery = "SELECT OBJID as id, STEXT as text ".
				  "FROM tm_master_compt ".
				  "WHERE OTYPE = 'JF' AND CURDATE() BETWEEN BEGDA AND ENDDA ";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}
	
	function get_emp_fam($sFam="-1"){
		$sQuery = "SELECT * FROM tm_emp_jobfam t ".
				  ($sFam==""?";":" WHERE FAMILY IN(".$sFam.");");
//                $sQuery="SELECT PERNR FROM tm_emp_org WHERE FAMILY IN(".$sFam.") 
//                        UNION 
//                        SELECT PERNR FROM tm_emp_org_old WHERE FAMILY IN(".$sFam.")";
		$oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}

	function get_relation_jobfam($sPos){
		$sRtn = "";
		$sQuery = "SELECT OBJID ".
				  "FROM tm_pos_detail p ".
				  "JOIN tm_job_fam_relation r ON p.FAMILY = r.OBJID AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA ".
				  "WHERE p.PLANS='" . $sPos . "' AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA;";
//                echo $sQuery;exit;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		
		if(count($aRes)>0){
			for($i=0;$i<count($aRes);$i++){
				$sRtn .= ($sRtn==""?"":",") . $aRes[$i]["OBJID"];
			}
		}
		
        return $sRtn;
	}
}

?>