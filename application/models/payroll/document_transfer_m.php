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
class document_transfer_m extends CI_Model {

    var $table = 'tm_document_transfer';
    var $table_bank= "tm_document_transfer_bank";
    var $table_bank_content= "tm_document_transfer_bank_content";
    var $table_host_bank= "tm_host_bank";

    public function __construct() {
        parent::__construct();
        $this->load->model('payroll/bank_transfer_m');
    }
    
    public function getDocumentTransfer($id_document_transfer=null){
        $this->db->from($this->table);
        if(!empty($id_document_transfer)){
            $this->db->where('id', $id_document_transfer);
            return $this->db->get()->row_array();
        }
        return $this->db->get()->result_array();
    }
    
    public function getHostBank($date){
        $this->db->from($this->table_host_bank);
        $this->db->where('BEGDA <=',$date);
        $this->db->where('ENDDA >= ',$date);
        return $this->db->get()->result_array();
    }
    public function getHostBankByBankID($bank_id,$date){
        $this->db->from($this->table_host_bank);
        $this->db->where('BEGDA <=',$date);
        $this->db->where('ENDDA >= ',$date);
        $this->db->where('id_bank',$bank_id);
        return $this->db->get()->row_array();
        
    }
    public function confirm_to_db($emp_transfer,$name,$bts_codes){
        if (empty($emp_transfer)) {
            return null;
        }
        $aIDBTS= null;
        if ($bts_codes) {
            $aIDBTS = explode(",", $bts_codes);
        }
        $input=array();
        $input['name']=$name;
        $input['id_bts_codes']=$bts_codes;
        $input['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table,$input);
        $id_document_transfer = $this->db->insert_id();
        $this->bank_transfer_m->updateStageForDoc($aIDBTS,$id_document_transfer);
        $docTransfer = $this->getDocumentTransfer($id_document_transfer);
        $hostBanks = $this->getHostBank($docTransfer['created_at']);
        $aIDHostbank=array();
        foreach($hostBanks as $row){
            $aIDHostbank[$row['id_bank']]=$row;
            if($row['is_other']==1){
                $aIDHostbank['others']=$row;
            }
        }
        $aDocBankContent=array();
        foreach($emp_transfer as $row){
            $input=array();
            $id_host_bank=0;
            $input['id_document_transfer']=$id_document_transfer;
            $input['BANK_ID']=$row['BANK_ID'];
            $input['BANK_NAME']=$row['BANK_NAME'];
            $input['BANK_PAYEE']=$row['BANK_PAYEE'];
            $input['BANK_ACCOUNT']=$row['BANK_ACCOUNT'];
            $input['WAMNT']=$row['SUM_WAMNT'];
            if(!empty($aIDHostbank[$row['BANK_ID']])){
                $id_host_bank=$aIDHostbank[$row['BANK_ID']]['id'];
                $aDocBankContent[$row['BANK_ID']][]=$input;
            }else{
                $id_host_bank=$aIDHostbank['others']['id'];
                $aDocBankContent['others'][]=$input;
            }
            $input['id_host_bank']=$id_host_bank;
            $input['created_by'] = $this->session->userdata('username');
            $this->db->insert($this->table_bank_content,$input);
            $id_document_transfer_content = $this->db->insert_id();
            $this->bank_transfer_m->updateEmpForDocContent($row['BANK_ID'],$row['BANK_PAYEE'],$row['BANK_ACCOUNT'],$row['BANK_NAME'],$aIDBTS,$id_document_transfer,$id_document_transfer_content);
        }
        $sStages = $this->bank_transfer_m->getStagesTextDocTransfer($id_document_transfer);
        return array('aIDHostbank'=>$aIDHostbank,'aDocBankContent'=>$aDocBankContent,'dt'=>$docTransfer,'stages'=>$sStages);
    }
    public function getContent($id_document_transfer,$id_host_bank,$bank_id=null){
        $this->db->from($this->table_bank_content);
        $this->db->where('id_document_transfer',$id_document_transfer);
        $this->db->where('id_host_bank',$id_host_bank);
        if(!empty($bank_id)){
            $this->db->where('BANK_ID',$bank_id);
        }
        $this->db->order_by('BANK_PAYEE');
        return $this->db->get()->result_array();
    }
    public function getOthersContent($id_document_transfer,$id_host_bank,$bank_id){
        $this->db->from($this->table_bank_content);
        $this->db->where('id_document_transfer',$id_document_transfer);
        $this->db->where('id_host_bank',$id_host_bank);
        $this->db->where('BANK_ID <>',$bank_id);
        return $this->db->get()->result_array();
    }
    
    public function getDocumentTransferBank($id_document_transfer,$id_host_bank){
        $this->db->from($this->table_bank);
        $this->db->where('id_document_transfer',$id_document_transfer);
        $this->db->where('id_host_bank',$id_host_bank);
        return $this->db->get()->row_array();
    }
    
    public function getDocumentTransferBankByID($id){
        $this->db->from($this->table_bank);
        $this->db->where('id',$id);
        return $this->db->get()->row_array();
    }
    
    public function saveDocumentTransferBank($id_document_transfer,$id_host_bank){
        $input=array();
        $input['id_document_transfer']=$id_document_transfer;
        $input['id_host_bank']=$id_host_bank;
        $input['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table_bank,$input);
        return $this->db->insert_id();
    }
    
    public function updateDocumentTransferBank($id,$filename){
        $this->db->where('id',$id);
        $a = array('generated_file'=>$filename);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update($this->table_bank_content,$a);
    }
    
    public function updateDocumentCMSBank($id,$filename){
        $this->db->where('id',$id);
        $a = array('generated_cms_file'=>$filename);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update($this->table_bank_content,$a);
    }
}