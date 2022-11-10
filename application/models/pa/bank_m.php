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
class bank_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
    
    function get_a_bank_data($sNopeg){
        $sQuery = "SELECT id_emp_bank,mbank.bank_mid,ebank.BEGDA,ebank.ENDDA,BANK_NAME,BANK_ACCOUNT,BANK_PAYEE,BANK_CURR,BANK_NOTE,BANK_ORDER FROM (SELECT * FROM tm_emp_bank WHERE PERNR='" . $sNopeg . "') ebank JOIN tm_master_bank  mbank ON ebank.BANK_MID=mbank.bank_mid";
//        echo $sQuery;exit;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function get_kode_bic_cms_mandiri($id){
        $sQuery = "SELECT kode_bic_cms_mandiri FROM tm_master_bank WHERE CURDATE() BETWEEN BEGDA AND ENDDA AND bank_mid=".$id;
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['kode_bic_cms_mandiri'];
    }
    function get_kode_oy($id){
        $sQuery = "SELECT kode_oy FROM tm_master_bank WHERE CURDATE() BETWEEN BEGDA AND ENDDA AND bank_mid=".$id;
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['kode_oy'];
//        return str_pad($aRow['kode_oy'],3,"0",STR_PAD_LEFT);
    }
    
    function get_master_bank(){
        $sQuery = "SELECT bank_mid,BANK_NAME FROM tm_master_bank WHERE CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    function personal_data_bank_fr_view($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_bank_row($iSeq, $sNopeg);
        $data['mbank'] = $this->get_master_bank();
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personal_data_bank_view';
        return $data;
    }
    function personal_data_bank_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['mbank'] = $this->get_master_bank();
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personal_data_bank_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#pd_bank_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        cname: "required",
                        fBank: "required",
                        cCurr: "required",
                        cNote: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        cname: "Please enter Name",
                        cCurr: "Please enter Currency",
                        cNote: "Please enter Note"
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_personal_data_bank", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_bank_fr_new")[0].submit();
                                }
                            });
                        });
                    },
                });
                $("#btnYes").click( function(){
                    modalAnswer="1";
                    $("#confirm-insert").modal("hide");
                });
                $("#btnNo").click( function(){
                    modalAnswer="2";
                    $("#confirm-insert").modal("hide");
                });
            });
            </script>
            ';
        return $data;
    }
    
    function personal_data_bank_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_bank_row($iSeq, $sNopeg);
        $data['mbank'] = $this->get_master_bank();
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personal_data_bank_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_bank_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                cname: "required",
                fBank: "required",
                cCurr: "required",
                cNote: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                cname: "Please enter Name",
                cCurr: "Please enter Currency",
                cNote: "Please enter Note"
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_emp_bank= $("#id_emp_bank").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_personal_data_bank", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_emp_bankX":id_emp_bank},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }
                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_bank_fr_update")[0].submit();
                                }
                            });
                        });
            },
        });
        $("#btnYes").click( function(){
            modalAnswer="1";
            $("#confirm-update").modal("hide");
        });
        $("#btnNo").click( function(){
            modalAnswer="2";
            $("#confirm-update").modal("hide");
        });
});
</script>
';
        return $data;
        
    }

    function personal_data_bank_ov($sNopeg) {
        $data['ov'] = $this->get_a_bank_data($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/bank_ov';
        $data['scriptJS'] .='
        <script>
        var modalAnswer="0";
        function confirm_delete(href){
            $("#confirm-delete").modal("show").on("hidden.bs.modal", function (e) {
                if(modalAnswer=="1"){                
                    window.location=href;
                }
            });
        }
        jQuery(document).ready(function() {
            $("#btnYes").click( function(){
                modalAnswer="1";
                $("#confirm-delete").modal("hide");
            });
            $("#btnNo").click( function(){
                modalAnswer="2";
                $("#confirm-delete").modal("hide");
            });
        });
        </script>
        ';
        return $data;
    }
    
    function check_time_constraint_personal_data_bank($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_bank where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else if($nRows==1){
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begda ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", do you want overwrite ?";
            }else{
                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
            }
        }else if($type=="UPDATE"){
            $sQuery="SELECT * from tm_emp_bank where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_bank<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_bank where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_bank<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function personal_data_bank_upd($id_emp_bank, $sNopeg, $a) {
        $this->db->where('id_emp_bank', $id_emp_bank);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_bank', $a);
    }

    function personal_data_bank_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_bank', $a);
    }

    function personal_data_bank_del($id_emp_bank, $sNopeg) {
        $this->db->where('id_emp_bank', $id_emp_bank);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_bank');
        $this->global_m->insert_log_delete('tm_emp_bank',array('PERNR'=> $sNopeg,'id_emp_bank'=>$id_emp_bank));
    }
    
    function get_tm_emp_bank_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_bank where pernr='" . $sNopeg . "' AND id_emp_bank='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
    
    function get_emp_bank_by_emp_date($aemp,$evtda){
//        $sQuery = "SELECT id_emp_bank,mbank.bank_mid,ebank.BEGDA,ebank.ENDDA,BANK_NAME,BANK_ACCOUNT,BANK_PAYEE,BANK_CURR,BANK_NOTE,BANK_ORDER "
//                . "FROM (SELECT * FROM tm_emp_bank WHERE PERNR in (" . implode("','", $aemp). ") AND BEGDA<='".$evtda."' AND ENDDA>='".$evtda."' AND WAMNT IS NULL AND WAPCT IS NULL ) ebank "
//                . "JOIN (SELECT * FROM tm_master_bank WHERE BEGDA<='".$evtda."' AND ENDDA>='".$evtda."' )  mbank ON ebank.BANK_MID=mbank.bank_mid";
////        echo $sQuery;exit;
//        $oRes = $this->db->query($sQuery);
//        $aRes = $oRes->result_array();
//        $oRes->free_result();
//        return $aRes;
        $this->db->from('tm_emp_bank');
        $this->db->join('tm_master_bank', 'tm_emp_bank.BANK_MID = tm_master_bank.bank_mid', 'left');
        $this->db->where("tm_emp_bank.BEGDA <= '" . $evtda . "'");
        $this->db->where("tm_emp_bank.ENDDA >= '" . $evtda . "'");
        $this->db->where_in('tm_emp_bank.PERNR', $aemp);
        $this->db->where("tm_emp_bank.WAMNT IS NULL");
        $this->db->where("tm_emp_bank.WAPCT IS NULL");
        $temp = $this->db->get();
        if (empty($temp)) {
            return null;
        }
        return $temp->result_array();
    }
}

?>