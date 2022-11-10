<section class="wrapper">
  <!-- page start-->
  <div class="row">
	  <aside class="profile-nav col-lg-3">
		  <section class="panel">
			  <div class="user-heading round">
				  <a href="#myModal2" data-toggle="modal">
					  <img alt="" src="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';">
				  </a>
				  <h1><?php echo $this->global_m->get_array_data($master_emp, "CNAME"); ?></h1>
                  <!-- <p><?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?> -->
                  <p><?php echo $this->global_m->get_array_data($emp_map, "NIK"); ?>
				  <br /><?php echo $this->global_m->get_array_data($emp_map, "POSISI"); ?>
				  <br /><?php echo $this->global_m->get_array_data($emp_map, "PERSH"); ?></p>
			  </div>

			  <ul class="nav nav-pills nav-stacked">
				  <li><a href="<?php echo $base_url; ?>index.php/tprofile/view/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-user"></i> Profile</a></li>
				  <!--<li class="active"><a href="<?php echo $base_url; ?>index.php/ecs/view/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-calendar"></i> Competency Summary</a></li>-->
				  <li><a href="<?php echo $base_url; ?>index.php/gen_pdf/ecs_all/<?php echo $hash; ?>"> <i class="fa fa-download"></i> Download Data</a></li>
				 <!-- <li><a href=""> <i class="fa fa-edit"></i> Succession Planning</a></li>-->
				  <li><a href="<?php echo $base_url; ?>index.php/ecs/search"> <i class="fa fa-mail-reply"></i> Back</a></li>
			  </ul>

		  </section>
	  </aside>
	  <aside class="profile-info col-lg-9">
		  <section class="panel">
			  <div class="bio-graph-heading">
				  Employee Competency Summary
			  </div>
			  <div class="panel-body bio-graph-info">
				  <div class="row">
					  <div class="col-lg-12">
						  <h1>Position Data</h1>
						  <p><span>Current Position </span>: <?php echo $this->global_m->get_array_data($posCurrent, "STEXT"); ?> </p>
						  <p><span>Job Level </span> : <?php echo $this->global_m->get_array_data($posDetail, "JOB_LEVEL"); ?></p>
						  <p><span>Job Group </span> : <?php echo $this->global_m->get_array_data($posDetail, "FAMILY_TXT"); ?></p>
						  <br />
						  <br />
					  </div>
					  <div class="col-lg-12">
							<h1>Competency<div class="pull-right"><a class="btn btn-info btn-shadow btn-sm" href="#myModal" data-toggle="modal">Compare </a></div></h1>
							<? if($this->common->cek_pihc_access()==1){ ?>
							<select id="selCompt" class="form-control input-sm m-bot05">
								<option value="1" <?php echo ($selBase==1?'selected="selected"':''); ?>>Holding Competency</option>
								<option value="2" <?php echo ($selBase==2?'selected="selected"':''); ?>>Subsidiary Competency</option>
							</select>
							<? } ?>
							<table class="table tbl-compt">
								<thead>
									<tr>
										<th rowspan="2">#</th>
										<th rowspan="2">Competency</th>
										<th colspan="6">Level</th>
									</tr>
									<tr>
										<th>1</th>
										<th>2</th>
										<th>3</th>
										<th>4</th>
										<th>5</th>
										<th>6</th>
									</tr>
								</thead>
								<tbody>
								<?php 
									if($empcompt){
										$sGroup = "";
										$iTotal = 0;
										for($i=0;$i<count($empcompt);$i++){
											if($sGroup<>$empcompt[$i]['OTYPE']){
												$sGroup = $empcompt[$i]['OTYPE'];
												?>
												<tr>
													<td colspan="8"><?php echo $comptDef['KC'][$empcompt[$i]['OTYPE']]; ?></td>
												</tr>
												<?php
											}
											?>
												<tr>
													<td><?php echo $i + 1;?></td>
													<td><?php echo $empcompt[$i]['STEXT']; ?></td>
													<?php 
														$iTotal += $empcompt[$i]['COVAL'];
														for($j=0; $j < $empcompt[$i]['COVAL']; $j++){
															echo "<td class=\"compt-green\"></td>";
														} 
														if($j < 6){
															for($k = $j; $k < 6 ; $k++){
																echo "<td></td>";
															}
														}
													?>
												</tr>
											<?php
										}
										$iAvg = $iTotal / $i;
										?>
												<tr>
													<td colspan="2"><b>Average</b></td>
													<td colspan="6"><b><?php echo number_format($iAvg, 2, ',', ' '); ?></b></td>
												</tr>
										<?php
									}else{	?>
										<tr><td colspan="8"><b>No Competency Data Found</b></td></tr>
									<?php
									} ?>
								</tbody>
							</table>
					  </div>
				  </div>
			  </div>
		  </section>
	  </aside>
  </div>
  <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
	  <div class="modal-dialog">
		  <div class="modal-content">
			  <div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">x</button>
				  <h4 class="modal-title">Compare with...</h4>
			  </div>
			  <div class="modal-body">
				  <form class="form-horizontal" role="form" action="<?php echo $base_url; ?>index.php/ecs/view/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" method="post">
					<input id="fpernr" type="hidden" name="pernr" value="<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"/>
					<input id="fcont" type="hidden" name="pernr" value="<?php echo $viewcont; ?>"/>
					  <div class="form-group">
						  <label for="fbukrs">Company</label>
						  <input type="text" class="form-control" id="fbukrs" name="fbukrs" value="<?php echo $this->global_m->get_array_data($emp_org, "BUKRS"); ?>" style="padding: 3px 0px;">
					  </div>
					  <div class="form-group">
						  <label for="forgeh">Unit</label>
						  <input type="text" class="form-control" id="forgeh" name="forgeh" value="<?php echo $this->global_m->get_array_data($emp_org, "ORGEH"); ?>" style="padding: 3px 0px;">
					  </div>
					  <div class="form-group">
						  <label for="fplans">Position</label>
						  <input type="text" class="form-control" id="fplans" name="fplans" value="<?php echo $this->global_m->get_array_data($emp_org, "PLANS"); ?>" style="padding: 3px 0px;">
					  </div>
					  <button type="submit" class="btn btn-default">Compare</button>
				  </form>
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
<img height="350px;" width="350px;" alt="Emp Photo" src="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';">
				</div>
			</div>
		</div>
    </div>
  <!-- page end-->
</section>