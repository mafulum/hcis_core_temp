<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gen_machine
 *
 * @author Garuda
 */
class gen_machine extends CI_Model {

    //put your code here
    var $STYPE_PLANSD = "PLANSD";
    var $STYPE_PLANS = "PLANS";
    var $STYPE_PERNR = "PERNR";
    var $STYPE_AGE = "AGE";
    var $STYPE_EDU = "EDU";
    var $STYPE_MED = "MED";
    var $STYPE_PERF = "PERF";
    var $SUBTY_CRITERIA_PERFORMANCE = 2;
    var $SUBTY_CRITERIA_AGE = 3;
    var $SUBTY_CRITERIA_EDU = 4;
    var $SUBTY_CRITERIA_MEDICAL = 5;

    function __construct() {
        parent::__construct();
        $this->load->model('orgchart_m');
        $this->load->model('ecs_m');
    }
    
    function get_from_job_compt() {
        $sQuery = "SELECT STELL,FAMILY FROM tm_job_compt GROUP BY STELL,FAMILY";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() == 0)
            return null;
        $aRes = $oRes->result_array();
        $oRes->free_result();
        $aRet=array();
        $aKey=array();
        for ($i = 0; $i < count($aRes); $i++) {
            $aConfig = $this->get_job_fam_config($aRes[$i]['STELL']);
            if (!empty($aConfig) && $aConfig['IS_FAM'] == 1) {
                $sQuery = "SELECT PLANS FROM tm_pos_detail WHERE STELL='" . $aRes[$i]['STELL'] . "' AND FAMILY='" . $aRes[$i]['FAMILY'] . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
            } else if (!empty($aConfig)) {
                $sQuery = "SELECT PLANS FROM tm_pos_detail WHERE STELL='" . $aRes[$i]['STELL'] . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
            }

            $oRes2 = $this->db->query($sQuery);
            if ($oRes2->num_rows() > 0) {
                $aRes2 = $oRes2->result_array();
                for ($j = 0; $j < count($aRes2); $j++) {
                    if(empty($aKey[$aRes2[$j]['PLANS']])){
                        $aKey[$aRes2[$j]['PLANS']]=$aRes2[$j]['PLANS'];
                        $aRet[]=$aRes2[$j]['PLANS'];
                    }
                }
            }
            $oRes2->free_result();
        }

        return $aRet;
    }

//    function gen_from_pos_detail() {
//        //insert all tm_pos_compt
//        $sQuery = "INSERT INTO tm_gen_trigger(`P_TYPE`,`P_VALUE`,`job_date`,`job_status`,`last_update`) 
//            SELECT '$this->STYPE_PLANS',PLANS,NOW(),2,NOW() FROM tm_pos_detail GROUP BY PLANS";
//        $this->db->query($sQuery);
//    }
//
//    function gen_from_job_compt() {
//        $sQuery = "SELECT STELL,FAMILY FROM tm_job_compt GROUP BY STELL,FAMILY";
//        $oRes = $this->db->query($sQuery);
//        if ($oRes->num_rows() == 0)
//            return null;
//        $aRes = $oRes->result_array();
//        $oRes->free_result();
//        for ($i = 0; $i < count($aRes); $i++) {
//            $aConfig = $this->get_job_fam_config($aRes[$i]['STELL']);
//            if (!empty($aConfig) && $aConfig['IS_FAM'] == 1) {
//                $sQuery = "SELECT PLANS FROM tm_pos_detail WHERE STELL='" . $aRes[$i]['STELL'] . "' AND FAMILY='" . $aRes[$i]['FAMILY'] . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
//            } else if (!empty($aConfig)) {
//                $sQuery = "SELECT PLANS FROM tm_pos_detail WHERE STELL='" . $aRes[$i]['STELL'] . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
//            }
//            $oRes = $this->db->query($sQuery);
//            if ($oRes->num_rows() > 0) {
//                $aRes = $oRes->result_array();
//                for ($i = 0; $i < count($aRes); $i++) {
//                    $this->save_to_trigger($this->STYPE_PLANS, $aRes[$i]['PLANS']);
//                }
//            }
//            $oRes->free_result();
//        }
//    }
//
    function get_job_fam_config($STELL) {
        $sQuery = "SELECT IS_FAM FROM tm_job_fam_config WHERE STELL='$STELL'";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() == 0)
            return null;
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
//
//    function gen_from_empcompt() {
//        //insert allt tm_emp_cmpt
//        $sQuery = "INSERT INTO tm_gen_trigger(`P_TYPE`,`P_VALUE`,`job_date`,`job_status`,`last_update`) 
//            SELECT '$this->STYPE_PERNR',PERNR,NOW(),2,NOW() FROM tm_emp_compt GROUP BY PERNR";
//        $this->db->query($sQuery);
//    }
//
//    function gen_from_age() {
//        $sQuery = "INSERT INTO tm_gen_trigger(`P_TYPE`,`P_VALUE`,`job_date`,`job_status`,`last_update`) 
//            SELECT '$this->STYPE_AGE',PERNR,NOW(),2,NOW() FROM tm_master_emp WHERE GBDAT IS NOT NULL AND GBDAT<>'0000-00-00' AND CURDATE() BETWEEN BEGDA AND ENDDA AND DATEDIFF(CURRENT_DATE,GBDAT)>0 AND PERNR is not null AND PERNR<>0";
//        $this->db->query($sQuery);
//    }
//
//    function gen_daily_age() {
//        $sQuery = "SELECT PERNR FROM tm_master_emp where GBDATE=CUR_DATE() AND CURDATE() BETWEEN BEGDA AND ENDDA;";
//        $oRes = $this->db->query($sQuery);
//        if ($oRes->num_row() > 0) {
//            $aRes = $oRes->result_array();
//            $oRes->free_result();
//            for ($i = 0; $i < count($aRes); $i++) {
//                $this->save_to_trigger($this->STYPE_AGE, $aRes[$i]['PERNR']);
//            }
//            unset($aRes);
//        }
//    }
//
//    function gen_from_educ() {
//        $sQuery = "SELECT PERNR,ee.id_educ,SLART,PERCT FROM tm_emp_educ ee
//JOIN tm_master_abbrev ma ON ee.AUSBI='Formal' AND ma.SUBTY='1' AND ee.SLART=ma.SHORT
//JOIN tm_criteria_readiness_detail crd ON crd.id_criteria='4' AND crd.SUBTY='1' AND ma.STEXT=crd.`MIN`
//where AUSBI='Formal' AND ee.AUSBI='Formal' AND ma.SUBTY='1' AND crd.id_criteria='4' AND crd.SUBTY='1'
//ORDER BY ma.id_abbrv DESC ";
//        $oRes = $this->db->query($sQuery);
//        if ($oRes->num_rows() > 0) {
//            $aRes = $oRes->result_array();
//            $oRes->free_result();
//            $aLock = array();
//            for ($i = 0; $i < count($aRes); $i++) {
//                if (empty($aLock[$aRes[$i]['PERNR']])) {
//                    $aLock[$aRes[$i]['PERNR']] = $aRes[$i]['id_educ'];
//                    $this->save_to_trigger($this->STYPE_EDU, $aRes[$i]['id_educ']);
//                }
//            }
//            unset($aRes);
//        }
//    }
//
//    function gen_from_medic() {
//        $sQuery = "SELECT id_medical,PERNR,STYPE,BEGDA FROM tm_emp_medical ORDER BY BEGDA DESC ";
//        $oRes = $this->db->query($sQuery);
//        if ($oRes->num_rows() > 0) {
//            $aRes = $oRes->result_array();
//            $oRes->free_result();
//            $aLock = array();
//            for ($i = 0; $i < count($aRes); $i++) {
//                if (empty($aLock[$aRes[$i]['PERNR']])) {
//                    $aLock[$aRes[$i]['PERNR']] = $aRes[$i]['id_medical'];
//                    $this->save_to_trigger($this->STYPE_MED, $aRes[$i]['id_medical']);
//                }
//            }
//            unset($aRes);
//        }
//    }
//
//    function gen_from_performance() {
//        $sQuery = "INSERT INTO tm_gen_trigger(`P_TYPE`,`P_VALUE`,`job_date`,`job_status`,`last_update`) 
//            SELECT '$this->STYPE_PERF',PERNR,NOW(),2,NOW() FROM tm_emp_perf GROUP BY PERNR";
//        $this->db->query($sQuery);
//    }

    function check_existing($sType, $sVal) {
        $sQuery = "SELECT * FROM tm_gen_trigger WHERE P_TYPE='$sType' AND P_VALUE='$sVal' AND job_status='2'";
        $oRes = $this->db->query($sQuery);
        $sRet = FALSE;
        if ($oRes->num_rows() > 0) {
            $sRet = TRUE;
        }

        $oRes->free_result();
        return $sRet;
    }

    function save_to_trigger($sType, $sVal) {
        $f = $this->check_existing($sType, $sVal);
        if ($f == TRUE)
            return null;
        $sQuery = "INSERT INTO tm_gen_trigger(`P_TYPE`,`P_VALUE`,`job_date`,`job_status`,`last_update`) 
            VALUES ('$sType','$sVal',CURDATE(),2,CURDATE())";
        $this->db->query($sQuery);
    }

    function run_trigger() {
        $sQuery = "SELECT * FROM tm_gen_trigger where job_status=2 ";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            for ($i = 0; $i < count($aRes); $i++) {
                $aJob = $aRes[$i];
                if ($aJob['P_TYPE'] == $this->STYPE_PERNR) {
                    $aRet = $this->run_job_pernr($aJob);
                } else if ($aJob['P_TYPE'] == $this->STYPE_PLANS) {
                    $aRet = $this->run_job_plans($aJob);
                } else if ($aJob['P_TYPE'] == $this->STYPE_PLANSD) {
                    $aRet = $this->run_job_plansd($aJob);
                } else if ($aJob['P_TYPE'] == $this->STYPE_PERF) {
                    $aRet = $this->run_job_perf($aJob);
                } else if ($aJob['P_TYPE'] == $this->STYPE_EDU) {
                    $aRet = $this->run_job_educ($aJob);
                } else if ($aJob['P_TYPE'] == $this->STYPE_MED) {
                    $aRet = $this->run_job_medic($aJob);
                } else if ($aJob['P_TYPE'] == $this->STYPE_AGE) {
                    $aRet = $this->run_job_age($aJob);
                }
                $sQuery = "UPDATE tm_gen_trigger SET job_status='" . $aRet[0] . "',note='" . $aRet[1] . "',updated_by = '".$this->session->userdata('username')."',last_update=NOW() WHERE id_job='" . $aJob['id_job'] . "'";
echo $sQuery;
                $this->db->query($sQuery);
            }
        }
        $oRes->free_result();
    }

    function run_job_medic($aJob) {
        $sQuery = "select em.PERNR,PERCT from tm_emp_medical em
JOIN tm_master_abbrev ma ON  ma.SUBTY=11 AND em.SUBTY=ma.SHORT
JOIN tm_criteria_readiness_detail crd ON crd.id_criteria='5' AND crd.SUBTY='1' AND ma.STEXT=crd.`MIN`
WHERE ma.SUBTY=11 AND em.PERNR='" . $aJob['P_VALUE'] . "' AND crd.id_criteria='5' AND crd.SUBTY='1' ORDER BY em.BEGDA DESC LIMIT 1";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRow = $oRes->row_array();
            $sQuery = "REPLACE INTO tm_emp_criteria(`PERNR`,`id_criteria`,`PERCT`,`DATE`) 
                    VALUES('" . $aRow['PERNR'] . "','" . $this->SUBTY_CRITERIA_MEDICAL . "','" . $aRow['PERCT'] . "',NOW())";
            $this->db->query($sQuery);
            return array(1, "SUCCESS");
        }
        return array(1, "SUCCESS Empty Data");
    }

    function run_job_educ($aJob) {
        $sQuery = "SELECT ee.PERNR,PERCT FROM tm_emp_educ ee
JOIN tm_master_abbrev ma ON ee.AUSBI='Formal' AND ma.SUBTY='1' AND ee.SLART=ma.SHORT
JOIN tm_criteria_readiness_detail crd ON crd.id_criteria='4' AND crd.SUBTY='1' AND ma.STEXT=crd.`MIN`
where AUSBI='Formal' AND ee.AUSBI='Formal' AND ma.SUBTY='1' AND crd.id_criteria='4' AND crd.SUBTY='1'
AND ee.PERNR=" . $aJob['P_VALUE']."  ORDER BY ee.ENDDA DESC limit 1";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRow = $oRes->row_array();
            $sQuery = "REPLACE INTO tm_emp_criteria(`PERNR`,`id_criteria`,`PERCT`,`DATE`) 
                    VALUES('" . $aRow['PERNR'] . "','" . $this->SUBTY_CRITERIA_EDU . "','" . $aRow['PERCT'] . "',NOW())";
            $this->db->query($sQuery);
            return array(1, "SUCCESS");
        }
        return array(1, "SUCCESS Empty Data");
    }

    function run_job_age($aJob) {
        $sQuery = "SELECT DATEDIFF(CURRENT_DATE,GBDAT)/365.25 usia FROM tm_master_emp WHERE PERNR='" . $aJob['P_VALUE'] . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0 && !empty($aRow['usia'])) {
            $aRow = $oRes->row_array();
            $sQuery = "SELECT PERCT FROM tm_criteria_readiness_detail WHERE id_criteria=3 and SUBTY=2 AND " . $aRow['usia'] . " BETWEEN MIN AND MAX ";
            $oRes2 = $this->db->query($sQuery);
            if ($oRes2->num_rows() > 0) {
                $aRow2 = $oRes2->row_array();
                $sQuery = "REPLACE INTO tm_emp_criteria(`PERNR`,`id_criteria`,`PERCT`,`DATE`) 
                    VALUES('" . $aJob['P_VALUE'] . "','" . $this->SUBTY_CRITERIA_AGE . "','" . $aRow2['PERCT'] . "',NOW())";
                $this->db->query($sQuery);
                return array(1, "SUCCESS");
            } else {
                return array(1, "SUCCESS Not Classified");
            }
        }
        return array(1, "SUCCESS Empty Data");

//        $sQuery="SELECT pernr,gbdat,DATEDIFF(CURRENT_DATE,GBDAT)/365.25 usia FROM tm_master_emp where GBDAT<>'0000-00-00' and GBDAT IS NOT NULL"
//        date_diff($object, $object2, $absolute)
    }

    function run_job_perf($aJob) {
        $aEmpPerf = $this->get_tm_emp_perf($aJob['P_VALUE']);
        $sRet = 0;
        if (!empty($aEmpPerf)) {
            $sNopeg_prefix = substr($aJob['P_VALUE'], 0, 3);
            $sShortConfig = $this->orgchart_m->get_short_by_prefix('PERNR', $sNopeg_prefix);
            $sOrgSeq = $this->orgchart_m->get_config_by_short('ORG', $sShortConfig);
            $sOrg_prefix = substr($sOrgSeq, 0, 3);
            $aPerfConfig = $this->get_master_performance_prefix($sOrg_prefix);
            $aPerfPercentage = $this->get_tm_criteria_readiness_detail_performance();
            $sText = "";
            if (!empty($aPerfConfig)) {
                $sTot = 0;
                for ($i = 0; $i < count($aEmpPerf); $i++) {
                    $iNilai = $aEmpPerf[$i]['NILAI'];
                    if ($iNilai >= $aPerfConfig['LMIN'] && $iNilai <= $aPerfConfig['LMAX']) {
                        $sTot+=$aPerfPercentage['L'];
                    } else if ($iNilai >= $aPerfConfig['MMIN'] && $iNilai <= $aPerfConfig['MMAX']) {
                        $sTot+=$aPerfPercentage['M'];
                    } else if ($iNilai >= $aPerfConfig['HMIN'] && $iNilai <= $aPerfConfig['HMAX']) {
                        $sTot+=$aPerfPercentage['H'];
                    }
                }
                $sRet = $sTot / 3;
            } else {
                $sText = "No Mapping";
            }
            $sQuery = "REPLACE INTO tm_emp_criteria(`PERNR`,`id_criteria`,`PERCT`,`DATE`) VALUES('" . $aJob['P_VALUE'] . "','" . $this->SUBTY_CRITERIA_PERFORMANCE . "','" . $sRet . "',NOW())";
            $this->db->query($sQuery);
            return array(1, "SUCCESS " . $sText);
        }
        return array(1, "SUCCESS Empty Data");
    }

    function get_master_performance_prefix($sOrg_prefix) {
        $sQuery = "SELECT * FROM tm_master_performance where LEFT(ORGID,3)=$sOrg_prefix";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRow = $oRes->row_array();
            $oRes->free_result();
            return $aRow;
        }
        return null;
    }

    function get_tm_emp_perf($sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_perf WHERE PERNR='$sNopeg' ORDER BY ENDDA DESC";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            $oRes->free_result();
            $aRet = array();
            for ($i = 0; $i < count($aRes); $i++) {
                if ($i >= 3)
                    break;
                $aRet[] = $aRes[$i];
            }
            return $aRet;
        }
    }

    function run_job_pernr($aJob) {
        $aEmpCompt = $this->get_tm_empcompt($aJob['P_VALUE']);
        //SUBTY 2
        $aAllPos = $this->get_all_tm_poscompt();
        $aMap = array();
        $aKey = array();
        $sCurPos = "";
        for ($i = 0; $i < count($aAllPos); $i++) {
            if ($sCurPos != $aAllPos[$i]['PLANS']) {
                $sCurPos = $aAllPos[$i]['PLANS'];
                $aKey[] = $sCurPos;
            }
            $aMap[$sCurPos][] = $aAllPos[$i];
        }
        for ($i = 0; $i < count($aKey); $i++) {
            $aVal = $this->compareCompt($aMap[$aKey[$i]], $aEmpCompt);
            $nVal = $aVal[2]['TotM'];
            $sQuery = "REPLACE INTO tm_emp_plans (`PERNR`,`PLANS`,`PERCENTAGE`,`RUNDATE`,`LASTPROC`,`SUBTY`)
            values ('" . $aJob['P_VALUE'] . "','" . $aKey[$i] . "','$nVal',NOW(),NOW(),'2');    
            ";
            $this->db->query($sQuery);
        }
        //SUBTY 1
        $aAllPos=$this->get_from_job_compt();
        for($i=0;$i<count($aAllPos);$i++){
            $aPosCompts=$this->get_job_plans_subty1($aAllPos[$i]);
            $aVal = $this->compareCompt($aPosCompts, $aEmpCompt);
            $nVal = $aVal[2]['TotM'];
            $sQuery = "REPLACE INTO tm_emp_plans (`PLANS`,`PERNR`,`PERCENTAGE`,`RUNDATE`,`LASTPROC`,`SUBTY`)
            values ('" . $aAllPos[$i] . "','" . $aJob['P_VALUE'] . "','$nVal',NOW(),NOW(),'1');    
            ";

            $this->db->query($sQuery);
            
        }
//        $aPosDetail = $this->ecs_m->getPosDetail($aJob['P_VALUE']);
//        $aJobConfig = $this->ecs_m->getJobFamConfig($aPosDetail["STELL"]);
//        $aPosCompts = $this->ecs_m->getJobCompt($aPosDetail["STELL"], $aPosDetail["FAMILY"], $aJobConfig["IS_FAM"]);
        
        return array(1, "SUCCESS");
    }

    function compareCompt($aPosCompts, $aEmpCompts) {
        $aRtn = null;
        foreach ($aEmpCompts as $aEmpCompt) {
            $aEmp[$aEmpCompt['SHORT']] = $aEmpCompt['COVAL'];
        }
        $sTmp = '';
        $iSubM = 0;
        $iSubG = 0;
        $iSub = 0;
        $iTotM = 0;
        $iTotG = 0;
        $iTot = 0;
        foreach ($aPosCompts as $aPosCompt) {
            $aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Pos'] = $aPosCompt['REQV'];
            $aEmp[$aPosCompt['SHORT']] = (array_key_exists($aPosCompt['SHORT'], $aEmp) ? $aEmp[$aPosCompt['SHORT']] : 0);
            $aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Emp'] = $aEmp[$aPosCompt['SHORT']];

            $iDiff = $aEmp[$aPosCompt['SHORT']] - $aPosCompt['REQV'];
            if ($iDiff > 0)
                $iDiff = 0;
            $iPct = $aEmp[$aPosCompt['SHORT']] / $aPosCompt['REQV'] * 100;
            if ($iPct > 100)
                $iPct = 100;

            $aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Match'] = $iPct;
            $aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Gap'] = $iDiff;

            if ($sTmp <> $aPosCompt['OTYPE']) {
                $aRtn[1][$aPosCompt['OTYPE']]['SubM'] = $iPct;
                $aRtn[1][$aPosCompt['OTYPE']]['SubG'] = $iDiff;
                $sTmp = $aPosCompt['OTYPE'];
                $iSub = 1;
            } else {
                $aRtn[1][$aPosCompt['OTYPE']]['SubM'] += $iPct;
                $aRtn[1][$aPosCompt['OTYPE']]['SubG'] += $iDiff;
                $iSub++;
            }
            $aRtn[1][$aPosCompt['OTYPE']]['Sub'] = $iSub;

            $iTotM += $iPct;
            $iTotG += $iDiff;
            $iTot++;
        }

        $aRtn[2]['TotM'] = $iTotM / $iTot;
        $aRtn[2]['TotG'] = $iTotG;

        return $aRtn;
    }
    
    function run_job_plansd($aJob) {
        $aPosCompts = $this->ecs_m->getPosCompt($aJob['P_VALUE']);
//        $aPos = $this->get_tm_poscompt($aJob['P_VALUE']);
//        $aPosDetail = $this->ecs_m->getPosDetail($sCompPos);
//        $aPosDetail = $this->ecs_m->getPosDetail($aJob['P_VALUE']);
//        $aJobConfig = $this->ecs_m->getJobFamConfig($aPosDetail["STELL"]);
//        $aPosCompts = $this->ecs_m->getJobCompt($aPosDetail["STELL"], $aPosDetail["FAMILY"], $aJobConfig["IS_FAM"]);
        if (empty($aPosCompts)) {
            return array(1, "No Data Compt");
        }
        $aAllEmp = $this->get_all_tm_empcompt();
        $aMap = array();
        $aKey = array();
        $sCurPernr = "";
        for ($i = 0; $i < count($aAllEmp); $i++) {
            if ($sCurPernr != $aAllEmp[$i]['PERNR']) {
                $sCurPernr = $aAllEmp[$i]['PERNR'];
                $aKey[] = $sCurPernr;
            }
            $aMap[$sCurPernr][] = $aAllEmp[$i];
        }
        for ($i = 0; $i < count($aKey); $i++) {
            $aVal = $this->compareCompt($aPosCompts, $aMap[$aKey[$i]]);
            $nVal = $aVal[2]['TotM'];
            $sQuery = "REPLACE INTO tm_emp_plans (`PLANS`,`PERNR`,`PERCENTAGE`,`RUNDATE`,`LASTPROC`,`SUBTY`)
            values ('" . $aJob['P_VALUE'] . "','" . $aKey[$i] . "','$nVal',NOW(),NOW(),'2');    
            ";
            $this->db->query($sQuery);
        }

        return array(1, "SUCCESS");
    }
    function get_job_plans_subty1($plans){
        $aPosDetail = $this->ecs_m->getPosDetail($plans);
        $aJobConfig = $this->ecs_m->getJobFamConfig($aPosDetail["STELL"]);
	if(empty($aJobConfig['IS_FAM']))$aJobCOnfig['IS_FAM']="0";
        $aPosCompts = $this->ecs_m->getJobCompt($aPosDetail["STELL"], $aPosDetail["FAMILY"], $aJobConfig["IS_FAM"]);
        return $aPosCompts;
    }

    function run_job_plans($aJob) {
        $aPosCompts=$this->get_job_plans_subty1($aJob['P_VALUE']);
        if (empty($aPosCompts)) {
            return array(1, "No Data Compt");
        }
        $aAllEmp = $this->get_all_tm_empcompt();
        $aMap = array();
        $aKey = array();
        $sCurPernr = "";
        for ($i = 0; $i < count($aAllEmp); $i++) {
            if ($sCurPernr != $aAllEmp[$i]['PERNR']) {
                $sCurPernr = $aAllEmp[$i]['PERNR'];
                $aKey[] = $sCurPernr;
            }
            $aMap[$sCurPernr][] = $aAllEmp[$i];
        }
        for ($i = 0; $i < count($aKey); $i++) {
            $aVal = $this->compareCompt($aPosCompts, $aMap[$aKey[$i]]);
            $nVal = $aVal[2]['TotM'];
            $sQuery = "REPLACE INTO tm_emp_plans (`PLANS`,`PERNR`,`PERCENTAGE`,`RUNDATE`,`LASTPROC`,`SUBTY`)
            values ('" . $aJob['P_VALUE'] . "','" . $aKey[$i] . "','$nVal',NOW(),NOW(),'1');    
            ";
            $this->db->query($sQuery);
        }

        return array(1, "SUCCESS");
    }

    function get_all_tm_poscompt() {
        $aRes = array();
        $sQuery = "SELECT c.PLANS,m.SHORT, m.STEXT, m.OTYPE, REQV FROM tm_pos_compt c JOIN tm_master_compt m ON m.OBJID = c.COMPT 
        where CURDATE() BETWEEN c.BEGDA AND c.ENDDA AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA  ORDER BY PLANS,OTYPE";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
        }
        $oRes->free_result();
        return $aRes;
    }

    function get_all_tm_empcompt() {
        $aRes = array();
        $sQuery = "SELECT PERNR,m.SHORT, COVAL  FROM tm_emp_compt  c JOIN tm_master_compt m ON m.OBJID = c.COMPT 
            where  CURDATE() BETWEEN c.BEGDA AND c.ENDDA
        AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA  ORDER BY PERNR";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
        }
        $oRes->free_result();
        return $aRes;
    }

    function get_tm_poscompt($sPos) {
        $aRes = array();
        $sQuery = "SELECT c.PLANS,m.SHORT, m.STEXT, m.OTYPE, REQV FROM tm_pos_compt c JOIN tm_master_compt m ON m.OBJID = c.COMPT 
        where PLANS='$sPos' AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ORDER BY OTYPE";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
        }
        $oRes->free_result();
        return $aRes;
    }

    function get_tm_empcompt($sPernr) {
        $aRes = array();
        $sQuery = "SELECT PERNR,m.SHORT, COVAL  FROM tm_emp_compt  c JOIN tm_master_compt m ON m.OBJID = c.COMPT 
        where PERNR='$sPernr'  AND CURDATE() BETWEEN c.BEGDA AND c.ENDDA
        AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA ";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
        }
        $oRes->free_result();
        return $aRes;
    }

    function get_tm_criteria_readiness_detail_performance() {
        $sQuery = "SELECT MIN,PERCT FROM tm_criteria_readiness_detail WHERE id_criteria=2 AND SUBTY=1";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $aRet = array();
        $oRes->free_result();
        for ($i = 0; $i < count($aRes); $i++) {
            $aRet[$aRes[$i]['MIN']] = $aRes[$i]['PERCT'];
        }
        unset($aRes);
        return $aRet;
    }

    function get_processs_2345($pernr) {
        $sQuery = "select PERNR,ec.id_criteria,ec.PERCT EC_PERCT,cr.PERCT CR_PERCT from tm_emp_criteria ec
JOIN tm_criteria_readiness cr ON ec.id_criteria=cr.id_criteria
WHERE ec.PERNR='$pernr'
ORDER BY ec.DATE DESC";
        $oRes = $this->db->query($sQuery);
        $sVal = 0;
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            for ($i = 0; $i < count($aRes); $i++) {
                $sVal+=$aRes[$i]['EC_PERCT'] * $aRes[$i]['CR_PERCT'] / 100;
            }
        }
        $oRes->free_result();
        return $sVal;
    }

    function get_percent_1() {
        $sQuery = "SELECT PERCT FROM tm_criteria_readiness WHERE SUBTY='1';";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        return $aRow['PERCT'];
    }

    function save_readiness($PERNR, $PLANS, $SFINAL,$SUBTY) {
        $sQuery = "REPLACE INTO tm_emp_readiness(`PERNR`,`PLANS`,`DATE`,`PERCT`,`SUBTY`)
            VALUES ('" . $PERNR . "','$PLANS',NOW(),'$SFINAL','$SUBTY')";
        $this->db->query($sQuery);
    }

    function gen_readiness() {
        $sQuery = "SELECT * FROM tm_emp_plans WHERE PERCENTAGE<>0 AND RUNDATE='".date('Ymd')."' ORDER BY PERNR DESC,PLANS DESC,RUNDATE DESC";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() > 0) {
            $sPerct = $this->get_percent_1();
            $aRes = $oRes->result_array();
            $oRes->free_result();
            $pernr = "";
            $sOther = 0;
            $aCheck = array();
            for ($i = 0; $i < count($aRes); $i++) {
                if ($pernr != $aRes[$i]['PERNR']) {
                    $pernr = $aRes[$i]['PERNR'];
                    $sOther = $this->get_processs_2345($pernr);
                    $aCheck = array();
                }
                $key = $aRes[$i]['PLANS'] . "." . $aRes[$i]['PERNR'];
                if (empty($aCheck[$key])) {
                    $aCheck[$key] = 1;
                    $sFinal = ($aRes[$i]['PERCENTAGE'] * $sPerct / 100) + $sOther;
                    $this->save_readiness($aRes[$i]['PERNR'], $aRes[$i]['PLANS'], $sFinal,$aRes[$i]['SUBTY']);
                }
            }
        }
    }

}

?>
