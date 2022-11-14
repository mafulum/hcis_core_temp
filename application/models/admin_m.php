<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_mma
 *
 * @author Garuda
 */
class admin_m extends CI_Model {

    var $aUserType = array(1 => "User", 99 => "Administrator");
    var $aSubty_cr_readiness = array("1" => "Competency", "2" => "Detail");
    var $aSubty_crd_readiness = array("1" => "Range Value", "2" => "Fix Value");

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('orgchart_m');
        $this->load->model('ecs_m');
    }

    function config($sNopeg = "") {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/config';
        $data['table'] = $this->get_config_table();
        $data["userid"] = $this->session->userdata('username');
        return $data;
    }

    function abbrev_fr_update($iSeq) {
        $data['frm'] = $this->get_abbrev_table($iSeq);
        $data['base_url'] = $this->config->item('base_url');
        $data['abbrev'] = $this->get_abbrev_group();
        $data['view'] = 'admin/abbrev_fr_update';
		$data["userid"] = $this->session->userdata('username');
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                SUBTY: "required",
                SHORT: "required",
                STEXT: "required"
            },
            messages: {
                SUBTY: "Please enter SubType",
                SHORT: "Please enter Short Code",
                STEXT: "Please enter STEXT"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function abbrev_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/abbrev_fr_new';
        $data['abbrev'] = $this->get_abbrev_group();
		$data["userid"] = $this->session->userdata('username');
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
        rules: {
            SUBTY: "required",
            SHORT: "required",
            STEXT: "required"
        },
        messages: {
            SUBTY: "Please enter SubType",
            SHORT: "Please enter Short Code",
            STEXT: "Please enter STEXT"
        },submitHandler: function() {
            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                if(modalAnswer=="1"){
                    $("#fr_insert")[0].submit();
                }
            });
        }
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

    function abbrev_upd($iSeq, $a) {
        $this->db->where('id_abbrv', $iSeq);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update("tm_master_abbrev", $a);
    }

    function abbrev_delete($iSeq) {
        $this->db->where('id_abbrv', $iSeq);
        $this->db->delete("tm_master_abbrev");
        $this->global_m->insert_log_delete('tm_master_abbrev',array('id_abbrv'=> $iSeq));
    }

    function abbrev_insert($a) {
        $a['created_by']= $this->session->userdata('username');
        $this->db->insert('tm_master_abbrev', $a);
    }

    function get_abbrev_group() {
        $sQuery = "SELECT * FROM tm_master_abbrev WHERE SHORT='0'";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_abbrev_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE abb.id_abbrv=$iSeq";
        }
        $sQuery = "SELECT abb.id_abbrv,abb.SUBTY,info.STEXT grp,abb.SHORT,abb.STEXT FROM (SELECT * FROM tm_master_abbrev WHERE SHORT<>'0') abb
            JOIN (SELECT STEXT,SUBTY FROM tm_master_abbrev where SHORT='0') info ON abb.SUBTY=info.SUBTY
            " . $iSeq;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function abbrev() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/abbrev';
		$data["userid"] = $this->session->userdata('username');
        $data['table'] = $this->get_abbrev_table();
        $data['scriptJS'] ='
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

    function config_fr_update($iSeq) {
        $data['frm'] = $this->get_config_table($iSeq);
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/config_fr_update';
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                seq: "required"
            },
            messages: {
                seq: "Please enter ID Config"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function config_upd($iSeq, $seq) {
        $sQuery = "UPDATE tm_config SET seq='$seq',updated_by = '".$this->session->userdata('username')."' WHERE idc='$iSeq'";
        $this->db->query($sQuery);
    }

    function get_config_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE idc=$iSeq";
        }
        $sQuery = "SELECT * FROM tm_config " . $iSeq;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function get_user_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " AND id='$iSeq'";
        }
        $sQuery = "SELECT id,pernr,username,user_type,last_login,pwd_raw FROM tm_user WHERE isActive=1" . $iSeq;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function get_userd_table($iSeq = "") {
        $sQuery = "SELECT om.id,SHORT,STEXT FROM tm_org_maintain om JOIN tm_master_org mo ON om.org_unit=mo.OBJID 
            WHERE OTYPE='O' AND id_user= '$iSeq';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes;
        return $aRes;
    }
    
    function get_userdm_table($iSeq = "") {
        $sQuery = "SELECT um.id_mod,ma.SHORT,ma.STEXT,um.id_module FROM tm_user_module um JOIN (SELECT * FROM tm_master_abbrev where SUBTY='13' AND SHORT<>'0') ma ON um.id_module=ma.SHORT
            WHERE id_user= '$iSeq' ORDER BY ma.SEQ ASC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes;
        return $aRes;
    }
    
    function m_competency_add($a){
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_master_compt',$a);
    }
    
    function m_competency_del($sPrefix,$id){
        $sQuery="DELETE FROM tm_master_compt where LEFT(OBJID,3)='$sPrefix' AND id_compt='$id'";
        $this->db->query($sQuery);
        $this->global_m->insert_log_delete('tm_master_compt',$sQuery);
    }
    
    function get_company_complist(){
        $sQuery="SELECT mo.OBJID,STEXT,k1.k FROM (
select  SHORT,concat(LEFT(seq,3),'00000') as a from tm_config where ctype='ORG') o1
JOIN tm_master_org mo ON o1.a=mo.OBJID AND mo.OTYPE='O'
JOIN (SELECT SHORT,LEFT(seq,3) k FROM tm_config WHERE ctype='KOMPETENSI') k1 ON o1.SHORT=k1.SHORT;";
        $oRes=$this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    function m_competency_detail($objid="",$sPrefix=""){
        if(empty($objid) || empty($sPrefix)){
            return $this->m_competency();
        }
        $sQuery="SELECT STEXT FROM tm_master_org where OBJID='$objid' AND OTYPE='O' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes=$this->db->query($sQuery);
        if($oRes->num_rows()==0)
            return $this->m_competency();
        $data['objid']=$objid;
        $data['sPrefix']=$sPrefix;
        $row=$oRes->row_array();
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/m_competency_detail';
        if($row['STEXT']=="ROOT")$row['STEXT']="GDPS Competency";
        $data['header_text']=$row['STEXT'];
        $sQuery="SELECT * FROM tm_master_compt WHERE OTYPE='KC' AND LEFT(OBJID,3)='".$sPrefix."' AND CURDATE() BETWEEN BEGDA AND ENDDA ";
        $oRes = $this->db->query($sQuery);
        $data['KC']=array();
        $data['det']=array();
        $data['cComp']=array();
        
        $data['scriptJS'] ='
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
        if($oRes->num_rows()==0){
            returN $data;
        }
        $data['KC']=$oRes->result_array();
        $sKC="";
        $data['dKey']=array();
        for($i=0;$i<count($data['KC']);$i++){
            if($i>0)$sKC.=",";
            $sKC.="'".$data['KC'][$i]['SHORT']."'";
            $data['dKey'][$data['KC'][$i]['SHORT']]=$data['KC'][$i]['STEXT'];
        }
        $sQuery="SELECT * FROM tm_master_compt WHERE OTYPE<>'KC' AND OTYPE IN ($sKC)  AND LEFT(OBJID,3)='".$sPrefix."' ";
        $oRes=$this->db->query($sQuery);
        if($oRes->num_rows()==0)return $data;
        $data['cComp']=$oRes->result_array();
        $oRes->free_result();
        return $data;        
    }
    
    function m_job_compt(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/m_job_compt';
        $data['table'] = $this->orgchart_m->getAllJob();
        $data['scriptJS'] ='
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
    
    function m_job_compt_requirement($objid){
        if(empty($objid) ){
            return $this->m_job_compt();
        }
        $aRes = $this->orgchart_m->get_master_org($objid,'C');
        if(empty($aRes)){
            return $this->m_job_compt();
        }
        $data['base_url'] = $this->config->item('base_url');
        $data['job']=$aRes;
        $data['view'] = 'admin/m_job_compt_requirement';
        $data['table'] = $this->ecs_m->getJobCompt($objid);
        $data['compt'] = $this->ecs_m->getMasterComptAndKC();
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
//        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['scriptJS'] ='
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
            $("#COMPT").select2();
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
    
    
    function m_competency(){
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/m_competency';
        $data['table'] = $this->get_company_complist();
        $data['scriptJS'] ='
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

    function user() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/user';
        $data['table'] = $this->get_user_table();
        $data['scriptJS'] ='
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

    function user_fr_new() {
		$data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/user_fr_new';
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#user_fr_new").validate({
            rules: {
                username: "required",
                password: "required"
            },
            messages: {
                username: "Please enter Username",
                password: "Please enter Password"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#user_fr_new")[0].submit();
                        }
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
	
    function user_fr_update($iSeq) {
        $data['frm'] = $this->get_user_table($iSeq);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/user_fr_update';
        $data['table_og'] = $this->get_userd_table($iSeq);
        $data['userd_filter'] = $this->get_userd_filter($iSeq);
        $data['table_dm']=$this->get_userdm_table($iSeq);
        $data['userdm_filter'] = $this->get_userdm_filter($iSeq);
        
        $data['scriptJS'] ='
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

    function get_userd_filter($iSeq) {
        $sQuery = "SELECT SHORT,STEXT,ol.OBJID FROM tm_org_level ol 
            JOIN tm_master_org mo ON ol.OBJID=mo.OBJID
            WHERE OTYPE='O' 
            AND CURDATE() BETWEEN mo.BEGDA AND mo.ENDDA AND ol.OBJID NOT IN(SELECT org_unit FROM tm_org_maintain WHERE id_user='$iSeq')";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_userdm_filter($iSeq) {
        $sQuery = "SELECT ma.SHORT,ma.STEXT FROM (SELECT * FROM tm_master_abbrev where SUBTY='13' AND SHORT<>'0') ma
        LEFT JOIN (SELECT * FROM tm_user_module WHERE id_user='$iSeq') um ON ma.SHORT=um.id_module
        WHERE um.id_user is null ORDER BY ma.SEQ ASC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function user_upd($iSeq, $a) {
        $this->db->where('id', $iSeq);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update("tm_user", $a);
    }

    function user_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_user', $a);
        return $this->db->insert_id();
    }

    function userd_add($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_org_maintain', $a);
    }

    function userdm_add($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_user_module', $a);
    }

    function user_delete($id) {
        $this->db->where('id', $id);
        $a = array('isActive' => '0');
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_user', $a);
    }

    function user_delete_maintain($id, $id_user) {
        $this->db->where('id', $id);
        $this->db->where('id_user', $id_user);
        $this->db->delete('tm_org_maintain');
        $this->global_m->insert_log_delete('tm_org_maintain',array('id_user'=> $id_user,'id'=>$id));
    }
    
    function user_delete_module($id,$id_user){
        $this->db->where('id_module', $id);
        $this->db->where('id_user', $id_user);
        $this->db->delete('tm_user_module');
        $this->global_m->insert_log_delete('tm_user_module',array('id_user'=> $id_user,'id_module'=>$id));
    }

    function log_login($id_user) {
        $this->db->where('id', $id_user);
        $a = array('last_login' => date("Ymd H:i:s"));
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_user', $a);
    }

    //---------------------

    function perusahaan() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/perusahaan';
        $data['table'] = $this->get_perusahaan_table();
        return $data;
    }

    function get_perusahaan_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE l.OBJID='$iSeq'";
        }
        $sQuery = "SELECT l.id_level,l.BEGDA,l.ENDDA,l.LEVEL,l.SEQ,o.SHORT,o.STEXT,l.OBJID FROM tm_org_level l
            JOIN tm_master_org o ON l.OBJID=o.OBJID AND o.OTYPE='O' " . $iSeq . " ORDER BY l.id_level";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function perusahaan_fr_update($iSeq) {
        $data['iSeq'] = $iSeq;
        $data['frm'] = $this->get_perusahaan_table($iSeq);
		$data["userid"] = $this->session->userdata('username');
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/perusahaan_fr_update';
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                BEGDA: "required",
                ENDDA: "required",
                LEVEL: "required",
                SEQ: "required"
            },
            messages: {
                BEGDA: "Please enter BEGDA",
                ENDDA: "Please enter ENDDA",
                LEVEL: "Please enter LEVEL",
                SEQ: "Please enter SEQ"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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
    function perusahaan_fr_new(){
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/perusahaan_fr_new';
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
            rules: {
                perusahaan: "required",
                kode: "required",
                BEGDA: "required",
                ENDDA: "required",
                LEVEL: "required",
                SEQ: "required"
            },
            messages: {
                username: "Please enter Username",
                kode: "Please enter kode",
                BEGDA: "Please enter BEGDA",
                ENDDA: "Please enter ENDDA",
                LEVEL: "Please enter LEVEL",
                SEQ: "Please enter SEQ"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_insert")[0].submit();
                        }
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

    function perusahaan_update($id_level, $objid, $a) {
        $this->db->where('id_level', $id_level);
        $this->db->where('OBJID', $objid);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_org_level', $a);
    }

    function matrix_js() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/matrix_js';
        $data['table'] = $this->get_matrixjs_table();
        $data['scriptJS'] ='
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

    function get_matrixjs_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE l.id_matrix='$iSeq'";
        }
        $sQuery = "SELECT l.id_matrix,l.ORGID,l.STELL,SCORE,o.SHORT o_SHORT,o.STEXT o_STEXT,ma.SHORT ma_short,ma.STEXT ma_STEXT FROM tm_matrix_job_score l
            JOIN tm_master_org o ON l.ORGID=o.OBJID AND o.OTYPE='O' 
            JOIN (SELECT * FROM tm_master_abbrev WHERE SUBTY='6' AND SHORT<>'0') ma ON l.STELL=ma.SHORT " . $iSeq . " ORDER BY o.SHORT ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function matrixjs_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/matrixjs_fr_new';
        $data['STELL'] = $this->global_m->get_abbrev("6");
		$data["userid"] = $this->session->userdata('username');
        $data['ORGID'] = $this->get_perusahaan_table();
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
            rules: {
                ORGID: "required",
                STELL: "required",
                SCORE: "required"
            },
            messages: {
                ORGID: "Please enter ORG",
                STELL: "Please enter STELL",
                SCORE: "Please enter SCORE"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_insert")[0].submit();
                        }
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

    function matrixjs_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_matrix_job_score', $a);
    }

    function matrixjs_fr_update($iSeq) {
        $data['frm'] = $this->get_matrixjs_table($iSeq);
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/matrixjs_fr_update';
        $data['STELL'] = $this->global_m->get_abbrev("6");
        $data['ORGID'] = $this->get_perusahaan_table();
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                BEGDA: "required",
                ENDDA: "required",
                LEVEL: "required",
                SEQ: "required"
            },
            messages: {
                BEGDA: "Please enter BEGDA",
                ENDDA: "Please enter ENDDA",
                LEVEL: "Please enter LEVEL",
                SEQ: "Please enter SEQ"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function matrixjs_upd($id_matrix, $a) {
        $this->db->where('id_matrix', $id_matrix);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_matrix_job_score', $a);
    }

    function matrixjs_delete($id_matrix) {
        $this->db->where('id_matrix', $id_matrix);
        $this->db->delete('tm_matrix_job_score');
        $this->global_m->insert_log_delete('tm_matrix_job_score',array('id_matrix'=> $id_matrix));
    }

    function mperformance() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/mperformance';
        $data['table'] = $this->get_mperformance_table();
        $data['scriptJS'] ='
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

    function get_mperformance_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " AND mp.id_perf='$iSeq'";
        }
        $sQuery = "SELECT mp.id_perf,mp.ORGID,mp.BEGDA,mp.ENDDA,o.SHORT,o.STEXT,LMIN,LMAX,MMIN,MMAX,HMIN,HMAX
            FROM tm_master_performance mp
            JOIN tm_master_org o ON mp.ORGID=o.OBJID AND o.OTYPE='O' 
            WHERE CURDATE() BETWEEN o.BEGDA AND o.ENDDA
            " . $iSeq . " ORDER BY o.SHORT ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function mperformance_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/mperformance_fr_new';
        $data['ORGID'] = $this->get_perusahaan_table();
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
            rules: {
                BEGDA: "required",
                ENDDA: "required",
                ORGID: "required",
                LMIN: "required",
                LMAX: "required",
                MMIN: "required",
                MMAX: "required",
                HMIN: "required",
                HMAX: "required"
            },
            messages: {
                BEGDA: "Please enter BEGDA",
                ENDDA: "Please enter ENDDA",
                ORGID: "Please enter ORG",
                LMIN: "Please enter LMIN",
                LMAX: "Please enter LMAX",
                MMIN: "Please enter MMIN",
                MMAX: "Please enter MMAX",
                HMIN: "Please enter HMIN",
                HMAX: "Please enter HMAX"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_insert")[0].submit();
                        }
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

    function mperformance_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_master_performance', $a);
        return $this->db->insert_id();
    }

    function mperformance_fr_update($iSeq) {
        $data['frm'] = $this->get_mperformance_table($iSeq);
		$data["userid"] = $this->session->userdata('username');
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/mperformance_fr_update';
        $data['ORGID'] = $this->get_perusahaan_table();
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                BEGDA: "required",
                ENDDA: "required",
                ORGID: "required",
                LMIN: "required",
                LMAX: "required",
                MMIN: "required",
                MMAX: "required",
                HMIN: "required",
                HMAX: "required"
            },
            messages: {
                BEGDA: "Please enter BEGDA",
                ENDDA: "Please enter ENDDA",
                ORGID: "Please enter ORG",
                LMIN: "Please enter LMIN",
                LMAX: "Please enter LMAX",
                MMIN: "Please enter MMIN",
                MMAX: "Please enter MMAX",
                HMIN: "Please enter HMIN",
                HMAX: "Please enter HMAX"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function mperformance_update($id_perf, $a) {
        $this->db->where('id_perf', $id_perf);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_master_performance', $a);
    }

    function mperformance_delete($id_perf, $org_id) {
        $this->db->where("id_perf", $id_perf);
        $this->db->where('ORGID', $org_id);
        $this->db->delete('tm_master_performance');
        $this->global_m->insert_log_delete('tm_master_performance',array('ORGID'=> $org_id,'id_perf'=>$id_perf));
    }

    function mpotential() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/mpotential';
        $data['table'] = $this->get_mpotential_table();
        $data['scriptJS'] ='
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

    function get_mpotential_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE id_pot='$iSeq'";
        }
        $sQuery = "SELECT id_pot,LEVEL,BEGDA,ENDDA,MIN,MAX
            FROM tm_master_potential
            " . $iSeq . " ORDER BY BEGDA DESC,LEVEL";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function mpotential_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/mpotential_fr_new';
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
            rules: {
                BEGDA: "required",
                ENDDA: "required",
                LEVEL: "required",
                MIN: "required",
                MAX: "required"
            },
            messages: {
                BEGDA: "Please enter BEGDA",
                ENDDA: "Please enter ENDDA",
                LEVEL: "Please enter LEVEL",
                MIN: "Please enter MIN",
                MAX: "Please enter MAX"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_insert")[0].submit();
                        }
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

    function mpotential_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_master_potential', $a);
        return $this->db->insert_id();
    }

    function mpotential_fr_update($iSeq) {
        $data['frm'] = $this->get_mpotential_table($iSeq);
		$data["userid"] = $this->session->userdata('username');
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/mpotential_fr_update';
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                BEGDA: "required",
                ENDDA: "required",
                LEVEL: "required",
                MIN: "required",
                MAX: "required"
            },
            messages: {
                BEGDA: "Please enter BEGDA",
                ENDDA: "Please enter ENDDA",
                LEVEL: "Please enter LEVEL",
                MIN: "Please enter MIN",
                MAX: "Please enter MAX"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function mpotential_update($id_pot, $a) {
        $this->db->where('id_pot', $id_pot);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_master_potential', $a);
    }

    function mpotential_delete($id_pot, $level) {
        $this->db->where("id_pot", $id_pot);
        $this->db->where('LEVEL', $level);
        $this->db->delete('tm_master_potential');
        $this->global_m->insert_log_delete('tm_master_potential',array('id_pot'=> $id_pot,'LEVEL'=>$level));
    }

    function mreadiness() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/mreadiness';
		$data["userid"] = $this->session->userdata('username');
        $data['table'] = $this->get_mreadiness_table();
        $data['scriptJS'] ='
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

    function get_mreadiness_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE id_readiness='$iSeq'";
        }
        $sQuery = "SELECT *
            FROM tm_master_readiness
            " . $iSeq . " ORDER BY MAX DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function mreadiness_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/mreadiness_fr_new';
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
            rules: {
                DESC: "required",
                MIN: "required",
                MAX: "required"
            },
            messages: {
                DESC: "Please enter DESC",
                MIN: "Please enter MIN",
                MAX: "Please enter MAX"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_insert")[0].submit();
                        }
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

    function mreadiness_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_master_readiness', $a);
        return $this->db->insert_id();
    }

    function mreadiness_fr_update($iSeq) {
        $data['frm'] = $this->get_mreadiness_table($iSeq);
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/mreadiness_fr_update';
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                DESC: "required",
                MIN: "required",
                MAX: "required"
            },
            messages: {
                DESC: "Please enter DESC",
                MIN: "Please enter MIN",
                MAX: "Please enter MAX"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function mreadiness_update($id_readiness, $a) {
        $this->db->where('id_readiness', $id_readiness);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_master_readiness', $a);
    }

    function mreadiness_delete($id_readiness) {
        $this->db->where("id_readiness", $id_readiness);
        $this->db->delete('tm_master_readiness');
        $this->global_m->insert_log_delete('tm_master_readiness',array('id_readiness'=> $id_readiness));
    }

    function talentdesc() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/talentdesc';
        $data['table'] = $this->get_talentdesc_table();
        $data['scriptJS'] ='
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

    function get_talentdesc_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE id_desc='$iSeq'";
        }
        $sQuery = "SELECT *
            FROM tm_talent_desc
            " . $iSeq . " ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }

    function talentdesc_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/talentdesc_fr_new';
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
            rules: {
                SHORT: "required",
                STEXT: "required",
                DESC: "required"
            },
            messages: {
                SHORT: "Please enter SHORT",
                STEXT: "Please enter STEXT",
                DESC: "Please enter DESC"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_insert")[0].submit();
                        }
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

    function talentdesc_new($a) {
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_talent_desc', $a);
        return $this->db->insert_id();
    }

    function talentdesc_fr_update($iSeq) {
        $data['frm'] = $this->get_talentdesc_table($iSeq);
		$data["userid"] = $this->session->userdata('username');
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/talentdesc_fr_update';
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                DESC: "required",
                STEXT: "required",
                SHORT: "required"
            },
            messages: {
                DESC: "Please enter DESC:",
                STEXT: "Please enter STEXT",
                SHORT: "Please enter SHORT"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function talentdesc_update($id_desc, $a) {
        $this->db->where('id_desc', $id_desc);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_talent_desc', $a);
    }

    function talentdesc_delete($id_desc) {
        $this->db->where("id_desc", $id_desc);
        $this->db->delete('tm_talent_desc');
        $this->global_m->insert_log_delete('tm_talent_desc',array('id_desc'=> $id_desc));
    }

    function cr_readiness() {
        $data['base_url'] = $this->config->item('base_url');
		$data["userid"] = $this->session->userdata('username');
        $data['view'] = 'admin/cr_readiness';
        $data['table'] = $this->get_cr_readiness_table();
        $data['SUBTY'] = $this->aSubty_cr_readiness;
        $data['scriptJS'] ='
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

    function get_cr_readiness_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE id_criteria='$iSeq'";
        }
        $sQuery = "SELECT *
            FROM tm_criteria_readiness
            " . $iSeq . " ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }
    function get_cr_readinessd_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE id_criteria='$iSeq'";
        }
        $sQuery = "SELECT *
            FROM tm_criteria_readiness_detail
            " . $iSeq . " ";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function cr_readiness_fr_update($iSeq) {
        $data['frm'] = $this->get_cr_readiness_table($iSeq);
        $data['table'] = $this->get_cr_readinessd_table($iSeq);
		$data["userid"] = $this->session->userdata('username');
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/cr_readiness_fr_update';
        $aKey=array_keys($this->aSubty_cr_readiness);
        for($i=0;$i<count($aKey);$i++){
            $sKey=$aKey[$i];
            $data['SUBTY'][$i]['id']=$sKey;
            $data['SUBTY'][$i]['desc']=$this->aSubty_cr_readiness[$sKey];
        }
        $aKeyD=array_keys($this->aSubty_crd_readiness);
        for($i=0;$i<count($aKeyD);$i++){
            $sKeyD=$aKeyD[$i];
            $data['SUBTYD'][$i]['id']=$sKeyD;
            $data['SUBTYD'][$i]['desc']=$this->aSubty_crd_readiness[$sKeyD];
        }
        $data['ASUBTYD']=$this->aSubty_crd_readiness;
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                SUBTY: "required",
                PERCT: "required",
                DESC: "required"
            },
            messages: {
                SUBTY: "Please enter SUBTY",
                PERCT: "Please enter PERCT",
                DESC: "Please enter DESC"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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
    function cr_readiness_fr_new(){        
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/cr_readiness_fr_new';
		$data["userid"] = $this->session->userdata('username');
        $aKey=array_keys($this->aSubty_cr_readiness);
        for($i=0;$i<count($aKey);$i++){
            $sKey=$aKey[$i];
            $data['SUBTY'][$i]['id']=$sKey;
            $data['SUBTY'][$i]['desc']=$this->aSubty_cr_readiness[$sKey];
        }
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_insert").validate({
            rules: {
                SUBTY: "required",
                PERCT: "required",
                DESC: "required"
            },
            messages: {
                SUBTY: "Please enter SUBTY",
                PERCT: "Please enter PERCT",
                DESC: "Please enter DESC"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_insert")[0].submit();
                        }
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
    function cr_readiness_new($a){
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_criteria_readiness', $a);
        return $this->db->insert_id();        
    }
    
    function cr_readiness_upd($id_criteria,$a){
        $this->db->where('id_criteria', $id_criteria);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update('tm_criteria_readiness', $a);    
    }
    
    function cr_readiness_delete($id_criteria){
        $this->db->where('id_criteria', $id_criteria);
        $this->db->delete('tm_criteria_readiness');
        $this->global_m->insert_log_delete('tm_criteria_readiness',array('id_criteria'=> $id_criteria));
    }
    function cr_readiness_detail_upd($a){
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_criteria_readiness_detail', $a);
        return $this->db->insert_id();  
    }
    function cr_readinessd_del($id_re_detail){        
        $this->db->where('id_re_detail', $id_re_detail);
        $this->db->delete('tm_criteria_readiness_detail');  
        $this->global_m->insert_log_delete('tm_criteria_readiness_detail',array('id_re_detail'=> $id_re_detail));
    }
    
    function create_prefix_perusahaan(){
        $sQuery="SELECT MAX(LEFT(OBJID,3))+1 n FROM tm_org_level;";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        return $aRow['n'];
    }
    
    function insert_org_level($a){
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_org_level', $a);
        return $this->db->insert_id();          
    }
    function insert_org_perusahaan($OBJID,$kode,$perusahaan,$begda,$endda){
        $a['OTYPE']="O";
        $a['OBJID']=$OBJID;
        $a['BEGDA']=$begda;
        $a['ENDDA']=$endda;
        $a['SHORT']=$kode;
        $a['STEXT']=$perusahaan;
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_master_org',$a);
    }

    function insert_relation_perusahaan($OBJID,$BEGDA,$ENDDA){
        $a['OTYPE']='O';
        $a['OBJID']=$OBJID;
        $a['SUBTY']="A002";
        $a['BEGDA']=$BEGDA;
        $a['ENDDA']=$ENDDA;
        $a['SCLAS']='O';
        $a['SOBID']='11000019';
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_master_relation',$a);
    }
    
    function genereate_tm_config($short,$objid){
        $a['short']=$short;
        $a['ctype']="PERNR";
        $a['seq']=$objid;
        $a['created_by'] = $this->session->userdata('username');
        $this->db->insert('tm_config',$a);
        $a['ctype']="ORG";
        $this->db->insert('tm_config',$a);
        $a['ctype']="POSITION";
        $a['seq']=$this->create_prefix_config("POSITION");
        $this->db->insert('tm_config',$a);
        $a['ctype']="JOB";
        $a['seq']=$this->create_prefix_config("JOB");
        $this->db->insert('tm_config',$a);        
        $a['ctype']="KOMPETENSI";
        $a['seq']=$this->create_prefix_config("KOMPETENSI");
        $this->db->insert('tm_config',$a);        
        
    }
    function create_prefix_config($ctype){
        $sQuery="SELECT MAX(LEFT(seq,3))+1 n FROM tm_config WHERE ctype='$ctype';";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        return $aRow['n']."000000";
    }


    function get_pic_customer_table($iSeq = "") {
        if (!empty($iSeq)) {
            $iSeq = " WHERE id=$iSeq";
        }
        $sQuery = "SELECT * FROM tm_pic_customer" . $iSeq;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        if (!empty($iSeq))
            return $aRes[0];
        return $aRes;
    }    
    function pic_customer() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/pic_customer';
		$data["userid"] = $this->session->userdata('username');
        $data['table'] = $this->get_pic_customer_table();
        $data['scriptJS'] ='
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

    

    function pic_customer_fr_update($iSeq) {
        $data['frm'] = $this->get_pic_customer_table($iSeq);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/pic_customer_fr_update';
		$data["userid"] = $this->session->userdata('username');
        $data['werks'] = $this->global_m->get_abbrev("5");
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#WERKS").select2();
    $("#fr_update").validate({
        rules: {
            begda: "required",
            endda: "required",
            WERKS: "required",
            type: "required",
            pernr: "required"
        },
        messages: {
            begda: "Please enter BEGDA",
            endda: "Please enter ENDDA",
            WERKS: "Please enter WERKS",
            pernr: "Please enter PERNR",
            type: "Please enter TYPE"
            },submitHandler: function() {
                $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                    if(modalAnswer=="1"){
                        $("#fr_update")[0].submit();
                    }
                });
            }
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

    function pic_customer_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'admin/pic_customer_fr_new';
        $data['werks'] = $this->global_m->get_abbrev("5");
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS'] ='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#WERKS").select2();
    $("#fr_insert").validate({
        rules: {
            begda: "required",
            endda: "required",
            WERKS: "required",
            type: "required",
            pernr: "required"
        },
        messages: {
            begda: "Please enter BEGDA",
            endda: "Please enter ENDDA",
            WERKS: "Please enter WERKS",
            pernr: "Please enter PERNR",
            type: "Please enter TYPE"
        },submitHandler: function() {
            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                if(modalAnswer=="1"){
                    $("#fr_insert")[0].submit();
                }
            });
        }
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

    function pic_customer_upd($iSeq, $a) {
        $this->db->where('id', $iSeq);
        $a['updated_by'] = $this->session->userdata('username');
        $this->db->update("tm_pic_customer", $a);
    }

    function pic_customer_delete($iSeq) {
        $this->db->where('id', $iSeq);
        $this->db->delete("tm_pic_customer");
        $this->global_m->insert_log_delete('tm_pic_customer',array('id'=> $iSeq));
    }

    function pic_customer_insert($a) {
        $a['created_by']= $this->session->userdata('username');
        $this->db->insert('tm_pic_customer', $a);
    }

}

?>
