<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tsearch
 *
 * @author maful
 */
class tsearch extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        $this->load->model('tprofile_m');
        $this->load->model('ecs_m');
        $this->load->model('srank_m');
        $this->load->model('tsearch_m');
        $this->common->cekMethod($this->uri->segment(3));
    }

    public function index() {
        $data = $this->tsearch_m->home();

        $aJobTemp = $this->orgchart_m->getAllJob();
        $aJob = [];
        foreach ($aJobTemp as $v) {
            $aJob[] = ['id' => $v['OBJID'], 'text' => $v['STEXT'] . " (" . $v['SHORT'] . ")"];
        }
        $sJob = json_encode($aJob);
        $aPrsh = $this->common->get_abbrev(5);
        $sPrsh = json_encode($aPrsh);

        $aEmp = $this->get_emp($aPrsh);
        $sEmp = json_encode($aEmp);

        $showErrorMsg = "";
        if ($this->session->flashdata('message')) {
            $msg = $this->session->flashdata('message');
            $showErrorMsg = "$('#div_error_message').show(); $('#error_message').html('" . $msg . "')";
        }

        $data['externalCSS'] = '<link href="' . base_url() . 'css/table-responsive.css" rel="stylesheet" />';
        $data['externalCSS'] .= '<style type="text/css"> 
                                .select2-selection--multiple{
                                    overflow: hidden !important;
                                    height: auto !important;
                                }
                                </style>';
        $data['externalCSS'] .= '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';

        $data['scriptJS'] = '<script type="text/javascript">
		function format(item) {
			if (!item.id) return "<b>" + item.text + "</b>"; // optgroup
			return "&nbsp;&nbsp;&nbsp;" + item.text;
		};
		$(document).ready(function() {
                       ' . $showErrorMsg . '
			$("#fnik").select2({
                            minimumInputLength: 2,
                            delay: 300,
                            dropdownAutoWidth: true,
                            multiple: true,
                            ajax: {
                                url: "' . base_url() . 'index.php/talent/tsearch/fetch_emp/",
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
			$("#fjob").select2({
				data: ' . $sJob . ',
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#fprsh").select2({
				data: ' . $sPrsh . ',
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#fjob2").select2({
				data: ' . $sJob . ',
				dropdownAutoWidth: true
			});
			$("#fprsh2").select2({
				data: ' . $sPrsh . ',
				dropdownAutoWidth: true
			});
			$("#fpos2").select2({
                            minimumInputLength: 2,
                            delay: 300,
                            dropdownAutoWidth: true,
                            ajax: {
                                url: "' . base_url() . 'index.php/talent/tsearch/fetch_position/",
                                dataType: "json",
                                type: "POST",
                                data: function (term, page) {
                                    return {
                                        q: term,
                                        comp :$("#fprsh2").select2("val"),
                                        job :$("#fjob2").select2("val"),
                                        unit :$("#funit2").select2("val")
                                    };
                                },
                                results: function (data, page) {
                                    return {results: data};
                                },initSelection: function(element, callback) {
                                }
                            }
			});
			$("#funit2").select2({
                            minimumInputLength: 2,
                            delay: 300,
                            dropdownAutoWidth: true,
                            ajax: {
                                url: "' . base_url() . 'index.php/talent/tsearch/fetch_unit/",
                                dataType: "json",
                                type: "POST",
                                data: function (term, page) {
                                    return {
                                        q: term,
                                        comp :$("#fprsh2").select2("val")
                                    };
                                },
                                results: function (data, page) {
                                        return {results: data};
                                },initSelection: function(element, callback) {
                                }
                            }
			});
		});
		</script>';

        $this->load->view('main', $data);
    }

    function compare() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('filter', 'filter', 'trim');
            $this->form_validation->set_rules('nopeg', 'nopeg', 'trim');
            if ($this->form_validation->run()) {
                $sFilter = $this->input->post('filter');
                $oFilter = json_decode($sFilter);
                $sNopeg = $this->input->post('nopeg');
                $data = $this->tsearch_m->view_compare();
                $data['externalCSS'] = '<link href="' . base_url() . 'css/profile.css" rel="stylesheet" />';
                $data['o_company']=null;
                if(!empty($oFilter->COMPANY)){
                    $data['o_company'] = $this->global_m->get_master_abbrev("5"," AND SHORT='".$oFilter->COMPANY."'");
                }
                $data['o_unit']=null;
                if(!empty($oFilter->UNIT)){
                    $data['o_unit'] = $this->orgchart_m->get_master_org($oFilter->UNIT,"O");
                }
                $data['o_pos']=null;
                if(!empty($oFilter->POS)){
                    $data['o_pos'] = $this->orgchart_m->get_master_org($oFilter->POS,"S");
                }
                $data['o_job']=null;
                if(!empty($oFilter->JOB)){
                    $data['o_job'] = $this->orgchart_m->get_master_org($oFilter->JOB,"C");
                }
                $data['emp_map'] = $this->employee_m->get_emp_mapping($sNopeg);
                $data['aCompt'] = $this->tsearch_m->filterTalent($oFilter->JOB, $oFilter->POS);
                if(!empty($data['aCompt']['compt'])){
                    $data['aCompt'] = $data['aCompt']['compt'];
                }
                $data['eCompt'] = $this->ecs_m->getEmpCompt($sNopeg);
                $aPosCompt = $this->common->processCompt($data['aCompt'],$data['eCompt']);
                
                $aCompt = $this->ecs_m->getCompt();
                $data['comptDef'] = $this->common->masterCompt($aCompt);
                $data['posCompt'] = $aPosCompt[0];
                $data['comptSub'] = $aPosCompt[1];
                $data['comptTot'] = $aPosCompt[2];
                $data['emp'] = $this->employee_m->getEmpByPernrs([$sNopeg]);
                $data['emp'] = $data['emp'][0]; 
                $this->load->view('main', $data);
            } else {
                redirect('talent/tsearch/index', 'refresh');
            }
        } else {
            redirect('talent/tsearch/index', 'refresh');
        }
    }

    function view() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('fprsh', 'fprsh', 'trim');
            $this->form_validation->set_rules('fjob', 'fjob', 'trim');
            $this->form_validation->set_rules('fnik', 'fnik', 'trim');
            $this->form_validation->set_rules('fjob2', 'fjob2', 'trim');
            $this->form_validation->set_rules('fprsh2', 'fprsh2', 'trim');
            $this->form_validation->set_rules('funit2', 'funit2', 'trim');
            $this->form_validation->set_rules('fpos2', 'fpos2', 'trim');
            if ($this->form_validation->run()) {
                $aEmp['PERNR'] = $this->input->post('fnik');
                $aEmp['WERKS'] = $this->input->post('fprsh');
                $aEmp['STELL'] = $this->input->post('fjob');
                $filter['JOB'] = $reqJob = $this->input->post('fjob2');
                $filter['POS'] = $reqPos = $this->input->post('fpos2');
                $filter['COMPANY'] = $reqComp = $this->input->post('fprsh2');
                $filter['UNIT'] = $reqUnit = $this->input->post('funit2');
                if (empty($reqJob) && empty($reqPos)) {
                    $this->session->set_flashdata('message', "Require Job or Position Filter Selected");
                    redirect('talent/tsearch/index', 'refresh');
                }
                $aCompt = $this->tsearch_m->searchTalent($reqJob, $reqPos, $aEmp);
                if (empty($aCompt) || empty($aCompt['emp_sort'])) {
                    $this->session->set_flashdata('message', "Talent not found for those criteria");
                    redirect('talent/tsearch/index', 'refresh');
                }
                $data = $this->tsearch_m->view();
                $data['obj'] = $aCompt;
                $data['filter_req'] = $filter;
                $data['filter_emp'] = $aEmp;
                $data['filter_emp_comp'] = (empty($filter['COMPANY'])) ? null : $this->global_m->get_master_abbrev("5", " AND SHORT='" . $filter['COMPANY'] . "'");
                $data['filter_emp_job'] = (empty($filter['JOB'])) ? null : $this->orgchart_m->get_master_org($filter['JOB'], 'C');
                $data['filter_emp_pos'] = (empty($filter['POS'])) ? null : $this->orgchart_m->get_master_org($filter['POS'], 'S');
                $data['filter_emp_unit'] = (empty($filter['UNIT'])) ? null : $this->orgchart_m->get_master_org($filter['UNIT'], 'O');
                $data['emp'] = $this->employee_m->getEmpByPernrs($aCompt['pernrs']);
                $data['emp'] = $this->common->getKVArr($data['emp'], 'PERNR');

                $data['scriptJS'] = '<script type="text/javascript">
                function compare(nopeg){
                    var f = $("<form target=\"_blank\" method=\"POST\" style=\"display:none;\"></form>").attr({
                        action: "' . base_url() . 'index.php/talent/tsearch/compare"
                    }).appendTo(document.body);
                    $("<input type=\"hidden\" />").attr({name: \'filter\',value: \'' . json_encode($filter) . '\'}).appendTo(f);
                    $("<input type=\"hidden\" />").attr({name: \'nopeg\',value: nopeg}).appendTo(f);
                    f.submit();
                    f.remove();
                }
		</script>';
                $this->load->view('main', $data);
            } else {
                redirect('talent/tsearch/index', 'refresh');
            }
        } else {
            redirect('talent/tsearch/index', 'refresh');
        }
    }

    function get_emp($aPrshs) {
        $aRtn = null;
        $i = 0;
        if ($aPrshs) {
            foreach ($aPrshs as $aPrsh) {
                $aRtn[$i]["text"] = $aPrsh["text"];
                $aEmps = $this->srank_m->get_mapping_pernr($aPrsh["id"]);
                if ($aEmps) {
                    foreach ($aEmps as $aEmp) {
                        $i++;
                        $aRtn[$i]["id"] = $aEmp["id"];
                        $aRtn[$i]["text"] = $aEmp["text"];
                    }
                }
                $i++;
            }
        }

        return $aRtn;
    }

    function fetch_unit() {
        $q = $this->input->post('q');
        $abbrvComp = $this->input->post('comp');
        if (empty($abbrvComp)) {
            echo "[]";
        } else {
            $aRes = $this->tsearch_m->get_unit($q, $abbrvComp);
            echo json_encode($aRes);
        }
    }

    function fetch_position() {
        $q = $this->input->post('q');
        $comp = $this->input->post('comp');
        $org_unit = $this->input->post('unit');
        $job = $this->input->post('job');
        if (empty($comp)) {
            echo "[]";
        } else {
            $aRes = $this->tsearch_m->get_position($q, $comp, $org_unit, $job);
            echo json_encode($aRes);
        }
    }

    function fetch_emp() {
        $q = $this->input->post('q');
        $aRes = $this->tsearch_m->get_emp($q);
        echo json_encode($aRes);
    }

}
