<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Superior Matriks
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" action="<?php echo $base_url; ?>index.php/employee/emp_sup_matriks_new" method="post" id="fr_new">
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
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">SUBTY</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="SUBTY" name="SUBTY" style="padding: 3px 0px;">
                                <?php
                                for ($i = 0; $i < count($SUBTY); $i++) {
                                    echo "<option value='" . $SUBTY[$i]['id'] . "' >" . $SUBTY[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">WERKS</label>
                        <div class="col-lg-4">
                            <select class="form-control" id="WERKS" name="WERKS" style="padding: 3px 0px;">
                                <?php
                                for ($i = 0; $i < count($WERKS); $i++) {
                                    echo "<option value='" . $WERKS[$i]['id'] . "' >" . $WERKS[$i]['text'] ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Nopeg Matriks</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="PERNR_MATRIKS" name="PERNR_MATRIKS" style="padding: 3px 0px;">
                            </select>
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/emp_sup_matriks" . "/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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