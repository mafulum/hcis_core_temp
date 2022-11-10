<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                BPJS Tenaga Kerja
                <?php if($this->common->check_permission('EmployeeMasterData.Payroll.BPJSTK.Maintain')){ ?>
                <a class="btn btn-danger btn-xs pull-right" href="<?= base_url(); ?>index.php/memp_payroll/personal_bpjs_tk_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
                <?php } ?>
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>BPJS ID</th>
                        <th>Fclty Type</th>
                        <th>Reg. Date</th>
                        <th></th>
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
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "ENDDA", $this->global_m->DATE_MYSQL); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "BPJSID"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "FCLTY"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "RDATE", $this->global_m->DATE_MYSQL); ?></td>
                                <td>
                                    <?php if($this->common->check_permission('EmployeeMasterData.Payroll.BPJSTK.View')){ ?>
                                    <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/memp_payroll/personal_bpjs_tk_view/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'id_emp_bpjs_tk'); ?>" data-toggle="modal"> <i class="fa fa-search"></i> </a>
                                    <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.BPJSTK.Maintain')){ ?>
                                    <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/memp_payroll/personal_bpjs_tk_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'id_emp_bpjs_tk'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                    <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/memp_payroll/personal_bpjs_tk_del/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'id_emp_bpjs_tk'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
                                    <?php } ?>
                                </td>
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