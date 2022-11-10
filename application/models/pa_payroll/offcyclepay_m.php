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
class offcyclepay_m extends CI_Model {

    var $table = "tm_emp_addoffcycle";

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }

    function get_a_offcycle($sNopeg) {
        $sQuery = "SELECT apay.*,mscale.LGTXT from (SELECT * FROM ".$this->table." WHERE PERNR='" . $sNopeg . "' ) apay " .
                "LEFT JOIN (select BEGDA,ENDDA,WGTYP,LGTXT from tm_master_payscale ) mscale ON apay.WGTYP=mscale.WGTYP " .
                "AND mscale.BEGDA<=apay.BEGDA AND mscale.ENDDA>=apay.BEGDA " .
                "ORDER BY mscale.LGTXT ASC,apay.BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    function get_master_payscale() {
        $sQuery = "select WGTYP,LGTXT from tm_master_payscale where PRTYP in('-','+')  ORDER BY WGTYP";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function view($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_offcycle_row($iSeq, $sNopeg);
        $data['wgtyps'] = $this->get_master_payscale();
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/offcycle_payment_view';
        return $data;
     }

    function overview($sNopeg) {
        $data['ov'] = $this->get_a_offcycle($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa_payroll/offcycle_payment_ov';
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

    function get_tm_emp_offcycle_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM ".$this->table." where pernr='" . $sNopeg . "' AND id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

}

?>