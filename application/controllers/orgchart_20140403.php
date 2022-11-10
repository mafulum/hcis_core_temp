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
        $data['externalJS'] = '<script src="' . base_url() . 'assets/fuelux/js/tree.min.js"></script>';
        $data['scriptJS'] = '<script>
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
												//console.log(response.data);
										},
										error: function (response) {
											console.log(response);
										}
									})
								}
							};

							$(\'#FlatTree\').tree({
								dataSource: new DataSourceTree({ url: "' . base_url() . 'index.php/orgchart/loadData" }),
								multiSelect: false,
								loadingHTML: \'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>\',
								\'selectable\': false
							});
						}
					};
				}();
				function orgEdit(param,obj_id,obj_text,obj_short,begda,endda,priox){
					if(param=="A"){
						$("#headOrg").html("Add Organization on "+obj_text);
                                                $("#iOrg").val("-1");
                                                $("#iParentOrg").val(obj_id);
                                                $("#cOrgText").val("");
                                                $("#cOrgShort").val("");
                                                $("#dOrgBegda").val("");
                                                $("#dOrgEndda").val("");
                                                $("#cOrgPriox").val("");
					}else{
						$("#headOrg").html("Edit Organization "+obj_text);
                                                $("#iOrg").val(obj_id);
                                                $("#iParentOrg").val("-1");
                                                $("#cOrgText").val("" + obj_text);
                                                $("#cOrgShort").val("" + obj_short );
                                                $("#dOrgBegda").val(begda);
                                                $("#dOrgEndda").val(endda);
                                                $("#cOrgPriox").val("" + priox);
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
					$("#fOrg").submit();
					return false;
				}
				
				function posEdit(param,obj_id,obj_text,obj_short,begda,endda,priox){
					if(param=="A"){
                                        $("#headPos").html("Add Position on "+obj_text);
                                                $("#iPosition").val("-1");
                                                $("#iParentPos").val(""+obj_id);
                                                $("#cPosText").val("");
                                                $("#cPosShort").val("");
                                                $("#dPosBegda").val("");
                                                $("#dPosEndda").val("");
                                                $("#cPosPriox").val("");
					}else{
						$("#headPos").html("Edit Position at "+obj_text);
                                                $("#iPosition").val(obj_id);
                                                $("#iParentPos").val("-1");
                                                $("#cPosText").val("" + obj_text);
                                                $("#cPosShort").val("" + obj_short );
                                                $("#dPosBegda").val(begda);
                                                $("#dPosEndda").val(endda);
                                                $("#cPosPriox").val("" + priox);
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
					$("#fPos").submit();
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
        if ($sID == 0)
            $sID = 10000000;
        $sDate = '20140101';
        $idx1 = 0;

        if ($sltype == 'P') {
            // Load Pegawai
            $aPSubs = $this->orgchart_m->get_sub_emp($sID, $sDate);
            if ($aPSubs) {
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
        } else {
            if ($sStaff == 'Y') {
                // Load Posisi
                $aSSubs = $this->orgchart_m->get_sub_pos($sID, $sDate);
                if ($aSSubs) {
                    foreach ($aSSubs as $idx1 => $aSSub) {
                        $aParam = null;
                        $aData[$idx1]['id'] = intval($aSSub['OBJID']);
                        $aData[$idx1]['name'] = '<span style="color:#1CAADC"><i class="fa fa-dot-circle-o"></i> ' . $aSSub['STEXT'] . '</span>' . 
                                ' <div class="tree-actions"><a href="#" onClick="posEdit(\'E\',\'' . $aSSub['OBJID'] . '\',\'' . $aSSub['STEXT'] . '\',\'' . $aSSub['SHORT'] . '\',\'' . $aSSub['BEGDA'] . '\',\'' . $aSSub['ENDDA'] . '\',\'' . $aSSub['PRIOX'] . '\');">'.
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
            $aSubs = $this->orgchart_m->get_sub_org($sID, $sDate);
            if ($aSubs) {
                // Load Organisasi
                foreach ($aSubs as $idx => $aSub) {
                    $idx2 = $idx1 + $idx + 1;
                    $aParam = null;
                    $aData[$idx2]['id'] = intval($aSub['OBJID']);
                    $aData[$idx2]['name'] = $aSub['STEXT'] . ' <div class="tree-actions">' . ($sStaff == 'Y' ? '<a href="#" onClick="posEdit(\'A\',\'' . $aSub['OBJID'] . '\',\'' . $aSub['STEXT'] . '\');"><i class="fa fa-dot-circle-o"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;' : '') .
                            '<a href="#" onClick="orgEdit(\'A\',\'' . $aSub['OBJID'] . '\',\'' . $aSub['STEXT'] . '\');"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;' .
                            '<a href="#" onClick="orgEdit(\'E\',\'' . $aSub['OBJID'] . '\',\'' . $aSub['STEXT'] . '\',\'' . $aSub['SHORT'] . '\',\'' . $aSub['BEGDA'] . '\',\'' . $aSub['ENDDA'] . '\',\'' . $aSub['PRIOX'] . '\');"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; </div>';
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
                $a['BEGDA'] = $this->input->post('begda');
                $a['ENDDA'] = $this->input->post('endda');
                $a['SHORT'] = $this->input->post('cOrgShort');
                $a['STEXT'] = $this->input->post('cOrgText');
                $a['PRIOX'] = $this->input->post('cOrgPriox');
                $this->orgchart_m->org_upd($iOrg, $iParentOrg, $a);
                redirect('orgchart/tree/N', 'refresh');
            } else
                redirect('orgchart/tree/N', 'refresh');
        } else {
            redirect('orgchart/tree/N', 'refresh');
        }
    }
    
    function update_pos(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('iPosition', 'iPosition', 'trim|required|numeric');
            $this->form_validation->set_rules('iParentPos', 'iParentPos', 'trim|required|numeric');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('cPosShort', 'cPosShort', 'trim');
            $this->form_validation->set_rules('cPosText', 'cPosText', 'trim');
            $this->form_validation->set_rules('cPosPriox', 'cPosPriox', 'trim');
            if ($this->form_validation->run()) {            
                $iPosition = $this->input->post('iPosition');
                $iParentPos = $this->input->post('iParentPos');
                $a['BEGDA'] = $this->input->post('begda');
                $a['ENDDA'] = $this->input->post('endda');
                $a['SHORT'] = $this->input->post('cPosShort');
                $a['STEXT'] = $this->input->post('cPosText');
                $a['PRIOX'] = $this->input->post('cPosPriox');
                $this->orgchart_m->pos_upd($iPosition, $iParentPos, $a);
                redirect('orgchart/tree/Y', 'refresh');
            } else
                redirect('orgchart/tree/Y', 'refresh');
        } else {
            redirect('orgchart/tree/Y', 'refresh');
        }
    }

    //**END OF UPDATE 2014-03-31
}

?>
