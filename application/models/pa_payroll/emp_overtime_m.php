<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class emp_overtime_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }

    function reff_upload_page() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/TM_OVERTIME';
        return $data;
    }

    function emp_overtime_new($arr) {
        if (isset($arr['CNAME'])) {
            unset($arr['CNAME']);
        }
        $arr['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_overtime', $arr);
    }

    function get_a_overtime_begti_endti($sNopeg, $begti, $endti) {
        $sQuery = "SELECT * FROM tm_emp_overtime WHERE PERNR='" . $sNopeg . "'   AND BEGTI>='$begti' AND ENDTI<='$endti' ORDER BY PRDPY ASC,BEGTI ASC";
        $oRes = $this->db->query($sQuery);
        if(empty($oRes)){
            return null;
        }
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

}
