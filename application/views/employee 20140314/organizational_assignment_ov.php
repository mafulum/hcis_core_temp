<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Personal Data
                <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/employee/organizational_assignmnt_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>CoCode</th>
                        <th>PA</th>
                        <th>P SubArea</th>
                        <th>Org</th>
                        <th>Pos</th>
                        <th>Job</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($ov) == 0) {
                        ?>
                        <tr>
                            <td colspan="8">No Data</td>
                        </tr>
                        <?php
                    } else {
                        for ($i = 0; $i < count($ov); $i++) {
                            ?>
                            <tr>
                                <td><a href="#"><?php echo $this->global_m->get_array_data($ov[$i], "BEGDA"); ?></a></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "BUKRS"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "WERKS"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "BTRTL"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "org_unit"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "pos_name"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "job_name"); ?></td>
                                <td>
                                    <!--<a class="btn btn-success btn-xs" href="<?php echo base_url() . "index.php/employee/personal_data_fr"; ?>" data-toggle="modal"> <i class="fa fa-search"></i> </a>-->
                                    <a class="btn btn-primary btn-xs" href="<?=base_url();?>index.php/employee/personal_data_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'id_emp'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                    <a class="btn btn-danger btn-xs" href="<?=base_url();?>index.php/employee/personal_data_del/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>/<?php echo $this->global_m->get_array_data($ov[$i], 'id_emp'); ?>" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <a class="btn btn-default" href="<?=base_url();?>index.php/employee/master/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>
</div>