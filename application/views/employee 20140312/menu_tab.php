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
                            <li><a href="<?php echo base_url() . "index.php/employee/edu_formal_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Education Formal</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/edu_nformal_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Education Non Formal</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/awards_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Awards</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/grievance_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Grievance</a></li>
                            <li><a href="<?php echo base_url() . "index.php/employee/medical_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-caret-square-o-right"></i> Medical</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane profile-nav" id="org">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="javascript:;"> <i class="fa fa-caret-square-o-right"></i> Organizational Assignment</a></li>
                            <li><a href="javascript:;"> <i class="fa fa-caret-square-o-right"></i> Other Assignment</a></li>
                            <li><a href="javascript:;"> <i class="fa fa-caret-square-o-right"></i> Grade</a></li>
                            <li><a href="javascript:;"> <i class="fa fa-caret-square-o-right"></i> Date Specification</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane profile-nav" id="add">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="javascript:;"> <i class="fa fa-caret-square-o-right"></i> Employee Competency</a></li>
                            <li><a href="javascript:;"> <i class="fa fa-caret-square-o-right"></i> Employee Performance</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--widget end-->
    </div>
</div>
