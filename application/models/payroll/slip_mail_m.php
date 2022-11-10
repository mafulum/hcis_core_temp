<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of employee_m
 *
 * @author Garuda
 */
class slip_mail_m extends CI_Model {

    var $table = 'tm_slip_mail';

    public function __construct() {
        parent::__construct();
        $this->load->model('payroll/running_payroll_m');
        $this->load->model('payroll/bank_transfer_m');
        $this->load->model('payroll/document_transfer_m');
    }
    
    public function getSlipMailUnSent($is_regular,$periode_regular){
        $this->db->from($this->table);
        $this->db->where('is_regular',$is_regular);
        $this->db->where('periode_regular',$periode_regular);
        $this->db->where('response_time is NOT NULL', NULL, FALSE);
        $this->db->where('response_text is NOT NULL', NULL, FALSE);
        $oRet = $this->db->get();
        return $oRet->result_array();
    }
    

}
