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
class tsearch_m extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('orgchart_m');
        $this->load->model('employee_m');
    }

    function home() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'talent_search/home';
        $data["userid"] = $this->session->userdata('username');
        return $data;
    }

    function view() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'talent_search/view';
        $data["userid"] = $this->session->userdata('username');
        $data['externalJS'] = '<script type="text/javascript" language="javascript" src="' . base_url() . 'assets/advanced-datatable/media/js/jquery.dataTables.min.js"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['externalCSS'] = '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" /> ';

        $data['scriptJS'] = '<script type="text/javascript">
		$(document).ready(function() {
                    $("#tblSrank").dataTable();
		});
		</script>';
        return $data;
    }
    
    function view_compare(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'talent_search/compare';
        $data["userid"] = $this->session->userdata('username');
        return $data;
    }

    function get_unit($q, $shortComp) {
        $aRet = [];
        $now = date("Y-m-d");
        $abbrv = $this->global_m->get_master_abbrev("5", " AND SHORT='$shortComp'");
        if (empty($abbrv)) {
            return $aRet;
        }
        $aOrgObjid = $this->orgchart_m->get_recursive_org("'" . $abbrv[0]['REF_OBJID'] . "'", $now);
        if (empty($aOrgObjid)) {
            return $aRet;
        }
        $aRetOrg = $this->orgchart_m->getMasterOrg($q, $aOrgObjid, $now);
        if (!empty($aRetOrg)) {
            foreach ($aRetOrg as $v) {
                $aRet[] = ["id" => $v['OBJID'], "text" => $v['STEXT'] . " (" . $v['SHORT'] . ")"];
            }
        }
        return $aRet;
    }

    function get_position($q, $shortComp = "", $org_unit = "", $job = "") {
        $aRet = [];
        $now = date("Y-m-d");
        $aOrg = [];
        if (!empty($shortComp) && empty($org_unit)) {
            $abbrv = $this->global_m->get_master_abbrev("5", " AND SHORT='$shortComp'");
            if (empty($abbrv)) {
                return $aRet;
            }
            $aOrg = $this->orgchart_m->get_recursive_org("'" . $abbrv[0]['REF_OBJID'] . "'", $now);
        } else if (!empty($shortComp)) {
            $aOrg = $this->orgchart_m->get_recursive_org("'" . $org_unit . "'", $now);
            $aOrg[] = $org_unit;
        }
        if (empty($aOrg)) {
            return $aRet;
        }
        $aJob = [];
        if (!empty($job)) {
            $aJob[] = $job;
        }
        $aPos = $this->orgchart_m->getMasterPositionByOrgJob($q, $aOrg, $aJob, $now);
        if (!empty($aPos)) {
            foreach ($aPos as $v) {
                $aRet[] = ["id" => $v['OBJID'], "text" => $v['STEXT'] . " (" . $v['OBJID'] . ")"];
            }
        }
        return $aRet;
    }

    function get_emp($q) {
        $aRet = [];
        $now = date("Y-m-d");
        $aRes = $this->employee_m->get_emp_filter_select($q, $now);
        foreach ($aRes as $v) {
            $aRet[] = ['id' => $v['PERNR'], 'text' => $v['PERNR'] . " / " . $v['CNAME'] . " (" . $v['WERKS'] . " / " . $v['STEXT'] . ")"];
        }
        return $aRet;
    }

    function array_merge_compt($aCompt) {
        $aRet = [];
        foreach ($aCompt as $compt) {
            if (!in_array($compt['COMPT'], $aRet) || (in_array($compt['COMPT'], $aRet) && $aRet[$compt['COMPT']]['REQV'] < $compt['REQV'])) {
                $aRet[$compt['COMPT']] = $compt;
            }
        }
        return $aRet;
    }

    function array_2d_PERNR_COMPT($aEmpCompt) {
        $aRet = [];
        foreach ($aEmpCompt as $emp) {
            if (!in_array($emp['PERNR'], $aRet) || !in_array($emp['COMPT'], $aRet[$emp['PERNR']]) || (in_array($emp['PERNR'], $aRet) && in_array($emp['COMPT'], $aRet[$emp['PERNR']]) && $aRet[$emp['PERNR']][$emp['COMPT']]['COVAL'] < $emp['COVAL'])) {
                $aRet[$emp['PERNR']][$emp['COMPT']] = $emp;
            }
        }
        return $aRet;
    }

    function filterTalent($job, $pos) {
        $aJobCompt = $this->ecs_m->getJobCompt($job);
        $aPosCompt = $this->ecs_m->getPosCompt($pos);
        $aReqCompt = array_merge($aJobCompt, $aPosCompt);
        if (empty($aReqCompt)) {
            return ['message' => 'Talent Requirement undefined', 'emp' => [], 'compt' => []];
        }
        return ['message' => 'Talent Requirement undefined', 'emp' => [], 'compt' => $aReqCompt];
    }
    

    function searchTalent($job, $pos, $aEmpFilter) {
        $aFilterCompt = $this->filterTalent($job, $pos);
//        $aJobCompt = $this->ecs_m->getJobCompt($job);
//        $aPosCompt = $this->ecs_m->getPosCompt($pos);
//        $aReqCompt = array_merge($aJobCompt, $aPosCompt);
        if (empty($aFilterCompt['compt'])) {
            return ['message' => 'Talent Requirement undefined', 'emp' => [], 'compt' => []];
        }
        $aReqCompt = $aFilterCompt['compt'];
        $aReqComptKV = $this->array_merge_compt($aReqCompt);
        $aCompt = $this->common->distinctArray($aReqCompt, "COMPT");
        $n_compt = count($aCompt);
        $aPernr = $this->employee_m->getPernrByFilter($aEmpFilter);
        $aEmpCompt = $this->employee_m->getEmpComptByPernrsCompt($aPernr, $aCompt);
        $aEmpComptKV = $this->array_2d_PERNR_COMPT($aEmpCompt);
        $aPernrComptDistinct = $this->common->distinctArray($aEmpCompt, "PERNR");
        $emp_map_compt = [];
        $iSumBobot = 0;
        foreach ($aReqComptKV as $kCompt => $vCompt) {
            $iSumBobot += $vCompt['bobot'];
        }
        foreach ($aPernrComptDistinct as $pernr) {
            $sum = 0;
            foreach ($aReqComptKV as $kCompt => $vCompt) {
                if (!empty($aEmpComptKV[$pernr]) && !empty($aEmpComptKV[$pernr][$kCompt])) {
                    $aEmpComptKV[$pernr][$kCompt]['VAL'] = ($vCompt['REQV'] < $aEmpComptKV[$pernr][$kCompt]['COVAL']) ? 1 : $aEmpComptKV[$pernr][$kCompt]['COVAL'] / $vCompt['REQV'];
                    $aEmpComptKV[$pernr][$kCompt]['VAL'] = $aEmpComptKV[$pernr][$kCompt]['VAL'] * 100 * $vCompt['bobot'] / $iSumBobot;
                    $sum += $aEmpComptKV[$pernr][$kCompt]['VAL'];
                }
            }
            $emp_map_compt[] = ['PERNR' => $pernr, 'AVG_COMPT' => ($sum)];
        }
        usort($emp_map_compt, function ($item1, $item2) {
            return $item2['AVG_COMPT'] > $item1['AVG_COMPT'];
        });
        return ['req_compt'=>$aReqCompt, 'pernrs' => $aPernrComptDistinct, 'compt' => ['req'=>$aCompt,'emp'=>$aEmpCompt], 'emp_sort' => $emp_map_compt, 'emp_compt' => $aEmpComptKV, 'n_emp' => count($aPernrComptDistinct)];
    }

}

?>