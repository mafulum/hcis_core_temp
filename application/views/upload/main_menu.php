<?php $aModule = $this->common->get_module_access(); ?>
<div id="sidebar"  class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
         <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'dashboard') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/dashboard"; ?>">
                <i class="fa fa-dashboard"></i>
                <span>Back Home</span>
            </a>
        </li>
<!--        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_organisasi' ) echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_organisasi"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Organisasi</span>
            </a>
        </li>-->
        <?php if($this->common->check_permission('MassUpload.MEPersonalID')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_posisi' ) echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_posisi"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Posisi</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpData')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_emp') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_emp"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Emp</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.MEPersonalID')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_personalid') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_personalid"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Emp Personal ID</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.MENPWP')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_npwp') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_npwp"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Emp NPWP</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.MERekening')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_rekening') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_rekening"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Emp Rekening</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.MEAddress')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_address') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_address"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Emp Address</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.MEComm')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_comm') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_comm"; ?>">
                <i class="fa fa-table"></i>
                <span>Master Emp Comm</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpMonitoringOfTask')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'emp_montask') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/emp_montask"; ?>">
                <i class="fa fa-table"></i>
                <span>Emp Monitoring of Task</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpHealthInsurance')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'emp_inshealth') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/emp_inshealth"; ?>">
                <i class="fa fa-table"></i>
                <span>Emp Health Insurance</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpHealthInsurance')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'emp_insurance') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/emp_insurance"; ?>">
                <i class="fa fa-table"></i>
                <span>Emp Insurance Cost</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpBPJSTK')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'emp_bpjs_tk') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/emp_bpjs_tk"; ?>">
                <i class="fa fa-table"></i>
                <span>Emp BPJS TK</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.PayrollRecurring')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'payroll_insert_recur' && $this->common->check_permission('MassUpload.PayrollRecurring')) echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/payroll_insert_recur"; ?>">
                <i class="fa fa-table"></i>
                <span>Payroll Insert Recurring</span>
            </a>
        </li>
<!--        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'payroll_delimit_recur' ) echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/payroll_delimit_recur"; ?>">
                <i class="fa fa-table"></i>
                <span>Payroll Delimit Recurring</span>
            </a>
        </li>-->
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.PayrollAdditional')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'payroll_insert_additional') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/payroll_insert_addpayment"; ?>">
                <i class="fa fa-table"></i>
                <span>Payroll Insert Additional</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.PayrollBasicPay')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'payroll_insert_basicpay') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/payroll_insert_basicpay"; ?>">
                <i class="fa fa-table"></i>
                <span>Payroll Insert Basic Pay</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.TimeEmpOvertime')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'tm_employee_overtime') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/tm_employee_overtime"; ?>">
                <i class="fa fa-table"></i>
                <span>Time - Employee Overtime</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpData')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'tm_employee_leave') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/tm_employee_leave"; ?>">
                <i class="fa fa-table"></i>
                <span>Time - Employee Leave</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpData')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_date' ) echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_date"; ?>">
                <i class="fa fa-table"></i>
                <span>PA- Employee Date</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpData')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_eduf') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_eduf"; ?>">
                <i class="fa fa-table"></i>
                <span>Education Formal</span>
            </a>
        </li>
        <?php } ?>
        <?php if($this->common->check_permission('MassUpload.EmpData')) { ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'master_family') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_family"; ?>">
                <i class="fa fa-table"></i>
                <span>Family</span>
            </a>
        </li>
        <?php } ?>
    </ul>
    <!-- sidebar menu end-->
</div>