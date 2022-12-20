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
class simulation extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/simulation';
        $data['externalCSS'] ='<link href="' . base_url() . 'css/select2.css" rel="stylesheet">';
//        $data['externalCSS'] .='<link href="' . base_url() . 'assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />';
//        $data['externalCSS'] .='<link href="' . base_url() . 'assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />';
        $data['externalCSS'] .='<link rel="stylesheet" href="' . base_url() . 'assets/datatables/datatables.bundle.css" />';
        $data['externalCSS'] .='<link rel="stylesheet" href="' . base_url() . 'assets/data-tables/DT_bootstrap.css" />';
        $data['externalCSS'] .='<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/bootstrap-datepicker/css/datepicker.css" />';
        
        $data['externalJS'] ='<script type="text/javascript" src="' . base_url() . 'js/select2.min.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'assets/datatables/datatables.all.min.js?v=7.0.6"></script>';
//        $data['externalJS'] .='<script type="text/javascript" language="javascript" src="' . base_url() . 'assets/advanced-datatable/media/js/jquery.dataTables.js"></script>';
        $data['externalJS'] .='<script type="text/javascript" src="' . base_url() . 'assets/data-tables/DT_bootstrap.js"></script>';
        $data['externalJS'] .= '<script src="' . base_url() . 'assets/jquery.blockUI.js"></script>';
        $this->load->model('employee_m');
        $aEmp = $this->employee_m->get_pernrs_abkrs();
        $aABKRS = $this->employee_m->get_abkrs();
        $aPERSG = $this->employee_m->get_persg();
        $aPERSK = $this->employee_m->get_persk();
//        $sAddUlum = "";
//        if($this->session->userdata('username')=='mafulum'){
            $sAddUlum = ',date_payroll: $("#fDateOfPayment").val()';
//        }
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
                    $("#fnik").select2({
                        data: '. json_encode($aEmp) .',
                        formatResult : format,
                        multiple: true,
                        dropdownAutoWidth: true
                    });
                    $("#fABKRS").select2({
                        data: '. json_encode($aABKRS) .',
                        formatResult : format,
                        multiple: true,
                        dropdownAutoWidth: true
                    });
                    $("#fPERSG").select2({
                        data: '. json_encode($aPERSG) .',
                        formatResult : format,
                        multiple: true,
                        dropdownAutoWidth: true
                    });
                    $("#fPERSK").select2({
                        data: '. json_encode($aPERSK) .',
                        formatResult : format,
                        multiple: true,
                        dropdownAutoWidth: true
                    });
                    
                    oSummaryTable = $("#summary-table").dataTable({
                        aoColumns: [
                            { mData: "WERKS_STEXT"},
                            { mData: "SUM_TFMNT"},
                            { mData: "IPK"},
                            { mData: "BPJS_TK"},
                            { mData: "BPJS_KESEHATAN"},
                            { mData: "PPH21"},
                            { mData: "PIHAK_KE_3"},
                            { mData: "GEWA"},
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
                            { mData: "BANK_NAME"},
                            { mData: "BANK_ACCOUNT"},
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
                            { mData: "TFMNT"},
                            { mData: "start_date_payroll"},
                            { mData: "end_date_payroll"}
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
                            { mData: "start_date_payroll"},
                            { mData: "end_date_payroll"}
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
                            { mData: "ABKRS"},
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
                            { mData: "WAMNT"},
                            { mData: "start_date_payroll"},
                            { mData: "end_date_payroll"}
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
                            { mData: "ABKRS"},
                            { mData: "PERNR"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
                            { mData: "WAMNT"}
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
                            { mData: "ABKRS"},
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
                            { mData: "WAMNT"},
                            { mData: "start_date_payroll"},
                            { mData: "end_date_payroll"}
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
                            { mData: "ABKRS"},
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
                            { mData: "WAMNT"},
                            { mData: "start_date_payroll"},
                            { mData: "end_date_payroll"}
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
                            { mData: "ABKRS"},
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
                            { mData: "WAMNT"},
                            { mData: "start_date_payroll"},
                            { mData: "end_date_payroll"}
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
                            { mData: "ABKRS"},
                            { mData: "PERNR"},
                            { mData: "BEGDA"},
                            { mData: "ENDDA"},
                            { mData: "WGTYP"},
                            { mData: "LGTXT"},
                            { mData: "PRTYP"},
                            { mData: "TNAME"},
                            { mData: "WAMNT"},
                            { mData: "start_date_payroll"},
                            { mData: "end_date_payroll"}
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
                    
                    oTableCheck = $("#check-table").dataTable({
                        aoColumns: [
                            { mData: "PERNR"},
                            { mData: "PRTYP"},
                            { mData: "item"},
                            { mData: "N_WGTYP"}
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
                            url: "'. base_url().'index.php/payroll/simulation/go",
                            type: "get", //send it through get method
                            timeout : 0,
                            data: { 
                              is_reg: vis_reg,
                              date_offcyecle: $("#fDateOffCycle").val(),
                              period_regular: $("#fPeriodRegular").val(),
                              pernr: $("#fnik").val(), 
                              abkrs: $("#fABKRS").val(), 
                              persg: $("#fPERSG").val(),
                              persk: $("#fPERSK").val()'.$sAddUlum.'
                            },
                            success: function(response) {
                                setTimeout($.unblockUI, 500);
                                oSummaryTable.fnClearTable();
//                                console.log(response.content.management_report);
                                oSummaryTable.fnAddData(response.content.management_report);
                                oTableCheck.fnClearTable();
                                oTableCheck.fnAddData(response.content.payroll_check);
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
                                if(response.content.title!=undefined){
                                    $("#title").html(response.content.title);
                                }
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
                                oTableCheck.fnClearTable();
                                setTimeout($.unblockUI, 500);
                                $("#title").html("");
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

    public function go() {
        set_time_limit(0);
        ini_set("max_execution_time",3600);
        $paramAPI['pernr']=$this->input->get('pernr');
        $paramAPI['abkrs']=$this->input->get('abkrs');
        $paramAPI['persg']=$this->input->get('persg');
        $paramAPI['persk']=$this->input->get('persk');
        $is_reg=$this->input->get('is_reg');
        $paramAPI['date_off_cycle']=$this->input->get('date_offcyecle');
        $paramAPI['period_regular']=$this->input->get('period_regular');
        $paramAPI['is_off_cycle']= false;
        if($is_reg=="off"){
            $paramAPI['is_off_cycle']=true;
        }
        
//        $data = http_build_query($paramAPI);
        $paramAPI['date_payroll']=$this->input->get('date_payroll');
        $data = http_build_query($paramAPI);
        $curl = curl_init();
        // $url = 'http://10.229.207.148:8001/payroll/simulation'."?".$data;
        $url = $this->config->item('base_url_engine_payroll').'/payroll/simulation'."?".$data;
//        if($this->session->userdata('username')=='mafulum'){
//            $url = 'http://localhost:8801/payroll/simulation'."?".$data;
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
