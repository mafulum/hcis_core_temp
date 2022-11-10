<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Family
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" action="<?php echo $base_url; ?>index.php/employee/personal_data_fam_new" method="post" new="pd_fam_fr_new" id="pd_fam_fr_new">
                    <input type="hidden" id="pernr" name="pernr" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"/>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" name="begda" id="begda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" name="endda" id="endda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Family Member</label>
                        <div class="col-lg-6">
                            <select class="form-control" id="fSubty" name="fSubty" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($osubty); $i++) {
                                    echo "<option value='" . $osubty[$i]['id'] . "' >" . $osubty[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Number</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" id="cObjps" name="cObjps">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Name</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="cName" name="cName">
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Gender</label>
                        <div class="col-lg-2">
                            <select class="form-control" id="fGesch" name="fGesch" style="padding: 3px 0px;">
                            <?
                            for ($i = 0; $i < count($fgesch); $i++) {
                                echo "<option value='" . $fgesch[$i]['id'] . "' >" . $fgesch[$i]['text'] ."</option>";
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input6" class="col-lg-2 col-sm-2 control-label">Birthplace</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="cGblnd" name="cGblnd">
                        </div>
                        <label for="input6" class="col-lg-2 col-sm-2 control-label">Date of birth</label>
                        <div class="col-lg-4">
                            <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" name="cGbdat" id="cGbdat"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Nationality</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="fNat" name="fNat" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($fNat); $i++) {
                                    echo "<option value='" . $fNat[$i]['id'] . "' >" . $fNat[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Country of Birth</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="fCty" name="fCty" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($fCty); $i++) {
                                    echo "<option value='" . $fCty[$i]['id'] . "' >" . $fCty[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Identity Type</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="fWN" name="fWN" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($fWN); $i++) {
                                    echo "<option value='" . $fWN[$i]['id'] . "' >" . $fWN[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Doc Cert.</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="cDocrt" name="cDocrt">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Benefit Include</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="fIncben" name="fIncben" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($fIncben); $i++) {
                                    echo "<option value='" . $fIncben[$i]['id'] . "' >" . $fIncben[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Status.</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="ffamstat" name="ffamstat" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($ffamstat); $i++) {
                                    echo "<option value='" . $ffamstat[$i]['id'] . "' >" . $ffamstat[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Note</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="cNote" name="cNote">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/personal_data_family" . "/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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