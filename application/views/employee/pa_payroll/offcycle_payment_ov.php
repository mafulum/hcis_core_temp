<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                OffCycle Payment
            </header>
            <table class="table table-striped table-hover" id="paTable">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>Event Date</th>
                        <th>WGTYP</th>
                        <th>LGTXT</th>
                        <th>WAMNT</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($ov) == 0) {
                        ?>
                        <tr>
                            <td colspan="5">No Data</td>
                        </tr>
                        <?php
                    } else {
                        for ($i = 0; $i < count($ov); $i++) {
                            ?>
                            <tr>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "BEGDA", $this->global_m->DATE_MYSQL); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "EVTDA", $this->global_m->DATE_MYSQL); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "WGTYP"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "LGTXT"); ?></td>
                                <td><?php echo $this->global_m->get_array_data_num3($ov[$i], "WAMNT"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "NOTE"); ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <a class="btn btn-default" href="<?= base_url(); ?>index.php/employee/master/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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