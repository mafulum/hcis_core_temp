<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    PIC Customer
                    <a class="btn btn-danger btn-xs pull-right" href="<?= base_url(); ?>index.php/admin/pic_customer_fr/" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
                </header>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>begda</th>
                            <th>endda</th>
                            <th>type</th>
                            <th>WERKS</th>
                            <th>PERNR</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Unit SHORT</th>
                            <th>Unit STEXT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($table) == 0) {
                            ?>
                            <tr>
                                <td colspan="4">No Data</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($table); $i++) {
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "begda"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "endda"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "type"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "WERKS"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "pernr"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "nama"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "email"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "position"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "unit_short"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "unit_stext"); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/pic_customer_fr/<?php echo $this->global_m->get_array_data($table[$i], 'id'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/admin/pic_customer_del/<?php echo $this->global_m->get_array_data($table[$i], 'id'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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