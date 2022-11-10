<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                BPJS Tenaga Kerja
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">To</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">No BPJS</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "BPJSID"); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Facilty Type</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "FCLTY"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Reg. Date</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "RDATE", $this->global_m->DATE_MYSQL); ?></label>
                </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_payroll/personal_bpjs_tk/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>