<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                UPLOAD - Employee Insurance Cost
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <?php if (!empty($sError)) { ?>
                    <div class="form-bordered">
                        <label for="fError" class="col-lg-2 col-sm-2 control-label">Status Info  </label>
                        <div class="error-message error">
                            <?php echo $sError; ?>
                        </div>
                    </div>

                    <?php
                }
                ?>
                <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/upload/emp_insurance/upload" method="post" id="emp_new"  enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fComp" class="col-lg-2 col-sm-2 control-label">File </label>
                        <div class="col-lg-10">
                            <input type="file" name="userfile"/>

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
    </div>
</section>


