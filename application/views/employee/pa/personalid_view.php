<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Monitoring of Task
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">To</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Type :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($subty); $i++) {
                                if ($subty[$i]['id'] == $this->global_m->get_array_data($frm, "SUBTY")){
                                    echo $subty[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                    </label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">ICNUM :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ICNUM"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Name :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "IDESC"); ?></label>
                    <label for="input6" class="col-lg-2 col-sm-2 control-label">Note :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "NOTE"); ?></label>
                </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/emp_personalid/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>