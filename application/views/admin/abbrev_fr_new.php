<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Add Abbreviation
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="fr_insert" name="fr_insert" action="<?php echo $base_url; ?>index.php/admin/abbrev_new" method="post">
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Subty</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="SUBTY" name="SUBTY" style="padding: 3px 0px;">
                                    <?
                                    for ($i = 0; $i < count($abbrev); $i++) {
                                        $selected = "";
                                        echo "<option value='" . $abbrev[$i]['SUBTY'] . "' " . $selected . ">" . $abbrev[$i]['STEXT'] . " </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div><div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Short</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="SHORT" name="SHORT" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Stext</label>
                            <div class="col-lg-10">
                                
                                <input type="text" class="form-control" id="STEXT" name="STEXT" value="">
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
            <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/abbrev"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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