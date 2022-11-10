<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-md-12" id="divResult">
            <div class="panel">
                <div class="weather-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="degree"> Talent Search Rank List </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="col-md-12">
                    <div class="col-lg-4 col-sm-6">
                        <div class="row state-overview">
                            <section class="panel">
                                <div class="symbol red">
                                    <i class="fa fa-tags"></i>
                                </div>
                                <div class="value" style="text-align: left;">
                                    <p>Company : <?php echo $this->global_m->get_array_data($filter_emp_comp, "STEXT"); ?> (<?php echo $this->global_m->get_array_data($filter_emp_comp, "SHORT"); ?>)</p>
                                    <p>Unit : <?php echo $this->global_m->get_array_data($filter_emp_unit, "STEXT"); ?> (<?php echo $this->global_m->get_array_data($filter_emp_unit, "SHORT"); ?>)</p>
                                    <p>Job : <?php echo $this->global_m->get_array_data($filter_emp_job, "STEXT"); ?> (<?php echo $this->global_m->get_array_data($filter_emp_job, "SHORT"); ?>)</p>
                                    <p>Position Name : <?php echo $this->global_m->get_array_data($filter_emp_pos, "STEXT"); ?></p>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="row state-overview">
                            <section class="panel">
                                <div class="symbol terques">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="value">
                                    <h1 class="count"><?php echo $obj['n_emp']; ?></h1>
                                    <p>Employee(s) Found</p>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="panel-heading">
                    Compare Potential Candidate
                </div>
                <div class="panel-body">
                    <section>
                        <table id="tblSrank" class="table table-bordered table-striped">
                            <thead class="cf">
                                <tr>
                                    <th class="numeric">#</th>
                                    <th>NIK</th>
                                    <th >Name</th>
                                    <th >Photo</th>
                                    <th>Readiness</th>
                                    <th >Curr. Pos</th>
                                    <th >Curr. Org</th>
                                    <th >Curr. Job</th>
                                    <th>Last Education</th>
                                    <th>Age</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($obj['emp_sort']) {
                                    $j = 1;
                                    foreach ($obj['emp_sort'] as $es) {
                                        $pernr = $es['PERNR'];
                                        ?>
                                        <tr>
                                            <td><?php echo $j; ?></td>
                                            <td><?php echo $this->global_m->get_array_data($es, "PERNR"); ?></td>
                                            <td><?php echo $this->global_m->get_array_data($emp[$pernr], "CNAME"); ?></td>
                                            <td><a class="fancybox" rel="group" href="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($es, "PERNR"); ?>.jpg"><img name="poto" width="60px" height="60px" alt="" src="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($es, "PERNR"); ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';"></a></td>
                                            <td>
                                                <div class="progress progress-striped progress-sm">
                                                    <div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($es, "AVG_COMPT"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($emp, "readiness"); ?>" role="progressbar">
                                                    </div>
                                                </div>
                                                <?php echo $this->global_m->get_array_data_num3decimal($es, "AVG_COMPT"); ?> %
                                            </td>
                                            <td><?php echo $this->global_m->get_array_data($emp[$pernr], "S_STEXT"); ?></td>
                                            <td><?php echo $this->global_m->get_array_data($emp[$pernr], "O_STEXT"); ?> (<?php echo $this->global_m->get_array_data($emp[$pernr], "O_SHORT"); ?>)</td>
                                            <td><?php echo $this->global_m->get_array_data($emp[$pernr], "C_STEXT"); ?> (<?php echo $this->global_m->get_array_data($emp[$pernr], "C_SHORT"); ?>)</td>
                                            <td>-</td>
                                            <td><?php echo  $this->global_m->get_array_data($emp[$pernr], "age") . " yrs "; ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" target="__blank" href="<?php echo $base_url; ?>index.php/tprofile/view/<?php echo $this->global_m->get_array_data($es, "PERNR"); ?>" title="Profile">  <i class="fa fa-list"></i> </a>
                                                <a class="btn btn-info btn-xs" href="#" title="Compare" onclick="return compare('<?php echo $this->global_m->get_array_data($es, "PERNR"); ?>');"> <i class="fa fa-exchange"></i> </a>
                                            </td>
                                        </tr>
                                        <?php
                                        $j++;
                                    }
                                } else {
                                    ?>
                                    <tr><td colspan="10">No Data Found</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- page end-->
</section>