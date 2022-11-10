<section class="wrapper">
  <!-- page start-->
  <div class="row">
		<div class="col-md-12" id="divResult">
			<div class="panel">
				<div class="weather-bg">
					<div class="panel-body">
						<div class="row">
							<div class="degree"> Succession Rank List </div>
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
								<div class="value2">
									<p>Company : <?php echo $this->global_m->get_array_data($position, "prsh"); ?></p>
									<p>Unit : <?php echo $this->global_m->get_array_data($position, "unit"); ?></p>
									<p>Job Level : <?php echo $this->global_m->get_array_data($position, "job"); ?></p>
									<p>Job Group : <?php echo $this->global_m->get_array_data($position, "fam"); ?></p>
									<p>Position Name : <?php echo $this->global_m->get_array_data($position, "desc"); ?></p>
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
									<h1 class="count"><?php echo $count; ?></h1>
									<p>Employee(s) Found</p>
								</div>
							</section>
						</div>
					</div>
				</div>
				<div class="panel-heading">
				    Compare Potential Candidate
					<div class="pull-right"><button class="btn btn-info btn-sm" id="btnShow">Show Detail</button></div>
			    </div>
				<div class="panel-body">
					<section>
					  <table id="tblSrank" class="table table-bordered table-striped">
						  <thead class="cf">
						  <tr>
							  <th width="70px" rowspan="2" class="numeric">#</th>
							  <th rowspan="2">NIK</th>
							  <th rowspan="2">Name</th>
							  <th rowspan="2">Photo</th>
							  <th rowspan="2">Curr. Position</th>
							  <th rowspan="2">MDG</th>
							  <th rowspan="2">Readiness</th>
							  <th colspan="5">Suitable</th>
							  <th rowspan="2">Last Education</th>
							  <th rowspan="2">Age</th>
							  <th rowspan="2">Medical</th>
							  <th rowspan="2">Performance</th>
							  <th rowspan="2">Comptency</th>
							  <th rowspan="2">Talent Map in Curr. Position</th>
						  </tr>
						  <tr>
							  <th>Competency</th>
							  <th>Performance</th>
							  <th>Age</th>
							  <th>Education</th>
							  <th>Medical</th>
						  </tr>
						  </thead>
						  <tbody>
							<?php
								if($employee){
									$j=1;
									foreach($employee as $emp) {
							?>
							  <tr>
								  <td><?php echo $j; ?>
									<img name="plusminus" src="<?= base_url(); ?>assets/advanced-datatable/examples/examples_support/details_open.png">
								  </td>
								  <td><?php echo $this->global_m->get_array_data($emp, "nik"); ?>
								  </td>
								  <td><a href="<?= base_url(); ?>index.php/ecs/view/<?php echo $this->global_m->get_array_data($emp, 'nik'); ?>/<?php echo $this->global_m->get_array_data($position, "pos"); ?>">
								  <?php echo $this->global_m->get_array_data($emp, "nama"); ?></a>
								  </td>
								  <td><a class="fancybox" rel="group" href="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($emp, "nik"); ?>.jpg"><img name="poto" width="60px" height="60px" alt="" src="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($emp, "nik"); ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';"></a></td>
								  <td><?php echo $this->global_m->get_array_data($emp, "currpos"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($emp, "mdg"); ?></td>
								  <td>
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($emp, "readiness"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($emp, "readiness"); ?>" role="progressbar">
										</div>
									</div><?php echo number_format(($emp["readiness"]?$emp["readiness"]:0), 2, ',', '.'); ?>% (<?php echo $this->global_m->get_array_data($emp, "ready"); ?>)
								  </td>
								  <td>
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($emp, "ccompt"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($emp, "ccompt"); ?>" role="progressbar">
										</div>
									</div><?php echo number_format(($emp["ccompt"]?$emp["ccompt"]:0), 2, ',', '.'); ?>%
								  </td>
								  <td>
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($emp, "cperf"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($emp, "cperf"); ?>" role="progressbar">
										</div>
									</div><?php echo number_format(($emp["cperf"]?$emp["cperf"]:0), 2, ',', '.'); ?>%
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($emp, "cage"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($emp, "cage"); ?>" role="progressbar">
										</div>
									</div><?php echo number_format(($emp["cage"]?$emp["cage"]:0), 2, ',', '.'); ?>%
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($emp, "ceduc"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($emp, "ceduc"); ?>" role="progressbar">
										</div>
									</div><?php echo number_format(($emp["ceduc"]?$emp["ceduc"]:0), 2, ',', '.'); ?>%
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($emp, "cmedical"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($emp, "cmedical"); ?>" role="progressbar">
										</div>
									</div><?php echo number_format(($emp["cmedical"]?$emp["cmedical"]:0), 2, ',', '.'); ?>%
								  </td>
								  <td><?php echo $this->global_m->get_array_data($emp, "educ"); ?>
								  <?php for ($i = 0; $i < count($emp['educ']); $i++) { ?>
								  <?php echo ($i<>0?" & ":"") . $this->global_m->get_array_data($emp['educ'][$i], "STEXT") . ", " . $this->global_m->get_array_data($emp['educ'][$i], "SLTP1"); ?>, <?php echo $this->global_m->get_array_data($emp['educ'][$i], "INSTI"); ?>
								  <?php } ?>
								  </td>
								  <td><?php echo $this->global_m->get_array_data($emp, "birthdate") . " (" .$this->global_m->get_array_data($emp, "age") ." yrs) "; ?></td>
								  <td><?php echo $this->global_m->get_array_data($emp['medical'], "STEXT"); ?> (<?php echo $this->global_m->get_array_data($emp['medical'], "BEGDA"); ?>)</td>
								  <td><?php echo $this->global_m->get_array_data($emp, "perf"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($emp, "comptavg"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($emp, "mapdesc"); ?> (<?php echo $this->global_m->get_array_data($emp, "map"); ?>)</td>
							  </tr>
							<?php
									$j++;
									}
								}else{
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
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  </tr>
							<?php }?>
						  </tbody>
					  </table>
					</section>
				</div>
			</div>
		</div>
	</div>
  <!-- page end-->
</section>