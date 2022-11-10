<div class="sidebar-toggle-box">
    <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
</div>
<!--logo start-->
<a class="logo" href="<?php echo base_url(); ?>">
    Beyond Apps <span>HC Core</span>
    <img alt="" src="<?php echo base_url(); ?>img/logo2.png">
</a>
<!--logo end-->
<div class="top-nav ">

    <script>
        function change_password(){
            $("#chg-pwd").modal("show");
    
            jQuery(document).ready(function() {
                
                $("#fr_chg_pwd").validate({
                    rules: {
                        oPass: {
                            required: true,
                            minlength: 6
                        },nPass: {
                            required: true,
                            minlength: 6
                        },
                        rPass: {
                            required: true,
                            minlength: 6,
                            equalTo: "#nPass"
                        }
                    },
                    messages: {
                        oPass: {
                            required: "Please provide current password",
                            minlength: "Your password must be at least 6 characters long"
                        },
                        nPass: {
                            required: "Please provide a new password",
                            minlength: "Your password must be at least 6 characters long"
                        },
                        rPass: {
                            required: "Please provide a password",
                            minlength: "Your password must be at least 6 characters long",
                            equalTo: "Please enter the same password as above"
                        }
                    },submitHandler: function() {
                        xoPass= $("#oPass").val();
                        xnPass = $("#nPass").val();
                        xrPass = $("#rPass").val();
                        xcPage = $("#cPage").val();
                        $.post( "<? echo base_url(); ?>index.php/login/change_password", { "oPass": xoPass,"nPass": xnPass, "rPass": xrPass,'cPage':xcPage },function (text){
                            if(text!="Success"){
                                $("#msg").html(text);
                            }else{
                                alert(text);
                                $("#chg-pwd").modal("hide");
                            }
                        });
                    }
                });
            });
        }
    </script>
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="<?php echo base_url(); ?>img/avatar-mini.jpg">
                <span class="username">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->session->userdata('username');?></span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <div class="log-arrow-up"></div>
            <!--     <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>   
                <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>-->
                <li><a href="#"></a></li>  
                <li><a href="#" onclick="change_password();"><i class="fa fa-key"></i>Change Password</a></li>
                <li><a href="<?php echo base_url(); ?>index.php/logout"><i class="fa fa-lock"></i>Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
    <!--search & user info end-->
</div>

