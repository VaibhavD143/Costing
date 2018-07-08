<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Add New Project Sub Group</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>project_sub_group/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li><a href='<?php echo base_url();?>project_sub_group/get_all?nav_menu=project_sub_group&token=<?php echo $_SESSION['token']; ?>'>Project Sub Group List</a></li>
									<li class='active'>Add New</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<?php if(isset($main_data) && $main_data != null) { 
										$action = site_url('project_sub_group/update?project_sub_group_id='.$_GET['project_sub_group_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('project_sub_group/insert?token=').$_SESSION['token']; 
									} ?>
									<?php if(isset($_GET['type']) && $_GET['type'] == 'update') { ?>
										<div class='alert alert-danger text-left'>Updation Unuccessful.</div>
									<?php } else if(isset($_GET['type']) && $_GET['type'] == 'insert') { ?>
										<div class='alert alert-danger text-left'>Insertion Unuccessful.</div>
									<?php } ?>
									<form class='cmxform tasi-form' id='signupForm' method='post' action='<?php echo $action; ?>' enctype='multipart/form-data' novalidate='novalidate'>
									<div class='grid-title text-right'>
										<button type='Submit' value='Submit' name='btnsubmit' href='<?php echo base_url(); ?>project_sub_group/get_all<?php echo '?nav_menu=project_sub_group&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Save' data-toggle='tooltip'> <i class='fa fa-save'></i> </button>
										<a href='<?php echo base_url(); ?>project_sub_group/get_all<?php echo '?nav_menu=project_sub_group&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Cancel' data-toggle='tooltip'> <i class='fa fa-reply'></i> </a>
									</div>
									<div class='grid-body'>
										<div class='form'>
										<div class='form-group col-md-12'>
											<label for='project_group_id' class='col-lg-2'>Project Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='roles' name='project_group_id'>
													<option value=''>Select Project Group Id</option>
													<?php foreach($project_groups as $project_group) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['project_group_id'] == $project_group['project_group_id']) { ?>
															<option value="<?php echo $project_group['project_group_id']; ?>" selected='selected'><?php echo $project_group['project_group_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $project_group['project_group_id']; ?>"><?php echo $project_group['project_group_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										
										<div class='form-group col-md-12'>
											<label for='project_sub_group_name' class='col-lg-2'>Project Sub Group Name<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='project_sub_group_name' name='project_sub_group_name' type='text' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['project_sub_group_name'];} ?>'>
											</div>
										</div>
										
										</div>
									</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
<script>
!function($) {
	"use strict";
	var FormValidator = function() {
		this.$signupForm = $("#signupForm");
	};
	FormValidator.prototype.init = function() {
		this.$signupForm.validate({
			rules: {
				project_group_id: "required",
				project_id: "required",
				project_sub_group_name: "required",
				project_sub_group_cost: "required",
			},
		});
	},
	$.FormValidator = new FormValidator, $.FormValidator.Constructor = FormValidator
}(window.jQuery),
function($) {
	"use strict";
	$.FormValidator.init()
}(window.jQuery);
</script>
<?php include('admin_footer.php'); ?>