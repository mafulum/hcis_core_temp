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
class npwp_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
    
    function get_a_npwp($sNopeg){
        $sQuery = "SELECT enpwp.*,stext from (SELECT * FROM tm_emp_tax WHERE PERNR='" . $sNopeg . "') enpwp 
        JOIN (select short,stext from tm_master_abbrev where SHORT<>'0' and SUBTY='21') ptkp ON enpwp.DEPND=ptkp.short";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function personal_npwp_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['fptkp'] = $this->global_m->get_abbrev(21);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/npwp_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#pd_npwp_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        taxid: "required",
                        rdate: "required",
                        depnd: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        taxid: "Please enter No NPWP",
                        rdate: "Please enter Registration Date",
                        depnd: "Please enter Type of Dependent"
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/memp_payroll/insert_check_time_constraint_personal_npwp", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_npwp_fr_new")[0].submit();
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
        $data['frm'] = $this->get_tm_emp_tax_row($iSeq, $sNopeg);
        $data['fptkp'] = $this->global_m->get_abbrev(21);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/npwp_view';
        return $data;
    }
    
    function personal_npwp_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_tax_row($iSeq, $sNopeg);
        $data['fptkp'] = $this->global_m->get_abbrev(21);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/npwp_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_npwp_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                taxid: "required",
                rdate: "required",
                depnd: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                taxid: "Please enter No NPWP",
                rdate: "Please enter Registration Date",
                depnd: "Please enter Type of Dependent"
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_emp_tax= $("#id_emp_tax").val();
                        $.post( "'.base_url().'index.php/memp_payroll/update_check_time_constraint_personal_npwp", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_emp_taxX":id_emp_tax},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }
                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_npwp_fr_update")[0].submit();
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

    function personal_npwp_ov($sNopeg) {
        $data['ov'] = $this->get_a_npwp($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/npwp_ov';
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
    
    function check_time_constraint_npwp($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_tax where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            $sQuery="SELECT * from tm_emp_tax where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_tax<>'$id'";
//            echo $sQuery;exit;
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_tax where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_tax<>'$id'";
//            echo $sQuery;exit;
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function personal_npwp_upd($id_emp_bank, $sNopeg, $a) {
        $this->db->where('id_emp_tax', $id_emp_bank);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_tax', $a);
    }

    function personal_npwp_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_tax', $a);
    }

    function personal_npwp_del($id_emp_tax, $sNopeg) {
        $this->db->where('id_emp_tax', $id_emp_tax);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_tax');
        $this->global_m->insert_log_delete('tm_emp_tax',array('PERNR'=> $sNopeg,'id_emp_tax'=>$id_emp_tax));
    }
    
    function get_tm_emp_tax_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_tax where pernr='" . $sNopeg . "' AND id_emp_tax='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
}

?>