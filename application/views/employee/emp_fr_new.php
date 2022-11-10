<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                ADD NEW EMPLOYEE - PERSONAL DATA (1)
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/employee/add_new_emp_pd" method="post" id="emp_new">
                <div class="form-group">
                    <label for="fComp" class="col-lg-2 col-sm-2 control-label">Company</label>
                    <div class="col-lg-10">
                        <select class="form-control" id="fComp" name="fComp" style="padding: 3px 0px;">
                            <?php
                            for ($i = 0; $i < count($werks); $i++) {
                                echo "<option value='" . $werks[$i]['id'] . "' >" . $werks[$i]['name'] . " ( " . $werks[$i]['id'] . " )</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Period</label>
                    <div class="col-lg-10">
                        <div class="input-group input-large" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control dpd1" name="begda" id="begda">
                            <span class="input-group-addon">To</span>
                            <input type="text" class="form-control dpd2" name="endda" id="endda">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Name</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="cname" name="cname">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Gender</label>
                    <div class="col-lg-10">
                        <input type="radio" name="gesch" id="gesch" value="1" checked> Male
                        <input type="radio" name="gesch" id="gesch" value="2"> Female
                    </div>
                </div>
                <div class="form-group">
                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Marital Status</label>
                    <div class="col-lg-10">
                        <select class="form-control" id="marst" name="marst" style="padding: 3px 0px;">
                            <?php
                            for ($i = 0; $i < count($marst); $i++) {
                                echo "<option value='" . $marst[$i]['SHORT'] . "' >" . $marst[$i]['STEXT'] ."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input6" class="col-lg-2 col-sm-2 control-label">Birth Date</label>
                    <div class="col-lg-10">
                        <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" name="gbdat" id="gbdat" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="input7" class="col-lg-2 col-sm-2 control-label">Birth Place</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="input7" name="gblnd" id="gblnd">
                    </div>
                </div>
                <div class="form-group">
                    <label for="input7" class="col-lg-2 col-sm-2 control-label">NIK</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="fNik" name="fNik" placeholder="Enter Old NIK">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>

        </section>
    </div>
    <a class="btn btn-default" href="<?php echo base_url() . "index.php/employee/master"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
    <div class="modal fade" id="confirm-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Insert Employee (1) Personal Data</h4>
                </div>
            
                <div class="modal-body">
                    <p>You are about to insert, this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>
</section>


