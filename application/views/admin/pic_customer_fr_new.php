<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Add PIC Customer
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="fr_insert" name="fr_insert" action="<?php echo $base_url; ?>index.php/admin/pic_customer_new" method="post">
                        <div class="form-group" >
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
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">WERKS</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="WERKS" name="WERKS" style="padding: 3px 0px;">
                                    <?php
                                    for ($i = 0; $i < count($werks); $i++) {
                                        echo "<option value='" . $werks[$i]['id'] . "' >" . $werks[$i]['text'] . " </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Type</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="type" name="type" style="padding: 3px 0px;">
                                    <option value='F020'>Operational Representative Management</option>
                                    <option value='F002'>PIC Customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Pernr/Nopeg</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="pernr" name="pernr" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="nama" name="nama" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="email" name="email" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Position</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="position" name="position" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Unit Short</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="unit_short" name="unit_short" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Unit Text</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="unit_stext" name="unit_stext" value="">
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