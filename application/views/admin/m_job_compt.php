<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/job_form" method="post">
                <header class="panel-heading">
                    Job Form
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
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">OBJID</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="OBJID" name="OBJID">
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
                        <button type="submit" class="btn btn-success" id="btnForm">Add JOB</button>
                    </div>
                </div>
            </form>
                <header class="panel-heading">
                    Job List
                </header>
                
                <table class="table table-striped table-hover">
                    <tbody>
                        <tr>
                            <th>BEGDA</th>
                            <th>ENDDA</th>
                            <th>OBJID</th>
                            <th>SHORT</th>
                            <th>STEXT</th>
                            <th></th>
                        </tr>
                        <?php
                        for ($i = 0; $i < count($table); $i++) {
                            ?>
                            <tr>
                                <td><?php echo $this->global_m->get_array_data($table[$i], "BEGDA"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($table[$i], "ENDDA"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($table[$i], "OBJID"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($table[$i], "SHORT"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($table[$i], "STEXT"); ?></td>
                                <td>
                                    <!--<a class="btn btn-primary btn-xs" href="#" onclick="update(<?php echo $this->global_m->get_array_data($table[$i], 'id_org'); ?>)"> <i class="fa fa-pencil"></i> </a>
                                    <a class="btn btn-warning btn-xs" href="#" onclick="delimit(<?php echo $this->global_m->get_array_data($table[$i], 'id_org'); ?>)" data-toggle="modal"> <i class="fa fa-code"></i> </a>
                                    -->
                                    <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/job_compt/<?php echo $this->global_m->get_array_data($table[$i], 'OBJID'); ?>" > <i class="fa fa-list"></i> </a>
                                    <a class="btn btn-danger btn-xs" href="<?= base_url(); ?>index.php/admin/delete_job/<?php echo $this->global_m->get_array_data($table[$i], 'id_org'); ?>/<?php echo $this->global_m->get_array_data($table[$i], 'OBJID'); ?>" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </section>
            <!--widget end-->
        </div>
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
</section>