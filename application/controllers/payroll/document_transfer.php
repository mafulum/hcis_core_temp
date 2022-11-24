<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author Garuda
 */
class document_transfer extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('payroll/bank_transfer_m');
        $this->load->model('payroll/document_transfer_m');
        $this->load->model('pa/bank_m');
    }

    function index() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/document_transfer';
        $data['adt'] = $this->document_transfer_m->getDocumentTransfer();
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['scriptJS'] = '<script type="text/javascript">
		$(document).ready(function() {
                    oSummaryTable = $("#summary-table").dataTable();
		});
		</script>';
        $this->load->view('main', $data);
    }

    public function process($id = null) {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/document_transfer_process';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-datepicker/css/datepicker.css" />';

        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['externalJS'] .= '<script src="' . base_url() . 'assets/jquery.blockUI.js"></script>';
        $aBTSUndocumented = $this->bank_transfer_m->getStageUndocumented();
        $data['id_document_transfer'] = $id;
        $sDataTables = '';

        if (!empty($id)) {
            $data['dt'] = $this->document_transfer_m->getDocumentTransfer($id);
            $data['stages'] = $this->bank_transfer_m->getStagesTextDocTransfer($id);
            $hostBanks = $this->document_transfer_m->getHostBank($data['dt']['created_at']);
            $aIDHostbank = array();
            $aDocBankContent = array();
            foreach ($hostBanks as $row) {
                $aIDHostbank[$row['id_bank']] = $row;
                $aDocBankContent[$row['id_bank']] = $this->document_transfer_m->getContent($id, $row['id'], $row['id_bank']);
                if (!empty($aDocBankContent[$row['id_bank']])) {
                    $sDataTables .= '$("#bt_stage_' . $row['id_bank'] . '").dataTable();';
                }
                if ($row['is_other'] == 1) {
                    $aIDHostbank['others'] = $row;
                    $aDocBankContent['others'] = $this->document_transfer_m->getOthersContent($id, $row['id'], $row['id_bank']);
                    if (!empty($aDocBankContent['others'])) {
                        $sDataTables .= '$("#bt_stage_others").dataTable();';
                    }
                }
            }
            $data['aIDHostbank'] = $aIDHostbank;
            $data['aDocBankContent'] = $aDocBankContent;
            $data['bto'] = $this->bank_transfer_m->getSummaryBankTransferOther($id);
            $data['bte'] = $this->bank_transfer_m->getDisplayBankTransferedEmp($id);
            $data['emp_transfer_abkrs'] = $this->bank_transfer_m->getEmpTransferWithABKRS($data['dt']['id_bts_codes']);
            $data['kv_eta'] = $this->common->getKVArr($data['emp_transfer_abkrs'], 'MAPID');
        }

        $data['scriptJS'] = '<script type="text/javascript">
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
                ' . $sDataTables . '
                $("#fBTS").select2({
                    data: ' . json_encode($aBTSUndocumented) . ',
                    formatResult : format,
                    multiple: true,
                    dropdownAutoWidth: true
                });    
            });
            </script>';

        $this->load->view('main', $data);
    }

    public function act_process() {
        $data['name'] = $this->input->post('name');
        $data['bts_code'] = $this->input->post('fBTS');
        $data['confirm'] = $this->input->post('confirm');
        $data['review'] = $this->input->post('review');
        $data['id_document_transfer'] = null;
        $data['emp_transfer'] = $this->bank_transfer_m->getEmpTransfer($data['bts_code']);
        $data['emp_transfer_abkrs'] = $this->bank_transfer_m->getEmpTransferWithABKRS($data['bts_code']);
        if ($data['confirm']) {
            $temp = $this->document_transfer_m->confirm_to_db($data['emp_transfer'], $data['name'], $data['bts_code']);
            $data = array_merge($data, $temp);
        }

        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/document_transfer_process';
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-datepicker/css/datepicker.css" />';

        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['externalJS'] .= '<script src="' . base_url() . 'assets/jquery.blockUI.js"></script>';
        $this->load->model('employee_m');
        $aBTSUndocumented = $this->bank_transfer_m->getStageUndocumented();
        $data['scriptJS'] = '<script type="text/javascript">
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
                    $("#fBTS").select2({
                        data: ' . json_encode($aBTSUndocumented) . ',
                        formatResult : format,
                        multiple: true,
                        dropdownAutoWidth: true
                    }); 
		});
		</script>';
        $this->load->view('main', $data);
    }

    public function download_bank_tranfer($id_document) {
        $doc_transfer = $this->document_transfer_m->getDocumentTransfer($id_document);
        $emp_transfer_abkrs = $this->bank_transfer_m->getEmpTransferWithABKRS($doc_transfer['id_bts_codes']);
        $kv_eta = $this->common->getKVArr($emp_transfer_abkrs, 'MAPID');
        $dt = Datetime::createFromFormat("Y-m-d H:i:s", $doc_transfer['created_at']);
        $sYearMonth = $dt->format("Ym");
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
//        $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->applyFromArray(
//                array('text' => array(
//                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
//                        'color' => array('argb' => 'FFCCFFCC')
//                    ),
//                    'borders' => array(
//                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK),
//                    )
//                )
//        );
//        $objPHPExcel->getActiveSheet()->getStyle('A2:A2')->applyFromArray(
//                array('text' => array(
//                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
//                        'color' => array('argb' => 'FFFFCCCC')
//                    )
//                )
//        );
//        $objPHPExcel->getActiveSheet()->getStyle('A3:A3')->applyFromArray(
//                array('text' => array(
//                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
//                        'color' => array('argb' => 'FFCCFFCC')
//                    )
//                )
//        );

        $objPHPExcel->getActiveSheet()->setCellValue("A1", "No")
                ->setCellValue("B1", "ABKRS")
                ->setCellValue("C1", "PERNR")
                ->setCellValue("D1", "Bank Name")
                ->setCellValue("E1", "Bank Payee")
                ->setCellValue("F1", "Bank Account")
                ->setCellValue("G1", "Bank Amount")
                ->setCellValue("H1", "Kode OY")
                ->setCellValue("I1", "Kode Mandiri");
        if (!empty($emp_transfer_abkrs)) {
            $rn = 2;
            $num = 1;
            $a_kode_mandiri = [];
            $a_kode_oy = [];
            foreach ($emp_transfer_abkrs as $row) {
                if(empty($a_kode_mandiri[$row['BANK_ID']])){
                    $kode_mandiri = $this->bank_m->get_kode_bic_cms_mandiri($row['BANK_ID']);
                    $a_kode_mandiri[$row['BANK_ID']] = $kode_mandiri; 
                }else{
                    $kode_mandiri = $a_kode_mandiri[$row['BANK_ID']];
                }
                
                if(empty($a_kode_oy[$row['BANK_ID']])){
                    $kode_oy = $this->bank_m->get_kode_oy($row['BANK_ID']);
                    $a_kode_oy[$row['BANK_ID']] = $kode_oy; 
                }else{
                    $kode_oy = $a_kode_oy[$row['BANK_ID']];
                }
                
                $objPHPExcel->getActiveSheet()->setCellValue("A" . $rn, "" . $num)
                        ->setCellValue("B" . $rn, $row['ABKRS'])
                        ->setCellValue("C" . $rn, $row['PERNR'])
                        ->setCellValue("D" . $rn, $row['BANK_NAME'])
                        ->setCellValue("E" . $rn, $row['BANK_PAYEE'])
                        ->setCellValueExplicit("F" . $rn, $row['BANK_ACCOUNT'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("G" . $rn, $row['SUM_WAMNT'])
                        ->setCellValueExplicit("H" . $rn, $kode_oy, PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("I" . $rn, $kode_mandiri, PHPExcel_Cell_DataType::TYPE_STRING);
                $rn++;
                $num++;
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $filename =  $doc_transfer['name'] . "_BRI_" . date("%Y%m%d%H%i%s") . ".xlsx";
        $filename = "BANK_TRANSFER_REPORT_" . $id_document . ".xlsx";
        $objWriter->save('doc_transfers/' . $filename);
        if (ob_get_length()) ob_end_clean();
        // ob_clean();
        //content type
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile('doc_transfers/' . $filename);
    }

    public function download_bank_tranfer_cms($id_document, $bank_id) {
        $doc_transfer = $this->document_transfer_m->getDocumentTransfer($id_document);
        $host_bank = $this->document_transfer_m->getHostBankByBankID($bank_id, $doc_transfer['created_at']);
        $emp_transfer_abkrs = $this->bank_transfer_m->getEmpTransferWithABKRS($doc_transfer['id_bts_codes']);
        $kv_eta = $this->common->getKVArr($emp_transfer_abkrs, 'MAPID');
        if (empty($host_bank)) {
            return null;
        }
        $document_transfer_bank = $this->document_transfer_m->getDocumentTransferBank($id_document, $host_bank['id']);
        if (empty($document_transfer_bank)) {
            //generate_file
            $docTransfer = $this->document_transfer_m->getContent($id_document, $host_bank['id']);
            //save to document_transfer_bank
            $document_transfer_bank_id = $this->document_transfer_m->saveDocumentTransferBank($id_document, $host_bank['id']);
            $document_transfer_bank = $this->document_transfer_m->getDocumentTransferBankByID($document_transfer_bank_id);
            $function = "generate_document_cms_" . $bank_id;
            $filename = $this->$function($doc_transfer, $document_transfer_bank, $docTransfer, $kv_eta);
            if (empty($filename)) {
                return null;
            }
            $this->document_transfer_m->updateDocumentCMSBank($document_transfer_bank_id, $filename, $docTransfer);
        } else if (!empty($document_transfer_bank) && empty($document_transfer_bank['generated_cms_file'])) {
            //genreate file
            $docTransfer = $this->document_transfer_m->getContent($id_document, $host_bank['id']);
            $function = "generate_document_cms_" . $bank_id;
            $filename = $this->$function($doc_transfer, $document_transfer_bank, $docTransfer, $kv_eta);
            //save to document_transfer_bank
            if (empty($filename)) {
                return null;
            }
            $this->document_transfer_m->updateDocumentCMSBank($document_transfer_bank['id'], $filename);
        } else if (!empty($document_transfer_bank) && empty($document_transfer_bank['generated_cms_file'])) {
            $filename = $document_transfer_bank['generated_file'];
        }
        if (empty($filename)) {
            return null;
        }
        if (ob_get_length()) ob_end_clean();
        // ob_clean();
        //content type
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile('doc_transfers/' . $filename);
    }

    public function download_document_tranfer($id_document, $bank_id) {
        $doc_transfer = $this->document_transfer_m->getDocumentTransfer($id_document);
        $host_bank = $this->document_transfer_m->getHostBankByBankID($bank_id, $doc_transfer['created_at']);
        $emp_transfer_abkrs = $this->bank_transfer_m->getEmpTransferWithABKRS($doc_transfer['id_bts_codes']);
        $kv_eta = $this->common->getKVArr($emp_transfer_abkrs, 'MAPID');
        if (empty($host_bank)) {
            return null;
        }
        $document_transfer_bank = $this->document_transfer_m->getDocumentTransferBank($id_document, $host_bank['id']);
        if (empty($document_transfer_bank)) {
            //generate_file
            $docTransfer = $this->document_transfer_m->getContent($id_document, $host_bank['id'], $bank_id);
            $docOtherTransfer = null;
            if ($host_bank['is_other'] == 1) {
                $docOtherTransfer = $this->document_transfer_m->getOthersContent($id_document, $host_bank['id'], $bank_id);
            }
            //save to document_transfer_bank
            $document_transfer_bank_id = $this->document_transfer_m->saveDocumentTransferBank($id_document, $host_bank['id']);
            $document_transfer_bank = $this->document_transfer_m->getDocumentTransferBankByID($document_transfer_bank_id);
            $function = "generate_document_transfer_" . $bank_id;
            $filename = $this->$function($doc_transfer, $document_transfer_bank, $docTransfer, $docOtherTransfer, $kv_eta);
            if (empty($filename)) {
                return null;
            }
            $this->document_transfer_m->updateDocumentTransferBank($document_transfer_bank_id, $filename, $docTransfer, $docOtherTransfer);
        } else if (!empty($document_transfer_bank) && empty($document_transfer_bank['generated_file'])) {
            //genreate file
            $docTransfer = $this->document_transfer_m->getContent($id_document, $host_bank['id'], $bank_id);
            $docOtherTransfer = null;
            if ($host_bank['is_other'] == 1) {
                $docOtherTransfer = $this->document_transfer_m->getOthersContent($id_document, $host_bank['id'], $bank_id);
            }
            $function = "generate_document_transfer_" . $bank_id;
            $filename = $this->$function($doc_transfer, $document_transfer_bank, $docTransfer, $docOtherTransfer, $kv_eta);
            //save to document_transfer_bank
            if (empty($filename)) {
                return null;
            }
            $this->document_transfer_m->updateDocumentTransferBank($document_transfer_bank['id'], $filename);
        } else if (!empty($document_transfer_bank) && empty($document_transfer_bank['generated_file'])) {
            $filename = $document_transfer_bank['generated_file'];
        }
        if (empty($filename)) {
            return null;
        }
        if (ob_get_length()) ob_end_clean();
        // ob_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile('doc_transfers/' . $filename);
    }

    private function generate_document_cms_1($doc_transfer, $doc_transfer_bank, $empTransfer = null, $kv_eta = null) {
        $dt = Datetime::createFromFormat("Y-m-d H:i:s", $doc_transfer['created_at']);
        $sYMD = $dt->format("Ymd");
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "P")->setCellValue("B1", $sYMD)->setCellValueExplicit("C1", "1550017117006", PHPExcel_Cell_DataType::TYPE_STRING)->setCellValue("D1", count($empTransfer));
        $sum = 0;
        $rn = 2;
        $aKey = [];
        foreach ($empTransfer as $row) {
            $sum = $sum + $row['WAMNT'];
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A" . $rn, $row['BANK_ACCOUNT'], PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue("B" . $rn, $row['BANK_PAYEE'])
                    ->setCellValue("C" . $rn, "Jakarta")
                    ->setCellValue("D" . $rn, "Jakarta")
                    ->setCellValue("E" . $rn, "0300")
                    ->setCellValue("F" . $rn, "IDR")
                    ->setCellValue("G" . $rn, $row['WAMNT']);
            if ($row['BANK_ID'] == "1") {
                $objPHPExcel->getActiveSheet()->setCellValue("J" . $rn, "IBU")
                        ->setCellValue("K" . $rn, "BMRIIDJA")
                        ->setCellValue("L" . $rn, "MANDIRI")
                        ->setCellValue("M" . $rn, "Jakarta");
            } else {
                if (empty($aKey[$row['BANK_ID']])) {
                    $aKey[$row['BANK_ID']] = $this->bank_m->get_kode_bic_cms_mandiri($row['BANK_ID']);
                }
                $objPHPExcel->getActiveSheet()->setCellValue("J" . $rn, "OBU")
                        ->setCellValue("K" . $rn, $aKey[$row['BANK_ID']])
                        ->setCellValue("L" . $rn, $row['BANK_NAME'])
                        ->setCellValue("M" . $rn, "Jakarta");
            }

            $objPHPExcel->getActiveSheet()->setCellValue("Q" . $rn, "Y")
                    ->setCellValue("R" . $rn, "Finance@garudapratama.com")
                    ->setCellValue("V" . $rn, "Y")
                    ->setCellValue("AM" . $rn, "OUR")
                    ->setCellValue("AN" . $rn, "1")
                    ->setCellValue("AO" . $rn, "E");
            $rn++;
        }
        $objPHPExcel->getActiveSheet()->setCellValue("E1", $sum);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $filename = $doc_transfer['name'] . "_MANDIRI_CMS.xlsx";
        $objWriter->save('doc_transfers/' . $filename);
        return $filename;
    }

    private function generate_document_transfer_3($doc_transfer, $doc_transfer_bank, $empTransfer = null, $empOtherTransfer = null, $kv_eta = null) {
        $dt = Datetime::createFromFormat("Y-m-d H:i:s", $doc_transfer['created_at']);
        $sYearMonth = $dt->format("Ym");
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->applyFromArray(
                array('text' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('argb' => 'FFCCFFCC')
                    ),
                    'borders' => array(
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK),
                    )
                )
        );
        $objPHPExcel->getActiveSheet()->getStyle('A2:A2')->applyFromArray(
                array('text' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('argb' => 'FFFFCCCC')
                    )
                )
        );
        $objPHPExcel->getActiveSheet()->getStyle('A3:A3')->applyFromArray(
                array('text' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('argb' => 'FFCCFFCC')
                    )
                )
        );
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "Converter Mass FT CMS BRI")
                ->setCellValue("A2", "Mandatory")
                ->setCellValue("A3", "Optional");

        $objPHPExcel->getActiveSheet()->mergeCells('A5:A6');
        $objPHPExcel->getActiveSheet()->mergeCells('C5:E5');
        $objPHPExcel->getActiveSheet()->mergeCells('F5:L5');

        $objPHPExcel->getActiveSheet()->setCellValue("A5", "No")
                ->setCellValue("B5", "Sender Information")
                ->setCellValue("C5", "Beneficiary Information")
                ->setCellValue("F5", "Transaction Information")
                ->setCellValue("B6", "Account Number")
                ->setCellValue("C6", "Account Number")
                ->setCellValue("D6", "Account Name")
                ->setCellValue("E6", "eMail Address")
                ->setCellValue("F6", "Amount")
                ->setCellValue("G6", "Currency")
                ->setCellValue("H6", "Charge Type")
                ->setCellValue("I6", "Voucher Code")
                ->setCellValue("J6", "BI Trx Code")
                ->setCellValue("K6", "Remark")
                ->setCellValue("L6", "Ref Number")
                ->setCellValue("N6", "NOPEG")
                ->setCellValue("O6", "DINAS");
        if (!empty($empTransfer)) {
            $rn = 7;
            $num = 1;
            foreach ($empTransfer as $row) {
                $pernr = "";
                $abkrs = "";
                $mapid = $row['BANK_ID'] . "_" . $row['BANK_ACCOUNT'];
                if (!empty($kv_eta) && !empty($kv_eta[$mapid])) {
                    $pernr = $kv_eta[$mapid]['PERNR'];
                    $abkrs = $kv_eta[$mapid]['ABKRS'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue("A" . $rn, "" . $num)
                        ->setCellValueExplicit("B" . $rn, "144201000003304", PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $rn, $row['BANK_ACCOUNT'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("D" . $rn, $row['BANK_PAYEE'])
                        ->setCellValue("E" . $rn, "finance@garudapratama.com")
                        ->setCellValue("F" . $rn, $row['WAMNT'])
                        ->setCellValue("G" . $rn, "IDR")
                        ->setCellValue("H" . $rn, "OUR")
                        ->setCellValue("J" . $rn, "150")
                        ->setCellValue("K" . $rn, $doc_transfer['remark'])
                        ->setCellValue("L" . $rn, $sYearMonth . "1E")
                        ->setCellValue("N" . $rn, $pernr)
                        ->setCellValue("O" . $rn, $abkrs);
                $rn++;
                $num++;
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $filename =  $doc_transfer['name'] . "_BRI_" . date("%Y%m%d%H%i%s") . ".xlsx";
        $filename = $doc_transfer['name'] . "_BRI.xlsx";
        $objWriter->save('doc_transfers/' . $filename);
        return $filename;
    }

    private function generate_document_transfer_2($doc_transfer, $doc_transfer_bank, $empTransfer = null, $empOtherTransfer = null, $kv_eta = null) {
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "NOPEG")
                ->setCellValue("B1", "NAMA")
                ->setCellValue("C1", "REK")
                ->setCellValue("D1", "NOMINAL")
                ->setCellValue("F1", "NOPEG")
                ->setCellValue("G1", "DINAS");
        if (!empty($empTransfer)) {
            $rn = 2;
            $num = 1;
            foreach ($empTransfer as $row) {
                $pernr = "";
                $abkrs = "";
                $mapid = $row['BANK_ID'] . "_" . $row['BANK_ACCOUNT'];
                if (!empty($kv_eta) && !empty($kv_eta[$mapid])) {
                    $pernr = $kv_eta[$mapid]['PERNR'];
                    $abkrs = $kv_eta[$mapid]['ABKRS'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue("A" . $rn, "" . $num)
                        ->setCellValueExplicit("B" . $rn, $row['BANK_PAYEE'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $rn, $row['BANK_ACCOUNT'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("D" . $rn, $row['WAMNT'])
                        ->setCellValue("F" . $rn, $pernr)
                        ->setCellValue("G" . $rn, $abkrs);
                $rn++;
                $num++;
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $filename =  $doc_transfer['name'] . "_BNI_"  . date("%Y%m%d%H%i%s") . ".xlsx";
        $filename = $doc_transfer['name'] . "_BNI.xlsx";
        $objWriter->save('doc_transfers/' . $filename);
        return $filename;
    }

    private function generate_document_transfer_1($doc_transfer, $doc_transfer_bank, $empTransfer = null, $empOtherTransfer = null, $kv_eta = null) {
        $dt = Datetime::createFromFormat("Y-m-d H:i:s", $doc_transfer['created_at']);
        $sYearMonth = $dt->format("Ym");
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle("MANDIRI");
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->setCellValue("A1", $sYearMonth);
        $objPHPExcel->getActiveSheet()->setCellValue("A2", "REKENING")
                ->setCellValue("B2", "PLUS")
                ->setCellValue("C2", "NOMINAL")
                ->setCellValue("D2", "CD")
                ->setCellValue("E2", "NO")
                ->setCellValue("F2", "NAMA")
                ->setCellValue("G2", "KETERANGAN")
                ->setCellValue("H2", "REKENING")
                ->setCellValue("J2", "NOPEG")
                ->setCellValue("K2", "DINAS");
        if (!empty($empTransfer)) {
            $rn = 3;
            $num = 1;
            foreach ($empTransfer as $row) {
                $pernr = "";
                $abkrs = "";
                $mapid = $row['BANK_ID'] . "_" . $row['BANK_ACCOUNT'];
                if (!empty($kv_eta) && !empty($kv_eta[$mapid])) {
                    $pernr = $kv_eta[$mapid]['PERNR'];
                    $abkrs = $kv_eta[$mapid]['ABKRS'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue("E" . $rn, "" . $num)
                        ->setCellValue("B" . $rn, "+")
                        ->setCellValue("D" . $rn, "C")
                        ->setCellValueExplicit("F" . $rn, $row['BANK_PAYEE'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("A" . $rn, $row['BANK_ACCOUNT'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("C" . $rn, $row['WAMNT'])
                        ->setCellValue("J" . $rn, $pernr)
                        ->setCellValue("K" . $rn, $abkrs);
                $rn++;
                $num++;
            }
        }
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle("NON MANDIRI");
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->setCellValue("A1", $sYearMonth);
        $objPHPExcel->getActiveSheet()->setCellValue("A2", "REKENING")
                ->setCellValue("B2", "PLUS")
                ->setCellValue("C2", "NOMINAL")
                ->setCellValue("D2", "CD")
                ->setCellValue("E2", "NO")
                ->setCellValue("F2", "NAMA")
                ->setCellValue("G2", "KETERANGAN")
                ->setCellValue("H2", "REKENING")
                ->setCellValue("J2", "NOPEG")
                ->setCellValue("K2", "DINAS");
        if (!empty($empOtherTransfer)) {
            $rn = 3;
            $num = 1;
            foreach ($empOtherTransfer as $row) {
                $pernr = "";
                $abkrs = "";
                $mapid = $row['BANK_ID'] . "_" . $row['BANK_ACCOUNT'];
                if (!empty($kv_eta) && !empty($kv_eta[$mapid])) {
                    $pernr = $kv_eta[$mapid]['PERNR'];
                    $abkrs = $kv_eta[$mapid]['ABKRS'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue("E" . $rn, "" . $num)
                        ->setCellValue("B" . $rn, "+")
                        ->setCellValue("D" . $rn, "C")
                        ->setCellValue("G" . $rn, $row['BANK_NAME'])
                        ->setCellValueExplicit("F" . $rn, $row['BANK_PAYEE'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("A" . $rn, $row['BANK_ACCOUNT'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("C" . $rn, $row['WAMNT'], PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("J" . $rn, $pernr)
                        ->setCellValue("K" . $rn, $abkrs);
                $rn++;
                $num++;
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $filename = $doc_transfer['name'] . "_MANDIRI_" . date("%Y%m%d%H%i%s") . ".xlsx";
        $filename = $doc_transfer['name'] . "_MANDIRI.xlsx";
        $objWriter->save('doc_transfers/' . $filename);
        return $filename;
    }

}

?>