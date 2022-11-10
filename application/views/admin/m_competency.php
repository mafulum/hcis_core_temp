<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    Company List (Master Competency)
                </header>
                <table class="table table-striped table-hover">
                    <tbody>
                        <tr>
                        <td>GDPS Competency</td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/m_competency/10000000/400" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                        </td>
                    </tr>
                        <?php
                            for ($i = 0; $i < count($table); $i++) {
                                if( $this->global_m->get_array_data($table[$i], 'OBJID')=="10000000" && $this->global_m->get_array_data($table[$i], 'k')=="400"){
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "STEXT"); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/m_competency/<?php echo $this->global_m->get_array_data($table[$i], 'OBJID'); ?>/<?php echo $this->global_m->get_array_data($table[$i], 'k'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
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