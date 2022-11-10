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
class comm_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
    }
//    
    function get_a_comm_data($sNopeg){
        $sQuery = "SELECT emp_comm_id,BEGDA,ENDDA,STEXT,USRID,IDESC,NOTE FROM (SELECT * FROM tm_emp_comm WHERE PERNR='" . $sNopeg . "') comm "
                . "JOIN (select SHORT,STEXT from tm_master_abbrev where SUBTY='24' AND SHORT<>'0') abbrv ON comm.SUBTY=abbrv.SHORT ORDER BY comm.SUBTY,comm.BEGDA";
        $oRes = $this->db->query($sQuery);
        if($oRes->num_rows()==0){
            return null;
        }
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function personal_data_comm_fr_new($sNopeg){
        $data = $this->employee_m->get_default_data(array(), $sNopeg);
        $data['comm_type'] = $this->common->get_abbrev(24);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/comm_fr_new';
        $data['scriptJS'] .='
            <script>
            jQuery(document).ready(function() {
                $("#form_fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        cname: "required",
                        fBank: "required",
                        cCurr: "required",
                        cNote: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        cname: "Please enter Name",
                        cCurr: "Please enter Currency",
                        cNote: "Please enter Note"
                    },submitHandler: function() {
                        $("#form_fr_new")[0].submit();
                    },
                });
            });
            </script>
            ';
        return $data;
    }
    
    function view($iSeq, $sNopeg){
        $data['frm'] = $this->get_row($iSeq, $sNopeg);
        $data['comm_type'] = $this->common->get_abbrev(24);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/comm_view';
        return $data;
    }
    
    function personal_data_comm_fr_update($iSeq, $sNopeg){
        $data['frm'] = $this->get_row($iSeq, $sNopeg);
        $data['comm_type'] = $this->common->get_abbrev(24);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/comm_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#form_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                subty: "required",
                usrid: "required",
           2 },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
            },submitHandler: function() {
                $("#form_fr_update")[0].submit();
            }
        });
});
</script>
';
        return $data;
        
    }

    function comm_ov($sNopeg) {
        $data['ov'] = $this->get_a_comm_data($sNopeg);
        $data = $this->employee_m->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/pa/comm_ov';
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
//    
//    function check_time_constraint_personal_data_bank($pernr,$begda,$endda,$type="INSERT",$id=0){
//        if($type=="INSERT"){
//            $sQuery="SELECT * from tm_emp_bank where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
//            $oRes = $this->db->query($sQuery);
//            $nRows = $oRes->num_rows();
//            if($nRows==0){
//                return "null";
//            }else if($nRows==1){
//                $aRow = $oRes->row_array();
//                return "your input had time constraint with another row with begda ".$this->global_m->get_array_data($aRow, "BEGDA",$this->global_m->DATE_MYSQL)." and endda ".$this->global_m->get_array_data($aRow, "ENDDA",$this->global_m->DATE_MYSQL).", do you want overwrite ?";
//            }else{
//                return "your input had time constraint with ".$nRows." row , do you want overwrite (can cause delete some row) ?";
//            }
//        }else if($type=="UPDATE"){
//            $sQuery="SELECT * from tm_emp_bank where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_bank<>'$id'";
//            $oRes = $this->db->query($sQuery);
//            $nRows = $oRes->num_rows();
//            if($nRows==0){
//                return "null";
//            }else {
//                return "your input had time constraint, please back and check your data period. Thank you.";
//            }
//        }else if($type=="CHECK"){
//            $sQuery="SELECT * from tm_emp_bank where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp_bank<>'$id'";
//            $oRes = $this->db->query($sQuery);
//            return $oRes;
//        }
//    }
//
    function comm_upd($id, $sNopeg, $a) {
        $this->db->where('emp_comm_id', $id);
        $this->db->where('pernr', $sNopeg);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_emp_comm', $a);
    }

    function comm_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_emp_comm', $a);
    }
//
    function comm_del($id, $sNopeg) {
        $this->db->where('emp_comm_id', $id);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_comm');
        $this->global_m->insert_log_delete('tm_emp_comm',array('emp_comm_id'=> $id,'PERNR'=>$sNopeg));
    }
//    
    function get_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_comm where pernr='" . $sNopeg . "' AND emp_comm_id='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
}

?>