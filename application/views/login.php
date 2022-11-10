<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="img/favicon.png">
        <title>HCIS GDPS - Beyond Care</title>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/bootstrap-reset.css" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/style-responsive.css" rel="stylesheet" />
    </head>

    <body class="login-body white-bg">
        <div class="container" style="margin-top: 100px;">
            <div class="row">
                <div class="col-md-5 text-right">
                    <div class="form-signin" >
                        <img src="<?php echo base_url();?>/img/Beyond_Care.png" style="width: 300px;padding-top: 20px;border-color: #000;"/>
                    </div>
                </div>
                <div class="col-md-5"style="border-left: 1px solid #233F90;">
                    <form method="post" class="form-signin" action="<?php echo base_url(); ?>index.php/login">
                        <h2 style="color:#233F90;text-align: center;font-weight: bolder;">Sign In</h2>
                        <div class="login-wrap">
                            <input name="username" type="text" class="form-control" placeholder="User ID" autofocus>
                            <input name="password" type="password" class="form-control" placeholder="Password">
                            <button class="btn btn-lg btn-login btn-block" style="background: #EE7D30;" type="submit">Sign in</button>
                            <div class="registration">
                                <? if (validation_errors()) { ?>
                                    <?php echo $this->form_validation->error('username'); ?>				
                                <? } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row" style="height: 50px;"></div>
        <div class="row" >
            <div class="col-md-2"></div>
            <div class="col-md-8" style="border-top: 1px solid #233F90;">
                <img src="<?php echo base_url();?>/img/gdps_logo.png" style="width: 150px;"/>
                <img src="<?php echo base_url();?>/img/people_meets_tech.png" style="padding-left:30px;height: 25px;"/>
                <img src="<?php echo base_url();?>/img/Beyond_Expectation_Logo-02.png" style="padding-left:30px;width: 150px;"/>
                <a href="#" class="go-top">
                    <i class="fa fa-angle-up"></i>
                </a>
            </div>
        </div>
        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    </body>
</html>
