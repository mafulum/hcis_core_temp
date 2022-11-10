<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-md-12" id="divResult">
            <div class="panel">
                <div class="weather-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="degree"> Succession Ranking List <?php echo $position_name; ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <section>
                        <table id="tblSrank" class="table table-bordered table-striped">
                            <thead class="cf">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Photo</th>
                                    <th rowspan="2" width="200">Name / NIK</th>
                                    <th rowspan="2" width="250">Curr. Position</th>
                                    <th rowspan="2" width="140">MDG</th>
                                    <th rowspan="2" width="250">Last Education</th>
                                    <th rowspan="2">Age</th>
                                    <th rowspan="2" width="300">Experience</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                if ($employee) {
                                    $j = 1;
                                    foreach ($employee as $emp) {
                                        ?>
                                        <tr>
                                            <td><?php echo $j; ?></td>
                                            <td><a class="fancybox" rel="group" href="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($emp, "nik"); ?>.jpg"><img name="poto" width="60px" height="60px" alt="" src="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($emp, "nik"); ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';"></a></td>
                                            <td>
                                                <?php echo $this->global_m->get_array_data($emp, "nama"); ?>
                                                <br/>
                                                <?php echo $this->global_m->get_array_data($emp, "nik"); ?>
                                            </td>
                                            <td><?php echo $this->global_m->get_array_data($emp, "currpos"); ?>
                                                <br/>
                                                <?php echo $this->global_m->get_array_data($emp, "perusahaan"); ?></td>
                                            <td><?php echo $this->global_m->get_array_data($emp, "mdg"); ?></td>

                                            <td><?php echo $this->global_m->get_array_data($emp, "educ"); ?>
                                                <?php for ($i = 0; $i < count($emp['educ']); $i++) { ?>
                                                    <?php echo ($i <> 0 ? " & " : "") . $this->global_m->get_array_data($emp['educ'][$i], "STEXT") . ", " . $this->global_m->get_array_data($emp['educ'][$i], "SLTP1"); ?>, <?php echo $this->global_m->get_array_data($emp['educ'][$i], "INSTI"); ?>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $this->global_m->get_array_data($emp, "age") ; ?></td>
                                            <td><?php echo str_replace("\n", "<br/>", $aHist[$j - 1]); ?></td>
                                        </tr>
                                        <?php
                                        $j++;
                                    }
                                } else {
                                    ?>
                                <tr><!--<td colspan="16">No Data Found</td> -->
                                        <td>-</td>
                                        <td>-</td>
                                        <td width="200px;">No Data Found</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        Concern Item 
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <div class="row" style="text-align:justify;margin-left: 15px;">
                                <?php echo str_replace("\n", "<br/>", $concern); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <a href="<?php echo $base_url; ?>index.php/gen_pdf/splan/<?php echo $hash; ?>"> <i class="fa fa-download"></i> Download Data</a>
                        <a href="<?php echo $base_url; ?>index.php/splan/opsi_1"> <i class="fa fa-home"></i> Back To Succession Form</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- page end-->
</section>