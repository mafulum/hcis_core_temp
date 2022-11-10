<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - InOut / Advance Payment  Process
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="<?php echo base_url()."index.php/payroll/in_out/process";?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fname" class="col-lg-2 col-sm-2 control-label">Name Of Bank Transfer</label>
                        <?php if(empty($io)){ ?>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="name" value="<?php if(!empty($name)){echo $name;}?>" style="padding: 0.5px 0px;">                            <span class="help-block">Select date</span>
                            <?php echo form_error('name'); ?> 
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-10 col-sm-2 control-label"> <?php echo ($io['name'])?  $io['name'] : "-" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">BEGDA</label>
                         <?php if(empty($io)){ ?>
                        <div class="col-md-4 col-xs-11">
                            <input class="form-control form-control-inline input-medium " data-date-format="yyyy-mm-dd" size="16" type="text" value="<?php if(!empty($begda)){echo $begda;}?>" id="begda" name="begda" />
                            <?php echo form_error('begda'); ?> 
                        </div>
                        <?php }else{ ?>
                        <label for="begda" class="col-lg-4 col-sm-2 control-label"> <?php echo ($io['begda'])?  $io['begda'] : "-" ; ?> </label>
                        <?php } ?>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Date of Event</label>
                         <?php if(empty($io)){ ?>
                        <div class="col-md-4 col-xs-11">
                            <input class="form-control form-control-inline input-medium " data-date-format="yyyy-mm-dd" size="16" type="text" value="<?php if(!empty($evtda)){echo $evtda;}?>" id="evtda" name="evtda" />
                            <?php echo form_error('evtda'); ?> 
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-4 col-sm-2 control-label"> <?php echo ($io['evtda'])?  $io['evtda'] : "-" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">File to be Process</label>
                        <div class="col-lg-10">
                            <input type="file" class="form-control" name="fupload" id="fupload"  style="padding: 0.5px 0px;">
                            <?php echo form_error('fupload'); ?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <a href="<?php echo base_url()."index.php/payroll/in_out";?>" class="btn btn-default"/>Back</a>
                            <button type="submit" class="btn btn-default" name="review" value="review" id="fReview">Review</button>
                            <button type="submit" class="btn btn-default" name="confirm" value="confirm" id="fConfirm">Confirm</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <?php if(!empty($sError)){ ?>
        <section class="panel">
            <header class="panel-heading">
                Payroll - InOut / Advance Payment  Process Error
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <p><?php echo $sError;?></p>
            </div>
        </section>
        <?php } ?>
        <?php if(!empty($success)){ ?>
        <section class="panel">
            <header class="panel-heading">
                Payroll - InOut / Advance Payment  Process Success
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <p><?php echo $success;?></p>
            </div>
        </section>
        <?php } ?>
    </div>
</section>