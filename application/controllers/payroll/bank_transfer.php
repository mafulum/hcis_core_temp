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
class bank_transfer extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
            $this->load->model('payroll/bank_transfer_m');
    }

    function index() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/bank_transfer';
        $data['abt'] = $this->bank_transfer_m->getBankTransfer();
        $data['externalCSS'] = '<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .= '<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalJS'] = '<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
        $data['externalJS'] .= '<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $this->load->view('main', $data);
    }

    public function process($id = null) {
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
        $data['id_bank_transfer'] = $id;

        if (!empty($id)) {
            $data['bt'] = $this->bank_transfer_m->getBankTransfer($id);
            $data['bts'] = $this->bank_transfer_m->getBankTransferStage($id);
            $data['bto'] = $this->bank_transfer_m->getSummaryBankTransferOther($id);
            $data['bte'] = $this->bank_transfer_m->getDisplayBankTransferedEmp($id);
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
        }else if (!empty($data['run_payroll']) && !empty($data['run_payroll']['transfer']) && $id_bank_transfer) {
            $data['run_payroll']['transfer'] = $this->bank_transfer_m->calculate_emp_bank_transfer($data['run_payroll']['transfer'], $data['run_payroll']['transfer_pernr_thp'], $percentage,$data['bte'] );
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