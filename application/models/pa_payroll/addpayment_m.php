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
class addpayment_m extends CI_Model {

    var $table = "tm_emp_addpayment";

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }

    function get_a_addpayment($sNopeg) {
        $sQuery = "SELECT apay.*,mscale.LGTXT from (SELECT * FROM tm_emp_addpayment WHERE PERNR='" . $sNopeg . "' ) apay " .
                "LEFT JOIN (select BEGDA,ENDDA,WGTYP,LGTXT from tm_master_payscale ) mscale ON apay.WGTYP=mscale.WGTYP " .
                "AND mscale.BEGDA<=apay.BEGDA AND mscale.ENDDA>=apay.BEGDA " .
                "ORDER BY mscale.LGTXT ASC,apay.BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_list_for_inout($begda, $evtda) {
        $this->db->from($this->table);
        $this->db->where('BEGDA', $begda);
        $this->db->where('EVTDA', $evtda);
        $temp = $this->db->get();
        if(empty($temp)){
            return null;
        }
        return $temp->result_array();
    }

    function get_master_payscale() {
//        $sQuery = "select WGTYP,LGTXT from tm_master_payscale where PRTYP in('-','+') AND (WGTYP NOT like '1%' AND WGTYP NOT like '6%') ORDER BY WGTYP";
        $sQuery = "select WGTYP,LGTXT from tm_master_payscale where PRTYP in('-','+')  ORDER BY WGTYP";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function personal_addpayment_fr_new($sNopeg) {
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['wgtyps'] = $this->get_master_payscale();
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addpayment_fr_new';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#wgtyp").select2();
                $("#pd_addpayment_fr_new").validate({
                    rules: {
                        begda: "required",
                        evtda: "required",
                        wgtyp: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        evtda: "Please enter Event Date",
                        wgtyp: "Please enter Wage Type"
                    },submitHandler: function() {
                        $("#pd_addpayment_fr_new")[0].submit();
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
        $data['frm'] = $this->get_tm_emp_addpayment_row($iSeq, $sNopeg);
        $data['wgtyps'] = $this->get_master_payscale();
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addpayment_view';
        return $data;
     }

    function personal_addpayment_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_addpayment_row($iSeq, $sNopeg);
        $data['wgtyps'] = $this->get_master_payscale();
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addpayment_fr_update';
        $data['scriptJS'] .= '
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
                $("#wgtyp").select2();
    $("#pd_addpayment_fr_update").validate({
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
                $("#pd_addpayment_fr_update")[0].submit();
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

    function personal_addpayment_ov($sNopeg) {
        $data['ov'] = $this->get_a_addpayment($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/addpayment_ov';
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
            $("#paTable").dataTable();
        });
        </script>
        ';
        return $data;
    }

    function check_time_constraint_addpayment($pernr, $begda, $endda, $wgtyp, $type = "INSERT", $id = 0) {
        $sAdd = "";
        if ($type == "INSERT") {
            $sQuery = "SELECT * from tm_emp_addpayment where PERNR='$pernr' AND WGTYP='$wgtyp' " . $sAdd . " AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if ($nRows == 0) {
                return "null";
            } else if ($nRows == 1) {
                $aRow = $oRes->row_array();
                return "your input had time constraint with another row with begda " . $this->global_m->get_array_data($aRow, "BEGDA", $this->global_m->DATE_MYSQL) . " and endda " . $this->global_m->get_array_data($aRow, "ENDDA", $this->global_m->DATE_MYSQL) . ", do you want overwrite ?";
            } else {
                return "your input had time constraint with " . $nRows . " row , do you want overwrite (can cause delete some row) ?";
            }
        } else if ($type == "UPDATE") {
            $sQuery = "SELECT * from tm_emp_addpayment where PERNR='$pernr' AND WGTYP='$wgtyp' " . $sAdd . " AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_addpayment_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if ($nRows == 0) {
                return "null";
            } else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        } else if ($type == "CHECK") {
            $sQuery = "SELECT * from tm_emp_addpayment where PERNR='$pernr' AND WGTYP='$wgtyp' " . $sAdd . " AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND emp_addpayment_id<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    function personal_addpayment_upd($id, $sNopeg, $a) {
        $this->db->where('emp_addpayment_id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_addpayment', $a);
    }

    function personal_addpayment_new($a) {
        foreach($a as $k=>$v){
            if(empty($v)){
                unset($a[$k]);
            }
        }
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_addpayment', $a);
        return $this->db->insert_id();
    }

    function personal_addpayment_del($id, $sNopeg) {
        $this->global_m->insert_log_delete('tm_emp_addpayment',array('PERNR'=> $sNopeg,'emp_addpayment_id'=>$id));
        $this->db->where('emp_addpayment_id', $id);
        $this->db->where('PERNR', $sNopeg);
        return $this->db->delete('tm_emp_addpayment');
    }

    function get_tm_emp_addpayment_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_addpayment where pernr='" . $sNopeg . "' AND emp_addpayment_id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

}

?>