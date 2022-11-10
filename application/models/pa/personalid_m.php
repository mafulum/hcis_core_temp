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
class personalid_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
//    
    function get_nopeg_data($sNopeg){
        $sQuery = "SELECT emp_personalid_id,BEGDA,ENDDA,STEXT,ICNUM,IDESC,NOTE FROM (SELECT * FROM tm_emp_personalid WHERE PERNR='" . $sNopeg . "') pid "
                . "JOIN (select SHORT,STEXT from tm_master_abbrev where SUBTY='20' AND SHORT<>'0') abbrv ON pid.SUBTY=abbrv.SHORT ORDER BY pid.SUBTY,pid.BEGDA";
        $oRes = $this->db->query($sQuery);
        if($oRes->num_rows()==0){
            return null;
        }
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
//    
//    function get_master_bank(){
//        $sQuery = "SELECT bank_mid,BANK_NAME FROM tm_master_bank WHERE CURDATE() BETWEEN BEGDA AND ENDDA";
//        $oRes = $this->db->query($sQuery);
//        $aRes = $oRes->result_array();
//        $oRes->free_result();
//        return $aRes;
//    }
//    
    function form_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['subty'] = $this->common->get_abbrev(20);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personalid_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#form_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        subty: "required",
                        icnum: "required",
                        idesc: "required",
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        subty: "Please enter Type",
                        icnum: "Please enter ID Number",
                        idesc: "Please enter Name Reff",
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        subty = $("#subty").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_emp_personalid", { "pernr": pernr,"begda": begda, "endda": endda,"subty":subty },function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#form_fr_new")[0].submit();
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
    
    function page_view($iSeq,$sNopeg){
        $data['frm'] = $this->get_row($iSeq, $sNopeg);
        $data['subty'] = $this->common->get_abbrev(20);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personalid_view';
        return $data;
    }  
    
    function form_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_row($iSeq, $sNopeg);
        $data['subty'] = $this->common->get_abbrev(20);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personalid_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#form_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                subty: "required",
                icnum: "required",
                idesc: "required",
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                subty: "Please enter Type",
                icnum: "Please enter ID Number",
                idesc: "Please enter Name Reff",
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        subty = $("#subty").val();
                        id= $("#id").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_emp_personalid", { "pernr": pernr,"begda": begda, "endda": endda,"subty":subty,"id":id},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }
                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#form_fr_update")[0].submit();
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
//
    function ov($sNopeg) {
        $data['ov'] = $this->get_nopeg_data($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personalid_ov';
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
    
    function check_time_constraint($pernr,$begda,$endda,$subty,$type="INSERT",$id=0){
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_personalid where PERNR='$pernr' AND SUBTY='$subty' AND((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            $sQuery="SELECT * from tm_emp_personalid where PERNR='$pernr' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_personalid_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_personalid where PERNR='$pernr' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_personalid_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }
//
    function personalid_upd($id, $sNopeg, $a) {
        $this->db->where('emp_personalid_id', $id);
        $this->db->where('PERNR', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_personalid', $a);
    }
//
    function personalid_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_personalid', $a);
    }
//
    function personalid_del($id, $sNopeg) {
        $this->db->where('emp_personalid_id', $id);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_personalid');
        $this->global_m->insert_log_delete('tm_emp_personalid',array('emp_personalid_id'=> $id,'PERNR'=>$sNopeg));
    }
//    
    function get_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_personalid where pernr='" . $sNopeg . "' AND emp_personalid_id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
}

?>