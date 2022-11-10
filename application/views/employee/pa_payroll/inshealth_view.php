<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                BPJS Kesehatan / Asuransi
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">To</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Asuransi</label>
                    <label for="input7" class="col-lg-10 col-sm-10 control-label">
                            <?php
                            for ($i = 0; $i < count($insty); $i++) {
                                if ($insty[$i]['id'] == $this->global_m->get_array_data($frm, "INSTY")){
                                    echo $insty[$i]['name'];
                                    break;
                                }
                            }
                            ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">No Asuransi</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "INSID"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Reg. Date</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "RDATE", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input7" class="col-lg-2 col-sm-2 control-label">Holder of Insurance</label>
                    <label for="input7" class="col-lg-10 col-sm-10 control-label">
                        <?php
                        if ("9999" == $this->global_m->get_array_data($frm, "FAMSA")) {
                            echo "Self Employee";
                        } else {
                            for ($i = 0; $i < count($afam); $i++) {
                                if ($afam[$i]['id_emp_fam'] == $this->global_m->get_array_data($frm, "id_emp_fam")) {
                                    echo $afam[$i]['CNAME'];
                                    break;
                                }
                            }
                        }
                        ?>
                    </label>
                </div>

            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_payroll/personal_inshealth/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>