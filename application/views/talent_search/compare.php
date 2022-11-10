<section class="wrapper">
  <!-- page start-->
  <div class="row">
	  <aside class="profile-nav col-lg-3">
		  <section class="panel">
			  <div class="user-heading round">
				  <a href="#myModal2" data-toggle="modal">
					  <img alt="" src="<?= base_url(); ?>img/photo/<?php echo $this->global_m->get_array_data($emp, "PERNR"); ?>.jpg" onerror="this.src='<?= base_url(); ?>img/photo/default.jpg';">
				  </a>
				  <h1><?php echo $this->global_m->get_array_data($emp, "CNAME"); ?></h1>
                                <p><?php echo $this->global_m->get_array_data($emp, "PERNR"); ?><?php if(!empty($emp_map['NIK'])) { echo " / ".$this->global_m->get_array_data($emp_map, "NIK"); } ?>
				  <br /><?php echo $this->global_m->get_array_data($emp_map, "POSISI"); ?>
				  <br /><?php echo $this->global_m->get_array_data($emp_map, "PERSH"); ?>
                                </p>
			  </div>

			  <ul class="nav nav-pills nav-stacked">
				  <li><a href="<?php echo $base_url; ?>index.php/tprofile/view/<?php echo $this->global_m->get_array_data($emp, "PERNR"); ?>"> <i class="fa fa-user"></i> Profile</a></li>
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
						  <p><span>Compare Job</span>: <?php echo $this->global_m->get_array_data($o_job, "STEXT"); ?> </p>
						  <p><span>Company</span>: <?php echo $this->global_m->get_array_data($o_company, "STEXT"); ?> (<?php echo $this->global_m->get_array_data($o_company, "SHORT"); ?>) </p>
						  <p><span>Compare Unit</span>: <?php echo $this->global_m->get_array_data($o_unit, "STEXT"); ?> (<?php echo $this->global_m->get_array_data($o_unit, "SHORT"); ?>) </p>
						  <p><span>Compare Position </span> : <?php echo $this->global_m->get_array_data($o_pos, "STEXT"); ?></p>
						  <br />
						  <br />
						  <br />
						  <br />
					  </div>
					  <div class="col-lg-12">
							<h1>Competency</h1>
							<table class="table tbl-compt">
								<thead>
									<tr>
										<th colspan="2" rowspan="2">Competency</th>
										<th colspan="7">Level</th>
										<th colspan="2">Match</th>
									</tr>
									<tr>
										<th>1</th>
										<th>2</th>
										<th>3</th>
										<th>4</th>
										<th>5</th>
										<th>Bobot</th>
										<th>%</th>
										<th>Gap</th>
									</tr>
								</thead>
								<tbody>
								<?php if($posCompt){ // $aRtn['C1']['ACH']['Pos'] = 1; $aRtn['KC']['C1'] = 'Core Competentcy';
										$j = 1;
										foreach ($posCompt as $sGroup => $aPosCompt) {
                                                                    ?>
											<tr>
												<td colspan="7"> <?php echo $comptDef['KC'][$sGroup]; ?></td>
												<td style="text-align: right;"></td>
												<td style="text-align: right;"><?php echo number_format($comptSub[$sGroup]['SubM'] , 2, ',', ' '); ?> %</td>
												<td style="text-align: right;"><?php echo $comptSub[$sGroup]['SubG']; ?> </td>
											</tr>
									<?php	if($aPosCompt){
												foreach($aPosCompt as $sCompt => $aDetail){ ?>
												<tr>
													<td rowspan="2"><?php echo $j;?></td>
													<td rowspan="2"><?php echo $comptDef[$sGroup][$sCompt]; ?></td>
													<?php 
														for($i=0; $i<$aDetail['Pos']; $i++){
															echo "<td class=\"compt-blue\"></td>";
														} 
														if($i < 5){
															for($k = $i; $k < 5 ; $k++){
																echo "<td></td>";
															}
														}
													?>
													<td style="text-align: right;" rowspan="2"><?php echo number_format($aDetail['bobot'], 2, ',', ' '); ?></td>
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
														if($l < 5){
                                                                                                                    for($m = $l; $m <= 5 ; $m++){
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
</section>