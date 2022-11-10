<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                BPJS Kesehatan / Asuransi (Cost)
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/memp_payroll/personal_insurance_upd" method="post" id="pd_insurance_fr_update" name="pd_insurance_fr_update">
                    <input type="hidden" id="emp_insurance_id" name="emp_insurance_id" value="<?php echo $this->global_m->get_array_data($frm, "emp_insurance_id"); ?>"/> 
                    <input type="hidden" id="pernr" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control dpd1" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?>" name="begda" id="begda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" value="<?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?>" name="endda" id="endda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Asuransi</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="insty" name="insty" style="padding: 3px 0px;">
                                <?php
                                for ($i = 0; $i < count($insty); $i++) {
                                    $selected = "";
                                    if($insty[$i]['SHORT']==$this->global_m->get_array_data($frm, "INSTY"))
                                            $selected="selected";
                                    echo "<option value='" . $insty[$i]['id'] . "' " . $selected . ">" . $insty[$i]['text'] . " </option>";
                                }
                                ?>
                            </select>   
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">% Employee</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="prcte" name="prcte" value="<?php echo $this->global_m->get_array_data($frm, "PRCTE"); ?>">
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">% Company</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="prcte" name="prctc" value="<?php echo $this->global_m->get_array_data($frm, "PRCTC"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Max Employee</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="prcte" name="maxre" value="<?php echo $this->global_m->get_array_data($frm, "MAXRE"); ?>">
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Max Company</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="prcte" name="maxrc" value="<?php echo $this->global_m->get_array_data($frm, "MAXRC"); ?>">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_payroll/personal_insurance/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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