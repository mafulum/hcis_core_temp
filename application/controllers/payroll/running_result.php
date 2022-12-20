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
class running_result extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/running_result';
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
                    oTable = $("#dynamic-table").dataTable({
                        aoColumns: [
                            { mData: "time_running"},
                            { mData: "created_at"},
                            { mData: "name_of_process"},
                            { mData: "is_offcycle"},
                            { mData: "date_offcycle"},
                            { mData: "periode_regular"},
                            { mData: "pernr"},
                            { mData: "persg"},
                            { mData: "persk"},
                            { mData: "abkrs"},
                            { mData: "by_draft"},
                            { mData: "time_draft"},
                            { mData: "by_confirm"},
                            { mData: "time_confirm"},
                            { mData: "action"},
                        ],
                        ajax: "' . base_url() . 'index.php/payroll/running_result/ajax",
                        autofill: true,
                        order: [[ 0, "desc" ]],
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
                    
		});
		</script>';
        $this->load->view('main', $data);
    }

    public function ajax() {
        $this->load->model('payroll/running_payroll_m');
        $list = $this->running_payroll_m->get_datatables();
        $data = array();
        $no = 0;
        if (isset($_GET['start']))
            $no = $_GET['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row['time_running'] = $field->time_running;
            $row['created_at'] = $field->created_at;
            $row['is_offcycle'] = $field->is_offcycle;
            $row['date_offcycle'] = $field->date_offcycle;
            $row['periode_regular'] = $field->periode_regular;
            $row['name_of_process'] = $field->name_of_process;
            $row['pernr'] = $field->pernr;
            $row['persg'] = $field->persg;
            $row['persk'] = $field->persk;
            $row['abkrs'] = $field->abkrs;
            $row['by_draft'] = $field->by_draft;
            $row['time_draft'] = $field->time_draft;
            $row['by_confirm'] = $field->by_confirm;
            $row['time_confirm'] = $field->time_confirm;
            if (!empty($row['by_confirm'])) {
                $row['action'] = '<a href="' . base_url() . 'index.php/payroll/running_result/view_running_result_detail/' . $field->id . '">VIEW</a>';
            } else {
                $row['action'] = '<a href="' . base_url() . 'index.php/payroll/running_result/running_result_detail/' . $field->id . '">OPEN</a>';
                $row['action'] .= ' | <a href="' . base_url() . 'index.php/payroll/running_result/delete/' . $field->id . '">DEL</a>';
            }

            $data[] = $row;
        }
        $draw = "";
        if (isset($_GET['draw'])) {
            $draw = $_GET['draw'];
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->running_payroll_m->count_all(),
            "recordsFiltered" => $this->running_payroll_m->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function action() {
        $aInput['name_of_process'] = $this->input->post('forNameProcess');
        if ($this->input->post('confirm')) {
            $aInput['by_confirm'] = $this->session->userdata('username');
            $aInput['time_confirm'] = date("Y-m-d H:i:s");
        } else if ($this->input->post('draft')) {
            $aInput['by_draft'] = $this->session->userdata('username');
            $aInput['time_draft'] = date("Y-m-d H:i:s");
        }
        $time_running = $this->input->post('formCodePayroll');
//        var_dump($time_running);exit;
        $objResult = $this->confirm_detail($time_running);
//        var_dump($objResult);exit;
        $this->load->model('payroll/running_payroll_m');
        if(!empty($objResult) && !empty($objResult->content)){
            $aInput['n_uploaded']=$objResult->content->n_uploaded;
            $aInput['n_conflict']=$objResult->content->n_conflict;
            $aInput['n_conflict_lock']=$objResult->content->n_conflict_lock;
            $this->running_payroll_m->updateByTimeRunning($time_running, $aInput);
        }
        if ($this->input->post('confirm')) {
            $data['base_url'] = $this->config->item('base_url');
            $data["userid"] = $this->session->userdata('username');
            $data['view'] = 'payroll/running_result_confirm';
            $data['uploaded'] = $objResult->content->uploaded;
            $data['conflict'] = $objResult->content->conflict;
            $data['conflict_lock'] = $objResult->content->conflict_lock;
            $data['time_running']=$time_running;
            $this->load->view('main', $data);
        } else {
            redirect(base_url() . "index.php/payroll/running_result", 'refresh');
        }
    }
    
    public function action_confirm(){
        $time_running = $this->input->post('formCodePayroll');
        $aPernrConflict = $this->input->post('conflict');
        if(empty($aPernrConflict)){
            redirect(base_url() . "index.php/payroll/running_result", 'refresh');
        }            
        $aMapPernr=array();
        foreach($aPernrConflict as $row){
            $opernr = explode("_", $row);
            $aMapPernr[]=array('pernr'=>$opernr[0],'id_payroll_running'=>$opernr[1]);
        }
        
        $time_running = $this->input->post('formCodePayroll');
//        echo $time_running;
//        echo json_encode($aMapPernr);
//        exit;
        $objResult = $this->reconfirm_detail($time_running,json_encode($aMapPernr));
        $this->load->model('payroll/running_payroll_m');
        if(!empty($objResult) && !empty($objResult->content)){
            $aInput['n_uploaded']=$objResult->content->n_uploaded;
            $aInput['n_conflict']=$objResult->content->n_conflict;
            $aInput['n_conflict_lock']=$objResult->content->n_conflict_lock;
            $this->running_payroll_m->updateByTimeRunning($time_running, $aInput);
            $data['base_url'] = $this->config->item('base_url');
            $data["userid"] = $this->session->userdata('username');
            $data['view'] = 'payroll/running_result_confirm';
            $data['uploaded'] = $objResult->content->uploaded;
            $data['conflict'] = $objResult->content->conflict;
            $data['conflict_lock'] = $objResult->content->conflict_lock;
            $data['time_running']=$time_running;
            $this->load->view('main', $data);
        }
    }

    public function running_result_detail($id) {
        $data = Array();
        $this->load->model('payroll/running_payroll_m');
        if (strlen($id) != 14) {
            $data['prr'] = $this->running_payroll_m->get_by_id($id);
        } else {
            $data['prr'] = $this->running_payroll_m->get_by_running_time($id);
        }
        if (empty($data['prr']) && empty($data['prr']['confirm_by'])) {
            die("~");
        }

        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/running_result_detail';
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
                    
                    oSummaryTable = $("#summary-table").dataTable({
                        aoColumns: [
                            { mData: "WERKS_STEXT"},
                            { mData: "SUM_TFMNT"},
                            { mData: "BPJS_TK"},
                            { mData: "BPJS_KESEHATAN"},
                            { mData: "PPH21"},
                            { mData: "PIHAK_KE_3"},
                            { mData: "N_PERNR"}
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
                    
                    oPihak3Table = $("#summary-pihak3-table").dataTable({
                        aoColumns: [
                            { mData: "CNAME"},
                            { mData: "BANK_PAYEE"},
                            { mData: "SUM_WAMNT"}
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
                    
                    oBankTransferSummaryTable = $("#summary-bank-table").dataTable({
                        aoColumns: [
                            { mData: "BANK_NAME"},
                            { mData: "TFMNT"},
                            { mData: "n_acc"}
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
                    
                    oBankTransferTable = $("#transfer-bank-table").dataTable({
                        aoColumns: [
                            { mData: "PERSG"},
                            { mData: "PERSK"},
                            { mData: "PERNR"},
                            { mData: "BANK_NAME"},
                            { mData: "BANK_ACCOUNT"},
                            { mData: "BANK_PAYEE"},
                            { mData: "TFMNT"}
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
                            { mData: "PERSG"},
                            { mData: "PERSK"},
                            { mData: "PERNR"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
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
                    
                    oTablePayrollEmp = $("#dynamic-table-payroll-emp").dataTable({
                        aoColumns: [
                            { mData: "PERSG"},
                            { mData: "PERSK"},
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                    
                    blockPage("Please wait for a while estimate about 1-5 minutes for loading");
                    $.ajax({
                        url: "' . base_url() . 'index.php/payroll/running_result/running_result_detail_load",
                        type: "get", //send it through get method
                        data: { 
                          payroll_code: ' . $data['prr']['time_running'] . '
                        },
                        success: function(response) {
                            setTimeout($.unblockUI, 500);
                            $("#panelConfirmation").show();
                            oTable.fnClearTable();
                            oTable.fnAddData(response.content.employee);
                            oSummaryTable.fnClearTable();
                            oSummaryTable.fnAddData(response.content.management_report);
                            oPihak3Table.fnClearTable()
                            oPihak3Table.fnAddData(response.content.pihak_3_summary);
                            oBankTransferSummaryTable.fnClearTable()
                            oBankTransferSummaryTable.fnAddData(response.content.bank_transfer_summary);
                            oBankTransferTable.fnClearTable()
                            oBankTransferTable.fnAddData(response.content.bank_transfer);
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
                            oPihak3Table.fnClearTable();
                            oBankTransferSummaryTable.fnClearTable();
                            oBankTransferTable.fnClearTable();
                            setTimeout($.unblockUI, 500);
                        }
                    });
		});
		</script>';
        $this->load->view('main', $data);
    }

    public function view_running_result_detail($id) {
        $data = Array();
        $this->load->model('payroll/running_payroll_m');
        if (strlen($id) != 14) {
            $data['prr'] = $this->running_payroll_m->get_by_id($id);
        } else {
            $data['prr'] = $this->running_payroll_m->get_by_running_time($id);
        }
        if (empty($data['prr']) && !empty($data['prr']['confirm_by'])) {
            die("~");
        }

        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/view_running_result_detail';
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
                    
                    oSummaryTable = $("#summary-table").dataTable({
                        aoColumns: [
                            { mData: "WERKS_STEXT"},
                            { mData: "SUM_TFMNT"},
                            { mData: "BPJS_TK"},
                            { mData: "BPJS_KESEHATAN"},
                            { mData: "PPH21"},
                            { mData: "PIHAK_KE_3"},
                            { mData: "N_PERNR"}
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
                    
                    oPihak3Table = $("#summary-pihak3-table").dataTable({
                        aoColumns: [
                            { mData: "CNAME"},
                            { mData: "BANK_PAYEE"},
                            { mData: "SUM_WAMNT"}
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
                    
                    oBankTransferSummaryTable = $("#summary-bank-table").dataTable({
                        aoColumns: [
                            { mData: "BANK_NAME"},
                            { mData: "TFMNT"},
                            { mData: "n_acc"}
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
                    
                    oBankTransferTable = $("#transfer-bank-table").dataTable({
                        aoColumns: [
                            { mData: "PERSG"},
                            { mData: "PERSK"},
                            { mData: "PERNR"},
                            { mData: "BANK_NAME"},
                            { mData: "BANK_ACCOUNT"},
                            { mData: "BANK_PAYEE"},
                            { mData: "TFMNT"}
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
                            { mData: "PERSG"},
                            { mData: "PERSK"},
                            { mData: "PERNR"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
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
                    
                    oTablePayrollEmp = $("#dynamic-table-payroll-emp").dataTable({
                        aoColumns: [
                            { mData: "PERSG"},
                            { mData: "PERSK"},
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                            { mData: "TNAME"},
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
                    
                    blockPage("Please wait for a while estimate about 1-5 minutes for loading");
                    $.ajax({
                        url: "' . base_url() . 'index.php/payroll/running_result/running_result_detail_load",
                        type: "get", //send it through get method
                        data: { 
                          payroll_code: ' . $data['prr']['time_running'] . '
                        },
                        success: function(response) {
                            setTimeout($.unblockUI, 500);
                            $("#panelConfirmation").show();
                            oSummaryTable.fnClearTable();
                            oSummaryTable.fnAddData(response.content.management_report);
                            oTable.fnClearTable();
                            oTable.fnAddData(response.content.employee);
                            oPihak3Table.fnClearTable()
                            oPihak3Table.fnAddData(response.content.pihak_3_summary);
                            oBankTransferSummaryTable.fnClearTable()
                            oBankTransferSummaryTable.fnAddData(response.content.bank_transfer_summary);
                            oBankTransferTable.fnClearTable()
                            oBankTransferTable.fnAddData(response.content.bank_transfer);
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
                            oTable.fnClearTable();
                            oSummaryTable.fnClearTable();
                            oTablePayrollEmp.fnClearTable();
                            oTablePayrollBPJS.fnClearTable();
                            oTablePayrollTax.fnClearTable();
                            oTablePayrollAccrued.fnClearTable();
                            oTablePayrollBase.fnClearTable();
                            oTablePayrollSlip.fnClearTable();
                            oPihak3Table.fnClearTable();
                            oBankTransferSummaryTable.fnClearTable();
                            oBankTransferTable.fnClearTable();
                            setTimeout($.unblockUI, 500);
                        }
                    });
		});
		</script>';
        $this->load->view('main', $data);
    }
    
    private function reconfirm_detail($payroll_code,$sJsonPernr) {
        $paramAPI['code_payroll'] = $payroll_code;
        $paramAPI['pernrs'] = $sJsonPernr;
        $data = http_build_query($paramAPI);
        $curl = curl_init();
        // $url = 'http://10.229.207.148:8001//payroll/employee/reconfirm' . "?" . $data;
        $url = $this->config->item('base_url_engine_payroll').'/payroll/employee/reconfirm' . "?" . $data;
        
//        if($this->session->userdata('username')=='mafulum'){
//            $url = 'http://localhost:8801/payroll/employee/reconfirm'."?".$data;
//        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
//        echo $response;exit;
        return json_decode($response);
    }

    private function confirm_detail($payroll_code = null) {
        if (empty($payroll_code)) {
            $paramAPI['code_payroll'] = $_GET['payroll_code'];
        } else {
            $paramAPI['code_payroll'] = $payroll_code;
        }
        $data = http_build_query($paramAPI);
        $curl = curl_init();
        // $url = 'http://10.229.207.148:8001/payroll/employee/confirm' . "?" . $data;
        $url = $this->config->item('base_url_engine_payroll').'/payroll/employee/confirm' . "?" . $data;
//        if($this->session->userdata('username')=='mafulum'){
//            $url = 'http://localhost:8801/payroll/employee/confirm'."?".$data;
//        }
//        echo $url;exit;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    public function running_result_detail_load() {
        $paramAPI['code_payroll'] = $_GET['payroll_code'];
        $data = http_build_query($paramAPI);
        $curl = curl_init();
        // $url = 'http://10.229.207.148:8001/payroll/open_parquet' . "?" . $data;
        $url = $this->config->item('base_url_engine_payroll').'/payroll/open_parquet' . "?" . $data;
//        if($this->session->userdata('username')=='mafulum'){
//            $url = 'http://localhost:8801/payroll/open_parquet'."?".$data;
//        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        header('Content-Type: application/json');
        echo $response;
    }

}

?>
