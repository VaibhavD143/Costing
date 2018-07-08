<?php include('/../admin_header.php'); ?>                    
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="pull-left page-title">Add New Admin</h4>
                                <ol class="breadcrumb pull-right">
                                    <li><a href="<?php echo base_url();?>admin/dashboard?token=<?php echo $_SESSION['token']; ?>">Dashboard</a></li>
									<li><a href="<?php echo base_url();?>admin/admin_list?token=<?php echo $_SESSION['token']; ?>">Admin List</a></li>
                                    <li class="active">Add New</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Form-validation -->
                        <div class="row">
                        
                            <div class="col-sm-12">
                                <div class="panel panel-default">
									<?php if(isset($main_admin) && $main_admin != null) { 
										$action = site_url('admin/update?admin_id='.$_GET['admin_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('admin/insert?token=').$_SESSION['token']; 
									} ?>
									<form class="cmxform form-horizontal tasi-form" id="signupForm" method="post" action="<?php echo $action; ?>" novalidate="novalidate">
										<div class="panel-heading text-right">
											<button type="Submit" value="Submit" name="btnsubmit" class="btn btn-icon waves-effect waves-light btn-primary m-b-5" title="" data-original-title="Save" data-toggle="tooltip"> <i class="fa fa-save"></i> </button>
											<a href="<?php echo base_url(); ?>admin/admin_list<?php echo "?token=".$_SESSION['token']; ?>" class="btn btn-icon waves-effect waves-light btn-primary m-b-5" title="" data-original-title="Cancel" data-toggle="tooltip"> <i class="fa fa-reply"></i> </a>
										</div>
										<div class="panel-body">
											<div class="form">
												<div class="form-group">
													<label for="txtfirstname" class="control-label col-lg-2">Firstname *</label>
													<div class="col-lg-10">
														<input class="form-control" id="txtfirstname" name="txtfirstname" type="text" value="<?php if(isset($main_admin) && $main_admin != null) { echo $main_admin['firstname'];} ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="txtlastname" class="control-label col-lg-2">Lastname  *</label>
													<div class="col-lg-10">
														<input class="form-control" id="txtlastname" name="txtlastname" type="text" value="<?php if(isset($main_admin) && $main_admin != null) { echo $main_admin['lastname'];} ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="txtemail" class="control-label col-lg-2">Email *</label>
													<div class="col-lg-10">
														<input class="form-control" id="txtemail" name="txtemail" type="email" value="<?php if(isset($main_admin) && $main_admin != null) { echo $main_admin['admin_email'];} ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="txtmobile" class="control-label col-lg-2">Mobile No. *</label>
													<div class="col-lg-10">
														<input class="form-control" id="txtmobile" name="txtmobile" type="number" value="<?php if(isset($main_admin) && $main_admin != null) { echo $main_admin['admin_contact_no'];} ?>">
													</div>
												</div>
												<?php /*<div class="form-group">
													<label for="txtpassword" class="control-label col-lg-2">Password *</label>
													<div class="col-lg-10">
														<input class="form-control" id="txtpassword" name="txtpassword" type="password" value="<?php if(isset($main_admin) && $main_admin != null) { echo $main_admin['admin_pass'];} ?>">
													</div>
												</div>
												<div class="form-group">
													<label for="confirm_password" class="control-label col-lg-2">Confirm Password *</label>
													<div class="col-lg-10">
														<input class="form-control" id="confirm_password" name="confirm_password" type="password" value="<?php if(isset($main_admin) && $main_admin != null) { echo $main_admin['admin_pass'];} ?>">
													</div>
												</div> */?>
												<div class="form-group">
													<label class="col-sm-2 control-label">Parent Admin</label>
													<div class="col-sm-10">
														<select class="form-control" id="parent_admin" name="parent_admin">
															<option value="">Select Parent Admin</option>
															<?php foreach($admins as $admin) { ?>
																<?php if(isset($main_admin) && $main_admin != null && $main_admin['parent_id'] == $admin['admin_id']) { ?>
																	<option value="<?php echo $admin['admin_id']; ?>" selected="selected"><?php echo $admin['admin_name']; ?></option>
															<?php	} else { ?>
																	<option value="<?php echo $admin['admin_id']; ?>"><?php echo $admin['admin_name']; ?></option>
															<?php } 
															} ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label">Role</label>
													<div class="col-sm-10">
														<select class="form-control" id="role" name="role">
															<option value="">Select Role</option>
															<?php foreach($roles as $role) { ?>
																<?php if(isset($main_admin) && $main_admin != null && $main_admin['role_id'] == $role['role_id']) { ?>
																	<option value="<?php echo $role['role_id']; ?>" selected="selected"><?php echo $role['role_name']; ?></option>
																<?php	} else { ?>
																	<option value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></option>
															<?php } 
															} ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label">Country</label>
													<div class="col-sm-10">
														<select class="form-control" id="country" name="country">
															<option value="">Select Country</option>
															<?php foreach($countries as $country) { ?>
																<?php if(isset($main_admin) && $main_admin != null && $main_admin['country_id'] == $country['country_id']) { ?>
																	<option value="<?php echo $country['country_id']; ?>"  selected="selected"><?php echo $country['country_name']; ?></option>
																<?php	} else { ?>
																	<option value="<?php echo $country['country_id']; ?>"><?php echo $country['country_name']; ?></option>
															<?php } 
															} ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label">State</label>
													<div class="col-sm-10">
														<select class="form-control" id="state" name="state">
															<option value="">Select State</option>
															<?php foreach($states as $state) { ?>
																<?php if(isset($main_admin) && $main_admin != null && $main_admin['state_id'] == $state['state_id']) { ?>
																	<option value="<?php echo $state['state_id']; ?>"  selected="selected"><?php echo $state['state_name']; ?></option>
																<?php	} else { ?>
																	<option value="<?php echo $state['state_id']; ?>"><?php echo $state['state_name']; ?></option>
															<?php } 
															} ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label">City</label>
													<div class="col-sm-10">
														<select class="form-control" id="city" name="city">
															<option value="">Select City</option>
															<?php foreach($cities as $city) { ?>
																<?php if(isset($main_admin) && $main_admin != null && $main_admin['city_id'] == $city['city_id']) { ?>
																	<option value="<?php echo $city['city_id']; ?>"  selected="selected"><?php echo $city['city_name']; ?></option>
																<?php	} else { ?>
																	<option value="<?php echo $city['city_id']; ?>"><?php echo $city['city_name']; ?></option>
															<?php }
															} ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label">Active</label>
													<div class="col-sm-10">
														<select class="form-control" name="is_active">
															<option value="0" <?php if(isset($main_admin) && $main_admin != null && $main_admin['is_active'] == 0) { echo "selected='selected'"; } ?>>Disable</option>
															<option value="1" <?php if(isset($main_admin) && $main_admin != null && $main_admin['is_active'] == 1) { echo "selected='selected'"; } ?>>Enable</option>
														</select>
													</div>
												</div>
											</div> <!-- .form -->
										</div> <!-- panel-body -->
									</form>
                                </div> <!-- panel -->
                            </div> <!-- col -->
                        </div> <!-- End row -->



            </div> <!-- container -->
                               
                </div> <!-- content -->

<script type="text/javascript" src="<?php echo base_url();?>admin_design/assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script>
!function($) {
    "use strict";

    var FormValidator = function() {
        this.$signupForm = $("#signupForm");
    };

    //init
    FormValidator.prototype.init = function() {
       

        // validate the comment form when it is submitted
        // validate signup form on keyup and submit
        this.$signupForm.validate({
            rules: {
                txtfirstname: "required",
                txtlastname: "required",
                txtemail: {
                    required: true,
                    email: true
                },
				txtmobile: "required",
                txtpassword: "required",
                confirm_password: {
                    required: true,
                    equalTo: "#txtpassword"
                },
            },
            messages: {
                txtfirstname: "Please enter your firstname",
                txtlastname: "Please enter your lastname",
				txtemail: "Please enter a valid email address",
				confirm_password: {
					equalTo: "Please enter the same password as above"
				},
            },
			
        });
    },
    //init
    $.FormValidator = new FormValidator, $.FormValidator.Constructor = FormValidator
}(window.jQuery),


//initializing 
function($) {
    "use strict";
    $.FormValidator.init()
}(window.jQuery);
</script>
<script type="text/javascript">
$('#country').change(function(){
	
	var country_id = $('#country').val();
    $.ajax({
        type: "GET",
        url: "<?php echo site_url('state_service/get_by_country_id?token=').$_SESSION['token']; ?>&country_id="+country_id, 
        data:country_id,
        dataType:"json",
        success: function(states){
			$("#state").html('');
			if(states.success) {
				var optionhtml = '<option value="">Select State</option>';
				$.each(states.data,function(i){
				   optionhtml += '<option value="' + states.data[i].state_id + '">' + states.data[i].state_name + '</option>';	
				});
				$("#state").append(optionhtml);
			}
        },
    });
});

$('#state').change(function(){
	
	var state_id = $('#state').val();
    $.ajax({
        type: "GET",
        url: "<?php echo site_url('city_service/get_by_state_id?token=').$_SESSION['token']; ?>&state_id="+state_id, 
        data:state_id,
        dataType:"json",
        success: function(cities){
			$("#city").html('');
			if(cities.success) {
				var optionhtml = '<option value="">Select City</option>';
				$.each(cities.data,function(i){
				   optionhtml += '<option value="' + cities.data[i].city_id + '">' + cities.data[i].city_name + '</option>';	
				});
				$("#city").append(optionhtml);
			}
        },
    });
});
</script>
<?php include('/../admin_footer.php'); ?>