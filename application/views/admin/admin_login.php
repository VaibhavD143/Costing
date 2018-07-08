<!DOCTYPE html>
<html>

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo base_url();?>admin_design/assets/images/favicon_1.ico">

        <title>Vishwa</title>

        <link href="<?php echo base_url();?>admin_design/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>admin_design/assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>admin_design/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>admin_design/assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>admin_design/assets/css/pages.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>admin_design/assets/css/menu.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>admin_design/assets/css/responsive.css" rel="stylesheet" type="text/css">

        <script href="<?php echo base_url();?>admin_design/assets/js/modernizr.min.js"></script>
    </head>
    <body>


        <div class="wrapper-page">
            <div class="panel panel-color panel-primary panel-pages">
                <div class="panel-heading bg-img"> 
                    <div class="bg-overlay"></div>
                    <h3 class="text-center m-t-10 text-white"> Sign In to <strong>Panel</strong> </h3>
                </div> 

                <div class="panel-body">
		<?php if(isset($_GET['error'])) {
			echo "<div class='error'>Invalid username or password</div>";
		} ?>
                <form class="form-horizontal m-t-20" action="<?php echo base_url(); ?>login/login" method="post">
				<?php echo validation_errors(); ?>		
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input name="txtemail" class="form-control input-lg" value="<?php echo set_value('txtpass'); ?>" type="text" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input name="txtpassword" class="form-control input-lg" type="password" placeholder="Password">
                        </div>
                    </div>
                    
                    <div class="form-group text-center m-t-40">
                        <div class="col-xs-12">
                            <button name="btnsubmit" class="btn btn-primary btn-lg w-lg waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>

                    <div class="form-group m-t-30">
                        <div class="col-sm-7">
                            <a href="<?php echo base_url();?>admin_design/recoverpw.html"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                        </div>
                        <!--<div class="col-sm-5 text-right">
                            <a href="<?php echo base_url();?>admin_design/register.html">Create an account</a>
                        </div>-->
                    </div>
                </form> 
                </div>                                 
                
            </div>
        </div>

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- Main  -->
        <script href="<?php echo base_url();?>admin_design/assets/js/jquery.min.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/bootstrap.min.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/detect.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/fastclick.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/jquery.slimscroll.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/jquery.blockUI.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/waves.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/wow.min.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/jquery.nicescroll.js"></script>
        <script href="<?php echo base_url();?>admin_design/assets/js/jquery.scrollTo.min.js"></script>

        <script href="<?php echo base_url();?>admin_design/assets/js/jquery.app.js"></script>
	
	</body>

<!-- Mirrored from moltran.coderthemes.com/dark/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 Mar 2016 02:12:00 GMT -->
</html>