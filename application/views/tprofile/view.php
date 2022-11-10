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
                                  <p><?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?> <?php if(!empty($emp_map['NIK'])){ echo " / ".$this->global_m->get_array_data($emp_map, "NIK"); } ?>
				  <br /><?php echo $this->global_m->get_array_data($emp_map, "POSISI"); ?>
				  <br /><?php echo $this->global_m->get_array_data($emp_map, "PERSH"); ?></p>
			  </div>

			  <ul class="nav nav-pills nav-stacked">
				  <li class="active"><a href="<?php echo $base_url; ?>index.php/tprofile/view/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-user"></i> Profile</a></li>
				  <li><a href="<?php echo $base_url; ?>index.php/gen_pdf/profile/<?php echo $hash; ?>"> <i class="fa fa-download"></i> Download Data</a></li>
                         <!--         <li><a href="<?php echo $base_url; ?>index.php/ecs/view/<?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?>"> <i class="fa fa-calendar"></i> Competency Summary</a> </li>
				  <li><a href="<?php echo $base_url; ?>index.php/srank/view/<?php echo $this->global_m->get_array_data($emp_org, "PLANS"); ?>"> <i class="fa fa-edit"></i> Succession Planning</a></li>-->
				  <li><a href="<?php echo $base_url; ?>index.php/tprofile/search"> <i class="fa fa-mail-reply"></i> Back</a></li>
			  </ul>

		  </section>
	  </aside>
	  <aside class="profile-info col-lg-9">
		  <section class="panel">
			  <div class="bio-graph-heading">
				  Talent Profile
			  </div>
			  <div class="panel-body bio-graph-info">
				  <div class="row">
					  <div class="bio-row">
						  <h1>Personal Data</h1>
                                                  <p><span>Name </span>: <?php echo $this->global_m->get_array_data($master_emp, "CNAME"); ?></p>
						  <p><span>NIK </span>: <?php echo $this->global_m->get_array_data($master_emp, "PERNR"); ?></p>
                                                  <?php if (!empty($emp_map['NIK'])) { ?>
						  <p><span>NIK Asal </span>: <?php echo $this->global_m->get_array_data($emp_map, "NIK"); ?> /  <?php echo $this->global_m->get_array_data($emp_map, "COMP_REF"); ?></p>
                                                  <?php } ?>
						  <p><span>Tgl. Lahir </span>: <?php echo $this->global_m->get_array_data($emp, "GBDAT"); ?> (<?php echo $this->global_m->get_array_data($emp, "age"); ?> years old)</p>
						  <p><span>Tgl. Masuk </span>: <?php echo $this->global_m->get_array_data($empDate, "TTanggalMasuk"); ?> </p>
						  <p><span>Tgl. Kary. Tetap </span>: <?php echo $this->global_m->get_array_data($empDate, "TTanggalPegTetap"); ?> </p>
						  <p><span>Tgl. Pensiun </span>: <?php echo $this->global_m->get_array_data($empDate, "TTanggalPensiun"); ?> </p>
						<!--  <p><span>Tgl. MPP </span>: <?php echo $this->global_m->get_array_data($empDate, "TTanggalMPP"); ?> </p>	-->
                                                  <?php if(!empty($mdg)) { ?>
						  <p><span>MDG </span>: Grade <?php echo $this->global_m->get_array_data($mdg, "TRFGR") . $this->global_m->get_array_data($mdg, "TRFST") ." (" . $this->global_m->get_array_data($mdg, "MDGY") ."years ". $this->global_m->get_array_data($mdg, "MDGM") ." months)"; ?></p>
                                                  <?php } ?>
						  <p><span>Service Year in Current Position </span>: <?php echo $this->global_m->get_array_data($svcyear, "SVCY") ." years ". $this->global_m->get_array_data($svcyear, "SVCM") ." months"; ?></p>
                                                  <br />
						  <br />
						  <h1>Last Education</h1>
						  <?php for ($i = 0; $i < count($lastEduc); $i++) { ?>
						  <p><?php echo $this->global_m->get_array_data($lastEduc[$i], "STEXT"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$i], "SLTP1"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$i], "INSTI"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$i], "EMARK"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$i], "LULUS"); ?> <?php if($this->global_m->get_array_data($lastEduc[$i], "BIAYA")!="Pribadi") echo ", ".$this->global_m->get_array_data($lastEduc[$i], "BIAYA"); ?></p>
						  <?php } ?>
						  <br />
						  <br />
						  <h1>Future Assignment</h1>
						  &nbsp;&nbsp;&nbsp;&nbsp;<i>Please see Profile Matchup Report</i>
						  <br />
						  <br />
						  <br />
						  <h1>Prior Assignment</h1>
						  <?php if(count($prior) > 1){?>
						  <ul>
							<?php for ($i = 1; $i < count($prior); $i++) { ?>
								<li><?php echo $this->global_m->get_array_data($prior[$i], "STEXT"); ?>,
								(<?php echo $this->global_m->get_array_data($prior[$i], "TBEGDA"); ?> - 
								<?php echo $this->global_m->get_array_data($prior[$i], "TENDDA"); ?>)
								</li>
							<?php } ?>
						  </ul>
						  <?php
							}else echo "-";
						  ?>
						  <br />
						  <br />
						  <h1>Achievement</h1>
						  <?php if($awards){?>
						  <ul>
							<?php for ($i = 0; $i < count($awards); $i++) { ?>
								<li><?php echo $this->global_m->get_array_data($awards[$i], "STEXT"); ?>,
								<?php echo $this->global_m->get_array_data($awards[$i], "TBEGDA"); ?>,
								<?php echo $this->global_m->get_array_data($awards[$i], "TEXT1"); ?>
								</li>
							<?php } ?>
						  </ul>
						  <?php
							}else echo "-";
						  ?>
						  <br />
						  <br />
						  <h1>Grievances</h1>
						  <?php if($grievances){?>
						  <ul>
							<?php for ($i = 0; $i < count($grievances); $i++) { ?>
								<li><?php echo $this->global_m->get_array_data($grievances[$i], "SUBTY"); ?>,
								<?php echo $this->global_m->get_array_data($grievances[$i], "TBEGDA"); ?> - <?php echo $this->global_m->get_array_data($grievances[$i], "TENDDA"); ?>,
								<?php echo $this->global_m->get_array_data($grievances[$i], "TEXT1"); ?>
								</li>
							<?php } ?>
						  </ul>
						  <?php
							}else echo "-";
						  ?>
						  <br />
						  <br />
						  <h1>Medical History</h1>
						  <?php if($medical){?>
						  <ul>
							<?php for ($i = 0; $i < count($medical); $i++) { ?>
								<li><?php echo $this->global_m->get_array_data($medical[$i], "STEXT"); ?>,
								<?php echo $this->global_m->get_array_data($medical[$i], "TBEGDA"); ?>,
								<?php echo ucwords(strtolower($this->global_m->get_array_data($medical[$i], "TEXT1"))); ?>
								</li>
							<?php } ?>
						  </ul>
						  <?php
							}else echo "-";
						  ?>
					  </div>
					  <div class="bio-row">
						  <h1>Talent Map</h1>
							<img width="350px" src="<?= base_url(); ?>img/tmap/<?php echo $this->global_m->get_array_data($talentMap, "SHORT"); ?>.PNG" alt="talentMap" onerror="this.src='<?= base_url(); ?>img/tmap/normal.png';">
						  <br />
						  <br />
						  <h1>Assessment</h1>
						  <ul>
							<li>Potential : <?php echo $potential;?></li>
							<li><?php 
								if($perf){
									echo "Performance : ".$perf[0]["DESC"]." (".$perf[0]["IDX"].")";
									for($i=0;$i<count($perf);$i++){
										echo "<p><span>".substr($perf[$i]['ENDDA'],0,4)." </span>: ".$perf[$i]["DESC"]." (".$perf[$i]["IDX"].")</p>";
									}
								}?>
							</li>
							<li style="	text-align: justify;">Description : <?php echo $this->global_m->get_array_data($talentMap, "STEXT"); ?> (<?php echo $this->global_m->get_array_data($talentMap, "SHORT"); ?>)
								<br /><?php echo $this->global_m->get_array_data($talentMap, "DESC"); ?>
							</li>
						  </ul>
						  <br />
						  <br />
					      <h1>Training History</h1>
						  <?php if($training){?>
						  <ul>
							<?php for ($i = 0; $i < count($training); $i++) { ?>
								<li><?php echo $this->global_m->get_array_data($training[$i], "STEXT"); ?>,
								<?php echo ucwords(strtolower($this->global_m->get_array_data($training[$i], "SLTP1"))); ?>, 
								<?php echo $this->global_m->get_array_data($training[$i], "TBEGDA"); ?> - <?php echo $this->global_m->get_array_data($training[$i], "TENDDA"); ?>, <?php echo ucwords(strtolower($this->global_m->get_array_data($training[$i], "INSTI"))); ?>
								</li>
							<?php } ?>
						  </ul>
						  <?php
							}else echo "-";
						  ?>
					  </div>
				  </div>
			  </div>
		  </section>
	  </aside>
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