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
class in_out extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('payroll/in_out_m');
    }

    function index() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/in_out';
        $data['io'] = $this->in_out_m->getInOut();
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
        $data['view'] = 'payroll/in_out_process';
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
                        $filename = "inout_advpayment_" . date("Ymd_Hi") . '.xls';
                        //copy file
                        $this->load->library('upload', array('upload_path' => $dir, 'overwrite' => true, 'allowed_types' => 'xls', 'remove_spaces' => true, 'file_name' => $filename));
                        $resUpload = $this->upload->do_upload('fupload');
                        if ($resUpload == false) {
                            $sError = $this->upload->display_errors();
                        }
                    }
                    $aMapADDPAYMENT = null;
                    if (empty($sError)) {
                        $this->load->model('pa_payroll/addpayment_m');
                        $this->load->model('payroll/in_out_m');
			$aMapADDPAYMENT = $this->addpayment_m->get_list_for_inout($begda, $evtda);
			//var_dump($aMapADDPAYMENT);exit;
                        if (empty($aMapADDPAYMENT)) {
                            $sError = "<br\>Data Add Payment with reference those begda and evtda not found";
                        } else {
			    $aTemp = $this->in_out_m->sanitize_add_payment($aMapADDPAYMENT);
			    //var_dump($aTemp);exit;
                            if (!empty($aTemp['error'])) {
                                $sError = $aTemp['error'];
                            } else if (!empty($aTemp['source'])) {
                                $aMapADDPAYMENT = $aTemp['source'];
                            }
                        }
                    }
                    if (empty($sError)) {
			    $aTemp = $this->load($dir . $filename, $name, $begda, $evtda, $aMapADDPAYMENT);
			    //var_dump($aTemp);exit;
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
	//var_dump($data);exit;
	//echo $data['sError'];exit;
        $this->load->view('main', $data);
    }

    function load($filename, $name, $begda, $evtda, $aMapAddPayment) {
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
        $aStringHeader = array("PERNR", "WGTYP", "WAMNT", "BEGDA", "EVTDA", "NOTE");
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
        if ($MAX_ROW == 1) {
            return "<b>ERROR</b> : <br/><br/>" . "Does not have data";
        }
        $sError = "";
        $aInput = array();
        $aWGTYP = $this->in_out_m->get_wgtype($aMapAddPayment);
        $aMapWGTYP = array();
        if (!empty($aWGTYP)) {
            foreach ($aWGTYP as $row) {
                $aMapWGTYP[$row['WGTYP']] = $row;
            }
        }
        unset($aWGTYP);
        $aemp = array();
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            $aData['PERNR'] = $this->reader->sheets[0]['cells'][$i][1];
            $aData['WGTYP'] = $this->reader->sheets[0]['cells'][$i][2];
            $aData['WAMNT'] = $this->reader->sheets[0]['cells'][$i][3];
            $aData['BEGDA'] = $this->reader->sheets[0]['cells'][$i][4];
            $aData['EVTDA'] = $this->reader->sheets[0]['cells'][$i][5];
            if (isset($this->reader->sheets[0]['cells'][$i][6])) {
                $aData['NOTE'] = $this->reader->sheets[0]['cells'][$i][6];
            }
            //pengecekan error 
            $aMandatory = array("PERNR", "WGTYP", "WAMNT", "BEGDA", "EVTDA");
            foreach ($aMandatory as $mandatory) {
                if (empty($aData[$mandatory])) {
                    $sError .= $mandatory . " empty @ row $i<br/>";
                }
            }
            $flag_found_pair = false;
            foreach($aMapAddPayment as $keyMap=>$addPayment){
                if (!empty($addPayment['found'])) {
                    continue;
                }
                if ($aData['PERNR'] == $addPayment['PERNR'] &&
                        $aData['WGTYP'] == $addPayment['WGTYP'] &&
                        $aData['WAMNT'] == $addPayment['WAMNT'] &&
                        $aData['BEGDA'] == $addPayment['BEGDA'] &&
                        $aData['EVTDA'] == $addPayment['EVTDA'] &&
                        $aData['NOTE'] == $addPayment['NOTE']) {
                    $flag_found_pair = true;
                    $aMapAddPayment[$keyMap]['found'] = true;
                    if (in_array($aData['PERNR'], $aemp) == false) {
                        $aemp[] = $aData['PERNR'];
                    }
                }
            }
//            for ($j = 0; $j < count($aMapAddPayment); $j++) {
//                if (!empty($aMapAddPayment[$j]['found'])) {
//                    continue;
//                }
//                if ($aData['PERNR'] == $aMapAddPayment[$j]['PERNR'] &&
//                        $aData['WGTYP'] == $aMapAddPayment[$j]['WGTYP'] &&
//                        $aData['WAMNT'] == $aMapAddPayment[$j]['WAMNT'] &&
//                        $aData['BEGDA'] == $aMapAddPayment[$j]['BEGDA'] &&
//                        $aData['EVTDA'] == $aMapAddPayment[$j]['EVTDA'] &&
//                        $aData['NOTE'] == $aMapAddPayment[$j]['NOTE']) {
//                    $flag_found_pair = true;
//                    $aMapAddPayment[$j]['found'] = true;
//                    if (in_array($aData['PERNR'], $aemp) == false) {
//                        $aemp[] = $aData['PERNR'];
//                    }
//                }
//            }
            if (!$flag_found_pair) {
                $sError .= " Not Found Pair on AddPayment @ row $i <br/>";
            }
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
        $id_inout = $this->in_out_m->createInOut($name, $begda, $evtda, $filename);
        $sReturn = "<table border='1' cellpadding='0' cellspacing='0'u><tr>";
        foreach ($aStringHeader as $header) {
            $sReturn .= "<td>" . $header . "</td>";
        }
        $sReturn .= "<td>BALANCING WGTYP</td>";
        $sReturn .= "</tr>";
        for ($i = 0; $i < count($aInput); $i++) {
            $aIOP = $this->in_out_m->run_in_out($id_inout, $aInput[$i], $aMapEmpOrg[$aInput[$i]['PERNR']], $aMapWGTYP[$aInput[$i]['WGTYP']]);
            $sReturn .= "<tr>";
            foreach ($aStringHeader as $header) {
                $sReturn .= "<td>" . $aInput[$i][$header] . "</td>";
            }
            $sReturn .= "<td>" . $aIOP['wgtyp'] . " | " . $aIOP['id_iop'] . "</td>";
            $sReturn .= "</tr>";
            $aInput[$i]['iop'] = $aIOP;
        }
        $sReturn .= "</table>";
//        $idInOut, $name, $adata, $awgtyp, $aemp, $aMapEmpOrg, $evtdate, $begda) 
        $this->in_out_m->publishToBankTransfer($id_inout, $name, $aInput, $aMapWGTYP, $aemp, $aMapEmpOrg, $evtda, $begda);
        return array('success' => "Success.<br/>" . $sReturn);
    }

    public function act_process() {
        $this->load->model('payroll/bank_transfer_m');
        $id_bank_transfer = null;
        if ($this->input->post('id_bank_transfer')) {
            $id_bank_transfer = $this->input->post('id_bank_transfer');
        }
        $data['name_stage'] = $this->input->post('name_stage');
        $data['percentage'] = $this->input->post('percentage');
        if ($data['percentage'] > 100) {
            $data['percentage'] = 100;
        }
//        var_dump($_POST);exit;
        $percentage = $data['percentage'];
        $data['abkrs'] = $this->input->post('fABKRS');
//        var_dump($data['abkrs']);exit;
        $data['confirm'] = $this->input->post('confirm');
        $data['review'] = $this->input->post('review');
//        var_dump($data);exit;
        //CHECK IF ID BANK TRANSFER DEFINED
        if (!empty($id_bank_transfer)) {
            $data['id_bank_transfer'] = $id_bank_transfer;
            $data['bt'] = $this->bank_transfer_m->getBankTransfer($id_bank_transfer);
            $data['bto'] = $this->bank_transfer_m->getSummaryBankTransferOther($id_bank_transfer);
            $data['bte'] = $this->bank_transfer_m->getDisplayBankTransferedEmp($id_bank_transfer);
            $data['bts'] = $this->bank_transfer_m->getBankTransferStage($id_bank_transfer);
            $data['name'] = $this->input->post('name');
            $is_offcycle = $data['is_offcycle'] = $data['bt']['is_offcycle'];
            $data['date_offcycle'] = $date_off_cycle = $data['bt']['date_offcycle'];
            $data['period_regular'] = $period_regular = $data['bt']['periode_regular'];
        } else {
            $data['name'] = $this->input->post('name');
            $data['is_reg'] = $is_reg = $this->input->post('fIsReg');
            $data['date_offcycle'] = $date_off_cycle = $this->input->post('fDateOffCycle');
            $data['period_regular'] = $period_regular = $this->input->post('fPeriodRegular');
            $is_offcycle = $data['is_offcycle'] = 0;
            if ($is_reg == "off") {
                $is_offcycle = $data['is_offcycle'] = 1;
            }
        }
        $data['run_payroll'] = $this->bank_transfer_m->get_emp_transfer_from_running_payroll($is_offcycle, $date_off_cycle, $period_regular, $data['abkrs'], $id_bank_transfer);
        if (!empty($data['run_payroll']) && !empty($data['run_payroll']['transfer']) && empty($id_bank_transfer)) {
            $data['run_payroll']['transfer'] = $this->bank_transfer_m->calculate_emp_bank_transfer($data['run_payroll']['transfer'], $data['run_payroll']['transfer_pernr_thp'], $percentage);
        } else if (!empty($data['run_payroll']) && !empty($data['run_payroll']['transfer']) && $id_bank_transfer) {
            $data['run_payroll']['transfer'] = $this->bank_transfer_m->calculate_emp_bank_transfer($data['run_payroll']['transfer'], $data['run_payroll']['transfer_pernr_thp'], $percentage, $data['bte']);
//            var_dump($data['run_payroll']['transfer']);exit;
        }
        if ($data['confirm']) {
            $id = $this->bank_transfer_m->confirm_to_db($data, $id_bank_transfer);
            if (empty($id_bank_transfer)) {
                $data['id_bank_transfer'] = $id_bank_transfer = $id;
                $data['bt'] = $this->bank_transfer_m->getBankTransfer($id_bank_transfer);
                $data['bto'] = $this->bank_transfer_m->getSummaryBankTransferOther($id_bank_transfer);
            }
            $data['bts'] = $this->bank_transfer_m->getBankTransferStage($id_bank_transfer);
        }

        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/bank_transfer_process';
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
        $aABKRS = $this->employee_m->get_abkrs();
        $data['id_bank_transfer'] = $id_bank_transfer;
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
                    $("#fABKRS").select2({
                        data: ' . json_encode($aABKRS) . ',
                        formatResult : format,
                        multiple: true,
                        dropdownAutoWidth: true
                    });                    
                    oSummaryTable = $("#summary-table").dataTable();
                    oTable = $("#dynamic-table").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "CNAME"},
                            { mData: "GESCH"},
                            { mData: "GBDAT"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "PLANS"},
                            { mData: "ORGEH"},
                            { mData: "PERSG"},
                            { mData: "PERSK"},
                            { mData: "ABKRS"},
                            { mData: "org_short"},
                            { mData: "org_stext"},
                            { mData: "pos_stext"},
                            { mData: "TAXID"},
                            { mData: "DEPND"},
                            { mData: "BPJS_TK"},
                            { mData: "INSTY"},
                            { mData: "PRCTE"},
                            { mData: "PRCTC"},
                            { mData: "MAXRE"},
                            { mData: "MAXRC"},
                        ],
                        autofill: true,
                        select: true,
                        responsive: true,
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        dom: "Bfrtip",
                        buttons: [
                            "csv"
                        ]
                    });
                    
                    oTablePayrollEmp = $("#dynamic-table-payroll-emp").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "tname"},
                            { mData: "WAMNT"},
                        ],
                        autofill: true,
                        select: true,
                        responsive: true,
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        dom: "Bfrtip",
                        buttons: [
                            "csv"
                        ]
                    });
                    
                    oTablePayrollSlip = $("#dynamic-table-payroll-slip").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "tname"},
                            { mData: "WAMNT"},
                        ],
                        autofill: true,
                        select: true,
                        responsive: true,
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        dom: "Bfrtip",
                        buttons: [
                            "csv"
                        ]
                    });
                    
                    oTablePayrollBPJS = $("#dynamic-table-payroll-bpjs").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "tname"},
                            { mData: "WAMNT"},
                        ],
                        autofill: true,
                        select: true,
                        responsive: true,
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        dom: "Bfrtip",
                        buttons: [
                            "csv"
                        ]
                    });
                    
                    
                    oTablePayrollTax = $("#dynamic-table-payroll-tax").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "tname"},
                            { mData: "WAMNT"},
                        ],
                        autofill: true,
                        select: true,
                        responsive: true,
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        dom: "Bfrtip",
                        buttons: [
                            "csv"
                        ]
                    });
                    
                    
                    oTablePayrollAccrued = $("#dynamic-table-payroll-accrued").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "tname"},
                            { mData: "WAMNT"},
                        ],
                        autofill: true,
                        select: true,
                        responsive: true,
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        dom: "Bfrtip",
                        buttons: [
                            "csv"
                        ]
                    });
                    
                    
                    oTablePayrollBase = $("#dynamic-table-payroll-base").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "tname"},
                            { mData: "WAMNT"},
                        ],
                        autofill: true,
                        select: true,
                        responsive: true,
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        dom: "Bfrtip",
                        buttons: [
                            "csv"
                        ]
                    });
                    
                    $("#fProcess").click(function(){
                        blockPage("Please wait for a while estimate about 4-8 minutes");
                        vis_reg = "on";
                        if ($("#fIsReg").is(":checked"))
                        {
                            vis_reg = "on";
                        }else{
                            vis_reg = "off";
                        }
                        $.ajax({
                            url: "' . base_url() . '/index.php/payroll/simulation/go",
                            type: "get", //send it through get method
                            timeout : 0,
                            data: { 
                              is_reg: vis_reg,
                              date_offcyecle: $("#fDateOffCycle").val(),
                              period_regular: $("#fPeriodRegular").val(),
                              pernr: $("#fnik").val(), 
                              abkrs: $("#fABKRS").val(), 
                              persg: $("#fPERSG").val(),
                              persk: $("#fPERSK").val()
                            },
                            success: function(response) {
                                console.log(response.content)
                                setTimeout($.unblockUI, 500);
                                oSummaryTable.fnClearTable();
                                oSummaryTable.fnAddData(response.content.management_report);
                                oTable.fnClearTable();
                                oTable.fnAddData(response.content.employee);
                                oTablePayrollEmp.fnClearTable();
                                oTablePayrollEmp.fnAddData(response.content.payroll_employee);
                                oTablePayrollBPJS.fnClearTable();
                                oTablePayrollBPJS.fnAddData(response.content.payroll_bpjs);
                                oTablePayrollTax.fnClearTable();
                                oTablePayrollTax.fnAddData(response.content.payroll_tax);
                                oTablePayrollAccrued.fnClearTable();
                                oTablePayrollAccrued.fnAddData(response.content.payroll_accrued);
                                oTablePayrollBase.fnClearTable();
                                oTablePayrollBase.fnAddData(response.content.payroll_base);
                                oTablePayrollSlip.fnClearTable();
                                oTablePayrollSlip.fnAddData(response.content.payroll_slip);
                            },
                            error: function(xhr) {
                              //Do Something to handle error
                                blockPage("Error");
                                oSummaryTable.fnClearTable();
                                oTable.fnClearTable();
                                oTablePayrollEmp.fnClearTable();
                                oTablePayrollBPJS.fnClearTable();
                                oTablePayrollTax.fnClearTable();
                                oTablePayrollAccrued.fnClearTable();
                                oTablePayrollBase.fnClearTable();
                                oTablePayrollSlip.fnClearTable();
                                setTimeout($.unblockUI, 500);
                            }
                        });
                    });
                    $("#fDateOffCycle").datepicker();
                    $("#cPeriodRegular").datepicker({
                        autoclose: true
                    });
                    $("[data-toggle=\'switch\']").wrap(\'<div class="switch" />\').parent().bootstrapSwitch();
		});
		</script>';
        $this->load->view('main', $data);
    }

}

?>
