<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of splan_m
 *
 * @author Garuda
 */
class splan_m extends CI_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }
    
    function get_variant_data($iSeq=0){
        if(!empty($iSeq))$this->db->where('id',$iSeq);
        $oRes=$this->db->get('tm_ao_varm');
        if(!empty($iSeq))$aRes = $oRes->row_array();
        else $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    function get_variant_ddata($iSeq){
        $sQuery="SELECT d.*,CNAME FROM tm_ao_vard d JOIN tm_master_emp me ON d.objid=me.pernr where d.idm='$iSeq' ORDER BY d.idd";
        $oRes=$this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        return $aRes;
    }
    function variant_fr_new() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'splan/variant_fr_new';
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
        $data['scriptJS']='
<script>
var modalAnswer="0";
jQuery(document).ready(function() {
    $("#user_fr_new").validate({
            rules: {
                name: "required"
            },
            messages: {
                name: "Please enter Variant Name"
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
	
    function variant_fr_update($iSeq) {
        $data['frm'] = $this->get_variant_data($iSeq);
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'splan/variant_fr_update';
        $data['table_detail'] = $this->get_variant_ddata($iSeq);
        $data['externalCSS'] ='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
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

    $("#iNopeg").select2({
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        ajax: {
                url: "'.base_url().'index.php/employee/fetch_emp/",
                dataType: "json",
                type: "POST",
                data: function (term, page) {
                        return {
                                q: term
                        };
                },
                results: function (data, page) {
                        return {results: data};
                },initSelection: function(element, callback) {
                }
        }
});

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

    function variant_ov() {
        $data['table'] = $this->get_variant_data();
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'splan/manage_var_ov';
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

    function get_perusahaan($sNopeg) {
        $sQuery = "SELECT STEXT FROM tm_mapping_pernr mp JOIN tm_master_org mo ON mp.ORGEH=mo.OBJID AND mo.OTYPE='O' 
WHERE mp.PERNR='$sNopeg'";
        $oRes = $this->db->query($sQuery);
        if ($oRes->num_rows() == 0)
            return "-";
        $aRow = $oRes->row_array();
        return $aRow['STEXT'];
    }
    
    function get_mapping_pernr($sPrsh) {
        $sQuery = "SELECT p.PERNR as id, m.CNAME as text " .
                "FROM tm_mapping_pernr p " .
                "JOIN tm_master_emp m ON m.PERNR = p.PERNR AND CURDATE() BETWEEN m.BEGDA AND m.ENDDA " .
                "JOIN (SELECT PERNR FROM
(SELECT objid FROM tm_ao_vard group by objid) nop
JOIN tm_mapping_pernr mp ON nop.objid=mp.PERNR GROUP BY PERNR) x ON p.PERNR=x.PERNR ".
                "WHERE ORGEH = '" . $sPrsh . "' AND  CURDATE() BETWEEN p.BEGDA AND p.ENDDA " .
                "ORDER BY CNAME ASC;";

        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();

        return $aRes;
    }

    function opsi_1() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['scriptJS'] = '';
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
        
        
        
        $sQuery = " SELECT mo.* FROM
(SELECT * FROM
(SELECT objid FROM tm_ao_vard group by objid) nop
JOIN tm_mapping_pernr mp ON nop.objid=mp.PERNR
GROUP BY mp.ORGEH) o
JOIN tm_master_org mo ON o.ORGEH=mo.OBJID and mo.OTYPE='O';";
        $oRes = $this->db->query($sQuery);
        $aPrshs = $oRes->result_array();
        $aRtn = null;
        $i = 0;
        if ($aPrshs) {
            foreach ($aPrshs as $aPrsh) {
                $aRtn[$i]["text"] = $aPrsh["STEXT"];
                $aEmps = $this->get_mapping_pernr($aPrsh["OBJID"]);
                if ($aEmps) {
                    foreach ($aEmps as $aEmp) {
                        $i++;
                        $aRtn[$i]["id"] = $aEmp["id"];
                        $aRtn[$i]["text"] = $aEmp["id"] . " | " . $aEmp["text"];
                    }
                }else{
			$aRtn[$i]=null;
			$i--;
		}
                $i++;
            }
        }
        
        $sEmp = json_encode($aRtn);
        
        
        $data['externalCSS'] .='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'js/jquery.validate.min.js"></script>';
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
                            $("#emp1").select2({
                                data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp2").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp3").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });

                            $("#emp4").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp5").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp6").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp7").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp8").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp9").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                            $("#emp10").select2({
                                    data : '.$sEmp.',
                                formatResult : format,
                                formatSelection : formatSel,
                                dropdownAutoWidth: true
                            });
                });
                </script>
                ';
        $sQuery = "SELECT * FROM tm_ao_varm m JOIN tm_ao_vard d ON m.id=d.idm order by m.id,d.idd";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $name = "";
        $aRet = array();
        $n = -1;
        for ($i = 0; $i < count($aRes); $i++) {
            if ($aRes[$i]['name'] != $name) {
                $n++;
                $name = $aRes[$i]['name'];
                $aRet[$n]['name'] = $name;
                $aRet[$n]['id'] = $aRes[$i]['idm'];
            }
            $aRet[$n]['data'][] = $aRes[$i]['objid'];
        }
        $data['param'] = $aRet;
        if (!empty($aRet)  ) {
            $data['scriptJS'] = $data['scriptJS'].'
                <script>
                    $(document).ready(function() {
                $("#variant").change(function(){
                    var val = $(this).val();';
            
                    for($i=0;$i<count($aRet);$i++){
                        $data['scriptJS']=$data['scriptJS']. "if(val==".$aRet[$i]['id']."){
                            $('#position').val('".$aRet[$i]['name']."');";
                        for($j=0;$j<count($aRet[$i]['data']) ;$j++){
                            $data['scriptJS']=$data['scriptJS'].'$("#emp'.($j+1).'").select2("val","'.$aRet[$i]['data'][$j].'");';
                        }
                        $data['scriptJS']=$data['scriptJS']. "}";
                    }
                    $data['scriptJS'] =$data['scriptJS'].'}); ';
                    $data['scriptJS'] =$data['scriptJS'].'});
                </script>
                ';
        }
        return $data;
    }

}

?>