<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Add Criteria Readiness
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="fr_update" name="fr_update" action="<?php echo $base_url; ?>index.php/admin/cr_readiness_upd" method="post">
                        <input type="hidden" name="id_criteria" value="<?php echo $this->global_m->get_array_data($frm, "id_criteria"); ?>"/> 
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Desc</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="DESC" name="DESC" value="<?php echo $this->global_m->get_array_data($frm, "DESC"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Subty</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="SUBTY" name="SUBTY" style="padding: 3px 0px;">
                                    <?
                                    for ($i = 0; $i < count($SUBTY); $i++) {
                                        $selected = "";
                                        if ($SUBTY[$i]['id'] == $this->global_m->get_array_data($frm, "SUBTY"))
                                            $selected = "selected='selected'";
                                        echo "<option value='" . $SUBTY[$i]['id'] . "' " . $selected . ">" . $SUBTY[$i]['desc'] . " </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Percentage</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="PERCT" name="PERCT" value="<?php echo $this->global_m->get_array_data($frm, "PERCT"); ?>">
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
            <?php if($this->global_m->get_array_data($frm, "SUBTY")==2){ ?>
            <section class="panel">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/cr_readiness_detail_upd/<?php echo $this->global_m->get_array_data($frm, "id_criteria"); ?>" method="post">
                    <input type="hidden" name="id_criteria" value="<?php echo $this->global_m->get_array_data($frm, "id_criteria"); ?>"/> 
                    <header class="panel-heading">
                        Detail Criteria Readiness
                        <span class="tools pull-right">
                            <a class="fa fa-chevron-down" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body" >
                        <label for="input5" class="col-lg-2 col-sm-2 control-label">SUBTY DETAIL</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="SUBTY" name="SUBTY" style="padding: 3px 0px;">
                                <?
                                for ($i = 0; $i < count($SUBTYD); $i++) {
                                    echo "<option value='" . $SUBTYD[$i]['id'] . "'  >" . $SUBTYD[$i]['desc'] ." </option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="panel-body" >
                        <label for="input5" class="col-lg-2 col-sm-2 control-label">MIN (RANGE) / VALUE (SINGLE)</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="MIN" name="MIN">
                        </div>
                    </div>
                    <div class="panel-body" >
                        <label for="input5" class="col-lg-2 col-sm-2 control-label">MAX (RANGE) </label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="MAX" name="MAX">
                        </div>
                    </div>
                    <div class="panel-body" >
                        <label for="input5" class="col-lg-2 col-sm-2 control-label">PERCT</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="PERCT" name="PERCT">
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-success">ADD Detail</button>
                        </div>
                    </div>
                </form>
                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>SUBTY</th>
                                <th>MIN</th>
                                <th>MAX</th>
                                <th>PERCT</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($table) == 0) {
                                ?>
                                <tr>
                                    <td colspan="4">No Unit Maintain</td>
                                </tr>
                                <?php
                            } else {
                                for ($i = 0; $i < count($table); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $ASUBTYD[$this->global_m->get_array_data($table[$i], "SUBTY")]; ?></td>
                                        <td><?php echo $this->global_m->get_array_data($table[$i], "MIN"); ?></td>
                                        <td><?php echo $this->global_m->get_array_data($table[$i], "MAX"); ?></td>
                                        <td><?php echo $this->global_m->get_array_data($table[$i], "PERCT"); ?></td>
                                        <td>
                                            <a class="btn btn-danger btn-xs" href="<?= base_url(); ?>index.php/admin/cr_readinessd_del/<?php echo $this->global_m->get_array_data($frm, "id_criteria"); ?>/<?php echo $this->global_m->get_array_data($table[$i], "id_re_detail"); ?>" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
<?php } ?>
            <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/cr_readiness"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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