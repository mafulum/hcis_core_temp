<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    PIC Customer Update
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" name="fr_update" id="fr_update" action="<?php echo $base_url; ?>index.php/admin/pic_customer_upd" method="post">
                        <input type="hidden" name="id" value="<?php echo $this->global_m->get_array_data($frm, "id"); ?>"/> 
                        <div class="form-group" >
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                            <div class="col-lg-10">
                                <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control dpd1" name="begda" id="begda" value="<?php echo $this->global_m->get_array_data($frm, "begda", $this->global_m->DATE_MYSQL); ?>">
                                    <span class="input-group-addon">To</span>
                                    <input type="text" class="form-control dpd2" name="endda" id="endda" value="<?php echo $this->global_m->get_array_data($frm, "endda", $this->global_m->DATE_MYSQL); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">WERKS</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="WERKS" name="WERKS" style="padding: 3px 0px;">
                                    <?php
                                    for ($i = 0; $i < count($werks); $i++) {
                                        $selected = "";
                                        if($werks[$i]['id']==$this->global_m->get_array_data($frm, "WERKS"))
                                                $selected="selected";
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
                                    <option value='F020' <?php if("F020"==$this->global_m->get_array_data($frm, "type")){ echo "selected";} ?>>Operational Representative Management</option>
                                    <option value='F002' <?php if("F002"==$this->global_m->get_array_data($frm, "type")){ echo "selected";} ?>>PIC Customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Pernr/Nopeg</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="pernr" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "pernr"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $this->global_m->get_array_data($frm, "nama"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $this->global_m->get_array_data($frm, "email"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Position</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="position" name="position" value="<?php echo $this->global_m->get_array_data($frm, "position"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Unit Short</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="unit_short" name="unit_short" value="<?php echo $this->global_m->get_array_data($frm, "unit_short"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Unit Text</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="unit_stext" name="unit_stext" value="<?php echo $this->global_m->get_array_data($frm, "unit_stext"); ?>">
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
            <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/pic_customer" ; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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