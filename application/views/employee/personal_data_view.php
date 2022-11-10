<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Personal Data
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">To</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Name</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "CNAME"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Gender</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label">
                            <?php
                            $aGesch = array('1'=>'Males','2'=>'Females');
                            if(in_array($this->global_m->get_array_data($frm, "GESCH"), $aGesch)){
                                echo $aGesch[$this->global_m->get_array_data($frm, "GESCH")];
                            }else{
                                echo "-";
                            }
                            ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="input6" class="col-lg-2 col-sm-2 control-label">Birth Date</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "GBDAT", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input7" class="col-lg-2 col-sm-2 control-label">Birth Place</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "GBLND"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input7" class="col-lg-2 col-sm-2 control-label">Marital Status</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label">
                            <?php
                            for ($i = 0; $i < count($marst); $i++) {
                                if ($marst[$i]['SHORT'] == $this->global_m->get_array_data($frm, "MARST")){
                                    echo $marst[$i]['STEXT'];
                                    break;
                                }
                            }
                            ?>
                    </label>
                </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/personal_data_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>