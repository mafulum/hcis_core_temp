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
class insurance_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
    
    function get_a_insurance($sNopeg){
        $sQuery = "SELECT ins.*,insty.* from (SELECT * FROM tm_emp_insurance WHERE PERNR='" . $sNopeg . "' ) ins ".
                "LEFT JOIN (select short,stext from tm_master_abbrev where SUBTY='25' AND SHORT NOT IN('0')) insty ON ins.INSTY=insty.SHORT ".
                "ORDER BY ins.INSTY ASC,ins.BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function personal_insurance_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['insty'] = $this->global_m->get_abbrev(25);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/insurance_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#pd_insurance_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        insty: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        insty: "Please enter Insurance Company"
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        insty = $("#insty").val();
                        $.post( "'.base_url().'index.php/memp_payroll/insert_check_time_constraint_personal_insurance", { "pernrX": pernr,"begdaX": begda, "enddaX": endda,"instyX": insty},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_insurance_fr_new")[0].submit();
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
        $data['frm'] = $this->get_tm_emp_insurance_row($iSeq, $sNopeg);
        $data['insty'] = $this->global_m->get_abbrev(25);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/insurance_view';
        return $data;
    }
    
    function personal_insurance_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_insurance_row($iSeq, $sNopeg);
        $data['insty'] = $this->global_m->get_abbrev(25);
//        var_dump($data['afam']);exit;
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/insurance_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_insurance_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                insty: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                insty: "Please enter No Asuransi"
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/memp_payroll/update_check_time_constraint_personal_insurance", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"emp_insurance_idX":emp_insurance_id},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_insurance_fr_update")[0].submit();
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

    function personal_insurance_ov($sNopeg) {
        $data['ov'] = $this->get_a_insurance($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/insurance_ov';
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
    
    function check_time_constraint_insurance($pernr,$begda,$endda,$insty,$type="INSERT",$id=0){
        $sAdd="";
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_insurance where PERNR='$pernr' AND INSTY='$insty' ".$sAdd." AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            $sQuery="SELECT * from tm_emp_insurance where PERNR='$pernr' AND INSTY='$insty' ".$sAdd." AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_insurance_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_insurance where PERNR='$pernr' AND INSTY='$insty' ".$sAdd." AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_insurance_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function personal_insurance_upd($id, $sNopeg, $a) {
        foreach($a as $k=>$v){
            if(empty($v)){
                unset($a[$k]);
            }
        }
        $this->db->where('emp_insurance_id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_insurance', $a);
    }

    function personal_insurance_new($a) {
        foreach($a as $k=>$v){
            if(empty($v)){
                unset($a[$k]);
            }
        }
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_insurance', $a);
    }

    function personal_insurance_del($id, $sNopeg) {
        $this->db->where('emp_insurance_id', $id);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_insurance');
        $this->global_m->insert_log_delete('tm_emp_insurance',array('PERNR'=> $sNopeg,'emp_insurance_id'=>$id));
    }
    
    function get_tm_emp_insurance_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_insurance where pernr='" . $sNopeg . "' AND emp_insurance_id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
    
    function reff_upload_page(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'upload/PY_INSURANCE';
        return $data;
    }
}

?>