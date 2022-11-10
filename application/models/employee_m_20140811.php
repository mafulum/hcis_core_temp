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
class employee_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('orgchart_m');
    }

    //put your code here
    function home($sNopeg = "") {
        $aBukrs = $this->employee_m->get_org_level();
        $sBukrs = json_encode($aBukrs);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data["userid"] = $this->session->userdata('username');
        $data['emp_view'] = 'employee/menu_tab';
        $data = $this->get_default_data($data, $sNopeg);
        $data['anak_perusahaan'] = $this->get_anak_perusahaan();
        $data['externalJS'].='<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalCSS'].='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['scriptJS'].='
<script>
jQuery(document).ready(function() {
    $("#fComp").select2({
		data: ' . $sBukrs . '
		,dropdownAutoWidth: true
    }).on("select2-selecting", function(e) {
        $("#fPos").select2("val","");
    });

    $("#fPos").select2({
    placeholder: "Search for a position",
    minimumInputLength: 1,
    ajax: {
        url: "' . base_url() . 'index.php/employee/fetch_empty_position/",
        dataType: "json",
        type: "POST",
        data: function (term, page) {
            return {
                q: term,
                comp :$("#fComp").select2("val")
            };
        },
        results: function (data, page) { 
            return {results: data};
        },initSelection: function(element, callback) {                   
            }
    }
    });
});
</script>
';
        $data['aCon'] = $data;
        return $data;
    }

    function get_default_data($data, $sNopeg) {
        $data['master_emp'] = "";
        $data['emp_org'] = "";
        $data['aCon'] = "";
        $data['externalCSS'] = '<style>
           .tt-dropdown-menu,
.gist {
  text-align: left;
}
.typeahead,
.tt-query,
.tt-hint {
  width: 396px;
  height: 30px;
  padding: 8px 12px;
  font-size: 24px;
  line-height: 30px;
  border: 2px solid #ccc;
  -webkit-border-radius: 8px;
     -moz-border-radius: 8px;
          border-radius: 8px;
  outline: none;
}

.typeahead {
  background-color: #fff;
}

.typeahead:focus {
  border: 2px solid #0097cf;
}

.tt-query {
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
  color: #999
}

.tt-dropdown-menu {
  width: 422px;
  margin-top: 12px;
  padding: 8px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 8px;
     -moz-border-radius: 8px;
          border-radius: 8px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
  padding: 3px 20px;
  font-size: 18px;
  line-height: 24px;
}

.tt-suggestion.tt-cursor {
  color: #fff;
  background-color: #0097cf;

}

.tt-suggestion p {
  margin: 0;
}

.gist {
  font-size: 14px;
} 
</style>';
        $aPrsh = $this->get_org_level();
        $sPrsh = json_encode($aPrsh);

        $aEmp = $this->get_emp($aPrsh);
        $sEmp = json_encode($aEmp);
        
        $aPHK = $this->common->get_abbrev(4,"9%");
        $sPHK = json_encode($aPHK);
        $data['externalCSS'] .='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        //    $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'assets/typeahead.bundle.js"></script>
        //        <script type="text/javascript" src="' . base_url() . 'assets/handlebars.js"></script>';
        $data['scriptJS'] = '
							<script>
                                                        var modalAnswerPHK="0";
							function format(item) {
								if (!item.id) return "<b>" + item.text + "</b>"; // optgroup
								return "&nbsp;&nbsp;&nbsp;" + item.text;
							};
							function formatSel(item){
								return item.id;
							};
							$(document).ready(function() {
								$("#iNopeg").select2({
									data: ' . $sEmp . ',
									formatResult : format,
									formatSelection : formatSel,
									dropdownAutoWidth: true
								});

								$("#iNopeg").change(function(){
										var sNopeg = $("#iNopeg").val();
										location.replace("' . base_url() . 'index.php/employee/master/" + sNopeg);
										return false;
								});
                                                                 $("#fPHK").select2({
                                                                        data: ' . $sPHK. '
                                                                        ,dropdownAutoWidth: true
                                                                });
                                                                $("#frmPHK").validate({
                                                                    rules: {
                                                                        terminate_date: "required",
                                                                        fPHK: "required"
                                                                    },
                                                                    messages: {
                                                                        terminate_date: "Please enter  Date",
                                                                        fPHK: "Please enter PHK Type"
                                                                    },submitHandler: function() {
                                                                        $("#confirm-PHK").modal("show").on("hidden.bs.modal", function (e) {
                                                                            if(modalAnswerPHK=="1"){
                                                                                $("#frmPHK")[0].submit();
                                                                            }
                                                                        });
                                                                    },
                                                                });
                                                                $("#btnYesPHK").click( function(){
                                                                    modalAnswerPHK="1";
                                                                    $("#confirm-PHK").modal("hide");
                                                                });
                                                                $("#btnNoPHK").click( function(){
                                                                    modalAnswerPHK="2";
                                                                    $("#confirm-PHK").modal("hide");
                                                                });
							});
							</script>
							';

        if (!empty($sNopeg)) {
            $data['master_emp'] = $this->get_master_emp_single($sNopeg);
            $data['emp_org'] = $this->get_emp_org_single($sNopeg);
            $data['emp_map'] = $this->get_emp_mapping($sNopeg);
        }
        return $data;
    }

    // Add by Andi 04.06.2014
    function get_emp($aPrshs) {
        $aRtn = null;
        $i = 0;
        if ($aPrshs) {
            foreach ($aPrshs as $aPrsh) {
                $aRtn[$i]["text"] = $aPrsh["text"];
                $aEmps = $this->get_mapping_pernr($aPrsh["id"]);
                if ($aEmps) {
                    foreach ($aEmps as $aEmp) {
                        $i++;
                        $aRtn[$i]["id"] = $aEmp["id"];
                        $aRtn[$i]["text"] = $aEmp["id"] . " | " . $aEmp["text"];
                    }
                }
                $i++;
            }
        }

        return $aRtn;
    }

    function get_mapping_pernr($sPrsh) {
        $sQuery = "SELECT p.PERNR as id, m.CNAME as text " .
                "FROM tm_mapping_pernr p " .
                "JOIN tm_master_emp m ON m.PERNR = p.PERNR AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
                "WHERE ORGEH = '" . $sPrsh . "' AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA " .
                "ORDER BY CNAME ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

        return $aRes;
    }

    function get_org_level() {
		$aOrg = $this->common->get_a_org_auth();
		$sOrg = implode(",",$aOrg);
		
        $sQuery = "SELECT l.OBJID as id, l.LEVEL, o.STEXT as text, o.SHORT, o.SHORT as name " .
                "FROM tm_org_level l " .
                "JOIN tm_master_org o ON o.OBJID = l.OBJID AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA " .
				"WHERE l.OBJID IN(".$sOrg.") ".
                "ORDER BY l.LEVEL ASC, l.SEQ ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    // End Add by Andi 04.06.2014

    function get_master_emp_single($sNopeg) {
        $sQuery = "SELECT * FROM tm_master_emp where PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_tm_master_emp_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_master_emp where PERNR='" . $sNopeg . "' AND id_emp='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    function get_arr_emp($sSearch) {
        $sSearch = str_replace("*", "%", $sSearch);
        $sQuery = "SELECT PERNR,CNAME,GBDAT FROM tm_master_emp WHERE CNAME like '%$sSearch' OR CNAME like '%$sSearch%' ORDER BY PERNR LIMIT 0,50";
        $oRes = $this->db->query($sQuery);
        $aRet = array();
        if ($oRes->num_rows() > 0) {
            $aRes = $oRes->result_array();
            for ($i = 0; $i < count($aRes); $i++) {
                $ret['value'] = $aRes[$i]['PERNR'];
                $ret['dob'] = $aRes[$i]['GBDAT'];
                $ret['name'] = $aRes[$i]['CNAME'];
                $ret['tokens'] = explode(" ", $aRes[$i]['CNAME']);
                $ret['tokens'][] = $aRes[$i]['PERNR'];
                $aRet[] = $ret;
            }
        }
        return $aRet;
    }

    function get_emp_org_single($sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_org where PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_emp_mapping($sNopeg) {
        $sQuery = "SELECT m.NIK, g.STEXT as PERSH, r.STEXT as ORG, p.STEXT as POSISI " .
                "FROM tm_mapping_pernr m JOIN tm_master_emp e " .
                "JOIN tm_emp_org o JOIN tm_master_org r JOIN tm_master_org p JOIN tm_master_org g " .
                "WHERE m.PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
                "AND m.PERNR = e.PERNR AND CURDATE() BETWEEN e.BEGDA AND e.ENDDA " .
                "AND e.PERNR = o.PERNR AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA " .
                "AND r.OBJID = o.ORGEH AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA AND r.OTYPE = 'O' " .
                "AND p.OBJID = o.PLANS AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA AND p.OTYPE = 'S' " .
                "AND g.OBJID = m.ORGEH AND CURDATE() BETWEEN g.BEGDA AND g.ENDDA AND g.OTYPE = 'O';";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function personal_data_ov($sNopeg) {
        $data['ov'] = $this->get_a_personal_data($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/personal_data_ov';
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

    function personal_data_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/personal_data_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_fr_new").validate({
            rules: {
                begda: "required",
                endda: "required",
                cname: "required",
                gbdat: "required",
                gblnd: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                cname: "Please enter Name",
                gbdat: "Please enter Birth Date",
                gblnd: "Please enter Birth Place"
            },submitHandler: function() {
            //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_personal_data", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                            }
                        }).done(function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_fr_new")[0].submit();
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

    function personal_data_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_master_emp_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/personal_data_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#pd_fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                cname: "required",
                gbdat: "required",
                gblnd: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                cname: "Please enter Name",
                gbdat: "Please enter Birth Date",
                gblnd: "Please enter Birth Place"
            },submitHandler: function() {
                     //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_emp= $("#id_emp").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_personal_data", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_empX":id_emp},function (text){
                            if(text!="null"){
                                $("#mb").html(text);
                                $("#btnYes").hide();
                            }else{
                                $("#btnYes").show();
                            }
                            
                        }).done(function() {
                            $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#pd_fr_update")[0].submit();
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

    function get_a_personal_data($sNopeg) {
        $sQuery = "SELECT *,TIMESTAMPDIFF(YEAR, GBDAT, CURDATE()) AS AGE FROM tm_master_emp WHERE PERNR='" . $sNopeg . "'";
        $oRes = $this->db->query($sQuery);
        //$this->db->where('pernr', $sNopeg);
        //$oRes = $this->db->get('tm_master_emp');
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function personal_data_upd($id_emp, $sNopeg, $a) {
        $this->db->where('id_emp', $id_emp);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_master_emp', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_AGE,$sNopeg);
    }

    function personal_data_new($a) {
        $this->db->insert('tm_master_emp', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_AGE,$a['PERNR']);
    }

    function personal_data_del($id_emp, $sNopeg) {
        $this->db->where('id_emp', $id_emp);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_master_emp');
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_AGE,$sNopeg);
    }

    //*OA / PA0001

    function organizational_assignment_ov($sNopeg) {
        $data['ov'] = $this->get_a_organizational_assignment($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/organizational_assignment_ov';
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

    function get_a_organizational_assignment($sNopeg) {
        $sQuery = "SELECT eo.id_eorg,eo.BEGDA,eo.BUKRS,eo.WERKS,eo.BTRTL,a.SHORT as AREA,o.STEXT as org_unit,p.STEXT as pos_name,s.STEXT as job_name, o2.SHORT as company
		FROM tm_emp_org eo
		LEFT JOIN tm_master_abbrev a ON eo.BTRTL = a.SHORT AND a.SUBTY=5
        LEFT JOIN tm_master_org o ON eo.ORGEH = o.OBJID AND o.OTYPE='O' AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA
        LEFT JOIN tm_master_org o2 ON eo.BUKRS = o2.OBJID AND o2.OTYPE='O' AND CURDATE() BETWEEN o2.BEGDA AND o2.ENDDA
        LEFT JOIN tm_master_org p ON eo.PLANS = p.OBJID AND p.OTYPE='S' AND CURDATE() BETWEEN p.BEGDA AND p.ENDDA
        LEFT JOIN tm_master_abbrev s ON eo.STELL = s.SHORT AND s.SUBTY=6
        WHERE PERNR='" . $sNopeg . "' ORDER by eo.BEGDA DESC";
        //echo $sQuery;exit;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function organizational_assignment_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/organizational_assignment_fr_new';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";     
            function formatSel2(item){
                    var tmp = item.text;
                    var rtn = tmp.replace(/-/g,"");
                    return rtn;
            };
            jQuery(document).ready(function() {
                 $("#fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        fbukrs: "required",
                        fbtrtl: "required",
                        forgeh: "required",
                        fplans: "required",
                        fstell: "required",
                        fpersg: "required",
                        fpersk: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        fbukrs: "Please enter Company",
                        fbtrtl: "Please enter Area",
                        forgeh: "Please enter Unit",
                        fplans: "Please enter Position",
                        fstell: "Please enter Job",
                        fpersg: "Please enter Employee Group",
                        fpersk: "Please enter Employee Sub Group"
                    },submitHandler: function() {
                        //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_organization_assignment", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
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

    function organizational_assignment_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_organizational_assignment_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/organizational_assignment_fr_update';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";     
            function formatSel2(item){
                    var tmp = item.text;
                    var rtn = tmp.replace(/-/g,"");
                    return rtn;
            };
            jQuery(document).ready(function() {
                 $("#fr_update").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        fbukrs: "required",
                        fbtrtl: "required",
                        forgeh: "required",
                        fplans: "required",
                        fstell: "required",
                        fpersg: "required",
                        fpersk: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        fbukrs: "Please enter Company",
                        fbtrtl: "Please enter Area",
                        forgeh: "Please enter Unit",
                        fplans: "Please enter Position",
                        fstell: "Please enter Job",
                        fpersg: "Please enter Employee Group",
                        fpersk: "Please enter Employee Sub Group"
                    },submitHandler: function() {
                         //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_eorg= $("#id_eorg").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_organization_assignment", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_eorgX":id_eorg},function (text){
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

    function organizational_assignment_upd($id_eorg, $sNopeg, $a) {
        $this->db->where('id_eorg', $id_eorg);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_org', $a);
    }

    function organizational_assignment_new($a) {
        $this->db->insert('tm_emp_org', $a);
    }

    function organizational_assignment_del($id_eorg, $sNopeg) {
        $this->db->where('id_eorg', $id_eorg);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_org');
    }

    function get_tm_organizational_assignment_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_org where pernr='" . $sNopeg . "' AND id_eorg ='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // Other Assignment

    function other_assignment_ov($sNopeg) {
        $data['ov'] = $this->get_a_other_assignment($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/other_assignment_ov';
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

    function get_a_other_assignment($sNopeg) {
        $sQuery = "SELECT * FROM tm_other_assignment where PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function other_assignment_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/other_assignment_fr_new';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";     
            jQuery(document).ready(function() {
                 $("#fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        orgeh_text: "required",
                        plans_text: "required",
                        locat: "required",
                        text1: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        locat: "Please enter Location",
                        orgeh_text: "Please enter Unit",
                        plans_text: "Please enter Position",
                        text1: "Please enter Note"
                    },submitHandler: function() {
                        $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                            if(modalAnswer=="1"){
                                $("#fr_new")[0].submit();
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

    function other_assignment_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_other_assignment_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/other_assignment_fr_update';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";     
            jQuery(document).ready(function() {
                 $("#fr_update").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        orgeh_text: "required",
                        plans_text: "required",
                        locat: "required",
                        text1: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        locat: "Please enter Location",
                        orgeh_text: "Please enter Unit",
                        plans_text: "Please enter Position",
                        text1: "Please enter Note"
                    },submitHandler: function() {
                        $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                            if(modalAnswer=="1"){
                                $("#fr_update")[0].submit();
                            }
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

    function other_assignment_upd($id_otheras, $sNopeg, $a) {
        $this->db->where('id_otheras', $id_otheras);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_other_assignment', $a);
    }

    function other_assignment_new($a) {
        $this->db->insert('tm_other_assignment', $a);
    }

    function other_assignment_del($id_otheras, $sNopeg) {
        $this->db->where('id_otheras', $id_otheras);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_other_assignment');
    }

    function get_tm_other_assignment_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_other_assignment where pernr='" . $sNopeg . "' AND id_otheras='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // 

    function emp_grade_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_grade($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_grade_ov';
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

    function get_a_emp_grade($sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_grade where PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_grade_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_grade_fr_new';
        $data['scriptJS'] .= '
            <script>
            
            var modalAnswer="0";     
            jQuery(document).ready(function() {
                 $("#fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        trfgr: "required",
                        trfst: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        trfgr: "Please enter Grade",
                        trfst: "Please enter Sub Grade"
                    },submitHandler: function() {
                    
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_grade", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
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

    function emp_grade_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_grade_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_grade_fr_update';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";     
            jQuery(document).ready(function() {
                 $("#fr_update").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        trfgr: "required",
                        trfst: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        trfgr: "Please enter Grade",
                        trfst: "Please enter Sub Grade"
                    },submitHandler: function() {
                         //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_egrd= $("#id_egrd").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_grade", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_egrdX":id_egrd},function (text){
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

    function emp_grade_upd($id_egrd, $sNopeg, $a) {
        $this->db->where('id_egrd', $id_egrd);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_grade', $a);
    }

    function emp_grade_new($a) {
        $this->db->insert('tm_emp_grade', $a);
    }

    function emp_grade_del($id_otheras, $sNopeg) {
        $this->db->where('id_otheras', $id_otheras);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_other_assignment');
    }

    function get_tm_emp_grade_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_grade where pernr='" . $sNopeg . "' AND id_egrd='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // tambahan dari ulum

    function emp_date_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_date($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_date_ov';
        $data['scriptJS']='
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

    function get_a_emp_date($sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_date where PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_date_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_date_fr_new';
        $data['scriptJS'] = '
            <script>
            var modalAnswer="0";     
            jQuery(document).ready(function() {
                 $("#fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        tanggal_masuk: "required",
                        tanggal_peg_tetap: "required",
                        tanggal_mpp: "required",
                        tanggal_pensiun: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        tanggal_masuk: "Please enter Tanggal Masuk",
                        tanggal_peg_tetap: "Please enter Tanggal Pegawai Tetap",
                        tanggal_mpp: "Please enter Tanggal MPP",
                        tanggal_pensiun: "Please enter Tanggal Pensiun"
                    },submitHandler: function() {
                    
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_date", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
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

    function emp_date_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_date_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_date_fr_update';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";     
            jQuery(document).ready(function() {
                 $("#fr_update").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        tanggal_masuk: "required",
                        tanggal_peg_tetap: "required",
                        tanggal_mpp: "required",
                        tanggal_pensiun: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        tanggal_masuk: "Please enter Tanggal Masuk",
                        tanggal_peg_tetap: "Please enter Tanggal Pegawai Tetap",
                        tanggal_mpp: "Please enter Tanggal MPP",
                        tanggal_pensiun: "Please enter Tanggal Pensiun"
                    },submitHandler: function() {
                         //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_edat= $("#id_edat").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_date", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_edatX":id_edat},function (text){
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

    function emp_date_upd($id_edat, $sNopeg, $a) {
        $this->db->where('id_edat', $id_edat);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_date', $a);
    }

    function emp_date_new($a) {
        $this->db->insert('tm_emp_date', $a);
    }

    function emp_date_del($id_edat, $sNopeg) {
        $this->db->where('id_edat', $id_edat);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_date');
    }

    function get_tm_emp_date_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_date where pernr='" . $sNopeg . "' AND id_edat='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // Formal Education

    function emp_eduf_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_eduf($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_eduf_ov';
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

    function get_a_emp_eduf($sNopeg) {
        $sQuery = "SELECT e.*, a.STEXT as EDUC, a2.STEXT as PAY FROM tm_emp_educ e " .
                "LEFT JOIN tm_master_abbrev a ON e.SLART = a.SHORT AND a.SUBTY = 1 " .
                "LEFT JOIN tm_master_abbrev a2 ON e.SLABS = a2.SHORT AND a2.SUBTY = 12 " .
                "WHERE PERNR='" . $sNopeg . "' AND AUSBI='Formal' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_eduf_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_eduf_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                endda: "required",
                slart: "required",
                insti: "required",
                sltp1: "required",
                sland: "required",
                emark: "required",
                slabs: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                slart: "Please enter Education",
                insti: "Please enter Institution",
                sltp1: "Please enter Branch Study",
                sland: "Please enter Location",
                emark: "Please enter GPA",
                slabs: "Please enter Payment By"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_new")[0].submit();
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

    function emp_eduf_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_eduf_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_eduf_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                slart: "required",
                insti: "required",
                sltp1: "required",
                sland: "required",
                emark: "required",
                slabs: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                slart: "Please enter Education",
                insti: "Please enter Institution",
                sltp1: "Please enter Branch Study",
                sland: "Please enter Location",
                emark: "Please enter GPA",
                slabs: "Please enter Payment By"
            },submitHandler: function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_update")[0].submit();
                        }
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

    function emp_eduf_upd($id_educ, $sNopeg, $a) {
        $this->db->where('id_educ', $id_educ);
        $this->db->where('AUSBI', 'Formal');
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_educ', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_EDU,$sNopeg);
    }

    function emp_eduf_new($a) {
        $this->db->insert('tm_emp_educ', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_EDU,$a['PERNR']);
    }

    function emp_eduf_del($id_educ, $sNopeg) {
        $this->db->where('id_educ', $id_educ);
        $this->db->where('PERNR', $sNopeg);
        $this->db->where('AUSBI', 'Formal');
        $this->db->delete('tm_emp_educ');
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_EDU,$sNopeg);
    }

    function get_tm_emp_eduf_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_educ where pernr='" . $sNopeg . "' AND AUSBI='Formal' AND id_educ='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // Non Formal Education

    function emp_edunf_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_edunf($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_edunf_ov';
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

    function get_a_emp_edunf($sNopeg) {
        $sQuery = "SELECT e.*, m.STEXT as T_STEXT, m2.STEXT as P_STEXT " .
                "FROM tm_emp_educ e LEFT JOIN tm_master_abbrev m ON e.AUSBI = m.SHORT AND m.SUBTY = 10 " .
                "LEFT JOIN tm_master_abbrev m2 ON e.AUSBI = m2.SHORT AND m2.SUBTY = 12 " .
                "WHERE PERNR='" . $sNopeg . "' AND AUSBI = 'Non Formal' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_edunf_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_edunf_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                endda: "required",
                slart: "required",
                insti: "required",
                sltp1: "required",
                sland: "required",
                slabs: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                slart: "Please enter Education",
                insti: "Please enter Institution",
                sltp1: "Please enter Branch Study",
                sland: "Please enter Location",
                slabs: "Please enter Payment By"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_new")[0].submit();
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

    function emp_edunf_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_edunf_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_edunf_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                slart: "required",
                insti: "required",
                sltp1: "required",
                sland: "required",
                slabs: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                slart: "Please enter Education",
                insti: "Please enter Institution",
                sltp1: "Please enter Branch Study",
                sland: "Please enter Location",
                slabs: "Please enter Payment By"
            },submitHandler: function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_update")[0].submit();
                        }
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

    function emp_edunf_upd($id_educ, $sNopeg, $a) {
        $this->db->where('id_educ', $id_educ);
        $this->db->where('PERNR', $sNopeg);
        $this->db->update('tm_emp_educ', $a);
    }

    function emp_edunf_new($a) {
        $this->db->insert('tm_emp_educ', $a);
    }

    function emp_edunf_del($id_educ, $sNopeg) {
        $this->db->where('id_educ', $id_educ);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_educ');
    }

    function get_tm_emp_edunf_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_educ where pernr='" . $sNopeg . "' AND AUSBI = 'Non Formal' AND id_educ='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // Awards

    function emp_awards_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_awards($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_awards_ov';
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

    function get_a_emp_awards($sNopeg) {
        $sQuery = "SELECT a.*, m.STEXT FROM tm_emp_awards a LEFT JOIN tm_master_abbrev m ON a.AWDTP = m.SHORT AND m.SUBTY = 8 " .
                "WHERE PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_awards_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_awards_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                awdtp: "required",
                text1: "required"
            },
            messages: {
                begda: "Please enter Date",
                awdtp: "Please enter Award Type",
                text1: "Please enter Note"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_new")[0].submit();
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

    function emp_awards_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_awards_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_awards_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                awdtp: "required",
                text1: "required"
            },
            messages: {
                begda: "Please enter Date",
                awdtp: "Please enter Award Type",
                text1: "Please enter Note"
            },submitHandler: function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_update")[0].submit();
                        }
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

    function emp_awards_upd($id_awards, $sNopeg, $a) {
        $this->db->where('id_awards', $id_awards);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_awards', $a);
    }

    function emp_awards_new($a) {
        $this->db->insert('tm_emp_awards', $a);
    }

    function emp_awards_del($id_awards, $sNopeg) {
        $this->db->where('id_awards', $id_awards);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_awards');
    }

    function get_tm_emp_awards_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_awards where pernr='" . $sNopeg . "' AND id_awards='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // GRIEVANCES

    function emp_grievances_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_grievances($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_grievances_ov';
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

    function get_a_emp_grievances($sNopeg) {
        $sQuery = "SELECT g.*, m.STEXT " .
                "FROM tm_emp_grievances g LEFT JOIN tm_master_abbrev m ON g.SUBTY = m.SHORT AND m.SUBTY = 9 " .
                "WHERE PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_grievances_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_grievances_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                endda: "required",
                subty: "required",
                text1: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                subty: "Please enter Type",
                text1: "Please enter Note"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_new")[0].submit();
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

    function emp_grievances_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_grievances_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_grievances_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                subty: "required",
                text1: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                subty: "Please enter Type",
                text1: "Please enter Note"
            },submitHandler: function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_update")[0].submit();
                        }
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

    function emp_grievances_upd($id_grievances, $sNopeg, $a) {
        $this->db->where('id_grievances', $id_grievances);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_grievances', $a);
    }

    function emp_grievances_new($a) {
        $this->db->insert('tm_emp_grievances', $a);
    }

    function emp_grievances_del($id_grievances, $sNopeg) {
        $this->db->where('id_grievances', $id_grievances);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_grievances');
    }

    function get_tm_emp_grievances_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_grievances where pernr='" . $sNopeg . "' AND id_grievances='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // MEDICAL

    function emp_medical_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_medical($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_medical_ov';
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

    function get_a_emp_medical($sNopeg) {
        $sQuery = "SELECT m.*, a.STEXT " .
                "FROM tm_emp_medical m LEFT JOIN tm_master_abbrev a ON m.SUBTY = a.SHORT AND a.SUBTY = 11 " .
                "WHERE PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_medical_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_medical_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                subty: "required",
                text1: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                subty: "Please enter Type",
                text1: "Please enter Note"
            },submitHandler: function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_new")[0].submit();
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

    function emp_medical_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_medical_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_medical_fr_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                subty: "required",
                text1: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                subty: "Please enter Type",
                text1: "Please enter Note"
            },submitHandler: function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){
                            $("#fr_update")[0].submit();
                        }
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

    function emp_medical_upd($id_medical, $sNopeg, $a) {
        $this->db->where('id_medical', $id_medical);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_medical', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_MED,$sNopeg);
    }

    function emp_medical_new($a) {
        $this->db->insert('tm_emp_medical', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_MED,$a['PERNR']);
    }

    function emp_medical_del($id_medical, $sNopeg) {
        $this->db->where('id_medical', $id_medical);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_medical');
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_MED,$sNopeg);
    }

    function get_tm_emp_medical_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_medical where pernr='" . $sNopeg . "' AND id_medical='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    // tambahan andi

    function emp_compt_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_compt($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_compt_ov';
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

    function get_a_emp_compt($sNopeg) {
		$aRes = array();
		$sOrg = "";
		$aMapPernr = $this->get_mapping_pernr_single($sNopeg);

		if($aMapPernr){
			$sOrg = $aMapPernr['ORGEH'];
		}
		
		if($sOrg<>""){
			$sPrefix = substr($sOrg,1,2);
			
			$sQuery = "SELECT e.*, m.SHORT FROM tm_emp_compt e " .
					"LEFT JOIN tm_master_compt m ON e.COMPT = m.OBJID AND OTYPE LIKE 'C%' AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
					"WHERE PERNR='" . $sNopeg . "' AND e.COMPT LIKE '4".$sPrefix."%' ".
					"ORDER by e.BEGDA DESC, OTYPE ASC, OBJID ASC;";
			$oRes = $this->db->query($sQuery);
			$aRes = $oRes->result_array();
			$oRes->free_result();
		}
        return $aRes;
    }

    function get_tm_emp_compt_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_compt WHERE PERNR='" . $sNopeg . "' AND id_ecom='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    function emp_compt_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_compt_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                endda: "required",
                compt: "required",
                coval: "required",
                insti: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                compt: "Please enter Competency",
                coval: "Please enter Value",
                insti: "Please enter Institution"
            },submitHandler: function() {
                    
                    
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        compt = $("#compt").val();
                        insti = $("#insti").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_compt", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"comptX":compt},function (text){
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

    function emp_compt_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_compt_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_compt_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                compt: "required",
                coval: "required",
                insti: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                compt: "Please enter Competency",
                coval: "Please enter Value",
                insti: "Please enter Institution"
            },submitHandler: function() {
                    
                         //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_ecom= $("#id_ecom").val();
                        compt= $("#compt").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_compt", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_ecomX":id_ecom,"comptX":compt},function (text){
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

    function emp_compt_upd($id_egrd, $sNopeg, $a) {
        $this->db->where('id_ecom', $id_egrd);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_compt', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERNR,$sNopeg);
    }

    function emp_compt_new($a) {
        $this->db->insert('tm_emp_compt', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERNR,$a['PERNR']);
    }

    function emp_compt_del($id_ecom, $sNopeg) {
        $this->db->where('id_ecom', $id_ecom);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_compt');
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERNR,$sNopeg);
    }
	
	// competency holding

    function emp_compt_holding_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_compt_holding($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_compt_holding_ov';
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

    function get_a_emp_compt_holding($sNopeg) {
		$sQuery = "SELECT e.*, m.SHORT FROM tm_emp_compt e " .
				"LEFT JOIN tm_master_compt m ON e.COMPT = m.OBJID AND OTYPE LIKE 'C%' AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
				"WHERE PERNR='" . $sNopeg . "' AND e.COMPT LIKE '400%' ".
				"ORDER by e.BEGDA DESC, OTYPE ASC, OBJID ASC;";
		$oRes = $this->db->query($sQuery);
		$aRes = $oRes->result_array();
		$oRes->free_result();

        return $aRes;
    }

    function get_tm_emp_compt_holding_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_compt WHERE PERNR='" . $sNopeg . "' AND id_ecom='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    function emp_compt_holding_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_compt_holding_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                endda: "required",
                compt: "required",
                coval: "required",
				insti: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                compt: "Please enter Competency",
                coval: "Please enter Value",
                insti: "Please enter Institution"
            },submitHandler: function() {
                    
                    
                    //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        compt = $("#compt").val();
						insti = $("#insti").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_compt", { "pernrX": pernr,"begdaX": begda, "enddaX": endda, "comptX": compt },function (text){
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

    function emp_compt_holding_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_compt_holding_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_compt_holding_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                compt: "required",
                coval: "required",
                insti: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                compt: "Please enter Competency",
                coval: "Please enter Value",
                insti: "Please enter Institution"
            },submitHandler: function() {
                    
                         //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        compt = $("#compt").val();
                        id_ecom= $("#id_ecom").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_grade", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_ecomX":id_ecom, "comptX": compt},function (text){
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

    function emp_compt_holding_upd($id_egrd, $sNopeg, $a) {
        $this->db->where('id_ecom', $id_egrd);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_compt', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERNR,$sNopeg);
    }

    function emp_compt_holding_new($a) {
        $this->db->insert('tm_emp_compt', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERNR,$a['PERNR']);
    }

    function emp_compt_holding_del($id_ecom, $sNopeg) {
        $this->db->where('id_ecom', $id_ecom);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_compt');
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERNR,$sNopeg);
    }
	
	// Performace

    function emp_perf_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_perf($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_perf_ov';
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

    function get_a_emp_perf($sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_perf where PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_tm_emp_perf_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_perf where PERNR='" . $sNopeg . "' AND id_perf='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }

    function emp_perf_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_perf_fr_new';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_new").validate({
            rules: {
                begda: "required",
                endda: "required",
                nilai: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                nilai: "Please enter Score"
            },submitHandler: function() {
            //check date
                pernr = $("#pernr").val();
                begda = $("#begda").val();
                endda = $("#endda").val();
                $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_perf", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
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

    function emp_perf_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_emp_perf_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_perf_update';
        $data['scriptJS'] .='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#fr_update").validate({
            rules: {
                begda: "required",
                endda: "required",
                nilai: "required"
            },
            messages: {
                begda: "Please enter Begin Date",
                endda: "Please enter End Date",
                nilai: "Please enter Score"
            },submitHandler: function() {
                    
                    //check date
                pernr = $("#pernr").val();
                begda = $("#begda").val();
                endda = $("#endda").val();
                id_perf= $("#id_perf").val();
                $.post( "'.base_url().'index.php/employee/update_check_time_constraint_perf", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_perfX":id_perf},function (text){
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

    function emp_perf_upd($id_perf, $sNopeg, $a) {
        $this->db->where('id_perf', $id_perf);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_perf', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERF,$sNopeg);
    }

    function emp_perf_new($a) {
        $this->db->insert('tm_emp_perf', $a);
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERF,$a['PERNR']);
    }

    function emp_perf_del($id_perf, $sNopeg) {
        $this->db->where('id_perf', $id_perf);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_perf');
        $this->load->model('gen_machine');
        $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PERF,$sNopeg);
    }

    function get_anak_perusahaan() {
    /*    $sQuery = "SELECT o.OBJID,o.SHORT,o.STEXT FROM tm_master_relation mr
JOIN tm_master_org o ON mr.OBJID=o.OBJID AND o.OTYPE='O'
WHERE (SOBID='10100019' AND SUBTY='A002' 
AND CURDATE() BETWEEN mr.BEGDA AND mr.ENDDA
AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA) OR o.OBJID=10100001
ORDER BY PRIOX";
	*/	
		$aOrg = $this->common->get_a_org_auth();
		$sOrg = implode(",",$aOrg);

		$sQuery = "SELECT l.OBJID,o.SHORT,o.STEXT ".
				  "FROM tm_org_level l ".
				  "JOIN tm_master_org o ON o.OBJID = l.OBJID AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				  "WHERE CURDATE() BETWEEN l.BEGDA AND l.ENDDA ".
				  "AND l.OBJID IN(".$sOrg.") ".
				  "ORDER BY l.LEVEL;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function add_new_employee($sComp, $oldNik, $begda, $endda) {
        //gen pernr
        $prefixComp = substr($sComp, 0, 3);
        $configShort = $this->orgchart_m->get_short_by_prefix("ORG", $prefixComp);
        $n_pernr = $this->orgchart_m->get_config_by_short("PERNR", $configShort);
        $this->orgchart_m->add_config_by_short("PERNR", $configShort, $n_pernr);
        $this->saving_map_pernr($n_pernr, $oldNik, $sComp, $begda);
        return $n_pernr;
    }

    function saving_master_emp($pernr, $nama, $begda) {
        $sQuery = "INSERT INTO tm_master_emp (`PERNR`,`CNAME`,`BEGDA`,`ENDDA`) values ('$pernr','$nama','$begda','9999-12-31')";
        $this->db->query($sQuery);
    }

    function saving_map_pernr($pernr, $nik, $orgeh, $begda) {
        $sQuery = "INSERT INTO tm_mapping_pernr (`PERNR`,`NIK`,`ORGEH`,`BEGDA`,`ENDDA`) values ('$pernr','$nik','$orgeh','$begda','9999-12-31')";
        $this->db->query($sQuery);
    }

    function saving_emp_org($pernr, $plans, $orgeh, $begda) {
        $sQuery = "INSERT INTO tm_emp_org (`PERNR`,`PLANS`,`ORGEH`,`BEGDA`,`ENDDA`) values ('$pernr','$plans','$orgeh','$begda','9999-12-31')";
        $this->db->query($sQuery);
    }

    function get_sub_org($iOrgUnit, $iAnper = '') {
        $sQuery = "SELECT m.* " .
                "FROM tm_master_relation r " .
                "JOIN tm_master_org m ON m.OBJID = r.OBJID AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
                "WHERE r.SUBTY = 'A002' AND r.OTYPE = 'O' AND r.SCLAS = 'O' AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA " .
                "AND r.SOBID = " . $iOrgUnit . " AND m.OBJID <> " . ($iAnper == "" ? 99 : $iAnper) . " " .
                "ORDER BY r.PRIOX;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_unit_desc($iOrg) {
        $sQuery = "SELECT OBJID as id, STEXT as text FROM tm_master_org WHERE OBJID = '" . $iOrg . "' AND OTYPE = 'O' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_plans_desc($iPlans) {
        $sQuery = "SELECT OBJID as id, STEXT as text FROM tm_master_org WHERE OBJID = '" . $iPlans . "' AND OTYPE = 'S' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function get_compt($sNopeg="") {
		$aRtn = array();
		$sOrg = "";
		$aMapPernr = $this->get_mapping_pernr_single($sNopeg);

		if($aMapPernr){
			$sOrg = $aMapPernr['ORGEH'];
		}

		if($sOrg<>""){
			$sPrefix = substr($sOrg,1,2);

			$sQuery = "SELECT OBJID as id, CONCAT(STEXT,' (',SHORT,')') as text FROM tm_master_compt " .
					"WHERE OTYPE like 'C%' AND OBJID LIKE '4".$sPrefix."%' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

			$oRes = $this->db->query($sQuery);
			$aRtn = $oRes->result_array();
			$oRes->free_result();
		}
        return $aRtn;
		
    }
	
	function get_compt_holding() {
        $sQuery = "SELECT OBJID as id, CONCAT(STEXT,' (',SHORT,')') as text FROM tm_master_compt " .
                "WHERE OTYPE like 'C%' AND OBJID LIKE '400%' AND CURDATE() BETWEEN BEGDA AND ENDDA;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    //*OA / PA0001  (OLD)

    function organizational_assignment_old_ov($sNopeg) {
        $data['ov'] = $this->get_a_organizational_assignment_old($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/organizational_assignment_old_ov';
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

    function get_a_organizational_assignment_old($sNopeg) {
        $sQuery = "SELECT eo.id_eorg,eo.BEGDA,eo.BUKRS,eo.WERKS,eo.BTRTL,eo.ORGEH,eo.PLANS,eo.STELL, o2.SHORT as company
		FROM tm_emp_org_old eo
        LEFT JOIN tm_master_org o2 ON eo.BUKRS = o2.OBJID AND o2.OTYPE='O' AND CURDATE() BETWEEN o2.BEGDA AND o2.ENDDA
        WHERE PERNR='" . $sNopeg . "' ORDER by eo.BEGDA DESC";
        //echo $sQuery;exit;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function organizational_assignment_old_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/organizational_assignment_old_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";     
            function formatSel2(item){
                    var tmp = item.text;
                    var rtn = tmp.replace(/-/g,"");
                    return rtn;
            };
            jQuery(document).ready(function() {
                 $("#fr_new").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        fbukrs: "required",
                        fbtrtl: "required",
                        forgeh: "required",
                        fplans: "required",
                        fstell: "required",
                        fpersg: "required",
                        fpersk: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        fbukrs: "Please enter Company",
                        fbtrtl: "Please enter Area",
                        forgeh: "Please enter Unit",
                        fplans: "Please enter Position",
                        fstell: "Please enter Job",
                        fpersg: "Please enter Employee Group",
                        fpersk: "Please enter Employee Sub Group"
                    },submitHandler: function() {
                        //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        $.post( "'.base_url().'index.php/employee/insert_check_time_constraint_organization_assignment", { "pernrX": pernr,"begdaX": begda, "enddaX": endda },function (text){
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

    function organizational_assignment_old_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_tm_organizational_assignment_old_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/organizational_assignment_old_fr_update';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";     
            function formatSel2(item){
                    var tmp = item.text;
                    var rtn = tmp.replace(/-/g,"");
                    return rtn;
            };
            jQuery(document).ready(function() {
                 $("#fr_update").validate({
                    rules: {
                        begda: "required",
                        endda: "required",
                        fbukrs: "required",
                        fbtrtl: "required",
                        forgeh: "required",
                        fplans: "required",
                        fstell: "required",
                        fpersg: "required",
                        fpersk: "required"
                    },
                    messages: {
                        begda: "Please enter Begin Date",
                        endda: "Please enter End Date",
                        fbukrs: "Please enter Company",
                        fbtrtl: "Please enter Area",
                        forgeh: "Please enter Unit",
                        fplans: "Please enter Position",
                        fstell: "Please enter Job",
                        fpersg: "Please enter Employee Group",
                        fpersk: "Please enter Employee Sub Group"
                    },submitHandler: function() {
                         //check date
                        pernr = $("#pernr").val();
                        begda = $("#begda").val();
                        endda = $("#endda").val();
                        id_eorg= $("#id_eorg").val();
                        $.post( "'.base_url().'index.php/employee/update_check_time_constraint_organization_assignment", { "pernrX": pernr,"begdaX": begda, "enddaX": endda ,"id_eorgX":id_eorg},function (text){
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

    function organizational_assignment_old_upd($id_eorg, $sNopeg, $a) {
        $this->db->where('id_eorg', $id_eorg);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_org_old', $a);
    }

    function organizational_assignment_old_new($a) {
        $this->db->insert('tm_emp_org_old', $a);
    }

    function organizational_assignment_old_del($id_eorg, $sNopeg) {
        $this->db->where('id_eorg', $id_eorg);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_org_old');
    }

    function get_tm_organizational_assignment_old_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_org_old where pernr='" . $sNopeg . "' AND id_eorg ='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
	
	//*NOTES

    function emp_note_ov($sNopeg) {
        $data['ov'] = $this->get_a_emp_note($sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['aCon'] = $data;
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_note_ov';
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

    function get_a_emp_note($sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_note WHERE PERNR='" . $sNopeg . "' ORDER by BEGDA DESC";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }

    function emp_note_fr_new($sNopeg) {
        $data = $this->get_default_data(array(), $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_note_fr_new';
        $data['scriptJS'] .='
            <script>
            var modalAnswer="0";     
            jQuery(document).ready(function() {
                 $("#fr_new").validate({
                    rules: {
                        begda: "required",
                        addby: "required",
                        notes: "required"
                    },
                    messages: {
                        begda: "Please enter Date",
                        addby: "Please enter Add By",
                        notes: "Please enter Notes"
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

    function emp_note_fr_update($iSeq, $sNopeg) {
        $data['frm'] = $this->get_emp_note_row($iSeq, $sNopeg);
        $data = $this->get_default_data($data, $sNopeg);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/home';
        $data['emp_view'] = 'employee/emp_note_fr_update';
        $data['scriptJS'] .= '
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                 $("#fr_update").validate({
                    rules: {
                        begda: "required",
                        addby: "required",
                        notes: "required"
                    },
                    messages: {
                         begda: "Please enter Date",
                        addby: "Please enter Add By",
                        notes: "Please enter Notes"
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

    function emp_note_upd($id_note, $sNopeg, $a) {
        $this->db->where('id_note', $id_note);
        $this->db->where('pernr', $sNopeg);
        $this->db->update('tm_emp_note', $a);
    }

    function emp_note_new($a) {
        $this->db->insert('tm_emp_note', $a);
    }

    function emp_note_del($id_note, $sNopeg) {
        $this->db->where('id_note', $id_note);
        $this->db->where('PERNR', $sNopeg);
        $this->db->delete('tm_emp_note');
    }

    function get_emp_note_row($iSeq, $sNopeg) {
        $sQuery = "SELECT * FROM tm_emp_note where pernr='" . $sNopeg . "' AND id_note ='" . $iSeq . "'";
        $oRes = $this->db->query($sQuery);
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow;
    }
	
	// New Employee

    function employee_fr_new() {
        $data['anak_perusahaan'] = $this->get_anak_perusahaan();
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'employee/emp_fr_new';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['scriptJS'] = '
            <script>
            var modalAnswer="0";
            jQuery(document).ready(function() {
                $("#fComp").select2().on("select2-selecting", function(e) {
                    $("#fPos").select2("val","");
                });
                $("#emp_new").validate({
                    rules: {
                        username: "required",
                        password: "required",
                        cname: "required",
                        gbdat: "required",
                        gblnd: "required",
                        fNIK: "required"
                    },
                    messages: {
                        username: "Please enter Username",
                        password: "Please enter Password",
                        cname: "Please enter Employee Name",
                        gbdat: "Please enter Employee Name",
                        gblnd: "Please enter Employee Name",
                        fNIK: "Please enter Employee Name"
                    },submitHandler: function() {
                            $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                                if(modalAnswer=="1"){
                                    $("#emp_new")[0].submit();
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

    function employee_fr_new_2($sbukrs) {
        $data['base_url'] = $this->config->item('base_url');
        $aBtrtl = $this->common->get_abbrev(5);
        //	$sOrgeh = $this->get_unit_cb2('11000001');
        $aPlans = $this->common->get_abbrev(1);
        $aStell = $this->common->get_abbrev(6);
        $aPersg = $this->common->get_abbrev(3);
        $aPersk = $this->common->get_abbrev(4);
        $sBtrtl = json_encode($aBtrtl);
        //	$sOrgeh = json_encode($aOrgeh);
        $sPlans = json_encode($aPlans);
        $sStell = json_encode($aStell);
        $sPersg = json_encode($aPersg);
        $sPersk = json_encode($aPersk);
        $data['view'] = 'employee/emp_fr_new_2';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['scriptJS'] = '
            <script>
            var modalAnswer="0";            
            function formatSel2(item){
                    var tmp = item.text;
                    var rtn = tmp.replace(/-/g,"");
                    return rtn;
            };
            jQuery(document).ready(function() {
                $("#fbtrtl").select2({
                        data: ' . $sBtrtl . '
                        ,dropdownAutoWidth: true
                });
                $("#forgeh").select2({
                        minimumInputLength: 1,
                        dropdownAutoWidth: true,
                        formatSelection : formatSel2,
                        ajax: {
                                url: "' . base_url() . 'index.php/employee/get_unit_cb/",
                                dataType: "json",
                                type: "POST",
                                data: function (term, page) {
                                        return {
                                                q: term,
                                                bukrs : "' . $sbukrs . '"
                                        };
                                },
                                results: function (data, page) {
                                        return {results: data};
                                }
                        },
                        initSelection: function(element, callback) {
                                var id;
                                id = $(element).val();
                                if (id!=="") {
                                        return $.ajax({
                                                type: "POST",
                                                url: "' . base_url() . 'index.php/employee/get_unit_desc/",
                                                dataType: "json",
                                                data: { id: id },
                                                success: function(data){
                                                        //results: data.results;
                                                }
                                        }).done(function(data) {
                                                var results;
                                                results = [];
                                                results.push({
                                                    id: data.id,
                                                    text: data.text
                                                });
                                                callback(results[0]);
                                        });
                                }
                        }
                });
                $("#fplans").select2({
                        minimumInputLength: 1,
                        dropdownAutoWidth: true,
                        ajax: {
                                url: "' . base_url() . 'index.php/employee/get_plans/",
                                dataType: "json",
                                type: "POST",
                                data: function (term, page) {
                                        return {
                                                q: term,
                                                orgeh :$("#forgeh").select2("val")
                                        };
                                },
                                results: function (data, page) {
                                        return {results: data};
                                }
                        },
                        initSelection: function(element, callback) {
                                var id;
                                id = $(element).val();
                                if (id!=="") {
                                        return $.ajax({
                                                type: "POST",
                                                url: "' . base_url() . 'index.php/employee/get_plans_desc/",
                                                dataType: "json",
                                                data: { id: id },
                                                success: function(data){
                                                        //results: data.results;
                                                }
                                        }).done(function(data) {
                                                var results;
                                                results = [];
                                                results.push({
                                                    id: data.id,
                                                    text: data.text
                                                });
                                                callback(results[0]);
                                        });
                                }
                        }
                });
                $("#fstell").select2({
                        data: ' . $sStell . '
                        ,dropdownAutoWidth: true
                });
                $("#fpersg").select2({
                        data: ' . $sPersg . '
                        ,dropdownAutoWidth: true
                });
                $("#fpersk").select2({
                        data: ' . $sPersk . '
                        ,dropdownAutoWidth: true
                });
                

                 $("#emp2_update").validate({
                    rules: {
                        fbtrtl: "required",
                        forgeh: "required",
                        fplans: "required",
                        fstell: "required",
                        fpersg: "required",
                        fpersk: "required"
                    },
                    messages: {
                        fbtrtl: "Please enter Area",
                        forgeh: "Please enter Unit",
                        fplans: "Please enter Position",
                        fstell: "Please enter Job",
                        fpersg: "Please enter Employee Group",
                        fpersk: "Please enter Employee Sub Group"
                    },submitHandler: function() {
                        $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                            if(modalAnswer=="1"){
                                $("#emp2_update")[0].submit();
                            }
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

    function get_mapping_pernr_single($sNopeg) {
        $sQuery = "SELECT * FROM tm_mapping_pernr where PERNR='" . $sNopeg . "' AND CURDATE() BETWEEN BEGDA AND ENDDA";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->row_array();
        $oRes->free_result();
        return $aRes;
    }

    function update_mapping_pernr($pernr, $fNik) {
        $sQuery = "UPDATE tm_mapping_pernr SET NIK='$fNik' WHERE PERNR='$pernr'";
        $this->db->query($sQuery);
    }
    
    
    function check_time_constraint_personal_data($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_master_emp where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            //
            $sQuery="SELECT * from tm_master_emp where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_master_emp where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_emp<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    
    function check_time_constraint_organization_assignment($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_org where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            //
            $sQuery="SELECT * from tm_emp_org where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_eorg<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_org where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_eorg<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    
    function check_time_constraint_grade($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_grade where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            //
            $sQuery="SELECT * from tm_emp_grade where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_egrd<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_grade where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_egrd<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }

    
    function check_time_constraint_date($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_date where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            //
            $sQuery="SELECT * from tm_emp_date where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_edat<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_date where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_edat<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }
    
    function check_time_constraint_compt($pernr,$begda,$endda,$compt,$type="INSERT",$id=0){
        if($type=="INSERT"){
            $sQuery="SELECT * from tm_emp_compt where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND COMPT ='$compt'";
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
            //
            $sQuery="SELECT * from tm_emp_compt where PERNR='$pernr'  AND COMPT='$compt' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_ecom<>'$id'";
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_compt where PERNR='$pernr'  AND COMPT = '$compt' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_ecom<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }
    
    
    function check_time_constraint_perf($pernr,$begda,$endda,$type="INSERT",$id=0){
        if($type=="INSERT"){
//            (BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_perf where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda'))";
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
            //
            $sQuery="SELECT * from tm_emp_perf where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda')  OR (BEGDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_perf<>'$id'";
            echo $sQuery;
            $oRes = $this->db->query($sQuery);
            $nRows = $oRes->num_rows();
            if($nRows==0){
                return "null";
            }else {
                return "your input had time constraint, please back and check your data period. Thank you.";
            }
        }else if($type=="CHECK"){
            //(BEGDA<='$begda' AND ENDDA>='$begda') OR 
            $sQuery="SELECT * from tm_emp_perf where PERNR='$pernr' AND ((BEGDA<='$begda' AND ENDDA>='$begda') OR (BEGDA<='$endda' AND ENDDA>='$endda')) AND id_perf<>'$id'";
            $oRes = $this->db->query($sQuery);
            return $oRes;
        }
    }
    
    function terminate($pernr,$terminate_date,$sPHK){
        //001
        $a['PERNR'] = $pernr = $this->input->post('pernr');
        $a['BEGDA'] = $terminate_date;
        $a['ENDDA'] = "9999-12-31";
        $a['PERSG'] = 'X';
        $a['PERSK'] = $sPHK;

        $oRes = $this->check_time_constraint_organization_assignment($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
        if($oRes->num_rows()>0){
            $sQuery="DELETE FROM tm_emp_org where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
            $this->db->query($sQuery);
            $oRes = $this->check_time_constraint_organization_assignment($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
        }
        if($oRes->num_rows()==1){
            $aRow=$oRes->row_array();
            $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
            $oRes=$this->db->query($sQuery);
            $aX=$oRes->row_array();
            $sQuery="UPDATE tm_emp_org SET ENDDA='".$aX['ival']."' WHERE id_eorg='".$aRow['id_eorg']."';";
            $this->db->query($sQuery);
        }
        $this->organizational_assignment_new($a);
        //mapping_pernr
        $sQuery="UPDATE tm_mapping_pernr SET endda='$terminate_date' WHERE PERNR='$pernr'";
        $this->db->query($sQuery);
    }
    

}

?>