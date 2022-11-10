<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pmatch_m
 *
 * @author Garuda
 */
class pmatch_m extends CI_Model {

    //put your code here
    function home() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'pmatch/home';
        $data["userid"] = $this->session->userdata('username');
        return $data;
    }

	function view() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'pmatch/view';
        return $data;
    }

	function getEmp($sNopeg) {
        $sQuery = "SELECT DATE_FORMAT(GBDAT,'%d.%m.%Y') as GBDAT,TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS age FROM tm_master_emp WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
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

		$sQuery = "SELECT l.OBJID as id, l.LEVEL, o.SHORT, o.STEXT  as text ".
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

	function get_position($q,$org_unit){
		$sWhere = "";
		foreach($org_unit as $sorg){
			$sPrefix = substr($sorg,0,3);
			$sWhere .= ($sWhere==""?"":" OR ") . " r.SOBID LIKE '".$sPrefix."%' ";
		}

		$sQuery="SELECT o.OBJID as id,o.STEXT as text
				FROM tm_master_org o
				JOIN tm_master_relation r ON r.OBJID=o.OBJID and o.OTYPE='S' AND SUBTY='A003'
				WHERE r.SCLAS='O' and (".$sWhere.") AND r.OTYPE='S'
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

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
		$sUnit = $aRes["OBJID"];
		$aRtn['unit'] = $aRes["STEXT"];
		
		$aRes = null;
		//get persh
		$sQuery="SELECT o.SHORT ".
				"FROM tm_master_org o ".
				"JOIN tm_org_level l ON l.OBJID = o.OBJID AND CURDATE() BETWEEN l.BEGDA AND l.ENDDA ".
				"WHERE l.OBJID LIKE '".substr($sUnit,0,3)."%' AND o.OTYPE = 'O' AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
		
		$aRtn['prsh'] = $aRes["SHORT"];
		
		$aRes = null;
		//get job Family
		$sQuery="SELECT m.STEXT as LEVEL, c.STEXT as FAMILY ".
				"FROM tm_pos_detail p ".
				"JOIN tm_master_abbrev m ON m.SHORT = p.STELL ".
				"LEFT JOIN tm_master_compt c ON p.FAMILY = c.OBJID AND OTYPE = 'JF' AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA ".
				"WHERE p.PLANS = '".$sPos."' AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
		
		if($aRes){
			$aRtn['job'] = $aRes["LEVEL"];
			$aRtn['fam'] = $aRes["FAMILY"];
		}else{
			$aRtn['job'] = "";
		}

		return $aRtn;
	}
	
	function get_pos_readiness($sNopeg,$iType,$sPos=""){
		$sQuery="SELECT e.PLANS, e.PERCT ".
				"FROM tm_emp_readiness e ".
				"JOIN tm_master_org m ON e.PLANS = m.OBJID AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
				"WHERE e.PERNR = '".$sNopeg."' AND e.SUBTY = ".$iType." ".
				($sPos==""?"":"AND e.PLANS IN(".$sPos.") ").
				"ORDER BY e.PERCT DESC;";
//echo $sQuery;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		
		return $aRes;
	}
	
	function get_detail_emp($sNopeg){
        $sQuery = "SELECT m.CNAME, TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS AGE, r.STEXT as POS, CONCAT(g.TRFGR,g.TRFST) as GRADE ".
				  ",a.STEXT as JOB, r3.STEXT as UNIT, r2.STEXT as COMPANY ".
				  "FROM tm_master_emp m ".
				  "JOIN tm_emp_org o ON m.PERNR = o.PERNR AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				  "JOIN tm_master_org r ON r.OBJID = o.PLANS AND r.OTYPE = 'S' AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA ".
				  "JOIN tm_master_abbrev a ON o.STELL = a.SHORT AND a.SUBTY = 6 ".
				  "JOIN tm_mapping_pernr p ON p.PERNR = m.PERNR AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA ".
				  "JOIN tm_master_org r2 ON r2.OBJID = p.ORGEH AND r2.OTYPE = 'O' AND CURDATE() BETWEEN r2.BEGDA AND r2.ENDDA ".
				  "JOIN tm_master_org r3 ON r3.OBJID = o.ORGEH AND r3.OTYPE = 'O' AND CURDATE() BETWEEN r3.BEGDA AND r3.ENDDA ".
				  "JOIN tm_emp_grade g ON g.PERNR = m.PERNR AND CURDATE() BETWEEN g.BEGDA AND g.ENDDA ".
				  "WHERE m.PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA";

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
	
	function get_emp_criteria($sNik){
		$sQuery="SELECT PERNR, id_criteria, PERCT ".
				"FROM tm_emp_criteria ".
				"WHERE PERNR = '".$sNik."' ".
				"ORDER BY PERNR, id_criteria;";

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
	
	function get_emp_plans($sNik,$iType,$sPos=""){
		$sQuery="SELECT PLANS, PERCENTAGE ".
				"FROM tm_emp_plans ".
				"WHERE PERNR = '".$sNik."' AND SUBTY = ".$iType." ".
				($sPos==""?"":"AND PLANS IN(".$sPos.") ").
				"ORDER BY PLANS;";

		$oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
		$aRtn = null;
		$sTmp = "";
		if($aRes){
			foreach($aRes as $aPos){
				$aRtn[$aPos["PLANS"]] = $aPos["PERCENTAGE"];
			}
		}

		return $aRtn;
	}
	
	function get_pos_selection($sPrsh,$sJob,$sPos){
		// Jangan ke tm_emp_org !!!
		// Klo Pos ada isinya lgs keluarin Pos aja, klo ga ada baru lanjut
		// looping per Prsh
		// Cari Prefix dari Prsh
		// Cari Plans dari Prefix tsb 

		$sRtn = "";
		if($sPos<>""){
			$sRtn = $sPos;
		}else{
			$sPlans = "";
			if($sJob<>""){
				$sQuery = "SELECT PLANS FROM tm_pos_detail WHERE STELL IN(".$sJob.") AND CURDATE() BETWEEN BEGDA AND ENDDA;";
				$oRes = $this->db->query($sQuery);
				$aRes = $oRes->result_array();
				$oRes->free_result();
				
				if(count($aRes)>0){
					for($j=0;$j<count($aRes);$j++){
						$sPlans .= ($sPlans==""?"":",") . $aRes[$j]['PLANS'];
					}
				}
				
				reset($aRes);
			}
			
			$sOBJID = "";
			$sPlans2 = "";
			
			if($sPrsh<>""){
				$aPrsh = explode(",",$sPrsh);
				for($i=0;$i<count($aPrsh);$i++){
					//$sPrefix = "2" . substr($aPrsh[$i],1,2);
					$sPrefix = "2". substr($aPrsh[$i],1,1);
					$sOBJID .= ($sOBJID==""?"":" OR ")." m.OBJID LIKE '".$sPrefix."%' ";
				}
			}
				
			$sQuery = "SELECT m.OBJID ".
					  "FROM tm_master_org m ".
					  "WHERE m.OTYPE = 'S' AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ".
					  ($sOBJID==""?"":"AND (".$sOBJID.") ") . 
					  ($sPlans==""?"":"AND OBJID IN(".$sPlans.") ");

			$oRes = $this->db->query($sQuery);
			$aRes = $oRes->result_array();
			$oRes->free_result();
			
			if(count($aRes)>0){
				for($k=0;$k<count($aRes);$k++){
					$sPlans2 .= ($sPlans2==""?"":",") . $aRes[$k]['OBJID'];
				}
			}
			
			$sRtn = $sPlans2;
		}

		return $sRtn;
	}
	
	function get_mdg($sNopeg) {
        $sQuery = "SELECT TRFGR, TRFST, DATE_FORMAT(BEGDA,'%d-%m-%Y') as BEGDA, TIMESTAMPDIFF(YEAR, BEGDA, CURDATE()) AS MDGY, TIMESTAMPDIFF(MONTH, BEGDA, CURDATE()) AS MDGM ".
				  "FROM tm_emp_grade ".
				  "WHERE PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }
	
	function get_jobfam(){
		$sQuery = "SELECT OBJID as id, STEXT  as text ".
				  "FROM tm_master_compt ".
				  "WHERE OTYPE = 'JF' AND CURDATE() BETWEEN BEGDA AND ENDDA ".
				  "ORDER BY STEXT ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
	}
}

?>