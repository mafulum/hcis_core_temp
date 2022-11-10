<?php $iPIHC = $this->common->cek_pihc_access(); ?>
<section class="wrapper">
    <!-- page start-->
    <div class="row">
		<form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/srank/view" method="post">
			<div class="col-md-6">
				<div class="panel">
					<div class="panel-heading">
						Position Selection
					</div>
					<div class="panel-body" id="divForm2">
						<div class="form-group">
							<label for="fprsh2" class="col-lg-2 col-sm-2 control-label">Company</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fprsh2" name="fprsh2" style="padding: 3px 0px;">
							</div>
						</div>
						<div class="form-group">
							<label for="funit2" class="col-lg-2 col-sm-2 control-label">Unit</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="funit2" name="funit2" style="padding: 3px 0px;">
							</div>
						</div>
						<div class="form-group">
							<label for="fjob2" class="col-lg-2 col-sm-2 control-label">Job Level</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fjob2" name="fjob2" style="padding: 3px 0px;">
							</div>
						</div>
						<div class="form-group">
							<label for="fpos2" class="col-lg-2 col-sm-2 control-label">Position</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fpos2" name="fpos2" style="padding: 3px 0px;">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel">
					<div class="panel-heading">
						Employee(s) Selection
					</div>
					<div class="panel-body" id="divForm">
						<div class="form-group">
							<label for="fnik" class="col-lg-2 col-sm-2 control-label">Name/NIK</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fnik" name="fnik" value="" style="padding: 0.5px 0px;">
							</div>
						</div>
						<div class="form-group">
							<label for="fjob" class="col-lg-2 col-sm-2 control-label">Job Level</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fjob" name="fjob" value="" style="padding: 0.5px 0px;">
							</div>
						</div>
						<div class="form-group">
							<label for="fgrade" class="col-lg-2 col-sm-2 control-label">Grade</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fgrade" name="fgrade" style="padding: 0.5px 0px;">
							</div>
						</div>
						<div class="form-group">
							<label for="fprsh" class="col-lg-2 col-sm-2 control-label">Company</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="fprsh" name="fprsh" value="" style="padding: 0.5px 0px;">
							</div>
						</div>
						<div class="form-group">
							<label for="ffam" class="col-lg-2 col-sm-2 control-label">Job Group</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="ffam" name="ffam" value="" style="padding: 0.5px 0px;">
							</div>
						</div>
					</div>			
				</div>
			</div>
			<div class="col-md-12" id="divBase">
				<div class="panel">
					<div class="panel-body">
						<div class="col-md-6" id="divBaseSearch">
							Search Base On.
							<div class="radios">
								<label class="label_radio" for="radio-01">
									<input name="fbase" id="radio-01" value="1" type="radio" checked /> Matrix Perusahaan
								</label>
								<label class="label_radio" for="radio-02">
									<input name="fbase" id="radio-02" value="0" type="radio" /> All Eligible Employee
								</label>
							</div>
						</div>
						<div class="col-md-6" id="divBaseCompt">
						<? if($iPIHC == 1){ ?>
							Competency Base On.
							<div class="radios">
								<label class="label_radio" for="radio-04">
									<input name="fcompt" id="radio-04" value="2" type="radio" checked /> Subsidiary Competency
								</label>
								<label class="label_radio" for="radio-03">
									<input name="fcompt" id="radio-03" value="1" type="radio" /> Holding Competency
								</label>
							</div>
						<? }else{ ?>
							<input type="hidden" id="fcompt" name="fcompt" value="2"/> 
						<? } ?>
						</div>
						<div class="col-md-12" id="divSearch">
							<button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
    <!-- page end-->
</section>