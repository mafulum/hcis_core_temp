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
class offcycle_m extends CI_Model {

    var $table = 'tm_payroll_offcycle';
    var $table_emp = "tm_emp_addoffcycle";

    public function __construct() {
        parent::__construct();
//        $this->load->model('payroll/bank_transfer_m');
    }

    public function getWGTYPInOut() {
        $aRet = array();
        foreach ($this->WGTYP_ADVANCE_PAYMENT as $key => $value) {
            $aRet[] = $value;
        }
        foreach ($this->WGTYP_ADVANCE_DEDUCPAYMENT as $key => $value) {
            $aRet[] = $value;
        }
        return $aRet;
    }

    public function createOffCycle($name, $begda, $evtda, $filepath,$percentage) {
        $input = array();
        $input['time_running'] = date("Ymd_His");
        $input['name'] = $name;
        $input['begda'] = $begda;
        $input['evtda'] = $evtda;
        $input['percentage'] = $percentage;
        $input['file_processed'] = $filepath;
        $input['created_by'] = $this->session->userdata('username');
        $input['created_at'] = date("Y-m-d H:i:s");
        $this->db->insert($this->table, $input);
        return $this->db->insert_id();
    }

    public function publishToBankTransfer($id_offcycle, $name, $adata, $awgtyp, $aemp, $aMapEmpOrg, $evtdate, $begda,$percentage) {
        //POPULATE BANK
        $this->load->model('pa/bank_m');
        $abank = $this->bank_m->get_emp_bank_by_emp_date($aemp, $evtdate);
        
        $aMapEmpBank = array();
        if (!empty($abank)) {
            foreach ($abank as $row) {
                $aMapEmpBank[$row['PERNR']] = $row;
            }
        }
//        POPULATE AMOUNT
        $aMapEmpAmt = array();
        foreach ($adata as $row) {
            if (empty($aMapEmpAmt[$row['PERNR']])) {
                $aMapEmpAmt[$row['PERNR']]=array();
                $aMapEmpAmt[$row['PERNR']]['WAMNT'] = 0;
            }
            if ($awgtyp[$row['WGTYP']]['PRTYP'] == '-') {
                $aMapEmpAmt[$row['PERNR']]['WAMNT'] = $aMapEmpAmt[$row['PERNR']]['WAMNT'] - $row['WAMNT'];
            } else if ($awgtyp[$row['WGTYP']]['PRTYP'] == '+') {
                $aMapEmpAmt[$row['PERNR']]['WAMNT'] = $aMapEmpAmt[$row['PERNR']]['WAMNT'] + $row['WAMNT'];
            }
            $aMapEmpAmt[$row['PERNR']]['ioc'][] = $row['ioc'];
        }
        $this->load->model('payroll/bank_transfer_m');
        //CREATE BT
        $id_bank_transfer = $this->bank_transfer_m->insertBankTransferForOffCycle($name, $begda,1,$evtdate);
        //CREATE BT.S
        $id_bank_transfer_stage = $this->bank_transfer_m->insertBankTransferStageForOffCycle($id_bank_transfer,$percentage);
        //UPDATING IO
        
        $this->db->where('id', $id_offcycle);
        $aUpdate =  array('id_bank_transfer' => $id_bank_transfer, 'id_bank_transfer_stage' => $id_bank_transfer_stage);
        $this->db->update($this->table,$aUpdate);
        //CREATE BT.E
        //UPDATING IO.P
        foreach ($aMapEmpAmt as $pernr => $row) {
            $input = array();
            $bank = $aMapEmpBank[$pernr];
            $eorg = $aMapEmpOrg[$pernr];
            $input['PERNR'] = $pernr;
            $input['id_bank_transfer'] = $id_bank_transfer;
            $input['id_bank_transfer_stage'] = $id_bank_transfer_stage;
            $input['BANK_ORDER'] = $bank['BANK_ORDER'];
            $input['BANK_ID'] = $bank['BANK_MID'];
            $input['BANK_NAME'] = $bank['BANK_NAME'];
            $input['BANK_PAYEE'] = $bank['BANK_PAYEE'];
            $input['BANK_ACCOUNT'] = $bank['BANK_ACCOUNT'];
            $input['percentage_rule'] = $percentage;
            $input['percentage'] = $percentage;
            $input['WAMNT'] = round($row['WAMNT']*($percentage/100),0); 
            $input['TFMNT'] = $row['WAMNT'];
            $input['ABKRS'] = $eorg['ABKRS'];
            $input['created_by'] = $this->session->userdata('username');
            $this->db->insert($this->bank_transfer_m->table_stage_emp,$input);
            $id_bte = $this->db->insert_id();
            $this->db->where_in('id',$row['ioc']);
            $this->db->update($this->table_emp,array('id_bank_transfer_emp'=>$id_bte));
        }
    }

    public function getOffCycle($id=null) {
        $this->db->from($this->table);
        if(!empty($id)){
            $this->db->where('id',$id);
            return $this->db->get()->row_array();
        }
        $this->db->order_by("id","DESC");
        return $this->db->get()->result_array();
    }
    
    public function getOffCycleDetail($id_offcycle){
        $this->db->from($this->table_emp);
        $this->db->where('id_payroll_offcycle',$id_offcycle);
        return $this->db->get()->result_array();
    }
    
    public function get_wgtype() {
        $awgtyp = ["2000","7000","2150","7150"];
        return $this->global_m->getMasterPayScaleArray($awgtyp);
    }

    public function run_offcycle($id_offcycle, $data, $begda,$evtda) {
        $data['BEGDA']=$begda;
        $data['EVTDA']=$evtda;
        $data['id_payroll_offcycle']=$id_offcycle;
        $data['created_by'] = $this->session->userdata('username');
        $data['created_at'] = date("Y-m-d H:i:s");
        $this->db->insert($this->table_emp, $data);
        return $this->db->insert_id();
    }

}
