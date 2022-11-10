<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Family
            </header>
            <div class="panel-body">
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Period :</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "BEGDA", $this->global_m->DATE_MYSQL); ?></label>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">To</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "ENDDA", $this->global_m->DATE_MYSQL); ?></label>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Family Member</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($osubty); $i++) {
                                if($osubty[$i]['id']==$this->global_m->get_array_data($frm, "SUBTY")){
                                    echo $osubty[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                        </label>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Number</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "OBJPS"); ?></label>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Name</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "CNAME"); ?></label>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Gender</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($fgesch); $i++) {
                                if($fgesch[$i]['id']==$this->global_m->get_array_data($frm, "GESCH")){
                                    echo $fgesch[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="input6" class="col-lg-2 col-sm-2 control-label">Birthplace</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "GBLND"); ?></label>
                        <label for="input6" class="col-lg-2 col-sm-2 control-label">Date of birth</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "GBDAT", $this->global_m->DATE_MYSQL); ?></label>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Nationality</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($fNat); $i++) {
                                if($fNat[$i]['id']==$this->global_m->get_array_data($frm, "GBNAT")){
                                    echo $fNat[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                        </label>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Country of Birth</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($fCty); $i++) {
                                if($fCty[$i]['id']==$this->global_m->get_array_data($frm, "GBCTY")){
                                    echo $fCty[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Identity Type</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($fWN); $i++) {
                                if($fWN[$i]['id']==$this->global_m->get_array_data($frm, "IDENT")){
                                    echo $fWN[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                        </label>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Doc Cert. :</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data($frm, "DOCERT"); ?></label>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Benefit Include :</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($fIncben); $i++) {
                                if($fIncben[$i]['id']==$this->global_m->get_array_data($frm, "INCBEN")){
                                    echo $fIncben[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                        </label>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Status :</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php
                            for ($i = 0; $i < count($ffamstat); $i++) {
                                if($ffamstat[$i]['id']==$this->global_m->get_array_data($frm, "FAMSTAT")){
                                    echo $ffamstat[$i]['text'];
                                    break;
                                }
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Note :</label>
                        <label for="input6" class="col-lg-10 col-sm-10 control-label"><?php echo $this->global_m->get_array_data($frm, "NOTE"); ?></label>
                    </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/personal_data_family" . "/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>
</div>