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
class in_out_m extends CI_Model {

    var $table = 'tm_payroll_inout';
    var $table_process = "tm_payroll_inout_process";
    var $WGTYP_ADVANCE_PAYMENT = array(1 => "3440", 2 => "8440");
    var $WGTYP_ADVANCE_DEDUCPAYMENT = array(1 => "3450", 2 => "8450");

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

    public function createInOut($name, $begda, $evtda, $filepath) {
        $input = array();
        $input['time_running'] = date("Ymd_His");
        $input['date_inout'] = date("Y-m-d");
        $input['name_inout'] = $name;
        $input['begda'] = $begda;
        $input['evtda'] = $evtda;
        $input['file_processed'] = $filepath;
        $input['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table, $input);
        return $this->db->insert_id();
    }

    public function publishToBankTransfer($idInOut, $name, $adata, $awgtyp, $aemp, $aMapEmpOrg, $evtdate, $begda) {
        //POPULATE BANK
        $this->load->model('pa/bank_m');
        $abank = $this->bank_m->get_emp_bank_by_emp_date($aemp, $evtdate);
        
        $aMapEmpBank = array();
        if (!empty($abank)) {
            foreach ($abank as $row) {
                $aMapEmpBank[$row['PERNR']] = $row;
            }
        }
        //POPULATE AMOUNT
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
            $aMapEmpAmt[$row['PERNR']]['iop'][] = $row['iop']['id_iop'];
        }
        $this->load->model('payroll/bank_transfer_m');
        //CREATE BT
        $id_bank_transfer = $this->bank_transfer_m->insertBankTransferForInOut($name, $begda);
        //CREATE BT.S
        $id_bank_transfer_stage = $this->bank_transfer_m->insertBankTransferStageForInOut($id_bank_transfer, $name, $begda);
        //UPDATING IO
        $this->db->where('id', $idInOut);
        $this->db->update($this->table, array('id_bank_transfer' => $id_bank_transfer, 'id_bank_transfer_stage' => $id_bank_transfer_stage));
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
            $input['percentage_rule'] = 100;
            $input['percentage'] = 100;
            $input['WAMNT'] = $input['TFMNT'] = $row['WAMNT'];
            $input['ABKRS'] = $eorg['ABKRS'];
            $input['created_by'] = $this->session->userdata('username');
            $this->db->insert($this->bank_transfer_m->table_stage_emp,$input);
            $id_bte = $this->db->insert_id();
            $this->db->where_in('id',$row['iop']);
            $this->db->update($this->table_process,array('id_bank_transfer_emp'=>$id_bte));
        }
    }

    public function getInOut() {
        $this->db->from($this->table);
        $this->db->order_by("id","DESC");
        return $this->db->get()->result_array();
    }

    public function sanitize_add_payment($aMapAddPayment) {
        $aRet = array('error' => null, 'source' => null);
        $aSource = array();
        $aBalance = array();
        $wg_inout = $this->getWGTYPInOut();
        foreach ($aMapAddPayment as $row) {
            if (in_array($row['WGTYP'], $wg_inout)) {
                $aBalance[] = $row;
            } else {
                $aSource[] = $row;
            }
        }
        if (empty($aSource)) {
            $aRet['error'] = 'source is empty';
            return $aRet;
        }
//        var_dump($aSource);
//        echo "<br/>";
//        var_dump($aBalance);
//        exit;
        foreach($aSource as $key=>$source){
            if (!empty($aBalance) && count($aBalance)>0) {
                foreach($aBalance as $keyB=>$balance){
                    if($source['PERNR'] == $balance['PERNR'] &&
                            $source['WAMNT'] == $balance['WAMNT'] &&
                            $source['NOTE'] == $balance['NOTE'] &&
                            $source['BEGDA'] == $balance['BEGDA']) {
//                        var_dump($aSource[$i]);exit;
//                        var_dump($aBalance[$j]);exit;
                        unset($aSource[$key]);
                        unset($aBalance[$keyB]);
                        break;
                    }
                }
            }
        }
        
//        for ($i = 0; $i < count($aSource); $i++) {
//            if (!empty($aBalance) && count($aBalance)>0) {
//                for ($j = 0; $j < count($aBalance); $j++) {
//                    if(empty($aBalance[$j])){
//                        break;
//                    }
//                    if ($aSource[$i]['PERNR'] == $aBalance[$j]['PERNR'] &&
//                            $aSource[$i]['WAMNT'] == $aBalance[$j]['WAMNT'] &&
//                            $aSource[$i]['NOTE'] == $aBalance[$j]['NOTE'] &&
//                            $aSource[$i]['BEGDA'] == $aBalance[$j]['BEGDA']) {
////                        var_dump($aSource[$i]);exit;
////                        var_dump($aBalance[$j]);exit;
//                        unset($aSource[$i]);
//                        unset($aBalance[$j]);
//                        $i--;
//                        $j--;
//                        break;
//                    }
//                }
//            }
//        }
        if (empty($aSource)) {
            $aRet['error'] = 'source is already balanced with Advance Payment/Deduction';
            return $aRet;
        }
        $aRet['source'] = $aSource;
        return $aRet;
    }

    public function get_wgtype($aMapAddPayment) {
        $awgtyp = array();
        foreach ($aMapAddPayment as $row) {
            if (in_array($row['WGTYP'], $awgtyp) == false) {
                $awgtyp[] = $row['WGTYP'];
            }
        }
        return $this->global_m->getMasterPayScaleArray($awgtyp);
    }

    public function run_in_out($id_inout, $data, $oEmp, $oWgtyp) {
        $wgtyp = null;
        if ($oWgtyp['PRTYP'] == '-') {
            if (in_array($oEmp['PERSG'], array('F', 'Z'))) {
                $wgtyp = $this->WGTYP_ADVANCE_DEDUCPAYMENT[2];
            } else {
                $wgtyp = $this->WGTYP_ADVANCE_DEDUCPAYMENT[1];
            }
        } else if ($oWgtyp['PRTYP'] == '+') {
            if (in_array($oEmp['PERSG'], array('F', 'Z'))) {
                $wgtyp = $this->WGTYP_ADVANCE_PAYMENT[2];
            } else {
                $wgtyp = $this->WGTYP_ADVANCE_PAYMENT[1];
            }
        }
        $data['WGTYP'] = $wgtyp;
        $data['REF_OBJ'] = json_encode(array('from' => 'INOUT', 'id' => $id_inout));
        $data['id_emp_addpayment'] = $id_addpayment = $this->addpayment_m->personal_addpayment_new($data);
        unset($data['REF_OBJ']);
        $data['WGTYP_S'] = $data['WGTYP'];
        $data['WGTYP_D'] = $oWgtyp['WGTYP'];
        unset($data['WGTYP']);
        unset($data['NOTE']);
        $data['id_payroll_inout']=$id_inout;
        $data['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table_process, $data);
        $id_iop = $this->db->insert_id();
        return array('id_iop' => $id_iop, 'wgtyp' => $wgtyp);
    }

}
