<?php 
$aModule = $this->common->get_module_access();
?>
<div id="sidebar"  class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
        <?php if($aModule[1] == 1){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'dashboard') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/dashboard"; ?>">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo base_url() . "index.php/dashboard"; ?>">Demography Dashboard</a></li>
                <!--<li><a href="<?php echo base_url() . "index.php/dashboard/manager"; ?>">Manager Dashboard</a></li>-->
            </ul>
        </li>
		<?php } ?>
		<?php if($aModule[2] == 1 && $this->common->check_permission('EmployeeMasterData')){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'employee' || substr($this->uri->segment(1),0,4) == 'memp') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/employee/master"; ?>" >
                <i class="fa fa-laptop"></i>
                <span>Empl. Master Data</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[3] == 1 && $this->common->check_permission('OrganizationManagement')){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'orgchart') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/orgchart/tree"; ?>" >
                <i class="fa fa-sitemap"></i>
                <span>Organization Structure</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[4] == 1 && false){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'tprofile') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/tprofile/search"; ?>" >
                <i class="fa fa-cogs"></i>
                <span>Talent Profile</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[4] == 1 ){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(2) == 'tsearch') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/talent/tsearch/index"; ?>" >
                <i class="fa fa-search"></i>
                <span>Talent Search</span>
            </a>
        </li>
        <?php } ?>
        <?php if($aModule[5] == 1 && false){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'ecs') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/ecs/search"; ?>" >
                <i class="fa fa-book"></i>
                <span>Empl. Competency Summary</span>
            </a>
        </li>
        <?php } ?>
        <?php if($aModule[6] == 1 && false){ ?>
        <li class="sub-menu">
			<a <?php if($this->uri->segment(1) == 'srank') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/srank/search"; ?>" >
                <i class="fa fa-tasks"></i>
                <span>Succession Rank</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[7] == 1 && false){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'pmatch') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/pmatch/search"; ?>" >
                <i class="fa fa-th"></i>
                <span>Profile Matchup</span>
            </a>
        </li>
        <?php } ?>
        <?php if($aModule[8] == 1 && $this->common->check_permission('PayrollMenu')){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'payroll') echo 'class="active"'; ?> href="#">
                <i class="fa fa-cogs"></i>
                <span>Payroll</span>
            </a>
            <ul class="sub">
		<?php if($this->common->check_permission('PayrollMenu.EmployeeValidation')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/employee_validation"; ?>">Employee Validation</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.Simulation')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/simulation"; ?>">Simulation</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.Running')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/running"; ?>">Running</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.RunningResult')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/running_result"; ?>">Running Result</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.InOut')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/in_out"; ?>">In-Out / Advance Payment</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.OffCycle')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/offcycle"; ?>">OffCycle Process</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.BankTransfer')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/bank_transfer"; ?>">Bank Transfer</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.BankTransferDocument')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/document_transfer"; ?>">Bank Transfer Document</a></li>
                <?php } if($this->common->check_permission('PayrollMenu.SlipGaji')){ ?>
                <li><a href="<?php echo base_url() . "index.php/payroll/slip_gaji"; ?>">Slip Gaji</a></li>
                <?php } ?>
            </ul>
        </li>  
        <?php } ?>
        <?php if($aModule[8] == 1 && $this->common->check_permission('ConfigMenu')){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'admin') echo 'class="active"'; ?> href="#">
                <i class="fa fa-dashboard"></i>
                <span>Config Menu</span>
            </a>
            <ul class="sub">
		<?php if($this->common->check_permission('ConfigMenu.Config')){ ?>
                <li><a  href="<?php echo base_url() . "index.php/admin/config"; ?>">Config</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.Abbrev')){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/abbrev"; ?>">Abbrev</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.Abbrev')){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/User"; ?>">User</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.Perusahaan')){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/perusahaan"; ?>">Perusahaan</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.JobCompt')){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/job_compt"; ?>">Job Qualification</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.MatrixJobScore') && false){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/matrix_js"; ?>">Matrix Job Score</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.MasterPerformance') && false){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/mperformance"; ?>">Master Performance</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.MasterReadiness') && false){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/mpotential"; ?>">Master Potential</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.MasterReadiness')){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/mreadiness"; ?>">Master Readiness</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.TalentDescription') && false){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/talentdesc"; ?>">Talent Description</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.CriteriaReadiness') && false){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/cr_readiness"; ?>">Criteria Readiness</a></li>
                <?php } if($this->common->check_permission('ConfigMenu.MasterCompetency')){ ?>
                <li><a href="<?php echo base_url() . "index.php/admin/m_competency"; ?>">Master Competency</a></li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>
        <?php if($aModule[8] == 1 && $this->common->check_permission('MassUpload')){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'upload') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/upload/master_emp"; ?>" >
                <i class="fa fa-upload"></i>
                <span>Mass Upload</span>
            </a>
        </li>
        <?php } ?>
        <?php if($aModule[8] == 1 && $this->common->check_permission('Download')){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'admin') echo 'class="active"'; ?> href="#">
                <i class="fa fa-download"></i>
                <span>Download</span>
            </a>
                <?php if($this->common->check_permission('Download.EmployeeOrg')){ ?>
            <ul class="sub">
                <li><a  href="<?php echo base_url() . "index.php/download/emp_org"; ?>">Employee Org.</a></li>
            </ul>
                <?php } if($this->common->check_permission('Download.EmployeeBPJS')){ ?>
            <ul class="sub">
                <li><a  href="<?php echo base_url() . "index.php/download/emp_bpjs"; ?>">Employee BPJS.</a></li>
            </ul>
                <?php } if($this->common->check_permission('Download.EmployeePayroll')){ ?>
            <ul class="sub">
                <li><a  href="<?php echo base_url() . "index.php/download/emp_payroll"; ?>">Employee For Payroll.</a></li>
            </ul>
                <?php } ?>
        </li>
              <?php
        }
        ?>
    </ul>
    <!-- sidebar menu end-->
</div>
