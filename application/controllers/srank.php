<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of srank
 *
 * @author Garuda
 */
class srank extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        $this->load->model('srank_m');
    }

    function search() {
		$data = $this->srank_m->home();
		$aGrade = $this->common->get_abbrev(7);
		$sGrade = json_encode($aGrade);

		$aJob = $this->common->get_abbrev(6);
		$sJob = json_encode($aJob);
		$aPrsh = $this->srank_m->get_org_level();
		$sPrsh = json_encode($aPrsh);

		$aEmp = $this->get_emp($aPrsh);
		$sEmp = json_encode($aEmp);
		
		$aFam = $this->srank_m->get_jobfam();
		$sFam = json_encode($aFam);		

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
			$("#fgrade").select2({
				data: {results: dataGrade},
				multiple: true,
				formatResult : format,
				dropdownAutoWidth: true
			});
			$("#fnik").select2({
				data: '. $sEmp .',
				formatResult : format,
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#fjob").select2({
				data: '. $sJob .',
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#ffam").select2({
				data: '. $sFam .',
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#fprsh").select2({
				data: '. $sPrsh .',
				multiple: true,
				dropdownAutoWidth: true
			});
			$("#fjob2").select2({
				data: '. $sJob .',
				dropdownAutoWidth: true
			});
			$("#fprsh2").select2({
				data: '. $sPrsh .',
				dropdownAutoWidth: true
			});
			$("#fpos2").select2({
				minimumInputLength: 1,
				dropdownAutoWidth: true,
				ajax: {
					url: "'.base_url().'index.php/srank/fetch_position/",
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
			$("#funit2").select2({
				minimumInputLength: 1,
				dropdownAutoWidth: true,
				ajax: {
					url: "'.base_url().'index.php/srank/fetch_unit/",
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

	function get_emp($aPrshs){
		$aRtn = null;
		$i=0;
		if($aPrshs){
			foreach($aPrshs as $aPrsh){
				$aRtn[$i]["text"] = $aPrsh["text"];
				$aEmps = $this->srank_m->get_mapping_pernr($aPrsh["id"]);
				if($aEmps){
					foreach($aEmps as $aEmp){
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

    function fetch_unit(){
        $q=$this->input->post('q');
        $org_unit=$this->input->post('comp');
        $prefix=  substr($org_unit,0,3);
		if($org_unit==""){
			echo "[]";
		}else{
			$aRes = $this->srank_m->get_unit($q,$prefix);
			echo json_encode($aRes);
		}
    }

    function fetch_position(){
        $q=$this->input->post('q');
        $org_unit=$this->input->post('comp');
        $prefix=  substr($org_unit,0,3);
		if($org_unit==""){
			echo "[]";
		}else{
			$aRes = $this->srank_m->get_position($q,$prefix);
			echo json_encode($aRes);
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

			$aRtn = $this->srank_m->getResultSearch($sCond);
		}

		return $aRtn;
	}
	
	private function postToString($sString){
		$sRtn = "";
		if($sString<>""){
			$sRtn = "'" . $sString . "'";
			$sRtn = str_replace(",","','",$sRtn);
		}
		return $sRtn;
	}

	function view($sPlans="") {
		if($sPlans<>""){
			$sPosCompare = $sPlans;
			$sGrade = "";
			$sJob = "";
			$sNik = "";
			$sPrsh = "";
			$iBase = 1;
			$iBaseCompt = 2;
		}else{
			$sPosCompare = $this->input->post('fpos2');
			$sGrade = $this->input->post('fgrade'); $sGrade = $this->postToString($sGrade);
			$sJob = $this->input->post('fjob'); $sJob = $this->postToString($sJob);
			$sNik = $this->input->post('fnik'); $sNik = $this->postToString($sNik);
			$sPrsh = $this->input->post('fprsh'); $sPrsh = $this->postToString($sPrsh);
			$sFam = $this->input->post('ffam'); //$sFam = $this->postToString($sFam);
			$iBase = $this->input->post('fbase');
			$iBaseCompt = $this->input->post('fcompt');
			//$sUnit = $this->input->post('funit'); $aUnit = explode(',',$sUnit);
		}

		if($sPosCompare<>""){
			// Cek apakah ada pending batch buat PLANS?
			// jika ada execute lgs

			$data = $this->srank_m->view();
			$aPos = $this->srank_m->getPositionDesc($sPosCompare);
					if(empty($aPos['jobshort']))$aPos['jobshort']="1";
					if(empty($aPos['jobseq']))$aPos['jobseq']="1";
			$data['position'] = $aPos;

			
			$sANik = "";
			// if($sNik<>""){
				// $sANik = $sNik;
			// }

			//compare sama job family
			if($sFam==""){
				// ikuti aturan job family di table tm_job_fam_relation;
				// cari job family dari position yg di cari
				$sFam = $this->srank_m->get_relation_jobfam($sPosCompare);
				//$sFam = $this->srank_m->get_relation_jobfam(20600057);
			}else{
				// ambil dari sFam;
				$sFam = $sFam;
			}

			$aEmpFam = $this->srank_m->get_emp_fam($sFam);
			
			$sEmpFam = "";
			if($aEmpFam){
				foreach($aEmpFam as $aEmpF){
					$sEmpFam .= ($sEmpFam==""?"":",") . $aEmpF["PERNR"];
				}
			}
			//employee selection
			$aEmpSel = $this->srank_m->get_emp_selection($sNik,$sGrade,$sJob,$sPrsh,$sEmpFam);
                            
			if($aEmpSel && $sFam <> ""){
				$aEmpSelTmp = null;
				$k=0;
				foreach($aEmpSel as $aEmpSelTmp2){
					$aEmpSelTmp[$k] = $aEmpSelTmp2['PERNR'];
					$k++;
				}
				$sANik = implode(",",$aEmpSelTmp);

				//echo "Employee(s) Not Found";
				//exit;

				// filter base on matrix job score
				if($iBase == 1){
					// difilter lagi berdasarkan matrix job score
					// cari company nya
					// cari level company nya
					// cari di "dalam satu level stell" : dari selevel sampe dua level company dibawahnya tapi tidak boleh melebihi job score nya
					// cari di "satu level dibawah stell" : selevel company dan max level company diatasnya, tapi tidak boleh melebihi job score nya
					
					$aLevel = $this->srank_m->get_level_org_persh($aPos['prshid']);
					$aScore = $this->srank_m->get_score_matrix_job($aPos['prshid'], $aPos['jobshort']);
					if(empty($aScore['SCORE']))$aScore['SCORE']="1";
					$aMatrix = $this->srank_m->get_org_stell($aLevel['LEVEL'], $aPos['jobseq'], $aScore['SCORE']);
					
					$aEmpMatrix = null;
					$z=0;
					foreach($aMatrix as $aOrgjob){

						$aTmp = $this->srank_m->get_pernr($aOrgjob['ORGID'], $aOrgjob['STELL']);

						if($aTmp){
							foreach($aTmp as $aEmpTmp){
								$aEmpMatrix[$z] = $aEmpTmp['PERNR'];
								$z++;
							}
						}

						// Non Struktural
						$aStellNon = $this->srank_m->get_stell_nons($aOrgjob['STELL']);
						if($aStellNon){
							for($d=0;$d<count($aStellNon);$d++){
								$aTmp2 = $this->srank_m->get_pernr($aOrgjob['ORGID'], $aStellNon[$d]['SHORT']);
								if($aTmp2){
									foreach($aTmp2 as $aEmpTmp2){
										$aEmpMatrix[$z] = $aEmpTmp2['PERNR'];
										$z++;
									}
								}
							}
						}
					}

					$aCombine = null;
					if($aEmpMatrix){
						if($sANik <> ""){
							$aANikCompare = explode(",",$sANik);
							$aCombine = array_intersect($aANikCompare,$aEmpMatrix);
							$sANik = implode(",",$aCombine);
						}else{
							$sANik = implode(",",$aEmpMatrix);
						}
					}

					$sANik = $sANik;
				}

				// filter by otorisasi
				$aANik = explode(",",$sANik);
				$aPernrAuth = $this->common->get_a_pernr_auth();
				$aFinal = array_intersect($aANik,$aPernrAuth);
				$sANik = implode(",",$aFinal);

				//get employee(s)
				$aEmps = $this->srank_m->get_emp_readiness($sPosCompare,$iBaseCompt,$sANik);
				$aCriteria = $this->srank_m->get_emp_criteria($sANik);
				$aEmpCompt = $this->srank_m->get_emp_plans($sPosCompare,$iBaseCompt,$sANik);
				$aPerfConfig = $this->srank_m->get_perf_config();

				$i = 0;
				$aEmps2 = null;
				if($aEmps){
					$this->load->model('tprofile_m');
					foreach($aEmps as $aEmp){
						if(!empty($aCriteria[$aEmp['PERNR']])){
							$aDet = $this->srank_m->get_detail_emp($aEmp['PERNR']);
							$aEducs = $this->tprofile_m->getEduc($aEmp['PERNR']);

							$aEmps2[$i]['nik'] = $aEmp['PERNR'];
							$aEmps2[$i]['nama'] = $aDet['CNAME'];
							$aEmps2[$i]['currpos'] = $aDet['POS'];
							$aEmps2[$i]['readiness'] = $aEmp['PERCT'];

							$aReady = $this->srank_m->get_ready($aEmp['PERCT']);
							$aEmps2[$i]['ready'] = $aReady['DESC'];
							$aEmps2[$i]['ccompt'] = $aEmpCompt[$aEmp["PERNR"]];
							$aEmps2[$i]['cperf'] = $aCriteria[$aEmp['PERNR']][2];
							$aEmps2[$i]['cage'] = $aCriteria[$aEmp['PERNR']][3];
							$aEmps2[$i]['ceduc'] = $aCriteria[$aEmp['PERNR']][4];
							$aEmps2[$i]['cmedical'] = $aCriteria[$aEmp['PERNR']][5];
							$aEmps2[$i]['grade'] = $aDet['GRADE'];
							$aEmps2[$i]['educ'] = $this->getLastEduc($aEducs);
							$aEmps2[$i]['age'] = $aDet['AGE'];
							$aEmps2[$i]['birthdate'] = $aDet['GBDAT'];
							$aEmps2[$i]['medical'] = $this->srank_m->get_medical($aEmp['PERNR']);

							$aPerf = $this->common->getTalentPerf($aEmp['PERNR'],$aPerfConfig);
							$sTalentMap = $this->common->getTalentMap($aEmp['PERNR'],$aDet['PLANS'],$aPerf);
							$aEmps2[$i]['map'] = $sTalentMap;
							$aDesc = $this->srank_m->get_talent_desc($sTalentMap);
							$aEmps2[$i]['mapdesc'] = ($aDesc?$aDesc['STEXT']:"");
							
							$aEmps2[$i]['comptavg'] = $this->srank_m->get_avg_compt($aEmp["PERNR"]);

							$aMdg = $this->srank_m->get_mdg($aEmp["PERNR"]);
							if($aMdg){
								$aEmps2[$i]['mdg'] = "Grade ". $aMdg["TRFGR"] . $aMdg["TRFST"] . " (".$aMdg["MDGY"]."year ".$aMdg["MDGM"]."month)";
							}else{
								$aEmps2[$i]['mdg'] = "";
							}

							$s3Perf = $this->get_3_perf($aPerf);
							$aEmps2[$i]['perf'] = $s3Perf;

							//$this->srank_m->get_master_readiness('');

							$i++;
						}
					}
				}
			}else{
				$aEmps2 = null;
				$aEmps = null;
			}
			
			$data['employee'] = $aEmps2;
			
			$data['count'] = count($aEmps);
			
			$data['externalJS'] = '<script type="text/javascript" language="javascript" src="' . base_url() .'assets/advanced-datatable/media/js/jquery.dataTables.min.js"></script>'.
								  //'<script src="' . base_url() . 'js/jquery.scrollTo.min.js"></script>'.
								  '<script src="' . base_url() . 'assets/fancybox/source/jquery.fancybox.js"></script>'.
								  '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';

			$data['externalCSS'] = '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/profile.css" /> ';
			$data['externalCSS'] .= '<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/fancybox/source/jquery.fancybox.css" /> ';
		//	$data['externalCSS'] .= '<link href="' . base_url() . 'css/table-responsive.css" rel="stylesheet" />';
			$data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" /> '.
									'<link href="' . base_url() . 'assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" /> '.
									'<link href="' . base_url() . 'assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />';
			$data['scriptJS'] = '<script type="text/javascript">
				function fnFormatDetails ( oTable, nTr ){
					var aData = oTable.fnGetData( nTr );
					var sOut = "<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' style=\'padding-left:50px; line-height:0px;\'>";
					sOut += "<tr><th>NIK</th><td>: "+aData[1]+"</td></tr>";
					sOut += "<tr><th>Competency</th><td>: "+aData[16]+" (Average)</td></tr>";
					sOut += "<tr><th>Performance</th><td>: "+aData[15]+"</td></tr>";
					sOut += "<tr><th>Birthdate</th><td>: "+aData[13]+"</td></tr>";
					sOut += "<tr><th>Education</th><td>: "+aData[12]+"</td></tr>";
					sOut += "<tr><th>Medical</th><td>: "+aData[14]+"</td></tr>";
					sOut += "</table>";

					return sOut;
				}
			    $(document).ready(function() {
					$(".fancybox").fancybox({
						arrows    : false
					});
					var oTable = $("#tblSrank").dataTable( {
						"aoColumnDefs": [
							{
								"aTargets": [ 0 ],
								"sType": "numeric"
							}
							,{
								"aTargets": [ 1 ],
								"bVisible": false
							}
							,{
								"aTargets": [ 12 ],
								"bVisible": false
							}
							,{
								"aTargets": [ 13 ],
								"bVisible": false
							}
							,{
								"aTargets": [ 14 ],
								"bVisible": false
							}
							,{
								"aTargets": [ 15 ],
								"bVisible": false
							}
							,{
								"aTargets": [ 16 ],
								"bVisible": false
							}
						]
					} );

					$("#tblSrank tbody td img").click(function () {
						if($(this).attr("name")=="plusminus"){
						var nTr = $(this).parents("tr")[0];
							if ( oTable.fnIsOpen(nTr) ){
								this.src = "' . base_url() . 'assets/advanced-datatable/examples/examples_support/details_open.png";
								oTable.fnClose( nTr );
							}else{
							  this.src = "' . base_url() . 'assets/advanced-datatable/examples/examples_support/details_close.png";
							  oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), "details" );
							}
						}
					});

					var iState = 1;
					$("#btnShow").click( function () {
						if(iState==1){
							$(this).html("Hide Detail");
							$("#tblSrank tbody tr td img:not(.details,.expand)").each( function() {
								if( !oTable.fnIsOpen( $(this).parents("tr")[0] ) ){
									$(this).click();
								}
							});
							iState = 0;
						}else{
							$(this).html("Show Detail");
							$("#tblSrank tbody tr td img:not(.details,.expand)").each( function() {
								if( oTable.fnIsOpen( $(this).parents("tr")[0] ) ){
									$(this).click();
								}
							});
							iState = 1;
						}
					});
				} );
			</script>';
			$this->load->view('main', $data);
		}else{
			redirect('srank/search', 'refresh');
		}
    }

	function getLastEduc($aEducs){
		$aEducLast = null;
		$aMax = 99;
		if($aEducs){

			// add index & get last educ max
			foreach($aEducs as $i => $aEduc){
				switch($aEduc['SLART']){
					case '3' : $aEducs[$i]["IDX"] = 8; if($aMax>8) $aMax = 8; break;
					case '4' : $aEducs[$i]["IDX"] = 7; if($aMax>7) $aMax = 7;break;
					case '5' : $aEducs[$i]["IDX"] = 6; if($aMax>7) $aMax = 7;break;
					case '6' : $aEducs[$i]["IDX"] = 5; if($aMax>7) $aMax = 7;break;
					case '7' : $aEducs[$i]["IDX"] = 4; if($aMax>7) $aMax = 7;break;
					case '8' : $aEducs[$i]["IDX"] = 3; if($aMax>3) $aMax = 3;break;
					case '9' : $aEducs[$i]["IDX"] = 2; if($aMax>3) $aMax = 3;break;
					case '10' : $aEducs[$i]["IDX"] = 1; if($aMax>3) $aMax = 3;break;
					default : $aEducs[$i]["IDX"] = 9; if($aMax>9) $aMax = 9;break;
				}
			}

			//sorting
			foreach($aEducs as $rec){
				$idx[] = $rec["IDX"];
			}
			array_multisort($idx,SORT_ASC,SORT_NUMERIC,$aEducs);

			//pop educ yg lebih besar dari max
			foreach($aEducs as $aEduc){
				if($aEduc["IDX"] > $aMax){
					array_pop($aEducs);
				}
			}

			//print_r($aEducs);  exit;
			$aEducLast = $aEducs;
		}

		return $aEducLast;
	}

	function get_3_perf($aPerf){
		// baru ambil 3 record terakhir
		$sRtn = "";

		if($aPerf){
			for($i=0;$i<count($aPerf);$i++){
				if($i>=3)
					break;
				$sRtn .= ($sRtn==""?"":", "). $aPerf[$i]["IDX"] . " (" . substr($aPerf[$i]["ENDDA"],0,4) . ")";
			}
		}

		return $sRtn;
	}
}

?>
