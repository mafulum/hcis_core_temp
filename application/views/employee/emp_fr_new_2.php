<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                ADD NEW EMPLOYEE - ORGANIZATIONAL ASSIGNMENT (2)
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/add_new_emp_om/<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>" method="post" id="emp2_update" new="emp2_update">
                <div class="form-group">
                    <label for="fComp" class="col-lg-2 col-sm-2 control-label">Company</label>
                    <div class="col-lg-10">
                            <?php echo $this->global_m->get_array_data($frm['unit'], "text"); ?>
                    </div>
                </div><div class="form-group">
                    <label for="fComp" class="col-lg-2 col-sm-2 control-label">Employee</label>
                    <div class="col-lg-10">
                            <?php echo $this->global_m->get_array_data($frm['emp'], "CNAME"); ?> / <?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>
                        <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/>
                        <input type="hidden" name="werks" value="<?php echo $this->global_m->get_array_data($frm, "WERKS"); ?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <div class="col-lg-10">
                        <div class="input-group input-large" data-date-format="dd.mm.yyyy">
                            <input type="text" class="form-control dpd1" id="begda" name="begda" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA"); ?>">
                            <span class="input-group-addon">To</span>
                            <input type="text" class="form-control dpd2" id="endda" name="endda" value="<?php echo $this->global_m->get_array_data($frm, "ENDDA"); ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Payroll Area</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="fabkrs" name="fabkrs" style="padding: 3px 0px;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Unit</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="forgeh" name="forgeh" style="padding: 3px 0px;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Position</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="fplans" name="fplans" style="padding: 3px 0px;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Wilayah Kerja</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="fbtrtl" name="fbtrtl" style="padding: 3px 0px;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input5" class="col-lg-2 col-sm-2 control-label">Employee Group</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="fpersg" name="fpersg" style="padding: 3px 0px;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input5" class="col-lg-2 col-sm-2 control-label">Employee SubGroup</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="fpersk" name="fpersk" style="padding: 3px 0px;">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>

        </section>
    </div>
    <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/master"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
    <div class="modal fade" id="confirm-update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Update Employee (2) Organizational Assignment</h4>
                </div>
            
                <div class="modal-body">
                    <p>You are about to update, this procedure is irreversible.</p>
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
</section>


