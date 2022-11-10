<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
	<form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/emp_edunf_ov/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" method="post" >
        <section class="panel">
            <header class="panel-heading">
                Employee Education Non Formal
                <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/employee/emp_edunf_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
			<th><input type="checkbox" name="cball" id="cball"/></th>
                        <th>Start Date</th>
                        <th>Institution</th>
                        <th>Name</th>
                        <th>Fee</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($ov) == 0) {
                        ?>
                        <tr>
                            <td colspan="6">No Data</td>
                        </tr>
                        <?php
                    } else {
                        for ($i = 0; $i < count($ov); $i++) {
                            ?>
                            <tr>
					<td><input type="checkbox" name="id[]" value="<?php echo $this->global_m->get_array_data($ov[$i], 'id_educ'); ?>" class="cb"/></td>
                                <td><a href="#"><?php echo $this->global_m->get_array_data($ov[$i], "BEGDA",$this->global_m->DATE_MYSQL); ?></a></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "INSTI"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "SLTP1"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "JBEZ1"); ?></td>
                                <td>
                                    <a class="btn btn-primary btn-xs " href="<?=base_url();?>index.php/employee/emp_edunf_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'id_educ'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                    <a class="btn btn-danger btn-xs " href="#" onclick="confirm_delete('<?=base_url();?>index.php/employee/emp_edunf_del/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'id_educ'); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
                                </td>
                            </tr>
                            <?php
                        }

	?>
 <tr>
					<td colspan="6"><button type="submit" class="btn btn-danger btn-xs  pull-left">Delete <i class="fa fa-trash-o"></i></button>
&nbsp;Please Check for multiple delete item
</td>
                            </tr>
	<?php
                    }
                    ?>
                </tbody>
            </table>
        </section>
</form>
        <a class="btn btn-default" href="<?=base_url();?>index.php/employee/master/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
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