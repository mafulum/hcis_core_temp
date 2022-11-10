<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Overtime
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" action="<?php echo $base_url; ?>index.php/memp_tms/personal_overtime_new" method="post" new="pd_overtime_fr_new" id="pd_overtime_fr_new">
                    <input type="hidden" id="pernr" name="pernr" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"/>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Begin Time</label>
                        <div class="col-lg-4">
                            <input size="16" id="begti" name="begti" type="text" value="" class="form_datetime form-control">
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">End Time</label>
                        <div class="col-lg-4">
                            <input size="16" id="endti" name="endti" type="text" value="" class="form_datetime form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">IS WorkDay</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="ihday" name="ihday" value="1" style="padding: 3px 0px;">
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Date Payroll</label>
                        <div class="col-lg-4">
                            <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" name="prdpy" id="prdpy"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Overtime Point</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" id="otpnt" name="otpnt" value="" style="padding: 3px 0px;">
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Note</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="note" name="note" value="" style="padding: 3px 0px;">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_tms/personal_overtime" . "/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>
</div>

<div class="modal fade" id="confirm-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Insert</h4>
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