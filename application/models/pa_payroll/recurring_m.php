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
class recurring_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
    
    function get_a_recurring($sNopeg){
        $sQuery = "SELECT recur.*,mscale.LGTXT from (SELECT * FROM tm_emp_recuradddeduc WHERE PERNR='" . $sNopeg . "' ) recur ".
                "LEFT JOIN (select BEGDA,ENDDA,WGTYP,LGTXT from tm_master_payscale ) mscale ON recur.WGTYP=mscale.WGTYP ".
                "AND mscale.BEGDA<=recur.BEGDA AND mscale.ENDDA>=recur.BEGDA ".
                "ORDER BY mscale.LGTXT ASC,recur.BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function get_master_payscale(){
        $sQuery = "select WGTYP,LGTXT from tm_master_payscale where (PRTYP in('-','+') AND (WGTYP NOT like '1%' AND WGTYP NOT like '6%')) OR WGTYP in('313E','310E') ORDER BY WGTYP";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function personal_recurring_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['wgtyps'] = $this->get_master_payscale();
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/recurring_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#wgtyp").select2();
                $("#pd_recurring_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        wgtyp: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        wgtyp: "Please enter Insurance Company"
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        wgtyp = $("#wgtyp").val();
                        $.post( "'.base_url().'index.php/memp_payroll/insert_check_time_constraint_personal_recurring", { "pernrX": pernr,"begdaX": begda, "enddaX": endda,"wgtypX": wgtyp},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_recurring_fr_new")[0].submit();
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
        $data['frm'] = $this->get_tm_emp_recuradddeduc_row($iSeq, $sNopeg);
        $data['wgtyps'] = $this->get_master_payscale();
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/recurring_view';
        return $data;
    }
    
    function personal_recurring_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_recuradddeduc_row($iSeq, $sNopeg);
        $data['wgtyps'] = $this->get_master_payscale();
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/recurring_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#wgtyp").select2();
    $("#pd_recurring_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                wgtyp: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                wgtyp: "Please enter Wage Type"
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        wgtyp = $("#wgtyp").val();
                        endda = $("#endda").val();
                        emp_recuradddeduc_id = $("#emp_recuradddeduc_id").val();
                        $.post( "'.base_url().'index.php/memp_payroll/update_check_time_constraint_personal_recurring", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"emp_recuradddeduc_idX":emp_recuradddeduc_id},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_recurring_fr_update")[0].submit();
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

    function personal_recurring_ov($sNopeg) {
        $data['ov'] = $this->get_a_recurring($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/recurring_ov';
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
    
    function check_time_constraint_recurring($pernr,$begda,$endda,$wgtyp,$type="INSERT",$id=0){
        $sAdd="";
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_recuradddeduc where PERNR='$pernr' AND WGTYP='$wgtyp' ".$sAdd." AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            $sQuery="SELECT * from tm_emp_recuradddeduc where PERNR='$pernr' AND WGTYP='$wgtyp' ".$sAdd." AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_recuradddeduc_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_recuradddeduc where PERNR='$pernr' AND WGTYP='$wgtyp' ".$sAdd." AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_recuradddeduc_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function personal_recurring_upd($id, $sNopeg, $a) {
        $this->db->where('emp_recuradddeduc_id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_recuradddeduc', $a);
    }

    function personal_recurring_new($a) {
        unset($a['CNAME']);
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_recuradddeduc', $a);
        return $this->db->insert_id();
    }

    function personal_recurring_del($id, $sNopeg) {
        $this->db->where('emp_recuradddeduc_id', $id);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_recuradddeduc');
        $this->global_m->insert_log_delete('tm_emp_recuradddeduc',array('PERNR'=> $sNopeg,'emp_recuradddeduc_id'=>$id));
    }
    
    function get_tm_emp_recuradddeduc_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_recuradddeduc where pernr='" . $sNopeg . "' AND emp_recuradddeduc_id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
    
    function reff_upload_page(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PY_RECURRING';
        return $data;
    }
}

?>