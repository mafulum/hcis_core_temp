<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tprofile
 *
 * @author Garuda
 */
class tprofile extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        $this->load->model('tprofile_m');
		
		$this->common->cekMethod($this->uri->segment(3));
    }

    function search($sPost="N") {
		$data = $this->tprofile_m->home();

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
				"' . base_url() . 'index.php/tprofile/getEmpJson"
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
				"' . base_url() . 'index.php/tprofile/getUnitJson"
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
				redirect('tprofile/search', 'refresh');
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
			
			$aRtn = $this->tprofile_m->getResultSearch($sCond);
		}

		return $aRtn;
	}

	function result() {

        $data = $this->tprofile_m->home();

		$data['externalCSS'] = '<link href="' . base_url() . 'css/table-responsive.css" rel="stylesheet" />';
        $data['externalJS'] = '';
        $data['scriptJS'] = '';

		$data['empsInfo'] = '';

		$this->load->view('main', $data);
    }

	function view($sNopeg='') {
		if($sNopeg<>''){
			$this->load->model('employee_m');
			$this->load->model('srank_m');
			
			$aPerfConfig = $this->srank_m->get_perf_config();

			$data = $this->tprofile_m->view($sNopeg);
			$data = $this->employee_m->get_default_data($data, $sNopeg);

			$data['externalCSS'] = '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/profile.css" />';
			$data['externalJS'] = '';
			$data['scriptJS'] = '';

			$data['emp']= $this->tprofile_m->getEmp($sNopeg);
			$data['empDate'] = $this->tprofile_m->get_a_emp_date($sNopeg);
			$data['mdg'] = $this->tprofile_m->get_mdg($sNopeg);

			$aEducs = $this->tprofile_m->getEduc($sNopeg);
			$data['lastEduc'] = $this->getLastEduc($aEducs);
			$aPlans = $this->tprofile_m->getPrior($sNopeg);
			$data['prior'] = $aPlans;
		//	$data['future'] = $this->tprofile_m->getFuture($sNopeg,$aPlans[0]['PLANS']);
			$data['awards'] = $this->tprofile_m->getAward($sNopeg);
			$data['grievances'] = $this->tprofile_m->getGrievances($sNopeg);
			$data['medical'] = $this->tprofile_m->getMedical($sNopeg);

			$aPerf = $this->common->getTalentPerf($sNopeg,$aPerfConfig);
			$sTalentMap = $this->common->getTalentMap($sNopeg,$aPlans[0]['PLANS'],$aPerf);			
			$aTalentDesc = $this->tprofile_m->get_talent_desc($sTalentMap);
			
			$aDescMap = array("L"=>"Low","M"=>"Medium","H"=>"High","-"=>"-");
			$sPot = $this->common->getTalentPot($sNopeg,$aPlans[0]['PLANS']);
			$aPerf2 = array();
			if($aPerf){
				for($i=0;$i<=count($aPerf);$i++){
					if($i>=3)
						break;
					$aPerf2[$i] = $aPerf[$i];
					$aPerf2[$i]["DESC"] = $aDescMap[$aPerf2[$i]["IDX"]];
				}
			}

			$data['potential'] = $aDescMap[$sPot] . " (".$sPot .")";
			$data['perf'] = $aPerf2;
			$data['talentMap'] = $aTalentDesc;
			$data['assessment'] = '';
			$data['training'] = $this->tprofile_m->getInEduc($sNopeg);

			$this->load->view('main', $data);
		}else{
			redirect('tprofile/search', 'refresh');
		}
    }

	function concatPosition($aOrg){
		$sTmp = "";
		$aRst = null;
		$i = 0;
		if($aOrgs){
			foreach($aOrgs as $aOrg){
				if($aOrg["PLANS"] <> $sTmp){
					$aRst[$i] = $aOrg;
					$i++;
				}else{
					// update endDate
					$aRst[$i]["ENDDA"] = $aOrg["ENDDA"];
					$aRst[$i]["TENDDA"] = $aOrg["TENDDA"];
				}
			}
		}

		return $aOrg;
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
	
	function getEmpJson(){
		$sSearch = $this->input->post('q');

		$aSearch = $this->tprofile_m->getEmpJson($sSearch);

		echo json_encode($aSearch);
	}
	
	function getUnitJson(){
		$sSearch = $this->input->post('q');

		$aSearch = $this->tprofile_m->getUnitJson($sSearch);

		echo json_encode($aSearch);
	}
}

?>
