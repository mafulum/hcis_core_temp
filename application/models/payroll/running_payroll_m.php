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
class running_payroll_m extends CI_Model {

    var $table = 'tm_payroll_running'; //nama tabel dari database
    var $table_emp = 'tm_payroll_running_emp'; //nama tabel dari database
    var $table_transfer = 'tm_payroll_running_transfer'; //nama tabel dari database
    var $table_wagetype = 'tm_payroll_running_wagetype'; //nama tabel dari database
    var $column_order = array(null, 'time_running', 'nopeg', 'persk', 'persk', 'persg', 'abkrs', 'draft_by', 'confirm_by'); //field yang ada di table user
    var $column_search = array('time_running', 'nopeg', 'persk', 'persk', 'persg', 'abkrs', 'draft_by', 'confirm_by'); //field yang diizin untuk pencarian 
    var $order = array('time_running' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }

    function updateByTimeRunning($timeRunning, $data) {
        $this->db->where('time_running', $timeRunning);
        $data['updated_by'] = $this->session->userdata('username');
        return $this->db->update('tm_payroll_running', $data);
    }

    private function _get_datatables_query() {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) { // looping awal
            if (isset($_GET['search']) && $_GET['search']['value']) { // jika datatable mengirimkan pencarian dengan metode POST
                if ($i === 0) { // looping awal
                    $this->db->group_start();
                    $this->db->like($item, $_GET['search']['value']);
                } else {
                    $this->db->or_like($item, $_GET['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

//        if (isset($_GET['order'])) {
//            $this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
//        } else if (isset($this->order)) {
//            $order = $this->order;
//            $this->db->order_by(key($order), $order[key($order)]);
//        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if (isset($_GET['length']) && $_GET['length'] != -1){
            $this->db->limit($_GET['length'], $_GET['start']);
        }
        
//        $this->db->where('axx',123);
        $query = $this->db->get();
//        print_r($this->db->last_query()); 
//        exit;
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    public function get_by_id($id){
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $temp= $this->db->get();
        if($temp->num_rows()==0){
            return null;
        }
        return $temp->row_array();
        
    }
    
    public function get_by_running_time($sRunningTime){
        $this->db->from($this->table);
        $this->db->where('time_running',$sRunningTime);
        $temp = $this->db->get();
        if($temp->num_rows()==0){
            return null;
        }
        return $temp->row_array();
    }
    
    public function get_ids_by_confirmed_type_and_periode($isOffCycle,$dateOffCycle,$regularPeriod,$id_bank_transfer=null){
        $this->db->from($this->table);
        $this->db->where('by_confirm is not null');
        if(empty($id_bank_transfer)){
            $this->db->where('id_bank_transfer is null');
        }else{
            $this->db->where('id_bank_transfer',$id_bank_transfer);
        }
        $this->db->where('is_offcycle',$isOffCycle);
        if($isOffCycle){
            $this->db->where('date_offcycle',$dateOffCycle);
        }else{
            $this->db->where('periode_regular',$regularPeriod);
        }
        $this->db->select('id');
        $temp = $this->db->get();
        if(empty($temp) || $temp->num_rows()==0){
            return null;
        }
        return $temp->result_array();
    }
    
    public function get_emp_transfers($aIDS,$abkrs=null){
        $this->db->from($this->table_transfer);
        $this->db->where_in('id_payroll_running',$aIDS);
        if($abkrs){
            $this->db->where_in("ABKRS",$abkrs);
        }
        $this->db->order_by('ABKRS ASC,PERNR ASC,BANK_ORDER ASC');
        $aEmpTransfer = $this->db->get();
        if(empty($aEmpTransfer) || $aEmpTransfer->num_rows()==0){
            return null;
        }
        return $aEmpTransfer->result_array();
    }
    
    public function get_emp_distinct_abkrs($aIDS,$abkrs=null){
        $this->db->from($this->table_emp);
        $this->db->where_in('id_payroll_running',$aIDS);
        if($abkrs){
            $this->db->where_in("ABKRS",$abkrs);
        }
        $this->db->select('ABKRS');
        $this->db->group_by('ABKRS');
        $aABKRS= $this->db->get();
        if(empty($aABKRS) || $aABKRS->num_rows()==0){
            return null;
        }
        return $aABKRS->result_array();
    }
    
    public function get_emp_wgtyp_count_pernr($aIDS,$abkrs=null){
        $sWhereAdd="";
        if($abkrs){
            $sWhereAdd=" AND ABKRS IN (".implode(",",$abkrs).") ";
        }
        $sQuery = "SELECT ABKRS,count(pernr) n FROM (SELECT ABKRS,PERNR FROM $this->table_wagetype "
                . " WHERE id_payroll_running in (".implode(",",$aIDS).") AND PRTYP<>'|' $sWhereAdd GROUP BY ABKRS,PERNR ) x "
                . "GROUP BY ABKRS";
        $aSum = $this->db->query($sQuery);
        if(empty($aSum) || $aSum->num_rows()==0){
            return null;
        }
        $aRet = array();
        $temp =  $aSum->result_array();
        foreach($temp as $row){
            $aRet[$row['ABKRS']]=$row['n'];
        }
        return $aRet;
    }
    
    public function get_emp_transfer_sum_wamnt($aIDS,$abkrs=null){
        $this->db->from($this->table_transfer);
        $this->db->where_in('id_payroll_running',$aIDS);
        if($abkrs){
            $this->db->where_in("ABKRS",$abkrs);
        }
        $this->db->select('sum(TFMNT) as sum_wamnt,ABKRS');
        $this->db->group_by("ABKRS");
        $aSum = $this->db->get();
        if(empty($aSum) || $aSum->num_rows()==0){
            return null;
        }
        $aRet = array();
        $temp =  $aSum->result_array();
        foreach($temp as $row){
            $aRet[$row['ABKRS']]=$row['sum_wamnt'];
        }
        return $aRet;
    }
    
    public function get_emp_wagetype_by_pernr_bank_transfer($id_bank_transfer,$pernr=null,$abkrs=null){
//        echo $id_bank_transfer."|".$pernr."|".$abkrs;exit;
        $this->db->from($this->table_wagetype);
        $this->db->where('id_bank_transfer',$id_bank_transfer);
        $this->db->where('PERNR',$pernr);
        if(!empty($abkrs)){
            $this->db->where('ABKRS',$abkrs);
        }
        $this->db->select('WGTYP,LGTXT,SUM(WAMNT) WAMNT,PRTYP');
        $this->db->group_by("PERNR,WGTYP,LGTXT,PRTYP,ABKRS");
        $this->db->order_by('PRTYP ASC,WGTYP ASC');
        $temp = $this->db->get();
        if(empty($temp) || $temp->num_rows()==0){
            return null;
        }
        return $temp->result_array();
                
    }
    
    public function get_emp_wgtyp_pernr_wamnt_wgtyp($aIDS,$aWGTYP,$abkrs=null){
        $this->db->from($this->table_wagetype);
        $this->db->where_in('id_payroll_running',$aIDS);
        $this->db->where_in('WGTYP',$aWGTYP);
        if($abkrs){
            $this->db->where_in("ABKRS",$abkrs);
        }
        $this->db->select('PERNR,SUM(WAMNT) sum_wamnt,ABKRS');
        $this->db->group_by('PERNR,ABKRS');
        $aSum = $this->db->get();
        if(empty($aSum) || $aSum->num_rows()==0){
            return null;
        }
        $aRet = array();
        $temp =  $aSum->result_array();
        foreach($temp as $row){
            $aRet[$row['ABKRS']][$row['PERNR']]=$row['sum_wamnt'];
        }
        return $aRet;
    }
    
    public function get_emp_wgtyp_sum_wamnt_wgtyp($aIDS,$aWGTYP,$abkrs=null){
        $this->db->from($this->table_wagetype);
        $this->db->where_in('id_payroll_running',$aIDS);
        $this->db->where_in('WGTYP',$aWGTYP);
        if($abkrs){
            $this->db->where_in("ABKRS",$abkrs);
        }
        $this->db->select('sum(WAMNT) as sum_wamnt,ABKRS');
        $this->db->group_by("ABKRS");
        $aSum = $this->db->get();
        if(empty($aSum) || $aSum->num_rows()==0){
            return null;
        }
        $aRet = array();
        $temp =  $aSum->result_array();
        foreach($temp as $row){
            $aRet[$row['ABKRS']]=$row['sum_wamnt'];
        }
        return $aRet;
    }
    
    public function setIDBankTransfer($aIDS,$id_bank_transfer){
        $this->db->where_in('id',$aIDS);
        $this->db->update($this->table,array('id_bank_transfer'=>$id_bank_transfer,'updated_by' => $this->session->userdata('username')));
        $this->db->where_in('id_payroll_running',$aIDS);
        $this->db->update($this->table_emp,array('id_bank_transfer'=>$id_bank_transfer,'updated_by' => $this->session->userdata('username')));
        $this->db->where_in('id_payroll_running',$aIDS);
        $this->db->update($this->table_transfer,array('id_bank_transfer'=>$id_bank_transfer,'updated_by' => $this->session->userdata('username')));
        $this->db->where_in('id_payroll_running',$aIDS);
        $this->db->update($this->table_wagetype,array('id_bank_transfer'=>$id_bank_transfer,'updated_by' => $this->session->userdata('username')));
    }
    
    public function get_emp_profile_by_pernr_bank_transfer($id_bank_transfer,$pernr=null,$abkrs=null){
        $this->db->from($this->table_emp);
        $this->db->where('id_bank_transfer',$id_bank_transfer);
        $this->db->where('PERNR',$pernr);
        if(!empty($abkrs)){
            $this->db->where('ABKRS',$abkrs);
        }
        $this->db->order_by('ENDDA DESC');
        $temp = $this->db->get();
        if(empty($temp) || $temp->num_rows()==0){
            return null;
        }
        return $temp->row_array();
    }
    
    public function get_row_by_bank_transfer($id_bank_transfer){
        $this->db->from($this->table);
        $this->db->where('id_bank_transfer',$id_bank_transfer);
        $temp = $this->db->get();
        if(empty($temp) || $temp->num_rows()==0){
            return null;
        }
        return $temp->row_array();
    }
}

?>