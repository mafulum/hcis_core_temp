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
        if (!empty($this->uri->segment(3)) && $this->uri->segment(3) != "" && $this->uri->segment(3) != "-") {
            $this->common->cekMethod($this->uri->segment(3));
        }
    }

    function fetch_emp() {
        $q = $this->input->post('q');
        $aPrsh = $this->employee_m->get_org_level();
//        var_dump($aPrsh);exit;
        $aRes = $this->employee_m->get_emp($aPrsh, $q);
        echo json_encode($aRes);
    }

    function master($sNopeg = "") {
        if (empty($sNopeg)) {
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

    function terminate() {
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

    function personal_data_addr($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->personal_data_addr($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function queries($sQuery = "") {
//        $this->input->xss
        $aRet = array();
        if (!empty($sQuery)) {
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
        format: 'dd-mm-yyyy'
    });
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    
    var startDate = nowTemp;
    var endDate = new Date(9999,11,31);
    
    var checkin = $('.dpd1').datepicker({
        format: 'dd-mm-yyyy',
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
        format: 'dd-mm-yyyy',
        onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        
        checkout.hide();
    }).data('datepicker');
    
});</script>";
        return $data;
    }
    
    function personal_data_view($sNopeg = "", $iSeq = 0) {
        $data = $this->employee_m->personal_data_view($iSeq, $sNopeg);
        $this->load->view('main', $data);
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
    
    function personal_data_addr_view($sNopeg = "", $iSeq = 0) {
        $data = $this->employee_m->personal_data_addr_view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }
    
    function personal_data_addr_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->personal_data_addr_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->personal_data_addr_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_addr_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_addr', 'id_addr', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('address_type', 'address_type', 'trim|required');
            $this->form_validation->set_rules('addr', 'addr', 'trim|required');
            $this->form_validation->set_rules('city', 'city', 'trim|required');
            $this->form_validation->set_rules('state', 'state', 'trim|required');
            $this->form_validation->set_rules('country', 'country', 'trim|required');
            if ($this->form_validation->run()) {
                $id_addr = $this->input->post('id_addr');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['ADDRESS_TYPE'] = $this->input->post('address_type');
                $a['ADDR'] = $this->input->post('addr');
                $a['CITY'] = $this->input->post('city');
                $a['STATE'] = $this->input->post('state');
                $a['COUNTRY'] = $this->input->post('country');
                $this->employee_m->personal_data_addr_upd($id_addr, $pernr, $a);
                redirect('employee/personal_data_addr/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_addr_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('address_type', 'address_type', 'trim|required');
            $this->form_validation->set_rules('addr', 'addr', 'trim|required');
            $this->form_validation->set_rules('city', 'city', 'trim|required');
            $this->form_validation->set_rules('state', 'state', 'trim|required');
            $this->form_validation->set_rules('country', 'country', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['ADDRESS_TYPE'] = $this->input->post('address_type');
                $a['ADDR'] = $this->input->post('addr');
                $a['CITY'] = $this->input->post('city');
                $a['STATE'] = $this->input->post('state');
                $a['COUNTRY'] = $this->input->post('country');
                $oRes = $this->employee_m->check_time_constraint_personal_data_addr($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['ADDRESS_TYPE'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_address where PERNR='" . $a['PERNR'] . "' AND ADDRESS_TYPE='" . $a['ADDRESS_TYPE'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND ENDDA<='" . $a['ENDDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_address',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_personal_data_addr($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['ADDRESS_TYPE'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_address SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_addr='" . $aRow['id_addr'] . "' AND ADDRESS_TYPE='" . $a['ADDRESS_TYPE'] . "' ;";
//                    echo $sQuery;exit;
                    $this->db->query($sQuery);
                }
                $this->employee_m->personal_data_addr_new($a);
                redirect('employee/personal_data_addr/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_addr_del($sNopeg, $id_addr) {
        $this->employee_m->personal_data_addr_del($id_addr, $sNopeg);
        redirect('employee/personal_data_addr/' . $sNopeg, 'refresh');
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
            $this->form_validation->set_rules('marst', 'marst', 'trim|required');
            if ($this->form_validation->run()) {
                $id_emp = $this->input->post('id_emp');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['GESCH'] = $this->input->post('gesch');
                $a['CNAME'] = $this->input->post('cname');
                $a['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('gbdat'));
                $a['GBLND'] = $this->input->post('gblnd');
                $a['MARST'] = $this->input->post('marst');
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
            $this->form_validation->set_rules('marst', 'marst', 'trim|required');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['GESCH'] = $this->input->post('gesch');
                $a['CNAME'] = $this->input->post('cname');
                $a['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('gbdat'));
                $a['GBLND'] = $this->input->post('gblnd');
                $a['MARST'] = $this->input->post('marst');
                $oRes = $this->employee_m->check_time_constraint_personal_data($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_master_emp where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_master_emp',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_personal_data($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_master_emp SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_emp='" . $aRow['id_emp'] . "';";
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

    function get_unit_cb() {
        $aRtn = null;
        $iPrsh = $this->input->post('werks');
        $ret = $this->global_m->get_master_abbrev('5', " AND SHORT='$iPrsh'");
        if (!empty($ret[0]['REF_OBJID'])) {
            $aDataUnit = $this->get_unit($ret[0]['REF_OBJID']);
//            var_dump($aDataUnit);exit;
            $i = 0;
            if ($aDataUnit) {
                foreach ($aDataUnit as $aUnit) {
                    $aRtn[$i]["id"] = $aUnit["OBJID"];
                    $aRtn[$i]["text"] = str_repeat('--', $aUnit['LEVEL']) . $aUnit["STEXT"];
                    $i++;
                }
            }
            echo json_encode($aRtn);
        } else {
            $aDataUnit = $this->get_unit($iPrsh);
            $i = 0;
            if ($aDataUnit) {
                foreach ($aDataUnit as $aUnit) {
                    $aRtn[$i]["id"] = $aUnit["OBJID"];
                    $aRtn[$i]["text"] = str_repeat('--', $aUnit['LEVEL']) . $aUnit["STEXT"];
                    $i++;
                }
            }
            echo json_encode($aRtn);
        }
    }

    function organizational_gen_cb($data) {
        $aBukrs = $this->employee_m->get_org_level();
        $aWerksAbkrs = $this->common->get_abbrev(5);
        //	$sOrgeh = $this->get_unit_cb2('11000001');
        //	$aPlans = $this->common->get_abbrev(1);
        $aBtrtl = $this->common->get_abbrev(26);
        $aPersg = $this->common->get_abbrev(3);
        $aPersk = $this->common->get_abbrev(4);
//		$aFam = $this->employee_m->get_job_fam();
        $aWerksAbkrs[] = Array("id" => "", "text" => "-", "name" => "-");
        $sWerksABkrs = json_encode($aWerksAbkrs);
        $sBtrtl = json_encode($aBtrtl);
        //	$sOrgeh = json_encode($aOrgeh);
        //	$sPlans = json_encode($aPlans);
//        $sStell = json_encode($aStell);
        $sPersg = json_encode($aPersg);
        $sPersk = json_encode($aPersk);
//		$sFam = json_encode($aFam);

        $data['scriptJS'] .= '<script>
                            function formatSel2(item){
                                    var tmp = item.text;
                                    var rtn = tmp.replace(/-/g,"");
                                    return rtn;
                            };
                            $(document).ready(function() {
                                    $("#fwerks").select2({
                                            data: ' . $sWerksABkrs . '
                                            ,dropdownAutoWidth: true
                                    });
                                    $("#fabkrs").select2({
                                            data: ' . $sWerksABkrs . '
                                            ,dropdownAutoWidth: true
                                    });
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
                                                                    werks :$("#fwerks").select2("val")
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
                                    $("#fpersg").select2({
                                            data: ' . $sPersg . '
                                            ,dropdownAutoWidth: true
                                    });
                                    $("#fpersk").select2({
                                            data: ' . $sPersk . '
                                            ,dropdownAutoWidth: true
                                    });

                            });
                            </script>
                            ';
//        $("#ffam").select2({
//									data: '. $sFam .'
//									,dropdownAutoWidth: true
//								});

        return $data;
    }

    function organizational_assignment_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->organizational_assignment_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function organizational_assignment_view($sNopeg = "", $iSeq = 0) {
        $data = $this->employee_m->organizational_assignment_view($iSeq, $sNopeg);
        $data = $this->organizational_gen_cb($data);
        $this->load->view('main', $data);
    }

    
    function organizational_assignment_fr($sNopeg = "", $iSeq = 0) {
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
            $this->form_validation->set_rules('fwerks', 'fwerks', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fabkrs', 'fabkrs', 'trim|required');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');

            if ($this->form_validation->run()) {
                $id_eorg = $this->input->post('id_eorg');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['WERKS'] = $this->input->post('fwerks');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
                $a['ABKRS'] = $this->input->post('fabkrs');
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
            $this->form_validation->set_rules('fwerks', 'fwerks', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fabkrs', 'fabkrs', 'trim');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['WERKS'] = $this->input->post('fwerks');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
                $a['ABKRS'] = $this->input->post('fabkrs');
                $a['PERSG'] = $this->input->post('fpersg');
                $a['PERSK'] = $this->input->post('fpersk');

                $oRes = $this->employee_m->check_time_constraint_organization_assignment($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_org where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_org',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_organization_assignment($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_org SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_eorg='" . $aRow['id_eorg'] . "';";
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

    function other_assignment_fr($sNopeg = "", $iSeq = 0) {
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
                $id_otheras = $this->input->post('id_otheras');
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

    function emp_grade_fr($sNopeg = "", $iSeq = 0) {
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
                $id_egrd = $this->input->post('id_egrd');
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
                $oRes = $this->employee_m->check_time_constraint_grade($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_grade where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_grade',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_grade($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_grade SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_egrd='" . $aRow['id_egrd'] . "';";
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
            //    $this->form_validation->set_rules('tanggal_mpp', 'tanggal_mpp', 'trim');
            $this->form_validation->set_rules('tanggal_pensiun', 'tanggal_pensiun', 'trim');
            if ($this->form_validation->run()) {
                $id_edat = $this->input->post('id_edat');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TanggalMasuk'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_masuk'));
                $a['TanggalPegTetap'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_peg_tetap'));
                //    $a['TanggalMPP'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_mpp'));
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
            //    $this->form_validation->set_rules('tanggal_mpp', 'tanggal_mpp', 'trim');
            $this->form_validation->set_rules('tanggal_pensiun', 'tanggal_pensiun', 'trim');
            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['TanggalMasuk'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_masuk'));
                $a['TanggalPegTetap'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_peg_tetap'));
                //        $a['TanggalMPP'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_mpp'));
                $a['TanggalPensiun'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tanggal_pensiun'));

                $oRes = $this->employee_m->check_time_constraint_date($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_date where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_date',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_date($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_date SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_edat='" . $aRow['id_edat'] . "';";
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
    function eduf_gen_cb($data) {
        $aEduf = $this->common->get_abbrev(1);
        $sEduf = json_encode($aEduf);

        $aPay = $this->common->get_abbrev(12);
        $sPay = json_encode($aPay);

        $data['scriptJS'] .= '<script>
							$(document).ready(function() {
								$("#slart").select2({
									data: ' . $sEduf . ',
									dropdownAutoWidth: true
								});
								$("#slabs").select2({
									data: ' . $sPay . '
									,dropdownAutoWidth: true
								});
							});
							</script>';

        return $data;
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
    function edunf_gen_cb($data) {
        $aEdunf = $this->common->get_abbrev(10);
        $sEdunf = json_encode($aEdunf);

        $aPay = $this->common->get_abbrev(12);
        $sPay = json_encode($aPay);

        $data['scriptJS'] .= '<script>
							$(document).ready(function() {
								$("#slart").select2({
									data: ' . $sEdunf . '
									,dropdownAutoWidth: true
								});
								$("#slabs").select2({
									data: ' . $sPay . '
									,dropdownAutoWidth: true
								});
							});
							</script>';

        return $data;
    }

    function emp_edunf_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            if ($this->input->post()) {
                if (!empty($_POST['id'])) {
                    $aIdEduc = $_POST['id'];
                    $this->employee_m->deletes_educ_nf($sNopeg, $aIdEduc);
                }
            }
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
    function awards_gen_cb($data) {
        $aAward = $this->common->get_abbrev(8);
        $sAward = json_encode($aAward);

        $data['scriptJS'] .= '<script>
							$(document).ready(function() {
								$("#awdtp").select2({
									data: ' . $sAward . '
									,dropdownAutoWidth: true
								});
							});
							</script>';

        return $data;
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
                $id_awards = $this->input->post('id_awards');
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
    function grievances_gen_cb($data) {
        $aGriev = $this->common->get_abbrev(9);
        $sGriev = json_encode($aGriev);

        $data['scriptJS'] .= '<script>
							$(document).ready(function() {
								$("#subty").select2({
									data: ' . $sGriev . '
									,dropdownAutoWidth: true
								});
							});
							</script>';

        return $data;
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

    function medical_gen_cb($data) {
        $aMed = $this->common->get_abbrev(11);
        $sMed = json_encode($aMed);

        $data['scriptJS'] .= '<script>
                                $(document).ready(function() {
                                        $("#subty").select2({
                                                data: ' . $sMed . '
                                                ,dropdownAutoWidth: true
                                        });
                                });
                                </script>';

        return $data;
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
    function compt_gen_cb($data, $sNopeg) {
        $aCompt = $this->employee_m->get_compt($sNopeg);
        $sCompt = json_encode($aCompt);

        $data['scriptJS'] .= '<script>
                                $(document).ready(function() {
                                        $("#compt").select2({
                                                data: ' . $sCompt . '
                                                ,dropdownAutoWidth: true
                                        });
                                });
                            </script>';

        return $data;
    }

    function emp_compt_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_compt_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_compt_fr($sNopeg = "", $iSeq = 0) {
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->employee_m->emp_compt_fr_update($iSeq, $sNopeg);
            $data = $this->compt_gen_cb($data, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->employee_m->emp_compt_fr_new($sNopeg);
            $data = $this->compt_gen_cb($data, $sNopeg);
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
                $id_ecom = $this->input->post('id_ecom');
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
                $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_compt where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_compt',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_compt SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_ecom='" . $aRow['id_ecom'] . "';";
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
    function compt_holding_gen_cb($data) {
        $aCompt = $this->employee_m->get_compt_holding();
        $sCompt = json_encode($aCompt);

        $data['scriptJS'] .= '<script>
							$(document).ready(function() {
								$("#compt").select2({
									data: ' . $sCompt . '
									,dropdownAutoWidth: true
								});
							});
							</script>';

        return $data;
    }

    function emp_compt_holding_ov($sNopeg) {
        if ($this->common->cek_pihc_access() == 0)
            redirect('employee/master', 'refresh');

        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->emp_compt_holding_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function emp_compt_holding_fr($sNopeg = "", $iSeq = 0) {
        if ($this->common->cek_pihc_access() == 0)
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
        if ($this->common->cek_pihc_access() == 0)
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
                $id_ecom = $this->input->post('id_ecom');
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
        if ($this->common->cek_pihc_access() == 0)
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

                $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_compt where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_compt',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_compt($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['COMPT'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_compt SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_ecom='" . $aRow['id_ecom'] . "';";
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
        if ($this->common->cek_pihc_access() == 0)
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

    function emp_perf_fr($sNopeg = "", $iSeq = 0) {
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
                $id_perf = $this->input->post('id_perf');
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

                $oRes = $this->employee_m->check_time_constraint_perf($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_perf where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_perf',$sQuery);
                    $oRes = $this->employee_m->check_time_constraint_perf($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_perf SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_perf='" . $aRow['id_perf'] . "';";
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

    function fetch_empty_position() {
        $q = $this->input->post('q');
        $org_unit = $this->input->post('comp');
        $prefix = substr($org_unit, 0, 3);
        $sQuery = "select o.OBJID,o.SHORT,o.STEXT from tm_master_relation r
JOIN tm_master_org o ON r.OBJID=o.OBJID and o.OTYPE='S' AND SUBTY='A003'
where r.SCLAS='O' and r.SOBID like '$prefix%' AND r.OTYPE='S'
AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA
AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA
AND (o.STEXT like '%$q%')
AND o.OBJID NOT IN(SELECT PLANS FROM tm_emp_org WHERE CURDATE() BETWEEN BEGDA AND ENDDA)";
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        $aRet = array();
        for ($i = 0; $i < count($aRes); $i++) {
            $a['id'] = $aRes[$i]['OBJID'];
            $a['text'] = $aRes[$i]['STEXT'];
            $aRet[] = $a;
        }
        unset($aRes);
        echo json_encode($aRet);
    }

    function add_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('fComp', 'fComp', 'trim|required');
            $this->form_validation->set_rules('fPos', 'fPos', 'trim|required');
            $this->form_validation->set_rules('fStart', 'fStart', 'trim|required');
            $this->form_validation->set_rules('fNik', 'fNik', 'trim|required');
            $this->form_validation->set_rules('fName', 'fName', 'trim|required');
            if ($this->form_validation->run()) {
                $fComp = $this->input->post('fComp');
                $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('fStart'));
                $fPos = $this->input->post('fPos');
                $oldNIK = $this->input->post('fNik');
                $nama = $this->input->post('fName');
                $pernr = $this->employee_m->add_new_employee($nama, $oldNIK, $fPos, $begda, $fComp);
                redirect('employee/master/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function get_unit_desc() {
        $iOrg = $this->input->post('id');

        $aOrg = $this->employee_m->get_unit_desc($iOrg);

        echo json_encode($aOrg);
    }

    function get_unit($iPrsh, $iLvl = 0) {
        $aRtn = array();
        if ($iPrsh == 11000001) {
            //ambil semua unit dibawahnya kecuali yg dibawah 11000019 (Anak Perusahaan)
            $iAnper = 11000019;
        } else {
            $iAnper = '';
        }

        $aOrgs = $this->employee_m->get_sub_org($iPrsh, $iAnper);
//        var_dump($aOrgs);exit;
        //$aRtn = $aOrgs;

        if ($aOrgs) {
            foreach ($aOrgs as $aOrg) {
                $aOrg['LEVEL'] = $iLvl;
                array_push($aRtn, $aOrg);
                $aTmps = [];

                if ($aOrg['OBJID'] <> $iAnper && $aOrg['OBJID'] <> $iPrsh) {
                    $aTmps = $this->get_unit($aOrg['OBJID'], $iLvl + 1);

                    if ($aTmps) {
                        foreach ($aTmps as $aTmp) {
                            array_push($aRtn, $aTmp);
                        }
                    }
                }
            }
        }
        return $aRtn;
    }

    function get_plans_desc() {
        $iPlans = $this->input->post('id');

        $aPlans = $this->employee_m->get_plans_desc($iPlans);

        echo json_encode($aPlans);
    }

    function get_plans($iType = 1) {
        $sOrg = $this->input->post('orgeh');
        $q = $this->input->post('q');
        $comp = $this->input->post('comp_objid');
        if (!empty($comp) && $comp != '10000001') {
            $iType = 0;
        } else if (substr($sOrg, 0, 2) != '10') {
            $iType = 0;
        }

        $sQuery = "SELECT o.OBJID,o.SHORT,o.STEXT " .
                "FROM tm_master_relation r " .
                "JOIN tm_master_org o ON r.OBJID=o.OBJID and o.OTYPE='S' AND SUBTY='A003' " .
                "WHERE r.SCLAS='O' and r.SOBID = '" . $sOrg . "' AND r.OTYPE='S' " .
                "AND CURDATE() BETWEEN r.BEGDA AND r.ENDDA " .
                "AND CURDATE() BETWEEN o.BEGDA AND o.ENDDA " .
                "AND (o.STEXT like '%" . $q . "%') " .
                ($iType == 1 ? "AND o.OBJID NOT IN(SELECT PLANS FROM tm_emp_org WHERE CURDATE() BETWEEN BEGDA AND ENDDA AND PLANS IS NOT NULL)" : "");
        $oRes = $this->db->query($sQuery);
        $aRes = $oRes->result_array();
        $oRes->free_result();
        $aRet = array();
        for ($i = 0; $i < count($aRes); $i++) {
            $a['id'] = $aRes[$i]['OBJID'];
            $a['text'] = $aRes[$i]['STEXT'];
            $aRet[] = $a;
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

    function emp_note_fr($sNopeg = "", $iSeq = 0) {
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
                $id_note = $this->input->post('id_note');
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

    function organizational_old_gen_cb($data) {
        $aBukrs = $this->employee_m->get_org_level();
        $aPersg = $this->common->get_abbrev(3);
        $aPersk = $this->common->get_abbrev(4);
        $aFam = $this->employee_m->get_job_fam();

        $sBukrs = json_encode($aBukrs);
        $sPersg = json_encode($aPersg);
        $sPersk = json_encode($aPersk);
        $sFam = json_encode($aFam);

        $data['scriptJS'] .= '<script>
							$(document).ready(function() {
								$("#fbukrs").select2({
									data: ' . $sBukrs . '
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
								$("#ffam").select2({
									data: ' . $sFam . '
									,dropdownAutoWidth: true
								});
							});
							</script>
							';

        return $data;
    }

    function organizational_assignment_old_ov($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $data = $this->employee_m->organizational_assignment_old_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function organizational_assignment_old_fr($sNopeg = "", $iSeq = 0) {
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
            $this->form_validation->set_rules('ffam', 'ffam', 'trim|required');

            if ($this->form_validation->run()) {
                $id_eorg = $this->input->post('id_eorg');
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
                $a['FAMILY'] = $this->input->post('ffam');
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
            $this->form_validation->set_rules('ffam', 'ffam', 'trim|required');

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
                $a['FAMILY'] = $this->input->post('ffam');
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
    function new_emp($pernr = "", $sWerks = "", $begda = "", $endda = "") {
        if (empty($pernr) && empty($sWerks) && empty($begda) && empty($endda)) {
            $data = $this->employee_m->employee_fr_new();
        } else if (!empty($pernr) && !empty($sWerks) && !empty($begda) && !empty($endda)) {
            $data = $this->employee_m->employee_fr_new_2($sWerks);
            $data['frm']['PERNR'] = $pernr;
            $data['frm']['WERKS'] = $sWerks;
            $data['frm']['unit'] = $this->employee_m->get_unit_desc($sWerks);
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
            $this->form_validation->set_rules('marst', 'marst', 'trim|required');
            $this->form_validation->set_rules('fNik', 'fNik', 'trim');
            if ($this->form_validation->run()) {
                $sComp = $this->input->post('fComp');
                $pd['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $pd['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $pd['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('gbdat'));
                $pd['CNAME'] = $this->input->post('cname');
                $pd['GESCH'] = $this->input->post('gesch');
                $pd['GBLND'] = $this->input->post('gblnd');
                $pd['MARST'] = $this->input->post('marst');
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
            $this->form_validation->set_rules('werks', 'werks', 'trim|required');
            $this->form_validation->set_rules('fabkrs', 'fabkrs', 'trim|required');
            $this->form_validation->set_rules('fbtrtl', 'fbtrtl', 'trim|required');
            $this->form_validation->set_rules('forgeh', 'forgeh', 'trim|required');
            $this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
            $this->form_validation->set_rules('fpersg', 'fpersg', 'trim|required');
            $this->form_validation->set_rules('fpersk', 'fpersk', 'trim|required');

            if ($this->form_validation->run()) {
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['WERKS'] = $this->input->post('werks');
                $a['ABKRS'] = $this->input->post('fabkrs');
                $a['BTRTL'] = $this->input->post('fbtrtl');
                $a['ORGEH'] = $this->input->post('forgeh');
                $a['PLANS'] = $this->input->post('fplans');
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
                if (!empty($_FILES['userfile']) && isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])) {
                    $len = strlen($_FILES['userfile']['name']);
                    $charDot3 = substr($_FILES['userfile']['name'], $len - 4, 1);
                    $charDot4 = substr($_FILES['userfile']['name'], $len - 5, 1);
                    if ($charDot3 == ".") {
                        $filename = $pernr . substr($_FILES['userfile']['name'], $len - 4, 4);
                    } else if ($charDot4 == ".") {
                        $filename = $pernr . substr($_FILES['userfile']['name'], $len - 5, 5);
                    }
                    $this->employee_m->update_mapping_pernr($pernr, $fNik);
                    $this->load->library('upload', array('upload_path' => 'img/photo/', 'overwrite' => true, 'allowed_types' => 'gif|jpg|png|jpeg', 'remove_spaces' => true, 'file_name' => $filename));
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

    function insert_check_time_constraint_personal_data() {
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_personal_data($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_personal_data() {
        $pernr = $this->input->post('pernrX');
        $id_emp = $this->input->post('id_empX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));

        echo $this->employee_m->check_time_constraint_personal_data($pernr, $begda, $endda, "UPDATE", $id_emp);
    }

    function insert_check_time_constraint_personal_data_addr() {
        $pernr = $this->input->post('pernrX');
        $address_type = $this->input->post('addressTypeX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_personal_data_addr($pernr, $begda, $endda, $address_type, "INSERT");
    }

    function update_check_time_constraint_personal_data_addr() {
        $pernr = $this->input->post('pernrX');
        $address_type = $this->input->post('addressTypeX');
        $id_addr = $this->input->post('id_addrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));

        echo $this->employee_m->check_time_constraint_personal_data($pernr, $begda, $endda, $address_type, "UPDATE", $id_addr);
    }

    function insert_check_time_constraint_organization_assignment() {
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_organization_assignment($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_organization_assignment() {
        $pernr = $this->input->post('pernrX');
        $id_eorg = $this->input->post('id_eorgX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_organization_assignment($pernr, $begda, $endda, "UPDATE", $id_eorg);
    }

    function insert_check_time_constraint_grade() {
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_grade($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_grade() {
        $pernr = $this->input->post('pernrX');
        $id_egrd = $this->input->post('id_egrdX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_grade($pernr, $begda, $endda, "UPDATE", $id_egrd);
    }

    function insert_check_time_constraint_date() {
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_date($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_date() {
        $pernr = $this->input->post('pernrX');
        $id_edat = $this->input->post('id_edatX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));

        echo $this->employee_m->check_time_constraint_date($pernr, $begda, $endda, "UPDATE", $id_edat);
    }

    function insert_check_time_constraint_compt() {
        $pernr = $this->input->post('pernrX');
        $compt = $this->input->post('comptX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_compt($pernr, $begda, $endda, $compt, "INSERT");
    }

    function update_check_time_constraint_compt() {
        $pernr = $this->input->post('pernrX');
        $compt = $this->input->post('comptX');
        $id_ecom = $this->input->post('id_ecomX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));

        echo $this->employee_m->check_time_constraint_compt($pernr, $begda, $endda, $compt, "UPDATE", $id_ecom);
    }

    function insert_check_time_constraint_perf() {
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->employee_m->check_time_constraint_perf($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_perf() {
        $pernr = $this->input->post('pernrX');
        $id_perf = $this->input->post('id_perfX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));

        echo $this->employee_m->check_time_constraint_perf($pernr, $begda, $endda, "UPDATE", $id_perf);
    }

    // Action PHK

    function emp_pens($sNopeg) {
        echo "PHK";
    }

    function personal_data_bank($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa/bank_m');
            $data = $this->bank_m->personal_data_bank_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }

    function personal_data_bank_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/bank_m');
        $data = $this->bank_m->personal_data_bank_fr_view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }
    
    function personal_data_bank_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/bank_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->bank_m->personal_data_bank_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->bank_m->personal_data_bank_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function insert_check_time_constraint_personal_data_bank() {
        $this->load->model('pa/bank_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->bank_m->check_time_constraint_personal_data_bank($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_personal_data_bank() {
        $this->load->model('pa/bank_m');
        $pernr = $this->input->post('pernrX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $id_emp_bank = $this->input->post('id_emp_bankX');
        echo $this->bank_m->check_time_constraint_personal_data_bank($pernr, $begda, $endda, "UPDATE", $id_emp_bank);
    }

    function personal_data_bank_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fBank', 'fBank', 'trim|required');
            $this->form_validation->set_rules('cAccount', 'cAccount', 'trim|required');
            $this->form_validation->set_rules('cName', 'cName', 'trim|required');
            $this->form_validation->set_rules('cCurr', 'cCurr', 'trim');
            $this->form_validation->set_rules('cNote', 'cNote', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa/bank_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BANK_MID'] = $this->input->post('fBank');
                $a['BANK_ACCOUNT'] = $this->input->post('cAccount');
                $a['BANK_PAYEE'] = $this->input->post('cName');
                $a['BANK_CURR'] = $this->input->post('cCurr');
                $a['BANK_NOTE'] = $this->input->post('cNote');
                $a['BANK_ORDER'] = $this->input->post('bank_order');
                $oRes = $this->bank_m->check_time_constraint_personal_data_bank($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_bank where PERNR='" . $a['PERNR'] . "' AND BEGDA>='" . $a['BEGDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_bank',$sQuery);
                    $oRes = $this->bank_m->check_time_constraint_personal_data_bank($a['PERNR'], $a['BEGDA'], $a['ENDDA'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_bank SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_emp_bank='" . $aRow['id_emp_bank'] . "';";
                    $this->db->query($sQuery);
                }

                $this->bank_m->personal_data_bank_new($a);
                redirect('employee/personal_data_bank/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_bank_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_emp_bank', 'id_emp_bank', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fBank', 'fBank', 'trim|required');
            $this->form_validation->set_rules('cAccount', 'cAccount', 'trim|required');
            $this->form_validation->set_rules('cName', 'cName', 'trim|required');
            $this->form_validation->set_rules('cCurr', 'cCurr', 'trim');
            $this->form_validation->set_rules('cNote', 'cNote', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa/bank_m');
                $id_emp_bank = $this->input->post('id_emp_bank');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['BANK_MID'] = $this->input->post('fBank');
                $a['BANK_ACCOUNT'] = $this->input->post('cAccount');
                $a['BANK_PAYEE'] = $this->input->post('cName');
                $a['BANK_CURR'] = $this->input->post('cCurr');
                $a['BANK_NOTE'] = $this->input->post('cNote');
                $a['BANK_ORDER'] = $this->input->post('bank_order');
                $this->bank_m->personal_data_bank_upd($id_emp_bank, $pernr, $a);
                redirect('employee/personal_data_bank/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_bank_del($sNopeg, $id_emp) {
        $this->load->model('pa/bank_m');
        $this->bank_m->personal_data_bank_del($id_emp, $sNopeg);
        redirect('employee/personal_data_bank/' . $sNopeg, 'refresh');
    }

    function personal_data_family($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa/fam_m');
            $data = $this->fam_m->personal_data_fam_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function personal_data_fam_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/fam_m');
        $data = $this->fam_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function personal_data_fam_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/fam_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->fam_m->personal_data_fam_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->fam_m->personal_data_fam_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_fam_update() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_emp_fam', 'id_emp_fam', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fSubty', 'fSubty', 'trim|required');
            $this->form_validation->set_rules('cObjps', 'cObjps', 'trim|required');
            $this->form_validation->set_rules('cName', 'cName', 'trim|required');
            $this->form_validation->set_rules('cGblnd', 'cGblnd', 'trim|required');
            $this->form_validation->set_rules('cGbdat', 'cGbdat', 'trim|required');
            $this->form_validation->set_rules('fNat', 'fNat', 'trim|required');
            $this->form_validation->set_rules('fCty', 'fCty', 'trim|required');
            $this->form_validation->set_rules('fWN', 'fWN', 'trim|required');
            $this->form_validation->set_rules('cDocrt', 'cDocrt', 'trim|required');
            $this->form_validation->set_rules('ffamstat', 'ffamstat', 'trim|required');
            $this->form_validation->set_rules('fIncben', 'fIncben', 'trim|required');
            $this->form_validation->set_rules('cNote', 'cNote', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa/fam_m');
                $id_emp_fam = $this->input->post('id_emp_fam');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('cGbdat'));
                $a['SUBTY'] = $this->input->post('fSubty');
                $a['GESCH'] = $this->input->post('fGesch');
                $a['GBLND'] = $this->input->post('cGblnd');
                $a['OBJPS'] = $this->input->post('cObjps');
                $a['CNAME'] = $this->input->post('cName');
                $a['GBNAT'] = $this->input->post('fNat');
                $a['GBCTY'] = $this->input->post('fCty');
                $a['IDENT'] = $this->input->post('fWN');
                $a['DOCERT'] = $this->input->post('cDocrt');
                $a['INCBEN'] = $this->input->post('fIncben');
                $a['FAMSTAT'] = $this->input->post('ffamstat');
                $a['NOTE'] = $this->input->post('cNote');
                $this->fam_m->personal_data_fam_upd($id_emp_fam, $pernr, $a);
                redirect('employee/personal_data_family/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_fam_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('fSubty', 'fSubty', 'trim|required');
            $this->form_validation->set_rules('cObjps', 'cObjps', 'trim|required');
            $this->form_validation->set_rules('cName', 'cName', 'trim|required');
            $this->form_validation->set_rules('cGblnd', 'cGblnd', 'trim|required');
            $this->form_validation->set_rules('cGbdat', 'cGbdat', 'trim|required');
            $this->form_validation->set_rules('fNat', 'fNat', 'trim|required');
            $this->form_validation->set_rules('fCty', 'fCty', 'trim|required');
            $this->form_validation->set_rules('fWN', 'fWN', 'trim|required');
            $this->form_validation->set_rules('cDocrt', 'cDocrt', 'trim|required');
            $this->form_validation->set_rules('ffamstat', 'ffamstat', 'trim|required');
            $this->form_validation->set_rules('fIncben', 'fIncben', 'trim|required');
            $this->form_validation->set_rules('cNote', 'cNote', 'trim');
            if ($this->form_validation->run()) {
                $this->load->model('pa/fam_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['GBDAT'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('cGbdat'));
                $a['SUBTY'] = $this->input->post('fSubty');
                $a['GESCH'] = $this->input->post('fGesch');
                $a['GBLND'] = $this->input->post('cGblnd');
                $a['OBJPS'] = $this->input->post('cObjps');
                $a['CNAME'] = $this->input->post('cName');
                $a['GBNAT'] = $this->input->post('fNat');
                $a['GBCTY'] = $this->input->post('fCty');
                $a['IDENT'] = $this->input->post('fWN');
                $a['DOCERT'] = $this->input->post('cDocrt');
                $a['INCBEN'] = $this->input->post('fIncben');
                $a['FAMSTAT'] = $this->input->post('ffamstat');
                $a['NOTE'] = $this->input->post('cNote');
                $oRes = $this->fam_m->check_time_constraint_personal_data_fam($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], $a['OBJPS'], "CHECK");
                if ($oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_fam where PERNR='" . $a['PERNR'] . "' AND OBJPS='" . $a['OBJPS'] . "' AND SUBTY='" . $a['SUBTY'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND ENDDA<='" . $a['ENDDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_fam',$sQuery);
                    $oRes = $this->fam_m->check_time_constraint_personal_data_fam($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], $a['OBJPS'], "CHECK");
                }
                if ($oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_fam SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id_emp_fam='" . $aRow['id_emp_fam'] . "' AND SUBTY='" . $a['SUBTY'] . "' AND OBJPS='" . $a['OBJPS'] . "' ;";
                    $this->db->query($sQuery);
                }
                $this->fam_m->personal_data_fam_new($a);
                redirect('employee/personal_data_family/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function personal_data_fam_del($sNopeg, $id_emp_fam) {
        $this->load->model('pa/fam_m');
        $this->fam_m->personal_data_fam_del($id_emp_fam, $sNopeg);
        redirect('employee/personal_data_family/' . $sNopeg, 'refresh');
    }

    function insert_check_time_constraint_personal_data_fam() {
        $this->load->model('pa/fam_m');
        $pernr = $this->input->post('pernrX');
        $objps = $this->input->post('objpsX');
        $subty = $this->input->post('subtyX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->bank_m->check_time_constraint_personal_data_bank($pernr, $begda, $endda, "INSERT");
    }

    function update_check_time_constraint_personal_data_fam() {
        $this->load->model('pa/fam_m');
        $pernr = $this->input->post('pernrX');
        $objps = $this->input->post('objpsX');
        $subty = $this->input->post('subtyX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $id_emp_bank = $this->input->post('id_emp_famX');
        echo $this->bank_m->check_time_constraint_personal_data_bank($pernr, $begda, $endda, "UPDATE", $id_emp_bank);
    }

    function emp_monitoring($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa/emp_motask_m');
            $data = $this->emp_motask_m->motask_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_monitoring_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/emp_motask_m');
        $data = $this->emp_motask_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function emp_monitoring_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/emp_motask_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->emp_motask_m->emp_monitoring_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->emp_motask_m->emp_monitoring_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_monitoring_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('mot', 'mot', 'trim|required');
            $this->form_validation->set_rules('motda', 'motda', 'trim|required');
            $this->form_validation->set_rules('skno', 'skno', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/emp_motask_m');
                $id = $this->input->post('id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['REMINDER_DATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('motda'));
                $a['REMINDER_TYPE'] = $this->input->post('mot');
                $a['SK_NO'] = $this->input->post('skno');
                $a['NOTE'] = $this->input->post('note');
                $this->emp_motask_m->emp_monitoring_upd($id, $pernr, $a);
                redirect('employee/emp_monitoring/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_monitoring_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('mot', 'mot', 'trim|required');
            $this->form_validation->set_rules('motda', 'motda', 'trim|required');
            $this->form_validation->set_rules('skno', 'skno', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/emp_motask_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['REMINDER_DATE'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('motda'));
                $a['REMINDER_TYPE'] = $this->input->post('mot');
                $a['SK_NO'] = $this->input->post('skno');
                $a['NOTE'] = $this->input->post('note');
                $oRes = $this->emp_motask_m->check_time_constraint_emp_monitoring($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['REMINDER_TYPE'], "CHECK");
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_motask where PERNR='" . $a['PERNR'] . "' AND REMINDER_TYPE='" . $a['REMINDER_TYPE'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND ENDDA<='" . $a['ENDDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_motask',$sQuery);
                    $oRes = $this->emp_motask_m->check_time_constraint_emp_monitoring($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], $a['REMINDER_TYPE'], "CHECK");
                }
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_motask SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id='" . $aRow['id'] . "' AND REMINDER_TYPE='" . $a['REMINDER_TYPE'] . "' ;";
                    $this->db->query($sQuery);
                }
                $this->emp_motask_m->emp_monitoring_new($a);
                redirect('employee/emp_monitoring/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_monitoring_del($sNopeg, $id) {
        $this->load->model('pa/emp_motask_m');
        $this->emp_motask_m->emp_monitoring_del($id, $sNopeg);
        redirect('employee/emp_monitoring/' . $sNopeg, 'refresh');
    }

    function insert_check_time_constraint_emp_monitoring() {
        $this->load->model('pa/emp_motask_m');
        $pernr = $this->input->post('pernrX');
        $mot = $this->input->post('motX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->emp_motask_m->check_time_constraint_emp_monitoring($pernr, $begda, $endda, $mot, "INSERT");
    }

    function update_check_time_constraint_emp_monitoring() {
        $this->load->model('pa/emp_motask_m');
        $pernr = $this->input->post('pernrX');
        $mot = $this->input->post('motX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $id = $this->input->post('id');
        echo $this->emp_motask_m->check_time_constraint_emp_monitoring($pernr, $begda, $endda, $mot, "UPDATE", $id);
    }


    function personal_data_comm($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa/comm_m');
            $data = $this->comm_m->comm_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_comm_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/comm_m');
        $data = $this->comm_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function emp_comm_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/comm_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->comm_m->personal_data_comm_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->comm_m->personal_data_comm_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_comm_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('usrid', 'usrid', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/comm_m');
                $id = $this->input->post('id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['USRID'] = $this->input->post('usrid');
                $a['NOTE'] = $this->input->post('note');
                $this->comm_m->comm_upd($id, $pernr, $a);
                redirect('employee/personal_data_comm/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_comm_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('usrid', 'usrid', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/comm_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['USRID'] = $this->input->post('usrid');
                $a['NOTE'] = $this->input->post('note');
                $this->comm_m->comm_new($a);
                redirect('employee/personal_data_comm/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_comm_del($sNopeg, $id) {
        $this->load->model('pa/comm_m');
        $this->comm_m->comm_del($id, $sNopeg);
        redirect('employee/personal_data_comm/' . $sNopeg, 'refresh');
    }

    function emp_personalid($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa/personalid_m');
            $data = $this->personalid_m->ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    function emp_personalid_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/personalid_m');
        $data = $this->personalid_m->page_view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }
    function emp_personalid_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/personalid_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->personalid_m->form_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->personalid_m->form_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_personalid_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('icnum', 'icnum', 'trim|required');
            $this->form_validation->set_rules('idesc', 'idesc', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/personalid_m');
                $id = $this->input->post('id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['ICNUM'] = $this->input->post('icnum');
                $a['IDESC'] = $this->input->post('idesc');
                $a['NOTE'] = $this->input->post('note');
                $this->personalid_m->personalid_upd($id, $pernr, $a);
                redirect('employee/emp_personalid/' . $pernr, 'refresh');
            } else
//                var_dump($_POST);exit;
                redirect('employee/master', 'refresh');
        } else {
//                var_dump($_POST);exit;
            redirect('employee/master', 'refresh');
        }
    }

    function emp_personalid_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('icnum', 'icnum', 'trim|required');
            $this->form_validation->set_rules('idesc', 'idesc', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/personalid_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['ICNUM'] = $this->input->post('icnum');
                $a['IDESC'] = $this->input->post('idesc');
                $a['NOTE'] = $this->input->post('note');
                $oRes = $this->personalid_m->check_time_constraint($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK");
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_personalid where PERNR='" . $a['PERNR'] . "' AND SUBTY='" . $a['SUBTY'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND ENDDA<='" . $a['ENDDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_motask',$sQuery);
                    $oRes = $this->personalid_m->check_time_constraint($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK");
                }
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_personalid SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id='" . $aRow['id'] . "' AND SUBTY='" . $a['SUBTY'] . "' ;";
                    $this->db->query($sQuery);
                }
                $this->personalid_m->personalid_new($a);
                redirect('employee/emp_personalid/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_personalid_del($sNopeg, $id) {
        $this->load->model('pa/personalid_m');
        $this->personalid_m->personalid_del($id, $sNopeg);
        redirect('employee/emp_personalid/' . $sNopeg, 'refresh');
    }

    function insert_check_time_constraint_emp_personalid() {
        $this->load->model('pa/personalid_m');
        $pernr = $this->input->post('pernr');
        $subty = $this->input->post('subty');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
        echo $this->personalid_m->check_time_constraint($pernr, $begda, $endda, $subty, "INSERT");
    }

    function update_check_time_constraint_emp_personalid() {
        $this->load->model('pa/personalid_m');
        $pernr = $this->input->post('pernr');
        $subty = $this->input->post('subty');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
        $id = $this->input->post('id');
        echo $this->personalid_m->check_time_constraint($pernr, $begda, $endda, $subty, "UPDATE", $id);
    }

    public function terminate_pernrs() {
        $this->load->model('employee_m');
        $alist = array(array('9702686', '2021-10-01', '9B'), array('9702695', '2021-10-01', '9B'), array('9702757', '2021-10-01', '9B'), array('9702806', '2021-10-01', '9B'), array('9702844', '2021-10-01', '9B'), array('9702940', '2021-10-01', '9B'), array('9702962', '2021-10-01', '9B'), array('9700391', '2021-10-01', '9F'), array('9700621', '2021-10-01', '9F'), array('9700680', '2021-10-01', '9F'), array('9700695', '2021-10-01', '9F'), array('9700807', '2021-10-01', '9F'), array('9700808', '2021-10-01', '9F'), array('9701538', '2021-10-01', '9F'), array('9706206', '2021-10-16', '9C'), array('9706486', '2021-10-01', '9C'), array('9706517', '2021-10-09', '9C'), array('9706549', '2021-10-01', '9C'), array('9706563', '2021-10-01', '9C'), array('9703277', '2021-10-01', '9B'), array('9703328', '2021-10-01', '9B'), array('9703406', '2021-10-01', '9B'), array('9703439', '2021-10-01', '9B'), array('9703475', '2021-10-01', '9B'), array('9703488', '2021-11-02', '9C'), array('9703502', '2021-10-27', '9C'), array('9703584', '2021-10-31', '9C'), array('9703775', '2021-10-01', '9B'), array('9703879', '2021-10-01', '9B'), array('9703954', '2021-10-01', '9B'), array('9704010', '2021-10-01', '9B'), array('9704035', '2021-10-01', '9B'), array('9704123', '2021-10-01', '9B'), array('9704239', '2021-10-01', '9B'), array('9704278', '2021-10-01', '9B'), array('9704279', '2021-10-01', '9B'), array('9704280', '2021-10-01', '9B'), array('9704386', '2021-10-01', '9B'), array('9704720', '2021-10-01', '9B'), array('9704723', '2021-10-01', '9B'), array('9704724', '2021-10-01', '9B'), array('9704725', '2021-10-01', '9B'), array('9704736', '2021-10-01', '9B'), array('9704737', '2021-10-01', '9B'), array('9704738', '2021-10-01', '9B'), array('9704739', '2021-10-01', '9B'), array('9705980', '2021-10-01', '9B'), array('9705983', '2021-10-01', '9B'), array('9705989', '2021-10-01', '9B'), array('9705994', '2021-10-01', '9B'), array('9706376', '2021-12-01', '9B'), array('9707330', '2021-10-01', '9B'), array('9707493', '2021-10-01', '9B'), array('9705687', '2021-10-01', '9E'), array('9705924', '2021-10-01', '9C'), array('9705925', '2021-10-01', '9C'), array('9707561', '2021-10-01', '9B'), array('9707519', '2021-10-01', '9F'), array('9707615', '2021-11-01', '9C'), array('9800369', '2021-10-01', '9B'), array('9800019', '2021-10-14', '9C'), array('9800190', '2021-10-01', '9B'), array('9800277', '2021-10-05', '9C'), array('9800529', '2021-10-01', '9B'), array('9800531', '2021-10-01', '9B'), array('9800532', '2021-10-01', '9B'), array('9800533', '2021-10-01', '9B'), array('9707851', '2021-10-16', '9C'), array('9708902', '2021-10-17', '9B'), array('9707802', '2021-10-01', '9F'), array('9707803', '2021-10-01', '9F'), array('9707804', '2021-10-01', '9F'), array('9707805', '2021-10-01', '9F'), array('9707806', '2021-10-01', '9F'), array('9707807', '2021-10-01', '9F'), array('9707808', '2021-10-01', '9F'), array('9707809', '2021-10-01', '9F'), array('9707965', '2021-10-01', '9F'), array('9707966', '2021-10-01', '9F'), array('9707967', '2021-10-01', '9F'), array('9707968', '2021-10-01', '9F'), array('9707969', '2021-10-01', '9F'), array('9707970', '2021-10-01', '9F'), array('9707971', '2021-10-01', '9F'), array('9707972', '2021-10-01', '9F'), array('9707973', '2021-10-01', '9F'), array('9707974', '2021-10-01', '9F'), array('9707975', '2021-10-01', '9F'), array('9708141', '2021-10-01', '9F'), array('9708831', '2021-10-01', '9F'), array('9708850', '2021-10-01', '9F'), array('9802104', '2021-10-24', '9C'), array('9800864', '2021-10-01', '9B'), array('9800865', '2021-10-01', '9B'), array('9800877', '2021-10-01', '9B'), array('9800881', '2021-10-01', '9B'), array('9800885', '2021-10-01', '9B'), array('9800890', '2021-10-01', '9B'), array('9800915', '2021-10-01', '9B'), array('9800916', '2021-10-01', '9B'), array('9800917', '2021-10-01', '9B'), array('9800918', '2021-10-01', '9B'), array('9800919', '2021-10-01', '9B'), array('9800929', '2021-10-09', '9C'), array('9800967', '2021-10-09', '9C'));
        foreach ($alist as $emp) {
            print_r($emp);
            $this->employee_m->terminate($emp[0], $emp[1], $emp[2]);
            echo "<br/>";
        }
    }

    function emp_sup_matriks($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa/emp_sup_matriks_m');
            $data = $this->emp_sup_matriks_m->ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_sup_matriks_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/emp_sup_matriks_m');
        $data = $this->emp_sup_matriks_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function emp_sup_matriks_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/emp_sup_matriks_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->emp_sup_matriks_m->fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->emp_sup_matriks_m->fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_sup_matriks_update() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('SUBTY', 'SUBTY', 'trim|required');
            $this->form_validation->set_rules('WERKS', 'WERKS', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/emp_sup_matriks_m');
                $id = $this->input->post('id');
                $pernr = $this->input->post('pernr');
                $a['begda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['endda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('SUBTY');
                $a['WERKS'] = $this->input->post('WERKS');
                $a['PERNR_MATRIKS'] = $this->input->post('PERNR_MATRIKS');
                $this->emp_sup_matriks_m->db_upd($id, $pernr, $a);
                redirect('employee/emp_sup_matriks/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_sup_matriks_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('SUBTY', 'SUBTY', 'trim|required');
            $this->form_validation->set_rules('WERKS', 'WERKS', 'trim|required');
            $this->form_validation->set_rules('PERNR_MATRIKS', 'PERNR_MATRIKS', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/emp_sup_matriks_m');
                $a['pernr'] = $pernr = $this->input->post('pernr');
                $a['begda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['endda'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('SUBTY');
                $a['WERKS'] = $this->input->post('WERKS');
                $a['PERNR_MATRIKS'] = $this->input->post('PERNR_MATRIKS');
                $oRes = $this->emp_sup_matriks_m->check_time_constraint($a['pernr'], $a['begda'], $a['endda'], $a['SUBTY'], "CHECK");
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_sup_matriks where pernr='" . $a['pernr'] . "' AND SUBTY='" . $a['SUBTY'] . "' AND begda>='" . $a['begda'] . "' AND endda<='" . $a['endda'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_sup_matriks',$sQuery);
                    $oRes = $this->emp_sup_matriks_m->check_time_constraint($a['pernr'], $a['begda'], $a['endda'], $a['SUBTY'], "CHECK");
                }   
                // var_dump($oRes);exit;
                // var_dump($oRes->row_array());exit;
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['begda'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_sup_matriks SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id='" . $aRow['id'] . "' AND SUBTY='" . $a['SUBTY'] . "' ;";
                    $this->db->query($sQuery);
                }
                $this->emp_sup_matriks_m->db_new($a);
                redirect('employee/emp_sup_matriks/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_sup_matriks_del($sNopeg, $id) {
        $this->load->model('pa/emp_sup_matriks_m');
        $this->emp_sup_matriks_m->db_del($id, $sNopeg);
        redirect('employee/emp_sup_matriks/' . $sNopeg, 'refresh');
    }

    function insert_check_time_constraint_emp_sup_matriks() {
        $this->load->model('pa/emp_sup_matriks_m');
        $pernr = $this->input->post('pernr');
        $subty = $this->input->post('subty');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
        echo $this->emp_sup_matriks_m->check_time_constraint($pernr, $begda, $endda, $subty, "INSERT");
    }

    function update_check_time_constraint_emp_sup_matriks() {
        $this->load->model('pa/emp_sup_matriks_m');
        $pernr = $this->input->post('pernr');
        $subty = $this->input->post('subty');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
        $id = $this->input->post('id');
        echo $this->emp_sup_matriks_m->check_time_constraint($pernr, $begda, $endda, $subty, "UPDATE", $id);
    }

    
    function emp_quota($sNopeg) {
        if (!$this->form_validation->validate($sNopeg, 'required|numeric|max_length[8]|xss_clean')) {
            redirect('employee/master', 'refresh');
        } else {
            $this->load->model('pa/emp_quota_m');
            $data = $this->emp_quota_m->quota_ov($sNopeg);
            $this->load->view('main', $data);
        }
    }
    
    function emp_quota_view($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/emp_quota_m');
        $data = $this->emp_quota_m->view($iSeq, $sNopeg);
        $this->load->view('main', $data);
    }

    function emp_quota_fr($sNopeg = "", $iSeq = 0) {
        $this->load->model('pa/emp_quota_m');
        if (!empty($iSeq) && !empty($sNopeg)) {
            $data = $this->emp_quota_m->emp_quota_fr_update($iSeq, $sNopeg);
            $this->load->view('main', $data);
        } else if (!empty($sNopeg)) {
            $data = $this->emp_quota_m->emp_quota_fr_new($sNopeg);
            $this->load->view('main', $data);
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_quota_upd() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('quota', 'quota', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/emp_quota_m');
                $id = $this->input->post('id');
                $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('SUBTY');
                $a['QUOTA'] = $this->input->post('QUOTA');
                $a['NOTE'] = $this->input->post('note');
                $this->emp_quota_m->emp_quota_upd($id, $pernr, $a);
                redirect('employee/emp_quota/' . $pernr, 'refresh');
            } else
                redirect('employee/master', 'refresh');
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_quota_new() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            $this->form_validation->set_rules('endda', 'endda', 'trim|required');
            $this->form_validation->set_rules('subty', 'subty', 'trim|required');
            $this->form_validation->set_rules('quota', 'quota', 'trim|required');
            $this->form_validation->set_rules('note', 'note', 'trim|required');
            if ($this->form_validation->run()) {
                $this->load->model('pa/emp_quota_m');
                $a['PERNR'] = $pernr = $this->input->post('pernr');
                $a['BEGDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begda'));
                $a['ENDDA'] = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('endda'));
                $a['SUBTY'] = $this->input->post('subty');
                $a['QUOTA'] = $this->input->post('quota');
                $a['NOTE'] = $this->input->post('note');
                $oRes = $this->emp_quota_m->check_time_constraint_emp_quota($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK");
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() > 0) {
                    $sQuery = "DELETE FROM tm_emp_quota where PERNR='" . $a['PERNR'] . "' AND SUBTY='" . $a['SUBTY'] . "' AND BEGDA>='" . $a['BEGDA'] . "' AND ENDDA<='" . $a['ENDDA'] . "'";
                    $this->db->query($sQuery);
                    $this->global_m->insert_log_delete('tm_emp_quota',$sQuery);
                    $oRes = $this->emp_motask_m->check_time_constraint_emp_quota($a['PERNR'], $a['BEGDA'], $a['ENDDA'], $a['SUBTY'], "CHECK");
                }
                if (!empty($oRes) && $oRes != 'null' && $oRes->num_rows() == 1) {
                    $aRow = $oRes->row_array();
                    $sQuery = "SELECT DATE_SUB('" . $a['BEGDA'] . "',INTERVAL 1 DAY) ival";
                    $oRes = $this->db->query($sQuery);
                    $aX = $oRes->row_array();
                    $sQuery = "UPDATE tm_emp_quota SET ENDDA='" . $aX['ival'] . "',updated_by = '".$this->session->userdata('username')."' WHERE id='" . $aRow['id'] . "' AND SUBTY='" . $a['SUBTY'] . "' ;";
                    $this->db->query($sQuery);
                }
                $this->emp_quota_m->emp_quota_new($a);
                redirect('employee/emp_quota/' . $pernr, 'refresh');
            } else {
                redirect('employee/master', 'refresh');
            }
        } else {
            redirect('employee/master', 'refresh');
        }
    }

    function emp_quota_del($sNopeg, $id) {
        $this->load->model('pa/emp_quota_m');
        $this->emp_quota_m->emp_quota_del($id, $sNopeg);
        redirect('employee/emp_quota/' . $sNopeg, 'refresh');
    }

    function insert_check_time_constraint_emp_quota() {
        $this->load->model('pa/emp_quota_m');
        $pernr = $this->input->post('pernrX');
        $subty = $this->input->post('subtyX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        echo $this->emp_quota_m->check_time_constraint_emp_quota($pernr, $begda, $endda, $subty, "INSERT");
    }

    function update_check_time_constraint_emp_quota() {
        $this->load->model('pa/emp_quota_m');
        $pernr = $this->input->post('pernrX');
        $subty = $this->input->post('subtyX');
        $begda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('begdaX'));
        $endda = $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('enddaX'));
        $id = $this->input->post('id');
        echo $this->emp_quota_m->check_time_constraint_emp_monitoring($pernr, $begda, $endda, $subty, "UPDATE", $id);
    }

}
?>