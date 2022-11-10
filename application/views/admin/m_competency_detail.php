<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/m_competency_cg_add" method="post">
                <input type="hidden" name="sPrefix" value="<?php echo $sPrefix; ?>"/> 
                <input type="hidden" name="objid" value="<?php echo $objid; ?>"/> 
                <header class="panel-heading">
                    Competency Group <?php echo $header_text; ?>
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>    
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <div class="col-lg-10">
                        <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control dpd1" name="begda" id="begda">
                            <span class="input-group-addon">To</span>
                            <input type="text" class="form-control dpd2" name="endda" id="endda">
                        </div>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">SHORT</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="SHORT" name="SHORT">
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">STEXT</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="SHORT" name="STEXT">
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">ADD Competency Group</button>
                    </div>
                </div>
            </form>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Competency Group</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($KC) == 0) {
                            ?>
                            <tr>
                                <td colspan="4">No Competency Group</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($KC); $i++) {
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($KC[$i], "STEXT") . " (" . $this->global_m->get_array_data($KC[$i], "SHORT") . ")"; ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/admin/m_competency_cg_del/<?php echo $objid . "/" . $sPrefix . "/" . $this->global_m->get_array_data($KC[$i], 'id_compt'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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
        <?php
        if(!empty($KC)){
        ?>
        <section class="panel">
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/m_competency_cd_add" method="post">
                <input type="hidden" name="sPrefix" value="<?php echo $sPrefix; ?>"/> 
                <input type="hidden" name="objid" value="<?php echo $objid; ?>"/> 
                <header class="panel-heading">
                    Competency <?php echo $header_text; ?>
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                    
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <div class="col-lg-10">
                        <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control dpd1" name="begda" id="begda">
                            <span class="input-group-addon">To</span>
                            <input type="text" class="form-control dpd2" name="endda" id="endda">
                        </div>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Group</label>
                    <div class="col-lg-10">
                            <select class="form-control" id="OTYPE" name="OTYPE" style="padding: 3px 0px;">
                                <?php
                                for ($i = 0; $i < count($KC); $i++) {
                                    echo "<option value='" . $KC[$i]['SHORT'] . "'>" . $KC[$i]['STEXT'] . " ( ".$KC[$i]['SHORT']." )</option>";
                                }
                                ?>
                            </select>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">SHORT</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="SHORT" name="SHORT">
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">STEXT</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="SHORT" name="STEXT">
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">ADD Competency</button>
                    </div>
                </div>
            </form>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>SHORT</th>
                            <th>STEXT</th>
                            <th>Group</th>
                            <th>BEGDA</th>
                            <th>ENDDA</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($cComp) == 0) {
                            ?>
                            <tr>
                                <td colspan="6">No Competency Maintain</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($cComp); $i++) {
                                $grp=$this->global_m->get_array_data($cComp[$i], "OTYPE");
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($cComp[$i], "SHORT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($cComp[$i], "STEXT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($dKey, $grp); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($cComp[$i], "BEGDA"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($cComp[$i], "ENDDA"); ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/admin/m_competency_cd_del//<?php echo $objid . "/" . $sPrefix . "/" . $this->global_m->get_array_data($cComp[$i], 'id_compt'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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
        <?php
        }
        ?>
    </div>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to delete, this procedure is irreversible.</p>
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
    <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/m_competency"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
</section>