<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of splan
 *
 * @author Garuda
 */
class splan extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('splan_m');
        $this->load->model('srank_m');
        $this->load->model('tprofile_m');
    }

    function manage_var() {
        $data = $this->splan_m->variant_ov();
        $this->load->view('splan/main', $data);
    }

    function variant_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->splan_m->variant_fr_update($iSeq);
            $this->load->view('splan/main', $data);
        } else {
            $data = $this->splan_m->variant_fr_new();
            $this->load->view('splan/main', $data);
        }
    }

    function variant_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'name', 'trim|required');
            if ($this->form_validation->run()) {
                $a['name'] = $this->input->post('name');
                $a['created_by']= $this->session->userdata('username');
                $this->db->insert('tm_ao_varm', $a);
                redirect('splan/manage_var', 'refresh');
            } else
                redirect('splan/manage_var', 'refresh');
        } else {
            redirect('splan/manage_var', 'refresh');
        }
    }

    function variant_del($iSeq) {
        $this->db->where('idm', $iSeq);
        $this->db->delete('tm_ao_vard');
        $this->global_m->insert_log_delete('tm_ao_vard',array('idm'=> $iSeq));
        $this->db->where('id', $iSeq);
        $this->db->delete('tm_ao_varm');
        $this->global_m->insert_log_delete('tm_ao_varm',array('id'=> $iSeq));
        redirect('splan/manage_var', 'refresh');
    }

    function variant_del_maintain($iSeq, $idm) {
        $this->db->where('idm', $idm);
        $this->db->where('idd', $iSeq);
        $this->db->delete('tm_ao_vard');
        $this->global_m->insert_log_delete('tm_ao_vard',array('idd'=> $iSeq,'idm'=>$idm));
        redirect('splan/variant_fr/' . $idm, 'refresh');
    }

    function variantd_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('idm', 'idm', 'trim|required|numeric');
            $this->form_validation->set_rules('nopeg', 'nopeg', 'trim|required');
            if ($this->form_validation->run()) {
                $a['idm'] = $id_user = $this->input->post('idm');
                $a['OBJID'] = $this->input->post('nopeg');
                $a['OTYPE'] = 'PERNR';
                $a['created_by']= $this->session->userdata('username');
                $this->db->insert('tm_ao_vard', $a);
                redirect('splan/variant_fr/' . $a['idm'], 'refresh');
            } else
                redirect('splan/manage_var', 'refresh');
        } else {
            redirect('splan/manage_var', 'refresh');
        }
    }

    function opsi_1() {
        $data = $this->splan_m->opsi_1();
        $data['externalCSS'] = '<link href="' . base_url() . 'css/table-responsive.css" rel="stylesheet" />';
        $data['externalCSS'] .='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['view'] = 'splan/opsi_1';

        $this->load->view('splan/main', $data);
    }

    function view_1() {
        $data['base_url'] = $this->config->item('base_url');
        $data['position_name'] = $this->input->post('position');
        $data['concern'] = $this->input->post('concern');
        $sNopeg1 = $this->input->post('emp1');
        if (!empty($sNopeg1)) {
            $data['aNopeg'][] = $sNopeg1;
            $data['aHist'][] = $this->input->post('hist1');
        }
        $sNopeg2 = $this->input->post('emp2');
        if (!empty($sNopeg2)) {
            $data['aNopeg'][] = $sNopeg2;
            $data['aHist'][] = $this->input->post('hist2');
        }
        $sNopeg3 = $this->input->post('emp3');
        if (!empty($sNopeg3)) {
            $data['aNopeg'][] = $sNopeg3;
            $data['aHist'][] = $this->input->post('hist3');
        }
        $sNopeg4 = $this->input->post('emp4');
        if (!empty($sNopeg4)) {
            $data['aNopeg'][] = $sNopeg4;
            $data['aHist'][] = $this->input->post('hist4');
        }
        $sNopeg5 = $this->input->post('emp5');
        if (!empty($sNopeg3)) {
            $data['aNopeg'][] = $sNopeg5;
            $data['aHist'][] = $this->input->post('hist5');
        }
        $sNopeg6 = $this->input->post('emp6');
        if (!empty($sNopeg6)) {
            $data['aNopeg'][] = $sNopeg6;
            $data['aHist'][] = $this->input->post('hist6');
        }
        $sNopeg7 = $this->input->post('emp7');
        if (!empty($sNopeg7)) {
            $data['aNopeg'][] = $sNopeg7;
            $data['aHist'][] = $this->input->post('hist7');
        }
        $sNopeg8 = $this->input->post('emp8');
        if (!empty($sNopeg8)) {
            $data['aNopeg'][] = $sNopeg8;
            $data['aHist'][] = $this->input->post('hist8');
        }
        $sNopeg9 = $this->input->post('emp9');
        if (!empty($sNopeg9)) {
            $data['aNopeg'][] = $sNopeg9;
            $data['aHist'][] = $this->input->post('hist9');
        }
        $sNopeg10 = $this->input->post('emp10');
        if (!empty($sNopeg10)) {
            $data['aNopeg'][] = $sNopeg10;
            $data['aHist'][] = $this->input->post('hist10');
        }
        $aPerfConfig = $this->srank_m->get_perf_config();

        for ($i = 0; $i < count($data['aNopeg']); $i++) {
            if (!empty($data['aNopeg'][$i])) {
                $aEmp['PERNR'] = $data['aNopeg'][$i];
                $aDet = $this->srank_m->get_detail_emp($aEmp['PERNR']);
                $aEducs = $this->tprofile_m->getEduc($aEmp['PERNR']);
                $aCriteria = $this->srank_m->get_emp_criteria($aEmp['PERNR']);

                $aEmps2[$i]['nik'] = $aEmp['PERNR'];
                $aEmps2[$i]['nama'] = $aDet['CNAME'];
                $aEmps2[$i]['currpos'] = $aDet['POS'];

                $aEmps2[$i]['cperf'] = $aCriteria[$aEmp['PERNR']][2];
                $aEmps2[$i]['cage'] = $aCriteria[$aEmp['PERNR']][3];
                $aEmps2[$i]['ceduc'] = $aCriteria[$aEmp['PERNR']][4];
                $aEmps2[$i]['cmedical'] = $aCriteria[$aEmp['PERNR']][5];
                $aEmps2[$i]['grade'] = $aDet['GRADE'];
                $aEmps2[$i]['educ'] = $this->getLastEduc($aEducs);
                $aEmps2[$i]['age'] = $aDet['AGE'];
                $aEmps2[$i]['birthdate'] = $aDet['GBDAT'];
                $aEmps2[$i]['medical'] = $this->srank_m->get_medical($aEmp['PERNR']);

                $aPerf = $this->common->getTalentPerf($aEmp['PERNR'], $aPerfConfig);
                $sTalentMap = $this->common->getTalentMap($aEmp['PERNR'], $aDet['PLANS'], $aPerf);
                $aEmps2[$i]['map'] = $sTalentMap;
                $aDesc = $this->srank_m->get_talent_desc($sTalentMap);
                $aEmps2[$i]['mapdesc'] = ($aDesc ? $aDesc['STEXT'] : "");

                $aEmps2[$i]['comptavg'] = $this->srank_m->get_avg_compt($aEmp["PERNR"]);

                $aMdg = $this->srank_m->get_mdg($aEmp["PERNR"]);
                if ($aMdg) {
                    $aEmps2[$i]['mdg'] = "Grade " . $aMdg["TRFGR"] . $aMdg["TRFST"] . "<br/> (" . $aMdg["MDGY"] . "year " . $aMdg["MDGM"] . "month)";
                } else {
                    $aEmps2[$i]['mdg'] = "";
                }
                $aEmps2[$i]['perusahaan'] = $this->splan_m->get_perusahaan($aEmp['PERNR']);

                $s3Perf = $this->get_3_perf($aPerf);
                $aEmps2[$i]['perf'] = $s3Perf;
            }
            //$this->srank_m->get_master_readiness('');
        }
        $data['employee'] = $aEmps2;
        $data['view'] = 'splan/view_1';
        $this->load->model('ecs_m');
        $sHash = $this->ecs_m->saveDataDL($data);
        $data['hash'] = $sHash;
        $this->load->view('splan/main', $data);
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
                            $aMax = 8; break;
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

    function get_3_perf($aPerf) {
        // baru ambil 3 record terakhir
        $sRtn = "";

        if ($aPerf) {
            for ($i = 0; $i < count($aPerf); $i++) {
                if ($i >= 3)
                    break;
                $sRtn .= ($sRtn == "" ? "" : ", ") . $aPerf[$i]["IDX"] . " (" . substr($aPerf[$i]["ENDDA"], 0, 4) . ")";
            }
        }

        return $sRtn;
    }

}

?>
