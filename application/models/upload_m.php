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
class upload_m extends CI_Model{
    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('global_m');
    }
    function master_organisasi(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/OM_ORG';
        return $data;
    }
    function master_posisi(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/OM_POS';
        return $data;
    }
    function master_emp(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA0000';
        return $data;
    }
    function master_eduf(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_EDUF';
        return $data;
    }
    function master_edunf(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_EDUNF';
        return $data;
    }
    function master_ach(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_AWARDS';
        return $data;
    }
    function master_gri(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_GRI';
        return $data;
    }
    function master_med(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_MED';
        return $data;
    }
    function master_date(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_DATE';
        return $data;
    }
    function master_emp_personalid(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_PersonalID';
        return $data;
    }
    function master_emp_npwp(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_NPWP';
        return $data;
    }
    
    function master_emp_address(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_ADDRESS';
        return $data;
    }
    
    function master_emp_comm(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_COMM';
        return $data;        
    }
    
    function master_emp_family(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_FAM';
        return $data;        
    }
    
    function master_emp_rekening(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_REKENING';
        return $data;
    }
    function master_notes(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_NOTES';
        return $data;
    }
    function get_flag_mapping_pernr($nik){
        $sQuery="SELECT count(*) n from tm_master_emp where PERNR='$nik'";
        $oRes = $this->db->query($sQuery);
        $aRow=$oRes->row_array();
        if($aRow['n']>0){
            return FALSE;
        }
        return TRUE;
    }
    function add_new_employee($configShort, $oldNik, $begda, $endda) {
        //gen pernr
        $prefixComp = substr($sComp, 0, 3);
        $n_pernr = $this->orgchart_m->get_config_by_short("PERNR", $configShort);
        $this->orgchart_m->add_config_by_short("PERNR", $configShort, $n_pernr);
        $this->saving_map_pernr($n_pernr, $oldNik, $sComp, $begda);
        return $n_pernr;
    }
}

?>
