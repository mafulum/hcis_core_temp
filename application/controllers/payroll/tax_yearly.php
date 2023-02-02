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
class tax_yearly extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'payroll/tax_yearly';
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
        // $aEmp = $this->employee_m->get_pernrs_abkrs();
        // $aABKRS = $this->employee_m->get_abkrs();
        // $aPERSG = $this->employee_m->get_persg();
        // $aPERSK = $this->employee_m->get_persk();
//        $sAddUlum = "";
//        if($this->session->userdata('username')=='mafulum'){
            // $sAddUlum = ',date_payroll: $("#fDateOfPayment").val()';
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
                              year: $("#fPeriodRegular").val()
                            },
                            success: function(response) {
                                setTimeout($.unblockUI, 500);
                                oTablePayrollTax.fnClearTable();
                                oTablePayrollTax.fnAddData(response.content.payroll_tax);
                            },
                            error: function(xhr) {
                              //Do Something to handle error
                                blockPage("Error");
                                oTablePayrollTax.fnClearTable();
                                setTimeout($.unblockUI, 500);
                                $("#title").html("");
                            }
                        });
                    });
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
        $paramAPI['year']=$this->input->get('year');
        
//        $data = http_build_query($paramAPI);
        $paramAPI['date_payroll']=$this->input->get('date_payroll');
        $data = http_build_query($paramAPI);
        $curl = curl_init();
        // $url = 'http://10.229.207.148:8001/payroll/simulation'."?".$data;
        $url = $this->config->item('base_url_engine_payroll').'/payroll/tax_yearly'."?".$data;
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
