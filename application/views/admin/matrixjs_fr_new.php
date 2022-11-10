<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Matrix Job Score [ADD NEW]
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="fr_insert" name="fr_insert"  action="<?php echo $base_url; ?>index.php/admin/matrixjs_new" method="post">
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">ORG</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="ORGID" name="ORGID" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($ORGID); $i++) {
                                    $selected = "";
                                    echo "<option value='" . $ORGID[$i]['OBJID'] . "' " . $selected . ">" . $ORGID[$i]['STEXT'] . " ( ".$ORGID[$i]['SHORT']. " )</option>";
                                }
                                ?>
                            </select>
                            </div>
                        </div><div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">STELL</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="STELL" name="STELL" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($STELL); $i++) {
                                    $selected = "";
                                    echo "<option value='" . $STELL[$i]['id'] . "' " . $selected . ">" . $STELL[$i]['text'] . " ( ".$STELL[$i]['id']. " )</option>";
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">SCORE</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="STEXT" name="SCORE" value="">
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
            <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/matrix_js" ; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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