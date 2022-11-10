<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-lg-4">
            <section class="panel">
                <header class="panel-heading">
                    Find Employee
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                <div class="panel-body">
                    <label class="col-lg-2 col-sm-2 control-label">NIK</label>
                    <div class="col-lg-10">
                        <div class="iconic-input right">
                            <a id="aEmp" href="#"><i class="fa fa-book"></i></a>
                            <input id="iNopeg" type="text" class="form-control" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" placeholder="right icon">
                        </div>
                    </div>
                </div>
            </section>
            <!--widget start-->
            <aside class="profile-nav alt green-border">
                <section class="panel">
                    <div class="user-heading alt green-bg">
                        <a href="#">
                            <img alt="" src="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>.png">
                        </a>
                        <h1><?php echo $this->global_m->get_array_data($master_emp, "CNAME"); ?></h1>
                        <p><?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?></p>
                    </div>

                    <ul class="nav nav-pills nav-stacked">
                        <li><a> <i class="fa fa-archive"></i> NIK Asal : <?php echo $this->global_m->get_array_data($emp_map, "NIK"); ?></a></li>
                        <li><a> <i class="fa fa-suitcase"></i> Posisi : <?php echo $this->global_m->get_array_data($emp_map, "POSISI"); ?></a></li>
                        <li><a> <i class="fa fa-users"></i> Unit : <?php echo $this->global_m->get_array_data($emp_map, "ORG"); ?></a></li>
                        <li><a> <i class="fa fa-building-o"></i> Perusahaan : <?php echo $this->global_m->get_array_data($emp_map, "PERSH"); ?></a></li>
                    </ul>

                </section>
            </aside>
            <!--widget end-->
        </div>
        <div class="col-lg-8">
            <?php $this->load->view($emp_view, $aCon); ?>
        </div>
    </div>
    <!-- page end-->
</section>