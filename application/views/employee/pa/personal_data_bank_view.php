<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Bank Detail
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">To</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Bank Name</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label">
                        <?php
                        for ($i = 0; $i < count($mbank); $i++) {
                            if($mbank[$i]['bank_mid']==$this->global_m->get_array_data($frm, "BANK_MID")){
                                echo $mbank[$i]['BANK_NAME'];
                                break;
                            }
                        }
                        ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Bank Account</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "BANK_ACCOUNT"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Name</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "BANK_PAYEE"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input6" class="col-lg-2 col-sm-2 control-label">Currency</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BANK_CURR"); ?></label>
                    <label for="input6" class="col-lg-2 col-sm-2 control-label">Bank Order</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BANK_ORDER"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input7" class="col-lg-2 col-sm-2 control-label">Note</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "BANK_NOTE"); ?></label>
                </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/personal_data_bank/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
    </div>