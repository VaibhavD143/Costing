<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Add New Labours</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>labours/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li><a href='<?php echo base_url();?>labours/get_all?nav_menu=labours&token=<?php echo $_SESSION['token']; ?>'>Labours List</a></li>
									<li class='active'>Add New</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<?php if(isset($main_data) && $main_data != null) { 
										$action = site_url('labours/update?labour_id='.$_GET['labour_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('labours/insert?token=').$_SESSION['token']; 
									} ?>
									<?php if(isset($_GET['type']) && $_GET['type'] == 'update') { ?>
										<div class='alert alert-danger text-left'>Updation Unuccessful.</div>
									<?php } else if(isset($_GET['type']) && $_GET['type'] == 'insert') { ?>
										<div class='alert alert-danger text-left'>Insertion Unuccessful.</div>
									<?php } ?>
									<form class='cmxform tasi-form' id='signupForm' method='post' action='<?php echo $action; ?>' enctype='multipart/form-data' novalidate='novalidate'>
									<div class='grid-title text-right'>
										<button type='Submit' value='Submit' name='btnsubmit' href='<?php echo base_url(); ?>labours/get_all<?php echo '?nav_menu=labours&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Save' data-toggle='tooltip'> <i class='fa fa-save'></i> </button>
										<a href='<?php echo base_url(); ?>labours/get_all<?php echo '?nav_menu=labours&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Cancel' data-toggle='tooltip'> <i class='fa fa-reply'></i> </a>
									</div>
									<div class='grid-body'>
										<div class='form'>
										<div class='form-group col-md-12'>
											<label for='labour_group_id' class='col-lg-2'>Labour Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='roles' name='labour_group_id'>
													<option value=''>Select Labour Group Id</option>
													<?php foreach($labour_groups as $labour_group) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['labour_group_id'] == $labour_group['labour_group_id']) { ?>
															<option value="<?php echo $labour_group['labour_group_id']; ?>" selected='selected'><?php echo $labour_group['labour_group_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $labour_group['labour_group_id']; ?>"><?php echo $labour_group['labour_group_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='unit_id' class='col-lg-2'>Unit Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='roles' name='unit_id'>
													<option value=''>Select Unit Id</option>
													<?php foreach($units as $unit) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['unit_id'] == $unit['unit_id']) { ?>
															<option value="<?php echo $unit['unit_id']; ?>" selected='selected'><?php echo $unit['unit_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='labour_name' class='col-lg-2'>Labour Name<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='labour_name' name='labour_name' type='text' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['labour_name'];} ?>'>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='labour_rate' class='col-lg-2'>Labour Rate<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='labour_rate' name='labour_rate' type='number' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['labour_rate'];} ?>'>
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
				labour_group_id: "required",
				unit_id: "required",
				labour_name: "required",
				labour_rate: "required",
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