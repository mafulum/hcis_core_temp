<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                User Update
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/user_new" method="post" id="user_fr_new">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="username" class="col-lg-2 col-sm-2 control-label">Username</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="username" name="username" value="">
                        </div>
                    </div><div class="form-group">
                        <label for="password" class="col-lg-2 col-sm-2 control-label">Password</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control" id="password" name="password" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pernr" class="col-lg-2 col-sm-2 control-label">PERNR</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="pernr" name="pernr" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="SUBMIT" id="bSave" class="btn btn-success">Save</button>
                        </div>
                    </div>
            </form>

        </section>
    </div>
    <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/user"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>

    <div class="modal fade" id="confirm-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Insert</h4>
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