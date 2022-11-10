<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Personal Data - Address
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period :</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?></label>
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">To</label>
                    <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?></label>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Address Type</label>
                     <label for="input6" class="col-lg-10 col-sm-10 control-label">
                            <?php
                            if ($this->global_m->get_array_data($frm, "ADDRESS_TYPE") == 'HOME'){ 
                                echo "Home";
                            }else if($this->global_m->get_array_data($frm, "ADDRESS_TYPE") == 'OFFC'){
                                echo "Office";
                            }else{
                                echo "-";
                            }
                            ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Address</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "ADDR"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">City</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "CITY"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input6" class="col-lg-2 col-sm-2 control-label">STATE</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "STATE"); ?></label>
                </div>
                <div class="form-group">
                    <label for="input7" class="col-lg-2 col-sm-2 control-label">Country</label>
                    <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "COUNTRY"); ?></label>
                </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/personal_data_addr/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>