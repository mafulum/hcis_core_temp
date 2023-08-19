
<section class="wrapper">
    <!--state overview start-->
    <div class="row state-overview">
        <div class="col-lg-3 col-sm-6"> 
            <section class="panel">
                <div class="symbol terques">
                    <i class="fa fa-user"></i>
                </div>
                <div class="count_value" style="color:#000099">
                    <h1 class="count">
                        <b><? echo $this->global_m->get_count_master_emp(); ?></b>
                    </h1>
                    <p style="font-size: 16px;">Head Count</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol" style="background-color: #2F90ED;">
                    <i class="fa fa-heart"></i>
                </div>
                <div class="count_value">
                    <h1 class=" count3"><b>
                        <?php
                        echo $this->global_m->get_count_bodboc();
                        ?></b>
                    </h1>
                    <p>BoD / BoC</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol" style="background-color: #2F90ED;">
                    <i class="fa fa-users"></i>
                </div>
                <div class="count_value">
                    <h1 class=" count3"><b>
                        <?php
                        echo $this->global_m->get_count_management();
                        ?></b>
                    </h1>
                    <p>GDPS management</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol terques">
                    <i class="fa fa-thumbs-up"></i>
                </div>
                <div class="count_value">
                    <h1 class=" count3"><b>
                        <?php
                        echo $this->global_m->get_count_all_tad();
                        ?></b>
                    </h1>
                    <p>GDPS TAD</p>
                </div>
            </section>
        </div>
    </div>
    <div class="row state-overview">
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol blue">
                    <i class="fa fa-plane"></i>
                </div>
                <div class="count_value" style="color:#000099">
                    <h1 class=" count2"><b>
                        <?php echo $this->global_m->get_count_gagroup();?></b>
                    </h1>
                    <p>GA Group Affiliation</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol blue">
                    <i class="fa fa-wrench"></i>
                </div>
                <div class="count_value" style="color:#000099">
                    <h1 class=" count4"><b>
                        <?php echo $this->global_m->get_count_gmf(); ?></b>
                    </h1>
                    <p>GMF</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol blue">
                    <i class="fa fa-external-link-square"></i>
                </div>
                <div class="count_value" style="color:#000099">
                    <h1 class=" count4">
                        <b><?php echo $this->global_m->get_count_other(); ?></b>
                    </h1>
                    <p>Non GA Group Affiliation</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol blue">
                    <i class="fa fa-magic"></i>
                </div>
                <div class="count_value" style="color:#000099">
                    <h1 class=" count4">
                        <b><?php echo $this->global_m->get_count_mitra(); ?></b>
                    </h1>
                    <p>GDPS Mitra</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol" style="background-color: #2F90ED;">
                    <i class="fa fa-angle-double-down"></i>
                </div>
                <div class="count_value">
                    <h1 class=" count3"><b>
                        <?php
                        echo $this->global_m->get_count_perbantuan_penugasan_in();
                        ?></b>
                    </h1>
                    <p>Perbantuan / Penugasan In</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol" style="background-color: #2F90ED;">
                    <i class="fa fa-angle-double-up"></i>
                </div>
                <div class="count_value">
                    <h1 class=" count3"><b>
                        <?php
                        echo $this->global_m->get_count_perbantuan_penugasan_out();
                        ?></b>
                    </h1>
                    <p>Perbantuan / Penugasan Out</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol blue">
                    <i class="fa fa-star"></i>
                </div>
                <div class="count_value" style="color:#000099">
                    <h1 class=" count4">
                        <b><?php echo $this->global_m->get_count_PKWTT(); ?></b>
                    </h1>
                    <p>PKWTT</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol blue">
                    <i class="fa fa-star-half-o"></i>
                </div>
                <div class="count_value" style="color:#000099">
                    <h1 class=" count4">
                        <b><?php echo $this->global_m->get_count_PKWT(); ?></b>
                    </h1>
                    <p>PKWT</p>
                </div>
            </section>
        </div>
    </div>
</section>