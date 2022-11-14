<?php $iPIHC = $this->common->cek_pihc_access(); ?>
<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading tab-bg-dark-navy-blue">
                <ul class="nav nav-tabs">
		<?php if($this->common->check_permission('EmployeeMasterData.Personal')){ ?>
                    <li class="active">
                        <a href="#basic" data-toggle="tab">
                            <i class="fa fa-user"></i> Basic Personal Data
                        </a>
                    </li>
                <?php } if($this->common->check_permission('EmployeeMasterData.Organization')){ ?>
                    <li class="">
                        <a href="#org" data-toggle="tab">
                            <i class="fa fa-sitemap"></i> Organization
                        </a>
                    </li>
                <?php } if($this->common->check_permission('EmployeeMasterData.Payroll')){ ?>
                    <li class="">
                        <a href="#payroll" data-toggle="tab">
                            <i class="fa fa-money"></i> Payroll
                        </a>
                    </li>
                <?php } if($this->common->check_permission('EmployeeMasterData.Time')){ ?>
                    <li class="">
                        <a href="#tms" data-toggle="tab">
                            <i class="fa fa-clock-o"></i> Time
                        </a>
                    </li>
		<?php } if($this->common->check_permission('EmployeeMasterData.Competency')){ ?>
                    <li class="">
                        <a href="#competency" data-toggle="tab">
                            <i class="fa fa-clock-o"></i> Competency
                        </a>
                    </li>
		<?php } ?>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content tasi-tab">
		<?php if($this->common->check_permission('EmployeeMasterData.Personal')){ ?>
                    <div class="tab-pane active profile-nav" id="basic">
                        <ul class="nav nav-pills nav-stacked">
                            <?php if($this->common->check_permission('EmployeeMasterData.Personal.PersonalData')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/personal_data_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Personal Data</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Personal.Address')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/personal_data_addr/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Address</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Personal.BankDetails')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/personal_data_bank/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Bank Details</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Personal.Family')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/personal_data_family/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Family</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Personal.Communication')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/personal_data_comm/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Communication</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Personal.PersonalID')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_personalid/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Personal ID</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Personal.FormalEducation') || true){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_eduf_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Formal Education</a></li>
                            <?php } ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_grievances_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Grievances</a></li>
                        </ul>
                    </div>
                <?php } if($this->common->check_permission('EmployeeMasterData.Organization')){ ?>
                    <div class="tab-pane profile-nav" id="org">
                        <ul class="nav nav-pills nav-stacked">
                            <?php if($this->common->check_permission('EmployeeMasterData.Organization.OrganizationAssignment')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/organizational_assignment_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Organization Assignment</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Organization.DateSpecification')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_date_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>""> <i class="fa fa-caret-square-o-right"></i> Date Specification</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Organization.MonitoringOfTask')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_monitoring/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>""> <i class="fa fa-caret-square-o-right"></i> Monitoring of Task</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Organization.MonitoringOfTask')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_sup_matriks/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>""> <i class="fa fa-caret-square-o-right"></i> Sup. Matriks</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } if($this->common->check_permission('EmployeeMasterData.Payroll')){ ?>
                    <div class="tab-pane profile-nav" id="payroll">
                        <ul class="nav nav-pills nav-stacked">
                            <?php if($this->common->check_permission('EmployeeMasterData.Payroll.BasicPay')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_basicpay/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Basic Pay</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.RecurringPay')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_recurring/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Recurring Payment / Deductions</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.AdditionPay')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_addpayment/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Additional Payments</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.OffCyclePay')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_offcycle/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> OffCycle Payments</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.Tax')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_npwp/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Tax / NPWP</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.BPJSTK')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_bpjs_tk/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> BPJS Tenaga Kerja</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.BPJSKES')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_inshealth/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> BPJS Kesehatan</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.Insurance')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_insurance/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Insurance (cost)</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.SlipAdditionalInfo')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_payroll/personal_addtlinfo/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Slip Additional Info</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } if($this->common->check_permission('EmployeeMasterData.Time')){ ?>
                    <div class="tab-pane profile-nav" id="tms">
                        <ul class="nav nav-pills nav-stacked">
                            <?php if($this->common->check_permission('EmployeeMasterData.Time.Leave')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_tms/personal_leave/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Leave</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Time.Overtime')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/memp_tms/personal_overtime/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Overtime</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Time.LeaveQuota')){ ?>
                            <li><a href="#"> <i class="fa fa-caret-square-o-right"></i> Leave Quota</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Time.OvertimeQuota')){ ?>
                            <li><a href="#"> <i class="fa fa-caret-square-o-right"></i> Overtime Quota</a></li>
                            <?php } ?>
                        </ul>
                    </div>
		<?php } if($this->common->check_permission('EmployeeMasterData.Competency')){ ?>
                    <div class="tab-pane profile-nav" id="competency">
                        <ul class="nav nav-pills nav-stacked">
                            <?php if($this->common->check_permission('EmployeeMasterData.Competency.Competency')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_compt_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Employee Competency</a></li>
                            <?php } if($this->common->check_permission('EmployeeMasterData.Competency.Performance')){ ?>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_perf_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Employee Performance</a></li>
                            <?php } ?>
                        </ul>
                    </div>
		<?php } ?>
                    
                </div>
            </div>
        </section>
        <!--widget end-->
    </div>
</div>