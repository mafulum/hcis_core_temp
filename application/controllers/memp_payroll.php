<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of employee
 *
 * @author Garuda
 */
class memp_payroll extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        if ($this->uri->segment(3) <> "" && $this->uri->segment(3) <> "-")
            $this->common->cekMethod($this->uri->segment(3));
    }

    function personal_npwp($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/npwp_m');
            $data = $this->npwp_m->personal_npwp_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_npwp_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/npwp_m');
        $data = $this->npwp_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_npwp_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/npwp_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->npwp_m->personal_npwp_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->npwp_m->personal_npwp_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_npwp() {
        $this->load->model('pa_payroll/npwp_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->npwp_m->check_time_constraint_npwp($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_personal_npwp() {
        $this->load->model('pa_payroll/npwp_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $id_emp_tax = $this->input->post('id_emp_taxX');
        echo $this->npwp_m->check_time_constraint_npwp($pernr, $begda, $endda, "UPDATE", $id_emp_tax);
    }

    function personal_npwp_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fptkp', 'fptkp', 'trim|required');
            $this->form_validation->set_rules('cTaxid', 'cTaxid', 'trim|required');
            $this->form_validation->set_rules('cRdate', 'cRdate', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/npwp_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TAXID'] = $this->input->post('cTaxid');
                $a['DEPND'] = $this->input->post('fptkp');
                $a['RDATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('cRdate'));
                $oRes = $this->npwp_m->check_time_constraint_npwp($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_tax where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_tax', $sQuery);
                    $oRes = $this->npwp_m->check_time_constraint_npwp($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_tax SET ENDDA='" . $aX['ival'] . "',updated_by = '" . $this->session->userdata('username') . "' WHERE id_emp_tax='" . $aRow['id_emp_tax'] . "';";
                    $this->db->query($sQuery);
                }

                $this->npwp_m->personal_npwp_new($a);
                redirect('memp_payroll/personal_npwp/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_npwp_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_emp_tax', 'id_emp_tax', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fptkp', 'fptkp', 'trim|required');
            $this->form_validation->set_rules('cTaxid', 'cTaxid', 'trim|required');
            $this->form_validation->set_rules('cRdate', 'cRdate', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/npwp_m');
                $id_emp_tax = $this->input->post('id_emp_tax');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TAXID'] = $this->input->post('cTaxid');
                $a['DEPND'] = $this->input->post('fptkp');
                $a['RDATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('cRdate'));
                $this->npwp_m->personal_npwp_upd($id_emp_tax, $pernr, $a);
                redirect('memp_payroll/personal_npwp/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_npwp_del($sNopeg, $id_npwp) {
        $this->load->model('pa_payroll/npwp_m');
        $this->npwp_m->personal_npwp_del($id_npwp, $sNopeg);
        redirect('memp_payroll/personal_npwp/' . $sNopeg, 'refresh');
    }

    function personal_bpjs_tk($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/bpjs_tk_m');
            $data = $this->bpjs_tk_m->personal_bpjs_tk_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_bpjs_tk_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/bpjs_tk_m');
        $data = $this->bpjs_tk_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_bpjs_tk_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/bpjs_tk_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->bpjs_tk_m->personal_bpjs_tk_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->bpjs_tk_m->personal_bpjs_tk_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

//
    function insert_check_time_constraint_personal_bpjs_tk() {
        $this->load->model('pa_payroll/bpjs_tk_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->bpjs_tk_m->check_time_constraint_bpjs_tk($pernr, $begda, $endda, "INSERT");
    }

//
    function update_check_time_constraint_personal_bpjs_tk() {
        $this->load->model('pa_payroll/bpjs_tk_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $id_emp_bpjs_tk = $this->input->post('id_emp_bpjs_tkX');
        echo $this->bpjs_tk_m->check_time_constraint_bpjs_tk($pernr, $begda, $endda, "UPDATE", $id_emp_bpjs_tk);
    }

//    
    function personal_bpjs_tk_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('bpjsid', 'bpjsid', 'trim|required');
            $this->form_validation->set_rules('rdate', 'rdate', 'trim');
            $this->form_validation->set_rules('fclty', 'fclty', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/bpjs_tk_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BPJSID'] = $this->input->post('bpjsid');
                $a['FCLTY'] = $this->input->post('fclty');
                $a['RDATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('rdate'));
                $oRes = $this->bpjs_tk_m->check_time_constraint_bpjs_tk($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_bpjs_tk where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_bpjs_tk', $sQuery);
                    $oRes = $this->bpjs_tk_m->check_time_constraint_bpjs_tk($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_bpjs_tk SET ENDDA='" . $aX['ival'] . "',updated_by = '" . $this->session->userdata('username') . "' WHERE id_emp_bpjs_tk='" . $aRow['id_emp_bpjs_tk'] . "';";
                    $this->db->query($sQuery);
                }

                $this->bpjs_tk_m->personal_bpjs_tk_new($a);
                redirect('memp_payroll/personal_bpjs_tk/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

//
    function personal_bpjs_tk_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_emp_bpjs_tk', 'id_emp_bpjs_tk', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('bpjsid', 'bpjsid', 'trim|required');
            $this->form_validation->set_rules('rdate', 'rdate', 'trim|required');
            $this->form_validation->set_rules('fclty', 'fclty', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/bpjs_tk_m');
                $id_emp_bpjs_tk = $this->input->post('id_emp_bpjs_tk');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BPJSID'] = $this->input->post('bpjsid');
                $a['FCLTY'] = $this->input->post('fclty');
                $a['RDATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('rdate'));
                $this->bpjs_tk_m->personal_bpjs_tk_upd($id_emp_bpjs_tk, $pernr, $a);
                redirect('memp_payroll/personal_bpjs_tk/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

//
    function personal_bpjs_tk_del($sNopeg, $id) {
        $this->load->model('pa_payroll/bpjs_tk_m');
        $this->bpjs_tk_m->personal_bpjs_tk_del($id, $sNopeg);
        redirect('memp_payroll/personal_bpjs_tk/' . $sNopeg, 'refresh');
    }

    function personal_inshealth($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/inshealth_m');
            $data = $this->inshealth_m->personal_inshealth_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_inshealth_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/inshealth_m');
        $data = $this->inshealth_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }
    function personal_inshealth_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/inshealth_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->inshealth_m->personal_inshealth_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->inshealth_m->personal_inshealth_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_inshealth() {
        $this->load->model('pa_payroll/inshealth_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $insty = $this->input->post('instyX');
        $fam = $this->input->post('famX');
        $objps = null;
        if ($fam != "9999") {
            $this->load->model('pa/fam_m');
            $fam = str_replace("F", "", $fam);
            $row_fam = $this->fam_m->get_tm_emp_fam_row($fam, $pernr);
            $fam = $row_fam['SUBTY'];
            $objps = $row_fam['OBJPS'];
        }
        echo $this->inshealth_m->check_time_constraint_inshealth($pernr, $begda, $endda, $insty, $fam, $objps, "INSERT");
    }

    function update_check_time_constraint_personal_inshealth() {
        $this->load->model('pa_payroll/inshealth_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $insty = $this->input->post('instyX');
        $fam = $this->input->post('famX');
        $objps = null;
        if ($fam != "9999") {
            $this->load->model('pa/fam_m');
            $fam = str_replace("F", "", $fam);
            $row_fam = $this->fam_m->get_tm_emp_fam_row($fam, $pernr);
            $fam = $row_fam['SUBTY'];
            $objps = $row_fam['OBJPS'];
        }
        $emp_inshealth_id = $this->input->post('emp_inshealth_idX');
        echo $this->inshealth_m->check_time_constraint_inshealth($pernr, $begda, $endda, $insty, $fam, $objps, "UPDATE", $emp_inshealth_id);
    }

    function personal_inshealth_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('insty', 'insty', 'trim|required');
            $this->form_validation->set_rules('fam', 'fam', 'trim|required');
            $this->form_validation->set_rules('rdate', 'rdate', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/inshealth_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['INSTY'] = $this->input->post('insty');
                $a['INSID'] = $this->input->post('insid');
                $id_fam = $this->input->post('fam');
                if ($id_fam == "9999") {
                    $a['FAMSA'] = $id_fam;
                    $a['OBJPS'] = null;
                } else {
                    $id_fam = str_replace("F", "", $id_fam);
                    $this->load->model('pa/fam_m');
                    $row_fam = $this->fam_m->get_tm_emp_fam_row($id_fam, $a['PERNR']);
                    $a['FAMSA'] = $row_fam['SUBTY'];
                    $a['OBJPS'] = $row_fam['OBJPS'];
                }
                $a['RDATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('rdate'));
                $oRes = $this->inshealth_m->check_time_constraint_inshealth($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['INSTY'], $a['FAMSA'], $a['OBJPS'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sAdd = "";
                    if ($a['FAMSA'] != "9999") {
                        $sAdd = " AND OBJPS='" . $a['OBJPS'] . "'";
                    }
                    $sQuery = "DELETE FROM tm_emp_inshealth where PERNR='" . $a['PERNR'] . "' AND FAMSA='" . $a['FAMSA'] . "' " . $sAdd . " AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_inshealth', $sQuery);
                    $oRes = $this->inshealth_m->check_time_constraint_inshealth($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['FAMSA'], $a['OBJPS'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_inshealth SET ENDDA='" . $aX['ival'] . "',updated_by = '" . $this->session->userdata('username') . "' WHERE emp_inshealth_id='" . $aRow['emp_inshealth_id'] . "';";
                    $this->db->query($sQuery);
                }

                $this->inshealth_m->personal_inshealth_new($a);
                redirect('memp_payroll/personal_inshealth_fr/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_inshealth_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('emp_inshealth_id', 'emp_inshealth_id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('marst', 'marst', 'trim|required');
            $this->form_validation->set_rules('bpjsid', 'bpjsid', 'trim|required');
            $this->form_validation->set_rules('rdate', 'rdate', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/inshealth_m');
                $emp_inshealth_id = $this->input->post('emp_inshealth_id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BPJSID'] = $this->input->post('bpjsid');
                $a['MARST'] = $this->input->post('marst');
                $a['RDATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('rdate'));
                $this->inshealth_m->personal_inshealth_upd($emp_inshealth_id, $pernr, $a);
                redirect('memp_payroll/personal_inshealth_fr/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_inshealth_del($sNopeg, $id) {
        $this->load->model('pa_payroll/inshealth_m');
        $this->inshealth_m->personal_inshealth_del($id, $sNopeg);
        redirect('memp_payroll/personal_inshealth/' . $sNopeg, 'refresh');
    }

    function personal_insurance($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/insurance_m');
            $data = $this->insurance_m->personal_insurance_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function personal_insurance_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/insurance_m');
        $data = $this->insurance_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_insurance_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/insurance_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->insurance_m->personal_insurance_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->insurance_m->personal_insurance_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_insurance() {
        $this->load->model('pa_payroll/insurance_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $insty = $this->input->post('instyX');
        echo $this->insurance_m->check_time_constraint_insurance($pernr, $begda, $endda, $insty, "INSERT");
    }

    function update_check_time_constraint_personal_insurance() {
        $this->load->model('pa_payroll/insurance_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $insty = $this->input->post('instyX');
        $emp_insurance_id = $this->input->post('emp_insurance_idX');
        echo $this->insurance_m->check_time_constraint_insurance($pernr, $begda, $endda, $insty, "UPDATE", $emp_insurance_id);
    }

    function personal_insurance_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('insty', 'insty', 'trim|required');
            $this->form_validation->set_rules('prcte', 'prcte', 'trim');
            $this->form_validation->set_rules('prctc', 'prctc', 'trim');
            $this->form_validation->set_rules('maxre', 'maxre', 'trim');
            $this->form_validation->set_rules('maxrc', 'maxrc', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/insurance_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['INSTY'] = $this->input->post('insty');
                $a['MAXRE'] = $this->input->post('maxre');
                $a['MAXRC'] = $this->input->post('maxrc');
                $a['PRCTE'] = $this->input->post('prcte');
                $a['PRCTC'] = $this->input->post('prctc');
                $oRes = $this->insurance_m->check_time_constraint_insurance($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['INSTY'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sAdd = "";

                    $sQuery = "DELETE FROM tm_emp_insurance where PERNR='" . $a['PERNR'] . "' AND INSTY='" . $a['INSTY'] . "' " . $sAdd . " AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_insurance', $sQuery);
                    $oRes = $this->insurance_m->check_time_constraint_insurance($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['INSTY'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_insurance SET ENDDA='" . $aX['ival'] . "',updated_by = '" . $this->session->userdata('username') . "' WHERE emp_insurance_id='" . $aRow['emp_insurance_id'] . "';";
                    $this->db->query($sQuery);
                }

                $this->insurance_m->personal_insurance_new($a);
                redirect('memp_payroll/personal_insurance/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_insurance_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('emp_insurance_id', 'emp_insurance_id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('insty', 'insty', 'trim|required');
            $this->form_validation->set_rules('prcte', 'prcte', 'trim');
            $this->form_validation->set_rules('prctc', 'prctc', 'trim');
            $this->form_validation->set_rules('maxre', 'maxre', 'trim');
            $this->form_validation->set_rules('maxrc', 'maxrc', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/insurance_m');
                $emp_insurance_id = $this->input->post('emp_insurance_id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['INSTY'] = $this->input->post('insty');
                $a['MAXRE'] = $this->input->post('maxre');
                $a['MAXRC'] = $this->input->post('maxrc');
                $a['PRCTE'] = $this->input->post('prcte');
                $a['PRCTC'] = $this->input->post('prctc');
                $this->insurance_m->personal_insurance_upd($emp_insurance_id, $pernr, $a);
                redirect('memp_payroll/personal_insurance/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_insurance_del($sNopeg, $id) {
        $this->load->model('pa_payroll/insurance_m');
        $this->insurance_m->personal_insurance_del($id, $sNopeg);
        redirect('memp_payroll/personal_insurance/' . $sNopeg, 'refresh');
    }

    ######################

    function personal_basicpay($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/basicpay_m');
            $data = $this->basicpay_m->personal_basicpay_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_basicpay_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/basicpay_m');
        $data = $this->basicpay_m->personal_basicpay_view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_basicpay_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/basicpay_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->basicpay_m->personal_basicpay_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->basicpay_m->personal_basicpay_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_basicpay() {
        $this->load->model('pa_payroll/basicpay_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $wgtyp = $this->input->post('wgtypX');
        echo $this->basicpay_m->check_time_constraint_basicpay($pernr, $begda, $endda, $wgtyp, "INSERT");
    }

    function update_check_time_constraint_personal_basicpay() {
        $this->load->model('pa_payroll/basicpay_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $insty = $this->input->post('instyX');
        $emp_basicpay_id = $this->input->post('emp_basicpay_idX');
        echo $this->basicpay_m->check_time_constraint_basicpay($pernr, $begda, $endda, $insty, "UPDATE", $emp_basicpay_id);
    }

    function personal_basicpay_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('wgtyp', 'wgtyp', 'trim|required');
            $this->form_validation->set_rules('wamnt', 'wamnt', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/basicpay_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['WGTYP'] = $this->input->post('wgtyp');
                $a['WAMNT'] = $this->input->post('wamnt');
                $oRes = $this->basicpay_m->check_time_constraint_basicpay($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['WGTYP'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sAdd = "";

                    $sQuery = "DELETE FROM tm_emp_basicpay where PERNR='" . $a['PERNR'] . "' AND WGTYP='" . $a['WGTYP'] . "' " . $sAdd . " AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_basicpay', $sQuery);
                    $oRes = $this->basicpay_m->check_time_constraint_basicpay($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['WGTYP'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_basicpay SET ENDDA='" . $aX['ival'] . "',updated_by = '" . $this->session->userdata('username') . "' WHERE emp_basicpay_id='" . $aRow['emp_basicpay_id'] . "';";
                    $this->db->query($sQuery);
                }

                $this->basicpay_m->personal_basicpay_new($a);
                redirect('memp_payroll/personal_basicpay/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_basicpay_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('emp_basicpay_id', 'emp_basicpay_id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('wgtyp', 'wgtyp', 'trim|required');
            $this->form_validation->set_rules('wamnt', 'wamnt', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/basicpay_m');
                $emp_basicpay_id = $this->input->post('emp_basicpay_id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['WGTYP'] = $this->input->post('wgtyp');
                $a['WAMNT'] = $this->input->post('wamnt');
                $this->basicpay_m->personal_basicpay_upd($emp_basicpay_id, $pernr, $a);
                redirect('memp_payroll/personal_basicpay/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_basicpay_del($sNopeg, $id) {
        $this->load->model('pa_payroll/basicpay_m');
        $this->basicpay_m->personal_basicpay_del($id, $sNopeg);
        redirect('memp_payroll/personal_basicpay/' . $sNopeg, 'refresh');
    }

    ######################

    function personal_recurring($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/recurring_m');
            $data = $this->recurring_m->personal_recurring_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_recurring_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/recurring_m');
        $data = $this->recurring_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_recurring_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/recurring_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->recurring_m->personal_recurring_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->recurring_m->personal_recurring_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_recurring() {
        $this->load->model('pa_payroll/recurring_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $wgtyp = $this->input->post('wgtypX');
        echo $this->recurring_m->check_time_constraint_recurring($pernr, $begda, $endda, $wgtyp, "INSERT");
    }

    function update_check_time_constraint_personal_recurring() {
        $this->load->model('pa_payroll/recurring_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $insty = $this->input->post('instyX');
        $emp_recurring_id = $this->input->post('emp_recurring_idX');
        echo $this->recurring_m->check_time_constraint_recurring($pernr, $begda, $endda, $insty, "UPDATE", $emp_recurring_id);
    }

    function personal_recurring_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('wgtyp', 'wgtyp', 'trim|required');
            $this->form_validation->set_rules('wamnt', 'wamnt', 'trim');
            $this->form_validation->set_rules('wapct', 'wapct', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/recurring_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['WGTYP'] = $this->input->post('wgtyp');
                $a['WAMNT'] = $this->input->post('wamnt');
                $a['WAPCT'] = $this->input->post('wapct');
                $oRes = $this->recurring_m->check_time_constraint_recurring($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['WGTYP'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sAdd = "";

                    $sQuery = "DELETE FROM tm_emp_recuradddeduc where PERNR='" . $a['PERNR'] . "' AND WGTYP='" . $a['WGTYP'] . "' " . $sAdd . " AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_basicpay', $sQuery);
                    $oRes = $this->recurring_m->check_time_constraint_recurring($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['WGTYP'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_recuradddeduc SET ENDDA='" . $aX['ival'] . "',updated_by = '" . $this->session->userdata('username') . "' WHERE emp_recurring_id='" . $aRow['emp_recurring_id'] . "';";
                    $this->db->query($sQuery);
                }

                $this->recurring_m->personal_recurring_new($a);
                redirect('memp_payroll/personal_recurring/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_recurring_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('emp_recuradddeduc_id', 'emp_recuradddeduc_id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('wgtyp', 'wgtyp', 'trim|required');
            $this->form_validation->set_rules('wamnt', 'wamnt', 'trim|');
            $this->form_validation->set_rules('wapct', 'wapct', 'trim|');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/recurring_m');
                $emp_recuradddeduc_id = $this->input->post('emp_recuradddeduc_id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['WGTYP'] = $this->input->post('wgtyp');
                $a['WAMNT'] = $this->input->post('wamnt');
                $a['WAPCT'] = $this->input->post('wapct');
                $this->recurring_m->personal_recurring_upd($emp_recuradddeduc_id, $pernr, $a);
                redirect('memp_payroll/personal_recurring/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_recurring_del($sNopeg, $id) {
        $this->load->model('pa_payroll/recurring_m');
        $this->recurring_m->personal_recurring_del($id, $sNopeg);
        redirect('memp_payroll/personal_recurring/' . $sNopeg, 'refresh');
    }

    ######################

    function personal_offcycle($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/offcyclepay_m');
            $data = $this->offcyclepay_m->overview($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_offcycle_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/offcyclepay_m');
        $data = $this->offcyclepay_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    ######################

    function personal_addpayment($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/addpayment_m');
            $data = $this->addpayment_m->personal_addpayment_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_addpayment_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/addpayment_m');
        $data = $this->addpayment_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_addpayment_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/addpayment_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->addpayment_m->personal_addpayment_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->addpayment_m->personal_addpayment_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_addpayment_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('evtda', 'evtda', 'trim|required');
            $this->form_validation->set_rules('wgtyp', 'wgtyp', 'trim|required');
            $this->form_validation->set_rules('wamnt', 'wamnt', 'trim');
            $this->form_validation->set_rules('wapnt', 'wapnt', 'trim');
            $this->form_validation->set_rules('note', 'note', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/addpayment_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['EVTDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('evtda'));
                $a['WGTYP'] = $this->input->post('wgtyp');
                $a['WAMNT'] = $this->input->post('wamnt');
                $a['WAPNT'] = $this->input->post('wapnt');
                $a['NOTE'] = $this->input->post('note');
                $a = $this->common->removeEmptyObjectFromArray($a);
                $this->addpayment_m->personal_addpayment_new($a);
                redirect('memp_payroll/personal_addpayment/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_addpayment_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('emp_addpayment_id', 'emp_addpayment_id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('evtda', 'ectda', 'trim|required');
            $this->form_validation->set_rules('wgtyp', 'wgtyp', 'trim|required');
            $this->form_validation->set_rules('wamnt', 'wamnt', 'trim|required');
            $this->form_validation->set_rules('wapnt', 'wapnt', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/addpayment_m');
                $emp_addpayment_id = $this->input->post('emp_addpayment_id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['EVTDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('evtda'));
                $a['WGTYP'] = $this->input->post('wgtyp');
                $a['WAMNT'] = $this->input->post('wamnt');
                $a['WAPNT'] = $this->input->post('wapnt');
                $a['NOTE'] = $this->input->post('note');
                $a = $this->common->removeEmptyObjectFromArray($a);
                $this->addpayment_m->personal_addpayment_upd($emp_addpayment_id, $pernr, $a);
                redirect('memp_payroll/personal_addpayment/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_addpayment_del($sNopeg, $id) {
        $this->load->model('pa_payroll/addpayment_m');
        $this->addpayment_m->personal_addpayment_del($id, $sNopeg);
        redirect('memp_payroll/personal_addpayment/' . $sNopeg, 'refresh');
    }

    ######################

    function personal_addtlinfo($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_payroll/addtlinfo_m');
            $data = $this->addtlinfo_m->personal_addtlinfo_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_addtlinfo_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/addtlinfo_m');
        $data = $this->addtlinfo_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_addtlinfo_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_payroll/addtlinfo_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->addtlinfo_m->personal_addtlinfo_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->addtlinfo_m->personal_addtlinfo_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_addtlinfo_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/addtlinfo_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['date_offcycle'] = $this->input->post('date_offcycle');
                $a['periode_regular'] = $this->input->post('periode_regular');
                $a['is_offcycle'] = $this->input->post('is_offcycle');
                $a['note'] = $this->input->post('note');
                $a = $this->common->removeEmptyObjectFromArray($a);
                $this->addtlinfo_m->personal_addtlinfo_new($a);
                redirect('memp_payroll/personal_addtlinfo/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_addtlinfo_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_payroll/addpayment_m');
                $emp_addpayment_id = $this->input->post('emp_addpayment_id');
                $pernr = $this->input->post('pernr');
                $a['date_offcycle'] = $this->input->post('date_offcycle');
                $a['periode_regular'] = $this->input->post('periode_regular');
                $a['is_offcycle'] = $this->input->post('is_offcycle');
                $a['note'] = $this->input->post('note');
                $a = $this->common->removeEmptyObjectFromArray($a);
                $this->addpayment_m->personal_addtlinfo_upd($emp_addpayment_id, $pernr, $a);
                redirect('memp_payroll/personal_addtlinfo/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_addtlinfo_del($sNopeg, $id) {
        $this->load->model('pa_payroll/addtlinfo_m');
        $this->addtlinfo_m->personal_addtlinfo_del($id, $sNopeg);
        redirect('memp_payroll/personal_addtlinfo/' . $sNopeg, 'refresh');
    }

}

?>
