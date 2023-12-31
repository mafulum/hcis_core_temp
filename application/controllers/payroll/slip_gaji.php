<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of slip_gaji
 *
 * @author mm
 */
class slip_gaji extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('payroll/running_payroll_m');
        $this->load->model('payroll/bank_transfer_m');
        $this->load->model('payroll/in_out_m');
        $this->load->model('orgchart_m');
        $this->load->model('global_m');
        $this->load->model('payroll/slip_mail_m');
        $this->load->model('payroll/document_transfer_m');
        $this->load->model('pa_payroll/addtlinfo_m');
        $this->load->library('FPDFlib');
    }

    public function index(){
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $this->load->model('payroll/offcycle_m');
        $data['offcycle'] = $this->offcycle_m->getOffCycle();
        $data['view'] = 'payroll/slip_gaji';
        $data['externalCSS'] ='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalCSS'] .='<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .='<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalCSS'] .='<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-datepicker/css/datepicker.css" />';
        
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['externalJS'] .='<script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>';
        // $data['externalJS'] .='<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.8/pdfobject.min.js" integrity="sha512-MoP2OErV7Mtk4VL893VYBFq8yJHWQtqJxTyIAsCVKzILrvHyKQpAwJf9noILczN6psvXUxTr19T5h+ndywCoVw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
        $data['externalJS'] .= '<script src="' . base_url() . 'assets/jquery.blockUI.js"></script>';
        $this->load->model('employee_m');
        $data['scriptJS'] = '<script type="text/javascript">
        var pdfjsLib = window["pdfjs-dist/build/pdf"];
        // The workerSrc property shall be specified.
        pdfjsLib.GlobalWorkerOptions.workerSrc = "//mozilla.github.io/pdf.js/build/pdf.worker.js";
		function format(item) {
			if (!item.id) return "<b>" + item.text + "</b>"; // optgroup
			return "&nbsp;&nbsp;&nbsp;" + item.text;
		};
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
                $(document).ready(function() {
                    $("#fnik").select2({
                        minimumInputLength: 1,
                        dropdownAutoWidth: true,
                        ajax: {
                            url: "' . base_url() . 'index.php/employee/fetch_emp/",
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

                    $("#fGenMasal").click(function(){
                        blockPage("Please wait for a while ");
                        $.ajax({
                            url: "'. base_url().'index.php/payroll/slip_gaji/go_massal",
                            type: "get", //send it through get method
                            timeout : 0,
                            data: { 
                              year: $("#gmFPeriodRegular").val(),
                              offcycle: $("#gmOffcycle").val(),
                              nopegs: $("#fniks").val(),
                            },
                            success: function(response) {
                                console.log(response);
                                var json = $.parseJSON(response);
                                window.location = "'. base_url().'index.php/payroll/slip_gaji/download_go_massal?content="+json.obj;
                                setTimeout($.unblockUI, 500);
                            },
                            error: function(xhr) {
                                blockPage("Error");
                                setTimeout($.unblockUI, 500);
                            }
                        });
                        
                    });

                    $("#fProcess").click(function(){
                        blockPage("Please wait for a while ");
                        vis_reg = "on";
                        if ($("#fIsReg").is(":checked"))
                        {
                            vis_reg = "on";
                        }else{
                            vis_reg = "off";
                        }
                        $.ajax({
                            url: "'. base_url().'index.php/payroll/slip_gaji/go",
                            type: "get", //send it through get method
                            timeout : 0,
                            data: { 
                              year: $("#fPeriodRegular").val(),
                              offcycle: $("#offcycle").val(),
                              nopegs: $("#fnik").val(),
                            },
                            success: function(response) {
                                console.log(response);
                                var json = $.parseJSON(response);

                                var loadingTask = pdfjsLib.getDocument({data: atob(json.obj)});
                                loadingTask.promise.then(function(pdf) {
                                console.log("PDF loaded");
                                // Fetch the first page
                                var pageNumber = 1;
                                pdf.getPage(pageNumber).then(function(page) {
                                    console.log("Page loaded");
                                    
                                    var scale = 1.5;
                                    var viewport = page.getViewport({scale: scale});

                                    // Prepare canvas using PDF page dimensions
                                    var canvas = document.getElementById("pdf_content");
                                    var context = canvas.getContext("2d");
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    // Render PDF page into canvas context
                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    var renderTask = page.render(renderContext);
                                    renderTask.promise.then(function () {
                                        console.log("Page rendered");
                                    });
                                });
                                }, function (reason) {
                                    // PDF loading error
                                    console.error(reason);
                                });

                                // $("#pdf_content").html("<iframe src=\"data:application/pdf;base64,"+json.obj+"\" height=\"100%\" width=\"100%\" type=\"application/pdf\"></iframe>");
                                // PDFObject.embed("data:application/pdf;base64,"+json.obj, "#pdf_content");
                                setTimeout($.unblockUI, 500);
                                // oTablePayrollTax.fnClearTable();
                                // oTablePayrollTax.fnAddData(response.content.tax);
                            },
                            error: function(xhr) {
                              //Do Something to handle error
                                blockPage("Error");
                                // oTablePayrollTax.fnClearTable();
                                setTimeout($.unblockUI, 500);
                                // $("#title").html("");
                            }
                        });
                    });
                    $("#cPeriodRegular").datepicker({
                        autoclose: true
                    });
                    $("#gmCPeriodRegular").datepicker({
                        autoclose: true
                    });
                    $("[data-toggle=\'switch\']").wrap(\'<div class="switch" />\').parent().bootstrapSwitch();
		});
		</script>';
        $this->load->view('main', $data);
    }

    public function go_massal(){
        set_time_limit(0);
        ini_set("max_execution_time",3600);
        $period = $this->input->get('year');
        $offcycle = $this->input->get('offcycle');
        $nopegs = $this->input->get('nopegs');
        $a_nopeg=explode(";",$nopegs);
        $year = substr($period,0,4);
        $zipname = "payslip/trash/gm_".date("Ymd_His").'.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);
        $aFile=[];
        if(!empty($offcycle)){
            $this->load->model('payroll/offcycle_m');
            $oOffcycle = $this->offcycle_m->getOffCycle($offcycle);
            $year = substr($oOffcycle['evtda'],0,4);
            foreach($a_nopeg as $nopeg){
                $filename_1 = getcwd().'/payslip/'.$nopeg."/".$year."/OFFCYCLE_".$oOffcycle['id'].".pdf";
                $filename_2 = getcwd().'/payslip/'.$nopeg."/".$year."/OFFCYCLE_".$oOffcycle['name'].".pdf";
                if(is_file($filename_1)){
                    $filename=$filename_1;
                    $aFile[]=$filename;
                    $zip->addFile($filename,$nopeg."_OFFCYCLE_".$oOffcycle['id'].".pdf");
                }else if(is_file($filename_2)){
                    $filename=$filename_2;
                    $aFile[]=$filename;
                    $zip->addFile($filename,$nopeg."_OFFCYCLE_".$oOffcycle['name'].".pdf");
                }else{
                    die($filename_1."|".$filename_2);
                }
            }
        }else{
            foreach($a_nopeg as $nopeg){
                $filename = getcwd().'/payslip/'.$nopeg."/".$year."/".$period.".pdf";
                if(is_file($filename)){
                    $aFile[]=$filename;
                    $zip->addFile($filename,$nopeg."_".$period.".pdf");
                }else{
                    die($filename);
                }
            }
        }
        $zip->close();
        echo json_encode(["obj"=>base64_encode($zipname)]);
        // header('Content-Type: application/zip');
        // header('Content-disposition: attachment; filename='.$zipname);
        // header('Content-Length: ' . filesize($zipname));
        // readfile($zipname);
    }

    public function download_go_massal(){
        $file_content = $this->input->get('content');
        $zipname = base64_decode($file_content);
        if(is_file($zipname)==false){
            die("Not Found");
        }
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.date("YmdHis").".zip");
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
    }
    
    public function go() {
        set_time_limit(0);
        ini_set("max_execution_time",3600);
        $period = $this->input->get('year');
        $offcycle = $this->input->get('offcycle');
        $nopeg = $this->input->get('nopegs');
        $year = substr($period,0,4);
        if(!empty($offcycle)){
            $this->load->model('payroll/offcycle_m');
            $oOffcycle = $this->offcycle_m->getOffCycle($offcycle);
            $year = substr($oOffcycle['evtda'],0,4);
            $filename_1 = getcwd().'/payslip/'.$nopeg."/".$year."/OFFCYCLE_".$oOffcycle['id'].".pdf";
            $filename_2 = getcwd().'/payslip/'.$nopeg."/".$year."/OFFCYCLE_".$oOffcycle['name'].".pdf";
            if(is_file($filename_1)){
                $filename=$filename_1;
            }else if(is_file($filename_2)){
                $filename=$filename_2;
            }else{
                die($filename_1."|".$filename_2);
            }
        }else{
            $filename = getcwd().'/payslip/'.$nopeg."/".$year."/".$period.".pdf";
        }
        $obj="";
        if(is_file($filename)){
            $file_contents = file_get_contents($filename); 
            $obj= base64_encode($file_contents);
        }
        echo json_encode(["obj"=>$obj]);
        // echo $year."|".$period."|".$nopeg;
        // $data = http_build_query($paramAPI);
        // $curl = curl_init();
        // $url = $this->config->item('base_url_engine_payroll').'/payroll/tax_yearly'."?".$data;
        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => $url,
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'GET',
        // ));

        // $response = curl_exec($curl);
        // curl_close($curl);
        
        // header('Content-Type: application/json');
        // echo $response;
    }
    
    public function sendMailSlip($id_document_transfer){
        $obj = $this->slip_mail_m->getSlipMailUnSent($id_document_transfer);
        var_dump($obj);
        exit;
    }

    private function keyValSlip($obj) {
        $aRet = array("str" => "", "amount" => "");
        if (empty($obj)) {
            return $aRet;
        }
        $aRet['str'] = $obj['LGTXT'];
        $aRet['amount'] = number_format($obj['WAMNT'], 0, ".", ",");
        return $aRet;
    }

    public function generate_single_regular($id_document_transfer, $pernr, $abkrs=null, $id_bank_transfer = null, $isRet = false) {
        ini_set("memory_limit", "512M");
        if (empty($id_bank_transfer)) {
            $id_bank_transfer = $this->bank_transfer_m->getIDBankTransferFromDocTransferEmp($id_document_transfer, $pernr);
            if (empty($id_bank_transfer)) {
                echo __LINE__;exit;
                return null;
            }
        }
        $wgtyps = $this->running_payroll_m->get_emp_wagetype_by_pernr_bank_transfer($id_bank_transfer, $pernr, $abkrs);
//        var_dump($wgtyps);exit;
        if(empty($wgtyps)){
           echo __LINE__;exit;
            return null;
        }
        $banks = $this->bank_transfer_m->get_bank_transfer_by_pernr_bank_transfer($id_bank_transfer, $pernr, $abkrs);
        if(empty($banks)){
           echo __LINE__;exit;
            return null;
        }
        $profile = $this->running_payroll_m->get_emp_profile_by_pernr_bank_transfer($id_bank_transfer, $pernr, $abkrs);
        if(empty($profile)){
           echo __LINE__;exit;
            return null;
        }
//        $profile['CNAME']="-";
//        $profile['PERNR']='-';
        $payroll_running = $this->running_payroll_m->get_row_by_bank_transfer($id_bank_transfer);
        // var_dump($payroll_running);exit;
        $addtl_info = null;
        if (empty($payroll_running['offcycle'])) {
            $profile['periode_text'] = $payroll_running['periode_regular'];
            $addtl_info = $this->addtlinfo_m->get_tm_emp_addtlinfo_by_periode_regular($payroll_running['periode_regular'], $pernr);
        } else {
            $profile['periode_text'] = $payroll_running['date_off_cycle'] . "_" . $payroll_running['name_of_process'];
            $addtl_info = $this->addtlinfo_m->get_tm_emp_addtlinfo_by_date_offcycle($payroll_running['date_off_cycle'], $pernr);
        }
//        var_dump($addtl_info);exit;
        $year = substr($profile['periode_text'], 0, 4);
        $filename = $profile['periode_text'];
        if (empty($profile['PLANS'])) {
            $profile['PLANS'] = "-";
        } else {
            $plans = $this->orgchart_m->get_master_org($profile['PLANS'], 'S');
            if (empty($plans)) {
                $profile['PLANS'] = "-";
            } else {
                $profile['PLANS'] = $plans['STEXT'];
            }
        }
        if (empty($profile['ORGEH'])) {
            $profile['ORGEH'] = "-";
        } else {
            $orgeh = $this->orgchart_m->get_master_org($profile['ORGEH'], 'O');
            if (empty($orgeh)) {
                $profile['ORGEH'] = "-";
            } else {
                $profile['ORGEH'] = $orgeh['STEXT'] . " (" . $orgeh['SHORT'] . ")";
            }
        }
        if (empty($profile['PERSG'])) {
            $profile['PERSG'] = "-";
        } else {
            $persg = $this->global_m->get_master_abbrev("3", "AND SHORT='" . $profile['PERSG'] . "'");
            if (empty($persg)) {
                $profile['PERSG'] = "-";
            } else {
                $profile['PERSG'] = $persg[0]['STEXT'];
            }
        }

        if (empty($profile['PERSK'])) {
            $profile['PERSK'] = "-";
        } else {
            $persk = $this->global_m->get_master_abbrev("4", "AND SHORT='" . $profile['PERSK'] . "'");
            if (empty($persk)) {
                $profile['PERSK'] = "-";
            } else {
                $profile['PERSK'] = $persk[0]['STEXT'];
            }
        }

        $aSlipPlus = array();
        $aSlipMinus = array();
        $aSlipComp = array();
        $thp = 0;
        $sumPlus = 0;
        $sumMinus = 0;
        $sumComp = 0;
//        var_dump($wgtyps);exit;
        foreach ($wgtyps as $wgtyp) {
            if (empty($wgtyp['WAMNT']) || $wgtyp['WAMNT'] == 0) {
                continue;
            }
            if ($wgtyp['PRTYP'] == '+') {
                $aSlipPlus[] = $wgtyp;
                $sumPlus = $sumPlus + $wgtyp['WAMNT'];
            } else if ($wgtyp['PRTYP'] == '-') {
                $aSlipMinus[] = $wgtyp;
                $sumMinus = $sumMinus + $wgtyp['WAMNT'];
            } else if ($wgtyp['PRTYP'] == '#' && !in_array($wgtyp['WGTYP'],['312E','812E'])) {
                $aSlipComp[] = $wgtyp;
                $sumComp = $sumComp + $wgtyp['WAMNT'];
            } else if ($wgtyp['PRTYP'] == '|' && $wgtyp['WGTYP'] == '/THP') {
                $thp = $wgtyp['WAMNT'];
            }
        }
        $max_line_slip = max(count($aSlipPlus), count($aSlipMinus), count($aSlipComp));

        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();

        $pdf->Image('http://localhost/img/Beyond_Care.png', 10, 2, 30, 0, 'PNG');
        $pdf->Image('http://localhost/img/gdps_logo_white.png', 210, 0, 60, 0, 'PNG');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);

        $aParamHead = array(1 => array('Nama', 'Periode'), 2 => array("Personal Number", 'Empl Group'),
            3 => array("Position", "Empl Sub Group"), 4 => array("Company", "Unit"));
        $aParamKV = array(1 => array('CNAME', 'periode_text'), 2 => array("PERNR", 'PERSG'),
            3 => array("PLANS", "PERSK"), 4 => array("WERKS", "ORGEH"));
        for ($i = 1; $i <= 4; $i++) {
            $pdf->Cell(40, 5, $aParamHead[$i][0], 1, 0, 'L');
            $pdf->Cell(5, 5, ":", 1, 0, 'C');
            $pdf->Cell(87, 5, $profile[$aParamKV[$i][0]], 1, 0, 'L');
            $pdf->Cell(40, 5, $aParamHead[$i][1], 1, 0, 'L');
            $pdf->Cell(5, 5, ":", 1, 0, 'C');
            $pdf->Cell(87, 5, $profile[$aParamKV[$i][1]], 1, 1, 'L');
        }
        $pdf->Ln(10);
        $pdf->Cell(88, 5, "Penerimaan", 1, 0, 'C');
        $pdf->Cell(88, 5, "Kontribusi Karyawan", 1, 0, 'C');
        $pdf->Cell(88, 5, "Kontribusi Perusahaan", 1, 1, 'C');
        $aslipDefault = array("str" => "", "amount" => "");
        $pdf->SetFont('Arial', '', 10);
        for ($i = 0; $i < $max_line_slip; $i++) {
            if (empty($aSlipPlus[$i])) {
                $aslip = $aslipDefault;
            } else {
                $aslip = $this->keyValSlip($aSlipPlus[$i]);
            }
            $pdf->Cell(60, 5, $aslip['str'], 1, 0, 'L');
            $pdf->Cell(28, 5, $aslip['amount'], 1, 0, 'R');
            if (empty($aSlipMinus[$i])) {
                $aslip = $aslipDefault;
            } else {
                $aslip = $this->keyValSlip($aSlipMinus[$i]);
            }
            $pdf->Cell(60, 5, $aslip['str'], 1, 0, 'L');
            $pdf->Cell(28, 5, $aslip['amount'], 1, 0, 'R');
            if (empty($aSlipComp[$i])) {
                $aslip = $aslipDefault;
            } else {
                $aslip = $this->keyValSlip($aSlipComp[$i]);
            }
            $pdf->Cell(60, 5, $aslip['str'], 1, 0, 'L');
            $pdf->Cell(28, 5, $aslip['amount'], 1, 1, 'R');
        }
//        var_dump($aSlipPlus);
//        echo "<br/>";
//        var_dump($sumMinus);
//        echo "<br/>";
//        var_dump($sumComp);
//        echo "<br/>";
//        var_dump($sumPlus - $sumMinus);
//        echo "<br/>";
//        exit;
        $pdf->Cell(60, 5, "Jumlah : ", 1, 0, 'R');
        $pdf->Cell(28, 5, number_format($sumPlus, 0, ".", ","), 1, 0, 'R');
        $pdf->Cell(60, 5, "Jumlah : ", 1, 0, 'R');
        $pdf->Cell(28, 5, number_format($sumMinus, 0, ".", ","), 1, 0, 'R');
        $pdf->Cell(60, 5, "Jumlah :", 1, 0, 'R');
        $pdf->Cell(28, 5, number_format($sumComp, 0, ".", ","), 1, 1, 'R');

        $pdf->Cell(60, 5, "Take Home Pay :", 1, 0, 'R');
        $pdf->Cell(28, 5, number_format($sumPlus - $sumMinus, 0, ".", ","), 1, 1, 'R');
        
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(264, 5, "Bank Transfer", 1, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        if (!empty($banks)) {
            foreach ($banks as $bank) {
//                $bank['BANK_PAYEE']='NAMA';
//                $bank['BANK_ACCOUNT']='BANK_ACCOUNT';
                $pdf->Cell(60, 5, $bank["BANK_NAME"], 1, 0, 'L');
                $pdf->Cell(70, 5, $bank["BANK_PAYEE"], 1, 0, 'L');
                $pdf->Cell(60, 5, $bank["BANK_ACCOUNT"], 1, 0, 'L');
                $pdf->Cell(74, 5, number_format($bank["WAMNT"], 0, ".", ","), 1, 1, 'R');
            }
        }
        //if exist additional information
        if (!empty($addtl_info)) {
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(264, 5, "Additional Information :", 1, 1, 'L');
            $pdf->SetFont('Arial', '', 10);
            foreach ($addtl_info as $row) {
                $pdf->Cell(264, 5, $row['note'], 1, 1, 'L');
            }
        }
        if ($isRet) {
            return array('pdf' => $pdf, 'year' => $year, 'filename' => $filename . ".pdf");
        } else {
            
            $pdf->Output();
            exit;
        }
    }

    public function generate_slip_regular_single_file($id_document_transfer, $pernr, $abkrs=null, $id_bank_transfer=null) {
        $aret = $this->generate_single_regular($id_document_transfer, $pernr, $abkrs, $id_bank_transfer, true);
        if (!empty($aret['pdf'])) {
            $year = $aret['year'];
            if (!is_dir("payslip/" . $pernr)) {
                mkdir("payslip/" . $pernr);
            }
            if (!is_dir("payslip/" . $pernr . "/" . $year)) {
                mkdir("payslip/" . $pernr . "/" . $year);
            }
            if (is_file("payslip/" . $pernr . "/" . $year . "/" . $aret['filename'])) {
                //move to trash
                rename("payslip/" . $pernr . "/" . $year . "/" . $aret['filename'], "payslip/trash/" . $pernr . "_" . $year . "_" . $aret['filename'] . "_" . date("YmdHis"));
            }
            $aret['pdf']->output('F', "payslip/" . $pernr . "/" . $year . "/" . $aret['filename']);
//                $btemps
            //check folder pernr/tahun     
            // pernr/period regular /tahun/ offcycle --- 2021-01.pdf / offcycle : 2021-01-01_CBLABLABLA.pdf
            //save in directory employee
            echo "DONE";
        } else {
            echo "NOT AVAILABLE";
        }
    }

    public function generate_slip_regular($id_document_transfer) {
        set_time_limit(0);
        $file_go_trash = true;
        $doc_transfer = $this->document_transfer_m->getDocumentTransfer($id_document_transfer);
        if (empty($doc_transfer)) {
            return null;
        }
        $inout=[];
        $payroll_running=[];
        $bank_transfer_emps = $this->bank_transfer_m->getPernrAbkrsStagesByIDStages(explode(",", $doc_transfer['id_bts_codes']));
        $zipname = "payslip/SLIP_".$id_document_transfer.'_'.$doc_transfer['name']. '.zip';
        if (is_file($zipname)==false) {
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);
            foreach ($bank_transfer_emps as $btemps) {
                if (empty($btemps['PERNR'])) {
                    continue;
                }
                if(!empty($inout[$btemps['id_bank_transfer']])){
                    //already detected as inout process
                    continue;
                }
                if (empty($btemps['id_bank_transfer'])) {
                    var_dump($btemps);
                    echo $id_document_transfer . " | ".$btemps['id_bank_transfer']. " | EMPTY PROFILE BANK TRANSFER";
                    echo __LINE__;exit;
                }
                $profile = $this->running_payroll_m->get_emp_profile_by_pernr_bank_transfer($btemps['id_bank_transfer'], $btemps['PERNR'], null);
                $filename=null;
                if(empty($profile)){
                    //CHECK IF INOUT
                    $bank_transfer = $this->bank_transfer_m->getBankTransfer($btemps['id_bank_transfer']);
                    $in_out_name = substr($bank_transfer['name'],6);
                    // echo $in_out_name;exit;
                    $inout = $this->in_out_m->getInOutByName($in_out_name);
                    if(!empty($inout)){
                        $inout[$btemps['id_bank_transfer']]=$inout;
                        continue;
                    }
                    var_dump($btemps);
                    echo $id_document_transfer . " | ".$btemps['PERNR']. " | EMPTY PROFILE BANK TRANSFER";
                    echo __LINE__;exit;
                }else{
                    if(empty($payroll_running[$profile['id_payroll_running']])){
                        $temp_payroll_running=$this->running_payroll_m->get_by_id($profile['id_payroll_running']);
                        if(empty($temp_payroll_running)){
                            continue;
                        }
                        $payroll_running[$profile['id_payroll_running']] = $temp_payroll_running;
                    }else{
                        $temp_payroll_running = $payroll_running[$profile['id_payroll_running']];
                    }
                    
                    if (empty($payroll_running['offcycle'])) {
                        $filename = $temp_payroll_running['periode_regular'];
                    } else {
                        $filename = $temp_payroll_running['date_off_cycle'] . "_" . $temp_payroll_running['name_of_process'];
                    }
                    // $filename = $profile['periode_text'].".pdf";
                }
                $aRet=null;
                $pernr = $btemps['PERNR'];
                $year = substr($payroll_running[$profile['id_payroll_running']]['periode_regular'],0,4);
                if (!is_dir("payslip/" . $pernr)) {
                    mkdir("payslip/" . $pernr);
                }
                if (!is_dir("payslip/" . $pernr . "/" . $year)) {
                    mkdir("payslip/" . $pernr . "/" . $year);
                }
                if (is_file("payslip/" . $pernr . "/" . $year . "/" . $filename)==false || $file_go_trash) {
                    $aret = $this->generate_single_regular($id_document_transfer, $btemps['PERNR'], null, $btemps['id_bank_transfer'], true);
                    if (!empty($aret) && !empty($aret['pdf'])) {
                        if (is_file("payslip/" . $pernr . "/" . $year . "/" . $aret['filename']) && $file_go_trash) {
                            rename("payslip/" . $pernr . "/" . $year . "/" . $aret['filename'], "payslip/trash/" . $pernr . "_" . $year . "_" . $aret['filename'] . "_" . date("YmdHis"));
                        }
                        $spath = "payslip/" . $pernr . "/" . $year . "/" . $aret['filename'];
                        $aret['pdf']->output('F', $spath);
                        $zip->addFile($spath);
                    }
                }else if(is_file("payslip/" . $pernr . "/" . $year . "/" . $filename) && $file_go_trash==false){
                    $spath = "payslip/" . $pernr . "/" . $year . "/" . $filename;
                    $zip->addFile($spath);
                }
                // $aret = $this->generate_single_regular($id_document_transfer, $btemps['PERNR'], $btemps['ABKRS'], $btemps['id_bank_transfer'], true);
                
            }
            $zip->close();
        }
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
    }

//  As request UHC UQ 
    public function gen_dummy_slip_linfox(){
                        //0         1       2       3       4           5           6               7   8       9               10      11
        $arr_ref_emp = ['period','nopeg','nama','position','company','emp_group','emp_sub_group','unit','thp','bank_name','bank_payee','bank_number'];
        $arr_emp_1 = [['2022-11','9710187','Nadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NADI','1109169923'],['2022-11','9713287','Heri','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Syariah Indonesia','HERI HERIYANTO','7185430604'],['2022-11','9713208','Abdul Majid','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ABDUL MAJID','1499598593'],['2022-11','9710189','Ismail Saiful M','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ISMAIL SAIFUL M','0902152370'],['2022-11','9714136','Sunaryat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BRI','SUNARYAT','227501002113505'],['2022-11','9713209','Mulyanto','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MULYANTO','0605000902'],['2022-11','9710191','Kusnadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KUSNADI','0836107705'],['2022-11','9710195','Samsul Bahri','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SAMSUL BAHRI ','0864725059'],['2022-11','9710193','Romli','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROMLI','0979134725'],['2022-11','9710192','Marsa','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MARSA','0915868537'],['2022-11','9713690','Chairul Umam','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KHAIRUL UMAM','1569467630'],['2022-11','9713688','Ramdan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Mandiri','RAMDAN','1560020059905'],['2022-11','9712164','Naufal Fauzan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NAUFAL FAUZAN','1438669171'],['2022-11','9710202','Roy Jaenudin','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROY JAENUDIN','1136024088'],['2022-11','9710201','Herman Bin Esan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','HERMAN SUSANTO','1108062695'],['2022-11','9710200','Mamat Sopiyan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Mandiri','MAMAT SOPIYAN','1270009962489'],['2022-11','9713687','Kurdi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KURDI','1569463668'],['2022-11','9713841','Reza Riyadika','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','REZA RIYADIKA','1575018369'],['2022-11','9710243','Rosid Ardiana','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROSID ARDIANA','1361868093'],['2022-11','9710203','Niman Budiman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NIMAN BUDIMAN','0969771596'],['2022-11','9713689','Siti Nurjanah','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SITI NURJANAH','1134531432'],['2022-11','9710205','Usih Susilawati','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','USIH SUSILAWATI','1342474974'],['2022-11','9713691','Ainun','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','AINUN','1559764984'],['2022-11','9710241','Agung Gunawan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','AGUNG GUNAWAN','1353082032'],['2022-11','9710314','Asep Junaedi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ASEP JUNAEDI','1370976708'],['2022-11','9710242','Imam Sudrajat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','IMAM SUDRAJAT','1362107977'],['2022-11','9712357','Abdul Rahman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','BPK ABDUL RAHMAN','1447592648'],['2022-11','9710294','Ardi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ARDI','0815761798'],['2022-11','9710301','Abdul Rosid','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ABDUL ROSID','0850548720'],['2022-11','9710295','Rahmat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','RAHMAT','0815761732'],['2022-11','9710186','Ade Dadang Putra','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ADE DADANG PUTRA','0969479615'],['2022-11','9710298','Muhamad Sidik','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MUHAMAD SIDIK','0815761551'],['2022-11','9710293','Yopri Apillatul','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','YOPRI APILLATUL','0829329193'],['2022-11','9710302','Endang','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI',' ENDANG','0850111098'],['2022-11','9710300','Maman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MAMAN','0486085027'],['2022-11','9710297','Sadim','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SADIM','0815761765'],['2022-11','9710291','Naja','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NAJA BIN ALIN','0815761630'],['2022-11','9710290','Nurdin','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NURDIN','0815761674'],['2022-11','9710204','Siti Hodijah','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SITI HODIJAH','1332614927'],['2022-11','9710305','Euis Sugita','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','EUIS SUGITA','1233324412'],['2022-11','9710299','Cahyadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI',' CAHYADI','0815361360'],['2022-11','9710303','Sunaryo','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SUNARYO','0834355853'],['2022-11','9710304','Edi Sudrajat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','EDI SUDRAJAT','0978275860'],['2022-11','9712356','Eko Nucahyo','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4784769','Bank Mandiri','EKO NURCAHYO ','1560016901771'],['2022-11','9710306','Barudin','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4784769','Bank BNI','BARUDIN','0815761709'],['2022-11','9710307','Yusup Sugianto','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4784769','Bank BNI','YUSUP SUGIANTO','0815928024'],['2022-11','9713212','Arif Sugiyanto','Site Manager','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','7000000','Bank Mandiri','HENDRA','1020007535971'],['2022-11','9713445','Ahmad Kurki','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4912669','Bank Mandiri','AHMAD KURKI','0060010799165'],['2022-11','9710287','Roni Ardi','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','5054269','Bank BNI','RONI ARDI','0583480526'],['2022-11','9710288','Dian Budiargo','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4920500','Bank BNI','DIAN BUDIARGO','1338222311'],['2022-11','9800196','Eko Priyanto','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','5054269','Bank Mandiri','EKO PRIYANTO','0700005830539'],['2022-12','9710187','Nadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NADI','1109169923'],['2022-12','9713287','Heri','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Syariah Indonesia','HERI HERIYANTO','7185430604'],['2022-12','9713208','Abdul Majid','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ABDUL MAJID','1499598593'],['2022-12','9710189','Ismail Saiful M','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ISMAIL SAIFUL M','0902152370'],['2022-12','9714136','Sunaryat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BRI','SUNARYAT','227501002113505'],['2022-12','9713209','Mulyanto','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MULYANTO','0605000902'],['2022-12','9710191','Kusnadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KUSNADI','0836107705'],['2022-12','9710195','Samsul Bahri','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SAMSUL BAHRI ','0864725059'],['2022-12','9710193','Romli','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROMLI','0979134725'],['2022-12','9710192','Marsa','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MARSA','0915868537'],['2022-12','9713690','Chairul Umam','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KHAIRUL UMAM','1569467630'],['2022-12','9713688','Ramdan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Mandiri','RAMDAN','1560020059905'],['2022-12','9712164','Naufal Fauzan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NAUFAL FAUZAN','1438669171'],['2022-12','9710202','Roy Jaenudin','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROY JAENUDIN','1136024088'],['2022-12','9710201','Herman Bin Esan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','HERMAN SUSANTO','1108062695'],['2022-12','9710200','Mamat Sopiyan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Mandiri','MAMAT SOPIYAN','1270009962489'],['2022-12','9713687','Kurdi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KURDI','1569463668'],['2022-12','9713841','Reza Riyadika','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','REZA RIYADIKA','1575018369'],['2022-12','9710243','Rosid Ardiana','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROSID ARDIANA','1361868093'],['2022-12','9710203','Niman Budiman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NIMAN BUDIMAN','0969771596'],['2022-12','9713689','Siti Nurjanah','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SITI NURJANAH','1134531432'],['2022-12','9710205','Usih Susilawati','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','USIH SUSILAWATI','1342474974'],['2022-12','9713691','Ainun','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','AINUN','1559764984'],['2022-12','9710241','Agung Gunawan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','AGUNG GUNAWAN','1353082032']];
        $arr_emp_2 = [['2022-12','9710314','Asep Junaedi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ASEP JUNAEDI','1370976708'],['2022-12','9710242','Imam Sudrajat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','IMAM SUDRAJAT','1362107977'],['2022-12','9712357','Abdul Rahman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','BPK ABDUL RAHMAN','1447592648'],['2022-12','9710294','Ardi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ARDI','0815761798'],['2022-12','9710301','Abdul Rosid','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ABDUL ROSID','0850548720'],['2022-12','9710295','Rahmat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','RAHMAT','0815761732'],['2022-12','9710186','Ade Dadang Putra','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ADE DADANG PUTRA','0969479615'],['2022-12','9710298','Muhamad Sidik','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MUHAMAD SIDIK','0815761551'],['2022-12','9710293','Yopri Apillatul','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','YOPRI APILLATUL','0829329193'],['2022-12','9710302','Endang','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI',' ENDANG','0850111098'],['2022-12','9710300','Maman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MAMAN','0486085027'],['2022-12','9710297','Sadim','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SADIM','0815761765'],['2022-12','9710291','Naja','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NAJA BIN ALIN','0815761630'],['2022-12','9710290','Nurdin','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NURDIN','0815761674'],['2022-12','9710204','Siti Hodijah','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SITI HODIJAH','1332614927'],['2022-12','9710305','Euis Sugita','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','EUIS SUGITA','1233324412'],['2022-12','9710299','Cahyadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI',' CAHYADI','0815361360'],['2022-12','9710303','Sunaryo','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SUNARYO','0834355853'],['2022-12','9710304','Edi Sudrajat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','EDI SUDRAJAT','0978275860'],['2022-12','9712356','Eko Nucahyo','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4791769','Bank Mandiri','EKO NURCAHYO ','1560016901771'],['2022-12','9710306','Barudin','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4791769','Bank BNI','BARUDIN','0815761709'],['2022-12','9710307','Yusup Sugianto','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4791769','Bank BNI','YUSUP SUGIANTO','0815928024'],['2022-12','9713212','Arif Sugiyanto','Site Manager','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','7000000','Bank Mandiri','HENDRA','1020007535971'],['2022-12','9713445','Ahmad Kurki','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4925169','Bank Mandiri','AHMAD KURKI','0060010799165'],['2022-12','9710287','Roni Ardi','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','5066769','Bank BNI','RONI ARDI','0583480526'],['2022-12','9710288','Dian Budiargo','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4933000','Bank BNI','DIAN BUDIARGO','1338222311'],['2022-12','9800196','Eko Priyanto','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','5066769','Bank Mandiri','EKO PRIYANTO','0700005830539'],['2023-01','9710187','Nadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NADI','1109169923'],['2023-01','9713287','Heri','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Syariah Indonesia','HERI HERIYANTO','7185430604'],['2023-01','9713208','Abdul Majid','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ABDUL MAJID','1499598593'],['2023-01','9710189','Ismail Saiful M','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ISMAIL SAIFUL M','0902152370'],['2023-01','9714136','Sunaryat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BRI','SUNARYAT','227501002113505'],['2023-01','9713209','Mulyanto','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MULYANTO','0605000902'],['2023-01','9710191','Kusnadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KUSNADI','0836107705'],['2023-01','9710195','Samsul Bahri','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SAMSUL BAHRI ','0864725059'],['2023-01','9710193','Romli','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROMLI','0979134725'],['2023-01','9710192','Marsa','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MARSA','0915868537'],['2023-01','9713690','Chairul Umam','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KHAIRUL UMAM','1569467630'],['2023-01','9713688','Ramdan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Mandiri','RAMDAN','1560020059905'],['2023-01','9712164','Naufal Fauzan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NAUFAL FAUZAN','1438669171'],['2023-01','9710202','Roy Jaenudin','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROY JAENUDIN','1136024088'],['2023-01','9710201','Herman Bin Esan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','HERMAN SUSANTO','1108062695'],['2023-01','9710200','Mamat Sopiyan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank Mandiri','MAMAT SOPIYAN','1270009962489'],['2023-01','9713687','Kurdi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','KURDI','1569463668'],['2023-01','9713841','Reza Riyadika','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','REZA RIYADIKA','1575018369'],['2023-01','9710243','Rosid Ardiana','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ROSID ARDIANA','1361868093'],['2023-01','9710203','Niman Budiman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NIMAN BUDIMAN','0969771596'],['2023-01','9713689','Siti Nurjanah','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SITI NURJANAH','1134531432'],['2023-01','9710205','Usih Susilawati','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','USIH SUSILAWATI','1342474974'],['2023-01','9713691','Ainun','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','AINUN','1559764984'],['2023-01','9710241','Agung Gunawan','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','AGUNG GUNAWAN','1353082032'],['2023-01','9710314','Asep Junaedi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ASEP JUNAEDI','1370976708'],['2023-01','9710242','Imam Sudrajat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','IMAM SUDRAJAT','1362107977'],['2023-01','9712357','Abdul Rahman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Mitra','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','BPK ABDUL RAHMAN','1447592648'],['2023-01','9710294','Ardi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ARDI','0815761798'],['2023-01','9710301','Abdul Rosid','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ABDUL ROSID','0850548720'],['2023-01','9710295','Rahmat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','RAHMAT','0815761732'],['2023-01','9710186','Ade Dadang Putra','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','ADE DADANG PUTRA','0969479615'],['2023-01','9710298','Muhamad Sidik','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MUHAMAD SIDIK','0815761551'],['2023-01','9710293','Yopri Apillatul','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','YOPRI APILLATUL','0829329193'],['2023-01','9710302','Endang','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI',' ENDANG','0850111098'],['2023-01','9710300','Maman','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','MAMAN','0486085027'],['2023-01','9710297','Sadim','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SADIM','0815761765'],['2023-01','9710291','Naja','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NAJA BIN ALIN','0815761630'],['2023-01','9710290','Nurdin','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','NURDIN','0815761674'],['2023-01','9710204','Siti Hodijah','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SITI HODIJAH','1332614927'],['2023-01','9710305','Euis Sugita','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','EUIS SUGITA','1233324412'],['2023-01','9710299','Cahyadi','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI',' CAHYADI','0815361360'],['2023-01','9710303','Sunaryo','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','SUNARYO','0834355853'],['2023-01','9710304','Edi Sudrajat','Operator Cleaner','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4600169','Bank BNI','EDI SUDRAJAT','0978275860'],['2023-01','9712356','Eko Nucahyo','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4791769','Bank Mandiri','EKO NURCAHYO ','1560016901771'],['2023-01','9710306','Barudin','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4791769','Bank BNI','BARUDIN','0815761709'],['2023-01','9710307','Yusup Sugianto','Gondola','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4791769','Bank BNI','YUSUP SUGIANTO','0815928024'],['2023-01','9713212','Arif Sugiyanto','Site Manager','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','7000000','Bank Mandiri','HENDRA','1020007535971'],['2023-01','9713445','Ahmad Kurki','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4925169','Bank Mandiri','AHMAD KURKI','0060010799165'],['2023-01','9710287','Roni Ardi','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','5066769','Bank BNI','RONI ARDI','0583480526'],['2023-01','9710288','Dian Budiargo','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','4933000','Bank BNI','DIAN BUDIARGO','1338222311'],['2023-01','9800196','Eko Priyanto','Team Leader','LNFX','Karyawan Kontrak TAD','Project','PT Linfox Logistics indonesia (LNFX)','5066769','Bank Mandiri','EKO PRIYANTO','0700005830539']];
        $arr_det_1 = [['2022-11','9710187','Gaji Pokok','+','1','4791843'],['2022-11','9713287','Gaji Pokok','+','1','4791843'],['2022-11','9713208','Gaji Pokok','+','1','4791843'],['2022-11','9710189','Gaji Pokok','+','1','4791843'],['2022-11','9714136','Gaji Pokok','+','1','4791843'],['2022-11','9713209','Gaji Pokok','+','1','4791843'],['2022-11','9710191','Gaji Pokok','+','1','4791843'],['2022-11','9710195','Gaji Pokok','+','1','4791843'],['2022-11','9710193','Gaji Pokok','+','1','4791843'],['2022-11','9710192','Gaji Pokok','+','1','4791843'],['2022-11','9713690','Gaji Pokok','+','1','4791843'],['2022-11','9713688','Gaji Pokok','+','1','4791843'],['2022-11','9712164','Gaji Pokok','+','1','4791843'],['2022-11','9710202','Gaji Pokok','+','1','4791843'],['2022-11','9710201','Gaji Pokok','+','1','4791843'],['2022-11','9710200','Gaji Pokok','+','1','4791843'],['2022-11','9713687','Gaji Pokok','+','1','4791843'],['2022-11','9713841','Gaji Pokok','+','1','4791843'],['2022-11','9710243','Gaji Pokok','+','1','4791843'],['2022-11','9710203','Gaji Pokok','+','1','4791843'],['2022-11','9713689','Gaji Pokok','+','1','4791843'],['2022-11','9710205','Gaji Pokok','+','1','4791843'],['2022-11','9713691','Gaji Pokok','+','1','4791843'],['2022-11','9710241','Gaji Pokok','+','1','4791843'],['2022-11','9710314','Gaji Pokok','+','1','4791843'],['2022-11','9710242','Gaji Pokok','+','1','4791843'],['2022-11','9712357','Gaji Pokok','+','1','4791843'],['2022-11','9710294','Gaji Pokok','+','1','4791843'],['2022-11','9710301','Gaji Pokok','+','1','4791843'],['2022-11','9710295','Gaji Pokok','+','1','4791843'],['2022-11','9710186','Gaji Pokok','+','1','4791843'],['2022-11','9710298','Gaji Pokok','+','1','4791843'],['2022-11','9710293','Gaji Pokok','+','1','4791843'],['2022-11','9710302','Gaji Pokok','+','1','4791843'],['2022-11','9710300','Gaji Pokok','+','1','4791843'],['2022-11','9710297','Gaji Pokok','+','1','4791843'],['2022-11','9710291','Gaji Pokok','+','1','4791843'],['2022-11','9710290','Gaji Pokok','+','1','4791843'],['2022-11','9710204','Gaji Pokok','+','1','4791843'],['2022-11','9710305','Gaji Pokok','+','1','4791843'],['2022-11','9710299','Gaji Pokok','+','1','4791843'],['2022-11','9710303','Gaji Pokok','+','1','4791843'],['2022-11','9710304','Gaji Pokok','+','1','4791843'],['2022-11','9710187','BPJS JKN EE','-','1','47918'],['2022-11','9713287','BPJS JKN EE','-','1','47918'],['2022-11','9713208','BPJS JKN EE','-','1','47918'],['2022-11','9710189','BPJS JKN EE','-','1','47918'],['2022-11','9714136','BPJS JKN EE','-','1','47918'],['2022-11','9713209','BPJS JKN EE','-','1','47918'],['2022-11','9710191','BPJS JKN EE','-','1','47918'],['2022-11','9710195','BPJS JKN EE','-','1','47918'],['2022-11','9710193','BPJS JKN EE','-','1','47918'],['2022-11','9710192','BPJS JKN EE','-','1','47918'],['2022-11','9713690','BPJS JKN EE','-','1','47918'],['2022-11','9713688','BPJS JKN EE','-','1','47918'],['2022-11','9712164','BPJS JKN EE','-','1','47918'],['2022-11','9710202','BPJS JKN EE','-','1','47918'],['2022-11','9710201','BPJS JKN EE','-','1','47918'],['2022-11','9710200','BPJS JKN EE','-','1','47918'],['2022-11','9713687','BPJS JKN EE','-','1','47918'],['2022-11','9713841','BPJS JKN EE','-','1','47918'],['2022-11','9710243','BPJS JKN EE','-','1','47918'],['2022-11','9710203','BPJS JKN EE','-','1','47918'],['2022-11','9713689','BPJS JKN EE','-','1','47918'],['2022-11','9710205','BPJS JKN EE','-','1','47918'],['2022-11','9713691','BPJS JKN EE','-','1','47918'],['2022-11','9710241','BPJS JKN EE','-','1','47918'],['2022-11','9710314','BPJS JKN EE','-','1','47918'],['2022-11','9710242','BPJS JKN EE','-','1','47918'],['2022-11','9712357','BPJS JKN EE','-','1','47918'],['2022-11','9710294','BPJS JKN EE','-','1','47918'],['2022-11','9710301','BPJS JKN EE','-','1','47918'],['2022-11','9710295','BPJS JKN EE','-','1','47918'],['2022-11','9710186','BPJS JKN EE','-','1','47918'],['2022-11','9710298','BPJS JKN EE','-','1','47918'],['2022-11','9710293','BPJS JKN EE','-','1','47918'],['2022-11','9710302','BPJS JKN EE','-','1','47918'],['2022-11','9710300','BPJS JKN EE','-','1','47918'],['2022-11','9710297','BPJS JKN EE','-','1','47918'],['2022-11','9710291','BPJS JKN EE','-','1','47918'],['2022-11','9710290','BPJS JKN EE','-','1','47918'],['2022-11','9710204','BPJS JKN EE','-','1','47918'],['2022-11','9710305','BPJS JKN EE','-','1','47918'],['2022-11','9710299','BPJS JKN EE','-','1','47918'],['2022-11','9710303','BPJS JKN EE','-','1','47918'],['2022-11','9710304','BPJS JKN EE','-','1','47918'],['2022-11','9710187','BPJS JP EE','-','2','47918'],['2022-11','9713287','BPJS JP EE','-','2','47918'],['2022-11','9713208','BPJS JP EE','-','2','47918'],['2022-11','9710189','BPJS JP EE','-','2','47918'],['2022-11','9714136','BPJS JP EE','-','2','47918'],['2022-11','9713209','BPJS JP EE','-','2','47918'],['2022-11','9710191','BPJS JP EE','-','2','47918'],['2022-11','9710195','BPJS JP EE','-','2','47918'],['2022-11','9710193','BPJS JP EE','-','2','47918'],['2022-11','9710192','BPJS JP EE','-','2','47918'],['2022-11','9713690','BPJS JP EE','-','2','47918'],['2022-11','9713688','BPJS JP EE','-','2','47918'],['2022-11','9712164','BPJS JP EE','-','2','47918'],['2022-11','9710202','BPJS JP EE','-','2','47918'],['2022-11','9710201','BPJS JP EE','-','2','47918'],['2022-11','9710200','BPJS JP EE','-','2','47918'],['2022-11','9713687','BPJS JP EE','-','2','47918'],['2022-11','9713841','BPJS JP EE','-','2','47918'],['2022-11','9710243','BPJS JP EE','-','2','47918'],['2022-11','9710203','BPJS JP EE','-','2','47918'],['2022-11','9713689','BPJS JP EE','-','2','47918'],['2022-11','9710205','BPJS JP EE','-','2','47918'],['2022-11','9713691','BPJS JP EE','-','2','47918'],['2022-11','9710241','BPJS JP EE','-','2','47918'],['2022-11','9710314','BPJS JP EE','-','2','47918'],['2022-11','9710242','BPJS JP EE','-','2','47918'],['2022-11','9712357','BPJS JP EE','-','2','47918'],['2022-11','9710294','BPJS JP EE','-','2','47918'],['2022-11','9710301','BPJS JP EE','-','2','47918'],['2022-11','9710295','BPJS JP EE','-','2','47918'],['2022-11','9710186','BPJS JP EE','-','2','47918'],['2022-11','9710298','BPJS JP EE','-','2','47918'],['2022-11','9710293','BPJS JP EE','-','2','47918'],['2022-11','9710302','BPJS JP EE','-','2','47918'],['2022-11','9710300','BPJS JP EE','-','2','47918'],['2022-11','9710297','BPJS JP EE','-','2','47918'],['2022-11','9710291','BPJS JP EE','-','2','47918'],['2022-11','9710290','BPJS JP EE','-','2','47918'],['2022-11','9710204','BPJS JP EE','-','2','47918'],['2022-11','9710305','BPJS JP EE','-','2','47918'],['2022-11','9710299','BPJS JP EE','-','2','47918'],['2022-11','9710303','BPJS JP EE','-','2','47918'],['2022-11','9710304','BPJS JP EE','-','2','47918'],['2022-11','9710187','BPJS JHT EE','-','3','95837'],['2022-11','9713287','BPJS JHT EE','-','3','95837'],['2022-11','9713208','BPJS JHT EE','-','3','95837'],['2022-11','9710189','BPJS JHT EE','-','3','95837'],['2022-11','9714136','BPJS JHT EE','-','3','95837'],['2022-11','9713209','BPJS JHT EE','-','3','95837'],['2022-11','9710191','BPJS JHT EE','-','3','95837'],['2022-11','9710195','BPJS JHT EE','-','3','95837'],['2022-11','9710193','BPJS JHT EE','-','3','95837'],['2022-11','9710192','BPJS JHT EE','-','3','95837'],['2022-11','9713690','BPJS JHT EE','-','3','95837'],['2022-11','9713688','BPJS JHT EE','-','3','95837'],['2022-11','9712164','BPJS JHT EE','-','3','95837'],['2022-11','9710202','BPJS JHT EE','-','3','95837'],['2022-11','9710201','BPJS JHT EE','-','3','95837'],['2022-11','9710200','BPJS JHT EE','-','3','95837'],['2022-11','9713687','BPJS JHT EE','-','3','95837'],['2022-11','9713841','BPJS JHT EE','-','3','95837'],['2022-11','9710243','BPJS JHT EE','-','3','95837'],['2022-11','9710203','BPJS JHT EE','-','3','95837'],['2022-11','9713689','BPJS JHT EE','-','3','95837'],['2022-11','9710205','BPJS JHT EE','-','3','95837'],['2022-11','9713691','BPJS JHT EE','-','3','95837'],['2022-11','9710241','BPJS JHT EE','-','3','95837'],['2022-11','9710314','BPJS JHT EE','-','3','95837'],['2022-11','9710242','BPJS JHT EE','-','3','95837'],['2022-11','9712357','BPJS JHT EE','-','3','95837'],['2022-11','9710294','BPJS JHT EE','-','3','95837'],['2022-11','9710301','BPJS JHT EE','-','3','95837'],['2022-11','9710295','BPJS JHT EE','-','3','95837'],['2022-11','9710186','BPJS JHT EE','-','3','95837'],['2022-11','9710298','BPJS JHT EE','-','3','95837'],['2022-11','9710293','BPJS JHT EE','-','3','95837'],['2022-11','9710302','BPJS JHT EE','-','3','95837'],['2022-11','9710300','BPJS JHT EE','-','3','95837'],['2022-11','9710297','BPJS JHT EE','-','3','95837'],['2022-11','9710291','BPJS JHT EE','-','3','95837'],['2022-11','9710290','BPJS JHT EE','-','3','95837'],['2022-11','9710204','BPJS JHT EE','-','3','95837'],['2022-11','9710305','BPJS JHT EE','-','3','95837'],['2022-11','9710299','BPJS JHT EE','-','3','95837'],['2022-11','9710303','BPJS JHT EE','-','3','95837'],['2022-11','9710304','BPJS JHT EE','-','3','95837'],['2022-11','9710187','BPJS JKN ER','#','1','191674'],['2022-11','9713287','BPJS JKN ER','#','1','191674'],['2022-11','9713208','BPJS JKN ER','#','1','191674'],['2022-11','9710189','BPJS JKN ER','#','1','191674'],['2022-11','9714136','BPJS JKN ER','#','1','191674'],['2022-11','9713209','BPJS JKN ER','#','1','191674'],['2022-11','9710191','BPJS JKN ER','#','1','191674'],['2022-11','9710195','BPJS JKN ER','#','1','191674'],['2022-11','9710193','BPJS JKN ER','#','1','191674'],['2022-11','9710192','BPJS JKN ER','#','1','191674'],['2022-11','9713690','BPJS JKN ER','#','1','191674'],['2022-11','9713688','BPJS JKN ER','#','1','191674'],['2022-11','9712164','BPJS JKN ER','#','1','191674'],['2022-11','9710202','BPJS JKN ER','#','1','191674'],['2022-11','9710201','BPJS JKN ER','#','1','191674'],['2022-11','9710200','BPJS JKN ER','#','1','191674'],['2022-11','9713687','BPJS JKN ER','#','1','191674'],['2022-11','9713841','BPJS JKN ER','#','1','191674'],['2022-11','9710243','BPJS JKN ER','#','1','191674'],['2022-11','9710203','BPJS JKN ER','#','1','191674'],['2022-11','9713689','BPJS JKN ER','#','1','191674'],['2022-11','9710205','BPJS JKN ER','#','1','191674'],['2022-11','9713691','BPJS JKN ER','#','1','191674'],['2022-11','9710241','BPJS JKN ER','#','1','191674'],['2022-11','9710314','BPJS JKN ER','#','1','191674'],['2022-11','9710242','BPJS JKN ER','#','1','191674'],['2022-11','9712357','BPJS JKN ER','#','1','191674'],['2022-11','9710294','BPJS JKN ER','#','1','191674'],['2022-11','9710301','BPJS JKN ER','#','1','191674'],['2022-11','9710295','BPJS JKN ER','#','1','191674'],['2022-11','9710186','BPJS JKN ER','#','1','191674'],['2022-11','9710298','BPJS JKN ER','#','1','191674'],['2022-11','9710293','BPJS JKN ER','#','1','191674'],['2022-11','9710302','BPJS JKN ER','#','1','191674'],['2022-11','9710300','BPJS JKN ER','#','1','191674'],['2022-11','9710297','BPJS JKN ER','#','1','191674'],['2022-11','9710291','BPJS JKN ER','#','1','191674'],['2022-11','9710290','BPJS JKN ER','#','1','191674'],['2022-11','9710204','BPJS JKN ER','#','1','191674'],['2022-11','9710305','BPJS JKN ER','#','1','191674'],['2022-11','9710299','BPJS JKN ER','#','1','191674'],['2022-11','9710303','BPJS JKN ER','#','1','191674'],['2022-11','9710304','BPJS JKN ER','#','1','191674'],['2022-11','9710187','BPJS JP ER','#','2','95837'],['2022-11','9713287','BPJS JP ER','#','2','95837'],['2022-11','9713208','BPJS JP ER','#','2','95837'],['2022-11','9710189','BPJS JP ER','#','2','95837'],['2022-11','9714136','BPJS JP ER','#','2','95837'],['2022-11','9713209','BPJS JP ER','#','2','95837'],['2022-11','9710191','BPJS JP ER','#','2','95837'],['2022-11','9710195','BPJS JP ER','#','2','95837'],['2022-11','9710193','BPJS JP ER','#','2','95837'],['2022-11','9710192','BPJS JP ER','#','2','95837'],['2022-11','9713690','BPJS JP ER','#','2','95837'],['2022-11','9713688','BPJS JP ER','#','2','95837'],['2022-11','9712164','BPJS JP ER','#','2','95837'],['2022-11','9710202','BPJS JP ER','#','2','95837'],['2022-11','9710201','BPJS JP ER','#','2','95837'],['2022-11','9710200','BPJS JP ER','#','2','95837'],['2022-11','9713687','BPJS JP ER','#','2','95837'],['2022-11','9713841','BPJS JP ER','#','2','95837'],['2022-11','9710243','BPJS JP ER','#','2','95837'],['2022-11','9710203','BPJS JP ER','#','2','95837'],['2022-11','9713689','BPJS JP ER','#','2','95837'],['2022-11','9710205','BPJS JP ER','#','2','95837'],['2022-11','9713691','BPJS JP ER','#','2','95837'],['2022-11','9710241','BPJS JP ER','#','2','95837'],['2022-11','9710314','BPJS JP ER','#','2','95837'],['2022-11','9710242','BPJS JP ER','#','2','95837'],['2022-11','9712357','BPJS JP ER','#','2','95837'],['2022-11','9710294','BPJS JP ER','#','2','95837'],['2022-11','9710301','BPJS JP ER','#','2','95837'],['2022-11','9710295','BPJS JP ER','#','2','95837'],['2022-11','9710186','BPJS JP ER','#','2','95837'],['2022-11','9710298','BPJS JP ER','#','2','95837'],['2022-11','9710293','BPJS JP ER','#','2','95837'],['2022-11','9710302','BPJS JP ER','#','2','95837'],['2022-11','9710300','BPJS JP ER','#','2','95837'],['2022-11','9710297','BPJS JP ER','#','2','95837'],['2022-11','9710291','BPJS JP ER','#','2','95837'],['2022-11','9710290','BPJS JP ER','#','2','95837'],['2022-11','9710204','BPJS JP ER','#','2','95837'],['2022-11','9710305','BPJS JP ER','#','2','95837'],['2022-11','9710299','BPJS JP ER','#','2','95837'],['2022-11','9710303','BPJS JP ER','#','2','95837'],['2022-11','9710304','BPJS JP ER','#','2','95837'],['2022-11','9710187','BPJS JHT ER','#','3','177298'],['2022-11','9713287','BPJS JHT ER','#','3','177298'],['2022-11','9713208','BPJS JHT ER','#','3','177298'],['2022-11','9710189','BPJS JHT ER','#','3','177298'],['2022-11','9714136','BPJS JHT ER','#','3','177298'],['2022-11','9713209','BPJS JHT ER','#','3','177298'],['2022-11','9710191','BPJS JHT ER','#','3','177298'],['2022-11','9710195','BPJS JHT ER','#','3','177298'],['2022-11','9710193','BPJS JHT ER','#','3','177298'],['2022-11','9710192','BPJS JHT ER','#','3','177298'],['2022-11','9713690','BPJS JHT ER','#','3','177298'],['2022-11','9713688','BPJS JHT ER','#','3','177298'],['2022-11','9712164','BPJS JHT ER','#','3','177298'],['2022-11','9710202','BPJS JHT ER','#','3','177298'],['2022-11','9710201','BPJS JHT ER','#','3','177298'],['2022-11','9710200','BPJS JHT ER','#','3','177298'],['2022-11','9713687','BPJS JHT ER','#','3','177298'],['2022-11','9713841','BPJS JHT ER','#','3','177298'],['2022-11','9710243','BPJS JHT ER','#','3','177298'],['2022-11','9710203','BPJS JHT ER','#','3','177298'],['2022-11','9713689','BPJS JHT ER','#','3','177298'],['2022-11','9710205','BPJS JHT ER','#','3','177298'],['2022-11','9713691','BPJS JHT ER','#','3','177298'],['2022-11','9710241','BPJS JHT ER','#','3','177298'],['2022-11','9710314','BPJS JHT ER','#','3','177298'],['2022-11','9710242','BPJS JHT ER','#','3','177298'],['2022-11','9712357','BPJS JHT ER','#','3','177298'],['2022-11','9710294','BPJS JHT ER','#','3','177298'],['2022-11','9710301','BPJS JHT ER','#','3','177298'],['2022-11','9710295','BPJS JHT ER','#','3','177298'],['2022-11','9710186','BPJS JHT ER','#','3','177298'],['2022-11','9710298','BPJS JHT ER','#','3','177298'],['2022-11','9710293','BPJS JHT ER','#','3','177298'],['2022-11','9710302','BPJS JHT ER','#','3','177298'],['2022-11','9710300','BPJS JHT ER','#','3','177298'],['2022-11','9710297','BPJS JHT ER','#','3','177298'],['2022-11','9710291','BPJS JHT ER','#','3','177298'],['2022-11','9710290','BPJS JHT ER','#','3','177298'],['2022-11','9710204','BPJS JHT ER','#','3','177298'],['2022-11','9710305','BPJS JHT ER','#','3','177298'],['2022-11','9710299','BPJS JHT ER','#','3','177298'],['2022-11','9710303','BPJS JHT ER','#','3','177298']];
        $arr_det_2 = [['2022-11','9710304','BPJS JHT ER','#','3','177298'],['2022-11','9710187','BPJS JKM ER','#','4','14376'],['2022-11','9713287','BPJS JKM ER','#','4','14376'],['2022-11','9713208','BPJS JKM ER','#','4','14376'],['2022-11','9710189','BPJS JKM ER','#','4','14376'],['2022-11','9714136','BPJS JKM ER','#','4','14376'],['2022-11','9713209','BPJS JKM ER','#','4','14376'],['2022-11','9710191','BPJS JKM ER','#','4','14376'],['2022-11','9710195','BPJS JKM ER','#','4','14376'],['2022-11','9710193','BPJS JKM ER','#','4','14376'],['2022-11','9710192','BPJS JKM ER','#','4','14376'],['2022-11','9713690','BPJS JKM ER','#','4','14376'],['2022-11','9713688','BPJS JKM ER','#','4','14376'],['2022-11','9712164','BPJS JKM ER','#','4','14376'],['2022-11','9710202','BPJS JKM ER','#','4','14376'],['2022-11','9710201','BPJS JKM ER','#','4','14376'],['2022-11','9710200','BPJS JKM ER','#','4','14376'],['2022-11','9713687','BPJS JKM ER','#','4','14376'],['2022-11','9713841','BPJS JKM ER','#','4','14376'],['2022-11','9710243','BPJS JKM ER','#','4','14376'],['2022-11','9710203','BPJS JKM ER','#','4','14376'],['2022-11','9713689','BPJS JKM ER','#','4','14376'],['2022-11','9710205','BPJS JKM ER','#','4','14376'],['2022-11','9713691','BPJS JKM ER','#','4','14376'],['2022-11','9710241','BPJS JKM ER','#','4','14376'],['2022-11','9710314','BPJS JKM ER','#','4','14376'],['2022-11','9710242','BPJS JKM ER','#','4','14376'],['2022-11','9712357','BPJS JKM ER','#','4','14376'],['2022-11','9710294','BPJS JKM ER','#','4','14376'],['2022-11','9710301','BPJS JKM ER','#','4','14376'],['2022-11','9710295','BPJS JKM ER','#','4','14376'],['2022-11','9710186','BPJS JKM ER','#','4','14376'],['2022-11','9710298','BPJS JKM ER','#','4','14376'],['2022-11','9710293','BPJS JKM ER','#','4','14376'],['2022-11','9710302','BPJS JKM ER','#','4','14376'],['2022-11','9710300','BPJS JKM ER','#','4','14376'],['2022-11','9710297','BPJS JKM ER','#','4','14376'],['2022-11','9710291','BPJS JKM ER','#','4','14376'],['2022-11','9710290','BPJS JKM ER','#','4','14376'],['2022-11','9710204','BPJS JKM ER','#','4','14376'],['2022-11','9710305','BPJS JKM ER','#','4','14376'],['2022-11','9710299','BPJS JKM ER','#','4','14376'],['2022-11','9710303','BPJS JKM ER','#','4','14376'],['2022-11','9710304','BPJS JKM ER','#','4','14376'],['2022-11','9710187','BPJS JKK ER','#','5','11500'],['2022-11','9713287','BPJS JKK ER','#','5','11500'],['2022-11','9713208','BPJS JKK ER','#','5','11500'],['2022-11','9710189','BPJS JKK ER','#','5','11500'],['2022-11','9714136','BPJS JKK ER','#','5','11500'],['2022-11','9713209','BPJS JKK ER','#','5','11500'],['2022-11','9710191','BPJS JKK ER','#','5','11500'],['2022-11','9710195','BPJS JKK ER','#','5','11500'],['2022-11','9710193','BPJS JKK ER','#','5','11500'],['2022-11','9710192','BPJS JKK ER','#','5','11500'],['2022-11','9713690','BPJS JKK ER','#','5','11500'],['2022-11','9713688','BPJS JKK ER','#','5','11500'],['2022-11','9712164','BPJS JKK ER','#','5','11500'],['2022-11','9710202','BPJS JKK ER','#','5','11500'],['2022-11','9710201','BPJS JKK ER','#','5','11500'],['2022-11','9710200','BPJS JKK ER','#','5','11500'],['2022-11','9713687','BPJS JKK ER','#','5','11500'],['2022-11','9713841','BPJS JKK ER','#','5','11500'],['2022-11','9710243','BPJS JKK ER','#','5','11500'],['2022-11','9710203','BPJS JKK ER','#','5','11500'],['2022-11','9713689','BPJS JKK ER','#','5','11500'],['2022-11','9710205','BPJS JKK ER','#','5','11500'],['2022-11','9713691','BPJS JKK ER','#','5','11500'],['2022-11','9710241','BPJS JKK ER','#','5','11500'],['2022-11','9710314','BPJS JKK ER','#','5','11500'],['2022-11','9710242','BPJS JKK ER','#','5','11500'],['2022-11','9712357','BPJS JKK ER','#','5','11500'],['2022-11','9710294','BPJS JKK ER','#','5','11500'],['2022-11','9710301','BPJS JKK ER','#','5','11500'],['2022-11','9710295','BPJS JKK ER','#','5','11500'],['2022-11','9710186','BPJS JKK ER','#','5','11500'],['2022-11','9710298','BPJS JKK ER','#','5','11500'],['2022-11','9710293','BPJS JKK ER','#','5','11500'],['2022-11','9710302','BPJS JKK ER','#','5','11500'],['2022-11','9710300','BPJS JKK ER','#','5','11500'],['2022-11','9710297','BPJS JKK ER','#','5','11500'],['2022-11','9710291','BPJS JKK ER','#','5','11500'],['2022-11','9710290','BPJS JKK ER','#','5','11500'],['2022-11','9710204','BPJS JKK ER','#','5','11500'],['2022-11','9710305','BPJS JKK ER','#','5','11500'],['2022-11','9710299','BPJS JKK ER','#','5','11500'],['2022-11','9710303','BPJS JKK ER','#','5','11500'],['2022-11','9710304','BPJS JKK ER','#','5','11500'],['2022-12','9710187','Gaji Pokok','+','1','4791843'],['2022-12','9713287','Gaji Pokok','+','1','4791843'],['2022-12','9713208','Gaji Pokok','+','1','4791843'],['2022-12','9710189','Gaji Pokok','+','1','4791843'],['2022-12','9714136','Gaji Pokok','+','1','4791843'],['2022-12','9713209','Gaji Pokok','+','1','4791843'],['2022-12','9710191','Gaji Pokok','+','1','4791843'],['2022-12','9710195','Gaji Pokok','+','1','4791843'],['2022-12','9710193','Gaji Pokok','+','1','4791843'],['2022-12','9710192','Gaji Pokok','+','1','4791843'],['2022-12','9713690','Gaji Pokok','+','1','4791843'],['2022-12','9713688','Gaji Pokok','+','1','4791843'],['2022-12','9712164','Gaji Pokok','+','1','4791843'],['2022-12','9710202','Gaji Pokok','+','1','4791843'],['2022-12','9710201','Gaji Pokok','+','1','4791843'],['2022-12','9710200','Gaji Pokok','+','1','4791843'],['2022-12','9713687','Gaji Pokok','+','1','4791843'],['2022-12','9713841','Gaji Pokok','+','1','4791843'],['2022-12','9710243','Gaji Pokok','+','1','4791843'],['2022-12','9710203','Gaji Pokok','+','1','4791843'],['2022-12','9713689','Gaji Pokok','+','1','4791843'],['2022-12','9710205','Gaji Pokok','+','1','4791843'],['2022-12','9713691','Gaji Pokok','+','1','4791843'],['2022-12','9710241','Gaji Pokok','+','1','4791843'],['2022-12','9710314','Gaji Pokok','+','1','4791843'],['2022-12','9710242','Gaji Pokok','+','1','4791843'],['2022-12','9712357','Gaji Pokok','+','1','4791843'],['2022-12','9710294','Gaji Pokok','+','1','4791843'],['2022-12','9710301','Gaji Pokok','+','1','4791843'],['2022-12','9710295','Gaji Pokok','+','1','4791843'],['2022-12','9710186','Gaji Pokok','+','1','4791843'],['2022-12','9710298','Gaji Pokok','+','1','4791843'],['2022-12','9710293','Gaji Pokok','+','1','4791843'],['2022-12','9710302','Gaji Pokok','+','1','4791843'],['2022-12','9710300','Gaji Pokok','+','1','4791843'],['2022-12','9710297','Gaji Pokok','+','1','4791843'],['2022-12','9710291','Gaji Pokok','+','1','4791843'],['2022-12','9710290','Gaji Pokok','+','1','4791843'],['2022-12','9710204','Gaji Pokok','+','1','4791843'],['2022-12','9710305','Gaji Pokok','+','1','4791843'],['2022-12','9710299','Gaji Pokok','+','1','4791843'],['2022-12','9710303','Gaji Pokok','+','1','4791843'],['2022-12','9710304','Gaji Pokok','+','1','4791843'],['2022-12','9710187','BPJS JKN EE','-','1','47918'],['2022-12','9713287','BPJS JKN EE','-','1','47918'],['2022-12','9713208','BPJS JKN EE','-','1','47918'],['2022-12','9710189','BPJS JKN EE','-','1','47918'],['2022-12','9714136','BPJS JKN EE','-','1','47918'],['2022-12','9713209','BPJS JKN EE','-','1','47918'],['2022-12','9710191','BPJS JKN EE','-','1','47918'],['2022-12','9710195','BPJS JKN EE','-','1','47918'],['2022-12','9710193','BPJS JKN EE','-','1','47918'],['2022-12','9710192','BPJS JKN EE','-','1','47918'],['2022-12','9713690','BPJS JKN EE','-','1','47918'],['2022-12','9713688','BPJS JKN EE','-','1','47918'],['2022-12','9712164','BPJS JKN EE','-','1','47918'],['2022-12','9710202','BPJS JKN EE','-','1','47918'],['2022-12','9710201','BPJS JKN EE','-','1','47918'],['2022-12','9710200','BPJS JKN EE','-','1','47918'],['2022-12','9713687','BPJS JKN EE','-','1','47918'],['2022-12','9713841','BPJS JKN EE','-','1','47918'],['2022-12','9710243','BPJS JKN EE','-','1','47918'],['2022-12','9710203','BPJS JKN EE','-','1','47918'],['2022-12','9713689','BPJS JKN EE','-','1','47918'],['2022-12','9710205','BPJS JKN EE','-','1','47918'],['2022-12','9713691','BPJS JKN EE','-','1','47918'],['2022-12','9710241','BPJS JKN EE','-','1','47918'],['2022-12','9710314','BPJS JKN EE','-','1','47918'],['2022-12','9710242','BPJS JKN EE','-','1','47918'],['2022-12','9712357','BPJS JKN EE','-','1','47918'],['2022-12','9710294','BPJS JKN EE','-','1','47918'],['2022-12','9710301','BPJS JKN EE','-','1','47918'],['2022-12','9710295','BPJS JKN EE','-','1','47918'],['2022-12','9710186','BPJS JKN EE','-','1','47918'],['2022-12','9710298','BPJS JKN EE','-','1','47918'],['2022-12','9710293','BPJS JKN EE','-','1','47918'],['2022-12','9710302','BPJS JKN EE','-','1','47918'],['2022-12','9710300','BPJS JKN EE','-','1','47918'],['2022-12','9710297','BPJS JKN EE','-','1','47918'],['2022-12','9710291','BPJS JKN EE','-','1','47918'],['2022-12','9710290','BPJS JKN EE','-','1','47918'],['2022-12','9710204','BPJS JKN EE','-','1','47918'],['2022-12','9710305','BPJS JKN EE','-','1','47918'],['2022-12','9710299','BPJS JKN EE','-','1','47918'],['2022-12','9710303','BPJS JKN EE','-','1','47918'],['2022-12','9710304','BPJS JKN EE','-','1','47918'],['2022-12','9710187','BPJS JP EE','-','2','47918'],['2022-12','9713287','BPJS JP EE','-','2','47918'],['2022-12','9713208','BPJS JP EE','-','2','47918'],['2022-12','9710189','BPJS JP EE','-','2','47918'],['2022-12','9714136','BPJS JP EE','-','2','47918'],['2022-12','9713209','BPJS JP EE','-','2','47918'],['2022-12','9710191','BPJS JP EE','-','2','47918'],['2022-12','9710195','BPJS JP EE','-','2','47918'],['2022-12','9710193','BPJS JP EE','-','2','47918'],['2022-12','9710192','BPJS JP EE','-','2','47918'],['2022-12','9713690','BPJS JP EE','-','2','47918'],['2022-12','9713688','BPJS JP EE','-','2','47918'],['2022-12','9712164','BPJS JP EE','-','2','47918'],['2022-12','9710202','BPJS JP EE','-','2','47918'],['2022-12','9710201','BPJS JP EE','-','2','47918'],['2022-12','9710200','BPJS JP EE','-','2','47918'],['2022-12','9713687','BPJS JP EE','-','2','47918'],['2022-12','9713841','BPJS JP EE','-','2','47918'],['2022-12','9710243','BPJS JP EE','-','2','47918'],['2022-12','9710203','BPJS JP EE','-','2','47918'],['2022-12','9713689','BPJS JP EE','-','2','47918'],['2022-12','9710205','BPJS JP EE','-','2','47918'],['2022-12','9713691','BPJS JP EE','-','2','47918'],['2022-12','9710241','BPJS JP EE','-','2','47918'],['2022-12','9710314','BPJS JP EE','-','2','47918'],['2022-12','9710242','BPJS JP EE','-','2','47918'],['2022-12','9712357','BPJS JP EE','-','2','47918'],['2022-12','9710294','BPJS JP EE','-','2','47918'],['2022-12','9710301','BPJS JP EE','-','2','47918'],['2022-12','9710295','BPJS JP EE','-','2','47918'],['2022-12','9710186','BPJS JP EE','-','2','47918'],['2022-12','9710298','BPJS JP EE','-','2','47918'],['2022-12','9710293','BPJS JP EE','-','2','47918'],['2022-12','9710302','BPJS JP EE','-','2','47918'],['2022-12','9710300','BPJS JP EE','-','2','47918'],['2022-12','9710297','BPJS JP EE','-','2','47918'],['2022-12','9710291','BPJS JP EE','-','2','47918'],['2022-12','9710290','BPJS JP EE','-','2','47918'],['2022-12','9710204','BPJS JP EE','-','2','47918'],['2022-12','9710305','BPJS JP EE','-','2','47918'],['2022-12','9710299','BPJS JP EE','-','2','47918'],['2022-12','9710303','BPJS JP EE','-','2','47918'],['2022-12','9710304','BPJS JP EE','-','2','47918'],['2022-12','9710187','BPJS JHT EE','-','3','95837'],['2022-12','9713287','BPJS JHT EE','-','3','95837'],['2022-12','9713208','BPJS JHT EE','-','3','95837'],['2022-12','9710189','BPJS JHT EE','-','3','95837'],['2022-12','9714136','BPJS JHT EE','-','3','95837'],['2022-12','9713209','BPJS JHT EE','-','3','95837'],['2022-12','9710191','BPJS JHT EE','-','3','95837'],['2022-12','9710195','BPJS JHT EE','-','3','95837'],['2022-12','9710193','BPJS JHT EE','-','3','95837'],['2022-12','9710192','BPJS JHT EE','-','3','95837'],['2022-12','9713690','BPJS JHT EE','-','3','95837'],['2022-12','9713688','BPJS JHT EE','-','3','95837'],['2022-12','9712164','BPJS JHT EE','-','3','95837'],['2022-12','9710202','BPJS JHT EE','-','3','95837'],['2022-12','9710201','BPJS JHT EE','-','3','95837'],['2022-12','9710200','BPJS JHT EE','-','3','95837'],['2022-12','9713687','BPJS JHT EE','-','3','95837'],['2022-12','9713841','BPJS JHT EE','-','3','95837'],['2022-12','9710243','BPJS JHT EE','-','3','95837'],['2022-12','9710203','BPJS JHT EE','-','3','95837'],['2022-12','9713689','BPJS JHT EE','-','3','95837'],['2022-12','9710205','BPJS JHT EE','-','3','95837'],['2022-12','9713691','BPJS JHT EE','-','3','95837'],['2022-12','9710241','BPJS JHT EE','-','3','95837'],['2022-12','9710314','BPJS JHT EE','-','3','95837'],['2022-12','9710242','BPJS JHT EE','-','3','95837'],['2022-12','9712357','BPJS JHT EE','-','3','95837'],['2022-12','9710294','BPJS JHT EE','-','3','95837'],['2022-12','9710301','BPJS JHT EE','-','3','95837'],['2022-12','9710295','BPJS JHT EE','-','3','95837'],['2022-12','9710186','BPJS JHT EE','-','3','95837'],['2022-12','9710298','BPJS JHT EE','-','3','95837'],['2022-12','9710293','BPJS JHT EE','-','3','95837'],['2022-12','9710302','BPJS JHT EE','-','3','95837'],['2022-12','9710300','BPJS JHT EE','-','3','95837'],['2022-12','9710297','BPJS JHT EE','-','3','95837'],['2022-12','9710291','BPJS JHT EE','-','3','95837'],['2022-12','9710290','BPJS JHT EE','-','3','95837'],['2022-12','9710204','BPJS JHT EE','-','3','95837'],['2022-12','9710305','BPJS JHT EE','-','3','95837'],['2022-12','9710299','BPJS JHT EE','-','3','95837'],['2022-12','9710303','BPJS JHT EE','-','3','95837'],['2022-12','9710304','BPJS JHT EE','-','3','95837'],['2022-12','9710187','BPJS JKN ER','#','1','191674'],['2022-12','9713287','BPJS JKN ER','#','1','191674'],['2022-12','9713208','BPJS JKN ER','#','1','191674'],['2022-12','9710189','BPJS JKN ER','#','1','191674'],['2022-12','9714136','BPJS JKN ER','#','1','191674'],['2022-12','9713209','BPJS JKN ER','#','1','191674'],['2022-12','9710191','BPJS JKN ER','#','1','191674'],['2022-12','9710195','BPJS JKN ER','#','1','191674'],['2022-12','9710193','BPJS JKN ER','#','1','191674'],['2022-12','9710192','BPJS JKN ER','#','1','191674'],['2022-12','9713690','BPJS JKN ER','#','1','191674'],['2022-12','9713688','BPJS JKN ER','#','1','191674'],['2022-12','9712164','BPJS JKN ER','#','1','191674'],['2022-12','9710202','BPJS JKN ER','#','1','191674'],['2022-12','9710201','BPJS JKN ER','#','1','191674'],['2022-12','9710200','BPJS JKN ER','#','1','191674'],['2022-12','9713687','BPJS JKN ER','#','1','191674'],['2022-12','9713841','BPJS JKN ER','#','1','191674'],['2022-12','9710243','BPJS JKN ER','#','1','191674'],['2022-12','9710203','BPJS JKN ER','#','1','191674'],['2022-12','9713689','BPJS JKN ER','#','1','191674'],['2022-12','9710205','BPJS JKN ER','#','1','191674'],['2022-12','9713691','BPJS JKN ER','#','1','191674'],['2022-12','9710241','BPJS JKN ER','#','1','191674'],['2022-12','9710314','BPJS JKN ER','#','1','191674'],['2022-12','9710242','BPJS JKN ER','#','1','191674'],['2022-12','9712357','BPJS JKN ER','#','1','191674'],['2022-12','9710294','BPJS JKN ER','#','1','191674'],['2022-12','9710301','BPJS JKN ER','#','1','191674'],['2022-12','9710295','BPJS JKN ER','#','1','191674'],['2022-12','9710186','BPJS JKN ER','#','1','191674'],['2022-12','9710298','BPJS JKN ER','#','1','191674'],['2022-12','9710293','BPJS JKN ER','#','1','191674'],['2022-12','9710302','BPJS JKN ER','#','1','191674'],['2022-12','9710300','BPJS JKN ER','#','1','191674'],['2022-12','9710297','BPJS JKN ER','#','1','191674'],['2022-12','9710291','BPJS JKN ER','#','1','191674'],['2022-12','9710290','BPJS JKN ER','#','1','191674'],['2022-12','9710204','BPJS JKN ER','#','1','191674'],['2022-12','9710305','BPJS JKN ER','#','1','191674'],['2022-12','9710299','BPJS JKN ER','#','1','191674']];
        $arr_det_3 = [['2022-12','9710303','BPJS JKN ER','#','1','191674'],['2022-12','9710304','BPJS JKN ER','#','1','191674'],['2022-12','9710187','BPJS JP ER','#','2','95837'],['2022-12','9713287','BPJS JP ER','#','2','95837'],['2022-12','9713208','BPJS JP ER','#','2','95837'],['2022-12','9710189','BPJS JP ER','#','2','95837'],['2022-12','9714136','BPJS JP ER','#','2','95837'],['2022-12','9713209','BPJS JP ER','#','2','95837'],['2022-12','9710191','BPJS JP ER','#','2','95837'],['2022-12','9710195','BPJS JP ER','#','2','95837'],['2022-12','9710193','BPJS JP ER','#','2','95837'],['2022-12','9710192','BPJS JP ER','#','2','95837'],['2022-12','9713690','BPJS JP ER','#','2','95837'],['2022-12','9713688','BPJS JP ER','#','2','95837'],['2022-12','9712164','BPJS JP ER','#','2','95837'],['2022-12','9710202','BPJS JP ER','#','2','95837'],['2022-12','9710201','BPJS JP ER','#','2','95837'],['2022-12','9710200','BPJS JP ER','#','2','95837'],['2022-12','9713687','BPJS JP ER','#','2','95837'],['2022-12','9713841','BPJS JP ER','#','2','95837'],['2022-12','9710243','BPJS JP ER','#','2','95837'],['2022-12','9710203','BPJS JP ER','#','2','95837'],['2022-12','9713689','BPJS JP ER','#','2','95837'],['2022-12','9710205','BPJS JP ER','#','2','95837'],['2022-12','9713691','BPJS JP ER','#','2','95837'],['2022-12','9710241','BPJS JP ER','#','2','95837'],['2022-12','9710314','BPJS JP ER','#','2','95837'],['2022-12','9710242','BPJS JP ER','#','2','95837'],['2022-12','9712357','BPJS JP ER','#','2','95837'],['2022-12','9710294','BPJS JP ER','#','2','95837'],['2022-12','9710301','BPJS JP ER','#','2','95837'],['2022-12','9710295','BPJS JP ER','#','2','95837'],['2022-12','9710186','BPJS JP ER','#','2','95837'],['2022-12','9710298','BPJS JP ER','#','2','95837'],['2022-12','9710293','BPJS JP ER','#','2','95837'],['2022-12','9710302','BPJS JP ER','#','2','95837'],['2022-12','9710300','BPJS JP ER','#','2','95837'],['2022-12','9710297','BPJS JP ER','#','2','95837'],['2022-12','9710291','BPJS JP ER','#','2','95837'],['2022-12','9710290','BPJS JP ER','#','2','95837'],['2022-12','9710204','BPJS JP ER','#','2','95837'],['2022-12','9710305','BPJS JP ER','#','2','95837'],['2022-12','9710299','BPJS JP ER','#','2','95837'],['2022-12','9710303','BPJS JP ER','#','2','95837'],['2022-12','9710304','BPJS JP ER','#','2','95837'],['2022-12','9710187','BPJS JHT ER','#','3','177298'],['2022-12','9713287','BPJS JHT ER','#','3','177298'],['2022-12','9713208','BPJS JHT ER','#','3','177298'],['2022-12','9710189','BPJS JHT ER','#','3','177298'],['2022-12','9714136','BPJS JHT ER','#','3','177298'],['2022-12','9713209','BPJS JHT ER','#','3','177298'],['2022-12','9710191','BPJS JHT ER','#','3','177298'],['2022-12','9710195','BPJS JHT ER','#','3','177298'],['2022-12','9710193','BPJS JHT ER','#','3','177298'],['2022-12','9710192','BPJS JHT ER','#','3','177298'],['2022-12','9713690','BPJS JHT ER','#','3','177298'],['2022-12','9713688','BPJS JHT ER','#','3','177298'],['2022-12','9712164','BPJS JHT ER','#','3','177298'],['2022-12','9710202','BPJS JHT ER','#','3','177298'],['2022-12','9710201','BPJS JHT ER','#','3','177298'],['2022-12','9710200','BPJS JHT ER','#','3','177298'],['2022-12','9713687','BPJS JHT ER','#','3','177298'],['2022-12','9713841','BPJS JHT ER','#','3','177298'],['2022-12','9710243','BPJS JHT ER','#','3','177298'],['2022-12','9710203','BPJS JHT ER','#','3','177298'],['2022-12','9713689','BPJS JHT ER','#','3','177298'],['2022-12','9710205','BPJS JHT ER','#','3','177298'],['2022-12','9713691','BPJS JHT ER','#','3','177298'],['2022-12','9710241','BPJS JHT ER','#','3','177298'],['2022-12','9710314','BPJS JHT ER','#','3','177298'],['2022-12','9710242','BPJS JHT ER','#','3','177298'],['2022-12','9712357','BPJS JHT ER','#','3','177298'],['2022-12','9710294','BPJS JHT ER','#','3','177298'],['2022-12','9710301','BPJS JHT ER','#','3','177298'],['2022-12','9710295','BPJS JHT ER','#','3','177298'],['2022-12','9710186','BPJS JHT ER','#','3','177298'],['2022-12','9710298','BPJS JHT ER','#','3','177298'],['2022-12','9710293','BPJS JHT ER','#','3','177298'],['2022-12','9710302','BPJS JHT ER','#','3','177298'],['2022-12','9710300','BPJS JHT ER','#','3','177298'],['2022-12','9710297','BPJS JHT ER','#','3','177298'],['2022-12','9710291','BPJS JHT ER','#','3','177298'],['2022-12','9710290','BPJS JHT ER','#','3','177298'],['2022-12','9710204','BPJS JHT ER','#','3','177298'],['2022-12','9710305','BPJS JHT ER','#','3','177298'],['2022-12','9710299','BPJS JHT ER','#','3','177298'],['2022-12','9710303','BPJS JHT ER','#','3','177298'],['2022-12','9710304','BPJS JHT ER','#','3','177298'],['2022-12','9710187','BPJS JKM ER','#','4','14376'],['2022-12','9713287','BPJS JKM ER','#','4','14376'],['2022-12','9713208','BPJS JKM ER','#','4','14376'],['2022-12','9710189','BPJS JKM ER','#','4','14376'],['2022-12','9714136','BPJS JKM ER','#','4','14376'],['2022-12','9713209','BPJS JKM ER','#','4','14376'],['2022-12','9710191','BPJS JKM ER','#','4','14376'],['2022-12','9710195','BPJS JKM ER','#','4','14376'],['2022-12','9710193','BPJS JKM ER','#','4','14376'],['2022-12','9710192','BPJS JKM ER','#','4','14376'],['2022-12','9713690','BPJS JKM ER','#','4','14376'],['2022-12','9713688','BPJS JKM ER','#','4','14376'],['2022-12','9712164','BPJS JKM ER','#','4','14376'],['2022-12','9710202','BPJS JKM ER','#','4','14376'],['2022-12','9710201','BPJS JKM ER','#','4','14376'],['2022-12','9710200','BPJS JKM ER','#','4','14376'],['2022-12','9713687','BPJS JKM ER','#','4','14376'],['2022-12','9713841','BPJS JKM ER','#','4','14376'],['2022-12','9710243','BPJS JKM ER','#','4','14376'],['2022-12','9710203','BPJS JKM ER','#','4','14376'],['2022-12','9713689','BPJS JKM ER','#','4','14376'],['2022-12','9710205','BPJS JKM ER','#','4','14376'],['2022-12','9713691','BPJS JKM ER','#','4','14376'],['2022-12','9710241','BPJS JKM ER','#','4','14376'],['2022-12','9710314','BPJS JKM ER','#','4','14376'],['2022-12','9710242','BPJS JKM ER','#','4','14376'],['2022-12','9712357','BPJS JKM ER','#','4','14376'],['2022-12','9710294','BPJS JKM ER','#','4','14376'],['2022-12','9710301','BPJS JKM ER','#','4','14376'],['2022-12','9710295','BPJS JKM ER','#','4','14376'],['2022-12','9710186','BPJS JKM ER','#','4','14376'],['2022-12','9710298','BPJS JKM ER','#','4','14376'],['2022-12','9710293','BPJS JKM ER','#','4','14376'],['2022-12','9710302','BPJS JKM ER','#','4','14376'],['2022-12','9710300','BPJS JKM ER','#','4','14376'],['2022-12','9710297','BPJS JKM ER','#','4','14376'],['2022-12','9710291','BPJS JKM ER','#','4','14376'],['2022-12','9710290','BPJS JKM ER','#','4','14376'],['2022-12','9710204','BPJS JKM ER','#','4','14376'],['2022-12','9710305','BPJS JKM ER','#','4','14376'],['2022-12','9710299','BPJS JKM ER','#','4','14376'],['2022-12','9710303','BPJS JKM ER','#','4','14376'],['2022-12','9710304','BPJS JKM ER','#','4','14376'],['2022-12','9710187','BPJS JKK ER','#','5','11500'],['2022-12','9713287','BPJS JKK ER','#','5','11500'],['2022-12','9713208','BPJS JKK ER','#','5','11500'],['2022-12','9710189','BPJS JKK ER','#','5','11500'],['2022-12','9714136','BPJS JKK ER','#','5','11500'],['2022-12','9713209','BPJS JKK ER','#','5','11500'],['2022-12','9710191','BPJS JKK ER','#','5','11500'],['2022-12','9710195','BPJS JKK ER','#','5','11500'],['2022-12','9710193','BPJS JKK ER','#','5','11500'],['2022-12','9710192','BPJS JKK ER','#','5','11500'],['2022-12','9713690','BPJS JKK ER','#','5','11500'],['2022-12','9713688','BPJS JKK ER','#','5','11500'],['2022-12','9712164','BPJS JKK ER','#','5','11500'],['2022-12','9710202','BPJS JKK ER','#','5','11500'],['2022-12','9710201','BPJS JKK ER','#','5','11500'],['2022-12','9710200','BPJS JKK ER','#','5','11500'],['2022-12','9713687','BPJS JKK ER','#','5','11500'],['2022-12','9713841','BPJS JKK ER','#','5','11500'],['2022-12','9710243','BPJS JKK ER','#','5','11500'],['2022-12','9710203','BPJS JKK ER','#','5','11500'],['2022-12','9713689','BPJS JKK ER','#','5','11500'],['2022-12','9710205','BPJS JKK ER','#','5','11500'],['2022-12','9713691','BPJS JKK ER','#','5','11500'],['2022-12','9710241','BPJS JKK ER','#','5','11500'],['2022-12','9710314','BPJS JKK ER','#','5','11500'],['2022-12','9710242','BPJS JKK ER','#','5','11500'],['2022-12','9712357','BPJS JKK ER','#','5','11500'],['2022-12','9710294','BPJS JKK ER','#','5','11500'],['2022-12','9710301','BPJS JKK ER','#','5','11500'],['2022-12','9710295','BPJS JKK ER','#','5','11500'],['2022-12','9710186','BPJS JKK ER','#','5','11500'],['2022-12','9710298','BPJS JKK ER','#','5','11500'],['2022-12','9710293','BPJS JKK ER','#','5','11500'],['2022-12','9710302','BPJS JKK ER','#','5','11500'],['2022-12','9710300','BPJS JKK ER','#','5','11500'],['2022-12','9710297','BPJS JKK ER','#','5','11500'],['2022-12','9710291','BPJS JKK ER','#','5','11500'],['2022-12','9710290','BPJS JKK ER','#','5','11500'],['2022-12','9710204','BPJS JKK ER','#','5','11500'],['2022-12','9710305','BPJS JKK ER','#','5','11500'],['2022-12','9710299','BPJS JKK ER','#','5','11500'],['2022-12','9710303','BPJS JKK ER','#','5','11500'],['2022-12','9710304','BPJS JKK ER','#','5','11500'],['2023-01','9710187','Gaji Pokok','+','1','4791843'],['2023-01','9713287','Gaji Pokok','+','1','4791843'],['2023-01','9713208','Gaji Pokok','+','1','4791843'],['2023-01','9710189','Gaji Pokok','+','1','4791843'],['2023-01','9714136','Gaji Pokok','+','1','4791843'],['2023-01','9713209','Gaji Pokok','+','1','4791843'],['2023-01','9710191','Gaji Pokok','+','1','4791843'],['2023-01','9710195','Gaji Pokok','+','1','4791843'],['2023-01','9710193','Gaji Pokok','+','1','4791843'],['2023-01','9710192','Gaji Pokok','+','1','4791843'],['2023-01','9713690','Gaji Pokok','+','1','4791843'],['2023-01','9713688','Gaji Pokok','+','1','4791843'],['2023-01','9712164','Gaji Pokok','+','1','4791843'],['2023-01','9710202','Gaji Pokok','+','1','4791843'],['2023-01','9710201','Gaji Pokok','+','1','4791843'],['2023-01','9710200','Gaji Pokok','+','1','4791843'],['2023-01','9713687','Gaji Pokok','+','1','4791843'],['2023-01','9713841','Gaji Pokok','+','1','4791843'],['2023-01','9710243','Gaji Pokok','+','1','4791843'],['2023-01','9710203','Gaji Pokok','+','1','4791843'],['2023-01','9713689','Gaji Pokok','+','1','4791843'],['2023-01','9710205','Gaji Pokok','+','1','4791843'],['2023-01','9713691','Gaji Pokok','+','1','4791843'],['2023-01','9710241','Gaji Pokok','+','1','4791843'],['2023-01','9710314','Gaji Pokok','+','1','4791843'],['2023-01','9710242','Gaji Pokok','+','1','4791843'],['2023-01','9712357','Gaji Pokok','+','1','4791843'],['2023-01','9710294','Gaji Pokok','+','1','4791843'],['2023-01','9710301','Gaji Pokok','+','1','4791843'],['2023-01','9710295','Gaji Pokok','+','1','4791843'],['2023-01','9710186','Gaji Pokok','+','1','4791843'],['2023-01','9710298','Gaji Pokok','+','1','4791843'],['2023-01','9710293','Gaji Pokok','+','1','4791843'],['2023-01','9710302','Gaji Pokok','+','1','4791843'],['2023-01','9710300','Gaji Pokok','+','1','4791843'],['2023-01','9710297','Gaji Pokok','+','1','4791843'],['2023-01','9710291','Gaji Pokok','+','1','4791843'],['2023-01','9710290','Gaji Pokok','+','1','4791843'],['2023-01','9710204','Gaji Pokok','+','1','4791843'],['2023-01','9710305','Gaji Pokok','+','1','4791843'],['2023-01','9710299','Gaji Pokok','+','1','4791843'],['2023-01','9710303','Gaji Pokok','+','1','4791843'],['2023-01','9710304','Gaji Pokok','+','1','4791843'],['2023-01','9710187','BPJS JKN EE','-','1','47918'],['2023-01','9713287','BPJS JKN EE','-','1','47918'],['2023-01','9713208','BPJS JKN EE','-','1','47918'],['2023-01','9710189','BPJS JKN EE','-','1','47918'],['2023-01','9714136','BPJS JKN EE','-','1','47918'],['2023-01','9713209','BPJS JKN EE','-','1','47918'],['2023-01','9710191','BPJS JKN EE','-','1','47918'],['2023-01','9710195','BPJS JKN EE','-','1','47918'],['2023-01','9710193','BPJS JKN EE','-','1','47918'],['2023-01','9710192','BPJS JKN EE','-','1','47918'],['2023-01','9713690','BPJS JKN EE','-','1','47918'],['2023-01','9713688','BPJS JKN EE','-','1','47918'],['2023-01','9712164','BPJS JKN EE','-','1','47918'],['2023-01','9710202','BPJS JKN EE','-','1','47918'],['2023-01','9710201','BPJS JKN EE','-','1','47918'],['2023-01','9710200','BPJS JKN EE','-','1','47918'],['2023-01','9713687','BPJS JKN EE','-','1','47918'],['2023-01','9713841','BPJS JKN EE','-','1','47918'],['2023-01','9710243','BPJS JKN EE','-','1','47918'],['2023-01','9710203','BPJS JKN EE','-','1','47918'],['2023-01','9713689','BPJS JKN EE','-','1','47918'],['2023-01','9710205','BPJS JKN EE','-','1','47918'],['2023-01','9713691','BPJS JKN EE','-','1','47918'],['2023-01','9710241','BPJS JKN EE','-','1','47918'],['2023-01','9710314','BPJS JKN EE','-','1','47918'],['2023-01','9710242','BPJS JKN EE','-','1','47918'],['2023-01','9712357','BPJS JKN EE','-','1','47918'],['2023-01','9710294','BPJS JKN EE','-','1','47918'],['2023-01','9710301','BPJS JKN EE','-','1','47918'],['2023-01','9710295','BPJS JKN EE','-','1','47918'],['2023-01','9710186','BPJS JKN EE','-','1','47918'],['2023-01','9710298','BPJS JKN EE','-','1','47918'],['2023-01','9710293','BPJS JKN EE','-','1','47918'],['2023-01','9710302','BPJS JKN EE','-','1','47918'],['2023-01','9710300','BPJS JKN EE','-','1','47918'],['2023-01','9710297','BPJS JKN EE','-','1','47918'],['2023-01','9710291','BPJS JKN EE','-','1','47918'],['2023-01','9710290','BPJS JKN EE','-','1','47918'],['2023-01','9710204','BPJS JKN EE','-','1','47918'],['2023-01','9710305','BPJS JKN EE','-','1','47918'],['2023-01','9710299','BPJS JKN EE','-','1','47918'],['2023-01','9710303','BPJS JKN EE','-','1','47918'],['2023-01','9710304','BPJS JKN EE','-','1','47918'],['2023-01','9710187','BPJS JP EE','-','2','47918'],['2023-01','9713287','BPJS JP EE','-','2','47918'],['2023-01','9713208','BPJS JP EE','-','2','47918'],['2023-01','9710189','BPJS JP EE','-','2','47918'],['2023-01','9714136','BPJS JP EE','-','2','47918'],['2023-01','9713209','BPJS JP EE','-','2','47918'],['2023-01','9710191','BPJS JP EE','-','2','47918'],['2023-01','9710195','BPJS JP EE','-','2','47918'],['2023-01','9710193','BPJS JP EE','-','2','47918'],['2023-01','9710192','BPJS JP EE','-','2','47918'],['2023-01','9713690','BPJS JP EE','-','2','47918'],['2023-01','9713688','BPJS JP EE','-','2','47918'],['2023-01','9712164','BPJS JP EE','-','2','47918'],['2023-01','9710202','BPJS JP EE','-','2','47918'],['2023-01','9710201','BPJS JP EE','-','2','47918'],['2023-01','9710200','BPJS JP EE','-','2','47918'],['2023-01','9713687','BPJS JP EE','-','2','47918'],['2023-01','9713841','BPJS JP EE','-','2','47918'],['2023-01','9710243','BPJS JP EE','-','2','47918'],['2023-01','9710203','BPJS JP EE','-','2','47918'],['2023-01','9713689','BPJS JP EE','-','2','47918'],['2023-01','9710205','BPJS JP EE','-','2','47918'],['2023-01','9713691','BPJS JP EE','-','2','47918'],['2023-01','9710241','BPJS JP EE','-','2','47918'],['2023-01','9710314','BPJS JP EE','-','2','47918'],['2023-01','9710242','BPJS JP EE','-','2','47918'],['2023-01','9712357','BPJS JP EE','-','2','47918'],['2023-01','9710294','BPJS JP EE','-','2','47918'],['2023-01','9710301','BPJS JP EE','-','2','47918'],['2023-01','9710295','BPJS JP EE','-','2','47918'],['2023-01','9710186','BPJS JP EE','-','2','47918'],['2023-01','9710298','BPJS JP EE','-','2','47918'],['2023-01','9710293','BPJS JP EE','-','2','47918'],['2023-01','9710302','BPJS JP EE','-','2','47918'],['2023-01','9710300','BPJS JP EE','-','2','47918'],['2023-01','9710297','BPJS JP EE','-','2','47918'],['2023-01','9710291','BPJS JP EE','-','2','47918'],['2023-01','9710290','BPJS JP EE','-','2','47918'],['2023-01','9710204','BPJS JP EE','-','2','47918'],['2023-01','9710305','BPJS JP EE','-','2','47918']];
        $arr_det_4 = [['2023-01','9710299','BPJS JP EE','-','2','47918'],['2023-01','9710303','BPJS JP EE','-','2','47918'],['2023-01','9710304','BPJS JP EE','-','2','47918'],['2023-01','9710187','BPJS JHT EE','-','3','95837'],['2023-01','9713287','BPJS JHT EE','-','3','95837'],['2023-01','9713208','BPJS JHT EE','-','3','95837'],['2023-01','9710189','BPJS JHT EE','-','3','95837'],['2023-01','9714136','BPJS JHT EE','-','3','95837'],['2023-01','9713209','BPJS JHT EE','-','3','95837'],['2023-01','9710191','BPJS JHT EE','-','3','95837'],['2023-01','9710195','BPJS JHT EE','-','3','95837'],['2023-01','9710193','BPJS JHT EE','-','3','95837'],['2023-01','9710192','BPJS JHT EE','-','3','95837'],['2023-01','9713690','BPJS JHT EE','-','3','95837'],['2023-01','9713688','BPJS JHT EE','-','3','95837'],['2023-01','9712164','BPJS JHT EE','-','3','95837'],['2023-01','9710202','BPJS JHT EE','-','3','95837'],['2023-01','9710201','BPJS JHT EE','-','3','95837'],['2023-01','9710200','BPJS JHT EE','-','3','95837'],['2023-01','9713687','BPJS JHT EE','-','3','95837'],['2023-01','9713841','BPJS JHT EE','-','3','95837'],['2023-01','9710243','BPJS JHT EE','-','3','95837'],['2023-01','9710203','BPJS JHT EE','-','3','95837'],['2023-01','9713689','BPJS JHT EE','-','3','95837'],['2023-01','9710205','BPJS JHT EE','-','3','95837'],['2023-01','9713691','BPJS JHT EE','-','3','95837'],['2023-01','9710241','BPJS JHT EE','-','3','95837'],['2023-01','9710314','BPJS JHT EE','-','3','95837'],['2023-01','9710242','BPJS JHT EE','-','3','95837'],['2023-01','9712357','BPJS JHT EE','-','3','95837'],['2023-01','9710294','BPJS JHT EE','-','3','95837'],['2023-01','9710301','BPJS JHT EE','-','3','95837'],['2023-01','9710295','BPJS JHT EE','-','3','95837'],['2023-01','9710186','BPJS JHT EE','-','3','95837'],['2023-01','9710298','BPJS JHT EE','-','3','95837'],['2023-01','9710293','BPJS JHT EE','-','3','95837'],['2023-01','9710302','BPJS JHT EE','-','3','95837'],['2023-01','9710300','BPJS JHT EE','-','3','95837'],['2023-01','9710297','BPJS JHT EE','-','3','95837'],['2023-01','9710291','BPJS JHT EE','-','3','95837'],['2023-01','9710290','BPJS JHT EE','-','3','95837'],['2023-01','9710204','BPJS JHT EE','-','3','95837'],['2023-01','9710305','BPJS JHT EE','-','3','95837'],['2023-01','9710299','BPJS JHT EE','-','3','95837'],['2023-01','9710303','BPJS JHT EE','-','3','95837'],['2023-01','9710304','BPJS JHT EE','-','3','95837'],['2023-01','9710187','BPJS JKN ER','#','1','191674'],['2023-01','9713287','BPJS JKN ER','#','1','191674'],['2023-01','9713208','BPJS JKN ER','#','1','191674'],['2023-01','9710189','BPJS JKN ER','#','1','191674'],['2023-01','9714136','BPJS JKN ER','#','1','191674'],['2023-01','9713209','BPJS JKN ER','#','1','191674'],['2023-01','9710191','BPJS JKN ER','#','1','191674'],['2023-01','9710195','BPJS JKN ER','#','1','191674'],['2023-01','9710193','BPJS JKN ER','#','1','191674'],['2023-01','9710192','BPJS JKN ER','#','1','191674'],['2023-01','9713690','BPJS JKN ER','#','1','191674'],['2023-01','9713688','BPJS JKN ER','#','1','191674'],['2023-01','9712164','BPJS JKN ER','#','1','191674'],['2023-01','9710202','BPJS JKN ER','#','1','191674'],['2023-01','9710201','BPJS JKN ER','#','1','191674'],['2023-01','9710200','BPJS JKN ER','#','1','191674'],['2023-01','9713687','BPJS JKN ER','#','1','191674'],['2023-01','9713841','BPJS JKN ER','#','1','191674'],['2023-01','9710243','BPJS JKN ER','#','1','191674'],['2023-01','9710203','BPJS JKN ER','#','1','191674'],['2023-01','9713689','BPJS JKN ER','#','1','191674'],['2023-01','9710205','BPJS JKN ER','#','1','191674'],['2023-01','9713691','BPJS JKN ER','#','1','191674'],['2023-01','9710241','BPJS JKN ER','#','1','191674'],['2023-01','9710314','BPJS JKN ER','#','1','191674'],['2023-01','9710242','BPJS JKN ER','#','1','191674'],['2023-01','9712357','BPJS JKN ER','#','1','191674'],['2023-01','9710294','BPJS JKN ER','#','1','191674'],['2023-01','9710301','BPJS JKN ER','#','1','191674'],['2023-01','9710295','BPJS JKN ER','#','1','191674'],['2023-01','9710186','BPJS JKN ER','#','1','191674'],['2023-01','9710298','BPJS JKN ER','#','1','191674'],['2023-01','9710293','BPJS JKN ER','#','1','191674'],['2023-01','9710302','BPJS JKN ER','#','1','191674'],['2023-01','9710300','BPJS JKN ER','#','1','191674'],['2023-01','9710297','BPJS JKN ER','#','1','191674'],['2023-01','9710291','BPJS JKN ER','#','1','191674'],['2023-01','9710290','BPJS JKN ER','#','1','191674'],['2023-01','9710204','BPJS JKN ER','#','1','191674'],['2023-01','9710305','BPJS JKN ER','#','1','191674'],['2023-01','9710299','BPJS JKN ER','#','1','191674'],['2023-01','9710303','BPJS JKN ER','#','1','191674'],['2023-01','9710304','BPJS JKN ER','#','1','191674'],['2023-01','9710187','BPJS JP ER','#','2','95837'],['2023-01','9713287','BPJS JP ER','#','2','95837'],['2023-01','9713208','BPJS JP ER','#','2','95837'],['2023-01','9710189','BPJS JP ER','#','2','95837'],['2023-01','9714136','BPJS JP ER','#','2','95837'],['2023-01','9713209','BPJS JP ER','#','2','95837'],['2023-01','9710191','BPJS JP ER','#','2','95837'],['2023-01','9710195','BPJS JP ER','#','2','95837'],['2023-01','9710193','BPJS JP ER','#','2','95837'],['2023-01','9710192','BPJS JP ER','#','2','95837'],['2023-01','9713690','BPJS JP ER','#','2','95837'],['2023-01','9713688','BPJS JP ER','#','2','95837'],['2023-01','9712164','BPJS JP ER','#','2','95837'],['2023-01','9710202','BPJS JP ER','#','2','95837'],['2023-01','9710201','BPJS JP ER','#','2','95837'],['2023-01','9710200','BPJS JP ER','#','2','95837'],['2023-01','9713687','BPJS JP ER','#','2','95837'],['2023-01','9713841','BPJS JP ER','#','2','95837'],['2023-01','9710243','BPJS JP ER','#','2','95837'],['2023-01','9710203','BPJS JP ER','#','2','95837'],['2023-01','9713689','BPJS JP ER','#','2','95837'],['2023-01','9710205','BPJS JP ER','#','2','95837'],['2023-01','9713691','BPJS JP ER','#','2','95837'],['2023-01','9710241','BPJS JP ER','#','2','95837'],['2023-01','9710314','BPJS JP ER','#','2','95837'],['2023-01','9710242','BPJS JP ER','#','2','95837'],['2023-01','9712357','BPJS JP ER','#','2','95837'],['2023-01','9710294','BPJS JP ER','#','2','95837'],['2023-01','9710301','BPJS JP ER','#','2','95837'],['2023-01','9710295','BPJS JP ER','#','2','95837'],['2023-01','9710186','BPJS JP ER','#','2','95837'],['2023-01','9710298','BPJS JP ER','#','2','95837'],['2023-01','9710293','BPJS JP ER','#','2','95837'],['2023-01','9710302','BPJS JP ER','#','2','95837'],['2023-01','9710300','BPJS JP ER','#','2','95837'],['2023-01','9710297','BPJS JP ER','#','2','95837'],['2023-01','9710291','BPJS JP ER','#','2','95837'],['2023-01','9710290','BPJS JP ER','#','2','95837'],['2023-01','9710204','BPJS JP ER','#','2','95837'],['2023-01','9710305','BPJS JP ER','#','2','95837'],['2023-01','9710299','BPJS JP ER','#','2','95837'],['2023-01','9710303','BPJS JP ER','#','2','95837'],['2023-01','9710304','BPJS JP ER','#','2','95837'],['2023-01','9710187','BPJS JHT ER','#','3','177298'],['2023-01','9713287','BPJS JHT ER','#','3','177298'],['2023-01','9713208','BPJS JHT ER','#','3','177298'],['2023-01','9710189','BPJS JHT ER','#','3','177298'],['2023-01','9714136','BPJS JHT ER','#','3','177298'],['2023-01','9713209','BPJS JHT ER','#','3','177298'],['2023-01','9710191','BPJS JHT ER','#','3','177298'],['2023-01','9710195','BPJS JHT ER','#','3','177298'],['2023-01','9710193','BPJS JHT ER','#','3','177298'],['2023-01','9710192','BPJS JHT ER','#','3','177298'],['2023-01','9713690','BPJS JHT ER','#','3','177298'],['2023-01','9713688','BPJS JHT ER','#','3','177298'],['2023-01','9712164','BPJS JHT ER','#','3','177298'],['2023-01','9710202','BPJS JHT ER','#','3','177298'],['2023-01','9710201','BPJS JHT ER','#','3','177298'],['2023-01','9710200','BPJS JHT ER','#','3','177298'],['2023-01','9713687','BPJS JHT ER','#','3','177298'],['2023-01','9713841','BPJS JHT ER','#','3','177298'],['2023-01','9710243','BPJS JHT ER','#','3','177298'],['2023-01','9710203','BPJS JHT ER','#','3','177298'],['2023-01','9713689','BPJS JHT ER','#','3','177298'],['2023-01','9710205','BPJS JHT ER','#','3','177298'],['2023-01','9713691','BPJS JHT ER','#','3','177298'],['2023-01','9710241','BPJS JHT ER','#','3','177298'],['2023-01','9710314','BPJS JHT ER','#','3','177298'],['2023-01','9710242','BPJS JHT ER','#','3','177298'],['2023-01','9712357','BPJS JHT ER','#','3','177298'],['2023-01','9710294','BPJS JHT ER','#','3','177298'],['2023-01','9710301','BPJS JHT ER','#','3','177298'],['2023-01','9710295','BPJS JHT ER','#','3','177298'],['2023-01','9710186','BPJS JHT ER','#','3','177298'],['2023-01','9710298','BPJS JHT ER','#','3','177298'],['2023-01','9710293','BPJS JHT ER','#','3','177298'],['2023-01','9710302','BPJS JHT ER','#','3','177298'],['2023-01','9710300','BPJS JHT ER','#','3','177298'],['2023-01','9710297','BPJS JHT ER','#','3','177298'],['2023-01','9710291','BPJS JHT ER','#','3','177298'],['2023-01','9710290','BPJS JHT ER','#','3','177298'],['2023-01','9710204','BPJS JHT ER','#','3','177298'],['2023-01','9710305','BPJS JHT ER','#','3','177298'],['2023-01','9710299','BPJS JHT ER','#','3','177298'],['2023-01','9710303','BPJS JHT ER','#','3','177298'],['2023-01','9710304','BPJS JHT ER','#','3','177298'],['2023-01','9710187','BPJS JKM ER','#','4','14376'],['2023-01','9713287','BPJS JKM ER','#','4','14376'],['2023-01','9713208','BPJS JKM ER','#','4','14376'],['2023-01','9710189','BPJS JKM ER','#','4','14376'],['2023-01','9714136','BPJS JKM ER','#','4','14376'],['2023-01','9713209','BPJS JKM ER','#','4','14376'],['2023-01','9710191','BPJS JKM ER','#','4','14376'],['2023-01','9710195','BPJS JKM ER','#','4','14376'],['2023-01','9710193','BPJS JKM ER','#','4','14376'],['2023-01','9710192','BPJS JKM ER','#','4','14376'],['2023-01','9713690','BPJS JKM ER','#','4','14376'],['2023-01','9713688','BPJS JKM ER','#','4','14376'],['2023-01','9712164','BPJS JKM ER','#','4','14376'],['2023-01','9710202','BPJS JKM ER','#','4','14376'],['2023-01','9710201','BPJS JKM ER','#','4','14376'],['2023-01','9710200','BPJS JKM ER','#','4','14376'],['2023-01','9713687','BPJS JKM ER','#','4','14376'],['2023-01','9713841','BPJS JKM ER','#','4','14376'],['2023-01','9710243','BPJS JKM ER','#','4','14376'],['2023-01','9710203','BPJS JKM ER','#','4','14376'],['2023-01','9713689','BPJS JKM ER','#','4','14376'],['2023-01','9710205','BPJS JKM ER','#','4','14376'],['2023-01','9713691','BPJS JKM ER','#','4','14376'],['2023-01','9710241','BPJS JKM ER','#','4','14376'],['2023-01','9710314','BPJS JKM ER','#','4','14376'],['2023-01','9710242','BPJS JKM ER','#','4','14376'],['2023-01','9712357','BPJS JKM ER','#','4','14376'],['2023-01','9710294','BPJS JKM ER','#','4','14376'],['2023-01','9710301','BPJS JKM ER','#','4','14376'],['2023-01','9710295','BPJS JKM ER','#','4','14376'],['2023-01','9710186','BPJS JKM ER','#','4','14376'],['2023-01','9710298','BPJS JKM ER','#','4','14376'],['2023-01','9710293','BPJS JKM ER','#','4','14376'],['2023-01','9710302','BPJS JKM ER','#','4','14376'],['2023-01','9710300','BPJS JKM ER','#','4','14376'],['2023-01','9710297','BPJS JKM ER','#','4','14376'],['2023-01','9710291','BPJS JKM ER','#','4','14376'],['2023-01','9710290','BPJS JKM ER','#','4','14376'],['2023-01','9710204','BPJS JKM ER','#','4','14376'],['2023-01','9710305','BPJS JKM ER','#','4','14376'],['2023-01','9710299','BPJS JKM ER','#','4','14376'],['2023-01','9710303','BPJS JKM ER','#','4','14376'],['2023-01','9710304','BPJS JKM ER','#','4','14376'],['2023-01','9710187','BPJS JKK ER','#','5','11500'],['2023-01','9713287','BPJS JKK ER','#','5','11500'],['2023-01','9713208','BPJS JKK ER','#','5','11500'],['2023-01','9710189','BPJS JKK ER','#','5','11500'],['2023-01','9714136','BPJS JKK ER','#','5','11500'],['2023-01','9713209','BPJS JKK ER','#','5','11500'],['2023-01','9710191','BPJS JKK ER','#','5','11500'],['2023-01','9710195','BPJS JKK ER','#','5','11500'],['2023-01','9710193','BPJS JKK ER','#','5','11500'],['2023-01','9710192','BPJS JKK ER','#','5','11500'],['2023-01','9713690','BPJS JKK ER','#','5','11500'],['2023-01','9713688','BPJS JKK ER','#','5','11500'],['2023-01','9712164','BPJS JKK ER','#','5','11500'],['2023-01','9710202','BPJS JKK ER','#','5','11500'],['2023-01','9710201','BPJS JKK ER','#','5','11500'],['2023-01','9710200','BPJS JKK ER','#','5','11500'],['2023-01','9713687','BPJS JKK ER','#','5','11500'],['2023-01','9713841','BPJS JKK ER','#','5','11500'],['2023-01','9710243','BPJS JKK ER','#','5','11500'],['2023-01','9710203','BPJS JKK ER','#','5','11500'],['2023-01','9713689','BPJS JKK ER','#','5','11500'],['2023-01','9710205','BPJS JKK ER','#','5','11500'],['2023-01','9713691','BPJS JKK ER','#','5','11500'],['2023-01','9710241','BPJS JKK ER','#','5','11500'],['2023-01','9710314','BPJS JKK ER','#','5','11500'],['2023-01','9710242','BPJS JKK ER','#','5','11500'],['2023-01','9712357','BPJS JKK ER','#','5','11500'],['2023-01','9710294','BPJS JKK ER','#','5','11500'],['2023-01','9710301','BPJS JKK ER','#','5','11500'],['2023-01','9710295','BPJS JKK ER','#','5','11500'],['2023-01','9710186','BPJS JKK ER','#','5','11500'],['2023-01','9710298','BPJS JKK ER','#','5','11500'],['2023-01','9710293','BPJS JKK ER','#','5','11500'],['2023-01','9710302','BPJS JKK ER','#','5','11500'],['2023-01','9710300','BPJS JKK ER','#','5','11500'],['2023-01','9710297','BPJS JKK ER','#','5','11500'],['2023-01','9710291','BPJS JKK ER','#','5','11500'],['2023-01','9710290','BPJS JKK ER','#','5','11500'],['2023-01','9710204','BPJS JKK ER','#','5','11500'],['2023-01','9710305','BPJS JKK ER','#','5','11500'],['2023-01','9710299','BPJS JKK ER','#','5','11500'],['2023-01','9710303','BPJS JKK ER','#','5','11500'],['2023-01','9710304','BPJS JKK ER','#','5','11500'],['2022-11','9713212','Gaji Pojok','+','1','6000000'],['2022-11','9713212','Tunjangan Fungsional','+','2','1240000'],['2022-11','9713212','BPJS JKN EE','-','1','60000'],['2022-11','9713212','BPJS JP EE','-','2','60000'],['2022-11','9713212','BPJS JHT EE','-','3','120000'],['2022-11','9713212','BPJS JKN ER','#','1','240000'],['2022-11','9713212','BPJS JP ER','#','2','120000'],['2022-11','9713212','BPJS JHT ER','#','3','222000'],['2022-11','9713212','BPJS JKM ER','#','4','18000'],['2022-11','9713212','BPJS JKK ER','#','5','14400'],['2022-12','9713212','Gaji Pojok','+','1','6000000'],['2022-12','9713212','Tunjangan Fungsional','+','2','1240000'],['2022-12','9713212','BPJS JKN EE','-','1','60000'],['2022-12','9713212','BPJS JP EE','-','2','60000'],['2022-12','9713212','BPJS JHT EE','-','3','120000'],['2022-12','9713212','BPJS JKN ER','#','1','240000'],['2022-12','9713212','BPJS JP ER','#','2','120000'],['2022-12','9713212','BPJS JHT ER','#','3','222000'],['2022-12','9713212','BPJS JKM ER','#','4','18000'],['2022-12','9713212','BPJS JKK ER','#','5','14400'],['2023-01','9713212','Gaji Pojok','+','1','6000000'],['2023-01','9713212','Tunjangan Fungsional','+','2','1240000'],['2023-01','9713212','BPJS JKN EE','-','1','60000'],['2023-01','9713212','BPJS JP EE','-','2','60000'],['2023-01','9713212','BPJS JHT EE','-','3','120000'],['2023-01','9713212','BPJS JKN ER','#','1','240000'],['2023-01','9713212','BPJS JP ER','#','2','120000'],['2023-01','9713212','BPJS JHT ER','#','3','222000'],['2023-01','9713212','BPJS JKM ER','#','4','18000'],['2023-01','9713212','BPJS JKK ER','#','5','14400'],['2022-11','9713445','Gaji Pokok','+','1','4791843'],['2022-11','9710288','Gaji Pokok','+','1','4800000'],['2022-11','9712356','Gaji Pokok','+','1','4801843'],['2022-11','9710306','Gaji Pokok','+','1','4801843'],['2022-11','9710307','Gaji Pokok','+','1','4801843'],['2022-11','9710287','Gaji Pokok','+','1','4939343'],['2022-11','9800196','Gaji Pokok','+','1','4939343'],['2022-11','9713445','Tunjangan Transport','+','2','312500'],['2022-11','9710288','Tunjangan Transport','+','2','312500']];
        $arr_det_5 = [['2022-11','9712356','Tunjangan Transport','+','2','175000'],['2022-11','9710306','Tunjangan Transport','+','2','175000'],['2022-11','9710307','Tunjangan Transport','+','2','175000'],['2022-11','9710287','Tunjangan Transport','+','2','312500'],['2022-11','9800196','Tunjangan Transport','+','2','312500'],['2022-11','9713445','BPJS JKN EE','-','1','47918'],['2022-11','9710288','BPJS JKN EE','-','1','48000'],['2022-11','9712356','BPJS JKN EE','-','1','48018'],['2022-11','9710306','BPJS JKN EE','-','1','48018'],['2022-11','9710307','BPJS JKN EE','-','1','48018'],['2022-11','9710287','BPJS JKN EE','-','1','49393'],['2022-11','9800196','BPJS JKN EE','-','1','49393'],['2022-11','9713445','BPJS JP EE','-','2','47918'],['2022-11','9710288','BPJS JP EE','-','2','48000'],['2022-11','9712356','BPJS JP EE','-','2','48018'],['2022-11','9710306','BPJS JP EE','-','2','48018'],['2022-11','9710307','BPJS JP EE','-','2','48018'],['2022-11','9710287','BPJS JP EE','-','2','49393'],['2022-11','9800196','BPJS JP EE','-','2','49393'],['2022-11','9713445','BPJS JHT EE','-','3','95837'],['2022-11','9710288','BPJS JHT EE','-','3','96000'],['2022-11','9712356','BPJS JHT EE','-','3','96037'],['2022-11','9710306','BPJS JHT EE','-','3','96037'],['2022-11','9710307','BPJS JHT EE','-','3','96037'],['2022-11','9710287','BPJS JHT EE','-','3','98787'],['2022-11','9800196','BPJS JHT EE','-','3','98787'],['2022-11','9713445','BPJS JKN ER','#','1','191674'],['2022-11','9710288','BPJS JKN ER','#','1','192000'],['2022-11','9712356','BPJS JKN ER','#','1','192074'],['2022-11','9710306','BPJS JKN ER','#','1','192074'],['2022-11','9710307','BPJS JKN ER','#','1','192074'],['2022-11','9710287','BPJS JKN ER','#','1','197574'],['2022-11','9800196','BPJS JKN ER','#','1','197574'],['2022-11','9713445','BPJS JP ER','#','2','95837'],['2022-11','9710288','BPJS JP ER','#','2','96000'],['2022-11','9712356','BPJS JP ER','#','2','96037'],['2022-11','9710306','BPJS JP ER','#','2','96037'],['2022-11','9710307','BPJS JP ER','#','2','96037'],['2022-11','9710287','BPJS JP ER','#','2','98787'],['2022-11','9800196','BPJS JP ER','#','2','98787'],['2022-11','9713445','BPJS JHT ER','#','3','177298'],['2022-11','9710288','BPJS JHT ER','#','3','177600'],['2022-11','9712356','BPJS JHT ER','#','3','177668'],['2022-11','9710306','BPJS JHT ER','#','3','177668'],['2022-11','9710307','BPJS JHT ER','#','3','177668'],['2022-11','9710287','BPJS JHT ER','#','3','182756'],['2022-11','9800196','BPJS JHT ER','#','3','182756'],['2022-11','9713445','BPJS JKM ER','#','4','14376'],['2022-11','9710288','BPJS JKM ER','#','4','14400'],['2022-11','9712356','BPJS JKM ER','#','4','14406'],['2022-11','9710306','BPJS JKM ER','#','4','14406'],['2022-11','9710307','BPJS JKM ER','#','4','14406'],['2022-11','9710287','BPJS JKM ER','#','4','14818'],['2022-11','9800196','BPJS JKM ER','#','4','14818'],['2022-11','9713445','BPJS JKK ER','#','5','11500'],['2022-11','9710288','BPJS JKK ER','#','5','11520'],['2022-11','9712356','BPJS JKK ER','#','5','11524'],['2022-11','9710306','BPJS JKK ER','#','5','11524'],['2022-11','9710307','BPJS JKK ER','#','5','11524'],['2022-11','9710287','BPJS JKK ER','#','5','11854'],['2022-11','9800196','BPJS JKK ER','#','5','11854'],['2022-12','9713445','Gaji Pokok','+','1','4791843'],['2022-12','9710288','Gaji Pokok','+','1','4800000'],['2022-12','9712356','Gaji Pokok','+','1','4801843'],['2022-12','9710306','Gaji Pokok','+','1','4801843'],['2022-12','9710307','Gaji Pokok','+','1','4801843'],['2022-12','9710287','Gaji Pokok','+','1','4939343'],['2022-12','9800196','Gaji Pokok','+','1','4939343'],['2022-12','9713445','Tunjangan Transport','+','2','325000'],['2022-12','9710288','Tunjangan Transport','+','2','325000'],['2022-12','9712356','Tunjangan Transport','+','2','182000'],['2022-12','9710306','Tunjangan Transport','+','2','182000'],['2022-12','9710307','Tunjangan Transport','+','2','182000'],['2022-12','9710287','Tunjangan Transport','+','2','325000'],['2022-12','9800196','Tunjangan Transport','+','2','325000'],['2022-12','9713445','BPJS JKN EE','-','1','47918'],['2022-12','9710288','BPJS JKN EE','-','1','48000'],['2022-12','9712356','BPJS JKN EE','-','1','48018'],['2022-12','9710306','BPJS JKN EE','-','1','48018'],['2022-12','9710307','BPJS JKN EE','-','1','48018'],['2022-12','9710287','BPJS JKN EE','-','1','49393'],['2022-12','9800196','BPJS JKN EE','-','1','49393'],['2022-12','9713445','BPJS JP EE','-','2','47918'],['2022-12','9710288','BPJS JP EE','-','2','48000'],['2022-12','9712356','BPJS JP EE','-','2','48018'],['2022-12','9710306','BPJS JP EE','-','2','48018'],['2022-12','9710307','BPJS JP EE','-','2','48018'],['2022-12','9710287','BPJS JP EE','-','2','49393'],['2022-12','9800196','BPJS JP EE','-','2','49393'],['2022-12','9713445','BPJS JHT EE','-','3','95837'],['2022-12','9710288','BPJS JHT EE','-','3','96000'],['2022-12','9712356','BPJS JHT EE','-','3','96037'],['2022-12','9710306','BPJS JHT EE','-','3','96037'],['2022-12','9710307','BPJS JHT EE','-','3','96037'],['2022-12','9710287','BPJS JHT EE','-','3','98787'],['2022-12','9800196','BPJS JHT EE','-','3','98787'],['2022-12','9713445','BPJS JKN ER','#','1','191674'],['2022-12','9710288','BPJS JKN ER','#','1','192000'],['2022-12','9712356','BPJS JKN ER','#','1','192074'],['2022-12','9710306','BPJS JKN ER','#','1','192074'],['2022-12','9710307','BPJS JKN ER','#','1','192074'],['2022-12','9710287','BPJS JKN ER','#','1','197574'],['2022-12','9800196','BPJS JKN ER','#','1','197574'],['2022-12','9713445','BPJS JP ER','#','2','95837'],['2022-12','9710288','BPJS JP ER','#','2','96000'],['2022-12','9712356','BPJS JP ER','#','2','96037'],['2022-12','9710306','BPJS JP ER','#','2','96037'],['2022-12','9710307','BPJS JP ER','#','2','96037'],['2022-12','9710287','BPJS JP ER','#','2','98787'],['2022-12','9800196','BPJS JP ER','#','2','98787'],['2022-12','9713445','BPJS JHT ER','#','3','177298'],['2022-12','9710288','BPJS JHT ER','#','3','177600'],['2022-12','9712356','BPJS JHT ER','#','3','177668'],['2022-12','9710306','BPJS JHT ER','#','3','177668'],['2022-12','9710307','BPJS JHT ER','#','3','177668'],['2022-12','9710287','BPJS JHT ER','#','3','182756'],['2022-12','9800196','BPJS JHT ER','#','3','182756'],['2022-12','9713445','BPJS JKM ER','#','4','14376'],['2022-12','9710288','BPJS JKM ER','#','4','14400'],['2022-12','9712356','BPJS JKM ER','#','4','14406'],['2022-12','9710306','BPJS JKM ER','#','4','14406'],['2022-12','9710307','BPJS JKM ER','#','4','14406'],['2022-12','9710287','BPJS JKM ER','#','4','14818'],['2022-12','9800196','BPJS JKM ER','#','4','14818'],['2022-12','9713445','BPJS JKK ER','#','5','11500'],['2022-12','9710288','BPJS JKK ER','#','5','11520'],['2022-12','9712356','BPJS JKK ER','#','5','11524'],['2022-12','9710306','BPJS JKK ER','#','5','11524'],['2022-12','9710307','BPJS JKK ER','#','5','11524'],['2022-12','9710287','BPJS JKK ER','#','5','11854'],['2022-12','9800196','BPJS JKK ER','#','5','11854'],['2023-01','9713445','Gaji Pokok','+','1','4791843'],['2023-01','9710288','Gaji Pokok','+','1','4800000'],['2023-01','9712356','Gaji Pokok','+','1','4801843'],['2023-01','9710306','Gaji Pokok','+','1','4801843'],['2023-01','9710307','Gaji Pokok','+','1','4801843'],['2023-01','9710287','Gaji Pokok','+','1','4939343'],['2023-01','9800196','Gaji Pokok','+','1','4939343'],['2023-01','9713445','Tunjangan Transport','+','2','325000'],['2023-01','9710288','Tunjangan Transport','+','2','325000'],['2023-01','9712356','Tunjangan Transport','+','2','182000'],['2023-01','9710306','Tunjangan Transport','+','2','182000'],['2023-01','9710307','Tunjangan Transport','+','2','182000'],['2023-01','9710287','Tunjangan Transport','+','2','325000'],['2023-01','9800196','Tunjangan Transport','+','2','325000'],['2023-01','9713445','BPJS JKN EE','-','1','47918'],['2023-01','9710288','BPJS JKN EE','-','1','48000'],['2023-01','9712356','BPJS JKN EE','-','1','48018'],['2023-01','9710306','BPJS JKN EE','-','1','48018'],['2023-01','9710307','BPJS JKN EE','-','1','48018'],['2023-01','9710287','BPJS JKN EE','-','1','49393'],['2023-01','9800196','BPJS JKN EE','-','1','49393'],['2023-01','9713445','BPJS JP EE','-','2','47918'],['2023-01','9710288','BPJS JP EE','-','2','48000'],['2023-01','9712356','BPJS JP EE','-','2','48018'],['2023-01','9710306','BPJS JP EE','-','2','48018'],['2023-01','9710307','BPJS JP EE','-','2','48018'],['2023-01','9710287','BPJS JP EE','-','2','49393'],['2023-01','9800196','BPJS JP EE','-','2','49393'],['2023-01','9713445','BPJS JHT EE','-','3','95837'],['2023-01','9710288','BPJS JHT EE','-','3','96000'],['2023-01','9712356','BPJS JHT EE','-','3','96037'],['2023-01','9710306','BPJS JHT EE','-','3','96037'],['2023-01','9710307','BPJS JHT EE','-','3','96037'],['2023-01','9710287','BPJS JHT EE','-','3','98787'],['2023-01','9800196','BPJS JHT EE','-','3','98787'],['2023-01','9713445','BPJS JKN ER','#','1','191674'],['2023-01','9710288','BPJS JKN ER','#','1','192000'],['2023-01','9712356','BPJS JKN ER','#','1','192074'],['2023-01','9710306','BPJS JKN ER','#','1','192074'],['2023-01','9710307','BPJS JKN ER','#','1','192074'],['2023-01','9710287','BPJS JKN ER','#','1','197574'],['2023-01','9800196','BPJS JKN ER','#','1','197574'],['2023-01','9713445','BPJS JP ER','#','2','95837'],['2023-01','9710288','BPJS JP ER','#','2','96000'],['2023-01','9712356','BPJS JP ER','#','2','96037'],['2023-01','9710306','BPJS JP ER','#','2','96037'],['2023-01','9710307','BPJS JP ER','#','2','96037'],['2023-01','9710287','BPJS JP ER','#','2','98787'],['2023-01','9800196','BPJS JP ER','#','2','98787'],['2023-01','9713445','BPJS JHT ER','#','3','177298'],['2023-01','9710288','BPJS JHT ER','#','3','177600'],['2023-01','9712356','BPJS JHT ER','#','3','177668'],['2023-01','9710306','BPJS JHT ER','#','3','177668'],['2023-01','9710307','BPJS JHT ER','#','3','177668'],['2023-01','9710287','BPJS JHT ER','#','3','182756'],['2023-01','9800196','BPJS JHT ER','#','3','182756'],['2023-01','9713445','BPJS JKM ER','#','4','14376'],['2023-01','9710288','BPJS JKM ER','#','4','14400'],['2023-01','9712356','BPJS JKM ER','#','4','14406'],['2023-01','9710306','BPJS JKM ER','#','4','14406'],['2023-01','9710307','BPJS JKM ER','#','4','14406'],['2023-01','9710287','BPJS JKM ER','#','4','14818'],['2023-01','9800196','BPJS JKM ER','#','4','14818'],['2023-01','9713445','BPJS JKK ER','#','5','11500'],['2023-01','9710288','BPJS JKK ER','#','5','11520'],['2023-01','9712356','BPJS JKK ER','#','5','11524'],['2023-01','9710306','BPJS JKK ER','#','5','11524'],['2023-01','9710307','BPJS JKK ER','#','5','11524'],['2023-01','9710287','BPJS JKK ER','#','5','11854'],['2023-01','9800196','BPJS JKK ER','#','5','11854']];

        $arr_emp = array_merge($arr_emp_1,$arr_emp_2);
        $arr_det = array_merge($arr_det_1,$arr_det_2,$arr_det_3,$arr_det_4,$arr_det_5);
        $arr_det_period_nopeg = [];
        foreach($arr_det as $det){
            if(array_key_exists($det[0].$det[1],$arr_det_period_nopeg)==false){
                $arr_det_period_nopeg[$det[0].$det[1]]  = ['+'=>[],'-'=>[],'#'=>[]];
            }
            $arr_det_period_nopeg[$det[0].$det[1]][$det[3]][]=$det;
        }
        foreach($arr_emp as $emp){
            // var_dump($emp);
            // sort($arr_det_period_nopeg[$emp[0].$emp[1]]['+']);
            // sort($arr_det_period_nopeg[$emp[0].$emp[1]]['-']);
            // sort($arr_det_period_nopeg[$emp[0].$emp[1]]['#']);
            $arr_con_slip = $arr_det_period_nopeg[$emp[0].$emp[1]];
            $max_line_slip = max(count($arr_det_period_nopeg[$emp[0].$emp[1]]['+']), count($arr_det_period_nopeg[$emp[0].$emp[1]]['-']), count($arr_det_period_nopeg[$emp[0].$emp[1]]['#']));
            $pdf = new FPDF('L', 'mm', 'Letter');
            $pdf->AddPage();

            $pdf->Image('http://localhost/img/Beyond_Care.png', 10, 2, 30, 0, 'PNG');
            $pdf->Image('http://localhost/img/gdps_logo_white.png', 210, 0, 60, 0, 'PNG');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);

            $aParamHead = array(1 => array('Nama', 'Periode'), 2 => array("Personal Number", 'Empl Group'),
                3 => array("Position", "Empl Sub Group"), 4 => array("Company", "Unit"));
            $aParamKV = array(1 => array($emp[2], $emp[0]), 2 => array($emp[1], $emp[5]),
                3 => array($emp[3], $emp[6  ]), 4 => array($emp[4], $emp[7]));
            for ($i = 1; $i <= 4; $i++) {
                $pdf->Cell(40, 5, $aParamHead[$i][0], 1, 0, 'L');
                $pdf->Cell(5, 5, ":", 1, 0, 'C');
                $pdf->Cell(87, 5, $aParamKV[$i][0], 1, 0, 'L');
                $pdf->Cell(40, 5, $aParamHead[$i][1], 1, 0, 'L');
                $pdf->Cell(5, 5, ":", 1, 0, 'C');
                $pdf->Cell(87, 5, $aParamKV[$i][1], 1, 1, 'L');
            }
            $pdf->Ln(10);
            $pdf->Cell(88, 5, "Penerimaan", 1, 0, 'C');
            $pdf->Cell(88, 5, "Kontribusi Karyawan", 1, 0, 'C');
            $pdf->Cell(88, 5, "Kontribusi Perusahaan", 1, 1, 'C');
            $aslipDefault = array("str" => "", "amount" => "",2=>"",5=>"");
            $pdf->SetFont('Arial', '', 10);
            
            $sumPlus = 0;
            $sumMinus = 0;
            $sumComp = 0;

            for ($i = 0; $i < $max_line_slip; $i++) {
                if (empty($arr_con_slip['+'][$i])) {
                    $aslip = $aslipDefault;
                } else {
                    $aslip = $arr_con_slip['+'][$i];
                    $sumPlus +=$aslip[5];
                }
                $pdf->Cell(60, 5, $aslip[2], 1, 0, 'L');
                $pdf->Cell(28, 5, $aslip[5], 1, 0, 'R');
                
                if (empty($arr_con_slip['-'][$i])) {
                    $aslip = $aslipDefault;
                } else {
                    $aslip = $arr_con_slip['-'][$i];
                    $sumMinus +=$aslip[5];
                }
                $pdf->Cell(60, 5, $aslip[2], 1, 0, 'L');
                $pdf->Cell(28, 5, $aslip[5], 1, 0, 'R');
                if (empty($arr_con_slip['#'][$i])) {
                    $aslip = $aslipDefault;
                } else {
                    $aslip = $arr_con_slip['#'][$i];
                    $sumComp +=$aslip[5];
                }
                $pdf->Cell(60, 5, $aslip[2], 1, 0, 'L');
                $pdf->Cell(28, 5, $aslip[5], 1, 1, 'R');
            }
            $pdf->Cell(60, 5, "Jumlah : ", 1, 0, 'R');
            $pdf->Cell(28, 5, number_format($sumPlus, 0, ".", ","), 1, 0, 'R');
            $pdf->Cell(60, 5, "Jumlah : ", 1, 0, 'R');
            $pdf->Cell(28, 5, number_format($sumMinus, 0, ".", ","), 1, 0, 'R');
            $pdf->Cell(60, 5, "Jumlah :", 1, 0, 'R');
            $pdf->Cell(28, 5, number_format($sumComp, 0, ".", ","), 1, 1, 'R');
    
            $pdf->Cell(60, 5, "Take Home Pay :", 1, 0, 'R');
            $pdf->Cell(28, 5, number_format($emp[8], 0, ".", ","), 1, 1, 'R');
            
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(264, 5, "Bank Transfer", 1, 1, 'L');
            $pdf->SetFont('Arial', '', 10);
    
            $pdf->Cell(60, 5, $emp[9], 1, 0, 'L');
            $pdf->Cell(70, 5, $emp[10], 1, 0, 'L');
            $pdf->Cell(60, 5, $emp[11], 1, 0, 'L');
            $pdf->Cell(74, 5, number_format(($emp[8]), 0, ".", ","), 1, 1, 'R');
            $filename = "payslip/LINFOX_" . $emp[0] . "_" . $emp[1].".pdf";
            echo "<br/>".$filename."<br/>";
            $pdf->output('F', $filename);
        }
        

    }

}
