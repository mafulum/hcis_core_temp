<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class emp_addpayment_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
    function reff_upload_page(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PY_ADDITIONAL';
        return $data;
    }
    function emp_addpayment_new($arr){
        if(isset($arr['CNAME'])){
            unset($arr['CNAME']);
        }
        $arr['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_addpayment', $arr);
    }
    
    
    
    
}