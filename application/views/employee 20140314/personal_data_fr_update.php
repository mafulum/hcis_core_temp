<div class="row">
    <div class="col-lg-12">
        <!--widget start-->
        <section class="panel">
            <header class="panel-heading">
                Personal Data
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/personal_data_upd" method="post">
                    <input type="hidden" name="id_emp" value="<?php echo $this->global_m->get_array_data($frm, "id_emp"); ?>"/> 
                    <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "PERNR"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode</label>
                        <div class="col-lg-10">
                            <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control dpd1" value="<?php echo $this->global_m->get_array_data($frm, "BEGDA"); ?>" name="begda">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control dpd2" value="<?php echo $this->global_m->get_array_data($frm, "ENDDA"); ?>" name="endda">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Nama</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="cname" name="cname" value="<?php echo $this->global_m->get_array_data($frm, "CNAME"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">Jenis Kelamin</label>
                        <div class="col-lg-10">
                            <input type="radio" name="gesch" id="gesch" value="1" <?php if ($this->global_m->get_array_data($frm, "GESCH") == '1') echo "checked"; ?>> Laki-Laki
                            <input type="radio" name="gesch" id="gesch" value="2"<?php if ($this->global_m->get_array_data($frm, "GESCH") == '2') echo "checked"; ?>> Perempuan
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input6" class="col-lg-2 col-sm-2 control-label">Tanggal Lahir</label>
                        <div class="col-lg-10">
                            <input class="form-control form-control-inline input-medium default-date-picker" name="gbdat"  size="16" type="text" value="<?php echo $this->global_m->get_array_data($frm, "GBDAT"); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input7" class="col-lg-2 col-sm-2 control-label">Tempat Lahir</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="input7" name="gblnd"value="<?php echo $this->global_m->get_array_data($frm, "GBLND"); ?>">
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
        <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/personal_data_ov/" . $this->global_m->get_array_data($master_emp, "PERNR"); ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
        <!--widget end-->
    </div>