<section class="wrapper">
    <!-- page start-->
    <div class="row">
		<div class="col-md-12">
		    <section class="panel">
			    <header class="panel-heading">
					Talent Profile Search
					<span class="tools pull-right">
						<a class="fa fa-chevron-down" href="javascript:;"></a>
					</span>
			    </header>
			    <div class="panel-body" id="divForm">
					<form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/tprofile/search/Y" method="post">
						<div class="form-group">
							<label for="fnik" class="col-lg-2 col-sm-2 control-label">Name/NIK</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fnik" name="fnik" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="fjob" class="col-lg-2 col-sm-2 control-label">Job</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fjob" name="fjob" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="fgrade" class="col-lg-2 col-sm-2 control-label">Grade</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fgrade" name="fgrade" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="fprsh" class="col-lg-2 col-sm-2 control-label">Company</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fprsh" name="fprsh" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="funit" class="col-lg-2 col-sm-2 control-label">Unit</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="funit" name="funit" value="">
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
							</div>
						</div>
					</form>
			    </div>
		    </section>
	    </div>
		<div class="col-md-12" id="divResult" style="display:none">
			<div class="panel">
			    <div class="panel-heading">
				    Search Result
			    </div>
			    <div class="panel-body">
					<section id="flip-scroll">
					  <table class="table table-bordered table-striped table-condensed cf">
						  <thead class="cf">
						  <tr>
							  <th class="numeric">NIK</th>
							  <th>Name</th>
							  <th>Job</th>
							  <th>Grade</th>
							  <th>Persh.</th>
							  <th>Unit</th>
						  </tr>
						  </thead>
						  <tbody>
							<?php if(isset($search)){
								for ($i = 0; $i < count($search); $i++) { ?>
								<tr>
								  <td><a href="<?php echo $base_url; ?>index.php/tprofile/view/<?php echo $this->global_m->get_array_data($search[$i], "PERNR"); ?>"><?php echo $this->global_m->get_array_data($search[$i], "PERNR"); ?></a></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "CNAME"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "STELL"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "GRADE"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "SHORT"); ?></td>
								  <td><?php echo $this->global_m->get_array_data($search[$i], "STEXT"); ?></td>
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