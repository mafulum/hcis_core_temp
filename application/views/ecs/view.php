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
				  <!--<li><a href="<?php echo $base_url; ?>index.php/srank/view/<?php echo $this->global_m->get_array_data($plansCompare, "PLANS"); ?>"> <i class="fa fa-edit"></i> Succession Planning</a></li>-->
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
					  <div class="col-lg-6">
						  <h1>Compare Data</h1>
						  <p><span>Current Position </span>: <?php echo $this->global_m->get_array_data($posCurrent, "STEXT"); ?> </p>
						  <p><span>Compare Position </span> : <?php echo $this->global_m->get_array_data($posCompare, "STEXT"); ?></p>
						  <p><span>Job Level </span> : <?php echo $this->global_m->get_array_data($posDetail, "JOB_LEVEL"); ?></p>
						  <p><span>Job Group </span> : <?php echo $this->global_m->get_array_data($posDetail, "FAMILY_TXT"); ?></p>
						  <a class="btn btn-info btn-shadow btn-sm" href="#myModal" data-toggle="modal"> Change </a>
						  <br />
						  <br />
						  <br />
						  <br />
					  </div>
					  <div class="col-lg-12">
							<h1>Competency<div class="pull-right"><a href="<?= base_url(); ?>index.php/ecs/all/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>" class="btn btn-info btn-shadow btn-sm" id="btnShow">View All Competency</a></div></h1>
							<? if($this->common->cek_pihc_access()==1){ ?>
							<select id="selCompt" class="form-control input-sm m-bot05">
								<option value="1" <?php echo ($selBase==1?'selected="selected"':''); ?>>Holding Competency</option>
								<option value="2" <?php echo ($selBase==2?'selected="selected"':''); ?>>Subsidiary Competency</option>
							</select>
							<? } ?>
							<table class="table tbl-compt">
								<thead>
									<tr>
										<th colspan="2" rowspan="2">Competency</th>
										<th colspan="6">Level</th>
										<th colspan="2">Match</th>
									</tr>
									<tr>
										<th>1</th>
										<th>2</th>
										<th>3</th>
										<th>4</th>
										<th>5</th>
										<th>6</th>
										<th>%</th>
										<th>Gap</th>
									</tr>
								</thead>
								<tbody>
								<?php if($posCompt){ // $aRtn['C1']['ACH']['Pos'] = 1; $aRtn['KC']['C1'] = 'Core Competentcy';
										$j = 1;
										foreach ($posCompt as $sGroup => $aPosCompt) { ?>
											<tr>
												<td colspan="8"> <?php echo $comptDef['KC'][$sGroup]; ?></td>
												<td style="text-align: right;"><?php echo number_format($comptSub[$sGroup]['SubM'] / $comptSub[$sGroup]['Sub'], 2, ',', ' '); ?> %</td>
												<td style="text-align: right;"><?php echo $comptSub[$sGroup]['SubG']; ?> </td>
											</tr>
									<?php	if($aPosCompt){
											//	$j = 1;
												foreach($aPosCompt as $sCompt => $aDetail){ ?>
												<tr>
													<td rowspan="2"><?php echo $j;?></td>
													<td rowspan="2"><?php echo $comptDef[$sGroup][$sCompt]; ?></td>
													<?php 
														for($i=0; $i<$aDetail['Pos']; $i++){
															echo "<td class=\"compt-blue\"></td>";
														} 
														if($i < 6){
															for($k = $i; $k < 6 ; $k++){
																echo "<td></td>";
															}
														}
													?>
													<td style="text-align: right;" rowspan="2"><?php echo number_format($aDetail['Match'], 2, ',', ' '); ?> %</td>
													<td style="text-align: right;" rowspan="2"><?php echo $aDetail['Gap']; ?></td>
												</tr>
												<tr>
													<?php 
														if($aDetail['Emp']==$aDetail['Pos']){
															$sClass = "compt-green";
														}elseif($aDetail['Emp']>$aDetail['Pos']){
															$sClass = "compt-green-max";
														}else{
															$sClass = "compt-red";
														}
														//$sClass = ($aDetail['Emp']>=$aDetail['Pos']?"compt-green-max":"compt-red");
														for($l=1; $l<=$aDetail['Emp']; $l++){
															echo "<td class=\"".$sClass."\"></td>";
														} 
														if($l < 6){
															for($m = $l; $m <= 6 ; $m++){
																echo "<td></td>";
															}
														}
													?>
												</tr>
												<tr>
													<td colspan="10"></td>
												</tr>
									<?php		$j++;
												}												
											}	?>
								<?php 	}
									}	?>
									<tr>
										<td colspan="2">Total Point of Suitability</td>
										<td colspan="6"></td>
										<td style="text-align: right;"><?php echo number_format($comptTot['TotM'], 2, ',', ' '); ?> %</td>
										<td style="text-align: right;"><?php echo $comptTot['TotG']; ?></td>
									</tr>
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
						  <input type="text" class="form-control" id="fplans" name="fplans" value="<?php echo $this->global_m->get_array_data($plansCompare, "PLANS"); ?>" style="padding: 3px 0px;">
					  </div>
					<!--  <div class="radios form-group">
							Base On.
							<label class="label_radio" for="radio-01">
								<input name="fcompt" id="radio-01" value="1" type="radio" checked /> Holding Competency
							</label>
							<label class="label_radio" for="radio-02">
								<input name="fcompt" id="radio-02" value="2" type="radio" /> Subsidiary Competency
							</label>
						</div>	-->
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