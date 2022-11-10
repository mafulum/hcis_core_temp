<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!--widget start-->
            <section class="panel">
                <header class="panel-heading">
                    Download Employee BPJS
                </header>
                <div class="panel-body">
                    <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/download/emp_bpjs/download" method="post" id="org_new"  enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="fComp" class="col-lg-2 col-sm-2 control-label">TMT Date </label>
                            <div class="col-lg-10">
                                <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" name="tmtdate" id="tmtdate"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fComp" class="col-lg-2 col-sm-2 control-label">Status Employee</label>
                            <div class="col-lg-10">
                                <label for="fComp" class="col-lg-2 col-sm-2 control-label">All</label>    
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success">Download</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>