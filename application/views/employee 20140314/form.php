
<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-lg-4">
            <section class="panel">
                <header class="panel-heading">
                    Collapsible Widget
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                <div class="panel-body">
                    <label  class="col-lg-3 col-sm-3 control-label">Nopeg</label>
                    <div class="col-lg-9">
                        <div class="iconic-input right">
                            <i class="fa fa-book"></i>
                            <input type="text" class="form-control" placeholder="right icon">
                        </div>
                    </div>
                </div>
            </section>
            <!--widget start-->
            <aside class="profile-nav alt green-border">
                <section class="panel">
                    <div class="user-heading alt green-bg">
                        <a href="#">
                            <img alt="" src="<?= base_url(); ?>img/profile-avatar.jpg">
                        </a>
                        <h1>Jonathan Smith</h1>
                        <p>300002</p>
                    </div>

                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="javascript:;"> <i class="fa fa-clock-o"></i> NIK Asal : T021343234</a></li>
                        <li><a href="javascript:;"> <i class="fa fa-calendar"></i> Posisi : Human Capital Analyst</a></li>
                        <li><a href="javascript:;"> <i class="fa fa-bell-o"></i> Unit : SDM</a></li>
                        <li><a href="javascript:;"> <i class="fa fa-envelope-o"></i> Perusahaan : Pupuk Kaltim</a></li>
                    </ul>

                </section>
            </aside>
            <!--widget end-->
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12">
                    <!--widget start-->
                    <section class="panel">
                        <header class="panel-heading">
                            Personal Data
                        </header>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="input1" class="col-lg-2 col-sm-2 control-label">NIK Asal</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input2" class="col-lg-2 col-sm-2 control-label">NIK ERP</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input2">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input3" class="col-lg-2 col-sm-2 control-label">Nama</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input3">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input4" class="col-lg-2 col-sm-2 control-label">Jenis Kelamin</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input4">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input5" class="col-lg-2 col-sm-2 control-label">Agama</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input5">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input6" class="col-lg-2 col-sm-2 control-label">Tanggal Lahir</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input6">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input7" class="col-lg-2 col-sm-2 control-label">Tempat Lahir</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input7">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input8" class="col-lg-2 col-sm-2 control-label">Warga Negara</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input8">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input9" class="col-lg-2 col-sm-2 control-label">Status</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="input9">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                       <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
					<a class="btn btn-default" href="<?php echo base_url()."index.php/employee/personal_data_ov";?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
                    <!--widget end-->
                </div>
            </div>
        </div>
    </div>
    <!-- page end-->
</section>