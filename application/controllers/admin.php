<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin
 *
 * @author Garuda
 */
class admin extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    //    if ($this->global_m->get_user_type() != $this->global_m->ADMIN_USER_TYPE) {
    //        echo "Not Authorized";
    //        exit;
    //    }
        $this->load->model('admin_m');
    }

    function config() {
        $data = $this->admin_m->config();
        $this->load->view('main', $data);
    }

    function config_fr($iSeq) {
        if (!empty($iSeq)) {
            $data = $this->admin_m->config_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            redirect('admin/config', 'refresh');
        }
    }

    function config_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('idc', 'idc', 'trim|required|numeric');
            $this->form_validation->set_rules('seq', 'seq', 'trim|required');
            if ($this->form_validation->run()) {
                $idc = $this->input->post('idc');
                $seq = $this->input->post('seq');
                $this->admin_m->config_upd($idc, $seq);
                redirect('admin/config/', 'refresh');
            } else
                redirect('admin/config', 'refresh');
        } else {
            redirect('admin/config', 'refresh');
        }
    }

    function abbrev() {
        $data = $this->admin_m->abbrev();
        $this->load->view('main', $data);
    }

    function abbrev_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->abbrev_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->abbrev_fr_new();
            $this->load->view('main', $data);
        }
    }

    function abbrev_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_abbrv', 'id_abbrv', 'trim|required|numeric');
            $this->form_validation->set_rules('SUBTY', 'SUBTY', 'trim|required');
            $this->form_validation->set_rules('SHORT', 'SHORT', 'trim|required');
            $this->form_validation->set_rules('STEXT', 'STEXT', 'trim|required');
            if ($this->form_validation->run()) {
                $id_abbrv = $this->input->post('id_abbrv');
                $a['SUBTY'] = $this->input->post('SUBTY');
                $a['SHORT'] = $this->input->post('SHORT');
                $a['STEXT'] = $this->input->post('STEXT');
                $this->admin_m->abbrev_upd($id_abbrv, $a);
                redirect('admin/abbrev/', 'refresh');
            } else
                redirect('admin/abbrev', 'refresh');
        } else {
            redirect('admin/abbrev', 'refresh');
        }
    }

    function abbrev_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('SUBTY', 'SUBTY', 'trim|required');
            $this->form_validation->set_rules('SHORT', 'SHORT', 'trim|required');
            $this->form_validation->set_rules('STEXT', 'STEXT', 'trim|required');
            if ($this->form_validation->run()) {
                $a['SUBTY'] = $this->input->post('SUBTY');
                $a['SHORT'] = $this->input->post('SHORT');
                $a['STEXT'] = $this->input->post('STEXT');
                $this->admin_m->abbrev_insert($a);
                redirect('admin/abbrev/', 'refresh');
            } else
                redirect('admin/abbrev', 'refresh');
        } else {
            redirect('admin/abbrev', 'refresh');
        }
    }

    function abbrev_del($iSeq) {
        $this->admin_m->abbrev_delete($iSeq);
        redirect('admin/abbrev/', 'refresh');
    }
    
    function job_compt($objid=null){
        if(empty($objid)){
            $data = $this->admin_m->m_job_compt();
        }else{
            $data = $this->admin_m->m_job_compt_requirement($objid);
        }
        $this->load->view('main', $data);
    }
    
    function m_job_compt_del($id,$stell,$compt){
        $this->load->model('ecs_m');
        $this->ecs_m->deleteJobCompt($id,$stell,$compt);
        redirect('admin/job_compt/'.$stell, 'refresh');
    }
    
    function m_job_compt_req(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('STELL', 'STELL', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            $this->form_validation->set_rules('REQV', 'REQV', 'trim|required');
            $this->form_validation->set_rules('COMPT', 'COMPT', 'trim|required');
            $this->form_validation->set_rules('bobot', 'bobot', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('ecs_m');
                $a['bobot'] = $this->input->post('bobot');
                $a['STELL'] = $this->input->post('STELL');
                $a['REQV'] = $this->input->post('REQV');
                $a['COMPT'] = $this->input->post('COMPT');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $this->ecs_m->insert_job_compt_req($a);
                redirect('admin/job_compt/'.$a['STELL'], 'refresh');
            } else{
                redirect('admin/job_compt/', 'refresh');
            }
        } else {
            redirect('admin/job_compt/', 'refresh');
        }
    }
    
    function job_form(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('OBJID', 'OBJID', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            $this->form_validation->set_rules('SHORT', 'SHORT', 'trim|required');
            $this->form_validation->set_rules('STEXT', 'STEXT', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('orgchart_m');
                $a['OBJID'] = $this->input->post('OBJID');
                $a['SHORT'] = $this->input->post('SHORT');
                $a['STEXT'] = $this->input->post('STEXT');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $a['OTYPE'] = 'C';
                $this->orgchart_m->insert_master_org($a);
                redirect('admin/job_compt/', 'refresh');
            } else{
                redirect('admin/job_compt/', 'refresh');
            }
        } else {
            redirect('admin/job_compt/', 'refresh');
        }
    }
    
    function delete_job($id,$objid){
        $this->load->model('orgchart_m');
        $this->orgchart_m->delete_org($id,$objid,'C');
        redirect('admin/job_compt/', 'refresh');
    }
    
    function m_competency($objid="",$sPrefix=""){
        if(empty($objid) && empty($sPrefix)){
            $data = $this->admin_m->m_competency();
        }else{
            $data = $this->admin_m->m_competency_detail($objid,$sPrefix);
        }
        $this->load->view('main', $data);
    }
    
    function m_competency_cg_add(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('objid', 'objid', 'trim|required|numeric');
            $this->form_validation->set_rules('sPrefix', 'sPrefix', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('SHORT', 'SHORT', 'trim|required');
            $this->form_validation->set_rules('STEXT', 'STEXT', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('orgchart_m');
                $objid = $id_user = $this->input->post('objid');
                $sPrefix = $this->input->post('sPrefix');
                $a['SHORT'] = $this->input->post('SHORT');
                $a['STEXT'] = $this->input->post('STEXT');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $seq=$this->orgchart_m->get_config_by_prefix("KOMPETENSI",$sPrefix);
                $a['OBJID'] = $seq;
                $a['OTYPE'] = 'KC';
                $this->admin_m->m_competency_add($a);
                $this->orgchart_m->add_config_by_prefix("KOMPETENSI",$sPrefix,$seq);
                redirect('admin/m_competency/' . $objid.'/'.$sPrefix, 'refresh');
            } else
                redirect('admin/m_competency/', 'refresh');
        } else {
            redirect('admin/m_competency/', 'refresh');
        }
    }
    
    function m_competency_cg_del($objid="",$sPrefix="",$id=""){
        if(empty($objid) || empty($sPrefix) || empty($id)){
            redirect('admin/m_competency/', 'refresh');            
        }
        $this->admin_m->m_competency_del($sPrefix,$id);
        redirect('admin/m_competency/' . $objid.'/'.$sPrefix, 'refresh');
    }
    function m_competency_cd_add(){
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('objid', 'objid', 'trim|required|numeric');
            $this->form_validation->set_rules('sPrefix', 'sPrefix', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('SHORT', 'SHORT', 'trim|required');
            $this->form_validation->set_rules('STEXT', 'STEXT', 'trim|required');
            $this->form_validation->set_rules('OTYPE', 'OTYPE', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('orgchart_m');
                $objid = $id_user = $this->input->post('objid');
                $sPrefix = $this->input->post('sPrefix');
                $a['SHORT'] = $this->input->post('SHORT');
                $a['STEXT'] = $this->input->post('STEXT');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $seq=$this->orgchart_m->get_config_by_prefix("KOMPETENSI",$sPrefix);
                $a['OBJID'] = $seq;
                $a['OTYPE'] = $this->input->post('OTYPE');
                $this->admin_m->m_competency_add($a);
                $this->orgchart_m->add_config_by_prefix("KOMPETENSI",$sPrefix,$seq);
                redirect('admin/m_competency/' . $objid.'/'.$sPrefix, 'refresh');
            } else
                redirect('admin/m_competency/', 'refresh');
        } else {
            redirect('admin/m_competency/', 'refresh');
        }
    }
    
    function m_competency_cd_del($objid="",$sPrefix="",$id=""){
        if(empty($objid) || empty($sPrefix) || empty($id)){
            redirect('admin/m_competency/', 'refresh');            
        }
        $this->admin_m->m_competency_del($sPrefix,$id);
        redirect('admin/m_competency/' . $objid.'/'.$sPrefix, 'refresh');
    }
    function user() {
        $data = $this->admin_m->user();
        $this->load->view('main', $data);
    }

    function user_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->user_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->user_fr_new();
            $this->load->view('main', $data);
        }
    }

    function user_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'username', 'trim|required');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('password', 'password', 'trim|required');
            if ($this->form_validation->run()) {
                $a['username'] = $this->input->post('username');
                $a['pernr'] = $this->input->post('pernr');
                $a['pwd_raw'] = $this->input->post('password');
                $a['password'] = md5($this->input->post('password'));
                $a['create_by'] = $this->session->userdata('id');
                $a['create_date'] = date("Ymd H:i:s");
                $a['update_by'] = $this->session->userdata('id');
                $a['update_date'] = date("Ymd H:i:s");
                $a['isActive'] = '1';
                $id = $this->admin_m->user_new($a);
                redirect('admin/user/', 'refresh');
            } else
                redirect('admin/user', 'refresh');
        } else {
            redirect('admin/user', 'refresh');
        }
    }

    function user_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('password', 'password', 'trim|required');
            $this->form_validation->set_rules('user_type', 'user_type', 'trim|required');
            if ($this->form_validation->run()) {
                $id = $this->input->post('id');
                $a['pernr'] = $this->input->post('pernr');
                $a['password'] = md5($this->input->post('password'));
                $a['user_type'] = $this->input->post('user_type');
                $a['update_by'] = $this->session->userdata('id');
                $a['update_date'] = date("Ymd H:i:s");
                $this->admin_m->user_upd($id, $a);
                redirect('admin/user/', 'refresh');
            } else
                redirect('admin/user', 'refresh');
        } else {
            redirect('admin/user', 'refresh');
        }
    }

    function userd_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_user', 'id_user', 'trim|required|numeric');
            $this->form_validation->set_rules('org_unit', 'org_unit', 'trim|required');
            if ($this->form_validation->run()) {
                $a['id_user'] = $id_user = $this->input->post('id_user');
                $a['org_unit'] = $this->input->post('org_unit');
                $a['update_by'] = $this->session->userdata('id');
                $a['update_time'] = date("Y-m-d H:i:s");
                $this->admin_m->userd_add($a);
                redirect('admin/user_fr/' . $id_user, 'refresh');
            } else
                redirect('admin/user/', 'refresh');
        } else {
            redirect('admin/user/', 'refresh');
        }
    }

    function userdm_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_user', 'id_user', 'trim|required|numeric');
            $this->form_validation->set_rules('id_module', 'id_module', 'trim|required');
            if ($this->form_validation->run()) {
                $a['id_user'] = $id_user = $this->input->post('id_user');
                $a['id_module'] = $this->input->post('id_module');
//                $a['update_by'] = $this->session->userdata('id');
//                $a['update_time'] = date("Y-m-d H:i:s");
                $this->admin_m->userdm_add($a);
                redirect('admin/user_fr/' . $id_user, 'refresh');
            } else
                redirect('admin/user/', 'refresh');
        } else {
            redirect('admin/user/', 'refresh');
        }
    }


    function user_del($iSeq) {
        $this->admin_m->user_delete($iSeq);
        redirect('admin/user/', 'refresh');
    }

    function user_del_maintain($iSeq, $id_user) {
        $this->admin_m->user_delete_maintain($iSeq, $id_user);
        redirect('admin/user_fr/' . $id_user, 'refresh');
    }
    function user_del_module($iSeq, $id_user) {
        $this->admin_m->user_delete_module($iSeq, $id_user);
        redirect('admin/user_fr/' . $id_user, 'refresh');
    }

    function perusahaan() {
        $data = $this->admin_m->perusahaan();
        $this->load->view('main', $data);
    }

    function perusahaan_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->perusahaan_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->perusahaan_fr_new();
            $this->load->view('main', $data);
        }
    }

    function perusahaan_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('OBJID', 'OBJID', 'trim|required|numeric');
            $this->form_validation->set_rules('id_level', 'id_level', 'trim|required|numeric');
            $this->form_validation->set_rules('LEVEL', 'LEVEL', 'trim|required');
            $this->form_validation->set_rules('SEQ', 'SEQ', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            if ($this->form_validation->run()) {
                $objid = $this->input->post('OBJID');
                $id_level = $this->input->post('id_level');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $a['LEVEL'] = $this->input->post('LEVEL');
                $a['SEQ'] = $this->input->post('SEQ');
                $this->admin_m->perusahaan_update($id_level, $objid, $a);
                redirect('admin/perusahaan/', 'refresh');
            } else
                redirect('admin/perusahaan/', 'refresh');
        } else {
            redirect('admin/perusahaan/', 'refresh');
        }
    }
    
    function perusahaan_new(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('perusahaan', 'perusahaan', 'trim|required');
            $this->form_validation->set_rules('kode', 'kode', 'trim|required');
            $this->form_validation->set_rules('LEVEL', 'LEVEL', 'trim|required');
            $this->form_validation->set_rules('SEQ', 'SEQ', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            if ($this->form_validation->run()) {
                $perusahaan = $this->input->post('perusahaan');
                $kode= $this->input->post('kode');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $a['LEVEL'] = $this->input->post('LEVEL');
                $a['SEQ'] = $this->input->post('SEQ');
                //create_prefix_perusahaan
                $OBJID=$this->admin_m->create_prefix_perusahaan();
                $OBJID.="00000";
                $a['OBJID']=$OBJID;
                //create org level
                $id_level = $this->admin_m->insert_org_level($a);
                //create ORG di master_org
                $this->admin_m->insert_org_perusahaan($OBJID,$kode,$perusahaan,$a['BEGDA'],$a['ENDDA']);
                //create relation di master_org
                $this->admin_m->insert_relation_perusahaan($OBJID,$a['BEGDA'],$a['ENDDA']);
                $this->admin_m->genereate_tm_config($kode,$OBJID);
                redirect('admin/perusahaan/', 'refresh');
            } else
                redirect('admin/perusahaan/', 'refresh');
        } else {
            redirect('admin/perusahaan/', 'refresh');
        }        
    }

    function matrix_js() {
        $data = $this->admin_m->matrix_js();
        $this->load->view('main', $data);
    }

    function matrixjs_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->matrixjs_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->matrixjs_fr_new();
            $this->load->view('main', $data);
        }
    }

    function matrixjs_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('ORGID', 'ORGID', 'trim|required');
            $this->form_validation->set_rules('STELL', 'STELL', 'trim|required');
            $this->form_validation->set_rules('SCORE', 'SCORE', 'trim|required');
            if ($this->form_validation->run()) {
                $a['ORGID'] = $this->input->post('ORGID');
                $a['STELL'] = $this->input->post('STELL');
                $a['SCORE'] = $this->input->post('SCORE');
                $id = $this->admin_m->matrixjs_new($a);
                redirect('admin/matrix_js/', 'refresh');
            } else
                redirect('admin/matrix_js', 'refresh');
        } else {
            redirect('admin/matrix_js', 'refresh');
        }
    }

    function matrixjs_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_matrix', 'id_matrix', 'trim|required');
            $this->form_validation->set_rules('ORGID', 'ORGID', 'trim|required');
            $this->form_validation->set_rules('STELL', 'STELL', 'trim|required');
            $this->form_validation->set_rules('SCORE', 'SCORE', 'trim|required');
            if ($this->form_validation->run()) {
                $id_matrix = $this->input->post('id_matrix');
                $a['ORGID'] = $this->input->post('ORGID');
                $a['STELL'] = $this->input->post('STELL');
                $a['SCORE'] = $this->input->post('SCORE');
                $id = $this->admin_m->matrixjs_upd($id_matrix, $a);
                redirect('admin/matrix_js/', 'refresh');
            } else
                redirect('admin/matrix_js', 'refresh');
        } else {
            redirect('admin/matrix_js', 'refresh');
        }
    }

    function matrixjs_del($iSeq) {
        $this->admin_m->matrixjs_delete($iSeq);
        redirect('admin/matrix_js', 'refresh');
    }

    function mperformance() {
        $data = $this->admin_m->mperformance();
        $this->load->view('main', $data);
    }

    function mperformance_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->mperformance_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->mperformance_fr_new();
            $this->load->view('main', $data);
        }
    }

    function mperformance_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('ORGID', 'ORGID', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            $this->form_validation->set_rules('LMIN', 'LMIN', 'trim|required');
            $this->form_validation->set_rules('LMAX', 'LMAX', 'trim|required');
            $this->form_validation->set_rules('MMIN', 'MMIN', 'trim|required');
            $this->form_validation->set_rules('MMAX', 'MMAX', 'trim|required');
            $this->form_validation->set_rules('HMIN', 'HMIN', 'trim|required');
            $this->form_validation->set_rules('HMAX', 'HMAX', 'trim|required');
            if ($this->form_validation->run()) {
                $a['ORGID'] = $this->input->post('ORGID');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $a['LMIN'] = $this->input->post('LMIN');
                $a['LMAX'] = $this->input->post('LMAX');
                $a['MMIN'] = $this->input->post('MMIN');
                $a['MMAX'] = $this->input->post('MMAX');
                $a['HMIN'] = $this->input->post('HMIN');
                $a['HMAX'] = $this->input->post('HMAX');
                $id = $this->admin_m->mperformance_new($a);
                redirect('admin/mperformance', 'refresh');
            } else
                redirect('admin/mperformance', 'refresh');
        } else {
            redirect('admin/mperformance', 'refresh');
        }
    }

    function mperformance_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_perf', 'id_perf', 'trim|required');
            $this->form_validation->set_rules('ORGID', 'ORGID', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            $this->form_validation->set_rules('LMIN', 'LMIN', 'trim|required');
            $this->form_validation->set_rules('LMAX', 'LMAX', 'trim|required');
            $this->form_validation->set_rules('MMIN', 'MMIN', 'trim|required');
            $this->form_validation->set_rules('MMAX', 'MMAX', 'trim|required');
            $this->form_validation->set_rules('HMIN', 'HMIN', 'trim|required');
            $this->form_validation->set_rules('HMAX', 'HMAX', 'trim|required');
            if ($this->form_validation->run()) {
                $id_perf = $this->input->post('id_perf');
                $a['ORGID'] = $this->input->post('ORGID');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $a['LMIN'] = $this->input->post('LMIN');
                $a['LMAX'] = $this->input->post('LMAX');
                $a['MMIN'] = $this->input->post('MMIN');
                $a['MMAX'] = $this->input->post('MMAX');
                $a['HMIN'] = $this->input->post('HMIN');
                $a['HMAX'] = $this->input->post('HMAX');
                $id = $this->admin_m->mperformance_update($id_perf, $a);
                redirect('admin/mperformance', 'refresh');
            } else
                redirect('admin/mperformance', 'refresh');
        } else {
            redirect('admin/mperformance', 'refresh');
        }
    }

    function mperformance_del($id_perf, $org_id) {
        $this->admin_m->mperformance_delete($id_perf, $org_id);
        redirect('admin/mperformance', 'refresh');
    }

    function mpotential() {
        $data = $this->admin_m->mpotential();
        $this->load->view('main', $data);
    }

    function mpotential_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->mpotential_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->mpotential_fr_new();
            $this->load->view('main', $data);
        }
    }

    function mpotential_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('LEVEL', 'LEVEL', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            $this->form_validation->set_rules('MIN', 'MIN', 'trim|required');
            $this->form_validation->set_rules('MAX', 'MAX', 'trim|required');
            if ($this->form_validation->run()) {
                $a['LEVEL'] = $this->input->post('LEVEL');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $a['MIN'] = $this->input->post('MIN');
                $a['MAX'] = $this->input->post('MAX');
                $id = $this->admin_m->mpotential_new($a);
                redirect('admin/mpotential', 'refresh');
            } else
                redirect('admin/mpotential', 'refresh');
        } else {
            redirect('admin/mpotential', 'refresh');
        }
    }

    function mpotential_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_pot', 'id_pot', 'trim|required');
            $this->form_validation->set_rules('BEGDA', 'BEGDA', 'trim|required');
            $this->form_validation->set_rules('ENDDA', 'ENDDA', 'trim|required');
            $this->form_validation->set_rules('MIN', 'MIN', 'trim|required');
            $this->form_validation->set_rules('MAX', 'MAX', 'trim|required');
            if ($this->form_validation->run()) {
                $id_pot = $this->input->post('id_pot');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('BEGDA'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('ENDDA'));
                $a['MIN'] = $this->input->post('MIN');
                $a['MAX'] = $this->input->post('MAX');
                $id = $this->admin_m->mpotential_update($id_pot, $a);
                redirect('admin/mpotential', 'refresh');
            } else
                redirect('admin/mpotential', 'refresh');
        } else {
            redirect('admin/mpotential', 'refresh');
        }
    }

    function mpotential_del($id_pot, $level) {
        $this->admin_m->mpotential_delete($id_pot, $level);
        redirect('admin/mpotential', 'refresh');
    }

    function mreadiness() {
        $data = $this->admin_m->mreadiness();
        $this->load->view('main', $data);
    }

    function mreadiness_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->mreadiness_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->mreadiness_fr_new();
            $this->load->view('main', $data);
        }
    }

    function mreadiness_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('DESC', 'DESC', 'trim|required');
            $this->form_validation->set_rules('MIN', 'MIN', 'trim|required');
            $this->form_validation->set_rules('MAX', 'MAX', 'trim|required');
            if ($this->form_validation->run()) {
                $a['DESC'] = $this->input->post('DESC');
                $a['MIN'] = $this->input->post('MIN');
                $a['MAX'] = $this->input->post('MAX');
                $id = $this->admin_m->mreadiness_new($a);
                redirect('admin/mreadiness', 'refresh');
            } else
                redirect('admin/mreadiness', 'refresh');
        } else {
            redirect('admin/mreadiness', 'refresh');
        }
    }

    function mreadiness_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_readiness', 'id_readiness', 'trim|required');
            $this->form_validation->set_rules('DESC', 'DESC', 'trim|required');
            $this->form_validation->set_rules('MIN', 'MIN', 'trim|required');
            $this->form_validation->set_rules('MAX', 'MAX', 'trim|required');
            if ($this->form_validation->run()) {
                $id_readiness = $this->input->post('id_readiness');
                $a['DESC'] = $this->input->post('DESC');
                $a['MIN'] = $this->input->post('MIN');
                $a['MAX'] = $this->input->post('MAX');
                $id = $this->admin_m->mreadiness_update($id_readiness, $a);
                redirect('admin/mreadiness', 'refresh');
            } else
                redirect('admin/mreadiness', 'refresh');
        } else {
            redirect('admin/mreadiness', 'refresh');
        }
    }

    function mreadiness_del($id_readiness) {
        $this->admin_m->mreadiness_delete($id_readiness);
        redirect('admin/mreadiness', 'refresh');
    }

    function talentdesc() {
        $data = $this->admin_m->talentdesc();
        $this->load->view('main', $data);
    }

    function talentdesc_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->talentdesc_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->talentdesc_fr_new();
            $this->load->view('main', $data);
        }
    }

    function talentdesc_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('SHORT', 'SHORT', 'trim|required');
            $this->form_validation->set_rules('STEXT', 'STEXT', 'trim|required');
            $this->form_validation->set_rules('DESC', 'DESC', 'trim|required');
            if ($this->form_validation->run()) {
                $a['DESC'] = $this->input->post('DESC');
                $a['SHORT'] = $this->input->post('SHORT');
                $a['STEXT'] = $this->input->post('STEXT');
                $id = $this->admin_m->talentdesc_new($a);
                redirect('admin/talentdesc', 'refresh');
            } else
                redirect('admin/talentdesc', 'refresh');
        } else {
            redirect('admin/talentdesc', 'refresh');
        }
    }

    function talentdesc_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_desc', 'id_desc', 'trim|required');
            $this->form_validation->set_rules('SHORT', 'SHORT', 'trim|required');
            $this->form_validation->set_rules('STEXT', 'STEXT', 'trim|required');
            $this->form_validation->set_rules('DESC', 'DESC', 'trim|required');
            if ($this->form_validation->run()) {
                $id_desc = $this->input->post('id_desc');
                $a['DESC'] = $this->input->post('DESC');
                $a['SHORT'] = $this->input->post('SHORT');
                $a['STEXT'] = $this->input->post('STEXT');
                $id = $this->admin_m->talentdesc_update($id_desc, $a);
                redirect('admin/talentdesc', 'refresh');
            } else
                redirect('admin/talentdesc', 'refresh');
        } else {
            redirect('admin/talentdesc', 'refresh');
        }
    }

    function talentdesc_del($id_desc) {
        $this->admin_m->talentdesc_delete($id_desc);
        redirect('admin/talentdesc', 'refresh');
    }

    function cr_readiness() {
        $data = $this->admin_m->cr_readiness();
        $this->load->view('main', $data);
    }

    function cr_readiness_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->cr_readiness_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->cr_readiness_fr_new();
            $this->load->view('main', $data);
        }
    }

    function cr_readiness_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('DESC', 'DESC', 'trim|required');
            $this->form_validation->set_rules('SUBTY', 'SUBTY', 'trim|required');
            $this->form_validation->set_rules('PERCT', 'PERCT', 'trim|required');
            if ($this->form_validation->run()) {
                $a['DESC'] = $this->input->post('DESC');
                $a['SUBTY'] = $this->input->post('SUBTY');
                $a['PERCT'] = $this->input->post('PERCT');
                $id = $this->admin_m->cr_readiness_new($a);
                redirect('admin/cr_readiness', 'refresh');
            } else
                redirect('admin/cr_readiness', 'refresh');
        } else {
            redirect('admin/cr_readiness', 'refresh');
        }
    }

    function cr_readiness_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_criteria', 'id_criteria', 'trim|required');
            $this->form_validation->set_rules('SUBTY', 'SUBTY', 'trim|required');
            $this->form_validation->set_rules('PERCT', 'PERCT', 'trim|required');
            $this->form_validation->set_rules('DESC', 'DESC', 'trim|required');
            if ($this->form_validation->run()) {
                $id_criteria = $this->input->post('id_criteria');
                $a['DESC'] = $this->input->post('DESC');
                $a['PERCT'] = $this->input->post('PERCT');
                $a['SUBTY'] = $this->input->post('SUBTY');
                $id = $this->admin_m->cr_readiness_upd($id_criteria, $a);
                redirect('admin/cr_readiness', 'refresh');
            } else
                redirect('admin/cr_readiness', 'refresh');
        } else {
            redirect('admin/cr_readiness', 'refresh');
        }
    }

    function cr_readiness_del($id_criteria) {
        $this->admin_m->cr_readiness_delete($id_criteria);
        redirect('admin/cr_readiness', 'refresh');
    }

    function cr_readiness_detail_upd($id_criteria) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_criteria', 'id_criteria', 'trim|required');
            $this->form_validation->set_rules('SUBTY', 'SUBTY', 'trim|required');
            $this->form_validation->set_rules('MIN', 'MIN', 'trim|required');
            $this->form_validation->set_rules('MAX', 'MAX', 'trim');
            $this->form_validation->set_rules('PERCT', 'PERCT', 'trim|required');
            if ($this->form_validation->run()) {
                $a['id_criteria'] = $this->input->post('id_criteria');
                $a['SUBTY'] = $this->input->post('SUBTY');
                $a['PERCT'] = $this->input->post('PERCT');
                $a['MIN'] = $this->input->post('MIN');
                $a['MAX'] = $this->input->post('MAX');
                $id = $this->admin_m->cr_readiness_detail_upd($a);
                redirect('admin/cr_readiness_fr/' . $id_criteria, 'refresh');
            } else
                redirect('admin/cr_readiness', 'refresh');
        } else {
            redirect('admin/cr_readiness', 'refresh');
        }
    }

    function cr_readinessd_del($id_criteria, $id_re_detail) {
        $this->admin_m->cr_readinessd_del($id_re_detail);
        redirect('admin/cr_readiness_fr/'. $id_criteria, 'refresh');
    }

    function pic_customer() {
        $data = $this->admin_m->pic_customer();
        $this->load->view('main', $data);
    }

    function pic_customer_fr($iSeq = "") {
        if (!empty($iSeq)) {
            $data = $this->admin_m->pic_customer_fr_update($iSeq);
            $this->load->view('main', $data);
        } else {
            $data = $this->admin_m->pic_customer_fr_new();
            $this->load->view('main', $data);
        }
    }

    function pic_customer_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('type', 'type', 'trim|required');
            $this->form_validation->set_rules('WERKS', 'WERKS', 'trim|required');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            if ($this->form_validation->run()) {
                $id = $this->input->post('id');
                $a['type'] = $this->input->post('type');
                $a['WERKS'] = $this->input->post('WERKS');
                $a['pernr'] = $this->input->post('pernr');
                $a['unit_stext'] = $this->input->post('unit_stext');
                $a['unit_short'] = $this->input->post('unit_short');
                $a['position'] = $this->input->post('position');
                $a['nama'] = $this->input->post('nama');
                $a['email'] = $this->input->post('email');
                $a['begda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['endda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                // echo $id;exit;
                // var_dump($a);exit;
                $this->admin_m->pic_customer_upd($id, $a);
                redirect('admin/pic_customer/', 'refresh');
            } else
                redirect('admin/pic_customer', 'refresh');
        } else {
            redirect('admin/pic_customer', 'refresh');
        }
    }

    function pic_customer_new() {
        if ($this->input->post()) {
            // var_dump($_POST);exit;
            $this->form_validation->set_rules('type', 'type', 'trim|required');
            $this->form_validation->set_rules('WERKS', 'WERKS', 'trim|required');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            if ($this->form_validation->run()) {
                $a['type'] = $this->input->post('type');
                $a['WERKS'] = $this->input->post('WERKS');
                $a['pernr'] = $this->input->post('pernr');
                $a['unit_stext'] = $this->input->post('unit_stext');
                $a['unit_short'] = $this->input->post('unit_short');
                $a['position'] = $this->input->post('position');
                $a['nama'] = $this->input->post('nama');
                $a['email'] = $this->input->post('email');
                $a['begda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['endda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $this->admin_m->pic_customer_insert($a);
                redirect('admin/pic_customer/', 'refresh');
            } else
                redirect('admin/pic_customer', 'refresh');
        } else {
            redirect('admin/pic_customer', 'refresh');
        }
    }

    function pic_customer_del($iSeq) {
        $this->admin_m->pic_customer_delete($iSeq);
        redirect('admin/pic_customer/', 'refresh');
    }

}

?>
