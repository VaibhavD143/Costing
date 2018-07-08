<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Add New Materials</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>materials/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li><a href='<?php echo base_url();?>materials/get_all?nav_menu=materials&token=<?php echo $_SESSION['token']; ?>'>Materials List</a></li>
									<li class='active'>Add New</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<?php if(isset($main_data) && $main_data != null) { 
										$action = site_url('materials/update?material_id='.$_GET['material_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('materials/insert?token=').$_SESSION['token']; 
									} ?>
									<?php if(isset($_GET['type']) && $_GET['type'] == 'update') { ?>
										<div class='alert alert-danger text-left'>Updation Unuccessful.</div>
									<?php } else if(isset($_GET['type']) && $_GET['type'] == 'insert') { ?>
										<div class='alert alert-danger text-left'>Insertion Unuccessful.</div>
									<?php }?>
									<form class='cmxform tasi-form' id='signupForm' method='post' action='<?php echo $action; ?>' enctype='multipart/form-data' novalidate='novalidate'>
									<div class='grid-title text-right'>
										<button type='Submit' value='Submit' name='btnsubmit' href='<?php echo base_url(); ?>materials/get_all<?php echo '?nav_menu=materials&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Save' data-toggle='tooltip'> <i class='fa fa-save'></i> </button>
										<a href='<?php echo base_url(); ?>materials/get_all<?php echo '?nav_menu=materials&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Cancel' data-toggle='tooltip'> <i class='fa fa-reply'></i> </a>
									</div>
									<div class='grid-body'>
										<div class='form'>
										<div class='form-group col-md-12'>
											<label for='material_group_id' class='col-lg-2'>Material Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='roles' name='material_group_id'>
													<option value=''>Select Material Group Id</option>
													<?php foreach($material_groups as $material_group) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['material_group_id'] == $material_group['material_group_id']) { ?>
															<option value="<?php echo $material_group['material_group_id']; ?>" selected='selected'><?php echo $material_group['material_group_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $material_group['material_group_id']; ?>"><?php echo $material_group['material_group_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='category_id' class='col-lg-2'>Category Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='roles' name='category_id'>
													<option value=''>Select Category Id</option>
													<?php foreach($categories as $category) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['category_id'] == $category['category_id']) { ?>
															<option value="<?php echo $category['category_id']; ?>" selected='selected'><?php echo $category['category_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
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
											<label for='material_name' class='col-lg-2'>Material Name<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='material_name' name='material_name' type='text' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['material_name'];} ?>'>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='material_rate' class='col-lg-2'>Material Rate<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='material_rate' name='material_rate' type='number' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['material_rate'];} ?>'>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='material_standard_credit' class='col-lg-2'>Material Standard Credit<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='material_standard_credit' name='material_standard_credit' type='text' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['material_standard_credit'];} ?>'>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='material_transport_cost' class='col-lg-2'>Material Transport Cost<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='material_transport_cost' name='material_transport_cost' type='number' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['material_transport_cost'];} ?>'>
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
				material_group_id: "required",
				category_id: "required",
				unit_id: "required",
				material_name: "required",
				material_rate: "required",
				material_standard_credit: "required",
				material_transport_cost: "required",
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