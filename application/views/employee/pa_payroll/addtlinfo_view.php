<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Additinal Payment / Deduction
            </header>
            <div class="panel-body">
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Is OffCycle</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php 
                            if(!empty($frm["is_offcycle"])){
                                echo "OffCycle";
                            }else{
                                echo "InCycle";
                            }
                            ?>
                        </label>
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Event Date</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php 
                            if(!empty($frm["date_offcycle"])){
                                echo $this->global_m->get_array_data($frm, "date_offcycle");
                            }else{
                                echo "-";
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode Regular</label>
                        <label for="input6" class="col-lg-4 col-sm-4 control-label">
                            <?php 
                            if(!empty($frm["periode_regular"])){
                                echo $this->global_m->get_array_data($frm, "periode_regular");
                            }else{
                                echo "-";
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Note</label
                        <label for="input6" class="col-lg-4 col-sm-4 control-label"><?php echo $this->global_m->get_array_data_num($frm, "note"); ?></label>
                    </div>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/memp_payroll/personal_addtlinfo/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>