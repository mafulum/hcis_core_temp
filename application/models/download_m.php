<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_emp
 *
 * @author Garuda
 */
class download_m extends CI_Model{
    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('global_m');
    }
    function emp_org(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'download/emp_org';
        return $data;
    }
    
    function emp_bpjs(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'download/emp_bpjs';
        return $data;
    }
    function emp_payroll(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'download/emp_payroll';
        return $data;
    }
}

?>
