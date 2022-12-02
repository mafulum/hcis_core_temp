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
        $this->load->model('orgchart_m');
        $this->load->model('global_m');
        $this->load->model('payroll/slip_mail_m');
        $this->load->model('payroll/document_transfer_m');
        $this->load->model('pa_payroll/addtlinfo_m');
        $this->load->library('FPDFlib');
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

    public function generate_single_regular($id_document_transfer, $pernr, $abkrs, $id_bank_transfer = null, $isRet = false) {
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
//            echo __LINE__;exit;
            return null;
        }
        $banks = $this->bank_transfer_m->get_bank_transfer_by_pernr_bank_transfer($id_bank_transfer, $pernr, $abkrs);
        if(empty($banks)){
//            echo __LINE__;exit;
            return null;
        }
        $profile = $this->running_payroll_m->get_emp_profile_by_pernr_bank_transfer($id_bank_transfer, $pernr, $abkrs);
        if(empty($profile)){
//            echo __LINE__;exit;
            return null;
        }
//        $profile['CNAME']="-";
//        $profile['PERNR']='-';
        $payroll_running = $this->running_payroll_m->get_row_by_bank_transfer($id_bank_transfer);
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

    public function generate_slip_regular_single_file($id_document_transfer, $pernr, $abkrs, $id_bank_transfer) {
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
        $doc_transfer = $this->document_transfer_m->getDocumentTransfer($id_document_transfer);
        if (empty($doc_transfer)) {
            return null;
        }
        $bank_transfer_emps = $this->bank_transfer_m->getPernrAbkrsStagesByIDStages(explode(",", $doc_transfer['id_bts_codes']));
        $zipname = "payslip/SLIP_".$id_document_transfer.'_'.$doc_transfer['name']. '.zip';
        if (is_file($zipname)==false) {
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);
            foreach ($bank_transfer_emps as $btemps) {
                if (empty($btemps['PERNR'])) {
                    continue;
//                    var_dump($btemps);
//                    exit;
                }
                $aret = $this->generate_single_regular($id_document_transfer, $btemps['PERNR'], $btemps['ABKRS'], $btemps['id_bank_transfer'], true);
                if (!empty($aret) && !empty($aret['pdf'])) {
                    $pernr = $btemps['PERNR'];
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

}
