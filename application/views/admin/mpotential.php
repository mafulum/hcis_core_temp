<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    Master Potential
                    <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/admin/mpotential_fr/" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
                </header>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>LEVEL</th>
                            <th>BEGDA</th>
                            <th>ENDDA</th>
                            <th>MIN</th>
                            <th>MAX</th>
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
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "LEVEL"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "BEGDA", $this->global_m->DATE_MYSQL); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "ENDDA", $this->global_m->DATE_MYSQL); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "MIN"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "MAX"); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/mpotential_fr/<?php echo $this->global_m->get_array_data($table[$i], 'id_pot'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?=base_url();?>index.php/admin/mpotential_del/<?php echo $this->global_m->get_array_data($table[$i], 'id_pot'); ?>/<?php echo $this->global_m->get_array_data($table[$i], 'LEVEL'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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