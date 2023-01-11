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
class emp_quota_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
    
    function get_a_quota_data($sNopeg){
        $sQuery = "SELECT quota.*, abbrv.SUBTY,abbrv.STEXT FROM "
                . "(SELECT * FROM tm_emp_quota WHERE PERNR='" . $sNopeg . "') quota "
                . "JOIN (SELECT * FROM tm_master_abbrev WHERE SUBTY='30') abbrv ON quota.SUBTY=abbrv.SHORT";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function emp_quota_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['subty'] = $this->common->get_abbrev(30);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/quota_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#pd_quota_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        subty: "required",
                        quota: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        subty: "Please enter Quota Type",
                        quota: "Please enter Quota"
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        subty = $("#subty").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_emp_quota", { "pernrX": pernr,"begdaX": begda, "enddaX": endda,"subtyX":subty },function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_quota_fr_new")[0].submit();
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
    
    function view($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_quota_row($iSeq, $sNopeg);
        $data['mot'] = $this->common->get_abbrev(30);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/quota_view';
        return $data;
    }
    
    function emp_monitoring_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_quota_row($iSeq, $sNopeg);
        $data['subty'] = $this->common->get_abbrev(30);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/quota_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_quota_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                subty: "required",
                quota: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                subty: "Please enter Quota Type",
                quota: "Please enter Quota"
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        subty = $("#subty").val();
                        quota = $("#quota").val();
                        id= $("#id").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_emp_quota", { "pernrX": pernr,"begdaX": begda, "enddaX": endda,"subtyX":subty ,"id":id},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }
                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_quota_fr_update")[0].submit();
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

    function quota_ov($sNopeg) {
        $data['ov'] = $this->get_a_quota_data($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/quota_ov';
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
    
    function check_time_constraint_emp_quota($pernr,$begda,$endda,$subty,$type="INSERT",$id=0){
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_quota where PERNR='$pernr' AND SUBTY='$subty' AND((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            $sQuery="SELECT * from tm_emp_quota where PERNR='$pernr' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_quota where PERNR='$pernr' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_bank<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function emp_quota_upd($id, $sNopeg, $a) {
        $this->db->where('id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_quota', $a);
    }

    function emp_quota_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_quota', $a);
    }

    function emp_quota_del($id, $sNopeg) {
        $this->db->where('id', $id);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_quota');
        $this->global_m->insert_log_delete('tm_emp_quota',array('PERNR'=> $sNopeg,'id'=>$id));
    }
    
    function get_tm_emp_quota_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_quota where pernr='" . $sNopeg . "' AND id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
    
    function reff_upload_page(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PA_QUOTA';
        return $data;
    }
    
    // function getReminderByDate($date){
    //     $sQuery = "SELECT * FROM tm_emp_quota where REMINDER_DATE='" . $date . "'";
    //     $oRes = $this->db->query($sQuery);
    //     $aRow = $oRes->result_array();
    //     $oRes->free_result();
    //     return $aRow;
        
    // }
}

?>