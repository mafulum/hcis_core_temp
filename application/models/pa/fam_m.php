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
class fam_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
        $this->load->model('global_m');
    }
    
    function get_a_fam_data($sNopeg){
        $sQuery = "SELECT id_emp_fam,subty_fam.STEXT,GESCH,OBJPS,CNAME,GBDAT,GBNAT,GBLND FROM "
                . "(SELECT * FROM tm_emp_fam WHERE PERNR='" . $sNopeg . "') efam "
                . "JOIN (SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY='14' ) subty_fam ON efam.SUBTY=subty_fam.SHORT ";
//        echo $sQuery;exit;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function get_subty_fam(){
        $sQuery = "SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY='14'";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    
    function personal_data_fam_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['fgesch'] = $this->global_m->get_abbrev(2);
        $data['osubty'] = $this->global_m->get_abbrev(14);
        $data['ffamstat'] = $this->global_m->get_abbrev(15);
        $data['fNat'] = $this->global_m->get_abbrev(16);
        $data['fCty'] = $this->global_m->get_abbrev(17);
        $data['fWN'] = $this->global_m->get_abbrev(18);
        $data['fIncben'] = $this->global_m->get_abbrev(19);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/personal_data_fam_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#pd_fam_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        cname: "required",
                        cGbdat: "required",
                        cGblnd: "required",
                        cDocrt: "required",
                        cObjps: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        cname: "Please enter Name",
                        cGbdat: "Please enter Birth Date",
                        cGblnd: "Please enter Birth Place",
                        cDocrt: "Please enter Document Cert",
                        cObjps: "Please enter Number Obj"
                    },submitHandler: function() {
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        fSubty = $("#fSubty").val();
                        cObjps = $("#cObjps").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_personal_data_fam", { "pernrX": pernr,"begdaX": begda, "enddaX": endda, "subtyX": fSubty, "objpsX": objps },function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_fam_fr_new")[0].submit();
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
        $data['frm'] = $this->get_tm_emp_fam_row($iSeq, $sNopeg);
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
    
    function personal_data_fam_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_tm_emp_fam_row($iSeq, $sNopeg);
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
        $data['emp_view'] = 'employee/pa/personal_data_fam_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_fam_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                cname: "required",
                cGbdat: "required",
                cGblnd: "required",
                cDocrt: "required",
                cObjps: "required"
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
                        fSubty = $("#fSubty").val();
                        cObjps = $("#cObjps").val();
                        id_emp_fam= $("#id_emp_fam").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_personal_data_fam", { "pernrX": pernr,"begdaX": begda, "enddaX": endda, "subtyX": fSubty, "objpsX": objps ,"id_emp_famX":id_emp_fam},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }
                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_fam_fr_update")[0].submit();
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

    function personal_data_fam_ov($sNopeg) {
        $data['ov'] = $this->get_a_fam_data($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/fam_ov';
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
    
    function check_time_constraint_personal_data_fam($pernr,$begda,$endda,$subty,$objps,$type="INSERT",$id=0){
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_fam where PERNR='$pernr' AND OBJPS='$objps' AND SUBTY='$subty' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else if($nRows==1){
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begda ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", Family Member ".$subty.", AND Number ".$objps.", do you want overwrite ?";
            }else{
                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
            }
        }else if($type=="UPDATE"){
            $sQuery="SELECT * from tm_emp_fam where PERNR='$pernr' AND SUBTY='$subty' AND OBJPS='$objps' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_fam<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            $sQuery="SELECT * from tm_emp_fam where PERNR='$pernr' AND SUBTY='$subty' AND OBJPS='$objps' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_fam<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function personal_data_fam_upd($id_emp_fam, $sNopeg, $a) {
        $this->db->where('id_emp_fam', $id_emp_fam);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_fam', $a);
    }

    function personal_data_fam_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_fam', $a);
    }

    function personal_data_fam_del($id_emp_fam, $sNopeg) {
        $this->db->where('id_emp_fam', $id_emp_fam);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_fam');
        $this->global_m->insert_log_delete('tm_emp_fam',array('PERNR'=> $sNopeg,'id_emp_fam'=>$id_emp_fam));
    }
    
    function get_tm_emp_fam_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_fam where pernr='" . $sNopeg . "' AND id_emp_fam='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
    
    function get_fam_id_name($sNopeg){
        $sQuery = "SELECT id_emp_fam,CNAME FROM tm_emp_fam where pernr='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRows = $oRes->result_array();
        $oRes->free_result();
        return $aRows;
        
    }
}

?>