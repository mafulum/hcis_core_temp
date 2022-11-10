<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gen_pdf
 *
 * @author Garuda
 */
class gen_pdf extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ecs_m');
        $this->load->model('employee_m');
        $this->load->model('tprofile_m');
    }

    //put your code here
    function profile($hash) {
        $str=$this->get_download($hash);

        $data= json_decode($str,true);
        require_once('assets/mpdf/mpdf.php');
        $mpdf = new mPDF('');
        $html = '
            <style>
            element.style {
            }
            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
		font-family: Calibri;
            }
            .bio-graph-heading {
		 border: 5px solid #41cac0;
                background: #fff;
		vertical-align:middle;
                color: #000;
                text-align: left;
                font-style: italic;
                padding: 10px ;
                border-radius: 4px 4px 0 0;
                -webkit-border-radius: 4px 4px 0 0;
                font-size: 24px;
                font-weight: 300;
		font-family: Calibri;
            }    
.bio-graph-heading table tr td{
                font-size: 24px;
                font-weight: 300;
		font-family: Calibri;		vertical-align:middle;
} 
            .bio-graph-info {
                color: #89817e;
		font-family: Calibri;
            }
            .panel-body {
                padding: 15px;
		font-family: Calibri;
            }
            .bio-row {
            width: 50%;
            float: left;
            margin-bottom: 10px;
            padding: 0 15px;
		font-family: Calibri;
            }
            h1 {
            font-size: 12px;
		font-family: Calibri;
            }
            span{
            font-size: 12px;
		font-family: Calibri;
            }
            p{
            font-size: 12px;
		font-family: Calibri;
            }
            .profile-nav .user-heading {
            background: #ff766c;
            color: #fff;
            border-radius: 4px 4px 0 0;
            -webkit-border-radius: 4px 4px 0 0;
            padding: 30px;
            text-align: center;
		font-family: Calibri;
            }
		ul li{
			            font-size: 12px;
		font-family: Calibri;
		}
		li{
			            font-size: 12px;
		font-family: Calibri;
		}

    </style>
        <section class="panel">
			  <div class="bio-graph-heading" >
                            <table><tr><td><img alt="" src="' . base_url() . 'img/logo2.jpg" /></td><td>Pupuk Indonesia Talent Profile</td></tr></table>
			  </div>
			  <div class="panel-body bio-graph-info">
                            <table><tr><td width="325px" valign="top">
                            <div class="user-heading round"> 
					  <img alt="" src="' . base_url() . 'img/photo/' . $this->global_m->get_array_data($data['master_emp'], "PERNR") . '.jpg" onerror="this.src=\'' . base_url() . 'img/photo/default.jpg\';" height="120px"/>
				  <h1>' . $this->global_m->get_array_data($data['master_emp'], "CNAME") . '</h1>
                                  <p>' . $this->global_m->get_array_data($data['master_emp'], "PERNR") . '
				  <br />' . $this->global_m->get_array_data($data['emp_map'], "POSISI") . '
				  <br />' . $this->global_m->get_array_data($data['emp_map'], "PERSH") . '</p>
			  </div>
                          <br/>
					  <div class="bio-row">
						  <h1>Personal Data</h1>
						  <p><span>Nama </span>: ' . $this->global_m->get_array_data($data['master_emp'], "CNAME") . '</p>
						  <p><span>NIK Asal </span>: ' . $this->global_m->get_array_data($data['emp_map'], "NIK") . '</p>
						  <p><span>Tgl. Lahir </span>: ' . $this->global_m->get_array_data($data['emp'], "GBDAT") . ' (' . $this->global_m->get_array_data($data['emp'], "age") . ' years old)</p>
						  <p><span>Tgl. Masuk </span>: ' . $this->global_m->get_array_data($data['empDate'], "TTanggalMasuk") . ' </p>
						  <p><span>Tgl. Peg. Tetap </span>: ' . $this->global_m->get_array_data($data['empDate'], "TTanggalPegTetap") . ' </p>
						  <p><span>Tgl. Pensiun </span>: ' . $this->global_m->get_array_data($data['empDate'], "TTanggalMPP") . ' </p>
						  <p><span>Tgl. MPP </span>: ' . $this->global_m->get_array_data($data['empDate'], "TTanggalPensiun") . ' </p>
						  <p><span>MDG </span>: Grade '.$this->global_m->get_array_data($data['mdg'], "TRFGR") . $this->global_m->get_array_data($data['mdg'], "TRFST") ." (" . $this->global_m->get_array_data($data['mdg'], "MDGY") .'years '. $this->global_m->get_array_data($data['mdg'], "MDGM") .'months) </p>
						  <br />
						  <h1>Last Education</h1>
						  ';
        for ($i = 0; $i < count($data['lastEduc']); $i++) {
            $html.='<p>' . $this->global_m->get_array_data($data['lastEduc'][$i], "STEXT") . ', ' . $this->global_m->get_array_data($data['lastEduc'][$i], "SLTP1") . ', ' . $this->global_m->get_array_data($data['lastEduc'][$i], "INSTI") .", ".$this->global_m->get_array_data($data['lastEduc'][$i], "EMARK").", ".$this->global_m->get_array_data($data['lastEduc'][$i], "LULUS").", ".$this->global_m->get_array_data($data['lastEduc'][$i], "BIAYA").'</p>';
        }
        $html .='<br />
						  <br />                                                  
						  <h1>Future Assignment</h1>';
                                                  if($data['future']){
                                                      $future=$data['future'];
							 for ($i = 0; $i < count($future); $i++) { 
								$html.='<p>- '.ucwords(strtolower($this->global_m->get_array_data($future[$i], "STEXT"))).',
								('.$this->global_m->get_array_data($future[$i], "TBEGDA").' - 
								'.$this->global_m->get_array_data($future[$i], "TENDDA").')
								</p>';
							} 

							}else $html.="<p><i>Please see Profile Matchup Report</i></p>";
						  $html.='<br /><br />
						  <h1>Prior Assignment</h1>';
        if ($data['prior']) {
            for ($i = 1; $i < count($data['prior']); $i++) {
                $html.='<p>- ' . ucwords(strtolower($this->global_m->get_array_data($data['prior'][$i], "STEXT"))) . ',
								(' . $this->global_m->get_array_data($data['prior'][$i], "TBEGDA") . ' - 
								' . ucwords(strtolower($this->global_m->get_array_data($data['prior'][$i], "TENDDA"))) .')</p>';
            }

        }else
            $html.= '-';
        $html.='<br /> <br />
						  <h1>Awards</h1>';
        if ($data['awards']) {
            for ($i = 0; $i < count($data['awards']); $i++) {
                $html.='<p>- ' . $this->global_m->get_array_data($data['awards'][$i], "STEXT") . ',
								' . $this->global_m->get_array_data($data['awards'][$i], "TBEGDA") . ',
								' . ucwords(strtolower($this->global_m->get_array_data($data['awards'][$i], "TEXT1"))) . '
								</p>';
            }
        } else {
            $html.="-";
        }
        $html .='<br /> <br />
						  <h1>Grievances</h1>';
        if ($data['grievances']) {
            for ($i = 0; $i < count($data['grievances']); $i++) {
                $html.='<p>- ' . $this->global_m->get_array_data($data['grievances'][$i], "SUBTY") . ',
								' . $this->global_m->get_array_data($data['grievances'][$i], "TBEGDA") . ' - ' . $this->global_m->get_array_data($data['grievances'][$i], "TENDDA") . ',
								' . ucwords(strtolower($this->global_m->get_array_data($data['grievances'][$i], "TEXT1"))) . '
								</p>';
            }
            $html .='</ul>';
        } else {
            $html .="-";
        }

        $html .='<br/><br/>
						  <h1>Medical History</h1>';
        if ($data['medical']) {
            for ($i = 0; $i < count($data['medical']); $i++) {
                $html.='<p>- ' . $this->global_m->get_array_data($data['medical'][$i], "STEXT") . ',
								' . $this->global_m->get_array_data($data['medical'][$i], "TBEGDA") . ',
								' . ucwords(strtolower($this->global_m->get_array_data($data['medical'][$i], "TEXT1"))) . '
								</p>';
            }
        } else {
            $html .="-";
        }

        $html.='</td></div><td width="325px" style="valign:top;">
                                        <div class="bio-row">
						  <h1>Talent Map</h1>
                                                  <br />
							<img height="180px" src="' . base_url() . 'img/tmap/'.$this->global_m->get_array_data($data['talentMap'], "SHORT").'.jpg" alt="talentMap">
						  <h1>Assessment</h1>

                                                   <p>- Potential : '.$data['potential'].'</p>
							<p>- '; 
								if($data['perf']){
                                                                    $perf=$data['perf'];
									$html.="Performance : ".$perf[0]["DESC"]." (".$perf[0]["IDX"].")";
									for($i=0;$i<count($data['perf']);$i++){
										$html.="<br/>* ".substr($perf[$i]['ENDDA'],0,4)." </span>: ".$perf[$i]["DESC"]." (".$perf[$i]["IDX"].")";
									}
								}
							$html.='</p>
							<p>- Description : '. $this->global_m->get_array_data($data['talentMap'], "STEXT").' ( '. $this->global_m->get_array_data($data['talentMap'], "SHORT").' )
								<br />'.$this->global_m->get_array_data($data['talentMap'], "DESC").'
							</p><br />
					      <h1>Training History</h1>';
        if ($data['training']) {
            $training = $data['training'];
            for ($i = 0; $i < count($training) && $i < 10; $i++) {
                $html.='<p>- ' . $this->global_m->get_array_data($training[$i], "AUSBI") . ',
								' . $this->global_m->get_array_data($training[$i], "TBEGDA") . ' - ' . $this->global_m->get_array_data($training[$i], "TENDDA") . ',
								' . ucwords(strtolower($this->global_m->get_array_data($training[$i], "SLTP1"))) . ',
								' . ucwords(strtolower($this->global_m->get_array_data($training[$i], "INSTI"))) . '
								</p>';
            }
        }else
            $html.= "-";

        $html.='</div></td></tr></table>
                          </div>
		  </section>';
//echo $html;exit;
//        if ($_REQUEST['source']) {
//            $file = __FILE__;
//            header("Content-Type: text/plain");
//            header("Content-Length: " . filesize($file));
//            header("Content-Disposition: attachment; filename=''");
//            readfile($file);
//            exit;
//        }
//==============================================================
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

// OUTPUT
        $mpdf->Output($this->global_m->get_array_data($data['master_emp'], "CNAME")." ( " . $this->global_m->get_array_data($data['master_emp'], "PERNR"). " ).pdf", "D");
        exit;
    }
    
    function splan($hash){
        $str=$this->get_download($hash);
	$data= json_decode($str,true);
$data['externalCSS'] = '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/profile.css" />';
			$data['externalJS'] = '';
			$data['scriptJS'] = '';
        require_once('assets/mpdf/mpdf.php');
        $mpdf = new mPDF('');
        $html = '
        <style>
        
element.style {
            }
            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }
            .bio-graph-heading {
                background: #41cac0;
                color: #fff;
                text-align: center;
                font-style: italic;
                padding: 40px 110px;
                border-radius: 4px 4px 0 0;
                -webkit-border-radius: 4px 4px 0 0;
                font-size: 24px;
                font-weight: 300;
            }    
            .bio-graph-info {
                color: #89817e;
            }
            .panel-body {
                padding: 15px;
            }
            .bio-row {
            width: 50%;
            float: left;
            margin-bottom: 10px;
            padding: 0 15px;
            }
            h1 {
            font-size: 16px;
            color: #000;
            }
            span{
            font-size: 12px;
            }
            p{
            font-size: 12px;
            }
            .profile-nav .user-heading {
            background: #ff766c;
            color: #fff;
            border-radius: 4px 4px 0 0;
            -webkit-border-radius: 4px 4px 0 0;
            padding: 30px;
            text-align: center;
            }
            
.profile-nav .user-heading p {
    font-size: 12px;
}
.profile-nav .user-heading h1 {
	text-decoration:underline;
	font-weight: 500;
}
.bio-graph-info h1  {
	text-decoration:underline;
    font-weight: 500;
}
.bio-graph-heading {
	font-size: 12px;
	padding: 15px;
}
.bio-search{
	font-size: 12px;
	padding: 15px 15px 0 15px;
}
.bio-row ul li {
    list-style-type: disc;
	margin-left: 15px;
}

.compt-blue{
	background-color: #5BC0DE;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.compt-green{
	background-color: #5CB85C;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.compt-red{
	background-color: #D9534F;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.tbl-compt tbody > tr > td{
	padding: 8px;
	line-height: 2;
	border: 1px solid #DDDDDD;
}

.tbl-compt > thead > tr > th {
	border: 1px solid #DDDDDD;
	vertical-align: middle;
	text-align: center;
	padding: 5px;
}
.table {
width: 100%;
margin-bottom: 20px;
}
table {
max-width: 100%;
background-color: transparent;
}
table {
border-collapse: collapse;
border-spacing: 0;
}

.tbl-compt> thead:first-child > tr:first-child > th {
	border-top: 1px solid #DDDDDD;
}

.space{
	padding: 5px;
}
</style>

            ';
$html.='<section class="panel">
			 <div class="bio-graph-heading" >
                            <table><tr><td><img alt="" src="' . base_url() . 'img/logo2.jpg" /></td><td>Succession Rank List '.$data['position_name'].' </td></tr></table>
			  </div>
			  <div class="panel-body bio-graph-info">
                          
					  <div class="col-lg-12">
							<table class="table tbl-compt" border="1" colspan="0" cellpadding="0">
								<thead>
									<tr>
										<th>No</th>
										<th>Photo</th>
										<th>Name / NIK</th>
										<th>Curr. Position</th>
										<th>MDG</th>
										<th>Last Education</th>
										<th>Age</th>
										<th>Experience</th>
									</tr>
								</thead>
								<tbody>';
                                                                        
                                if ($data['employee']) {
                                    $j = 1;
                                    foreach ($data['employee'] as $emp) {
                                        $html.='<tr>
                                            <td>'. $j.'</td>
                                            <td><a class="fancybox" rel="group" href="'.base_url().'img/photo/'. $this->global_m->get_array_data($emp, "nik").'.jpg"><img name="poto" width="60px" height="60px" alt="" src="'.base_url().'img/photo/'. $this->global_m->get_array_data($emp, "nik").'.jpg" onerror="this.src='. base_url().'img/photo/default.jpg"></a></td>
                                            <td>'.$this->global_m->get_array_data($emp, "nama").'
                                                <br/>'.$this->global_m->get_array_data($emp, "nik").'
                                            </td>
                                            <td>'. $this->global_m->get_array_data($emp, "currpos").'</td>
                                            <td>'. $this->global_m->get_array_data($emp, "mdg").'</td>
                                            <td>'. $this->global_m->get_array_data($emp, "educ");
                                        for ($i = 0; $i < count($emp['educ']); $i++) {
                                                    $html.= ($i <> 0 ? " & " : "") . $this->global_m->get_array_data($emp['educ'][$i], "STEXT") . ", " . $this->global_m->get_array_data($emp['educ'][$i], "SLTP1").' , '. $this->global_m->get_array_data($emp['educ'][$i], "INSTI"); 
                                                } 
                                            $html.='</td>
                                            <td>'.$this->global_m->get_array_data($emp, "age") . ' yrs </td>
                                            <td>'. str_replace("\n", "<br/>", $data['aHist'][$j - 1]).'</td>
                                        </tr>';
                                        $j++;
                                    }
                                } else {
                                $html.='<tr>
                                        <td colspan="8">No Data Found</td>
                                    </tr>';
                                 }
                            $html.='</tbody></table>
                                </section>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        Concern Item 
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <div class="row" style="text-align:justify;margin-left: 15px;">
                               '. str_replace("\n", "<br/>", $data['concern']).'
                            </div>
                        </div>
                    </div>
                </div>
		  </section>';
                            echo $html;exit;
 $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

// OUTPUT
        $mpdf->Output("Succesion Rank List ".$data['position_name'].".pdf", "D");
        exit;
    }
    private function get_download($hash){
        $this->db->where('id_hash',$hash);
        $oRes = $this->db->get('tm_download');
        if ($oRes->num_rows()==0) {
            echo "not authorized";
            exit;
        }
        $aRow = $oRes->row_array();
        $oRes->free_result();
        return $aRow['content'];
    }

function ecs_all($hash){
        $str=$this->get_download($hash);
	$data= json_decode($str,true);
$data['externalCSS'] = '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/profile.css" />';
			$data['externalJS'] = '';
			$data['scriptJS'] = '';
        require_once('assets/mpdf/mpdf.php');
        $mpdf = new mPDF('');
        $html = '
        <style>
        
element.style {
            }
            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }
            .bio-graph-heading {
                background: #41cac0;
                color: #fff;
                text-align: center;
                font-style: italic;
                padding: 40px 110px;
                border-radius: 4px 4px 0 0;
                -webkit-border-radius: 4px 4px 0 0;
                font-size: 24px;
                font-weight: 300;
            }    
            .bio-graph-info {
                color: #89817e;
            }
            .panel-body {
                padding: 15px;
            }
            .bio-row {
            width: 50%;
            float: left;
            margin-bottom: 10px;
            padding: 0 15px;
            }
            h1 {
            font-size: 16px;
            color: #000;
            }
            span{
            font-size: 12px;
            }
            p{
            font-size: 12px;
            }
            .profile-nav .user-heading {
            background: #ff766c;
            color: #fff;
            border-radius: 4px 4px 0 0;
            -webkit-border-radius: 4px 4px 0 0;
            padding: 30px;
            text-align: center;
            }
            
.profile-nav .user-heading p {
    font-size: 12px;
}
.profile-nav .user-heading h1 {
	text-decoration:underline;
	font-weight: 500;
}
.bio-graph-info h1  {
	text-decoration:underline;
    font-weight: 500;
}
.bio-graph-heading {
	font-size: 12px;
	padding: 15px;
}
.bio-search{
	font-size: 12px;
	padding: 15px 15px 0 15px;
}
.bio-row ul li {
    list-style-type: disc;
	margin-left: 15px;
}

.compt-blue{
	background-color: #5BC0DE;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.compt-green{
	background-color: #5CB85C;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.compt-red{
	background-color: #D9534F;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.tbl-compt tbody > tr > td{
	padding: 8px;
	line-height: 2;
	border: 1px solid #DDDDDD;
}

.tbl-compt > thead > tr > th {
	border: 1px solid #DDDDDD;
	vertical-align: middle;
	text-align: center;
	padding: 5px;
}
.table {
width: 100%;
margin-bottom: 20px;
}
table {
max-width: 100%;
background-color: transparent;
}
table {
border-collapse: collapse;
border-spacing: 0;
}

.tbl-compt> thead:first-child > tr:first-child > th {
	border-top: 1px solid #DDDDDD;
}

.space{
	padding: 5px;
}
</style>

            ';
$txtSelBase="Holding Competency";
			if($data['selBase']=="1"){
$txtSelBase="Holding Competency";
			}else{
$txtSelBase="Subsidiary Competency";
			}
$html.='<section class="panel">
			 <div class="bio-graph-heading" >
                            <table><tr><td><img alt="" src="' . base_url() . 'img/logo2.jpg" /></td><td>Pupuk Indonesia Talent Profile</td></tr></table>
			  </div>
			  <div class="panel-body bio-graph-info">
                            <table>
					<tr><td valign="top">
                            <div class="user-heading round"> 
					  <img alt="" src="' . base_url() . 'img/photo/' . $this->global_m->get_array_data($data['master_emp'], "PERNR") . '.jpg" onerror="this.src=\'' . base_url() . 'img/photo/default.jpg\';" height="120px"/>
			  </div>
				</td>
				<td valign="top">
                            <div class="user-heading round"> 
				  <h1>' . $this->global_m->get_array_data($data['master_emp'], "CNAME") . '</h1>
                                  <p>' . $this->global_m->get_array_data($data['master_emp'], "PERNR") . '
				  <br />' . $this->global_m->get_array_data($data['emp_map'], "POSISI") . '
				  <br />' . $this->global_m->get_array_data($data['emp_map'], "PERSH") . '</p>
			  </div>
				</td>
				</tr>
				</table>
                            <table>
					<tr><td valign="top">
					  <div class="user-heading round">
						  <h1>Position Data</h1>
						  <p><span>Current Position </span>: ' . $this->global_m->get_array_data($data['posCurrent'], "STEXT") . ' 
						  <br/><span>Job Level </span> : '. $this->global_m->get_array_data($data['posDetail'], "JOB_LEVEL") .'
						  <br/><span>Job Family </span> : '.  $this->global_m->get_array_data($data['posDetail'], "FAMILY_TXT").'</p>
					  </div>				
				</td>
				</tr>
				</table>
					  <div class="col-lg-12">
							<h1><b>Competency '.$txtSelBase.'</b></h1>
							<table class="table tbl-compt" border="1" colspan="0" cellpadding="0">
								<thead>
									<tr>
										<th rowspan="2">#</th>
										<th rowspan="2">Competency</th>
										<th colspan="6">Level</th>
									</tr>
									<tr>
										<th>1</th>
										<th>2</th>
										<th>3</th>
										<th>4</th>
										<th>5</th>
										<th>6</th>
									</tr>
								</thead>
								<tbody>';
        if ($data['empcompt']) {
		$sGroup = "";
		$iTotal = 0;
		for($i=0;$i<count($data['empcompt']);$i++){
			if($sGroup<>$data['empcompt'][$i]['OTYPE']){
				$sGroup = $data['empcompt'][$i]['OTYPE'];
				$html.='<tr>
					<td colspan="8">'.$data['comptDef']['KC'][$data['empcompt'][$i]['OTYPE']].'</td>
					</tr>';
			}

			$html.='<tr>
   					<td>'. ($i + 1).'</td>
					<td>'. $data['empcompt'][$i]['STEXT'].'</td>';
			$iTotal += $data['empcompt'][$i]['COVAL'];
			for($j=0; $j < $data['empcompt'][$i]['COVAL']; $j++){
				$html.='<td class="compt-green"></td>';
			} 
			if($j < 6){
				for($k = $j; $k < 6 ; $k++){
					$html.='<td></td>';
				}
			}
			$html.='</tr>';
		}
		$iAvg = $iTotal / $i;
		$html.='<tr>
				<td colspan="2"><b>Average</b></td>
				<td colspan="6"><b>'.number_format($iAvg, 2, ',', ' ').'</b></td>
			</tr>';
	}else{
		$html.='<tr><td colspan="8"><b>No Competency Data Found</b></td></tr>';
	} 
	$html.='</tbody>
							</table>
					  </div>
			  </div>
		  </section>';
 $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

// OUTPUT
        $mpdf->Output($this->global_m->get_array_data($data['master_emp'], "CNAME")." ( " . $this->global_m->get_array_data($data['master_emp'], "PERNR"). " ).pdf", "D");
        exit;
	
}

    function competency_summary($hash) {
        $str=$this->get_download($hash);
        $str='{"base_url":"http://localhost/talent/","view":"ecs/view","master_emp":{"id_emp":"2598","PERNR":"15000010","CNAME":"Paulus Poniman","GESCH":"1","GBDAT":"1959-05-26","GBLND":"Bantul","BEGDA":"1900-01-01","ENDDA":"9999-12-31"},"emp_org":{"id_eorg":"3074","PERNR":"15000010","BEGDA":"1900-01-01","ENDDA":"9999-12-31","PERSG":"1","PERSK":"1","BUKRS":"15000000","WERKS":"PSP","BTRTL":"PLM","ORGEH":"15000002","PLANS":"25000002","STELL":"GM"},"aCon":"","externalCSS":"","externalJS":"","scriptJS":"","emp_map":{"NIK":"870453","PERSH":"PT Pupuk Sriwidjaja Palembang","ORG":"Operasi","POSISI":"GM Operasi"},"emp":{"GBDAT":"26.05.1959","age":"55"},"comptDef":{"C1":{"ACH":"Achievement Orientation","CSO":"Customer Service Orientation","ING":"Integrity"},"C3":{"AT":"Analytical Thinking","CT":"Conceptual Thinking","TE":"Technical Expertise"},"C2":{"BO":"Business Orientation","ST":"Strategic Thinking","TL":"Team Leadership"},"KC":{"C1":"Core Competentcy","C2":"Leadership Competency","C3":"Role Competency","C4":"Additional Competency"},"C4":{"CO":"Concern For Order","DEV":"Developing Others","FLX":"Flexibility","IMP":"Impact & Influence","INF":"Information Seeking","INT":"Initiative","IU":"Interpersonal Understanding","OA":"Organizational Awareness","OC":"Organizational Commitment","RB":"Relationship Building","SCF":"Self Confidence","SCT":"Self Control","TW":"Team Work"},"JF":{"JF1":"Operasi","JF10":"Corporate Communication & General Affair","JF11":"Hukum","JF2":"Pemeliharaan","JF3":"Engineering/Konstruksi","JF4":"Pemasaran","JF5":"Keuangan","JF6":"Perencanaan dan Pengembangan","JF7":"Manajemen SDM","JF8":"Teknologi Informasi","JF9":"Manajemen Pengadaan"}},"posCurrent":{"STEXT":"GM Operasi"},"posCompare":{"STEXT":"GM Operasi"},"posDetail":{"STELL":"GM","FAMILY":"JF1","JOB_LEVEL":"General Manager - 1A","FAMILY_TXT":"Operasi"},"posCompt":{"C1":{"ACH":{"Pos":"4","Emp":0,"Match":0,"Gap":-4},"CSO":{"Pos":"4","Emp":"4","Match":100,"Gap":0},"ING":{"Pos":"4","Emp":"3","Match":75,"Gap":-1}},"C3":{"TE":{"Pos":"3","Emp":"5","Match":100,"Gap":0}}},"comptSub":{"C1":{"SubM":175,"SubG":-5,"Sub":3},"C3":{"SubM":100,"SubG":0,"Sub":1}},"comptTot":{"TotM":68.75,"TotG":-5},"plansCompare":{"PLANS":"25000002"}}';
        $data= json_decode($str,true);
        $sNopeg=$data['master_emp']['PERNR'];


			$data['externalCSS'] = '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/profile.css" />';
			$data['externalJS'] = '';
			$data['scriptJS'] = '';
        require_once('assets/mpdf/mpdf.php');
        $mpdf = new mPDF('');
        $html = '
        <style>
        
element.style {
            }
            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }
            .bio-graph-heading {
                background: #41cac0;
                color: #fff;
                text-align: center;
                font-style: italic;
                padding: 40px 110px;
                border-radius: 4px 4px 0 0;
                -webkit-border-radius: 4px 4px 0 0;
                font-size: 24px;
                font-weight: 300;
            }    
            .bio-graph-info {
                color: #89817e;
            }
            .panel-body {
                padding: 15px;
            }
            .bio-row {
            width: 50%;
            float: left;
            margin-bottom: 10px;
            padding: 0 15px;
            }
            h1 {
            font-size: 16px;
            }
            span{
            font-size: 12px;
            }
            p{
            font-size: 12px;
            }
            .profile-nav .user-heading {
            background: #ff766c;
            color: #fff;
            border-radius: 4px 4px 0 0;
            -webkit-border-radius: 4px 4px 0 0;
            padding: 30px;
            text-align: center;
            }
            
.profile-nav .user-heading p {
    font-size: 12px;
}
.profile-nav .user-heading h1 {
	text-decoration:underline;
	font-weight: 500;
}
.bio-graph-info h1  {
	text-decoration:underline;
    font-weight: 500;
}
.bio-graph-heading {
	font-size: 12px;
	padding: 15px;
}
.bio-search{
	font-size: 12px;
	padding: 15px 15px 0 15px;
}
.bio-row ul li {
    list-style-type: disc;
	margin-left: 15px;
}

.compt-blue{
	background-color: #5BC0DE;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.compt-green{
	background-color: #5CB85C;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.compt-red{
	background-color: #D9534F;
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
}

.tbl-compt tbody > tr > td{
	padding: 8px;
	line-height: 2;
	border: 1px solid #DDDDDD;
}

.tbl-compt > thead > tr > th {
	border: 1px solid #DDDDDD;
	vertical-align: middle;
	text-align: center;
	padding: 5px;
}
.table {
width: 100%;
margin-bottom: 20px;
}
table {
max-width: 100%;
background-color: transparent;
}
table {
border-collapse: collapse;
border-spacing: 0;
}

.tbl-compt> thead:first-child > tr:first-child > th {
	border-top: 1px solid #DDDDDD;
}

.space{
	padding: 5px;
}
</style>

            ';
        $html.='<section class="panel">
			  <div class="bio-graph-heading">
				  Employee Competency Summary
			  </div>
			  <div class="panel-body bio-graph-info">
                          <div class="user-heading round">
                          <table><tr><td>
					  <img alt="" src="' . base_url() . 'img/photo/' . $this->global_m->get_array_data($data['master_emp'], "PERNR") . '.png" onerror="this.src=\'' . base_url() . 'img/photo/default.jpg\';" height="120px";>
                                </td><td>
				  <h1>' . $this->global_m->get_array_data($data['master_emp'], "CNAME") . '</h1>
                                  <p>' . $this->global_m->get_array_data($data['master_emp'], "PERNR") . '
				  <br />' . $this->global_m->get_array_data($data['emp_map'], "POSISI") . '
				  <br />' . $this->global_m->get_array_data($data['emp_map'], "PERSH") . '</p>
                                      </td></tr></table>
			  </div>
                          <br/>
				  <div class="row">
					  <div class="col-lg-6">
						  <h1>Compare Data</h1>
						  <p><span>Last Position </span>: ' . $this->global_m->get_array_data($data['posCurrent'], "STEXT") . ' </p>
						  <p><span>Compare Position </span> : ' . $this->global_m->get_array_data($data['posCompare'], "STEXT") . '</p>
						  <p><span>Job Level </span> : '. $this->global_m->get_array_data($data['posDetail'], "JOB_LEVEL") .'</p>
						  <p><span>Job Family </span> : '.  $this->global_m->get_array_data($data['posDetail'], "FAMILY_TXT").'</p>
					  </div>
					  <div class="col-lg-12">
							<h1>Competency</h1>
							<p><span>Assessment Date </span>: 01.01.2012 </p>
							<table class="table tbl-compt" border="1" colspan="0" cellpadding="0">
								<thead>
									<tr>
										<th colspan="2" rowspan="2">Competency</th>
										<th colspan="6">Level</th>
										<th colspan="2">Match</th>
									</tr>
									<tr>
										<th>1</th>
										<th>2</th>
										<th>3</th>
										<th>4</th>
										<th>5</th>
										<th>6</th>
										<th>%</th>
										<th>Gap</th>
									</tr>
								</thead>
								<tbody>';
        if ($data['posCompt']) {
            foreach ($data['posCompt'] as $sGroup => $aPosCompt) {
                $html.='<tr>
												<td colspan="8"> ' . $data['comptDef']['KC'][$sGroup] . '</td>
												<td style="text-align: right;">'.number_format($data['comptSub'][$sGroup]['SubM'] / $data['comptSub'][$sGroup]['Sub'], 2, ',', ' ').' %</td>
												<td style="text-align: right;">'. $data['comptSub'][$sGroup]['SubG'].' </td>
											</tr>';
                if ($aPosCompt) {
                    $j = 1;
                    foreach ($aPosCompt as $sCompt => $aDetail) {
                        $html.='<tr>
													<td rowspan="2">' . $j . '</td>
													<td rowspan="2">' . $data['comptDef'][$sGroup][$sCompt] . '</td>';
                        for ($i = 0; $i < $aDetail['Pos']; $i++) {
                            $html.='<td class="compt-blue"></td>';
                        }
                        if ($i < 6) {
                            for ($k = $i; $k < 6; $k++) {
                                $html.='<td></td>';
                            }
                        }
                        $html.='<td rowspan="2">' . $aDetail['Match'] . '</td>
													<td rowspan="2">' . $aDetail['Gap'] . '</td>
												</tr>
												<tr>';

                        $sClass = ($aDetail['Emp']>=$aDetail['Pos']?"compt-green":"compt-red");
                        for($l=1; $l<=$aDetail['Emp']; $l++){
                                $html.= "<td class=\"".$sClass."\"></td>";
                                } 

                        if ($l < 6) {
                            for ($m = $l; $m <= 6; $m++) {
                                $html.= '<td></td>';
                            }
                        }
                        $html.='</tr>
												<tr>
													<td colspan="10"></td>
												</tr>';
                        $j++;
                    }
                }
            }
        }
        $html.='<tr>
										<td colspan="2">Total Point of Suitability</td>
										<td colspan="6"></td>
										<td style="text-align: right;">'. number_format($data['comptTot']['TotM'], 2, ',', ' ').' %</td>
										<td style="text-align: right;">'. $data['comptTot']['TotG'].'</td>
									</tr>
								</tbody>
							</table>
					  </div>
				  </div>
			  </div>
		  </section>';
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

// OUTPUT
        $mpdf->Output("Competency Summary " . $sNopeg . " to " . $data['posCompare']['STEXT'] . ".pdf", "D");
    }
}

?>
