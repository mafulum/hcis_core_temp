
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
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><a href="#">01.01.2013</a></td>
                                <td>31.12.9999</td>
                                <td>Andi Sulistianto</td>
                                <td>01.01.1980</td>
                                <td>
									<a class="btn btn-success btn-xs" href="<?php echo base_url()."index.php/employee/personal_data_fr";?>" data-toggle="modal"> <i class="fa fa-search"></i> </a>
                                    <a class="btn btn-primary btn-xs" href="<?php echo base_url()."index.php/employee/personal_data_fr";?>" data-toggle="modal"> <i class="fa fa-pencil"></i> </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </section>
					<a class="btn btn-default" href="<?php echo base_url()."index.php/employee/master";?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
                    <!--widget end-->
                </div>
            </div>
        </div>
    </div>
    <!-- page end-->
</section>