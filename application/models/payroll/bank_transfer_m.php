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
class bank_transfer_m extends CI_Model {

    var $table = 'tm_bank_transfer';
    var $table_stage = "tm_bank_transfer_stage";
    var $table_stage_emp = "tm_bank_transfer_emp";
    var $table_stage_other = "tm_bank_transfer_other";
    var $flag_3_bpjs_tk = array('3070', '8070', '807E', '307E', '807S', '309E', '809E', '809S', '308E', '808E', '808S', '3060', '8060', '8060', '3060', '306E', '806E', '306E', '806E', '806S');
    var $flag_3_bpjs_kes = array('3050', '8050', '805E', '305E', '805S');
    var $flag_3_bpjs_pihak_3 = array('3170', '317E', '319E', '318E', '3150', '315E', '3160', '3160', '316E', '316E', '3100', '310E', '3350', '335E', '3130', '313E', '3110', '8110', '3030', '8030', '3110', '8110', '3320', '8320', '3040', '304E', '3330', '3340', '3350', '335E', '3370', '3380', '3400', '8040');
    var $flag_3_bpjs_tax = array('312E', '812E');
    var $flag_3_gaji_gesa = array('3480', '3490', '8480', '8490');
    var $flag_3_ipk = array('4000', '9000');

    public function __construct() {
        parent::__construct();
        $this->load->model('payroll/running_payroll_m');
    }

    public function get_emp_transfer_from_running_payroll($isOffCycle, $dateOffCycle, $regularPeriod, $abkrs = null, $id_bank_transfer = null) {
        $aABKRS = null;
        if ($abkrs) {
            $aABKRS = explode(",", $abkrs);
        }
        $aIDStemp = $this->running_payroll_m->get_ids_by_confirmed_type_and_periode($isOffCycle, $dateOffCycle, $regularPeriod, $id_bank_transfer);
        if (empty($aIDStemp)) {
            return null;
        }
        $aIDS = array();
        foreach ($aIDStemp as $row) {
            $aIDS[] = $row['id'];
        }
        $aEmpTransfer = $this->running_payroll_m->get_emp_transfers($aIDS, $aABKRS);
        if (empty($aEmpTransfer)) {
            return null;
        }
        $aEmpABKRS = $this->running_payroll_m->get_emp_distinct_abkrs($aIDS, $aABKRS);
        if (empty($aEmpABKRS)) {
            return null;
        }
        $asum_transfer = $this->running_payroll_m->get_emp_transfer_sum_wamnt($aIDS);
        $asum_pihak_3_bpjs_jkn = $this->running_payroll_m->get_emp_wgtyp_sum_wamnt_wgtyp($aIDS, $this->flag_3_bpjs_kes);
        $asum_pihak_3_tax = $this->running_payroll_m->get_emp_wgtyp_sum_wamnt_wgtyp($aIDS, $this->flag_3_bpjs_tax);
        $asum_pihak_3_bpjs_tk = $this->running_payroll_m->get_emp_wgtyp_sum_wamnt_wgtyp($aIDS, $this->flag_3_bpjs_tk);
        $asum_pihak_3_pihak_3 = $this->running_payroll_m->get_emp_wgtyp_sum_wamnt_wgtyp($aIDS, $this->flag_3_bpjs_pihak_3);
        $transfer_pernr_thp = $this->running_payroll_m->get_emp_wgtyp_pernr_wamnt_wgtyp($aIDS, array('/THP'), $aABKRS);
        $asum_gaji_gesa = $this->running_payroll_m->get_emp_wgtyp_pernr_wamnt_wgtyp($aIDS, $this->flag_3_gaji_gesa);
        $asum_ipk = $this->running_payroll_m->get_emp_wgtyp_pernr_wamnt_wgtyp($aIDS, $this->flag_3_ipk);
        $an_emp = $this->running_payroll_m->get_emp_wgtyp_count_pernr($aIDS);
        $aRet = array("transfer" => $aEmpTransfer, "abkrs" => $aEmpABKRS, 'id_payroll_running_s' => $aIDS,
            'an_emp' => $an_emp,
            'transfer_pernr_thp' => $transfer_pernr_thp,
            'gaji_gesa' => $asum_gaji_gesa,
            'ipk' => $asum_ipk,
            'sum_transfer' => $asum_transfer,
            'bpjs_jkn' => $asum_pihak_3_bpjs_jkn,
            'bpjs_tk' => $asum_pihak_3_bpjs_tk,
            "tax" => $asum_pihak_3_tax,
            'pihak_3' => $asum_pihak_3_pihak_3);
        return $aRet;
    }

//    public function get_already_processed($id_bank_transfer){
//        $aEmpTransfer = $this->getDisplayBankTransferedEmp($id_bank_transfer);
//        if (empty($aEmpTransfer)) {
//            return null;
//        }
//        return $aEmpTransfer;
//    }

    public function calculate_emp_bank_transfer($emp_transfer, $emp_thp, $percentage, $bte_asli = null) {
        $pernr = "";
        $abkrs = "";
        $nominal = 0;
        $nominal_already_pay = 0;
        $bte = array();
        if (empty($percentage)) {
            $percentage = 100;
        }
        $aTempABKRS = array();
        if (!empty($bte_asli)) {
            foreach ($bte_asli as $row) {
                if (!in_array($row['ABKRS'], $aTempABKRS)) {
                    $aTempABKRS[] = $row['ABKRS'];
                }
                if (!empty($bte[$row['ABKRS']]) && !empty($bte[$row['ABKRS']][$row['PERNR']]) && !empty($bte[$row['ABKRS']][$row['PERNR']]['sum'])) {
                    $row['sum_wamnt'] = $row['sum_wamnt'] + $bte[$row['ABKRS']][$row['PERNR']]['sum'];
                }
                $bte[$row['ABKRS']][$row['PERNR']]['sum'] = $row['sum_wamnt'];
                $bte[$row['ABKRS']][$row['PERNR']]['bank_order'][$row['BANK_ORDER']] = $row;
            }
        }
        for ($i = 0; $i < count($emp_transfer); $i++) {
            if ($emp_transfer[$i]['TFMNT'] == 0) {
                continue;
            }

            if ($pernr != $emp_transfer[$i]['PERNR'] || $abkrs != $emp_transfer[$i]['ABKRS']) {
                $pernr = $emp_transfer[$i]['PERNR'];
                $abkrs = $emp_transfer[$i]['ABKRS'];
                if (empty($emp_thp[$abkrs][$pernr])) {
                    continue;
                }
                if (!empty($bte) && !empty($bte[$abkrs]) && !empty($bte[$abkrs][$pernr]) && $emp_transfer[$i]['TFMNT'] == $bte[$abkrs][$pernr]['bank_order'][$emp_transfer[$i]['BANK_ORDER']]['sum_wamnt']) {
                    continue;
                }
                if (!empty($bte) && !empty($bte[$abkrs]) && !empty($bte[$abkrs][$pernr])) {
                    $nominal_already_pay = $bte[$abkrs][$pernr]['sum'];
                }
//                echo $emp_thp[$abkrs][$pernr]."|".$percentage."<br/>";
                $nominal = round($emp_thp[$abkrs][$pernr] * ($percentage / 100), 0);
//                echo $nominal."<br/>";
                if (($nominal + $nominal_already_pay) > $emp_thp[$abkrs][$pernr]) {
                    $nominal = $emp_thp[$abkrs][$pernr] - $nominal_already_pay;
                }
//                echo $nominal."<br/>";
            }
            $emp_transfer[$i]['percentage_rule'] = $percentage;
            if (!empty($bte) && !empty($bte[$abkrs]) && !empty($bte[$abkrs][$pernr]) && !empty($bte[$abkrs][$pernr]['bank_order'])) {
                $already_pay = $bte[$abkrs][$pernr]['bank_order'][$emp_transfer[$i]['BANK_ORDER']]['sum_wamnt'];
                $already_percentage = $bte[$abkrs][$pernr]['bank_order'][$emp_transfer[$i]['BANK_ORDER']]['sum_percentage'];
//                echo $emp_transfer[$i]['TFMNT']."|".$already_pay."|".$nominal."<br/>";
                if ($nominal >= ($emp_transfer[$i]['TFMNT'] - $already_pay)) {
                    $emp_transfer[$i]['WAMNT'] = $emp_transfer[$i]['TFMNT'] - $already_pay;
                    $nominal = $nominal - $emp_transfer[$i]['TFMNT'];
                    $emp_transfer[$i]['percentage'] = 100 - $already_percentage;
                } else if ($nominal < $emp_transfer[$i]['TFMNT']) {
                    $emp_transfer[$i]['WAMNT'] = $nominal;
                    $emp_transfer[$i]['percentage'] = ($nominal / $emp_transfer[$i]['TFMNT']) * 100;
                    $nominal = 0;
                }
                //bank order amount sudah disiapkan
                //jika sudah percentage
                //
            } else {
                if (empty($nominal)) {
                    $emp_transfer[$i]['WAMNT'] = 0;
                    $emp_transfer[$i]['percentage'] = 0;
                } else if ($nominal == $emp_transfer[$i]['TFMNT']) {
                    $emp_transfer[$i]['WAMNT'] = $nominal;
                    $emp_transfer[$i]['percentage'] = 100;
                    $nominal = 0;
                } else if ($nominal > $emp_transfer[$i]['TFMNT']) {
                    $emp_transfer[$i]['WAMNT'] = $emp_transfer[$i]['TFMNT'];
                    $nominal = $nominal - $emp_transfer[$i]['TFMNT'];
                    $emp_transfer[$i]['percentage'] = 100;
                } else if ($nominal < $emp_transfer[$i]['TFMNT']) {
                    $emp_transfer[$i]['WAMNT'] = $nominal;
                    $emp_transfer[$i]['percentage'] = ($nominal / $emp_transfer[$i]['TFMNT']) * 100;
                    $nominal = 0;
                }
            }
        }
        return $emp_transfer;
    }

    public function confirm_to_db($data, $id_bank_transfer = null) {
        if (empty($data['run_payroll']['transfer'])) {
            return null;
        }
        $run_payroll = $data['run_payroll'];
        if (empty($id_bank_transfer)) {
            $input = array();
            $input['is_offcycle'] = $data['is_offcycle'];
            if (!empty($data['date_offcycle'])) {
                $input['date_offcycle'] = $data['date_offcycle'];
            }
            if (!empty($data['period_regular'])) {
                $input['periode_regular'] = $data['period_regular'];
            }
            $input['name'] = $data['name'];
            $input['created_by'] = $this->session->userdata('username');
            $this->db->insert($this->table, $input);
            $id_bank_transfer = $this->db->insert_id();
            $this->running_payroll_m->setIDBankTransfer($data['run_payroll']['id_payroll_running_s'], $id_bank_transfer);
            $colname_summary = array('sum_transfer', 'bpjs_tk', 'bpjs_jkn', 'tax', 'pihak_3', 'an_emp');
            $aABKRS = array();
            foreach ($colname_summary as $colname) {
                if (!empty($run_payroll[$colname])) {
                    foreach ($run_payroll[$colname] as $abkrs => $wamnt) {
                        if (!in_array($abkrs, $aABKRS)) {
                            $aABKRS[] = $abkrs;
                        }
                    }
                }
            }
            foreach ($aABKRS as $abkrs) {
                $input = array();
                $input['id_bank_transfer'] = $id_bank_transfer;
                $input['name'] = 'sum_transfer';
                $input['ABKRS'] = $abkrs;
                $input['WAMNT'] = 0;
                if (!empty($run_payroll['sum_transfer'][$abkrs])) {
                    $input['WAMNT'] = $run_payroll['sum_transfer'][$abkrs];
                }
                $input['created_by'] = $this->session->userdata('username');
                $this->db->insert($this->table_stage_other, $input);
                $input['name'] = 'bpjs_tk';
                $input['WAMNT'] = 0;
                if (!empty($run_payroll['bpjs_tk'][$abkrs])) {
                    $input['WAMNT'] = $run_payroll['bpjs_tk'][$abkrs];
                }
                $this->db->insert($this->table_stage_other, $input);
                $input['name'] = 'bpjs_jkn';
                $input['WAMNT'] = 0;
                if (!empty($run_payroll['bpjs_jkn'][$abkrs])) {
                    $input['WAMNT'] = $run_payroll['bpjs_jkn'][$abkrs];
                }
                $this->db->insert($this->table_stage_other, $input);
                $input['name'] = 'tax';
                $input['WAMNT'] = 0;
                if (!empty($run_payroll['tax'][$abkrs])) {
                    $input['WAMNT'] = $run_payroll['tax'][$abkrs];
                }
                $this->db->insert($this->table_stage_other, $input);
                $input['name'] = 'pihak_3';
                $input['WAMNT'] = 0;
                if (!empty($run_payroll['pihak_3'][$abkrs])) {
                    $input['WAMNT'] = $run_payroll['pihak_3'][$abkrs];
                }
                $this->db->insert($this->table_stage_other, $input);
                $input['name'] = 'an_emp';
                $input['WAMNT'] = 0;
                if (!empty($run_payroll['an_emp'][$abkrs])) {
                    $input['WAMNT'] = $run_payroll['an_emp'][$abkrs];
                }
                $this->db->insert($this->table_stage_other, $input);
            }
        }
        $input = array();
        $input['id_bank_transfer'] = $id_bank_transfer;
        if (!empty($data['name_stage'])) {
            $input['name'] = $data['name_stage'];
        }
        if (!empty($data['abkrs'])) {
            $input['ABKRS'] = $data['abkrs'];
        }
        if (!empty($data['percentage'])) {
            $input['percentage_emp'] = $data['percentage'];
        }
        $input['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table_stage, $input);
        $id_bank_transfer_stage = $this->db->insert_id();
        foreach ($data['run_payroll']['transfer'] as $row) {
            if (empty($row['WAMNT'])) {
                continue;
            }
            $input = array();
            $input['id_bank_transfer'] = $id_bank_transfer;
            $input['id_bank_transfer_stage'] = $id_bank_transfer_stage;
            $input['PERNR'] = $row['PERNR'];
            $input['BANK_ORDER'] = $row['BANK_ORDER'];
            $input['BANK_NAME'] = $row['BANK_NAME'];
            $input['BANK_PAYEE'] = $row['BANK_PAYEE'];
            $input['BANK_ACCOUNT'] = $row['BANK_ACCOUNT'];
            $input['percentage_rule'] = $row['percentage_rule'];
            $input['percentage'] = $row['percentage'];
            $input['TFMNT'] = $row['TFMNT'];
            $input['WAMNT'] = $row['WAMNT'];
            $input['ABKRS'] = $row['ABKRS'];
            $input['BANK_ID'] = $row['BANK_ID'];
            $input['created_by'] = $this->session->userdata('username');
            $this->db->insert($this->table_stage_emp, $input);
        }
        return $id_bank_transfer;
    }

    public function getBankTransfer($id_bank_transfer = null) {
        $this->db->from($this->table);
        if (!empty($id_bank_transfer)) {
            $this->db->where('id', $id_bank_transfer);
            return $this->db->get()->row_array();
        }
        return $this->db->get()->result_array();
    }

    public function getBankTransferStage($id_bank_transfer) {
        $this->db->from($this->table_stage);
        $this->db->where('id_bank_transfer', $id_bank_transfer);
        return $this->db->get()->result_array();
    }

    public function getPernrAbkrsStagesByIDStages($ids_bank_transfer_stage) {
//        echo $ids_bank_transfer;exit;
        $this->db->from($this->table_stage_emp);
        $this->db->where_in('id_bank_transfer_stage', $ids_bank_transfer_stage);
        $this->db->select('PERNR,ABKRS,id_bank_transfer,id_bank_transfer_stage');
        $this->db->group_by('PERNR,ABKRS,id_bank_transfer,id_bank_transfer_stage');
        return $this->db->get()->result_array();
    }

    public function getStageUndocumented() {
        $sQuery = "SELECT bts.id,concat(bt.name,'-',bts.name) as text FROM (SELECT id,name,id_bank_transfer FROM $this->table_stage ) bts "
                . "JOIN $this->table bt ON bts.id_bank_transfer=bt.id";
        return $this->db->query($sQuery)->result_array();
    }

    public function getSummaryBankTransferOther($id_bank_transfer) {
        $this->db->from($this->table_stage_other);
        $this->db->where_in('id_bank_transfer', $id_bank_transfer);
        $this->db->order_by("ABKRS,name");
        $aSum = $this->db->get();
        if (empty($aSum) || $aSum->num_rows() == 0) {
            return null;
        }
        $aRet = array();
        $temp = $aSum->result_array();
        foreach ($temp as $row) {
            $aRet[$row['ABKRS']][$row['name']] = $row['WAMNT'];
        }
        return $aRet;
    }

    public function getDisplayBankTransferedEmp($id_bank_transfer) {
        $this->db->from($this->table_stage_emp);
        $this->db->where('id_bank_transfer', $id_bank_transfer);
        $this->db->select('sum(WAMNT) as sum_wamnt,ABKRS,PERNR,BANK_ORDER,BANK_NAME,BANK_PAYEE,BANK_ACCOUNT,sum(percentage_rule) as sum_percentage_rule,sum(percentage) as sum_percentage,avg(TFMNT) as avg_tfmnt');
        $this->db->group_by("ABKRS,PERNR,BANK_ORDER,BANK_NAME,BANK_PAYEE,BANK_ACCOUNT");
        $this->db->order_by("ABKRS,PERNR");
        $aSum = $this->db->get();
        if (empty($aSum) || $aSum->num_rows() == 0) {
            return null;
        }
        return $aSum->result_array();
    }

    public function getIDBankTransferFromDocTransferEmp($id_document_transfer, $pernr) {
        $this->db->from($this->table_stage_emp);
        $this->db->where('id_document_transfer', $id_document_transfer);
        $this->db->where('PERNR', $pernr);
        $this->db->select('id_bank_transfer');
        $temp = $this->db->get();
        if (empty($temp) || $temp->num_rows() == 0) {
            return null;
        }
        return $temp->row_array()['id_bank_transfer'];
    }

    public function getSummaryBankTransferEmp($id_bank_transfer) {
        $this->db->from($this->table_stage_emp);
        $this->db->where('id_bank_transfer', $id_bank_transfer);
        $this->db->select('sum(WAMNT) as sum_wamnt,ABKRS,PERNR');
        $this->db->group_by("ABKRS,PERNR");
        $this->db->order_by("ABKRS,PERNR");
        $aSum = $this->db->get();
        if (empty($aSum) || $aSum->num_rows() == 0) {
            return null;
        }
        $aRet = array();
        $temp = $aSum->result_array();
        foreach ($temp as $row) {
            $aRet[$row['ABKRS']][$row['PERNR']] = $row['sum_wamnt'];
        }
        return $aRet;
    }

    public function getBankTransferEmp($id_bank_transfer, $id_bank_transfer_stage) {
        $this->db->from($this->table_stage_emp);
        $this->db->where('id_bank_transfer', $id_bank_transfer);
        $this->db->where('id_bank_transfer_stage', $id_bank_transfer_stage);
        $this->db->select('WAMNT,ABKRS,PERNR');
        $this->db->order_by("ABKRS,PERNR");
        $aSum = $this->db->get();
        if (empty($aSum) || $aSum->num_rows() == 0) {
            return null;
        }
        $aRet = array();
        $temp = $aSum->result_array();
        foreach ($temp as $row) {
            $aRet[$row['ABKRS']][$row['PERNR']] = $row['WAMNT'];
        }
        return $aRet;
    }

    public function getEmpTransfer($IDBTS) {
        $aIDBTS = null;
        if ($IDBTS) {
            $aIDBTS = explode(",", $IDBTS);
        }
        $this->db->from($this->table_stage_emp);
        $this->db->select('BANK_NAME,BANK_ID,BANK_PAYEE,BANK_ACCOUNT,SUM(WAMNT) SUM_WAMNT');
        $this->db->where_in('id_bank_transfer_stage', $aIDBTS);
        $this->db->group_by("BANK_NAME,BANK_ID,BANK_PAYEE,BANK_ACCOUNT");
        $this->db->order_by("BANK_ID,BANK_NAME,BANK_PAYEE");
        return $this->db->get()->result_array();
    }

    public function updateEmpForDocContent($BANK_ID, $BANK_PAYEE, $BANK_ACCOUNT, $BANK_NAME, $aIDBTS, $id_document_transfer, $id_document_transfer_content) {
        $this->db->where_in('id_bank_transfer_stage', $aIDBTS);
        $this->db->where('BANK_ID', $BANK_ID);
//        $this->db->where('BANK_PAYEE',$BANK_PAYEE);
        $this->db->where('BANK_ACCOUNT', $BANK_ACCOUNT);
//        $this->db->where('BANK_NAME',$BANK_NAME);
        $a = array('id_document_transfer' => $id_document_transfer, 'id_document_transfer_content', $id_document_transfer_content);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update($this->table_stage_emp, $a);
    }

    public function updateStageForDoc($aIDBTS, $id_document_transfer) {
        $this->db->where_in('id', $aIDBTS);
        $a = array('id_document_transfer' => $id_document_transfer);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update($this->table_stage, $a);
    }

    public function getStagesTextDocTransfer($id_document_transfer) {
        $sQuery = "SELECT concat(bt.name,'-',bts.name) as text FROM (SELECT id_document_transfer,id,name,id_bank_transfer "
                . "FROM $this->table_stage WHERE id_document_transfer=$id_document_transfer) bts "
                . "JOIN $this->table bt ON bts.id_bank_transfer=bt.id";
        $temps = $this->db->query($sQuery)->result_array();
        $sRet = "";
        foreach ($temps as $row) {
            if (!empty($sRet)) {
                $sRet .= "| ";
            }
            $sRet .= $row['text'];
        }
        return $sRet;
    }

    public function insertBankTransferForInOut($name, $begda) {
        $obegda = DateTime::createFromFormat("Y-m-d", $begda);
        $input = array();
        $input['periode_regular'] = $obegda->format("Y-m");
        $input['name'] = "INOUT_" . $name;
        $this->db->insert($this->table, $input);
        return $this->db->insert_id();
    }

    public function insertBankTransferStageForInOut($id_bank_transfer) {
        $input = array();
        $input['id_bank_transfer'] = $id_bank_transfer;
        $input['name'] = "STAGE_100%_KOREKSI";
        $input['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table_stage, $input);
        return $this->db->insert_id();
    }

    public function insertBankTransferForOffCycle($name, $begda, $is_offcycle, $date_offcycle) {
        $obegda = DateTime::createFromFormat("Y-m-d", $begda);
        $input = array();
        $input['is_offcycle'] = $is_offcycle;
        $input['date_offcycle'] = $date_offcycle;
        $input['name'] = "OFFCYCLE_" . $name;
        $this->db->insert($this->table, $input);
        return $this->db->insert_id();
    }

    public function insertBankTransferStageForOffCycle($id_bank_transfer, $percentage) {
        $input = array();
        $input['id_bank_transfer'] = $id_bank_transfer;
        $input['name'] = "STAGE_" . $percentage . "%_OFFCYCLE";
        $input['percentage_emp'] = $percentage;
        $input['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table_stage, $input);
        return $this->db->insert_id();
    }

    public function get_bank_transfer_by_pernr_bank_transfer($id_bank_transfer, $pernr, $abkrs=null) {
        $this->db->from($this->table_stage_emp);
        $this->db->where('id_bank_transfer', $id_bank_transfer);
        $this->db->where('PERNR', $pernr);
        if(!empty($abkrs)){
            $this->db->where('ABKRS', $abkrs);
        }
        $temp = $this->db->get();
        if (empty($temp)) {
            return null;
        }
        return $temp->result_array();
    }

    public function getEmpTransferWithABKRS($IDBTS) {
        $aIDBTS = null;
        if ($IDBTS) {
            $aIDBTS = explode(",", $IDBTS);
        }
        $this->db->from($this->table_stage_emp);
        $this->db->select("ABKRS,PERNR,BANK_NAME,BANK_ID,CONCAT(BANK_ID ,'_' , BANK_ACCOUNT ) AS MAPID,BANK_PAYEE,BANK_ACCOUNT,SUM(WAMNT) SUM_WAMNT", FALSE);
        $this->db->where_in('id_bank_transfer_stage', $aIDBTS);
        $this->db->group_by("ABKRS,PERNR,BANK_NAME,BANK_ID,BANK_PAYEE,BANK_ACCOUNT,MAPID");
        $this->db->order_by("BANK_ID,BANK_NAME,BANK_PAYEE");
        $obj = $this->db->get();
//        echo $this->db->last_query();exit;
        return $obj->result_array();
    }

}
