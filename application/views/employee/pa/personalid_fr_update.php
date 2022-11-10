<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Monitoring of Task
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/emp_personalid_upd" method="post" id="form_fr_update" name="form_fr_update">
                    <input type="hidden" id="id" name="id" value="<?php echo $this->global_m->get_array_data($frm, "emp_personalid_id"); ?>"/> 
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
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Type</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="subty" name="subty" style="padding: 3px 0px;">
                                <?php
                                for ($i = 0; $i < count($subty); $i++) {
                                    $selected = "";
                                    if($subty[$i]['id']==$this->global_m->get_array_data($frm, "SUBTY"))
                                            $selected="selected";
                                    echo "<option value='" . $subty[$i]['id'] . "' " . $selected . ">" . $subty[$i]['text'] . " </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">ICNUM</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="icnum" name="icnum" value="<?php echo $this->global_m->get_array_data($frm, "ICNUM"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">REff NAME</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="idesc" name="idesc" value="<?php echo $this->global_m->get_array_data($frm, "IDESC"); ?>">
                        </div>
                        <label for="input6" class="col-lg-2 col-sm-2 control-label">Note</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="note" name="note" value="<?php echo $this->global_m->get_array_data($frm, "NOTE"); ?>">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/emp_personalid/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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