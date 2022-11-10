<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                BPJS Kesehatan / Asuransi (Cost)
                <?php if($this->common->check_permission('EmployeeMasterData.Payroll.Insurance.Maintain')){ ?>
                <a class="btn btn-danger btn-xs pull-right" href="<?= base_url(); ?>index.php/memp_payroll/personal_insurance_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
                <?php } ?>
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Insty</th>
                        <th>% Emp</th>
                        <th>% Comp</th>
                        <th>Max IDR Emp</th>
                        <th>Max IDR Comp</th>
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
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "stext"); ?></td>
                                <td><?php echo $this->global_m->get_array_data_num3decimal($ov[$i], "PRCTE"); ?></td>
                                <td><?php echo $this->global_m->get_array_data_num3decimal($ov[$i], "PRCTC"); ?></td>
                                <td><?php echo $this->global_m->get_array_data_num3($ov[$i], "MAXRE"); ?></td>
                                <td><?php echo $this->global_m->get_array_data_num3($ov[$i], "MAXRC"); ?></td>
                                <td>
                                    <?php if($this->common->check_permission('EmployeeMasterData.Payroll.Insurance.View')){ ?>
                                    <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/memp_payroll/personal_insurance_view/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'emp_insurance_id'); ?>" data-toggle="modal"> <i class="fa fa-search"></i> </a>
                                    <?php } if($this->common->check_permission('EmployeeMasterData.Payroll.Insurance.Maintain')){ ?>
                                    <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/memp_payroll/personal_insurance_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'emp_insurance_id'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                    <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/memp_payroll/personal_insurance_del/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'emp_insurance_id'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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