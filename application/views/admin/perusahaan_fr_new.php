<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Perusahaan Update
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="fr_insert" name="fr_insert"  action="<?php echo $base_url; ?>index.php/admin/perusahaan_new" method="post">                        
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Nama Perusahaan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="perusahaan" name="perusahaan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Kode</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="kode" name="kode">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">PERIODE</label>
                            <div class="col-lg-10">
                                <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control dpd1" name="BEGDA">
                                    <span class="input-group-addon">To</span>
                                    <input type="text" class="form-control dpd2" name="ENDDA">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Level</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="LEVEL" name="LEVEL">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Seq</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="SEQ" name="SEQ">
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
        <div class="modal fade" id="confirm-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Confirm Insert</h4>
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