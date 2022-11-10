<?php $iPIHC = $this->common->cek_pihc_access(); ?>
<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="alert alert-block alert-danger fade in" id="div_error_message" style="display: none;">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            <strong>Error !</strong> <span id="error_message"></span>
        </div>
    </div>
    <div class="row">
        <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/talent/tsearch/view" method="post">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        Requirement Selection Filter
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="fjob2" class="col-lg-2 col-sm-2 control-label">Job</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="fjob2" name="fjob2" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fprsh2" class="col-lg-2 col-sm-2 control-label">Company</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="fprsh2" name="fprsh2" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="funit2" class="col-lg-2 col-sm-2 control-label">Unit</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="funit2" name="funit2" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fpos2" class="col-lg-2 col-sm-2 control-label">Position</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="fpos2" name="fpos2" style="padding: 3px 0px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        Employee(s) Selection
                    </div>
                    <div class="panel-body" id="divForm">
                        <div class="form-group">
                            <label for="fprsh" class="col-lg-2 col-sm-2 control-label">Company</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="fprsh" name="fprsh" value="" style="padding: 0.5px 0px;overflow: hidden !important;height: auto !important;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fjob" class="col-lg-2 col-sm-2 control-label">Job</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="fjob" name="fjob" value="" style="padding: 0.5px 0px;overflow: hidden !important;height: auto !important;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fnik" class="col-lg-2 col-sm-2 control-label">Name/NIK</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="fnik" name="fnik" value="" style="padding: 0.5px 0px;overflow: hidden !important;height: auto !important;">
                            </div>
                        </div>
                    </div>			
                </div>
            </div>
            <div class="col-md-12" id="divBase">
                <div class="panel">
                    <div class="panel-body">
                        <div class="col-md-12" id="divSearch">
                            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Talent Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- page end-->
</section>