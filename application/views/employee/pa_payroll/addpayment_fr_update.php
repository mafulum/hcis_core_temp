<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Additinal Payment / Deduction
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/memp_payroll/personal_addpayment_upd" method="post" id="pd_addpayment_fr_update" name="pd_addpayment_fr_update">
                    <input type="hidden" id="emp_addpayment_id" name="emp_addpayment_id" value="<?php echo $this->global_m->get_array_data($frm, "emp_addpayment_id"); ?>"/> 
                    <input type="hidden" id="pernr" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Begin Date</label>
                        <div class="col-lg-4" data-date-format="dd.mm.yyyy">
                            <input class="form-control form-control-inline input-medium default-date-picker" data-date-format="dd.mm.yyyy" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?>"  size="16" type="text" name="begda" id="begda"/>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Event Date</label>
                        <div class="col-lg-4" data-date-format="dd.mm.yyyy">
                            <input class="form-control form-control-inline input-medium default-date-picker" data-date-format="dd.mm.yyyy"  value="<?php echo $this->global_m->get_array_data($frm, "EVTDA", $this->global_m->DATE_MYSQL); ?>" size="16" type="text" name="evtda" id="evtda"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Wage Type</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="wgtyp" name="wgtyp" style="padding: 3px 0px;">
                                <?php
                                for ($i = 0; $i < count($wgtyps); $i++) {
                                    $selected = "";
                                    if($wgtyps[$i]['WGTYP']==$this->global_m->get_array_data($frm, "WGTYP"))
                                            $selected="selected";
                                    echo "<option value='" . $wgtyps[$i]['WGTYP'] . "' ".$selected.">" . $wgtyps[$i]['LGTXT'] . " ( ".$wgtyps[$i]['WGTYP']." ) </option>";
                                }
                                ?>
                            </select>   
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Amount</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="wamnt" name="wamnt" value="<?php echo $this->global_m->get_array_data_num($frm, "WAMNT"); ?>">
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Point</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="wapnt" name="wapnt" value="<?php echo $this->global_m->get_array_data_num($frm, "WAPNT"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Note</label>
                        <div class="col-lg-10">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_payroll/personal_addpayment/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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