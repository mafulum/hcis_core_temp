<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Mosaddek">
        <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
        <link rel="shortcut icon" href="<?php echo base_url(); ?>img/favicon.png">

        <title>HCIS GDPS - Beyond Care</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/bootstrap-reset.css" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

        <!-- Custom styles for this template -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap-datepicker/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />

        <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/style-responsive.css" rel="stylesheet" />

        <?php
        if (isset($externalCSS)) {
            echo $externalCSS;
        }
        ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <section id="container" >
            <!--header start-->
            <header class="header white-bg">
                <?php echo $this->load->view('main_header'); ?>
            </header>
            <!--header end-->
            <!--sidebar start-->
            <aside>
                <?php echo $this->load->view('upload/main_menu'); ?>
            </aside>
            <!--sidebar end-->
            <!--main content start-->
            <section id="main-content">
                <!-- page start-->
                <?php echo $this->load->view($view); ?>
                <!-- page end-->
                <div class="modal " id="chg-pwd" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
                            </div>
                            <form class="form-horizontal" role="form" action="<?php echo $base_url; ?>index.php/login/change_password" method="post" id="fr_chg_pwd" name="fr_chg_pwd">
                                <input type="hidden" name="cPage" value="<?php echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>"/>
                                <div class="modal-body" id="mbody">
                                    <div class="form-group has-error" style="font-weight: bold;font-size: large;color:red;">
                                        <span id="msg" class="error-message"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="oPass" class="col-lg-4 col-sm-2 control-label">Old Password</label>
                                        <div class="col-lg-8">
                                            <input style="padding: 3px 0px;" type="password" class="form-control" id="oPass" name="compt">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nPass" class="col-lg-4 col-sm-2 control-label">New Password</label>
                                        <div class="col-lg-8">
                                            <input style="padding: 3px 0px;" type="password" class="form-control" id="nPass" name="nPass">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="rPass" class="col-lg-4 col-sm-2 control-label">Retype New Password</label>
                                        <div class="col-lg-8">
                                            <input style="padding: 3px 0px;" type="password" class="form-control" id="rPass" name="rPass">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" onclick="$('#chg-pwd').modal('hide');" id="btnCC">Cancel</button>
                                    <button type="submit" class="btn btn-danger danger" id="btnYesC">Yes</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!--main content end-->
            <!--footer start-->
            <footer class="site-footer">
                <?php echo $this->load->view('main_footer'); ?>
            </footer>
            <!--footer end-->
        </section>

        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?= base_url(); ?>js/bootstrap.min.js"></script>
        <script class="include" type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dcjqaccordion.2.7.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.nicescroll.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap-switch.js"></script>
        <script src="<?php echo base_url(); ?>js/advanced-form-components.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>

        <script src="<?php echo base_url(); ?>js/respond.min.js"></script>
        <?php
        if (isset($externalJS)) {
            echo $externalJS;
        }
        ?>
        <!--common script for all pages-->
        <script src="<?php echo base_url(); ?>js/common-scripts.js"></script>
        <?php
        if (isset($scriptJS)) {
            echo $scriptJS;
        }
        ?>


    </body>
</html>
