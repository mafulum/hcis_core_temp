<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-lg-4">
            <section class="panel">
                <header class="panel-heading">
                    Find Employee
                    <span class="tools pull-right">
                        <?php if($this->common->check_permission('EmployeeMasterData.Organization.OrganizationAssignment.Maintain')){ ?>
                        <a class="btn btn-success btn-xs" href="<?php echo base_url() . "index.php/employee/new_emp"; ?>" data-toggle="modal"> <i class="fa fa-plus" style="color: #FFFFFF;"></i> </a>
                        <?php 
                        if($this->global_m->get_array_data($master_emp, "PERNR")!="-"){ ?>
                            <a href="#myModalPHK" 
                               class="btn btn-danger btn-xs"  data-toggle="modal"> <i class="fa fa-trash-o" style="color: #FFFFFF;"></i> </a>
                        <?php } } ?>
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="iNopeg" class="col-lg-2 col-sm-2 control-label">NIK</label>
                        <div class="col-lg-10">
<input type="text" class="form-control" id="iNopeg" name="iNopeg" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"style="padding: 3px 0px;">
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </section>
            <!--widget start-->
            <aside class="profile-nav alt green-border">
                <section class="panel">
                    <div class="user-heading alt" style="background: #2F90ED;">
                        <a href="#myModal2" data-toggle="modal">
                            <img alt="" src="<?= $photo_src; ?>" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';">
                        </a>
                        <h1><?php echo $this->global_m->get_array_data($master_emp, "CNAME"); ?></h1>
                        <!-- <p><?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?></p>	-->
                        <!--<p><?php echo $this->global_m->get_array_data($emp_map, "NIK"); ?></p>-->
						
                    </div>

                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#myModal" data-toggle="modal"> <i class="fa fa-archive"></i> NIK Talent : <?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?></a></li>
                        <li><a> <i class="fa fa-suitcase"></i> Position : <?php echo $this->global_m->get_array_data($emp_map, "POSISI"); ?></a></li>
                        <li><a> <i class="fa fa-users"></i> Unit : <?php echo $this->global_m->get_array_data($emp_map, "ORG"); ?></a></li>
                        <li><a> <i class="fa fa-building-o"></i> Company : <?php echo $this->global_m->get_array_data($emp_map, "PERSH"); ?></a></li>
                    </ul>

                </section>
            </aside>
            <!--widget end-->
        </div>
        <div class="col-lg-8">
            <?php $this->load->view($emp_view, $aCon); ?>
        </div>
    </div>
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">x</button>
                    <h4 class="modal-title">Update Employee Photo</h4>
                </div>
                <div class="modal-body">
                    <? if ($this->global_m->get_array_data($master_emp, "PERNR") != "-") { ?>
                        <form method="POST" action="<? echo base_url(); ?>index.php/employee/update_photo/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"  enctype="multipart/form-data">
                            <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"/>
                            <div class="form-group">
                                <label for="fComp">Photo</label>
                                <input id="fDok" name="userfile" type="file" class="form-control" style="padding: 3px 0px;" placeholder="Company">
                            </div>
                            <div class="form-group master_data">
                                <label for="fNik">Original NIK</label>
                                <input type="text" class="form-control" id="fNik" name="fNik" placeholder="Enter Original NIK" value="<?php echo $this->global_m->get_array_data($emp_map, "NIK"); ?>">
                            </div>
                            <button type="submit" class="btn btn-default  master_data">Submit</button>
                        </form>
                    <?
                    } else {
                        echo "Please Select Employee First";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 390px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Photo</h4>
                </div>
                <div class="modal-body">
                    <img height="350px;" width="350px;" alt="Emp Photo" src="<?=$photo_src; ?>" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalPHK" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 390px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Terminate Employee</h4>
                </div>
                <div class="modal-body">
                    <? if ($this->global_m->get_array_data($master_emp, "PERNR") != "-") { ?>
                        <form name="frmPHK" id="frmPHK" method="POST" action="<? echo base_url(); ?>index.php/employee/terminate"  enctype="multipart/form-data">
                            <input type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"/>
                            <div class="form-group">
                                <label for="fComp">Terminate Date</label>
                                <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" name="terminate_date" id="terminate_date"/>
                            </div>
                            <div class="form-group master_data">
                                <label for="fNik">Terminate Type</label>
                                <input type="text" class="form-control" id="fPHK" name="fPHK" style="padding: 3px 0px;">
                            </div>
                            <button type="submit" class="btn btn-default  master_data">Submit</button>
                        </form>
                    <?
                    } else {
                        echo "Please Select Employee First";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    
<div class="modal fade" id="confirm-PHK" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Terminate</h4>
            </div>

            <div class="modal-body">
                <p>You are about to delete, this procedure is irreversible.</p>
                <p>Do you want to proceed?</p>
                <p class="debug-url"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btnNoPPHK">Cancel</button>
                <a href="#" class="btn btn-danger danger" id="btnYesPHK">Yes</a>
            </div>
        </div>
    </div>
</div>
    <!-- page end-->
</section>