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
class addtlinfo_m extends CI_Model {

    var $table = "tm_emp_slip_addtlinfo";

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }

    function get_a_addtlinfo($sNopeg) {
        $sQuery = "SELECT * FROM ".$this->table." WHERE PERNR='".$sNopeg."'";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function personal_addtlinfo_fr_new($sNopeg) {
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addtlinfo_fr_new';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#fDateOffCycle").datepicker();
                $("#cPeriodRegular").datepicker({
                    autoclose: true
                });
                $("#pd_addtlinfo_fr_new").validate({
                    rules: {
                        note: "required"
                    },
                    messages: {
                        note: "Please enter note texr"
                    },submitHandler: function() {
                        $("#pd_addtlinfo_fr_new")[0].submit();
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

    function view($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_addtlinfo_row($iSeq, $sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addtlinfo_view';
        return $data;
    }

    function personal_addtlinfo_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_addtlinfo_row($iSeq, $sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addtlinfo_fr_update';
        $data['scriptJS'] .= '
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    
    $("#fDateOffCycle").datepicker();
    $("#cPeriodRegular").datepicker({
        autoclose: true
    });
    $("#pd_addtlinfo_fr_update").validate({
            rules: {
                note: "required"
            },
            messages: {
                note: "Please enter note"
            },submitHandler: function() {
                $("#pd_addtlinfo_fr_update")[0].submit();
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

    function personal_addtlinfo_ov($sNopeg) {
        $data['ov'] = $this->get_a_addtlinfo($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addtlinfo_ov';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['scriptJS'] .= '
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
    
    function personal_addtlinfo_upd($id, $sNopeg, $a) {
        $this->db->where('id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update($this->table, $a);
    }

    function personal_addtlinfo_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert($this->table, $a);
        return $this->db->insert_id();
    }

    function personal_addtlinfo_del($id, $sNopeg) {
        $this->global_m->insert_log_delete($this->table,array('PERNR'=> $sNopeg,'id'=>$id));
        $this->db->where('id', $id);
        $this->db->where('PERNR', $sNopeg);
        return $this->db->delete($this->table);
    }

    function get_tm_emp_addtlinfo_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM $this->table where PERNR='" . $sNopeg . "' AND id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
    
    function get_tm_emp_addtlinfo_by_periode_regular($periode,$sNopeg){
//        echo $periode."|".$sNopeg;exit;
        $this->db->from($this->table);
        $this->db->where('is_offcycle is null');
        $this->db->where('periode_regular',$periode);
        $this->db->where('PERNR',$sNopeg);
        $temp = $this->db->get();
        if(empty($temp)){
            return null;
        }
        return $temp->result_array();
    }
    
    function get_tm_emp_addtlinfo_by_date_offcycle($sDateOffCycle,$sNopeg){
        $this->db->from($this->table);
        $this->db->where('is_offcycle is not null');
        $this->db->where('date_offcycle',$sDateOffCycle);
        $this->db->where('PERNR',$sNopeg);
        $temp = $this->db->get();
        if(empty($temp)){
            return null;
        }
        return $temp->result_array();
    }

}

?>