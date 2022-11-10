<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    Matrix Job Score
                    <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/admin/matrixjs_fr/" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
                </header>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>UniT Stext</th>
                            <th>STELL</th>
                            <th>STELL Stext</th>
                            <th>Score</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($table) == 0) {
                            ?>
                            <tr>
                                <td colspan="6">No Data</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($table); $i++) {
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "o_SHORT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "o_STEXT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "ma_short"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "ma_STEXT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "SCORE"); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/admin/matrixjs_fr/<?php echo $this->global_m->get_array_data($table[$i], 'id_matrix'); ?>');" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?=base_url();?>index.php/admin/matrixjs_del/<?php echo $this->global_m->get_array_data($table[$i], 'id_matrix'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
                                    </td>
                                </tr>
                                <?php
                            }
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