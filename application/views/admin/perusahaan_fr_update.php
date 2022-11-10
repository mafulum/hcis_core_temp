<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Perusahaan Update -> <?php echo $iSeq; ?>
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="fr_update" name="fr_update" action="<?php echo $base_url; ?>index.php/admin/perusahaan_upd" method="post">
                        <input type="hidden" name="id_level" value="<?php echo $this->global_m->get_array_data($frm, "id_level"); ?>"/> 
                        <input type="hidden" name="objid" value="<?php echo $this->global_m->get_array_data($frm, "OBJID"); ?>"/> 
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Short</label>
                            <div class="col-lg-10">
                                <label for="input3" class="">: <?php echo $this->global_m->get_array_data($frm, "SHORT"); ?></label>
                            </div>
                        </div><div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Type</label>
                            <div class="col-lg-10">
                                <label for="input3" class="">: <?php echo $this->global_m->get_array_data($frm, "STEXT"); ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">PERIODE</label>
                            <div class="col-lg-10">
                                <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control dpd1" name="BEGDA"  value="<?php echo $this->global_m->get_array_data($frm, "BEGDA",$this->global_m->DATE_MYSQL); ?>" >
                                    <span class="input-group-addon">To</span>
                                    <input type="text" class="form-control dpd2" name="ENDDA"  value="<?php echo $this->global_m->get_array_data($frm, "ENDDA",$this->global_m->DATE_MYSQL); ?>" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Level</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="LEVEL" name="LEVEL" value="<?php echo $this->global_m->get_array_data($frm, "LEVEL"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Seq</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="SEQ" name="SEQ" value="<?php echo $this->global_m->get_array_data($frm, "SEQ"); ?>">
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
            <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/perusahaan"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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