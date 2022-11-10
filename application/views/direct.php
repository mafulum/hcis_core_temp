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
            <!--header end-->
            <!--sidebar start-->
            <!--sidebar end-->
            <!--main content start-->
                <!-- page start-->
                <?php echo $this->load->view($view); ?>
                <!-- page end-->
                
            <!--main content end-->
            <!--footer start-->
            <!--footer end-->
        </section>

        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?= base_url(); ?>js/bootstrap.min.js"></script>
        <script class="include" type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dcjqaccordion.2.7.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.nicescroll.js" type="text/javascript"></script>
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
