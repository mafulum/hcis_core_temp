<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Organization Assignment
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/organizational_assignment_old_upd" method="post">
                    <input type="hidden" name="id_eorg" value="<?php echo $this->global_m->get_array_data($frm, "id_eorg"); ?>"/> 
                    <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA",$this->global_m->DATE_MYSQL); ?>" name="begda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" value="<?php echo $this->global_m->get_array_data($frm, "ENDDA",$this->global_m->DATE_MYSQL); ?>" name="endda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Organisasi</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="cname" name="orgeh_text" value="<?php echo $this->global_m->get_array_data($frm, "ORGEH_TEXT"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Posisi</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="cname" name="plans_text" value="<?php echo $this->global_m->get_array_data($frm, "PLANS_TEXT"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input7" class="col-lg-2 col-sm-2 control-label">Location</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="input7" name="locat" value="<?php echo $this->global_m->get_array_data($frm, "LOCAT"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input8" class="col-lg-2 col-sm-2 control-label">Note</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="input8" name="text1" value="<?php echo $this->global_m->get_array_data($frm, "TEXT1"); ?>">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/organizational_assignment_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>