<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of orgchart
 *
 * @author Garuda
 */
class orgchart extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        $this->load->model('orgchart_m');
    }

    function tree($sStaff = 'N') {

        $data = $this->orgchart_m->home();
        $data['sStaff'] = $sStaff;

        $data['externalCSS'] = '<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/fuelux/css/tree-style.css" />';
        $data['externalCSS'].='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script src="' . base_url() . 'assets/fuelux/js/tree.min.js"></script>';
        $data['externalJS'] .= '<script src="' . base_url() . 'assets/jquery.blockUI.js"></script>';
        $data['externalJS'] .= '<script src="' . base_url() . 'js/select2.min.js"></script>';
//        $data['aStell'] = $this->orgchart_m->get_stell();
//        $data['aFam'] = $this->orgchart_m->get_job_family();
        $data['scriptJS'] = '<script>
            jQuery(document).ready(function() {
                $("#cStell").select2();
                $("#cFam").select2();
            });
				var TreeView = function () {
					return {

						init : function(){
							var DataSourceTree = function (options) {
								this.url = options.url;
							}

							DataSourceTree.prototype.data = function (options, callback) {
								var self = this;
								var $data = null;
								var param = null;
								var ltype = null;

								if (!("name" in options) && !("type" in options)) {
									param = 0;//load the first level
									ltype = "O";
								}
								else if ("type" in options && options.type == "folder") {
									if ("additionalParameters" in options && "children" in options.additionalParameters) {
										param = options.additionalParameters["id"];
										ltype = options.additionalParameters["ltype"];
									}
								}

								if (param != null) {
									$.ajax({
										url: this.url,
										data: { id: param, staff: \'' . $sStaff . '\', ltype : ltype },
										type: \'POST\',
										dataType: \'json\',
										success: function (response) {
											if (response.status == "OK")
												callback({ data: response.data });
										},
										error: function (response) {
											console.log(response);
										}
									})
								}
							};

							$(\'#FlatTree\').tree({
								dataSource: new DataSourceTree({ url: "' . base_url() . 'index.php/orgchart/loadData" }),
								loadingHTML: \'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>\',
                                                                cacheItems: false,
                                                                folderSelect: false,
                                                                multiSelect: false
							});
						}
					};
				}();
                                function blockPage(text){   
                                    if(text==undefined || text==""){
                                        text="Loading..."; 
                                    }
                                    $.blockUI({ message: \'<img width="200px" src="' . base_url() . 'img/loader.gif" /><h1>\'+text+ \'</h1>\',   
                                        css: {   
                                        border: \'none\',  
                                        width: \'240px\',  
                                        \'-webkit-border-radius\': \'10px\',   
                                        \'-moz-border-radius\': \'10px\',   
                                        opacity: .9  
                                        }   
                                    });   
                                    return false;  
                                }  
                                
                                function ajaxCompetency(iPosition){
                                    $( "#competency" ).load("' . base_url() . 'index.php/orgchart/load_competency/"+iPosition);
                                }
                                function ajaxAddCompetency(iPosition){
                                    $( "#competency" ).load("' . base_url() . 'index.php/orgchart/add_competency/"+iPosition);
                                }
                                function ajaxEditCompetency(iPosition,iPCom){
                                    $( "#competency" ).load("' . base_url() . 'index.php/orgchart/update_competency/"+iPosition+"/"+iPCom);
                                }
                                function ajaxDelCompetency(iPosition,iPCom){
                                    blockPage("");
                                    $.post("' . base_url() . 'index.php/orgchart/del_poscompt",{plans:iPosition,id_pcom:iPCom},function(result){                                             
                                        blockPage(result);
                                        setTimeout($.unblockUI, 500);
                                        ajaxCompetency(iPosition);
                                    });
                                    return false;
                                }
                                
                                function ajaxOAdditionalRelationship (orgID){
                                    $( "#oAdditionalRelationship" ).load("' . base_url() . 'index.php/orgchart/additional_relationship/"+orgID);                                
                                }
                                function ajaxAddAdditionalRelationship(orgID){
                                    $( "#oAdditionalRelationship" ).load("' . base_url() . 'index.php/orgchart/add_additional_relationship/"+orgID);
                                }
                                
                                function ajaxORelationship(orgID){
                                    $( "#orelationship" ).load("' . base_url() . 'index.php/orgchart/load_org/"+orgID);                                
                                }
                                function ajaxEditORelationship(orgID,id_rel,sobid){
                                    $( "#orelationship" ).load("' . base_url() . 'index.php/orgchart/update_orelationship/"+orgID+"/"+id_rel+"/"+sobid);                                
                                }
                                function ajaxPRelationship(posID){
                                    $( "#prelationship" ).load("' . base_url() . 'index.php/orgchart/load_pos/"+posID);                                
                                }
                                function ajaxAddPRelationship(posID){
                                    $( "#prelationship" ).load("' . base_url() . 'index.php/orgchart/add_prelationship/"+posID);                                
                                }
                                function ajaxEditPRelationship(posID,id_rel,sobid){
                                    $( "#prelationship" ).load("' . base_url() . 'index.php/orgchart/update_prelationship/"+posID+"/"+id_rel+"/"+sobid);                                
                                }
                                    
				function orgEdit(param,obj_id,obj_text,obj_short,begda,endda,priox){
					if(param=="A"){
						$("#headOrg").html("Add Organization on "+obj_text+" ("+obj_id+")");
                                                $("#iOrg").val("-1");
                                                $("#iParentOrg").val(obj_id);
                                                $("#cOrgText").val("");
                                                $("#cOrgShort").val("");
                                                $("#dOrgBegda").val("");
                                                $("#dOrgEndda").val("");
                                                $("#cOrgPriox").val("");
                                                $("#orelationship").html("Active when editing Organisation");
                                                $("#oAdditionalRelationship").html("Active when editing Organisation");
					}else{
						$("#headOrg").html("Edit Organization "+obj_text+" ("+obj_id+")");
                                                $("#iOrg").val(obj_id);
                                                $("#iParentOrg").val("-1");
                                                $("#cOrgText").val("" + obj_text);
                                                $("#cOrgShort").val("" + obj_short );
                                                $("#dOrgBegda").val(begda);
                                                $("#dOrgEndda").val(endda);
                                                $("#cOrgPriox").val("" + priox);
                                                ajaxORelationship(obj_id);
                                                ajaxOAdditionalRelationship(obj_id);
					}
					$("#divEditS").hide();
					$("#divEditO").show();					
					return false;
				};

				function closeUpdOrg(){
					$("#divEditO").hide();
					return false;
				}

				function confirmUpdOrg(){
                                        blockPage("");
                                        xiOrg=$("#iOrg").val();
                                        xiParentOrg=$("#iParentOrg").val();
                                        dOrgBegda=$("#dOrgBegda").val();
                                        dOrgEndda=$("#dOrgEndda").val();
                                        xcOrgShort=$("#cOrgShort").val();
                                        xcOrgText=$("#cOrgText").val();
                                        xcOrgPriox=$("#cOrgPriox").val();
                                        $.post("' . base_url() . 'index.php/orgchart/update_org",{cOrgPriox:xcOrgPriox,cOrgText:xcOrgText,cOrgShort:xcOrgShort,begda:dOrgBegda,endda:dOrgEndda,iOrg:xiOrg,iParentOrg:xiParentOrg},function(result){                                             
                                            blockPage(result);
                                            if(xiParentOrg!="-1"){ //ADD
                                                $(".tree-folder-header").each(function(i,v){
                                                    if($(v).data().id==xiParentOrg){
                                                        $(v).click();
                                                        if($(v).find(".fa-folder-open").length==0){
                                                            $(v).click();
                                                        }
                                                    }
                                                });
                                            }else{ //UPDATE
                                                $(".tree-folder-header").each(function(i,v){
                                                    if($(v).data().id==xiOrg){
                                                        target =$(v).parent().parent().parent().children(".tree-folder-header");
                                                        $(target).click();
                                                        if($(v).parent().parent().parent().find(".fa-folder-open").length==0){
                                                            $(target).click();
                                                        }
                                                    }
                                                });
                                            }
                                            setTimeout($.unblockUI, 500);
                                        });
					return false;
				}
				
				function posEdit(param,obj_id,obj_text,obj_short,begda,endda,priox,reffKontrak,emailSup,nameSup){
					if(param=="A"){
                                            $("#headPos").html("Add Position on "+obj_text+" ("+obj_id+")");
                                            $("#iPosition").val("-1");
                                            $("#iParentPos").val(""+obj_id);
                                            $("#cPosText").val("");
                                            $("#cPosShort").val("");
                                            $("#dPosBegda").val("");
                                            $("#dPosEndda").val("");
                                            $("#cPosPriox").val("");
                                            $("#competency").html("Active when editing position");
                                            $("#prelationship").html("Active when editing position");
					}else{
                                            $("#headPos").html("Edit Position at "+obj_text+" ("+obj_id+")");
                                            $("#iPosition").val(obj_id);
                                            $("#iParentPos").val("-1");
                                            $("#cPosText").val("" + obj_text);
                                            $("#cPosShort").val("" + obj_short );
                                            $("#dPosBegda").val(begda);
                                            $("#dPosEndda").val(endda);
                                            $("#cPosPriox").val("" + priox);
                                            $("#cReffKontrak").val(reffKontrak);
                                            $("#cEmailSup").val(emailSup);
                                            $("#cNameSup").val(nameSup);
                                            ajaxCompetency(obj_id);
                                            ajaxPRelationship(obj_id);
					}
                                        if(obj_id.substring(0,3)==="100"){
                                            $(".fortad").hide();
                                        }else{
                                            $(".fortad").show();
                                        }
					$("#divEditO").hide();
					$("#divEditS").show();
					return false;
				};

				function closeUpdPos(){
					$("#divEditS").hide();
					return false;
				}

				function confirmUpdPos(){
                                        blockPage("");
                                        xiPosition=$("#iPosition").val();
                                        xiParentPos=$("#iParentPos").val();
                                        dPosBegda=$("#dPosBegda").val();
                                        dPosEndda=$("#dPosEndda").val();
                                        xcPosShort=$("#cPosShort").val();
                                        xcPosText=$("#cPosText").val();
                                        xcPosPriox=$("#cPosPriox").val();
                                        xcReffKontrak=$("#cReffKontrak").val();
                                        xcEmailSup=$("#cEmailSup").val();
                                        xcNameSup=$("#cNameSup").val();
					$.post("' . base_url() . 'index.php/orgchart/update_pos",{iPosition:xiPosition,iParentPos:xiParentPos,
                                            cPosShort:xcPosShort,begda:dPosBegda,endda:dPosEndda,cPosText:xcPosText,cPosPriox:xcPosPriox,cReffKontrak:xcReffKontrak,cEmailSup:xcEmailSup,cNameSup:xcNameSup},function(result){                                             
                                            blockPage(result);
                                            if(xiParentPos!="-1"){ //ADD
                                                $(".tree-folder-header").each(function(i,v){
                                                    if($(v).data().id==xiParentPos){
                                                        $(v).click();
                                                        if($(v).find(".fa-folder-open").length==0){
                                                            $(v).click();
                                                        }
                                                    }
                                                });
                                            }else{ //UPDATE
                                                $(".tree-folder-header").each(function(i,v){
                                                    if($(v).data().id==xiPosition){
                                                        target =$(v).parent().parent().parent().children(".tree-folder-header");
                                                        $(target).click();
                                                        if($(v).parent().parent().parent().find(".fa-folder-open").length==0){
                                                            $(target).click();
                                                        }
                                                    }
                                                });
                                            }
                                            setTimeout($.unblockUI, 500);
                                        });
					return false;
				}

				jQuery(document).ready(function() {
					TreeView.init();
				});
			</script>';
        $this->load->view('main', $data);
    }

    function loadData() {
        $sID = $this->input->post('id');
        $sStaff = $this->input->post('staff');
        $sltype = $this->input->post('ltype'); // load type
        $aData = null;
        $sStatus = '';
        //if($sID==0) $sID = 20000001;

        $aOrgAuth = $this->common->get_a_org_auth();
//        var_dump($aOrgAuth);exit;
        if ($sID == 0) {
            if (in_array("10000001", $aOrgAuth)) {
                $sID = 10000000;
            } else {
                $sID = 12000000;
            }
        }
//        echo $sID;exit;
        //if ($sID == 0)

        date_default_timezone_set("Asia/Jakarta");
        $sDate = date("Y-m-d");
        $idx1 = 0;
//        echo $sltype;exit;
        if ($sltype == 'P') {
            // Load Pegawai
            $aPSubs = $this->orgchart_m->get_sub_emp($sID, $sDate);
            $n=0;
            if ($aPSubs) {
                $n=count($aPSubs);
                foreach ($aPSubs as $idx1 => $aPSub) {
                    $aParam = null;
                    $aData[$idx1]['id'] = intval($aPSub['PERNR']);
                    $aData[$idx1]['name'] = '<span style="color:#FF6C60"><i class="fa fa-user"></i> ' . $aPSub['PERNR'] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $aPSub['CNAME'] . '</span> <div class="tree-actions"><a href="' . base_url() . 'index.php/employee/master/' . intval($aPSub['PERNR']) . '"><i class="fa fa-search"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; </div>';
                    $aData[$idx1]['type'] = 'item';
                    $aParam['id'] = intval($aPSub['PERNR']);
                    $aParam['ltype'] = 'X';
                    $aParam['children'] = false;
                    $aParam['itemSelected'] = true;
                    $aData[$idx1]['additionalParameters'] = $aParam;
                }
                $sStatus = 'OK';
            }
            $aPSubsJob = $this->orgchart_m->get_sub_job($sID,$sDate);
            if($aPSubsJob){
                
                foreach ($aPSubsJob as $idx1 => $aPSub) {
                    $aParam = null;
                    $aData[$idx1+$n]['id'] = intval($aPSub['OBJID']);
                    $aData[$idx1+$n]['name'] = '<span style="color:#1CAADC"><i class="fa fa-suitcase"></i> ' . $aPSub['OBJID'] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $aPSub['STEXT'] . ' / '.$aPSub['SHORT'].'</span> <div class="tree-actions"></div>';
                    $aData[$idx1+$n]['type'] = 'item';
                    $aParam['id'] = intval($aPSub['OBJID']);
                    $aParam['ltype'] = 'C';
                    $aParam['children'] = false;
                    $aParam['itemSelected'] = true;
                    $aData[$idx1+$n]['additionalParameters'] = $aParam;
                }
                $sStatus = 'OK';
            }
        } else {
            if ($sStaff == 'Y') {
                // Load Posisi
                $aSSubs = $this->orgchart_m->get_sub_pos($sID, $sDate);
                if ($aSSubs) {
                    foreach ($aSSubs as $idx1 => $aSSub) {
                        $aParam = null;
                        $aData[$idx1]['id'] = intval($aSSub['OBJID']);
//                        param,obj_id,obj_text,obj_short,begda,endda,priox,stell,family,score
                        $aData[$idx1]['name'] = '<span style="color:#1CAADC"><i class="fa fa-dot-circle-o"></i> ' . $aSSub['STEXT'] . '</span>';
                        if ($aData[$idx1]['id'] != '10000001')
                            $aData[$idx1]['name'] .=' <div class="tree-actions"><a href="#" onClick="posEdit(\'E\',\'' . $aSSub['OBJID'] . '\',\'' . $aSSub['STEXT'] . '\',\'' . $aSSub['SHORT'] . '\',\'' . $this->global_m->convert_yyyymmdd_ddmmyyyy($aSSub['BEGDA']) . '\',\'' . $this->global_m->convert_yyyymmdd_ddmmyyyy($aSSub['ENDDA']) . '\',\'' . $aSSub['PRIOX'] . '\',\'' . $aSSub['REFF_KONTRAK'] . '\',\'' . $aSSub['EMAIL_SUP'] . '\',\'' . $aSSub['NAME_SUP'] . '\');">' .
                                    '<i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; </div>';
                        $aData[$idx1]['type'] = 'folder';
                        $aParam['id'] = intval($aSSub['OBJID']);
                        $aParam['ltype'] = 'P';
                        $aParam['children'] = true;
                        $aParam['itemSelected'] = false;
                        $aData[$idx1]['additionalParameters'] = $aParam;
                    }
                    $sStatus = 'OK';
                }
            }
            $iFlag = 0;
//            if ($sID == 10000000)
//                $iFlag = 1;
            $aSubs = $this->orgchart_m->get_sub_org($sID, $sDate, $iFlag);
            if ($aSubs) {
                // Load Organisasi
                foreach ($aSubs as $idx => $aSub) {
                    $idx2 = $idx1 + $idx + 1;
                    $aParam = null;
                    $aData[$idx2]['id'] = intval($aSub['OBJID']);
                    $aData[$idx2]['name'] = $aSub['STEXT'];
                    if ($aData[$idx2]['id'] != '10000001')
                        $aData[$idx2]['name'] .=' <div class="tree-actions">' . ($sStaff == 'Y' ? '<a href="#" onClick="posEdit(\'A\',\'' . $aSub['OBJID'] . '\',\'' . $aSub['STEXT'] . '\');"><i class="fa fa-dot-circle-o"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;' : '') .
                                '<a href="#" onClick="orgEdit(\'A\',\'' . $aSub['OBJID'] . '\',\'' . $aSub['STEXT'] . '\');"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;' .
                                '<a href="#" onClick="orgEdit(\'E\',\'' . $aSub['OBJID'] . '\',\'' . $aSub['STEXT'] . '\',\'' . $aSub['SHORT'] . '\',\'' . $this->global_m->convert_yyyymmdd_ddmmyyyy($aSub['BEGDA']) . '\',\'' . $this->global_m->convert_yyyymmdd_ddmmyyyy($aSub['ENDDA']) . '\',\'' . $aSub['PRIOX'] . '\');"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; </div>';
                    $aData[$idx2]['type'] = 'folder';
                    $aParam['id'] = intval($aSub['OBJID']);
                    $aParam['ltype'] = 'S';
                    $aParam['children'] = true;
                    $aParam['itemSelected'] = false;
                    $aData[$idx2]['additionalParameters'] = $aParam;
                }
                $sStatus = 'OK';
            }
        }

        $sRtn['status'] = $sStatus;
        $sRtn['data'] = $aData;

        echo json_encode($sRtn);
    }

    //**ULUM UPDATE 2014-03-31 12:39
    function update_org() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('iOrg', 'iOrg', 'trim|required|numeric');
            $this->form_validation->set_rules('iParentOrg', 'iParentOrg', 'trim|required|numeric');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('cOrgShort', 'cOrgShort', 'trim');
            $this->form_validation->set_rules('cOrgText', 'cOrgText', 'trim');
            $this->form_validation->set_rules('cOrgPriox', 'cOrgPriox', 'trim');
            if ($this->form_validation->run()) {
                $iOrg = $this->input->post('iOrg');
                $iParentOrg = $this->input->post('iParentOrg');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SHORT'] = $this->input->post('cOrgShort');
                $a['STEXT'] = $this->input->post('cOrgText');
                $a['PRIOX'] = $this->input->post('cOrgPriox');
                $this->orgchart_m->org_upd($iOrg, $iParentOrg, $a);
                echo "Success";
                //redirect('orgchart/tree/N', 'refresh');
            } else
                echo "Some parameter unavailable";
            //redirect('orgchart/tree/N', 'refresh');
        } else {
            echo "Not Authorized Access";
//            redirect('orgchart/tree/N', 'refresh');
        }
    }

    function update_pos() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('iPosition', 'iPosition', 'trim|required|numeric');
            $this->form_validation->set_rules('iParentPos', 'iParentPos', 'trim|required|numeric');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('cPosShort', 'cPosShort', 'trim');
            $this->form_validation->set_rules('cPosText', 'cPosText', 'trim');
            $this->form_validation->set_rules('cPosPriox', 'cPosPriox', 'trim');
            $this->form_validation->set_rules('cReffKontrak', 'cReffKontrak', 'trim');
            $this->form_validation->set_rules('cEmailSup', 'cEmailSup', 'trim');
            $this->form_validation->set_rules('cNameSup', 'cNameSup', 'trim');
            if ($this->form_validation->run()) {
                $iPosition = $this->input->post('iPosition');
                $iParentPos = $this->input->post('iParentPos');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SHORT'] = $this->input->post('cPosShort');
                $a['STEXT'] = $this->input->post('cPosText');
                $a['PRIOX'] = $this->input->post('cPosPriox');
                $iPosition = $this->orgchart_m->pos_upd($iPosition, $iParentPos, $a);
                if(substr($iParentPos,0,3)!="100" && !empty($iPosition)){
                    $ad['REFF_KONTRAK'] = $this->input->post('cReffKontrak');
                    $ad['EMAIL_SUP'] = $this->input->post('cEmailSup');
                    $ad['NAME_SUP'] = $this->input->post('cNameSup');
                    $iPosition = $this->orgchart_m->postad_upd($iPosition, $a, $ad);
                }
                echo "Success";
            } else {
                echo "Some parameter unavailable";
            }
        } else {
            echo "Not Authorized Access";
        }
    }

    //**END OF UPDATE 2014-03-31
    function load_competency($iPosition) {
        //get view
        $aCompetency = $this->orgchart_m->get_pos_compt($iPosition);
        $sRet = '<tr>
                            <td colspan="6">No Data</td>
                        </tr>';
        if (!empty($aCompetency)) {
            $sRet = "";
            for ($i = 0; $i < count($aCompetency); $i++) {
                $sRet.='<tr>
                                <td>' . $this->global_m->get_array_data($aCompetency[$i], "BEGDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->global_m->get_array_data($aCompetency[$i], "ENDDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->global_m->get_array_data($aCompetency[$i], "COMPT") . '</td>
                                <td>' . $this->global_m->get_array_data($aCompetency[$i], "REQV") . '</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="#" data-toggle="modal" onclick="ajaxEditCompetency(' . $iPosition . ',' . $this->global_m->get_array_data($aCompetency[$i], "id_pcom") . ')"> <i class="fa fa-pencil"></i> </a>
                                    <a class="btn btn-danger btn-xs" href="#" data-toggle="modal" onclick="ajaxDelCompetency(' . $iPosition . ',' . $this->global_m->get_array_data($aCompetency[$i], "id_pcom") . ')"> <i class="fa fa-trash-o"></i> </a>
                                </td>
                            </tr>
';
            }
        }
        echo '<header class="panel-heading">
                Position Competency
                <a class="btn btn-danger btn-xs pull-right" href="#" data-toggle="modal" onclick="ajaxAddCompetency(' . $iPosition . ')"> <i class="fa fa-plus"></i> </a>
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Competency</th>
                        <th>Value</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>' . $sRet . '
                
                                            
                                        </tbody>
            </table>
';
    }

    function add_competency($iPosition) {
        $aComp = $this->orgchart_m->get_competency($iPosition);
        $sCompID = '<SELECT id="compID" name="compID" class="form-control" style="padding: 3px 0px;">';
        for ($i = 0; $i < count($aComp); $i++) {
            if($aComp[$i]['OTYPE']=='KC'){
                continue;
            }
            $sCompID.='<option value="' . $aComp[$i]['OBJID'] . '">' . $aComp[$i]['SHORT'] . ' - ' . $aComp[$i]['STEXT'] . '</option>';
        }
        $sCompID.='</SELECT>';
        echo '<script src="' . base_url() . 'js/advanced-form-components.js"></script>
            <script>
                jQuery(document).ready(function() {
                    $("#compID").select2({dropdownAutoWidth: true});
                });
                
            </script>
            <header class="panel-heading">
                Add Position Competency
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="frm_insert" >
                <input type="hidden" id="compPlans" value="' . $iPosition . '"/>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" id="compBegda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" id="compEndda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="compt" class="col-lg-2 col-sm-2 control-label">Competency</label>
                        <div class="col-lg-10">
                            ' . $sCompID . '
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coval" class="col-lg-2 col-sm-2 control-label">Value</label>
                        <div class="col-lg-10">     
                            <input type="text" class="form-control" id="compVal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coval" class="col-lg-2 col-sm-2 control-label">Bobot</label>
                        <div class="col-lg-10">     
                            <input type="text" class="form-control" id="compBobot">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-success" onClick="addPosComp();">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal fade" id="confirm-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Insert</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to insert, this procedure is irreversible.</p>
                    <p><b><span id="mb"></span></b></p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>
            <script>
            var modalAnswer="0";
            function addPosComp(){
                modalAnswer="0";
                sCompPlans=$("#compPlans").val();
                dBegda=$("#compBegda").val();
                dEndda=$("#compEndda").val();
                sCompID=$("#compID").val();
                sCompVal=$("#compVal").val();
                sCompBobot=$("#compBobot").val();
                
                $.post( "' . base_url() . 'index.php/orgchart/insert_check_time_constraint_competency", { plans: sCompPlans,begda: dBegda, endda: dEndda,compID:sCompID },function (text){
                    if(text==="Please Check and Fill your input first"){
                        $("#mb").html(text);
                        $("#btnYes").hide();
                    }else if(text!="null"){
                        $("#mb").html(text);
                        $("#btnYes").show();
                    }else{
                        $("#btnYes").show();
                    }
                }).done(function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){                            
                            blockPage("Saving");
                            $.post("' . base_url() . 'index.php/orgchart/add_poscompt",{plans:sCompPlans,begda:dBegda,
                                endda:dEndda,compID:sCompID,compVal:sCompVal,bobot:sCompBobot},function(result){                                             
                                blockPage(result);
                                setTimeout($.unblockUI, 500);
                                ajaxCompetency(' . $iPosition . ');
                            });
                        }
                    });
                });
                return false;
            }
        $("#btnYes").click( function(){
            modalAnswer="1";
            $("#confirm-insert").modal("hide");
        });
        $("#btnNo").click( function(){
            modalAnswer="2";
            $("#confirm-insert").modal("hide");
        });
            </script>
            ';
    }

    function add_poscompt() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('plans', 'plans', 'trim|required|numeric');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('compID', 'compID', 'trim|required');
            $this->form_validation->set_rules('compVal', 'compVal', 'trim|required');
            $this->form_validation->set_rules('bobot', 'bobot', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PLANS'] = $iPosition = $this->input->post('plans');
                $a['bobot'] = $iPosition = $this->input->post('bobot');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['COMPT'] = $this->input->post('compID');
                $a['REQV'] = $this->input->post('compVal');
                #$plans,$begda,$endda,$compID,"INSERT")
                $oRes = $this->orgchart_m->check_time_constraint_competency($a['PLANS'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_pos_compt where PLANS='" . $a['PLANS'] . "' AND COMPT='" . $a['COMPT'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_pos_compt',$sQuery);
                    $oRes = $this->orgchart_m->check_time_constraint_competency($a['PLANS'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_pos_compt SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_pcom='" . $aRow['id_pcom'] . "';";
                    $this->db->query($sQuery);
                }
                $a['created_by']= $this->session->userdata('username');
                $this->db->insert('tm_pos_compt', $a);
                $this->load->model('gen_machine');
                $this->gen_machine->save_to_trigger($this->gen_machine->STYPE_PLANSD, $iPosition);
                echo "Success";
                //redirect('orgchart/tree/Y', 'refresh');
            } else {
                echo "Some parameter unavailable";
                //redirect('orgchart/tree/Y', 'refresh');
            }
        } else {
            echo "Not Authorized Access";
            //redirect('orgchart/tree/Y', 'refresh');
        }
    }

    function update_competency($iPosition, $iPCom) {
        $aPosComp = $this->orgchart_m->get_apos_compt($iPosition, $iPCom);
        if (empty($aPosComp)) {
            echo "INVALID PARAMETER";
        } else {
            $aComp = $this->orgchart_m->get_competency($iPosition);
            $sCompID = '<SELECT id="compID" name="compID">';
            for ($i = 0; $i < count($aComp); $i++) {
                if($aComp[$i]['OTYPE']=='KC'){
                    continue;
                }
                $selected = "";
                if ($aComp[$i]['OBJID'] == $aPosComp['COMPT']){
                    $selected = "selected";
                }
                $sCompID.='<option value="' . $aComp[$i]['OBJID'] . '" ' . $selected . '>' . $aComp[$i]['SHORT'] . ' - ' . $aComp[$i]['STEXT'] . '</option>';
            }
            $sCompID.='</SELECT>';
            echo '<script src="' . base_url() . 'js/advanced-form-components.js"></script>
            <script>
                jQuery(document).ready(function() {
                    $("#compID").select2();
                });
            </script><header class="panel-heading">
                Update Position Competency
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" >
                <input type="hidden" id="idPosComp" value="' . $iPCom . '"/>
                <input type="hidden" id="compPlans" value="' . $iPosition . '"/>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" id="compBegda" value="' . $this->global_m->get_array_data($aPosComp, "BEGDA", $this->global_m->DATE_MYSQL) . '">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" id="compEndda" value="' . $this->global_m->get_array_data($aPosComp, "ENDDA", $this->global_m->DATE_MYSQL) . '">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="compt" class="col-lg-2 col-sm-2 control-label">Competency</label>
                        <div class="col-lg-10">
                                ' . $sCompID . '
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coval" class="col-lg-2 col-sm-2 control-label">Value</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="compVal" value="' . $this->global_m->get_array_data($aPosComp, "REQV") . '">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coval" class="col-lg-2 col-sm-2 control-label">Bobot</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="compBobot" value="' . $this->global_m->get_array_data($aPosComp, "bobot") . '">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-success" onClick="updatePosComp();">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal fade" id="confirm-update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Update</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to insert, this procedure is irreversible.</p>
                    <p><b><span id="mb"></span></b></p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>
            <script>
            var modalAnswer="0";
            function updatePosComp(){
                sPosComp=$("#idPosComp").val();
                sCompPlans=$("#compPlans").val();
                dBegda=$("#compBegda").val();
                dEndda=$("#compEndda").val();
                sCompID=$("#compID").val();
                sCompVal=$("#compVal").val();
                sCompBobot=$("#compBobot").val();
                
                $.post( "' . base_url() . 'index.php/orgchart/update_check_time_constraint_competency", { plans: sCompPlans,begda: dBegda, endda: dEndda,compID:sCompID,id_pcom:sPosComp,bobot:sCompBobot },function (text){
                    if(text==="Please Check and Fill your input first"){
                        $("#mb").html(text);
                        $("#btnYes").hide();
                    }else if(text!="null"){
                        $("#mb").html(text);
                        $("#btnYes").hide();
                        $("#btnYes").show();
                    }else{
                        $("#btnYes").show();
                    }
                }).done(function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){                            
                            blockPage("Update");
                            $.post("' . base_url() . 'index.php/orgchart/update_poscompt",{plans:sCompPlans,begda:dBegda,
                                endda:dEndda,compID:sCompID,compVal:sCompVal,id_pcom:sPosComp,bobot:sCompBobot},function(result){                                             
                                blockPage(result);
                                setTimeout($.unblockUI, 500);
                                ajaxCompetency(' . $iPosition . ');
                            });
                        }
                    });
                });
                
                return false;
            }
        $("#btnYes").click( function(){
            modalAnswer="1";
            $("#confirm-update").modal("hide");
        });
        $("#btnNo").click( function(){
            modalAnswer="2";
            $("#confirm-update").modal("hide");
        });
            </script>
            ';
        }
    }

    function update_poscompt() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_pcom', 'id_pcom', 'trim|required|numeric');
            $this->form_validation->set_rules('plans', 'plans', 'trim|required|numeric');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('compID', 'compID', 'trim|required');
            $this->form_validation->set_rules('compVal', 'compVal', 'trim|required');
            $this->form_validation->set_rules('bobot', 'bobot', 'trim|required');
            if ($this->form_validation->run()) {
                $id_pcom = $this->input->post('id_pcom');
                $a['PLANS'] = $iPosition = $this->input->post('plans');
                $a['bobot'] = $iPosition = $this->input->post('bobot');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['COMPT'] = $this->input->post('compID');
                $a['REQV'] = $this->input->post('compVal');
                $oRes = $this->orgchart_m->check_time_constraint_competency($a['PLANS'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK", $id_pcom);
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_pos_compt where PLANS='" . $a['PLANS'] . "' AND COMPT='" . $a['COMPT'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND id_pcom<>'$id_pcom'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_pos_compt',$sQuery);
                    $oRes = $this->orgchart_m->check_time_constraint_competency($a['PLANS'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK", $id_pcom);
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_pos_compt SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_pcom='" . $aRow['id_pcom'] . "';";
//                    echo $sQuery;
                    $this->db->query($sQuery);
                }
                $a['updated_by'] = $this->session->userdata('username');
                $this->db->where('id_pcom', $id_pcom);
                $this->db->update('tm_pos_compt', $a);
                echo "Success";
                //redirect('orgchart/tree/Y', 'refresh');
            } else {
                echo "Some parameter unavailable";
                //redirect('orgchart/tree/Y', 'refresh');
            }
        } else {
            echo "Not Authorized Access";
            //redirect('orgchart/tree/Y', 'refresh');
        }
    }

    function del_poscompt() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_pcom', 'id_pcom', 'trim|required|numeric');
            $this->form_validation->set_rules('plans', 'plans', 'trim|required|numeric');
            if ($this->form_validation->run()) {
                $id_pcom = $this->input->post('id_pcom');
                $plans = $this->input->post('plans');
                $this->db->where('id_pcom', $id_pcom);
                $this->db->where('PLANS', $plans);
                $this->db->delete('tm_pos_compt');
                $this->global_m->insert_log_delete('tm_pos_compt',array('id_pcom'=> $id_pcom,'PLANS'=> $plans));
                echo "Success";
                //redirect('orgchart/tree/Y', 'refresh');
            } else {
                echo "Some parameter unavailable";
                //redirect('orgchart/tree/Y', 'refresh');
            }
        } else {
            echo "Not Authorized Access";
            //redirect('orgchart/tree/Y', 'refresh');
        }
    }

    function insert_check_time_constraint_competency() {
        $plans = trim($this->input->post('plans'));
        $begda = trim($this->input->post('begda'));
        $endda = trim($this->input->post('endda'));
        $compID = trim($this->input->post('compID'));
        if (empty($plans) || empty($begda) || empty($endda) || empty($compID) || $plans == "" || $begda == "" || $endda == "" || $compID == "") {
            echo "Please Check and Fill your input first";
            exit;
        }
        $begda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($begda));
        $endda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($endda));
        echo $this->orgchart_m->check_time_constraint_competency($plans, $begda, $endda, $compID, "INSERT");
    }

    function update_check_time_constraint_competency() {
        $plans = trim($this->input->post('plans'));
        $begda = trim($this->input->post('begda'));
        $endda = trim($this->input->post('endda'));
        $compID = trim($this->input->post('compID'));
        $id_pcom = trim($this->input->post('id_pcom'));
        if (empty($plans) || empty($begda) || empty($endda) || empty($compID) || empty($id_pcom) || $plans == "" || $begda == "" || $endda == "" || $id_pcom == "") {
            echo "Please Check and Fill your input first";
            exit;
        }
        $begda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($begda));
        $endda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($endda));
        echo $this->orgchart_m->check_time_constraint_competency($plans, $begda, $endda, $compID, "UPDATE", $id_pcom);
    }

    function load_org($org_id) {
        //get view
        $aRelationship = $this->orgchart_m->get_master_relation($org_id, "O");
//        var_dump($aRelationship);exit;
        $sRet = '<tr>
                    <td colspan="5">No Data</td>
                </tr>';
        if (!empty($aRelationship)) {
            $sRet = "";
            for ($i = 0; $i < count($aRelationship); $i++) {
                $sRet.='<tr>
                                <td>' . $this->global_m->get_array_data($aRelationship[$i], "BEGDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->global_m->get_array_data($aRelationship[$i], "ENDDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->orgchart_m->get_master_org_stext($this->global_m->get_array_data($aRelationship[$i], "SOBID"), 'O') . '</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="#" data-toggle="modal" onclick="ajaxEditORelationship(' . $org_id . ',' . $this->global_m->get_array_data($aRelationship[$i], "id_rel") . ','.$this->global_m->get_array_data($aRelationship[$i], "SOBID").')"> <i class="fa fa-pencil"></i> </a>
                                </td>
                            </tr>
';
            }
        }
        echo '<header class="panel-heading">
                Update Relationship
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>PARENT</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>' . $sRet . '
                </tbody>
            </table>
';
    }


    function additional_relationship($org_id) {
        //get view
        $aRelationship = $this->orgchart_m->get_master_relation($org_id, "O");
//        var_dump($aRelationship);exit;
        $sRet = '<tr>
                    <td colspan="5">No Data</td>
                </tr>';
        if (!empty($aRelationship) && false) {
            $sRet = "";
            for ($i = 0; $i < count($aRelationship); $i++) {
                $sRet.='<tr>
                                <td>' . $this->global_m->get_array_data($aRelationship[$i], "BEGDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->global_m->get_array_data($aRelationship[$i], "ENDDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->orgchart_m->get_master_org_stext($this->global_m->get_array_data($aRelationship[$i], "SOBID"), 'O') . '</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="#" data-toggle="modal" onclick="ajaxEditORelationship(' . $org_id . ',' . $this->global_m->get_array_data($aRelationship[$i], "id_rel") . ','.$this->global_m->get_array_data($aRelationship[$i], "SOBID").')"> <i class="fa fa-pencil"></i> </a>
                                </td>
                            </tr>
';
            }
        }
        echo '<header class="panel-heading">
                Update Additional Relationship (TAD)
                <a class="btn btn-danger btn-xs pull-right" href="#" data-toggle="modal" onclick="ajaxAddAdditionalRelationship(' . $org_id . ')"> <i class="fa fa-plus"></i> </a>
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>SubType</th>
                        <th>Object</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>' . $sRet . '
                </tbody>
            </table>
';
    }
    
    function add_additional_relationship($org_id){
//        $aComp = $this->orgchart_m->get_competency($iPosition);
        $sCompID = '<SELECT id="compID" name="compID" class="form-control" style="padding: 3px 0px;">';
//        for ($i = 0; $i < count($aComp); $i++) {
//            if($aComp[$i]['OTYPE']=='KC'){
//                continue;
//            }
//            $sCompID.='<option value="' . $aComp[$i]['OBJID'] . '">' . $aComp[$i]['SHORT'] . ' - ' . $aComp[$i]['STEXT'] . '</option>';
//        }
        $sCompID.='</SELECT>';
        echo '<script src="' . base_url() . 'js/advanced-form-components.js"></script>
            <script>
                jQuery(document).ready(function() {
                    $("#compID").select2({dropdownAutoWidth: true});
                });
                
            </script>
            <header class="panel-heading">
                Add Additional Relationship
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="frm_insert" >
                <input type="hidden" id="org_id" value="' . $org_id . '"/>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" id="compBegda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" id="compEndda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="compt" class="col-lg-2 col-sm-2 control-label">Competency</label>
                        <div class="col-lg-10">
                            ' . $sCompID . '
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coval" class="col-lg-2 col-sm-2 control-label">Value</label>
                        <div class="col-lg-10">     
                            <input type="text" class="form-control" id="compVal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coval" class="col-lg-2 col-sm-2 control-label">Bobot</label>
                        <div class="col-lg-10">     
                            <input type="text" class="form-control" id="compBobot">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-success" onClick="addPosComp();">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal fade" id="confirm-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Insert</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to insert, this procedure is irreversible.</p>
                    <p><b><span id="mb"></span></b></p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>
            <script>
            var modalAnswer="0";
            function addPosComp(){
                modalAnswer="0";
                sCompPlans=$("#compPlans").val();
                dBegda=$("#compBegda").val();
                dEndda=$("#compEndda").val();
                sCompID=$("#compID").val();
                sCompVal=$("#compVal").val();
                sCompBobot=$("#compBobot").val();
                
                $.post( "' . base_url() . 'index.php/orgchart/insert_check_time_constraint_competency", { plans: sCompPlans,begda: dBegda, endda: dEndda,compID:sCompID },function (text){
                    if(text==="Please Check and Fill your input first"){
                        $("#mb").html(text);
                        $("#btnYes").hide();
                    }else if(text!="null"){
                        $("#mb").html(text);
                        $("#btnYes").show();
                    }else{
                        $("#btnYes").show();
                    }
                }).done(function() {
                    $("#confirm-insert").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){                            
                            blockPage("Saving");
                            $.post("' . base_url() . 'index.php/orgchart/add_poscompt",{plans:sCompPlans,begda:dBegda,
                                endda:dEndda,compID:sCompID,compVal:sCompVal,bobot:sCompBobot},function(result){                                             
                                blockPage(result);
                                setTimeout($.unblockUI, 500);
                                ajaxCompetency(' . $org_id . ');
                            });
                        }
                    });
                });
                return false;
            }
        $("#btnYes").click( function(){
            modalAnswer="1";
            $("#confirm-insert").modal("hide");
        });
        $("#btnNo").click( function(){
            modalAnswer="2";
            $("#confirm-insert").modal("hide");
        });
            </script>
            ';
    }

    function get_unit_cb() {
        $aRtn = null;
        $q = $this->db->escape_str($this->input->post('q'));
        $prefix = $this->input->post('prefix');
        $aDataUnit = $this->orgchart_m->get_master_org_cb($prefix, $q);
        $i = 0;
        if ($aDataUnit) {
            foreach ($aDataUnit as $aUnit) {
                $aRtn[$i]["id"] = $aUnit["OBJID"];
                $aRtn[$i]["text"] = $aUnit["STEXT"] . " ( " . $aUnit['SHORT'] . " )";
                $i++;
            }
        }
        echo json_encode($aRtn);
    }

    function update_orelationship($org_id, $id_rel,$sobid) {
        $aMasterRelation = $this->orgchart_m->get_master_relation_id_rel($org_id, $id_rel);
        if (empty($aMasterRelation)) {
            echo "INVALID PARAMETER";
        } else {
//        $aComp=$this->orgchart_m->get_competency($iPosition);
//		$sCompID='<SELECT id="compID" name="compID" class="form-control" style="padding: 3px 0px;">';
//        for($i=0;$i<count($aComp);$i++){
//            $selected="";
//            if($aComp[$i]['OBJID']==$aPosComp['COMPT'])$selected="selected";
//            $sCompID.='<option value="'.$aComp[$i]['OBJID'].'" '.$selected.'>'.$aComp[$i]['SHORT'].' - '.$aComp[$i]['STEXT'].'</option>';
//        }
//        $sCompID.='</SELECT>';
            echo '<script src="' . base_url() . 'js/advanced-form-components.js"></script>
            <script>
            var modalAnswer="0";            
            function formatSel2(item){
                    var tmp = item.text;
                    var rtn = tmp.replace(/-/g,"");
                    return rtn;
            };
            jQuery(document).ready(function() {
                $("#fObj").select2({
                        minimumInputLength: 1,
                        dropdownAutoWidth: true,
                        formatSelection : formatSel2,
                        ajax: {
                                url: "' . base_url() . 'index.php/orgchart/get_unit_cb/",
                                dataType: "json",
                                type: "POST",
                                data: function (term, page) {
                                        return {
                                                q: term,
                                                prefix:"' . substr($org_id, 0, 3) . '"
                                        };
                                },
                                results: function (data, page) {
                                        return {results: data};
                                }
                        },
                        initSelection: function(element, callback) {
                                var id = "'.$sobid.'";
                                if (id!=="") {
                                        return $.ajax({
                                                type: "POST",
                                                url: "' . base_url() . 'index.php/orgchart/get_unit_desc/",
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

                 
            });
            </script><header class="panel-heading">
                Update Relationship
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="oRel_update">
                <input type="hidden" id="id_rel" value="' . $id_rel . '"/>
                <input type="hidden" id="objid" value="' . $org_id . '"/>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" id="oRelBegda" value="' . $this->global_m->get_array_data($aMasterRelation, "BEGDA", $this->global_m->DATE_MYSQL) . '">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" id="oRelEndda" value="' . $this->global_m->get_array_data($aMasterRelation, "ENDDA", $this->global_m->DATE_MYSQL) . '">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="compt" class="col-lg-2 col-sm-2 control-label">Unit</label>
                        <div class="col-lg-10">
                        <input type="text" id="fObj" name="fObj" class="form-control" style="padding: 3px 0px;"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-default" onClick="ajaxORelationship(' . $org_id . ');">< Back</button>
                            <button type="button" class="btn btn-success" onClick="updateORel();">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal fade" id="confirm-update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Update</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to insert, this procedure is irreversible.</p>
                    <p><b><span id="mb"></span></b></p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>
            <script>
            function updateORel(){
                sid_rel=$("#id_rel").val();
                sObjid=$("#objid").val();
                dBegda=$("#oRelBegda").val();
                dEndda=$("#oRelEndda").val();
                sObj=$("#fObj").val();
                
                $.post( "' . base_url() . 'index.php/orgchart/update_check_time_constraint_orel", { id_rel: sid_rel,
                    begda: dBegda, 
                    endda: dEndda,
                    subty: "A002",
                    objid:sObjid },function (text){
                    if(text==="Please Check and Fill your input first"){
                        $("#mb").html(text);
                        $("#btnYes").hide();
                    }else if(text!="null"){
                        $("#mb").html(text);
                        $("#btnYes").show();
                    }else{
                        $("#btnYes").show();
                    }
                }).done(function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){                            
                            blockPage("Saving");
                            $.post("' . base_url() . 'index.php/orgchart/update_oRel",{id_rel: sid_rel,
                    begda: dBegda, 
                    endda: dEndda,
                    subty: "A002",
                    objid:sObjid,
                    sobid:sObj},function(result){                                             
                            blockPage(result);
                            setTimeout($.unblockUI, 500);
                            ajaxORelationship(' . $org_id . ');
                            $(".tree-folder-header").each(function(i,v){
                                if($(v).data().id==sObj){
                                    target =$(v).parent().parent().parent().children(".tree-folder-header");
                                    $(target).click();
                                    if($(v).parent().parent().parent().find(".fa-folder-open").length==0){
                                        $(target).click();
                                    }
                                }
                            });    
                        });
                        }
                    });
                });

                
                
                return false;
                
            }
            
        $("#btnYes").click( function(){
            modalAnswer="1";
            $("#confirm-update").modal("hide");
        });
        $("#btnNo").click( function(){
            modalAnswer="2";
            $("#confirm-update").modal("hide");
        });
            </script>
            ';
        }
    }

    function get_unit_desc() {
        $iOrg = $this->input->post('id');
        $this->load->model('employee_m');
        $aOrg = $this->employee_m->get_unit_desc($iOrg);

        echo json_encode($aOrg);
    }

    function insert_check_time_constraint_orel() {        
        $objid = trim($this->input->post('objid'));
        $begda = trim($this->input->post('begda'));
        $endda = trim($this->input->post('endda'));
        $subty = trim($this->input->post('subty'));
        if (empty($objid) || empty($begda) || empty($endda) || empty($subty)  || $objid == "" || $begda == "" || $endda == "" ) {
            echo "Please Check and Fill your input first";
            exit;
        }
        $begda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($begda));
        $endda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($endda));
        echo $this->orgchart_m->check_time_constraint_orel($objid, $begda, $endda, $subty, "INSERT");
//        $plans = trim($this->input->post('plans'));
//        $begda = trim($this->input->post('begda'));
//        $endda = trim($this->input->post('endda'));
//        $compID = trim($this->input->post('compID'));
//        if (empty($plans) || empty($begda) || empty($endda) || empty($compID) || $plans == "" || $begda == "" || $endda == "" || $compID == "") {
//            echo "Please Check and Fill your input first";
//            exit;
//        }
//        $begda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($begda));
//        $endda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($endda));
//        echo $this->orgchart_m->check_time_constraint_competency($plans, $begda, $endda, $compID, "INSERT");
    }

    function update_check_time_constraint_orel() {
        $objid = trim($this->input->post('objid'));
        $begda = trim($this->input->post('begda'));
        $endda = trim($this->input->post('endda'));
        $subty = trim($this->input->post('subty'));
        $id_rel = trim($this->input->post('id_rel'));
        if (empty($objid) || empty($begda) || empty($endda) || empty($subty) || empty($id_rel) || $objid == "" || $begda == "" || $endda == "" || $id_rel == "") {
            echo "Please Check and Fill your input first";
            exit;
        }
        $begda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($begda));
        $endda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($endda));
        echo $this->orgchart_m->check_time_constraint_orel($objid, $begda, $endda, $subty, "UPDATE", $id_rel);
    }
    
    
    function update_orel() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_rel', 'id_rel', 'trim|required|numeric');
            $this->form_validation->set_rules('objid', 'objid', 'trim|required|numeric');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('sobid', 'sobid', 'trim|required');
            if ($this->form_validation->run()) {
                $id_rel = $this->input->post('id_rel');
                $a['OBJID'] = $iPosition = $this->input->post('objid');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SOBID'] = $this->input->post('sobid');
                $a['SUBTY'] = $this->input->post('subty');
                $oRes = $this->orgchart_m->check_time_constraint_orel($a['OBJID'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK", $id_rel);
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_master_relation where OBJID='" . $a['OBJID'] . "' AND OTYPE='O' AND SUBTY='" . $a['SUBTY'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND id_rel<>'$id_rel'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_master_relation',$sQuery);
                    $oRes = $this->orgchart_m->check_time_constraint_orel($a['OBJID'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK", $id_rel);
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_master_relation SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_rel='" . $aRow['id_rel'] . "';";
//                    echo $sQuery;
                    $this->db->query($sQuery);
                }
                $a['updated_by'] = $this->session->userdata('username');
                $this->db->where('id_rel', $id_rel);
                $this->db->update('tm_master_relation', $a);
                echo "Success";
                //redirect('orgchart/tree/Y', 'refresh');
            } else {
                echo "Some parameter unavailable";
                //redirect('orgchart/tree/Y', 'refresh');
            }
        } else {
            echo "Not Authorized Access";
            //redirect('orgchart/tree/Y', 'refresh');
        }
    }
    
    

    function load_pos($pos_id) {
        //get view
        $aRelationship = $this->orgchart_m->get_master_relation($pos_id, "S");
        $aRelationship2 = $this->orgchart_m->get_master_relation_sobid($pos_id, "S");
//        var_dump($aRelationship);exit;
        $sRet = '<tr>
                            <td colspan="5">No Data</td>
                        </tr>';
        if (!empty($aRelationship) || !empty($aRelationship2)) {
            $sRet = "";
            $aMerge = array_merge($aRelationship,$aRelationship2);
            for ($i = 0; $i < count($aMerge); $i++) {
                $sRet.='<tr>
                                <td>' . $this->global_m->get_array_data($aMerge[$i], "OBJID") . '</td>
                                <td>' . $this->orgchart_m->get_master_pos_stext($this->global_m->get_array_data($aMerge[$i], "OBJID"), $aMerge[$i]['OTYPE']) . '</td>
                                <td>' . $this->global_m->get_array_data($aMerge[$i], "SUBTY") . '</td>
                                <td>' . $this->global_m->get_array_data($aMerge[$i], "BEGDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->global_m->get_array_data($aMerge[$i], "ENDDA", $this->global_m->DATE_MYSQL) . '</td>
                                <td>' . $this->orgchart_m->get_master_pos_stext($this->global_m->get_array_data($aMerge[$i], "SOBID"), $aMerge[$i]['SCLAS']) . '</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="#" data-toggle="modal" onclick="ajaxEditPRelationship(' . $pos_id . ',' . $this->global_m->get_array_data($aMerge[$i], "id_rel") . ','.$this->global_m->get_array_data($aMerge[$i], "SOBID").')"> <i class="fa fa-pencil"></i> </a>
                                </td>
                            </tr>
';
            }
        }
        echo '<header class="panel-heading">
                Update Relationship
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>OBJID</th>
                        <th>STEXT</th>
                        <th>SUBTY</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>PARENT</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>' . $sRet . '
                </tbody>
            </table>
';
    }


    function update_prelationship($pos_id, $id_rel,$sobid) {
        $aMasterRelation = $this->orgchart_m->get_master_relation_id_rel($pos_id, $id_rel);
        if (empty($aMasterRelation)) {
            echo "INVALID PARAMETER";
        } else {
            echo '<script src="' . base_url() . 'js/advanced-form-components.js"></script>
            <script>
            var modalAnswer="0";            
            function formatSel2(item){
                    var tmp = item.text;
                    var rtn = tmp.replace(/-/g,"");
                    return rtn;
            };
            jQuery(document).ready(function() {
                $("#fObj").select2({
                        minimumInputLength: 1,
                        dropdownAutoWidth: true,
                        formatSelection : formatSel2,
                        ajax: {
                                url: "' . base_url() . 'index.php/orgchart/get_unit_cb/",
                                dataType: "json",
                                type: "POST",
                                data: function (term, page) {
                                        return {
                                                q: term,
                                                prefix:"' . substr($sobid, 0, 3) . '"
                                        };
                                },
                                results: function (data, page) {
                                        return {results: data};
                                }
                        },
                        initSelection: function(element, callback) {
                                var id = "'.$sobid.'";
                                if (id!=="") {
                                        return $.ajax({
                                                type: "POST",
                                                url: "' . base_url() . 'index.php/orgchart/get_unit_desc/",
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

                 
            });
            </script><header class="panel-heading">
                Update Position Competency
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="oRel_update">
                <input type="hidden" id="id_rel" value="' . $id_rel . '"/>
                <input type="hidden" id="objid" value="' . $pos_id . '"/>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" id="oRelBegda" value="' . $this->global_m->get_array_data($aMasterRelation, "BEGDA", $this->global_m->DATE_MYSQL) . '">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" id="oRelEndda" value="' . $this->global_m->get_array_data($aMasterRelation, "ENDDA", $this->global_m->DATE_MYSQL) . '">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="compt" class="col-lg-2 col-sm-2 control-label">Unit</label>
                        <div class="col-lg-10">
                        <input type="text" id="fObj" name="fObj" class="form-control" style="padding: 3px 0px;"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-default" onClick="ajaxPRelationship(' . $pos_id . ');">< Back</button>
                            <button type="button" class="btn btn-success" onClick="updatePRel();">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal fade" id="confirm-update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Update</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to insert, this procedure is irreversible.</p>
                    <p><b><span id="mb"></span></b></p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>
            <script>
            function updatePRel(){
                sid_rel=$("#id_rel").val();
                sObjid=$("#objid").val();
                dBegda=$("#oRelBegda").val();
                dEndda=$("#oRelEndda").val();
                sObj=$("#fObj").val();
                
                $.post( "' . base_url() . 'index.php/orgchart/update_check_time_constraint_prel", { id_rel: sid_rel,
                    begda: dBegda, 
                    endda: dEndda,
                    subty: "A003",
                    objid:sObjid },function (text){
                    if(text==="Please Check and Fill your input first"){
                        $("#mb").html(text);
                        $("#btnYes").hide();
                    }else if(text!="null"){
                        $("#mb").html(text);
                        $("#btnYes").show();
                    }else{
                        $("#btnYes").show();
                    }
                }).done(function() {
                    $("#confirm-update").modal("show").on("hidden.bs.modal", function (e) {
                        if(modalAnswer=="1"){                            
                            blockPage("Saving");
                            $.post("' . base_url() . 'index.php/orgchart/update_pRel",{id_rel: sid_rel,
                    begda: dBegda, 
                    endda: dEndda,
                    subty: "A003",
                    objid:sObjid,
                    sobid:sObj},function(result){                                             
                            blockPage(result);
                            setTimeout($.unblockUI, 500);
                            ajaxPRelationship(' . $pos_id . ');
                            $(".tree-folder-header").each(function(i,v){
                                if($(v).data().id==sObj){
                                    target =$(v).parent().parent().parent().children(".tree-folder-header");
                                    $(target).click();
                                    if($(v).parent().parent().parent().find(".fa-folder-open").length==0){
                                        $(target).click();
                                    }
                                }
                            });    
                        });
                        }
                    });
                });

                
                
                return false;
                
            }
            
        $("#btnYes").click( function(){
            modalAnswer="1";
            $("#confirm-update").modal("hide");
        });
        $("#btnNo").click( function(){
            modalAnswer="2";
            $("#confirm-update").modal("hide");
        });
            </script>
            ';
        }
    }

    function insert_check_time_constraint_prel() {        
        $objid = trim($this->input->post('objid'));
        $begda = trim($this->input->post('begda'));
        $endda = trim($this->input->post('endda'));
        $subty = trim($this->input->post('subty'));
        if (empty($objid) || empty($begda) || empty($endda) || empty($subty)  || $objid == "" || $begda == "" || $endda == "" ) {
            echo "Please Check and Fill your input first";
            exit;
        }
        $begda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($begda));
        $endda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($endda));
        echo $this->orgchart_m->check_time_constraint_prel($objid, $begda, $endda, $subty, "INSERT");
    }

    function update_check_time_constraint_prel() {
        $objid = trim($this->input->post('objid'));
        $begda = trim($this->input->post('begda'));
        $endda = trim($this->input->post('endda'));
        $subty = trim($this->input->post('subty'));
        $id_rel = trim($this->input->post('id_rel'));
        if (empty($objid) || empty($begda) || empty($endda) || empty($subty) || empty($id_rel) || $objid == "" || $begda == "" || $endda == "" || $id_rel == "") {
            echo "Please Check and Fill your input first";
            exit;
        }
        $begda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($begda));
        $endda = trim($this->global_m->convert_ddmmyyyy_yyyymmdd($endda));
        echo $this->orgchart_m->check_time_constraint_prel($objid, $begda, $endda, $subty, "UPDATE", $id_rel);
    }
    
    
    function update_prel() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_rel', 'id_rel', 'trim|required|numeric');
            $this->form_validation->set_rules('objid', 'objid', 'trim|required|numeric');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('sobid', 'sobid', 'trim|required');
            if ($this->form_validation->run()) {
                $id_rel = $this->input->post('id_rel');
                $a['OBJID'] = $iPosition = $this->input->post('objid');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SOBID'] = $this->input->post('sobid');
                $a['SUBTY'] = $this->input->post('subty');
                $oRes = $this->orgchart_m->check_time_constraint_orel($a['OBJID'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK", $id_rel);
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_master_relation where OBJID='" . $a['OBJID'] . "' AND OTYPE='O' AND SUBTY='" . $a['SUBTY'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND id_rel<>'$id_rel'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_master_relation',$sQuery);
                    $oRes = $this->orgchart_m->check_time_constraint_prel($a['OBJID'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK", $id_rel);
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_master_relation SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_rel='" . $aRow['id_rel'] . "';";
                    echo $sQuery;
                    $this->db->query($sQuery);
                }
                $a['updated_by'] = $this->session->userdata('username');
                $this->db->where('id_rel', $id_rel);
                $this->db->update('tm_master_relation', $a);
                echo "Success";
                //redirect('orgchart/tree/Y', 'refresh');
            } else {
                echo "Some parameter unavailable";
                //redirect('orgchart/tree/Y', 'refresh');
            }
        } else {
            echo "Not Authorized Access";
            //redirect('orgchart/tree/Y', 'refresh');
        }
    }

}

?>
