<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of employee
 *
 * @author Garuda
 */
class employee extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        $this->load->model('employee_m');
		if($this->uri->segment(3)<>"" && $this->uri->segment(3)<>"-")
			$this->common->cekMethod($this->uri->segment(3));
    }

    function master($sNopeg="") {
        if(empty($sNopeg)){
            $sNopeg = "10100002";
        }
        $data = $this->employee_m->home($sNopeg);
//		$data['scriptJS'] = $data['scriptJS'].'<script>
//			jQuery(document).ready(function() {
//					$("#aEmp").click(function(){
//						var sNopeg = $("#iNopeg").val();
//						location.replace("' . base_url() . 'index.php/employee/master/" + sNopeg);
//						return false;
//					});
//				});
//		</script>';
        $this->load->view('main', $data);
    }
    
    function terminate(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('terminate_date', 'terminate_date', 'trim|required');
            $this->form_validation->set_rules('fPHK', 'fPHK', 'trim|required');
            if ($this->form_validation->run()) {
                $pernr = $this->input->post('pernr');
                $terminate_date = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('terminate_date'));
                $sPHK = $this->input->post('fPHK');
                $this->employee_m->terminate($pernr, $terminate_date, $sPHK);
                redirect('employee/master', 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->personal_data_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function queries($sQuery=""){
//        $this->input->xss
        $aRet = array();
        if(!empty($sQuery)){
            $aRet = $this->employee_m->get_arr_emp($sQuery);
        }
        echo json_encode($aRet);
    }
	
    private function get_package_datepickrange($data) {
        if (empty($data['externalCSS']))
            $data['externalCSS'] = '';
        if (empty($data['externalJS']))
            $data['externalJS'] = '';
        if (empty($data['scriptJS']))
            $data['scriptJS'] = '';
        $data['externalCSS'] = $data['externalCSS'] . '<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-datepicker/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />';
        $data['externalJS'] = $data['externalJS'] . '<script type="text/javascript" src="' . base_url() . 'assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="' . base_url() . 'assets/bootstrap-daterangepicker/daterangepicker.js"></script>';
        $data['scriptJS'] = $data['scriptJS'] . "<script>
if (top.location != location) {
    top.location.href = document.location.href ;
}
$(function(){
    window.prettyPrint && prettyPrint();
    $('.default-date-picker').datepicker({
        format: 'dd/mm/yyyy'
    });
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    
    var startDate = nowTemp;
    var endDate = new Date(9999,11,31);
    
    var checkin = $('.dpd1').datepicker({
        format: 'dd/mm/yyyy',
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() > checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() + 1);
            checkout.setValue(newDate);
        }
        checkin.hide();
        $('.dpd2')[0].focus();
    }).data('datepicker');
    
    var checkout = $('.dpd2').datepicker({
        format: 'dd/mm/yyyy',
        onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        
        checkout.hide();
    }).data('datepicker');
    
});</script>";
        return $data;
    }

    function personal_data_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->personal_data_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->personal_data_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_emp', 'id_emp', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('gesch', 'gesch', 'trim|required');
            $this->form_validation->set_rules('cname', 'cname', 'trim|required');
            $this->form_validation->set_rules('gbdat', 'gbdat', 'trim|required');
            $this->form_validation->set_rules('gblnd', 'gblnd', 'trim|required');
            if ($this->form_validation->run()) {
                $id_emp = $this->input->post('id_emp');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['GESCH'] = $this->input->post('gesch');
                $a['CNAME'] = $this->input->post('cname');
                $a['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('gbdat'));
                $a['GBLND'] = $this->input->post('gblnd');
                $this->employee_m->personal_data_upd($id_emp, $pernr, $a);
                redirect('employee/personal_data_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('gesch', 'gesch', 'trim|required');
            $this->form_validation->set_rules('cname', 'cname', 'trim|required');
            $this->form_validation->set_rules('gbdat', 'gbdat', 'trim|required');
            $this->form_validation->set_rules('gblnd', 'gblnd', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['GESCH'] = $this->input->post('gesch');
                $a['CNAME'] = $this->input->post('cname');
                $a['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('gbdat'));
                $a['GBLND'] = $this->input->post('gblnd');
                $oRes = $this->employee_m->check_time_constraint_personal_data($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                if($oRes->num_rows()>0){
                    $sQuery="DELETE FROM tm_master_emp where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
                    $this->db->query($sQuery);
                    $oRes = $this->employee_m->check_time_constraint_personal_data($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                }
                if($oRes->num_rows()==1){
                    $aRow=$oRes->row_array();
                    $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
                    $oRes=$this->db->query($sQuery);
                    $aX=$oRes->row_array();
                    $sQuery="UPDATE tm_master_emp SET ENDDA='".$aX['ival']."' WHERE id_emp='".$aRow['id_emp']."';";
                    $this->db->query($sQuery);
                }
                $this->employee_m->personal_data_new($a);
                redirect('employee/personal_data_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_del($sNopeg, $id_emp) {
        $this->employee_m->personal_data_del($id_emp, $sNopeg);
        redirect('employee/personal_data_ov/' . $sNopeg, 'refresh');
    }
	
	// PA0001
	
	function get_unit_cb(){
		$aRtn = null;
		$iPrsh = $this->input->post('bukrs');

		$aDataUnit = $this->get_unit($iPrsh);
		
		$i=0;
		if($aDataUnit){
			foreach($aDataUnit as $aUnit){
				$aRtn[$i]["id"] = $aUnit["OBJID"];
				$aRtn[$i]["text"] = str_repeat('--',$aUnit['LEVEL']).$aUnit["STEXT"];
				$i++;
			}
		}

		echo json_encode($aRtn);
	}
	
	function organizational_gen_cb($data){
		$aBukrs = $this->employee_m->get_org_level();
		$aBtrtl = $this->common->get_abbrev(5);
	//	$sOrgeh = $this->get_unit_cb2('11000001');
	//	$aPlans = $this->common->get_abbrev(1);
		$aStell = $this->common->get_abbrev(6);
		$aPersg = $this->common->get_abbrev(3);
		$aPersk = $this->common->get_abbrev(4);
		
		$sBukrs = json_encode($aBukrs);
		$sBtrtl = json_encode($aBtrtl);
	//	$sOrgeh = json_encode($aOrgeh);
	//	$sPlans = json_encode($aPlans);
		$sStell = json_encode($aStell);
		$sPersg = json_encode($aPersg);
		$sPersk = json_encode($aPersk);

        $data['scriptJS'].= '<script>
							function formatSel2(item){
								var tmp = item.text;
								var rtn = tmp.replace(/-/g,"");
								return rtn;
							};
							$(document).ready(function() {
								$("#fbukrs").select2({
									data: '. $sBukrs .'
									,dropdownAutoWidth: true
								});
								$("#fbtrtl").select2({
									data: '. $sBtrtl .'
									,dropdownAutoWidth: true
								});
								$("#forgeh").select2({
									minimumInputLength: 1,
									dropdownAutoWidth: true,
									formatSelection : formatSel2,
									ajax: {
										url: "'.base_url().'index.php/employee/get_unit_cb/",
										dataType: "json",
										type: "POST",
										data: function (term, page) {
											return {
												q: term,
												bukrs :$("#fbukrs").select2("val")
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
												url: "'.base_url().'index.php/employee/get_unit_desc/",
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
										url: "'.base_url().'index.php/employee/get_plans/",
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
												url: "'.base_url().'index.php/employee/get_plans_desc/",
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
									data: '. $sStell .'
									,dropdownAutoWidth: true
								});
								$("#fpersg").select2({
									data: '. $sPersg .'
									,dropdownAutoWidth: true
								});
								$("#fpersk").select2({
									data: '. $sPersk .'
									,dropdownAutoWidth: true
								});
							});
							</script>
							';
							
		return  $data;
	}

    function organizational_assignment_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->organizational_assignment_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
	
	function organizational_assignment_fr($sNopeg = "", $iSeq = 0){
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->organizational_assignment_fr_update($iSeq, $sNopeg);
			$data = $this->organizational_gen_cb($data);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->organizational_assignment_fr_new($sNopeg);
			$data = $this->organizational_gen_cb($data);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function organizational_assignment_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_eorg', 'id_eorg', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fbukrs', 'fbukrs', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fstell', 'fstell', 'trim|required');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');
            
            if ($this->form_validation->run()) {
                $id_eorg= $this->input->post('id_eorg');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BUKRS'] = $this->input->post('fbukrs');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
                $a['STELL'] = $this->input->post('fstell');
                $a['PERSG'] = $this->input->post('fpersg');
                $a['PERSK'] = $this->input->post('fpersk');
                $this->employee_m->organizational_assignment_upd($id_eorg, $pernr, $a);
                redirect('employee/organizational_assignment_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function organizational_assignment_new() {
        if ($this->input->post()) {
            //$this->form_validation->set_rules('id_eorg', 'id_eorg', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fbukrs', 'fbukrs', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fstell', 'fstell', 'trim|required');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BUKRS'] = $this->input->post('fbukrs');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
                $a['STELL'] = $this->input->post('fstell');
                $a['PERSG'] = $this->input->post('fpersg');
                $a['PERSK'] = $this->input->post('fpersk');
                
                $oRes = $this->employee_m->check_time_constraint_organization_assignment($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                if($oRes->num_rows()>0){
                    $sQuery="DELETE FROM tm_emp_org where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
                    $this->db->query($sQuery);
                    $oRes = $this->employee_m->check_time_constraint_organization_assignment($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                }
                if($oRes->num_rows()==1){
                    $aRow=$oRes->row_array();
                    $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
                    $oRes=$this->db->query($sQuery);
                    $aX=$oRes->row_array();
                    $sQuery="UPDATE tm_emp_org SET ENDDA='".$aX['ival']."' WHERE id_eorg='".$aRow['id_eorg']."';";
                    $this->db->query($sQuery);
                }
                $this->employee_m->organizational_assignment_new($a);
                redirect('employee/organizational_assignment_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function organizational_assignment_del($sNopeg, $id_eorg) {
        $this->employee_m->organizational_assignment_del($id_eorg, $sNopeg);
        redirect('employee/organizational_assignment_ov/' . $sNopeg, 'refresh');
    }

	// ==========
	
    function other_assignment_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->other_assignment_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function other_assignment_fr($sNopeg = "", $iSeq = 0){
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->other_assignment_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->other_assignment_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function other_assignment_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_otheras', 'id_otheras', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('orgeh_text', 'orgeh_text', 'trim|required');
            $this->form_validation->set_rules('plans_text', 'plans_text', 'trim|required');
            $this->form_validation->set_rules('locat', 'locat', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim');
            if ($this->form_validation->run()) {
                $id_otheras= $this->input->post('id_otheras');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['ORGEH_TEXT'] = $this->input->post('orgeh_text');
                $a['PLANS_TEXT'] = $this->input->post('plans_text');
                $a['LOCAT'] = $this->input->post('locat');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->other_assignment_upd($id_otheras, $pernr, $a);
                redirect('employee/other_assignment_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function other_assignment_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('orgeh_text', 'orgeh_text', 'trim|required');
            $this->form_validation->set_rules('plans_text', 'plans_text', 'trim|required');
            $this->form_validation->set_rules('locat', 'locat', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['ORGEH_TEXT'] = $this->input->post('orgeh_text');
                $a['PLANS_TEXT'] = $this->input->post('plans_text');
                $a['LOCAT'] = $this->input->post('locat');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->other_assignment_new($a);
                redirect('employee/other_assignment_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function other_assignment_del($sNopeg, $id_otheras) {
        $this->employee_m->other_assignment_del($id_otheras, $sNopeg);
        redirect('employee/other_assignment_ov/' . $sNopeg, 'refresh');
    }
    
	function emp_grade_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_grade_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_grade_fr($sNopeg = "", $iSeq = 0){
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_grade_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_grade_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_grade_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_egrd', 'id_egrd', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('trfgr', 'trfgr', 'trim|required');
            $this->form_validation->set_rules('trfst', 'trfst', 'trim|required');
            if ($this->form_validation->run()) {
                $id_egrd= $this->input->post('id_egrd');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TRFGR'] = $this->input->post('trfgr');
                $a['TRFST'] = $this->input->post('trfst');
                $this->employee_m->emp_grade_upd($id_egrd, $pernr, $a);
                redirect('employee/emp_grade_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_grade_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('trfgr', 'trfgr', 'trim|required');
            $this->form_validation->set_rules('trfst', 'trfst', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TRFGR'] = $this->input->post('trfgr');
                $a['TRFST'] = $this->input->post('trfst');
                $oRes = $this->employee_m->check_time_constraint_grade($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                if($oRes->num_rows()>0){
                    $sQuery="DELETE FROM tm_emp_grade where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
                    $this->db->query($sQuery);
                    $oRes = $this->employee_m->check_time_constraint_grade($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                }
                if($oRes->num_rows()==1){
                    $aRow=$oRes->row_array();
                    $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
                    $oRes=$this->db->query($sQuery);
                    $aX=$oRes->row_array();
                    $sQuery="UPDATE tm_emp_grade SET ENDDA='".$aX['ival']."' WHERE id_egrd='".$aRow['id_egrd']."';";
                    $this->db->query($sQuery);
                }
                $this->employee_m->emp_grade_new($a);
                redirect('employee/emp_grade_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_grade_del($sNopeg, $id_egrd) {
        $this->employee_m->emp_grade_del($id_egrd, $sNopeg);
        redirect('employee/emp_grade_ov/' . $sNopeg, 'refresh');
    }

	// tambahan dari ulum
	
    function emp_date_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_date_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_date_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_date_fr_update($iSeq, $sNopeg);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_date_fr_new($sNopeg);
        } else {
            redirect('employee/master', 'refresh');
        }
        
        if (!empty($data)) {
            $data = $this->get_package_datepickrange($data);
            $this->load->view('main', $data);
        }
    }

    function emp_date_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_edat', 'id_edat', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('tanggal_masuk', 'tanggal_masuk', 'trim');
            $this->form_validation->set_rules('tanggal_peg_tetap', 'tanggal_peg_tetap', 'trim');
            $this->form_validation->set_rules('tanggal_mpp', 'tanggal_mpp', 'trim');
            $this->form_validation->set_rules('tanggal_pensiun', 'tanggal_pensiun', 'trim');
            if ($this->form_validation->run()) {
                $id_edat = $this->input->post('id_edat');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TanggalMasuk'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_masuk'));
                $a['TanggalPegTetap'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_peg_tetap'));
                $a['TanggalMPP'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_mpp'));
                $a['TanggalPensiun'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_pensiun'));
                $this->employee_m->emp_date_upd($id_edat, $pernr, $a);
                redirect('employee/emp_date_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_date_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('tanggal_masuk', 'tanggal_masuk', 'trim');
            $this->form_validation->set_rules('tanggal_peg_tetap', 'tanggal_peg_tetap', 'trim');
            $this->form_validation->set_rules('tanggal_mpp', 'tanggal_mpp', 'trim');
            $this->form_validation->set_rules('tanggal_pensiun', 'tanggal_pensiun', 'trim');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TanggalMasuk'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_masuk'));
                $a['TanggalPegTetap'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_peg_tetap'));
                $a['TanggalMPP'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_mpp'));
                $a['TanggalPensiun'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_pensiun'));
                
                $oRes = $this->employee_m->check_time_constraint_date($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                if($oRes->num_rows()>0){
                    $sQuery="DELETE FROM tm_emp_date where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
                    $this->db->query($sQuery);
                    $oRes = $this->employee_m->check_time_constraint_date($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                }
                if($oRes->num_rows()==1){
                    $aRow=$oRes->row_array();
                    $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
                    $oRes=$this->db->query($sQuery);
                    $aX=$oRes->row_array();
                    $sQuery="UPDATE tm_emp_date SET ENDDA='".$aX['ival']."' WHERE id_edat='".$aRow['id_edat']."';";
                    $this->db->query($sQuery);
                }
                $this->employee_m->emp_date_new($a);
                redirect('employee/emp_date_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_date_del($sNopeg, $id_edat) {
        $this->employee_m->emp_date_del($id_edat, $sNopeg);
        redirect('employee/emp_date_ov/' . $sNopeg, 'refresh');
    }

	// FORMAL EDUCATION
	function eduf_gen_cb($data){
		$aEduf = $this->common->get_abbrev(1);
		$sEduf = json_encode($aEduf);
		
		$aPay = $this->common->get_abbrev(12);
		$sPay = json_encode($aPay);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#slart").select2({
									data: '. $sEduf .',
									dropdownAutoWidth: true
								});
								$("#slabs").select2({
									data: '. $sPay .'
									,dropdownAutoWidth: true
								});
							});
							</script>';

		return  $data;
	}

    function emp_eduf_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_eduf_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_eduf_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $aEduc = $this->common->get_abbrev(1);
			$sEduc = json_encode($aEduc);
			$data = $this->employee_m->emp_eduf_fr_update($iSeq, $sNopeg);
			$data = $this->eduf_gen_cb($data);
        } else if (!empty($sNopeg)) {            
			$aEduc = $this->common->get_abbrev(1);
			$sEduc = json_encode($aEduc);
			
			$data = $this->employee_m->emp_eduf_fr_new($sNopeg);
			$data = $this->eduf_gen_cb($data);
        } else {
            redirect('employee/master', 'refresh');
        }
        
        if (!empty($data)) {
            $data = $this->get_package_datepickrange($data);
            $this->load->view('main', $data);
        }
    }

    function emp_eduf_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_educ', 'id_educ', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('slart', 'slart', 'trim|required');
            $this->form_validation->set_rules('insti', 'insti', 'trim');
            $this->form_validation->set_rules('sltp1', 'sltp1', 'trim');
            $this->form_validation->set_rules('slabs', 'slabs', 'trim');
            $this->form_validation->set_rules('sland', 'sland', 'trim');
            $this->form_validation->set_rules('emark', 'emark', 'trim');
            $this->form_validation->set_rules('jbez1', 'jbez1', 'trim');
            if ($this->form_validation->run()) {
                $id_educ = $this->input->post('id_educ');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['AUSBI'] = 'Formal';
                $a['SLART'] = $this->input->post('slart');
                $a['INSTI'] = $this->input->post('insti');
                $a['SLTP1'] = $this->input->post('sltp1');
                $a['SLABS'] = $this->input->post('slabs');
                $a['SLAND'] = $this->input->post('sland');
                $a['EMARK'] = $this->input->post('emark');
                $a['JBEZ1'] = $this->input->post('jbez1');
                $this->employee_m->emp_eduf_upd($id_educ, $pernr, $a);
                redirect('employee/emp_eduf_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_eduf_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('slart', 'slart', 'trim|required');
            $this->form_validation->set_rules('insti', 'insti', 'trim');
            $this->form_validation->set_rules('sltp1', 'sltp1', 'trim');
            $this->form_validation->set_rules('slabs', 'slabs', 'trim');
            $this->form_validation->set_rules('sland', 'sland', 'trim');
            $this->form_validation->set_rules('emark', 'emark', 'trim');
            $this->form_validation->set_rules('jbez1', 'jbez1', 'trim');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['AUSBI'] = 'Formal';
                $a['SLART'] = $this->input->post('slart');
                $a['INSTI'] = $this->input->post('insti');
                $a['SLTP1'] = $this->input->post('sltp1');
                $a['SLABS'] = $this->input->post('slabs');
                $a['SLAND'] = $this->input->post('sland');
                $a['EMARK'] = $this->input->post('emark');
                $a['JBEZ1'] = $this->input->post('jbez1');
                $this->employee_m->emp_eduf_new($a);
                redirect('employee/emp_eduf_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_eduf_del($sNopeg, $id_edat) {
        $this->employee_m->emp_eduf_del($id_edat, $sNopeg);
        redirect('employee/emp_eduf_ov/' . $sNopeg, 'refresh');
    }
	
	// NON FORMAL EDUCATION
	function edunf_gen_cb($data){
		$aEdunf = $this->common->get_abbrev(10);
		$sEdunf = json_encode($aEdunf);
		
		$aPay = $this->common->get_abbrev(12);
		$sPay = json_encode($aPay);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#slart").select2({
									data: '. $sEdunf .'
									,dropdownAutoWidth: true
								});
								$("#slabs").select2({
									data: '. $sPay .'
									,dropdownAutoWidth: true
								});
							});
							</script>';

		return  $data;
	}

    function emp_edunf_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_edunf_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_edunf_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_edunf_fr_update($iSeq, $sNopeg);
			$data = $this->edunf_gen_cb($data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_edunf_fr_new($sNopeg);
			$data = $this->edunf_gen_cb($data);
        } else {
            redirect('employee/master', 'refresh');
        }
        
        if (!empty($data)) {
            $data = $this->get_package_datepickrange($data);
            $this->load->view('main', $data);
        }
    }

    function emp_edunf_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_educ', 'id_educ', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('insti', 'insti', 'trim');
            $this->form_validation->set_rules('slart', 'slart', 'trim');
            $this->form_validation->set_rules('sltp1', 'sltp1', 'trim');
            $this->form_validation->set_rules('slabs', 'slabs', 'trim');
            $this->form_validation->set_rules('sland', 'sland', 'trim');
            $this->form_validation->set_rules('jbez1', 'jbez1', 'trim');
            if ($this->form_validation->run()) {
                $id_educ = $this->input->post('id_educ');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['AUSBI'] = 'Non Formal';
                $a['SLART'] = $this->input->post('slart');
                $a['INSTI'] = $this->input->post('insti');
                $a['SLTP1'] = $this->input->post('sltp1');
                $a['SLABS'] = $this->input->post('slabs');
                $a['SLAND'] = $this->input->post('sland');
                $a['JBEZ1'] = $this->input->post('jbez1');
                $this->employee_m->emp_edunf_upd($id_educ, $pernr, $a);
                redirect('employee/emp_edunf_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_edunf_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('insti', 'insti', 'trim');
            $this->form_validation->set_rules('slart', 'slart', 'trim');
            $this->form_validation->set_rules('sltp1', 'sltp1', 'trim');
            $this->form_validation->set_rules('slabs', 'slabs', 'trim');
            $this->form_validation->set_rules('sland', 'sland', 'trim');
            $this->form_validation->set_rules('jbez1', 'jbez1', 'trim');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['AUSBI'] = 'Non Formal';
                $a['SLART'] = $this->input->post('slart');
                $a['INSTI'] = $this->input->post('insti');
                $a['SLTP1'] = $this->input->post('sltp1');
                $a['SLABS'] = $this->input->post('slabs');
                $a['SLAND'] = $this->input->post('sland');
                $a['JBEZ1'] = $this->input->post('jbez1');
                $this->employee_m->emp_edunf_new($a);
                redirect('employee/emp_edunf_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_edunf_del($sNopeg, $id_edat) {
        $this->employee_m->emp_edunf_del($id_edat, $sNopeg);
        redirect('employee/emp_edunf_ov/' . $sNopeg, 'refresh');
    }
    
    // AWARDS
	function awards_gen_cb($data){
		$aAward = $this->common->get_abbrev(8);
		$sAward = json_encode($aAward);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#awdtp").select2({
									data: '. $sAward .'
									,dropdownAutoWidth: true
								});
							});
							</script>';

		return  $data;
	}

    function emp_awards_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_awards_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_awards_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_awards_fr_update($iSeq, $sNopeg);
			$data = $this->awards_gen_cb($data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_awards_fr_new($sNopeg);
        } else {
            redirect('employee/master', 'refresh');
        }
        
        if (!empty($data)) {
            $data = $this->get_package_datepickrange($data);
			$data = $this->awards_gen_cb($data);
            $this->load->view('main', $data);
        }
    }

    function emp_awards_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_awards', 'id_awards', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('awdtp', 'awdtp', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim|required');
            if ($this->form_validation->run()) {
                $id_awards= $this->input->post('id_awards');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['AWDTP'] = $this->input->post('awdtp');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->emp_awards_upd($id_awards, $pernr, $a);
                redirect('employee/emp_awards_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_awards_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('awdtp', 'awdtp', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['AWDTP'] = $this->input->post('awdtp');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->emp_awards_new($a);
                redirect('employee/emp_awards_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_awards_del($sNopeg, $id_awards) {
        $this->employee_m->emp_awards_del($id_awards, $sNopeg);
        redirect('employee/emp_awards_ov/' . $sNopeg, 'refresh');
    }

	// GRIEVANCES
	function grievances_gen_cb($data){
		$aGriev = $this->common->get_abbrev(9);
		$sGriev = json_encode($aGriev);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#subty").select2({
									data: '. $sGriev .'
									,dropdownAutoWidth: true
								});
							});
							</script>';

		return  $data;
	}
		
    function emp_grievances_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_grievances_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_grievances_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_grievances_fr_update($iSeq, $sNopeg);
			$data = $this->grievances_gen_cb($data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_grievances_fr_new($sNopeg);
			$data = $this->grievances_gen_cb($data);
        } else {
            redirect('employee/master', 'refresh');
        }
        
        if (!empty($data)) {
            $data = $this->get_package_datepickrange($data);
            $this->load->view('main', $data);
        }
    }

    function emp_grievances_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_grievances', 'id_grievances', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim|required');
            if ($this->form_validation->run()) {
                $id_grievances = $this->input->post('id_grievances');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->emp_grievances_upd($id_grievances, $pernr, $a);
                redirect('employee/emp_grievances_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_grievances_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->emp_grievances_new($a);
                redirect('employee/emp_grievances_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_grievances_del($sNopeg, $id_grievances) {
        $this->employee_m->emp_grievances_del($id_grievances, $sNopeg);
        redirect('employee/emp_grievances_ov/' . $sNopeg, 'refresh');
    }
	
	// MEDICAL
	
	function medical_gen_cb($data){
		$aMed = $this->common->get_abbrev(11);
		$sMed = json_encode($aMed);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#subty").select2({
									data: '. $sMed .'
									,dropdownAutoWidth: true
								});
							});
							</script>';

		return  $data;
	}

    function emp_medical_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_medical_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_medical_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_medical_fr_update($iSeq, $sNopeg);
			$data = $this->medical_gen_cb($data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_medical_fr_new($sNopeg);
			$data = $this->medical_gen_cb($data);
        } else {
            redirect('employee/master', 'refresh');
        }
        
        if (!empty($data)) {
            $data = $this->get_package_datepickrange($data);
            $this->load->view('main', $data);
        }
    }

    function emp_medical_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_medical', 'id_medical', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim|required');
            if ($this->form_validation->run()) {
                $id_medical = $this->input->post('id_medical');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->emp_medical_upd($id_medical, $pernr, $a);
                redirect('employee/emp_medical_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_medical_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('text1', 'text1', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['TEXT1'] = $this->input->post('text1');
                $this->employee_m->emp_medical_new($a);
                redirect('employee/emp_medical_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_medical_del($sNopeg, $id_medical) {
        $this->employee_m->emp_medical_del($id_medical, $sNopeg);
        redirect('employee/emp_medical_ov/' . $sNopeg, 'refresh');
    }
	
	// Employee Competency
	function compt_gen_cb($data,$sNopeg){
		$aCompt = $this->employee_m->get_compt($sNopeg);
		$sCompt = json_encode($aCompt);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#compt").select2({
									data: '. $sCompt .'
									,dropdownAutoWidth: true
								});
							});
							</script>';

		return  $data;
	}
	
	function emp_compt_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_compt_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_compt_fr($sNopeg = "", $iSeq = 0){
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_compt_fr_update($iSeq, $sNopeg);
			$data = $this->compt_gen_cb($data,$sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_compt_fr_new($sNopeg);
			$data = $this->compt_gen_cb($data,$sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_compt_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_ecom', 'id_ecom', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('compt', 'compt', 'trim|required');
            $this->form_validation->set_rules('coval', 'coval', 'trim|required');
            $this->form_validation->set_rules('insti', 'insti', 'trim|required');
            if ($this->form_validation->run()) {
                $id_ecom= $this->input->post('id_ecom');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['COMPT'] = $this->input->post('compt');
                $a['COVAL'] = $this->input->post('coval');
                $a['INSTI'] = $this->input->post('insti');
                $this->employee_m->emp_compt_upd($id_ecom, $pernr, $a);
                redirect('employee/emp_compt_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_compt_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('compt', 'compt', 'trim|required');
            $this->form_validation->set_rules('coval', 'coval', 'trim|required');
            $this->form_validation->set_rules('insti', 'insti', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['COMPT'] = $this->input->post('compt');
                $a['COVAL'] = $this->input->post('coval');
                $a['INSTI'] = $this->input->post('insti');
                $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'],$a['BEGDA'],$a['ENDDA'],$a['COMPT'],"CHECK");
                if($oRes->num_rows()>0){
                    $sQuery="DELETE FROM tm_emp_compt where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
                    $this->db->query($sQuery);
                    $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'],$a['BEGDA'],$a['ENDDA'],$a['COMPT'],"CHECK");
                }
                if($oRes->num_rows()==1){
                    $aRow=$oRes->row_array();
                    $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
                    $oRes=$this->db->query($sQuery);
                    $aX=$oRes->row_array();
                    $sQuery="UPDATE tm_emp_compt SET ENDDA='".$aX['ival']."' WHERE id_ecom='".$aRow['id_ecom']."';";
                    $this->db->query($sQuery);
                }
                $this->employee_m->emp_compt_new($a);
                redirect('employee/emp_compt_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_compt_del($sNopeg, $id_ecom) {
        $this->employee_m->emp_compt_del($id_ecom, $sNopeg);
        redirect('employee/emp_compt_ov/' . $sNopeg, 'refresh');
    }
	
	// Employee Competency (Holding)
	function compt_holding_gen_cb($data){
		$aCompt = $this->employee_m->get_compt_holding();
		$sCompt = json_encode($aCompt);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#compt").select2({
									data: '. $sCompt .'
									,dropdownAutoWidth: true
								});
							});
							</script>';

		return  $data;
	}
	
	function emp_compt_holding_ov($sNopeg) {
		if($this->common->cek_pihc_access() == 0)
			redirect('employee/master', 'refresh');

        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_compt_holding_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_compt_holding_fr($sNopeg = "", $iSeq = 0){
		if($this->common->cek_pihc_access() == 0)
			redirect('employee/master', 'refresh');
			
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_compt_holding_fr_update($iSeq, $sNopeg);
			$data = $this->compt_holding_gen_cb($data);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_compt_holding_fr_new($sNopeg);
			$data = $this->compt_holding_gen_cb($data);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_compt_holding_upd() {
		if($this->common->cek_pihc_access() == 0)
			redirect('employee/master', 'refresh');
			
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_ecom', 'id_ecom', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('compt', 'compt', 'trim|required');
            $this->form_validation->set_rules('coval', 'coval', 'trim|required');
			$this->form_validation->set_rules('insti', 'insti', 'trim|required');
            if ($this->form_validation->run()) {
                $id_ecom= $this->input->post('id_ecom');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['COMPT'] = $this->input->post('compt');
                $a['COVAL'] = $this->input->post('coval');
				$a['INSTI'] = $this->input->post('insti');
                $this->employee_m->emp_compt_holding_upd($id_ecom, $pernr, $a);
                redirect('employee/emp_compt_holding_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_compt_holding_new() {
		if($this->common->cek_pihc_access() == 0)
			redirect('employee/master', 'refresh');

        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('compt', 'compt', 'trim|required');
            $this->form_validation->set_rules('coval', 'coval', 'trim|required');
			$this->form_validation->set_rules('insti', 'insti', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['COMPT'] = $this->input->post('compt');
                $a['COVAL'] = $this->input->post('coval');
				$a['INSTI'] = $this->input->post('insti');                
                
                $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'],$a['BEGDA'],$a['ENDDA'],$a['COMPT'],"CHECK");
                if($oRes->num_rows()>0){
                    $sQuery="DELETE FROM tm_emp_compt where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
                    $this->db->query($sQuery);
                    $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'],$a['BEGDA'],$a['ENDDA'],$a['COMPT'],"CHECK");
                }
                if($oRes->num_rows()==1){
                    $aRow=$oRes->row_array();
                    $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
                    $oRes=$this->db->query($sQuery);
                    $aX=$oRes->row_array();
                    $sQuery="UPDATE tm_emp_compt SET ENDDA='".$aX['ival']."' WHERE id_ecom='".$aRow['id_ecom']."';";
                    $this->db->query($sQuery);
                }
                $this->employee_m->emp_compt_holding_new($a);
                redirect('employee/emp_compt_holding_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_compt_holding_del($sNopeg, $id_ecom) {
		if($this->common->cek_pihc_access() == 0)
			redirect('employee/master', 'refresh');

        $this->employee_m->emp_compt_holding_del($id_ecom, $sNopeg);
        redirect('employee/emp_compt_holding_ov/' . $sNopeg, 'refresh');
    }
	
	// Emp Performance
	
	function emp_perf_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_perf_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_perf_fr($sNopeg = "", $iSeq = 0){
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_perf_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_perf_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_perf_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_perf', 'id_perf', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('nilai', 'nilai', 'trim|required');
            if ($this->form_validation->run()) {
                $id_perf= $this->input->post('id_perf');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['NILAI'] = $this->input->post('nilai');
                $this->employee_m->emp_perf_upd($id_perf, $pernr, $a);
                redirect('employee/emp_perf_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_perf_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('nilai', 'nilai', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['NILAI'] = $this->input->post('nilai');
                
                
                $oRes = $this->employee_m->check_time_constraint_perf($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                if($oRes->num_rows()>0){
                    $sQuery="DELETE FROM tm_emp_perf where PERNR='".$a['PERNR']."' AND BEGDA>='".$a['BEGDA']."'";
                    $this->db->query($sQuery);
                    $oRes = $this->employee_m->check_time_constraint_perf($a['PERNR'],$a['BEGDA'],$a['ENDDA'],"CHECK");
                }
                if($oRes->num_rows()==1){
                    $aRow=$oRes->row_array();
                    $sQuery="SELECT DATE_SUB('".$a['BEGDA']."',INTERVAL 1 DAY) ival";
                    $oRes=$this->db->query($sQuery);
                    $aX=$oRes->row_array();
                    $sQuery="UPDATE tm_emp_perf SET ENDDA='".$aX['ival']."' WHERE id_perf='".$aRow['id_perf']."';";
                    $this->db->query($sQuery);
                }
                $this->employee_m->emp_perf_new($a);
                redirect('employee/emp_perf_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_perf_del($sNopeg, $id_perf) {
        $this->employee_m->emp_perf_del($id_perf, $sNopeg);
        redirect('employee/emp_perf_ov/' . $sNopeg, 'refresh');
    }
    
    function fetch_empty_position(){
        $q=$this->input->post('q');
        $org_unit=$this->input->post('comp');
        $prefix=  substr($org_unit,0,3);
        $sQuery="select o.OBJID,o.SHORT,o.STEXT from tm_master_relation r
JOIN tm_master_org o ON r.OBJID=o.OBJID and o.OTYPE='S' AND SUBTY='A003'
where r.SCLAS='O' and r.SOBID like '$prefix%' AND r.OTYPE='S'
AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA
AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA
AND (o.STEXT like '%$q%')
AND o.OBJID NOT IN(SELECT PLANS FROM tm_emp_org WHERE CURDATE() BETWEEN BEGDA AND ENDDA)";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        $aRet=array();
        for($i=0;$i<count($aRes);$i++){
            $a['id']=$aRes[$i]['OBJID'];
            $a['text']=$aRes[$i]['STEXT'];
            $aRet[]=$a;
        }
        unset($aRes);
        echo json_encode($aRet);
    }

    function add_new(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('fComp', 'fComp', 'trim|required');
            $this->form_validation->set_rules('fPos', 'fPos', 'trim|required');
            $this->form_validation->set_rules('fStart', 'fStart', 'trim|required');
            $this->form_validation->set_rules('fNik', 'fNik', 'trim|required');
            $this->form_validation->set_rules('fName', 'fName', 'trim|required');
            if ($this->form_validation->run()) {
                $fComp = $this->input->post('fComp');
                $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('fStart'));
                $fPos= $this->input->post('fPos');
                $oldNIK = $this->input->post('fNik');
                $nama = $this->input->post('fName');
                $pernr = $this->employee_m->add_new_employee($nama,$oldNIK,$fPos,$begda,$fComp);
                redirect('employee/master/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
	
	function get_unit_desc(){
		$iOrg = $this->input->post('id');
		
		$aOrg = $this->employee_m->get_unit_desc($iOrg);
		
		echo json_encode($aOrg);
	}
	
	function get_unit($iPrsh,$iLvl=0){
		$aRtn = array();
		if($iPrsh == 11000001){
			//PIHC
			//ambil semua unit dibawahnya kecuali yg dibawah 11000019 (Anak Perusahaan)
			$iAnper = 11000019;
		}else{
			$iAnper = '';
		}

		$aOrgs = $this->employee_m->get_sub_org($iPrsh,$iAnper);
		//$aRtn = $aOrgs;

		if($aOrgs){
			foreach($aOrgs as $aOrg){
				$aOrg['LEVEL'] = $iLvl;
				array_push($aRtn,$aOrg);
				$aTmps = null;

				if($aOrg['OBJID']<>$iAnper){
					$aTmps = $this->get_unit($aOrg['OBJID'],$iLvl +  1);
					
					if($aTmps){
						foreach($aTmps as $aTmp){
							array_push($aRtn,$aTmp);
						}
					}
				}
			}
		}

		return $aRtn;
	}
	
	function get_plans_desc(){
		$iPlans = $this->input->post('id');
		
		$aPlans = $this->employee_m->get_plans_desc($iPlans);
		
		echo json_encode($aPlans);
	}
	
	function get_plans($iType=1){
		$sOrg=$this->input->post('orgeh');
		$q=$this->input->post('q');

        $sQuery="SELECT o.OBJID,o.SHORT,o.STEXT ".
				"FROM tm_master_relation r ".
				"JOIN tm_master_org o ON r.OBJID=o.OBJID and o.OTYPE='S' AND SUBTY='A003' ".
				"WHERE r.SCLAS='O' and r.SOBID = '".$sOrg."' AND r.OTYPE='S' ".
				"AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA ".
				"AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA ".
				"AND (o.STEXT like '%".$q."%') ".
				($iType==1?"AND o.OBJID NOT IN(SELECT PLANS FROM tm_emp_org WHERE CURDATE() BETWEEN BEGDA AND ENDDA AND PLANS IS NOT NULL)":";");
//echo $sQuery;
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        $aRet=array();
        for($i=0;$i<count($aRes);$i++){
            $a['id']=$aRes[$i]['OBJID'];
            $a['text']=$aRes[$i]['STEXT'];
            $aRet[]=$a;
        }
        unset($aRes);
        echo json_encode($aRet);
	}
	
	// NOTES
	
    function emp_note_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_note_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
	
	function emp_note_fr($sNopeg = "", $iSeq = 0){
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_note_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_note_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_note_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_note', 'id_note', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('addby', 'addby', 'trim|required');
            $this->form_validation->set_rules('notes', 'notes', 'trim|required');
            
            if ($this->form_validation->run()) {
                $id_note= $this->input->post('id_note');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ADDBY'] = $this->input->post('addby');
                $a['NOTES'] = $this->input->post('notes');
                $this->employee_m->emp_note_upd($id_note, $pernr, $a);
                redirect('employee/emp_note_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_note_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('addby', 'addby', 'trim|required');
            $this->form_validation->set_rules('notes', 'notes', 'trim|required');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ADDBY'] = $this->input->post('addby');
                $a['NOTES'] = $this->input->post('notes');
                $this->employee_m->emp_note_new($a);
                redirect('employee/emp_note_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function emp_note_del($sNopeg, $id_note) {
        $this->employee_m->emp_note_del($id_note, $sNopeg);
        redirect('employee/emp_note_ov/' . $sNopeg, 'refresh');
    }
	
		// PA0001 (OLD)
	
	function organizational_old_gen_cb($data){
		$aBukrs = $this->employee_m->get_org_level();
		$aPersg = $this->common->get_abbrev(3);
		$aPersk = $this->common->get_abbrev(4);
		
		$sBukrs = json_encode($aBukrs);
		$sPersg = json_encode($aPersg);
		$sPersk = json_encode($aPersk);

        $data['scriptJS'].= '<script>
							$(document).ready(function() {
								$("#fbukrs").select2({
									data: '. $sBukrs .'
									,dropdownAutoWidth: true
								});
								$("#fpersg").select2({
									data: '. $sPersg .'
									,dropdownAutoWidth: true
								});
								$("#fpersk").select2({
									data: '. $sPersk .'
									,dropdownAutoWidth: true
								});
							});
							</script>
							';
							
		return  $data;
	}

    function organizational_assignment_old_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->organizational_assignment_old_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
	
	function organizational_assignment_old_fr($sNopeg = "", $iSeq = 0){
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->organizational_assignment_old_fr_update($iSeq, $sNopeg);
			$data = $this->organizational_old_gen_cb($data);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->organizational_assignment_old_fr_new($sNopeg);
			$data = $this->organizational_old_gen_cb($data);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function organizational_assignment_old_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_eorg', 'id_eorg', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fbukrs', 'fbukrs', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fstell', 'fstell', 'trim|required');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');
            
            if ($this->form_validation->run()) {
                $id_eorg= $this->input->post('id_eorg');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BUKRS'] = $this->input->post('fbukrs');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
                $a['STELL'] = $this->input->post('fstell');
                $a['PERSG'] = $this->input->post('fpersg');
                $a['PERSK'] = $this->input->post('fpersk');
                $this->employee_m->organizational_assignment_old_upd($id_eorg, $pernr, $a);
                redirect('employee/organizational_assignment_old_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function organizational_assignment_old_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fbukrs', 'fbukrs', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fstell', 'fstell', 'trim|required');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BUKRS'] = $this->input->post('fbukrs');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
                $a['STELL'] = $this->input->post('fstell');
                $a['PERSG'] = $this->input->post('fpersg');
                $a['PERSK'] = $this->input->post('fpersk');
                $this->employee_m->organizational_assignment_old_new($a);
                redirect('employee/organizational_assignment_old_ov/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }
    
    function organizational_assignment_old_del($sNopeg, $id_eorg) {
        $this->employee_m->organizational_assignment_old_del($id_eorg, $sNopeg);
        redirect('employee/organizational_assignment_old_ov/' . $sNopeg, 'refresh');
    }
	
	// Add New Employee
	function new_emp($pernr = "", $sBukrs = "", $begda = "", $endda = "") {
        if (empty($pernr) && empty($sBukrs) && empty($begda) && empty($endda)) {
            $data = $this->employee_m->employee_fr_new();
        } else if (!empty($pernr) && !empty($sBukrs) && !empty($begda) && !empty($endda)) {
            $data = $this->employee_m->employee_fr_new_2($sBukrs);
            $data['frm']['PERNR'] = $pernr;
            $data['frm']['SBUKRS'] = $sBukrs;
            $data['frm']['unit'] = $this->employee_m->get_unit_desc($sBukrs);
            $data['frm']['emp'] = $this->employee_m->get_master_emp_single($pernr);
            $data['frm']['BEGDA'] = $this->global_m->convert_yyyymmdd_ddmmyyyy($begda);
            $data['frm']['ENDDA'] = $this->global_m->convert_yyyymmdd_ddmmyyyy($endda);
        }
        $this->load->view('main', $data);
    }
	
	function add_new_emp_pd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('fComp', 'fComp', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('cname', 'cname', 'trim|required');
            $this->form_validation->set_rules('gesch', 'gesch', 'trim|required');
            $this->form_validation->set_rules('gbdat', 'gbdat', 'trim|required');
            $this->form_validation->set_rules('gblnd', 'gblnd', 'trim|required');
            $this->form_validation->set_rules('fNik', 'fNik', 'trim|required');
            if ($this->form_validation->run()) {
                $sComp = $this->input->post('fComp');
                $pd['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $pd['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $pd['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('gbdat'));
                $pd['CNAME'] = $this->input->post('cname');
                $pd['GESCH'] = $this->input->post('gesch');
                $pd['GBLND'] = $this->input->post('gblnd');
                $oldNik = $this->input->post('fNik');
                $pd['PERNR'] = $pernr = $this->employee_m->add_new_employee($sComp, $oldNik, $pd['BEGDA'], $pd['ENDDA']);
                $this->employee_m->personal_data_new($pd);
                redirect('employee/new_emp/' . $pernr . '/' . $sComp . '/' . $pd['BEGDA'] . '/' . $pd['ENDDA'], 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function add_new_emp_om($sPernr) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fbukrs', 'fbukrs', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fstell', 'fstell', 'trim|required');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BUKRS'] = $this->input->post('fbukrs');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
                $a['STELL'] = $this->input->post('fstell');
                $a['PERSG'] = $this->input->post('fpersg');
                $a['PERSK'] = $this->input->post('fpersk');
                $this->employee_m->organizational_assignment_new($a);
                redirect('employee/master/' . $pernr, 'refresh');
            } else {
                $b_plan['emp'] = $this->employee_m->get_master_emp_single($sPernr);
                $b_plan['MAP'] = $this->employee_m->get_mapping_pernr_single($sPernr);
                redirect('employee/new_emp/' . $sPernr . '/' . $b_plan['MAP']['ORGEH'] . '/' . $b_plan['emp']['BEGDA'] . '/' . $b_plan['emp']['ENDDA'], 'refresh');
            }
        } else {
            $b_plan['emp'] = $this->employee_m->get_master_emp_single($sPernr);
            $b_plan['MAP'] = $this->employee_m->get_mapping_pernr_single($sPernr);
            redirect('employee/new_emp/' . $sPernr . '/' . $b_plan['MAP']['ORGEH'] . '/' . $b_plan['emp']['BEGDA'] . '/' . $b_plan['emp']['ENDDA'], 'refresh');
        }
    }
	
	public function update_photo($sPernr) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('fNik', 'fNik', 'trim|required');
            if ($this->form_validation->run()) {
                $pernr = $this->input->post('pernr');
                $fNik = $this->input->post('fNik');
                if(!empty($_FILES['userfile']) && isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){
                    $len = strlen($_FILES['userfile']['name']);
                    $charDot3 = substr($_FILES['userfile']['name'], $len - 4, 1);
                    $charDot4 = substr($_FILES['userfile']['name'], $len - 5, 1);
                    if ($charDot3 == ".") {
                        $filename = $pernr . substr($_FILES['userfile']['name'], $len - 4, 4);
                    } else if ($charDot4 == ".") {
                        $filename = $pernr . substr($_FILES['userfile']['name'], $len - 5, 5);
                    }
                    $this->employee_m->update_mapping_pernr($pernr, $fNik);
                    $this->load->library('upload', array('upload_path' => 'img/photo/','overwrite'=>true, 'allowed_types' => 'gif|jpg|png|jpeg', 'remove_spaces' => true, 'file_name' => $filename));
                    $resUpload = $this->upload->do_upload('userfile');
                    if ($resUpload == false) {
                        $msg = $this->upload->display_errors();
                    }
                }
                redirect('employee/master/' . $pernr, 'refresh');
            } else
                redirect('employee/master/' . $sPernr, 'refresh');
        } else {
            redirect('employee/master/' . $sPernr, 'refresh');
        }
    }
    
    function insert_check_time_constraint_personal_data(){
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_personal_data($pernr,$begda,$endda,"INSERT");
    }
    function update_check_time_constraint_personal_data(){
        $pernr = $this->input->post('pernrX');
        $id_emp = $this->input->post('id_empX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        
        echo $this->employee_m->check_time_constraint_personal_data($pernr,$begda,$endda,"UPDATE",$id_emp);
    }
    
    
    function insert_check_time_constraint_organization_assignment(){
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_organization_assignment($pernr,$begda,$endda,"INSERT");
    }
    function update_check_time_constraint_organization_assignment(){
        $pernr = $this->input->post('pernrX');
        $id_eorg = $this->input->post('id_eorgX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_organization_assignment($pernr,$begda,$endda,"UPDATE",$id_eorg);
    }

    function insert_check_time_constraint_grade(){
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_grade($pernr,$begda,$endda,"INSERT");
    }
    function update_check_time_constraint_grade(){
        $pernr = $this->input->post('pernrX');
        $id_egrd = $this->input->post('id_egrdX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        
        echo $this->employee_m->check_time_constraint_grade($pernr,$begda,$endda,"UPDATE",$id_egrd);
    }

    
    
    function insert_check_time_constraint_date(){
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_date($pernr,$begda,$endda,"INSERT");
    }
    function update_check_time_constraint_date(){
        $pernr = $this->input->post('pernrX');
        $id_edat = $this->input->post('id_edatX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        
        echo $this->employee_m->check_time_constraint_date($pernr,$begda,$endda,"UPDATE",$id_edat);
    }

    
    
    function insert_check_time_constraint_compt(){
        $pernr = $this->input->post('pernrX');
        $compt = $this->input->post('comptX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_compt($pernr,$begda,$endda,$compt,"INSERT");
    }
    function update_check_time_constraint_compt(){
        $pernr = $this->input->post('pernrX');
        $compt = $this->input->post('comptX');
        $id_ecom= $this->input->post('id_ecomX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        
        echo $this->employee_m->check_time_constraint_compt($pernr,$begda,$endda,$compt,"UPDATE",$id_ecom);
    }    
    
    function insert_check_time_constraint_perf(){
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_perf($pernr,$begda,$endda,"INSERT");
    }
	
    function update_check_time_constraint_perf(){
        $pernr = $this->input->post('pernrX');
        $id_perf= $this->input->post('id_perfX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        
        echo $this->employee_m->check_time_constraint_perf($pernr,$begda,$endda,"UPDATE",$id_perf);
    }

	// Action PHK
	
	function emp_pens($sNopeg){
		echo "PHK";
	}


}

?>
