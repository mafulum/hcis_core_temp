<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Employee Education Formal
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/emp_eduf_upd" method="post">
                    <input type="hidden" name="id_educ" value="<?php echo $this->global_m->get_array_data($frm, "id_educ"); ?>"/> 
                    <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA",$this->global_m->DATE_MYSQL); ?>" name="begda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" value="<?php echo $this->global_m->get_array_data($frm, "ENDDA",$this->global_m->DATE_MYSQL); ?>" name="endda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Education</label>
                        <div class="col-lg-10">
							<input type="text" class="form-control" id="slart" name="slart" value="<?php echo $this->global_m->get_array_data($frm, "SLART"); ?>" style="padding: 3px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Institution</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="insti" name="insti" value="<?php echo $this->global_m->get_array_data($frm, "INSTI"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input7" class="col-lg-2 col-sm-2 control-label">Branch Study</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="sltp1" name="sltp1" value="<?php echo $this->global_m->get_array_data($frm, "SLTP1"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input8" class="col-lg-2 col-sm-2 control-label">Location</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="sland" name="sland" value="<?php echo $this->global_m->get_array_data($frm, "SLAND"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input8" class="col-lg-2 col-sm-2 control-label">GPA</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="emark" name="emark" value="<?php echo $this->global_m->get_array_data($frm, "EMARK"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input8" class="col-lg-2 col-sm-2 control-label">Payment By</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="slabs" name="slabs" value="<?php echo $this->global_m->get_array_data($frm, "SLABS"); ?>" style="padding: 3px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input8" class="col-lg-2 col-sm-2 control-label">Fee</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="jbez1" name="jbez1" value="<?php echo $this->global_m->get_array_data($frm, "JBEZ1"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/emp_eduf_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>