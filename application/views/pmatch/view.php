<section class="wrapper">
  <!-- page start-->
  <div class="row">
		<div class="col-md-12" id="divResult">
			<div class="panel">
				<div class="weather-bg">
					<div class="panel-body">
						<div class="row">
							<div class="degree"> Profile Matchup </div>
						</div>
					</div>
				</div>
				<br />
				<div class="col-md-12">
					<div class="col-lg-4 col-sm-6">
						<div class="row state-overview">
							<section class="panel">
								<div class="symbol red">
									<a href="#myModal2" data-toggle="modal">
									<img width="120px" height="120px"  alt="" src="<?= base_url(); ?>img/photo/<?php echo $nik; ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';">
									</a>
								</div>
								<div class="value2">
									<p>Name : <?php echo $this->global_m->get_array_data($emp, "CNAME"); ?></p>
									<p>Age : <?php echo $this->global_m->get_array_data($emp, "AGE"); ?></p>
									<p><?php echo $mdg; ?>
									<p>Curr. Position : <?php echo $this->global_m->get_array_data($emp, "POS"); ?></p>
									<p>Job Level : <?php echo $this->global_m->get_array_data($emp, "JOB"); ?></p>
									<p>Unit : <?php echo $this->global_m->get_array_data($emp, "UNIT"); ?></p>
									<p>Company : <?php echo $this->global_m->get_array_data($emp, "COMPANY"); ?></p>
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
									<i class="fa fa-tags"></i>
								</div>
								<div class="value">
									<h1 class="count"><?php echo $count; ?></h1>
									<p>Position(s) Found</p>
								</div>
							</section>
						</div>
					</div>
				</div>
				<div class="panel-heading">
				    Compare Positions
			    </div>
				<div class="panel-body">
					<section>
					  <table class="table table-bordered table-striped table-condensed cf" id="tblPmatch">
						  <thead class="cf">
						  <tr>
							  <th rowspan="2" class="numeric">#</th>
							  <th rowspan="2">Position</th>
							  <th rowspan="2">Job Level</th>
							  <th rowspan="2">Unit</th>
							  <th rowspan="2">Company</th>
							  <th rowspan="2">Readiness</th>
							  <th colspan="5">Suitable</th>
							<!--  <th rowspan="2">Others Info</th> -->
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
								if($position){
									$j=1;
									foreach($position as $pos) {
							?>
							  <tr>
								  <td><?php echo $j; ?></td>
								  <td>
								  <a href="http://localhost/talent/index.php/ecs/view/<?php echo $nik; ?>/<?php echo $this->global_m->get_array_data($pos, "posid"); ?>">
								  <?php echo $this->global_m->get_array_data($pos, "position"); ?></a>
								  </td>
								  <td><?php echo $this->global_m->get_array_data($pos, "job"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($pos, "unit"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($pos, "company"); ?></td>
								  <td>
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($pos, "readiness"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($pos, "readiness"); ?>" role="progressbar">
										</div>
									</div><?php echo $this->global_m->get_array_data($pos, "readiness"); ?>% (<?php echo $this->global_m->get_array_data($pos, "ready"); ?>)
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($pos, "ccompt"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($pos, "ccompt"); ?>" role="progressbar">
										</div>
									</div><?php echo $this->global_m->get_array_data($pos, "ccompt"); ?>%
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($pos, "cperf"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($pos, "cperf"); ?>" role="progressbar">
										</div>
									</div><?php echo $this->global_m->get_array_data($pos, "cperf"); ?>%
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($pos, "cage"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($pos, "cage"); ?>" role="progressbar">
										</div>
									</div><?php echo $this->global_m->get_array_data($pos, "cage"); ?>%
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($pos, "ceduc"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($pos, "ceduc"); ?>" role="progressbar">
										</div>
									</div><?php echo $this->global_m->get_array_data($pos, "ceduc"); ?>%
								  </td>
								  <td style="width:80px;">
									<div class="progress progress-striped progress-sm">
										<div class="progress-bar progress-bar-success" style="width: <?php echo $this->global_m->get_array_data($pos, "cmedical"); ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $this->global_m->get_array_data($pos, "cmedical"); ?>" role="progressbar">
										</div>
									</div><?php echo $this->global_m->get_array_data($pos, "cmedical"); ?>%
								  </td>
							  </tr>
							<?php
									$j++;
									}
								}else{
							?>
							<tr>
								<td>-</td>
								<td>No Data Found</td>
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
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width: 390px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Photo</h4>
				</div>
				<div class="modal-body">
<img height="350px;" width="350px;" alt="Emp Photo" src="<?= base_url(); ?>img/photo/<?php echo $nik; ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';">
				</div>
			</div>
		</div>
    </div>
  <!-- page end-->
</section>