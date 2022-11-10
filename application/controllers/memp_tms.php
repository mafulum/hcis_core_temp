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
class memp_tms extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        if ($this->uri->segment(3) <> "" && $this->uri->segment(3) <> "-")
            $this->common->cekMethod($this->uri->segment(3));
    }

    function personal_leave($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_tms/leave_m');
            $data = $this->leave_m->personal_leave_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_leave_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_tms/leave_m');
        $data = $this->leave_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_leave_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_tms/leave_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->leave_m->personal_leave_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->leave_m->personal_leave_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_leave() {
        $this->load->model('pa_tms/leave_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->leave_m->check_time_constraint_leave($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_personal_leave() {
        $this->load->model('pa_tms/leave_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $id = $this->input->post('id_emp_leaveX');
        echo $this->leave_m->check_time_constraint_leave($pernr, $begda, $endda, "UPDATE", $id);
    }
    
    function personal_leave_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('lvtyp', 'lvtyp', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_tms/leave_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['LVTYP'] = $this->input->post('lvtyp');
                $a['NOTE'] = $this->input->post('note');
                $oRes = $this->leave_m->check_time_constraint_leave($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_leave where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_leave',$sQuery);
                    $oRes = $this->leave_m->check_time_constraint_leave($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_leave SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id='" . $aRow['id_emp_tax'] . "';";
                    $this->db->query($sQuery);
                }

                $this->leave_m->personal_leave_new($a);
                redirect('memp_tms/personal_leave/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_leave_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('lvtyp', 'lvtyp', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_tms/leave_m');
                $id= $this->input->post('id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['id'] = $this->input->post('id');
                $a['LVTYP'] = $this->input->post('lvtyp');
                $this->leave_m->personal_leave_upd($id, $pernr, $a);
                redirect('memp_tms/personal_leave/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_leave_del($sNopeg, $id_leave) {
        $this->load->model('pa_tms/leave_m');
        $this->leave_m->personal_leave_del($id_leave, $sNopeg);
        redirect('memp_tms/personal_leave/' . $sNopeg, 'refresh');
    }  
    
    function personal_overtime($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa_tms/overtime_m');
            $data = $this->overtime_m->personal_overtime_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_overtime_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_tms/overtime_m');
        $data = $this->overtime_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_overtime_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa_tms/overtime_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->overtime_m->personal_overtime_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->overtime_m->personal_overtime_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_overtime() {
        $this->load->model('pa_tms/overtime_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->input->post('begtiX');
        $endda = $this->input->post('endtiX');
        echo $this->overtime_m->check_time_constraint_overtime($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_personal_overtime() {
        $this->load->model('pa_tms/overtime_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->input->post('begtiX');
        $endda = $this->input->post('endtiX');
        $id = $this->input->post('id_emp_overtimeX');
        echo $this->overtime_m->check_time_constraint_overtime($pernr, $begda, $endda, "UPDATE", $id);
    }
    
    function personal_overtime_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begti', 'begti', 'trim|required');
            $this->form_validation->set_rules('endti', 'endti', 'trim|required');
            $this->form_validation->set_rules('prdpy', 'prdpy', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            $this->form_validation->set_rules('ihday', 'ihday', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa_tms/overtime_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGTI'] = $this->input->post('begti');
                $a['ENDTI'] = $this->input->post('endti');
                $a['IHDAY'] = $this->input->post('ihday');
                $a['PRDPY'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('prdpy'));
                $a['NOTE'] = $this->input->post('note');
                $oRes = $this->overtime_m->check_time_constraint_overtime($a['PERNR'], $a['BEGTI'], $a['ENDTI'], "CHECK");
                
//                if ($oRes->num_rows() > 0) {
//                    $sQuery = "DELETE FROM tm_emp_overtime where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
//                    $this->db->query($sQuery);
//                    $oRes = $this->overtime_m->check_time_constraint_overtime($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
//                }
//                
//                if ($oRes->num_rows() == 1) {
//                    $aRow = $oRes->row_array();
//                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
//                    $oRes = $this->db->query($sQuery);
//                    $aX = $oRes->row_array();
//                    $sQuery = "UPDATE tm_emp_overtime SET ENDDA='" . $aX['ival'] . "' WHERE id='" . $aRow['id_emp_tax'] . "';";
//                    $this->db->query($sQuery);
//                }

                $this->overtime_m->personal_overtime_new($a);
                redirect('memp_tms/personal_overtime/' . $pernr, 'refresh');
            } else{
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_overtime_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begti', 'begti', 'trim|required');
            $this->form_validation->set_rules('endti', 'endti', 'trim|required');
            $this->form_validation->set_rules('prdpy', 'prdpy', 'trim|required');
            $this->form_validation->set_rules('ihday', 'ihday', 'trim|required');
            $this->form_validation->set_rules('otpnt', 'otpnt', 'trim');
            $this->form_validation->set_rules('note', 'note', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa_tms/overtime_m');
                $id= $this->input->post('id');
                $pernr = $this->input->post('pernr');
                $a['BEGTI'] = $this->input->post('begti');
                $a['ENDTI'] = $this->input->post('endti');
                $a['PRDPY'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('prdpy'));
                $a['IHDAY'] = $this->input->post('ihday');
                $a['OTPNT'] = $this->input->post('otpnt');
                $a['NOTE'] = $this->input->post('note');
                $this->overtime_m->personal_overtime_upd($id, $pernr, $a);
                redirect('memp_tms/personal_overtime/' . $pernr, 'refresh');
            } else{
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_overtime_del($sNopeg, $id_overtime) {
        $this->load->model('pa_tms/overtime_m');
        $this->overtime_m->personal_overtime_del($id_overtime, $sNopeg);
        redirect('memp_tms/personal_overtime/' . $sNopeg, 'refresh');
    }  
}
?>