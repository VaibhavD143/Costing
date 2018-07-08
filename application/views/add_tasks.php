<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Add New Tasks</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>tasks/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li><a href='<?php echo base_url();?>tasks/get_all?nav_menu=tasks&token=<?php echo $_SESSION['token']; ?>'>Tasks List</a></li>
									<li class='active'>Add New</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<?php if(isset($main_data) && $main_data != null) { 
										$action = site_url('tasks/update?task_id='.$_GET['task_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('tasks/insert?token=').$_SESSION['token']; 
									} ?>
									<?php if(isset($_GET['type']) && $_GET['type'] == 'update') { ?>
										<div class='alert alert-danger text-left'>Updation Unuccessful.</div>
									<?php } else if(isset($_GET['type']) && $_GET['type'] == 'insert') { ?>
										<div class='alert alert-danger text-left'>Insertion Unuccessful.</div>
									<?php } ?>
									<form class='cmxform tasi-form' id='signupForm' method='post' action='<?php echo $action; ?>' enctype='multipart/form-data' novalidate='novalidate'>
									<div class='grid-title text-right'>
										<button type='Submit' value='Submit' name='btnsubmit' href='<?php echo base_url(); ?>tasks/get_all<?php echo '?nav_menu=tasks&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Save' data-toggle='tooltip'> <i class='fa fa-save'></i> </button>
										<a href='<?php echo base_url(); ?>tasks/get_all<?php echo '?nav_menu=tasks&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Cancel' data-toggle='tooltip'> <i class='fa fa-reply'></i> </a>
									</div>
									<div class='grid-body'>
										<div class='form'>
										<div class='form-group col-md-12'>
											<label for='task_group_id' class='col-lg-2'>Task Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='roles' name='task_group_id'>
													<option value=''>Select Task Group Id</option>
													<?php foreach($task_groups as $task_group) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['task_group_id'] == $task_group['task_group_id']) { ?>
															<option value="<?php echo $task_group['task_group_id']; ?>" selected='selected'><?php echo $task_group['task_group_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $task_group['task_group_id']; ?>"><?php echo $task_group['task_group_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='task_name' class='col-lg-2'>Task Name<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='task_name' name='task_name' type='text' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['task_name'];} ?>'>
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
				task_group_id: "required",
				task_name: "required",
				material_cost: "required",
				labour_cost: "required",
				equipment_cost: "required",
				total_cost: "required",
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