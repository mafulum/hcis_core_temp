<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-lg-4">
            <section class="panel">
                <header class="panel-heading">
                    Collapsible Widget
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                <div class="panel-body">
                    <label  class="col-lg-3 col-sm-3 control-label">Nopeg</label>
                    <div class="col-lg-9">
                        <div class="iconic-input right">
                            <i class="fa fa-book"></i>
                            <input type="text" class="form-control" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" placeholder="right icon">
                        </div>
                    </div>
                </div>
            </section>
            <!--widget start-->
            <aside class="profile-nav alt green-border">
                <section class="panel">
                    <div class="user-heading alt green-bg">
                        <a href="#">
                            <img alt="" src="<?= base_url(); ?>img/profile-avatar.jpg">
                        </a>
                        <h1><?php echo $this->global_m->get_array_data($master_emp, "CNAME"); ?></h1>
                        <p><?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?></p>
                    </div>

                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="javascript:;"> <i class="fa fa-clock-o"></i> NIK Asal : <?php echo $this->global_m->get_array_data($emp_org, "nik_asal"); ?></a></li>
                        <li><a href="javascript:;"> <i class="fa fa-calendar"></i> Posisi : <?php echo $this->global_m->get_array_data($emp_org, "position_name"); ?></a></li>
                        <li><a href="javascript:;"> <i class="fa fa-bell-o"></i> Unit : <?php echo $this->global_m->get_array_data($emp_org, "org_stext"); ?></a></li>
                        <li><a href="javascript:;"> <i class="fa fa-envelope-o"></i> Perusahaan : <?php echo $this->global_m->get_array_data($emp_org, "PR"); ?></a></li>
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