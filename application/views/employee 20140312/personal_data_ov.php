<script type="text/javascript">
//    function update(seq){
//        var form = document.createElement("form");
//        form.setAttribute("method", 'post');
//        form.setAttribute("action", '<?=base_url();?>index.php/employee/personal_data_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>');
//        var hiddenField = document.createElement("input");
//        hiddenField.setAttribute("type", "hidden");
//        hiddenField.setAttribute("name", 'iSeq');
//        hiddenField.setAttribute("value", seq);
//        form.appendChild(hiddenField);
//        document.body.appendChild(form);
//        form.submit();
//    }
//    
//    function back(){
//        var form = document.createElement("form");
//        form.setAttribute("method", 'post');
//        form.setAttribute("action", '<?=base_url();?>index.php/employee/master');
//        var hiddenField = document.createElement("input");
//        hiddenField.setAttribute("type", "hidden");
//        hiddenField.setAttribute("name", 'nopeg');
//        hiddenField.setAttribute("value", <?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>);
//        form.appendChild(hiddenField);
//        document.body.appendChild(form);
//        form.submit();
//    }
    
</script>    

<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Personal Data
                <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/employee/personal_data_fr/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class="fa fa-plus"></i> </a>
            </header>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
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
                                <td><a href="#"><?php echo $this->global_m->get_array_data($ov[$i], "BEGDA"); ?></a></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "ENDDA"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "CNAME"); ?></td>
                                <td><?php echo $this->global_m->get_array_data($ov[$i], "GBDAT"); ?></td>
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
        <a class="btn btn-default" href="" onclick="back();" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>
</div>
