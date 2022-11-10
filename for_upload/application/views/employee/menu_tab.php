<?php $iPIHC = $this->common->cek_pihc_access(); ?>
<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading tab-bg-dark-navy-blue">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#basic" data-toggle="tab">
                            <i class="fa fa-user"></i> Basic Personal Data
                        </a>
                    </li>
                    <li class="">
                        <a href="#org" data-toggle="tab">
                            <i class="fa fa-sitemap"></i> Organization
                        </a>
                    </li>
                    <li class="">
                        <a href="#add" data-toggle="tab">
                            <i class="fa fa-tasks"></i> Competency Data
                        </a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content tasi-tab">
                    <div class="tab-pane active profile-nav" id="basic">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="<?php echo base_url() . "index.php/employee/personal_data_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Personal Data</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_eduf_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Education Formal</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_edunf_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Education Non Formal</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_awards_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Achievement</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_grievances_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Grievances</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_medical_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Medical</a></li>
							<li><a href="<?php echo base_url() . "index.php/employee/emp_note_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Note</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane profile-nav" id="org">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="<?php echo base_url() . "index.php/employee/organizational_assignment_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Organization Assignment</a></li>
							<li><a href="<?php echo base_url() . "index.php/employee/organizational_assignment_old_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Organization Assignment (Old)</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/other_assignment_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>""> <i class="fa fa-caret-square-o-right"></i> Other Assignment</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_grade_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>""> <i class="fa fa-caret-square-o-right"></i> Grade</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/emp_date_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>""> <i class="fa fa-caret-square-o-right"></i> Date Specification</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane profile-nav" id="add">
                        <ul class="nav nav-pills nav-stacked">
							<li><a href="<?php echo base_url() . "index.php/employee/emp_compt_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Employee Competency</a></li>
							<? if($iPIHC == 1){ ?>
							<li><a href="<?php echo base_url() . "index.php/employee/emp_compt_holding_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Employee Competency (Holding)</a></li>
							<? } ?>
							<li><a href="<?php echo base_url() . "index.php/employee/emp_perf_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Employee Performance</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--widget end-->
    </div>
</div>