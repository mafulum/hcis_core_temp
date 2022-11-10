<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Organization Assignment (Old)
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/organizational_assignment_old_upd" method="post" id="fr_update" name="fr_update">
                    <input type="hidden" name="id_eorg" value="<?php echo $this->global_m->get_array_data($frm, "id_eorg"); ?>"/> 
                    <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?>" name="begda" id="begda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" value="<?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?>" name="endda" id="endda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Company</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" style="padding: 3px 0px;" id="fbukrs" name="fbukrs" value="<?php echo $this->global_m->get_array_data($frm, "BUKRS"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Area</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fbtrtl" name="fbtrtl" value="<?php echo $this->global_m->get_array_data($frm, "BTRTL"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Unit</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="forgeh" name="forgeh" value="<?php echo $this->global_m->get_array_data($frm, "ORGEH"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Position</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fplans" name="fplans" value="<?php echo $this->global_m->get_array_data($frm, "PLANS"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input7" class="col-lg-2 col-sm-2 control-label">Job</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fstell" name="fstell" value="<?php echo $this->global_m->get_array_data($frm, "STELL"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input7" class="col-lg-2 col-sm-2 control-label">Job Group</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" style="padding: 3px 0px;" id="ffam" name="ffam" value="<?php echo $this->global_m->get_array_data($frm, "FAMILY"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input8" class="col-lg-2 col-sm-2 control-label">Employee Group</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" style="padding: 3px 0px;" id="fpersg" name="fpersg" value="<?php echo $this->global_m->get_array_data($frm, "PERSG"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input8" class="col-lg-2 col-sm-2 control-label">Employee SubGroup</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" style="padding: 3px 0px;" id="fpersk" name="fpersk" value="<?php echo $this->global_m->get_array_data($frm, "PERSK"); ?>">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/organizational_assignment_old_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>

    <div class="modal fade" id="confirm-update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Update</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to insert, this procedure is irreversible.</p>
                    <p><b><span id="mb"></span></b></p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>