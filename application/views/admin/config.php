<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    Config ID
                </header>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>Awards Type</th>
                            <th>Name</th>
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
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "short"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "ctype"); ?></td>
                                    <td><?php echo $this->global_m->get_array_data($table[$i], "seq"); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?= base_url(); ?>index.php/admin/config_fr/<?php echo $this->global_m->get_array_data($table[$i], 'idc'); ?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
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