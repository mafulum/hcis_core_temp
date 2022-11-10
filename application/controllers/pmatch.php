<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pmatch
 *
 * @author Garuda
 */
class pmatch extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        $this->load->model('pmatch_m');
    }

    function search() {
		$data = $this->pmatch_m->home();
		$aGrade = $this->common->get_abbrev(7);
		$sGrade = json_encode($aGrade);

		$aJob = $this->common->get_abbrev(6);
		$sJob = json_encode($aJob);
		$aPrsh = $this->pmatch_m->get_org_level();
		$sPrsh = json_encode($aPrsh);
		$aFam = $this->pmatch_m->get_jobfam();
		$sFam = json_encode($aFam);
		
		$aEmp = $this->get_emp($aPrsh);
	//	print_r($aEmp); exit();
		$sEmp = json_encode($aEmp);

		$data['externalCSS'] = '<link href="' . base_url() . 'css/table-responsive.css" rel="stylesheet" />';
		$data['externalCSS'] .='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
		$data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        
		$data['scriptJS'] = '<script type="text/javascript">
		var dataGrade = '.$sGrade.';
		function format(item) { 
			if (!item.id) return "<b>" + item.text + "</b>"; // optgroup
			return "&nbsp;&nbsp;&nbsp;" + item.text;
		};
		$(document).ready(function() {
			$("#fnik").select2({
				data: '. $sEmp .',
				formatResult : format,
				dropdownAutoWidth: true
			});
			$("#fjob2").select2({
				data: '. $sJob .',
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#fprsh2").select2({
				data: '. $sPrsh .',
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#fpos2").select2({
				minimumInputLength: 1,
				dropdownAutoWidth: true,
				multiple: true,
				ajax: {
					url: "'.base_url().'index.php/pmatch/fetch_position/",
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
			$("#ffam2").select2({
				data: '. $sFam .',
				multiple: true,
				dropdownAutoWidth: true
			});
		});
		</script>';

		$this->load->view('main', $data);
    }
	
	function get_emp($aPrshs){
		$aRtn = null;
		$i=0;
		if($aPrshs){
			foreach($aPrshs as $aPrsh){
				$aRtn[$i]["text"] = $aPrsh["text"];
				$aPos = $this->pmatch_m->get_mapping_pernr($aPrsh["id"]);
				if($aPos){
					foreach($aPos as $aEmp){
						$i++;
						$aRtn[$i]["id"] = $aEmp["id"];
						//$aRtn[$i]["text"] = $aEmp["text"];
						$aRtn[$i]["text"] = $aEmp["id"] . " | " . $aEmp["text"];
					}
				}
				$i++;
			}
		}

		return $aRtn;
	}
	
	function fetch_position(){
        $q=$this->input->post('q');
        $org_unit=$this->input->post('comp');
		if($org_unit==""){
			echo "[]";
		}else{
			$aRes = $this->pmatch_m->get_position($q,$org_unit);
			echo json_encode($aRes);
		}
    }

	function view() {
		$sNik = $this->input->post('fnik');

		$this->common->cekPernrAuth($sNik);
		
		$sPos = $this->input->post('fpos2'); // $sPos = $this->postToString($sPos); 
		$sJob = $this->input->post('fjob2'); $sJob = $this->postToString($sJob);
		$sPrsh = $this->input->post('fprsh2'); // $sPrsh = $this->postToString($sPrsh);
		$iBase = $this->input->post('fbase');
		$iBaseCompt = $this->input->post('fcompt');

		if($sNik<>""){
			// Cek apakah ada pending batch buat PERNR?
			// jika ada execute lgs
			
			$data = $this->pmatch_m->view();
			
			//position selection
			$aPosSel = $this->pmatch_m->get_pos_selection($sPrsh,$sJob,$sPos);
			
			//employee detail			
			$aEmp = $this->pmatch_m->get_detail_emp($sNik);
			$data['emp'] = $aEmp;
			$data['nik'] = $sNik;
			$aMdg = $this->pmatch_m->get_mdg($sNik);
			$data['mdg'] = (!$aMdg?"Grade : -":"Grade : ". $aMdg["TRFGR"] . $aMdg["TRFST"] . " (".$aMdg["MDGY"]."y ".$aMdg["MDGM"]."m)");

			$sAPos = $aPosSel;
			
			if($iBase == 1){
				//Filter Matrix Job Score
				$sAPos = "";
			}

			//get position(s)
			$aPos = $this->pmatch_m->get_pos_readiness($sNik,$iBaseCompt,$sAPos);
			$aCriteria = $this->pmatch_m->get_emp_criteria($sNik);
			$aEmpCompt = $this->pmatch_m->get_emp_plans($sNik,$iBaseCompt,$sAPos);
			
			$i = 0;
			$aPos2 = null;
			if($aPos){
				foreach($aPos as $aPosTmp){
					$aDet = $this->pmatch_m->getPositionDesc($aPosTmp['PLANS']);

					$aPos2[$i]['position'] = $aDet['desc'];
					$aPos2[$i]['posid'] = $aPosTmp['PLANS'];
					$aPos2[$i]['job'] = $aDet['job'];
					$aPos2[$i]['unit'] = $aDet['unit'];
					$aPos2[$i]['company'] = $aDet['prsh'];
					$aPos2[$i]['readiness'] = $aPosTmp['PERCT'];
					
					$aReady = $this->pmatch_m->get_ready($aPosTmp['PERCT']);
					$aPos2[$i]['ready'] = $aReady['DESC'];

					$aPos2[$i]['ccompt'] = $aEmpCompt[$aPosTmp["PLANS"]];
					$aPos2[$i]['cperf'] = $aCriteria[$sNik][2];
					$aPos2[$i]['cage'] = $aCriteria[$sNik][3];
					$aPos2[$i]['ceduc'] = $aCriteria[$sNik][4];
					$aPos2[$i]['cmedical'] = $aCriteria[$sNik][5];

					$i++;
				}
			}
			$data['position'] = $aPos2;

			$data['count'] = count($aPos);

			$data['externalJS'] = '<script type="text/javascript" language="javascript" src="' . base_url() . 'assets/advanced-datatable/media/js/jquery.dataTables.min.js"></script>'.
								  '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
			$data['externalCSS'] = '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/profile.css" />';
		//	$data['externalCSS'] .= '<link href="' . base_url() . 'css/table-responsive.css" rel="stylesheet" />';
			$data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" /> '.
									'<link href="' . base_url() . 'assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" /> '.
									'<link href="' . base_url() . 'assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />';
			$data['scriptJS'] = '<script type="text/javascript">
					$(document).ready(function() {
						var oTable = $("#tblPmatch").dataTable( {} );
					} );
				</script>';

			$this->load->view('main', $data);
		}else{
			redirect('pmatch/search', 'refresh');
		}
    }
	
	private function postToString($sString){
		$sRtn = "";
		if($sString<>""){
			$sRtn = "'" . $sString . "'";
			$sRtn = str_replace(",","','",$sRtn);
		}
		return $sRtn;
	}
	
}

?>
