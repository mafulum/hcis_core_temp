<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    Perusahaan
                    <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/admin/perusahaan_fr/" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
                </header>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>SHORT</th>
                            <th>STEXT</th>
                            <th>BEGDA</th>
                            <th>ENDDA</th>
                            <th>Level</th>
                            <th>Seq</th>
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
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "SHORT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "STEXT"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "BEGDA",$this->global_m->DATE_MYSQL); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "ENDDA",$this->global_m->DATE_MYSQL); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "LEVEL"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "SEQ"); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/perusahaan_fr/<?php echo $this->global_m->get_array_data($table[$i], 'OBJID'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
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
</section>