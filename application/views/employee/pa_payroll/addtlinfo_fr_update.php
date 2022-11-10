<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Additinal Payment / Deduction
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/memp_payroll/personal_addtlinfo_upd" method="post" id="pd_addpayment_fr_update" name="pd_addtlinfo_fr_update">
                    <input type="hidden" id="emp_addpayment_id" name="id" value="<?php echo $this->global_m->get_array_data($frm, "id"); ?>"/> 
                    <input type="hidden" id="pernr" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Is OffCycle</label>
                        <div class="col-lg-4">
                            <input class="form-control form-control-inline" type="checkbox" name="is_offcycle" id="is_offcycle" <?php if(!empty($frm["is_offcycle"])) { echo "checked"; } ?>/>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Event Date</label>
                        <div class="col-lg-4">
                            <input class="form-control form-control-inline input-medium default-date-picker"  value="<?php if(!empty($frm["date_offcycle"])) { echo $this->global_m->get_array_data($frm, "date_offcycle"); } ?>" size="16" type="text" name="date_offcycle" id="date_offcycle"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode Regular</label>
                        <div class="col-lg-4">
                            <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="yyyy-mm" data-date=""  class="input-append date dpMonths" id="cPeriodRegular">
                                <input type="text" size="16" class="form-control" id="periode_regular" name="periode_regular" value="<?php if(!empty($frm["periode_regular"])) { echo $this->global_m->get_array_data($frm, "periode_regular"); } ?>">
                                    <span class="input-group-btn add-on">
                                      <button class="btn btn-danger" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                            </div>
                            <span class="help-block">Select month only</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Note</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="note" name="note" value="<?php echo $this->global_m->get_array_data($frm, "note"); ?>">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_payroll/personal_addtlinfo/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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