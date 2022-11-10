<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Employee Medical
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/emp_medical_upd" method="post">
                    <input type="hidden" name="id_medical" value="<?php echo $this->global_m->get_array_data($frm, "id_medical"); ?>"/> 
                    <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Medex Date</label>
                        <div class="col-lg-10">
                            <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" name="begda" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA",$this->global_m->DATE_MYSQL); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Type</label>
                        <div class="col-lg-10">
                            <input type="text" style="padding: 3px 0px;" class="form-control" id="subty" name="subty" value="<?php echo $this->global_m->get_array_data($frm, "SUBTY"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Note</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="text1" name="text1" value="<?php echo $this->global_m->get_array_data($frm, "TEXT1"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/emp_medical_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
    </div>