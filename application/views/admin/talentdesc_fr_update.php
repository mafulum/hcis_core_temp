<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Talent Description 
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="fr_update" name="fr_update"  action="<?php echo $base_url; ?>index.php/admin/talentdesc_upd" method="post"> 
                        <input type="hidden" name="id_desc" value="<?php echo $this->global_m->get_array_data($frm, "id_desc"); ?>"/>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">SHORT</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="SHORT" name="SHORT" value="<?php echo $this->global_m->get_array_data($frm, "SHORT"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">STEXT</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="STEXT" name="STEXT" value="<?php echo $this->global_m->get_array_data($frm, "STEXT"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">DESC</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="DESC" name="DESC" value="<?php echo $this->global_m->get_array_data($frm, "DESC"); ?>">
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
            <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/talentdesc"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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