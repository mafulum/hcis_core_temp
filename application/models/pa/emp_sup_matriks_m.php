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
class emp_sup_matriks_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
        $this->load->model('global_m');
    }
    
    function get_data($sNopeg){
        $sQuery = "SELECT m.*,subty.STEXT FROM "
                . "(SELECT * FROM tm_emp_sup_matriks WHERE PERNR='" . $sNopeg . "') m "
                . "JOIN (SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY='32' ) subty ON m.SUBTY=subty.SHORT ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function get_subty(){
        $sQuery = "SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY='32'";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_pic_customer($subty="",$werks=""){
        $sQuery = "SELECT id,type,WERKS,pernr,nama,email,position,unit_stext,unit_short FROM tm_pic_customer";
        if(!empty($subty) || !empty($werks)){
            $sQuery .= " WHERE deleted_at is null AND ";
            if(!empty($subty)){
                $sQuery .= "SUBTY='".$subty."' ";
            }
            if(!empty($subty) || !empty($werks)){
                $sQuery .=" AND ";
            }
            if(!empty($werks)){
                $sQuery .= "WERKS='".$werks."' ";
            }
        }
        
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        foreach($aRes as $key=>$val){
            if(empty($val['position']) && $val['WERKS']=="GDPS"){
                $emp = $this->employee_m->get_emp_mapping($val['pernr']);
                $aRes[$key]['position']=$emp['POSISI'];
                $aRes[$key]['nama']=$emp['CNAME'];
            }
        }
        return $aRes;
    }
    
    function fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['PIC_CUSTOMER'] = $this->get_pic_customer();
        $data['WERKS'] = $this->global_m->get_abbrev(5);
        $data['SUBTY'] = $this->global_m->get_abbrev(32);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/sup_matriks_fr_new';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['scriptJS'] .='
            <script>
            var pic_cust = '.json_encode($data['PIC_CUSTOMER']).';
            var modalAnswer="0";
            function reloadPicCustomer(){
                var sel_subty = $("#SUBTY").val();
                var sel_werks = $("#WERKS").val();
                $("#PERNR_MATRIKS").empty().trigger("change");
                if(pic_cust.length > 0){
                    for (let i = 0; i < pic_cust.length; i++) {
                        if(pic_cust[i]["type"]!=sel_subty || pic_cust[i]["WERKS"]!=sel_werks){
                            continue;
                        } 
                        var newOption = new Option(pic_cust[i]["pernr"]+" -"+pic_cust[i]["nama"]+" - "+pic_cust[i]["position"], pic_cust[i]["pernr"], false, false);
                        $("#PERNR_MATRIKS").append(newOption);
                    }
                    $("#PERNR_MATRIKS").trigger("change");
                } 
            }
            jQuery(document).ready(function() {
                $("#PERNR_MATRIKS").select2();
                $("#SUBTY").select2();
                $("#WERKS").select2();
                reloadPicCustomer();
                $("#SUBTY").on("change", function() {
                    reloadPicCustomer();
                });
                $("#WERKS").on("change", function() {
                    reloadPicCustomer();
                });
                $("#fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        subty: "required",
                        werks: "required",
                        pernr_matriks: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        subty: "Please enter SUBTY",
                        werks: "Please enter WERKS",
                        pernr_matriks: "Please enter PERNR MATRIKS"
                    },submitHandler: function() {
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        subty = $("#SUBTY").val();
                        werks = $("#WERKS").val();
                        pernr_matriks = $("#PERNR_MATRIKS").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_emp_sup_matriks", { "pernr": pernr,"begda": begda, "endda": endda, "subty": subty },function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#fr_new")[0].submit();
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
        $data['frm'] = $this->get_row($iSeq, $sNopeg);
        $data['fgesch'] = $this->global_m->get_abbrev(2);
        $data['osubty'] = $this->global_m->get_abbrev(14);
        $data['ffamstat'] = $this->global_m->get_abbrev(15);
        $data['fNat'] = $this->global_m->get_abbrev(16);
        $data['fCty'] = $this->global_m->get_abbrev(17);
        $data['fWN'] = $this->global_m->get_abbrev(18);
        $data['fIncben'] = $this->global_m->get_abbrev(19);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personal_data_fam_view';
        return $data;
    }
    
    function fr_update($iSeq, $sNopeg){
        $data['PIC_CUSTOMER'] = $this->get_pic_customer();
        $data['WERKS'] = $this->global_m->get_abbrev(5);
        $data['SUBTY'] = $this->global_m->get_abbrev(32);
        $data['frm'] = $this->get_row($iSeq, $sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/sup_matriks_fr_update';
        $data['scriptJS'] .='
<script>
var pic_cust = '.json_encode($data['PIC_CUSTOMER']).';
var modalAnswer="0";
function reloadPicCustomer(){
    var sel_subty = $("#SUBTY").val();
    var sel_werks = $("#WERKS").val();
    $("#PERNR_MATRIKS").empty().trigger("change");
    if(pic_cust.length > 0){
        for (let i = 0; i < pic_cust.length; i++) {
            if(pic_cust[i]["type"]!=sel_subty || pic_cust[i]["WERKS"]!=sel_werks){
                continue;
            } 
            var newOption = new Option(pic_cust[i]["nama"]+" - "+pic_cust[i]["position"], pic_cust[i]["pernr"], false, false);
            $("#PERNR_MATRIKS").append(newOption);
        }
        $("#PERNR_MATRIKS").trigger("change");
    } 
}
jQuery(document).ready(function() {
    $("#SUBTY").select2();
    $("#WERKS").select2();
    reloadPicCustomer();
    $("#PERNR_MATRIKS").val("'.$data['frm']['PERNR_MATRIKS'].'");
    $("#SUBTY").on("change", function() {
        reloadPicCustomer();
    });
    $("#WERKS").on("change", function() {
        reloadPicCustomer();
    });
    $("#fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                subty: "required",
                werks: "required",
                pernr_matriks: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                subty: "Please enter SUBTY",
                werks: "Please enter WERKS",
                pernr_matriks: "Please enter PERNR MATRIKS"
            },submitHandler: function() {
                     //check date
                pernr = $("#pernr").val();
                begda = $("#begda").val();
                endda = $("#endda").val();
                subty = $("#SUBTY").val();
                werks = $("#WERKS").val();
                pernr_matriks = $("#PERNR_MATRIKS").val();
                id= $("#id").val();
                $.post( "'.base_url().'index.php/employee/update_check_time_constraint_emp_sup_matriks", { "pernr": pernr,"begda": begda, "endda": endda, "subty": subty,"id":id},function (text){
                    if(text!="null"){
                        $("#mb").html(text);
                        $("#btnYes").hide();
                    }else{
                        $("#btnYes").show();
                    }
                    
                }).done(function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_update")[0].submit();
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

    function ov($sNopeg) {
        $data['ov'] = $this->get_data($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/sup_matriks_ov';
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
            $sQuery="SELECT * from tm_emp_sup_matriks where PERNR='$pernr' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else if($nRows==1){
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begda ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", SUBTYPE ".$subty.", do you want overwrite ?";
            }else{
                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
            }
        }else if($type=="UPDATE"){
            $sQuery="SELECT * from tm_emp_sup_matriks where PERNR='$pernr' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda')    OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_sup_matriks where PERNR='$pernr' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function db_upd($id, $sNopeg, $a) {
        $this->db->where('id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_sup_matriks', $a);
    }

    function db_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_sup_matriks', $a);
    }

    function db_del($id, $sNopeg) {
        $this->db->where('id', $id);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_sup_matriks');
        $this->global_m->insert_log_delete('tm_emp_sup_matriks',array('PERNR'=> $sNopeg,'id'=>$id));
    }
    
    function get_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_sup_matriks where pernr='" . $sNopeg . "' AND id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
    
    // function get_fam_id_name($sNopeg){
    //     $sQuery = "SELECT id_emp_fam,CNAME FROM tm_emp_fam where pernr='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
    //     $oRes = $this->db->query($sQuery);
    //     $aRows = $oRes->result_array();
    //     $oRes->free_result();
    //     return $aRows;
        
    // }
}

?>