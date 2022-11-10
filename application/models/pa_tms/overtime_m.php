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
class overtime_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
    
    function get_a_overtime($sNopeg){
        $sQuery = "SELECT * FROM tm_emp_overtime WHERE PERNR='" . $sNopeg . "' ORDER BY PRDPY ASC,BEGTI ASC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function personal_overtime_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['fovertime'] = $this->global_m->get_abbrev(28);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_tms/overtime_fr_new';
        $data['externalCSS'] .='<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-datetimepicker/css/datetimepicker.css" />';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
            $(".form_datetime").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                autoclose: true,
                todayBtn: true,
                pickerPosition: "bottom-left"

            });
                $("#pd_overtime_fr_new").validate({
                    rules: {
                        begti: "required",
                        endti: "required",
                        ihday: "required",
                        prdpy: "required",
                    },
                    messages: {
                        begti: "Please enter Begin Time",
                        endti: "Please enter End Time",
                        prdpy: "Please enter Periode Payroll",
                        ihday: "Please enter is WorkDay"
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begti").val();
                        endda = $("#endti").val();
                        $.post( "'.base_url().'index.php/memp_tms/insert_check_time_constraint_personal_overtime", { "pernrX": pernr,"begtiX": begda, "endtiX": endda},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_overtime_fr_new")[0].submit();
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
        $data['frm'] = $this->get_tm_emp_overtime_row($iSeq, $sNopeg);
        $data['fovertime'] = $this->global_m->get_abbrev(28);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_tms/overtime_view';
        return $data;
    }
    
    function personal_overtime_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_overtime_row($iSeq, $sNopeg);
        $data['fovertime'] = $this->global_m->get_abbrev(28);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_tms/overtime_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_overtime_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date"
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begti").val();
                        endda = $("#endti").val();
                        id_emp_overtime= $("#id").val();
                        $.post( "'.base_url().'index.php/memp_tms/update_check_time_constraint_personal_overtime", { "pernrX": pernr,"begtiX": begda, "endtiX": endda, "id_emp_overtimeX":id_emp_overtime},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }
                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_overtime_fr_update")[0].submit();
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

    function personal_overtime_ov($sNopeg) {
        $data['ov'] = $this->get_a_overtime($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_tms/overtime_ov';
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
    
    function check_time_constraint_overtime($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_overtime where PERNR='$pernr' AND ((BEGTI<='$begda' AND ENDTI>='$begda') OR (BEGTI<='$endda' AND ENDTI>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else if($nRows==1){
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begin time ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", do you want overwrite ?";
            }else{
                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
            }
        }else if($type=="UPDATE"){
            $sQuery="SELECT * from tm_emp_overtime where PERNR='$pernr' AND ((BEGTI<='$begda' AND ENDTI>='$begda')  OR (BEGTI<='$endda' AND ENDTI>='$endda')) AND id<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_overtime where PERNR='$pernr' AND ((BEGTI<='$begda' AND ENDTI>='$begda') OR (BEGTI<='$endda' AND ENDTI>='$endda')) AND id<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function personal_overtime_upd($id, $sNopeg, $a) {
        foreach($a as $k=>$v){
            if(empty($v)){
                unset($a[$k]);
            }
        }
        $this->db->where('id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_overtime', $a);
    }

    function personal_overtime_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_overtime', $a);
    }

    function personal_overtime_del($id, $sNopeg) {
        $this->db->where('id', $id);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_overtime');
        $this->global_m->insert_log_delete('tm_emp_overtime',array('PERNR'=> $sNopeg,'id'=>$id));
    }
    
    function get_tm_emp_overtime_row($id, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_overtime where pernr='" . $sNopeg . "' AND id='" . $id . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
}

?>