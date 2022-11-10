<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    Master Performance
                    <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/admin/mperformance_fr/" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
                </header>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Unit Stext</th>
                            <th>LMAX</th>
                            <th>MMAX</th>
                            <th>HMAX</th>
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
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "SHORT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "STEXT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "LMAX"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "MMAX"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "HMAX"); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/mperformance_fr/<?php echo $this->global_m->get_array_data($table[$i], 'id_perf'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?=base_url();?>index.php/admin/mperformance_del/<?php echo $this->global_m->get_array_data($table[$i], 'id_perf'); ?>/<?php echo $this->global_m->get_array_data($table[$i], 'ORGID'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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