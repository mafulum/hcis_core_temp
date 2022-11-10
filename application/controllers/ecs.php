<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ecs
 *
 * @author Garuda
 */
class ecs extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        $this->load->model('ecs_m');
		
		$this->common->cekMethod($this->uri->segment(3));
    }

    function search($sPost="N") {

		$data = $this->ecs_m->home();
		$aStell = $this->common->get_abbrev(6);
		$sStell = json_encode($aStell);
		$aGrade = $this->common->get_abbrev(7);
		$sGrade = json_encode($aGrade);

		$this->load->model('employee_m');
		$aBukrs = $this->employee_m->get_org_level();
		$sBukrs = json_encode($aBukrs);

		$data['externalCSS'] = '<link href="' . base_url() . 'css/table-responsive.css" rel="stylesheet" /><link href="' . base_url() . 'assets/jquery-token-input/styles/token-input-facebook.css" rel="stylesheet" />';
		$data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'assets/jquery-token-input/src/jquery.tokeninput.min.js"></script>';
		$data['scriptJS'] = '<script type="text/javascript">
		$(document).ready(function() {
			$("#fnik").tokenInput(
				"' . base_url() . 'index.php/ecs/getEmpJson"
			,{
				theme: "facebook"
				,method: "POST"
				,minChars: 3
				,preventDuplicates: true
				,noResultsText: "Not Found"
				,resultsFormatter: function(item){ return "<li>" + item.id + "|" + item.name + "</li>" }
			});
			$("#fjob").tokenInput(
				'.$sStell.'
			,{
				theme: "facebook"
				,minChars: 1
				,preventDuplicates: true
				,noResultsText: "Not Found"
			});
			$("#fgrade").tokenInput(
				'.$sGrade.'
			,{
				theme: "facebook"
				,minChars: 1
				,preventDuplicates: true
				,noResultsText: "Not Found"
			});
			$("#fprsh").tokenInput(
				'.$sBukrs.'
			,{
				theme: "facebook"
				,minChars: 1
				,preventDuplicates: true
				,noResultsText: "Not Found"
			});
			$("#funit").tokenInput(
				"' . base_url() . 'index.php/ecs/getUnitJson"
			,{
				theme: "facebook"
				,method: "POST"
				,minChars: 3
				,preventDuplicates: true
				,noResultsText: "Not Found"
			});
		});
		</script>';

		if($sPost<>"Y"){
			$this->load->view('main', $data);
		}else{
			if ($this->input->post()) {
				$data['scriptJS'] .= '<script type="text/javascript">
					$(document).ready(function() {
						$("#divForm").hide();
						$("#divResult").show();
					});
					</script>';

				//$this->form_validation->set_rules('id_emp', 'id_emp', 'trim|required|numeric');
				//if ($this->form_validation->run()) {
					$aSearch['e.PERNR'] = $this->input->post('fnik');
					$aSearch['o.STELL'] = $this->input->post('fjob');
					$aSearch['CONCAT(g.TRFGR,g.TRFST)'] = $this->input->post('fgrade');
					$aSearch['m.ORGEH'] = $this->input->post('fprsh');
					$aSearch['o.ORGEH'] = $this->input->post('funit');
					//print_r($aSearch); exit;
					$aRst = $this->getSearchResult($aSearch);
					$data['search'] = $aRst;
					$this->load->view('main', $data);
			} else {
				redirect('ecs/search', 'refresh');
			}
		}
    }

	private function getSearchResult($aSearch){
		$aRtn = null;

		if($aSearch){
			$sCond = "";
			foreach($aSearch as $sField => $sVal){
				if($sVal <> ""){
					$aSearch[$sField] = "'" . str_replace("#","','",$sVal) . "'";
					$sCond .= ($sCond==""?"":"AND ") . $sField . " IN(". $aSearch[$sField] .") ";
				}
			}

			$aRtn = $this->ecs_m->getResultSearch($sCond);
		}

		return $aRtn;
	}
	
	private function createDialog($data){
		$this->load->model('employee_m');
		
		$aBukrs = $this->employee_m->get_org_level();
		$sBukrs = json_encode($aBukrs);

		$data['externalCSS'] .= '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/profile.css" />';
		$data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
		$data['externalCSS'].='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';

		$data['scriptJS'] = '<script>
						function formatSel2(item){
							var tmp = item.text;
							var rtn = tmp.replace(/-/g,"");
							return rtn;
						};
						$(document).ready(function() {
							$("#selCompt").change(function(){
								var tipe = $(this).val();
								var nopeg = $("#fpernr").val();
								var plans = $("#fplans").val();
								var cont = $("#fcont").val();
								window.location = "'.base_url().'index.php/ecs/" + cont + "/" + nopeg + "/" + plans + "/" + tipe;
							});
							$("#fbukrs").select2({
								data: '. $sBukrs .'
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
									url: "'.base_url().'index.php/employee/get_plans/2",
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
						});
						</script>';

		return $data;
	}
	
	function all($sNopeg='',$sCompPos='',$iBase=2){
		// $iBase 1 = holding, 2 = Anper
		if($sNopeg<>''){
			$this->load->model('employee_m');
			if($iBase==1){
				if($this->common->cek_pihc_access()==0)
					redirect('notauth', 'refresh');
			}

			$sPrefix = '400';
			if($iBase==2){
				$aMapPernr = $this->employee_m->get_mapping_pernr_single($sNopeg);
				if($aMapPernr){
					$sOrg = $aMapPernr["ORGEH"];
					$sPrefix = '4' . substr($sOrg,1,2);
				}
			}
			$data = $this->ecs_m->all($sNopeg);
			
			$data = $this->employee_m->get_default_data($data, $sNopeg);
			$data = $this->createDialog($data);
			
			$data['emp']= $this->ecs_m->getEmp($sNopeg);
			$sCurPos = $this->ecs_m->getEmpOrg($sNopeg);
			$sCurPosText = $this->ecs_m->getPosition($sCurPos['PLANS']);
			$aPosDetail = $this->ecs_m->getPosDetail($sCurPos['PLANS']);
			
			$aEmpCompts = $this->ecs_m->getEmpCompt($sNopeg,$sPrefix);
			
			$aCompt = $this->ecs_m->getCompt($sPrefix);

			$data['posDetail'] = (isset($aPosDetail)?$aPosDetail:null);
			$data['comptDef'] = $this->masterCompt($aCompt);
			$data['empcompt'] = $aEmpCompts;
			$data['posCurrent'] = $sCurPosText;
			$data['selBase'] = $iBase;
			$data['viewcont'] = 'all';
			$sHash = $this->ecs_m->saveDataDL($data);
			$data['hash'] = $sHash;
			$this->load->view('main', $data);
		}else{
			redirect('ecs/search', 'refresh');
		}
	}

	function view($sNopeg='',$sCompPos='',$iCompt=2) {

		if($sNopeg==''){
			if($sCompPos==''){
				$this->form_validation->set_rules('pernr', 'pernr', 'trim|required');
				$this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
				
				$this->common->cekPernrAuth($this->input->post('pernr'));
				if ($this->form_validation->run()) {
					$sNopeg= $this->input->post('pernr');
					$sCompPos = $this->input->post('fplans');
					$iCompt = $this->input->post('fcompt');
				} else
					redirect('ecs/search', 'refresh');
			}
		}
		if($sNopeg<>''){
			if($iCompt==1){
				if($this->common->cek_pihc_access()==0)
					redirect('notauth', 'refresh');
			}
			
			$this->load->model('employee_m');

			$data = $this->ecs_m->view($sNopeg);
			$data = $this->employee_m->get_default_data($data, $sNopeg);

			$data = $this->createDialog($data);

			$data['emp']= $this->ecs_m->getEmp($sNopeg);
			$sCurPos = $this->ecs_m->getEmpOrg($sNopeg);
			$sCurPosText = $this->ecs_m->getPosition($sCurPos['PLANS']);

			if($sCompPos==''){
				$this->form_validation->set_rules('fplans', 'fplans', 'trim|required');
				$sCompPos = $this->input->post('fplans');
				if($sCompPos ==''){
					$sCompPos = $sCurPos['PLANS'];
				}
			}
			$aCompPosText = $this->ecs_m->getPosition($sCompPos);
			
			$sPrefix = '400';
			if($iCompt==2){
				$aMapPernr = $this->employee_m->get_mapping_pernr_single($sNopeg);
				if($aMapPernr){
					$sOrg = $aMapPernr["ORGEH"];
					$sPrefix = '4' . substr($sOrg,1,2);
				}
			}

			$aEmpCompts = $this->ecs_m->getEmpCompt($sNopeg,$sPrefix);
			$aPosCompt = null;

			//$aPosCompts = $this->ecs_m->getPosCompt($sCompPos);
			// 1. Cari Job dan Family di tm_pos_detail
			// 2. cari IS_FAM, di table tm_job_fam_config
			// 3. if IS_FAM = 0, cari COMPT, REQV di tm_job_compt dgn FAMILY IS NULL sesuai job
			// 4. else IS_FAM = 1, cari COMPT, REQV di tm_job_compt dgn FAMILY dan job yg sesuai Kalau tidak ada err msg. "Competency not defined"
			$aPosDetail = $this->ecs_m->getPosDetail($sCompPos);

			if($iCompt==1){
				// Menggunakan Kompetensi Holding
				//$aPosDetail = $this->ecs_m->getPosDetail($sCompPos);
				if($aPosDetail){
					$aJobConfig = $this->ecs_m->getJobFamConfig($aPosDetail["STELL"]);
					$aPosCompts = $this->ecs_m->getJobCompt($aPosDetail["STELL"], $aPosDetail["FAMILY"], $aJobConfig["IS_FAM"]);
					if($aPosCompts){
						$aPosCompt = $this->processCompt($aPosCompts,$aEmpCompts);
					}
				}
			}else{
				// Menggunakan Kompetensi Anak Perusahaan / Masing2 posisi
				$aPosCompts = $this->ecs_m->getPosCompt($sCompPos);
				if($aPosCompts){
					$aPosCompt = $this->processCompt($aPosCompts,$aEmpCompts);
				}
			}

			$aCompt = $this->ecs_m->getCompt($sPrefix);

			$data['comptDef'] = $this->masterCompt($aCompt);
			$data['posCurrent'] = $sCurPosText;
			$data['posCompare'] = $aCompPosText;
			$data['posDetail'] = (isset($aPosDetail)?$aPosDetail:null);
			$data['posCompt'] = $aPosCompt[0];
			$data['comptSub'] = $aPosCompt[1];
			$data['comptTot'] = $aPosCompt[2];
			$data['plansCompare'] = array("PLANS"=>$sCompPos);
			$data['selBase'] = $iCompt;
			$data['viewcont'] = 'view';

			$sHash = $this->ecs_m->saveDataDL($data);
			$data['hash'] = $sHash;

			$this->load->view('main', $data);
		}else{
			redirect('ecs/search', 'refresh');
		}
    }

	function masterCompt($aCompt){
		//$aRtn['KC']['C1'] = 'Core Competentcy';
		//$aRtn['C1']['ACH'] = 'Achievement Orientation';
		$aRtn = null;

		foreach($aCompt as $aCompt){
			$aRtn[$aCompt['OTYPE']][$aCompt['SHORT']] = $aCompt['STEXT'];
		}
		return $aRtn;
	}

	function processCompt($aPosCompts,$aEmpCompts){
		//$aRtn[0]['C1']['ACH']['Pos'] = 1;
		//$aRtn[0]['C1']['ACH']['Emp'] = 1;
		//$aRtn[0]['C1']['ACH']['Match'] = 100;
		//$aRtn[0]['C1']['ACH']['Gap'] = 0;
		//$aRtn[1]['C1']['SubM'] = 100;
		//$aRtn[1]['C1']['SubG'] = 0;
		//$aRtn[2]['TotM'] = 100;
		//$aRtn[2]['TotG'] = 0;
		$aRtn = null;
		$aEmp = array();
		foreach($aEmpCompts as $aEmpCompt){
			$aEmp[$aEmpCompt['SHORT']] = $aEmpCompt['COVAL'];
		}
		$sTmp = '';
		$iSubM = 0;
		$iSubG = 0;
		$iSub = 0;
		$iTotM = 0;
		$iTotG = 0;
		$iTot = 0;
		foreach($aPosCompts as $aPosCompt){
			$aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Pos'] = $aPosCompt['REQV'];
			$aEmp[$aPosCompt['SHORT']] = (array_key_exists($aPosCompt['SHORT'], $aEmp) ?$aEmp[$aPosCompt['SHORT']]:0);
			$aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Emp'] = $aEmp[$aPosCompt['SHORT']];

			$iDiff = $aEmp[$aPosCompt['SHORT']] - $aPosCompt['REQV'];
			if($iDiff > 0)
				$iDiff = 0;
			$iPct = $aEmp[$aPosCompt['SHORT']] / $aPosCompt['REQV'] * 100;
			if($iPct > 100)
				$iPct = 100;

			$aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Match'] = $iPct;
			$aRtn[0][$aPosCompt['OTYPE']][$aPosCompt['SHORT']]['Gap'] = $iDiff;

			if($sTmp <> $aPosCompt['OTYPE']){
				$aRtn[1][$aPosCompt['OTYPE']]['SubM'] = $iPct;
				$aRtn[1][$aPosCompt['OTYPE']]['SubG'] = $iDiff;
				$sTmp = $aPosCompt['OTYPE'];
				$iSub = 1;
			}else{
				$aRtn[1][$aPosCompt['OTYPE']]['SubM'] += $iPct;
				$aRtn[1][$aPosCompt['OTYPE']]['SubG'] += $iDiff;
				$iSub++;
			}
			$aRtn[1][$aPosCompt['OTYPE']]['Sub'] = $iSub;

			$iTotM += $iPct;
			$iTotG += $iDiff;
			$iTot++;
		}

		$aRtn[2]['TotM'] = $iTotM / $iTot;
		$aRtn[2]['TotG'] = $iTotG;

		return $aRtn;
	}

	function getEmpJson(){
		$sSearch = $this->input->post('q');

		$aSearch = $this->ecs_m->getEmpJson($sSearch);

		echo json_encode($aSearch);
	}

	function getUnitJson(){
		$sSearch = $this->input->post('q');

		$aSearch = $this->ecs_m->getUnitJson($sSearch);

		echo json_encode($aSearch);
	}
}

?>
