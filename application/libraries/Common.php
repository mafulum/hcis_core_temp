<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//$_HCPATH="../../";
//require_once($_HCPATH."module/session/session.php");

class Common {

    protected $_CI;

    /**
     * Constructor
     */
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('global_m');
        $controller = $this->CI->router->fetch_class();
        $controller_allow_not_login = array("login","logout","api_slip","api_sk","api_spt","monitoring_of_task");
//        $controller != "login" && $controller != "logout"
        if (!in_array($controller, $controller_allow_not_login)) {
            $pernr = $this->CI->session->userdata('pernr');
            if (empty($pernr)) {
                redirect('login', 'refresh');
            }

            $aModule = $this->get_module_access();
            if ($aModule) {
                // Cek Otorisasi
                $iMod = $this->convert_module($controller);
                if ($iMod <> 0) {
                    if ($aModule[$iMod] == 0) {
                        // Not Authorize
                        redirect('notauth', 'refresh');
                    }
                }
            } else {
                redirect('notauth', 'refresh');
            }
        }
    }

    function convert_module($controller) {
        $iMod = 0;

        switch ($controller) {
            case 'dashboard' : $iMod = 1;
                break;
            case 'employee' : $iMod = 2;
                break;
            case 'memp_payroll' : $iMod = 2;
                break;
            case 'memp_tms' : $iMod = 2;
                break;
            case 'orgchart' : $iMod = 3;
                break;
            case 'tprofile' : $iMod = 4;
                break;
            case 'ecs' : $iMod = 5;
                break;
            case 'srank' : $iMod = 6;
                break;
            case 'pmatch' : $iMod = 7;
                break;
            case 'admin' : $iMod = 8;
                break;
            default : $iMod = 0;
        }

        return $iMod;
    }

    function get_module_access() {
        $aModule = $this->CI->session->userdata('module');
//        var_dump($aModule);exit;
        return $aModule;
    }

    function check_permission($auth_code) {
        $pernr = $this->CI->session->userdata('pernr');
        $aPerm = $this->CI->session->userdata('permission');
//        if(!in_array($pernr, array("9500067","9500034"))){
//            return true;
//        }
        $aPermission = $this->CI->global_m->get_all_permission();
        if (empty($aPerm)) {
            return false;
        }
        foreach ($aPerm as $sID) {
            $sVal = $aPermission[$sID];
            if (strpos($sVal, $auth_code) !== false) {
                return true;
            }
        }
        return false;
    }

    function save_session($row) {
        if (!empty($row['pernr'])) {
            //$this->CI->global_m->del_upernr_maintain($row['pernr']);
            //$this->CI->global_m->del_uorg_maintain($row['pernr']);
        }
        $sId = $this->CI->session->userdata('id');
        if (!empty($sId)) {
            $this->CI->session->unset_userdata('id');
            $this->CI->session->unset_userdata('username');
            $this->CI->session->unset_userdata('pernr');
            $this->CI->session->unset_userdata('password');
            $this->CI->session->unset_userdata('user_type');
            $this->CI->session->sess_destroy();
        }
        if (empty($row) || empty($row['id'])) {
            return FALSE;
        }

        $aModule = $this->CI->global_m->get_user_module($row['id']);
        $aPermission = $this->CI->global_m->get_user_permission($row['id']);
        $this->CI->session->set_userdata('pernr', $row['pernr']);
        $this->CI->session->set_userdata('id', $row['id']);
        $this->CI->session->set_userdata('username', $row['username']);
        $this->CI->session->set_userdata('user_type', $row['user_type']);
        $this->CI->session->set_userdata('module', $aModule);
        $this->CI->session->set_userdata('permission', $aPermission);
//      $this->CI->global_m->gen_aOrg_maintain($row['pernr']);
//      $this->CI->global_m->gen_aPernr_maintain($row['pernr']);

        return TRUE;
    }

    function clear_session() {
        $pernr = $this->CI->session->userdata('pernr');
        //    $this->CI->global_m->del_upernr_maintain($pernr);
        //    $this->CI->global_m->del_uorg_maintain($pernr);
        $this->CI->session->unset_userdata('id');
        $this->CI->session->unset_userdata('username');
        $this->CI->session->unset_userdata('pernr');
        $this->CI->session->unset_userdata('fullname');
//		$this->CI->session->unset_userdata('password');
        $this->CI->session->unset_userdata('user_type');
        $this->CI->session->sess_destroy();
        return true;
    }

    // Add by Andi S 20140503
    function get_abbrev($iTipe, $sTy = "") {
        $data = $this->CI->global_m->get_abbrev($iTipe, $sTy);

        return $data;
    }

    // End - Andi S 20140503
    // Add by Andi S 20140612
    function getTalentPerf($sNopeg, $aPerfConfig) {
        $aPerf = null;

        $this->CI->load->model('srank_m');
        $aEmpPerf = $this->CI->srank_m->get_emp_perf($sNopeg);
        $aEmp = $this->CI->srank_m->get_mapping_pernr2($sNopeg);


        if (array_key_exists($aEmp['ORGEH'], $aPerfConfig)) {
            $aPerfConfigOrg = $aPerfConfig[$aEmp['ORGEH']];

            if ($aPerfConfigOrg) {
                if ($aEmpPerf) {
                    for ($i = 0; $i < count($aEmpPerf); $i++) {
                        $sPerf = "";
                        if ($aEmpPerf[$i]['NILAI'] >= $aPerfConfigOrg['LMIN'] && $aEmpPerf[$i]['NILAI'] <= $aPerfConfigOrg['LMAX']) {
                            $sPerf = "L";
                        } elseif ($aEmpPerf[$i]['NILAI'] >= $aPerfConfigOrg['MMIN'] && $aEmpPerf[$i]['NILAI'] <= $aPerfConfigOrg['MMAX']) {
                            $sPerf = "M";
                        } elseif ($aEmpPerf[$i]['NILAI'] >= $aPerfConfigOrg['HMIN'] && $aEmpPerf[$i]['NILAI'] <= $aPerfConfigOrg['HMAX']) {
                            $sPerf = "H";
                        }
                        $aPerf[$i]['IDX'] = $sPerf;
                        $aPerf[$i]['ENDDA'] = $aEmpPerf[$i]['ENDDA'];
                    }
                }
            }
        }


        return $aPerf;
    }

    function getTalentPot($sNopeg, $sPlans) {
        $this->CI->load->model('srank_m');
        $sPot = "-";
        $aPot = $this->CI->srank_m->get_emp_plans($sPlans, 1, $sNopeg);
        if ($aPot) {
            $sPot = $this->CI->srank_m->get_emp_pot($aPot[$sNopeg]);
            $sPot = $sPot['LEVEL'];
        }

        return $sPot;
    }

    function getTalentMap($sNopeg, $sPlans, $aPerf) {
        $sRtn = "";
        $sPerf = "-";

        if ($aPerf) {
            $sPerf = $aPerf[0]['IDX'];
        }
        $sPot = $this->getTalentPot($sNopeg, $sPlans);


        $sRtn = $sPot . $sPerf;
        return $sRtn;
    }

    // End - Andi S 20140612
    // Add by Andi S 20140707
    function cekPernrAuth($sNopeg) {
        $iUid = $this->CI->session->userdata('id');

        $bRet = $this->CI->global_m->cek_otorisasi($iUid, $sNopeg);
        if ($bRet == FALSE) {
            redirect('notauth', 'refresh');
        }

        return $bRet;
    }

    function cekMethod($sNopeg) {
        $controller = $this->CI->router->fetch_class();
        $method = $this->CI->router->fetch_method();

        $iMod = $this->convert_module($controller);
        if ($iMod == 2) {
            if ($method <> "get_plans")
                $this->cekPernrAuth($sNopeg);
        }

        if ($iMod == 4) {
            if ($method <> "search" && $method <> "table" && $method <> "getEmpJson" && $method <> "getUnitJson")
                $this->cekPernrAuth($sNopeg);
        }

        if ($iMod == 5) {
            if ($method <> "search" && $method <> "getEmpJson" && $method <> "getUnitJson")
                $this->cekPernrAuth($sNopeg);
        }

        return TRUE;
    }

    function get_a_org_auth() {
        $iUid = $this->CI->session->userdata('id');

        $aOrg = $this->CI->global_m->get_a_org_auth($iUid);
//		var_dump($aOrg);exit;
        return $aOrg;
    }

    function get_a_pernr_auth() {
        $iUid = $this->CI->session->userdata('id');

        $aPernr = $this->CI->global_m->get_a_pernr_auth($iUid);

        return $aPernr;
    }

    // End - Andi S 20140707
    // Add - Andi S 20140811
    function cek_pihc_access() {
        $iUid = $this->CI->session->userdata('id');

        $iRtn = $this->CI->global_m->cek_pihc_access($iUid);

        return $iRtn;
    }

    // End - Andi S 20140811

    function removeEmptyObjectFromArray($a) {
        foreach ($a as $key => $val) {
            if (empty($val)) {
                unset($a[$key]);
            }
        }
        return $a;
    }

    function getKVArr($rows, $key) {
        $aRet = [];
        foreach ($rows as $k => $v) {
            $aRet[$v[$key]] = $v;
        }
        return $aRet;
    }

    function distinctArray($arr, $key) {
        $aRet = [];
        foreach ($arr as $v) {
            if (!in_array($v[$key], $aRet)) {
                $aRet[] = $v[$key];
            }
        }
        return $aRet;
    }

    function processCompt($aPosCompts, $aEmpCompts) {
        $aEmp = array();
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
        $iTotalBobot = 0;
        foreach ($aPosCompts as $aPosCompt){
            $iTotalBobot += $aPosCompt['bobot'];
        }
        foreach ($aPosCompts as $aPosCompt) {
            if(empty($aPosCompt['OTYPE'])){
                continue;
            }
            $aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Pos'] = $aPosCompt['REQV'];
            $aEmp[$aPosCompt['SHORT']] = (array_key_exists($aPosCompt['SHORT'], $aEmp) ? $aEmp[$aPosCompt['SHORT']] : 0);
            $aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Emp'] = $aEmp[$aPosCompt['SHORT']];

            $iDiff = $aEmp[$aPosCompt['SHORT']] - $aPosCompt['REQV'];
            if ($iDiff > 0)
                $iDiff = 0;
            $iPct = $aEmp[$aPosCompt['SHORT']] / $aPosCompt['REQV'] * 100;
            if ($iPct > 100)
                $iPct = 100;
            $iPct = $iPct * $aPosCompt['bobot'] / $iTotalBobot;
            
            $aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['bobot'] = $aPosCompt['bobot'];
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
        $aRtn[2]['TotM'] = $iTotM ;
        $aRtn[2]['TotG'] = $iTotG;

        return $aRtn;
    }


    function masterCompt($aCompt){
            //$aRtn['KC']['C1'] = 'Core Competentcy';
            //$aRtn['C1']['ACH'] = 'Achievement Orientation';
            $aRtn = null;

            foreach($aCompt as $aCompt){
                    $aRtn[$aCompt['OTYPE']][$aCompt['SHORT']] = $aCompt['STEXT'];
            }
            return $aRtn;
    }

}
