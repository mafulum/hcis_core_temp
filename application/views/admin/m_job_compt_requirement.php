<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
                <header class="panel-heading">
                    JOB
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>    
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <div class="col-lg-10">
                        <?php echo $job['BEGDA'];?> s/d <?php echo $job['ENDDA']; ?>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">SHORT</label>
                    <div class="col-lg-10">
                        <?php echo $job['OBJID'];?>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">SHORT</label>
                    <div class="col-lg-10">
                        <?php echo $job['SHORT'];?>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">STEXT</label>
                    <div class="col-lg-10">
                        <?php echo $job['STEXT'];?>
                    </div>
                </div>
        </section>
        <section class="panel">
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/m_job_compt_req" method="post">
                <input type="hidden" name="STELL" value ="<?php echo $job['OBJID'];?>"/>
                <header class="panel-heading">
                    Competency Requirement
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                    
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <div class="col-lg-10">
                        <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control dpd1" name="BEGDA" id="BEGDA">
                            <span class="input-group-addon">To</span>
                            <input type="text" class="form-control dpd2" name="ENDDA" id="ENDDA">
                        </div>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Competency</label>
                    <div class="col-lg-10">
                           <select class="form-control" id="COMPT" name="COMPT" style="padding: 3px 0px;">
                                <?php
                                $aKC = [];
                                for ($i = 0; $i < count($compt); $i++) {
                                    $sAdd = "";
                                    if(!empty($compt[$i]['PARENT'])){
                                        $sAdd = $compt[$i]['PARENT']['STEXT']." - ";
                                    }
                                    echo "<option value='" . $compt[$i]['OBJID'] . "'>" .$sAdd. $compt[$i]['STEXT'] . " ( ".$compt[$i]['SHORT']." )</option>";
                                }
                                ?>
                            </select>
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Bobot</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="bobot" name="bobot" value="1">
                    </div>
                </div>
                <div class="panel-body" >
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Level</label>
                    <div class="col-lg-10">
                           <select class="form-control" id="REQV" name="REQV" style="padding: 3px 0px;">
                                <option value="1">L1</option>
                                <option value="2">L2</option>
                                <option value="3">L3</option>
                                <option value="4">L4</option>
                                <option value="5">L5</option>
                            </select>
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
                            <th>GROUP</th>
                            <th>COMPT</th>
                            <th>STEXT</th>
                            <th>Bobot</th>
                            <th>Level</th>
                            <th>BEGDA</th>
                            <th>ENDDA</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($table) == 0) {
                            ?>
                            <tr>
                                <td colspan="6">No Competency Maintain</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($table); $i++) {
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "PARENT_STEXT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "STEXT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "SHORT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "bobot"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "REQV"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "BEGDA"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "ENDDA"); ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?php echo $base_url; ?>index.php/admin/m_job_compt_del/<?php echo $this->global_m->get_array_data($table[$i], "id_jobcompt"); ?>/<?php echo $this->global_m->get_array_data($table[$i], "STELL"); ?>/<?php echo $this->global_m->get_array_data($table[$i], "COMPT"); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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
    <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/job_compt"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
</section>