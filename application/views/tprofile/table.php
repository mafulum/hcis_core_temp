<section class="wrapper">
    <!-- page start-->
    <div class="row">
		<div class="col-md-12" id="divResult">
			<div class="panel">
			    <div class="panel-heading">
				    All Employee Data
			    </div>
			    <div class="panel-body">
					<section id="flip-scroll">
					  <table class="table table-bordered table-striped table-condensed cf">
						  <thead class="cf">
						  <tr>
							  <th class="numeric">NIK Talent</th>
							  <th>Nama</th>
							  <th>NIK Asal</th>
							  <th>GESCH</th>
							  <th>Persh</th>
							  <th>Unit</th>
							  <th>Job</th>
							  <th>Posisi</th>
							  <th>Tgl Lahir</th>
							  <th>Usia</th>
							  <th>Tgl Masuk</th>
							  <th>Tgl Kary Tetap</th>
							  <th>Tgl Pensiun</th>
							  <th>MDG</th>
							  <th>Service Year in Curr.Pos</th>
							  <th>Last Educ -1</th>
							  <th>Last Educ -2</th>
							  <th>Last Educ -3</th>
							  <th>Last Educ -4</th>
							  <th>Last Educ -5</th>
						  </tr>
						  </thead>
						  <tbody>
							<?php if(isset($search)){
                                                            $this->load->model('tprofile_m');
								for ($i = 0; $i < count($search); $i++) { 
                                                                    $mdg = $this->tprofile_m->get_mdg($this->global_m->get_array_data($search[$i], "PERNR"));
                                                                    $aEducs = $this->tprofile_m->getEduc($this->global_m->get_array_data($search[$i], "PERNR"));
                                                                    $lastEduc = $this->tprofile_m->getLastEduc($aEducs);
                                                                    ?>
								<tr>
								  <td><a href="<?php echo $base_url; ?>index.php/tprofile/view/<?php echo $this->global_m->get_array_data($search[$i], "PERNR"); ?>"><?php echo $this->global_m->get_array_data($search[$i], "PERNR"); ?></a></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "CNAME"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "NIK"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "GESCH"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($org,$this->global_m->get_array_data($search[$i], "PERUS")); ?></td>
								  <td><?php echo $this->global_m->get_array_data($org,$this->global_m->get_array_data($search[$i], "ORGEH")); ?></td>
								  <td><?php echo $this->global_m->get_array_data($stell,$this->global_m->get_array_data($search[$i], "STELL")); ?></td>
								  <td><?php echo $this->global_m->get_array_data($pos,$this->global_m->get_array_data($search[$i], "PLANS")); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "GBDAT"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "age"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "TTanggalMasuk"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "TTanggalPegTetap"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "TTanggalPensiun"); ?></td>
								  <td><?php echo "Grade ".$this->global_m->get_array_data($mdg, "TRFGR") . $this->global_m->get_array_data($mdg, "TRFST") ." (" . $this->global_m->get_array_data($mdg, "MDGY") ."years ". $this->global_m->get_array_data($mdg, "MDGM") ."months)"; ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "SVCY") ."years ". $this->global_m->get_array_data($search[$i], "SVCM") ."months"; ?></td>
                                                                  
                                                                  <?php for ($j = 0; $j < count($lastEduc); $j++) { 
                                                                      ?>
                                                                <?php echo '<td>'.$this->global_m->get_array_data($lastEduc[$j], "STEXT"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$j], "SLTP1"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$j], "INSTI"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$j], "EMARK"); ?>, <?php echo $this->global_m->get_array_data($lastEduc[$j], "LULUS"); ?>,  <?php if($this->global_m->get_array_data($lastEduc[$j], "BIAYA")!="Pribadi") echo ", ".$this->global_m->get_array_data($lastEduc[$j], "BIAYA").'</td>'; ?>
                                                                <?php } 
                                                                        for(;$j<5;$j++){
                                                                            echo "<td></td>";
                                                                        }
                                                                   ?>
                                                                
								</tr>
							<?php } 
								}else{
							?>
								<tr><td colspan="6">No Data Found</td></tr>
							<?php
								}								
							?>
						  </tbody>
					  </table>
					</section>
				</div>
			</div>
		</div>
	</div>
    <!-- page end-->
</section>