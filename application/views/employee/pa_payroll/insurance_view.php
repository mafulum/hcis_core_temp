<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                BPJS Kesehatan / Asuransi (Cost)
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
                            if ($insty[$i]['id'] == $this->global_m->get_array_data($frm, "INSTY")) {
                                echo $insty[$i]['text'];
                                break;
                            }
                        }
                        ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">% Employee</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data_num($frm, "PRCTE"); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">% Company</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data_num($frm, "PRCTC"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Max Employee</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data_num($frm, "MAXRE"); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Max Company</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data_num($frm, "MAXRC"); ?></label>
                </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_payroll/personal_insurance/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>