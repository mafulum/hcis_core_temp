<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author Garudaper
 */
class offcycle extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('payroll/offcycle_m');
    }

    function index() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/offcycle';
        $data['oc'] = $this->offcycle_m->getOffCycle();
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $this->load->view('main', $data);
    }

    public function process() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/offcycle_process';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-datepicker/css/datepicker.css" />';

        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['externalJS'] .= '<script src="' . base_url() . 'assets/jquery.blockUI.js"></script>';
        $data['scriptJS'] = '<script type="text/javascript">
		$(document).ready(function() {
                    $("#evtda").datepicker();
                    $("#begda").datepicker();
		});
		</script>';
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_rules('evtda', 'evtda', 'trim|required');
            $this->form_validation->set_rules('begda', 'begda', 'trim|required');
            if ($this->form_validation->run()) {
                $name = $this->input->post('name');
                $begda = $this->input->post('begda');
                $evtda = $this->input->post('evtda');
                $sError = "<b>ERROR</b> : <br/><br/>" . "Empty File";
                if (!empty($_FILES['fupload']) && isset($_FILES['fupload']['name']) && !empty($_FILES['fupload']['name'])) {
                    $sError = "";
                    $filename = "";
                    $dir = "mass_upload/";
                    $len = strlen($_FILES['fupload']['name']);
                    $ext = substr($_FILES['fupload']['name'], $len - 3, 3);
                    //pengecekan extension
                    if ($ext != "xls") {
                        $sError = "<b>ERROR</b> : <br/><br/>" . "Extension file must be xls";
                    }
                    if (empty($sError)) {
                        $filename = "offcycle_payment_" . date("Ymd_Hi") . '.xls';
                        //copy file
                        $this->load->library('upload', array('upload_path' => $dir, 'overwrite' => true, 'allowed_types' => 'xls', 'remove_spaces' => true, 'file_name' => $filename));
                        $resUpload = $this->upload->do_upload('fupload');
                        if ($resUpload == false) {
                            $sError = $this->upload->display_errors();
                        }
                    }
                    if (empty($sError)) {
                        $percentage = 100;
                        $aTemp = $this->load($dir . $filename, $name, $begda, $evtda, $percentage);
                        if (!empty($aTemp['success'])) {
                            $data['success'] = $aTemp['success'];
                        }
                        if (!empty($aTemp['error'])) {
                            $data['sError'] = $aTemp['error'];
                        }
                    } else {
                        $data['sError'] = $sError;
                    }
                }
            }
        }
        $this->load->view('main', $data);
    }

    function load($filename, $name, $begda, $evtda, $percentage) {
        if (!empty($this->reader)) {
            return "<b>ERROR</b> : <br/><br/>" . "Library Error";
        }
        if (!file_exists($filename)) {
            return "<b>ERROR</b> : <br/><br/>" . "file does not exist";
        }
        $this->load->library('reader');
        $this->reader->setOutputEncoding('CP1251');
        $this->reader->read($filename);
        //HEADER
        $header = 1;
        $col = 1;
        $aStringHeader = array("PERNR", "WGTYP", "WAMNT", "NOTE");
        foreach ($aStringHeader as $headerName) {
            if (empty($this->reader->sheets[0]['cells'][$header][$col])) {
                return "<b>ERROR</b> : <br/><br/>" . "Header Miss " . $headerName . "|";
            }
            $text = $this->reader->sheets[0]['cells'][$header][$col];
            if (!empty($text) && $text != $headerName) {
                return "<b>ERROR</b> : <br/><br/>" . "Header Mismatch " . $headerName . "|" . $text;
            }
            $col++;
        }
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
//        if ($MAX_ROW == $col) {
//            return "<b>ERROR</b> : <br/><br/>" . "Does not have data";
//        }
        $sError = "";
        $aInput = array();
        $aemp = array();
        $aWGTYP = $this->offcycle_m->get_wgtype();
        $aMapWGTYP = array();
        if (!empty($aWGTYP)) {
            foreach ($aWGTYP as $row) {
                $aMapWGTYP[$row['WGTYP']] = $row;
            }
        }
        unset($aWGTYP);
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            $aData['PERNR'] = $this->reader->sheets[0]['cells'][$i][1];
            $aData['WGTYP'] = $this->reader->sheets[0]['cells'][$i][2];
            $aData['WAMNT'] = $this->reader->sheets[0]['cells'][$i][3];
            if (isset($this->reader->sheets[0]['cells'][$i][4])) {
                $aData['NOTE'] = $this->reader->sheets[0]['cells'][$i][4];
            }
            //pengecekan error 
            $aMandatory = array("PERNR", "WGTYP", "WAMNT", "NOTE");
            foreach ($aMandatory as $mandatory) {
                if (empty($aData[$mandatory])) {
                    $sError .= $mandatory . " empty @ row $i<br/>";
                }
            }
            if (empty($aMapWGTYP[$aData['WGTYP']])) {
                $sError .= " OffCycle Only allow THR & Insentif @ row $i<br/>";
            }
            $aemp[] = $aData['PERNR'];
            $aInput[] = $aData;
        }
        if (!empty($sError)) {
            return array('error' => "<b>ERROR</b> : <br/><br/>" . $sError);
        }
        $aEmpOrg = $this->global_m->getEmpOrgArrayDate($aemp, $evtda);
        $aMapEmpOrg = array();
        if (!empty($aEmpOrg)) {
            foreach ($aEmpOrg as $row) {
                $aMapEmpOrg[$row['PERNR']] = $row;
            }
        }
        unset($aEmpOrg);
        $id_offcycle = $this->offcycle_m->createOffCycle($name, $begda, $evtda, $filename, $percentage);
        $sReturn = "<table border='1' cellpadding='0' cellspacing='0'u><tr>";
        foreach ($aStringHeader as $header) {
            $sReturn .= "<td>" . $header . "</td>";
        }
        $sReturn .= "<td>ID OffCycle</td>";
        $sReturn .= "<td>BEGDA</td>";
        $sReturn .= "<td>EVTDA</td>";
        $sReturn .= "</tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $id_empoffcycle = $this->offcycle_m->run_offcycle($id_offcycle, $aInput[$i], $begda, $evtda);
            $sReturn .= "<tr>";
            foreach ($aStringHeader as $header) {
                $sReturn .= "<td>" . $aInput[$i][$header] . "</td>";
            }
            $sReturn .= "<td>" . $id_empoffcycle . "</td>";
            $sReturn .= "<td>" . $begda . "</td>";
            $sReturn .= "<td>" . $evtda . "</td>";
            $sReturn .= "</tr>";
            $aInput[$i]['ioc'] = $id_empoffcycle;
        }
        $sReturn .= "</table>";
        $this->offcycle_m->publishToBankTransfer($id_offcycle, $name, $aInput, $aMapWGTYP, $aemp, $aMapEmpOrg, $evtda, $begda, $percentage);
        return array('success' => "Success.<br/>" . $sReturn);
    }

    public function gen_slip($id_offcycle) {
        ini_set("memory_limit", "512M");
        set_time_limit(0);
        $this->load->model('payroll/bank_transfer_m');
        $this->load->model('orgchart_m');
        $this->load->model('employee_m');
        $this->load->library('FPDFlib');
        $persg = $this->global_m->get_master_abbrev("3");
        $kv['PERSG'] = $this->common->getKVArr($persg, "SHORT");
        $persk = $this->global_m->get_master_abbrev("4");
        $kv['PERSK'] = $this->common->getKVArr($persk, "SHORT");
        $werks = $this->global_m->get_master_abbrev("5");
        $kv['WERKS'] = $this->common->getKVArr($werks, "SHORT");
        $aIDOffCycle = $this->offcycle_m->getOffCycle($id_offcycle);
        $aOffCycleDetail = $this->offcycle_m->getOffCycleDetail($id_offcycle);
        $aWGTYP = $this->offcycle_m->get_wgtype();
        $aMapWGTYP = array();
        if (!empty($aWGTYP)) {
            foreach ($aWGTYP as $row) {
                $aMapWGTYP[$row['WGTYP']] = $row;
            }
        }
        unset($aWGTYP);
        $aemp = [];
        foreach ($aOffCycleDetail as $aDetail) {
            $aemp[] = $aDetail['PERNR'];
        }
        $aEmpOrg = $this->global_m->getEmpOrgArrayDate($aemp, $aIDOffCycle['evtda']);
        $aMapEmpOrg = array();
        if (!empty($aEmpOrg)) {
            foreach ($aEmpOrg as $row) {
                $aMapEmpOrg[$row['PERNR']] = $row;
            }
        }
        unset($aEmpOrg);
        $zipname = "payslip/SLIP_OFFCYCLE_".$aIDOffCycle['id']. '.zip';
        if (is_file($zipname)==false) {
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);
            foreach ($aOffCycleDetail as $aDetail) {
                $aret = $this->slip_single($aIDOffCycle, $kv, $aMapWGTYP, $aDetail, $aMapEmpOrg[$aDetail['PERNR']],true);
                if(!empty($aret) && !empty($aret['pdf'])) {
                    $pernr = $aDetail['PERNR'];
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
                    $spath = "payslip/" . $pernr . "/" . $year . "/" . $aret['filename'];
                    $aret['pdf']->output('F', $spath);
                    $zip->addFile($spath);
    //                $btemps
                    //check folder pernr/tahun     
                    // pernr/period regular /tahun/ offcycle --- 2021-01.pdf / offcycle : 2021-01-01_CBLABLABLA.pdf
                    //save in directory employee
                }
            }
            $zip->close();
        }
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
    }

    public function slip_single($aIDOffCycle, $kv, $aWGTYP, $aDetail, $empOrg, $isRet = false) {
        ini_set("memory_limit", "512M");

        $banks = $this->bank_transfer_m->get_bank_transfer_by_pernr_bank_transfer($aIDOffCycle['id_bank_transfer'], $aDetail['PERNR']);
        if (empty($banks)) {
            echo __LINE__;
            exit;
            return null;
        }

        $addtl_info = null;
        $profile = array();
        $filename = "OFFCYCLE_" . $aIDOffCycle['name'];
        $profile['periode_text'] = $aIDOffCycle['name'];
        $profile['PERNR'] = $aDetail['PERNR'];
        $master_emp = $this->employee_m->get_master_emp_single($aDetail['PERNR']);
        $profile['CNAME'] = $master_emp['CNAME'];
        if (empty($empOrg['PLANS'])) {
            $profile['PLANS'] = "-";
        } else {
            $plans = $this->orgchart_m->get_master_org($empOrg['PLANS'], 'S');
            if (empty($plans)) {
                $profile['PLANS'] = "-";
            } else {
                $profile['PLANS'] = $plans['STEXT'];
            }
        }
        if (empty($empOrg['ORGEH'])) {
            $profile['ORGEH'] = "-";
        } else {
            $orgeh = $this->orgchart_m->get_master_org($empOrg['ORGEH'], 'O');
            if (empty($orgeh)) {
                $profile['ORGEH'] = "-";
            } else {
                $profile['ORGEH'] = $orgeh['STEXT'] . " (" . $orgeh['SHORT'] . ")";
            }
        }
        if (empty($empOrg['PERSG'])) {
            $profile['PERSG'] = "-";
        } else {
            $persg = $kv['PERSG'][$empOrg['PERSG']]['STEXT']; //$this->global_m->get_master_abbrev("3", "AND SHORT='" . $empOrg['PERSG'] . "'");
            if (empty($persg)) {
                $profile['PERSG'] = "-";
            } else {
                $profile['PERSG'] = $persg;
            }
        }

        if (empty($empOrg['PERSK'])) {
            $profile['PERSK'] = "-";
        } else {
            $persk = $kv['PERSK'][$empOrg['PERSK']]['STEXT']; //$this->global_m->get_master_abbrev("4", "AND SHORT='" . $empOrg['PERSK'] . "'");
            if (empty($persk)) {
                $profile['PERSK'] = "-";
            } else {
                $profile['PERSK'] = $persk; //$persk[0]['STEXT'];
            }
        }

        if (empty($empOrg['WERKS'])) {
            $profile['WERKS'] = "-";
        } else {
            $werks = $kv['WERKS'][$empOrg['WERKS']]['STEXT']; //$this->global_m->get_master_abbrev("4", "AND SHORT='" . $empOrg['PERSK'] . "'");
            if (empty($werks)) {
                $profile['WERKS'] = "-";
            } else {
                $profile['WERKS'] = $werks;
            }
        }
        $aSlipPlus = array();
        $aSlipMinus = array();
        $aSlipComp = array();
        $thp = 0;
        $sumPlus = 0;
        $sumMinus = 0;
        $sumComp = 0;
        $wgtyp = $aWGTYP[$aDetail['WGTYP']];
        if ($wgtyp['PRTYP'] == '+') {
            $aSlipPlus[] = $aDetail;
            $sumPlus = $sumPlus + $aDetail['WAMNT'];
        } else if ($wgtyp['PRTYP'] == '-') {
            $aSlipMinus[] = $aDetail;
            $sumMinus = $sumMinus + $aDetail['WAMNT'];
        }
        $thp = $sumPlus;
        $max_line_slip = max(count($aSlipPlus), count($aSlipMinus), count($aSlipComp));

        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();

        $pdf->Image('http://localhost/hcis_gdps/img/Beyond_Care.png', 10, 2, 30, 0, 'PNG');
        $pdf->Image('http://localhost/hcis_gdps/img/gdps_logo_white.png', 210, 0, 60, 0, 'PNG');
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
                $aslip = $this->keyValSlip($aWGTYP, $aSlipPlus[$i]);
            }
            $pdf->Cell(60, 5, $aslip['str'], 1, 0, 'L');
            $pdf->Cell(28, 5, $aslip['amount'], 1, 0, 'R');
            if (empty($aSlipMinus[$i])) {
                $aslip = $aslipDefault;
            } else {
                $aslip = $this->keyValSlip($aWGTYP, $aSlipMinus[$i]);
            }
            $pdf->Cell(60, 5, $aslip['str'], 1, 0, 'L');
            $pdf->Cell(28, 5, $aslip['amount'], 1, 0, 'R');
            if (empty($aSlipComp[$i])) {
                $aslip = $aslipDefault;
            } else {
                $aslip = $this->keyValSlip($aWGTYP, $aSlipComp[$i]);
            }
            $pdf->Cell(60, 5, $aslip['str'], 1, 0, 'L');
            $pdf->Cell(28, 5, $aslip['amount'], 1, 1, 'R');
        }

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
                $pdf->Cell(60, 5, $bank["BANK_NAME"], 1, 0, 'L');
                $pdf->Cell(70, 5, $bank["BANK_PAYEE"], 1, 0, 'L');
                $pdf->Cell(60, 5, $bank["BANK_ACCOUNT"], 1, 0, 'L');
                $pdf->Cell(74, 5, number_format($bank["WAMNT"], 0, ".", ","), 1, 1, 'R');
            }
        }

        if ($isRet) {
            return array('pdf' => $pdf, 'year' => substr($aDetail['BEGDA'],0,4), 'filename' => $filename . ".pdf");
        } else {
            $pdf->Output();
            exit;
        }
    }

    private function keyValSlip($aWGTYP, $obj) {
        $aRet = array("str" => "", "amount" => "");
        if (empty($obj)) {
            return $aRet;
        }
        $aRet['str'] = $aWGTYP[$obj['WGTYP']]['LGTXT'];
        $aRet['amount'] = number_format($obj['WAMNT'], 0, ".", ",");
        return $aRet;
    }

}

?>