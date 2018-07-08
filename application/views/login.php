<html>
	<head>
		<title>Login Page</title>
	<script type="text/javascript">
	//<![CDATA[
	try{if (!window.CloudFlare) {var CloudFlare=[{verbose:0,p:0,byc:0,owlid:"cf",bag2:1,mirage2:0,oracle:0,paths:{cloudflare:"/cdn-cgi/nexp/dok3v=1613a3a185/"},atok:"3d55a0f7e40ee7eae0a33383c2f88e19",petok:"ef99ca0ce09004a1c0562f38b70b5d5d89e6547a-1496233470-1800",zone:"revox.io",rocket:"0",apps:{"ga_key":{"ua":"UA-56895490-1","ga_bs":"1"}}}];!function(a,b){a=document.createElement("script"),b=document.getElementsByTagName("script")[0],a.async=!0,a.src="../../../../ajax.cloudflare.com/cdn-cgi/nexp/dok3v%3d85b614c0f6/cloudflare.min.js",b.parentNode.insertBefore(a,b)}()}}catch(e){};
	//]]>
	</script>
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/jquery-metrojs/MetroJs.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>admin_design/assets2/plugins/shape-hover/css/demo.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>admin_design/assets2/plugins/shape-hover/css/component.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>admin_design/assets2/plugins/owl-carousel/owl.carousel.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>admin_design/assets2/plugins/owl-carousel/owl.theme.css"/>
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo base_url();?>admin_design/assets2/plugins/jquery-ricksaw-chart/css/rickshaw.css" type="text/css" media="screen">
	<link rel="stylesheet" href="<?php echo base_url();?>admin_design/assets2/plugins/Mapplic/mapplic/mapplic.css" type="text/css" media="screen">
	 
	<!-- DataTables -->
			<link href="<?php echo base_url();?>admin_design/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
			<link href="<?php echo base_url();?>admin_design/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
			<link href="<?php echo base_url();?>admin_design/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
			<link href="<?php echo base_url();?>admin_design/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
			<link href="<?php echo base_url();?>admin_design/assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

	<link href="<?php echo base_url();?>admin_design/assets2/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/animate.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>admin_design/assets2/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo base_url();?>admin_design/assets2/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	 
	 
	<link href="<?php echo base_url();?>admin_design/webarch/css/webarch.css" rel="stylesheet" type="text/css"/>
	 
	<script type="text/javascript">
	/* <![CDATA[ */
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-56895490-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

	/* ]]> */
	</script>

	</head>
	<body >
	<?php //print_r($projects);?>
		<div class='content-page' style="background-color:white">
			<div class='content'>
				<div class='container'>
					<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<form class='cmxform tasi-form' id='signupForm' method='post' action='<?php echo site_url('login/login'); ?>' enctype='multipart/form-data' novalidate='novalidate'>
									<div class='grid-body'>
										<div class='form'>
										<div class='form-group col-md-12'>
											<label for='project' class='col-lg-2'>Project Name<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='project' name='project'>
													<option value=''>Select Labour Group Id</option>
													<?php foreach($projects as $project) {?>
															<option value="<?php echo $project['project_id'].",".$project['project_name']; ?>"><?php echo $project['project_name']; ?></option>
														<?php  
													} ?>
												</select>
											</div>
										</div>
										<center>
										<button class="btn btn-primary " type="submit">Submit</button>
										</center>
										</div>
									</div>
									</form>
								</div>
							</div>
						</div>	
				</div>
			</div>
		</div>
	</body>
</html>