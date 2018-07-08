<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Add New Project Task</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>project_task/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li><a href='<?php echo base_url();?>project_task/get_all?nav_menu=project_task&token=<?php echo $_SESSION['token']; ?>'>Project Task List</a></li>
									<li class='active'>Add New</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<?php if(isset($main_data) && $main_data != null) { 
										$action = site_url('project_task/update?project_task_id='.$_GET['project_task_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('project_task/insert?token=').$_SESSION['token']; 
									} ?>
									<?php if(isset($_GET['type']) && $_GET['type'] == 'update') { ?>
												<div class='alert alert-danger text-left'>Updation Unuccessful.</div>
										<?php
											} elseif(isset($_GET['type']) && $_GET['type'] == 'insert' && $_GET['success'] =="true") {?>
											
												<div class='alert alert-success text-left'>Insertion Successful.</div>
											<?php
									}elseif(isset($_GET['type']) && $_GET['type'] == 'insert'){
										?>
											<div class='alert alert-danger text-left'>Insertion Unuccessful.</div>
										<?php
									} ?>
									<form class='cmxform tasi-form' id='signupForm' method='post' action='<?php echo $action; ?>' enctype='multipart/form-data' novalidate='novalidate'>
									<div class='grid-title text-right'>
										<button type='Submit' value='Submit' name='btnsubmit' href='<?php echo base_url(); ?>project_task/get_all<?php echo '?nav_menu=project_task&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Save' data-toggle='tooltip'> <i class='fa fa-save'></i> </button>
										<a href='<?php echo base_url(); ?>project_task/get_all<?php echo '?nav_menu=project_task&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Cancel' data-toggle='tooltip'> <i class='fa fa-reply'></i> </a>
									</div>
									<div class='grid-body'>
										<div class='form'>
										<div class='form-group col-md-12'>
											<label for='project_group_id' class='col-lg-2'>Project Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='project_group_id' name='project_group_id'>
													<option value=''>Select Project Group Id</option>
													<?php foreach($project_groups as $project_group) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['project_group_id'] == $project_group['project_group_id'] or (isset($_GET['project_group_id']) and $_GET['project_group_id'] != null and $_GET['project_group_id'] == $project_group['project_group_id'])) { ?>
															<option value="<?php echo $project_group['project_group_id']; ?>" selected='selected'><?php echo $project_group['project_group_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $project_group['project_group_id']; ?>"><?php echo $project_group['project_group_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='project_sub_group_id' class='col-lg-2'>Project Sub Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='project_sub_group_id' name='project_sub_group_id'>
													<option value=''>Select Project Sub Group Id</option>
													<?php foreach($project_sub_groups as $project_sub_group) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['project_sub_group_id'] == $project_sub_group['project_sub_group_id'] or (isset($_GET['project_sub_group_id']) and $_GET['project_sub_group_id'] != null and $_GET['project_sub_group_id'] == $project_sub_group['project_sub_group_id'])) { ?>
															<option value="<?php echo $project_sub_group['project_sub_group_id']; ?>" selected='selected'><?php echo $project_sub_group['project_sub_group_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $project_sub_group['project_sub_group_id']; ?>"><?php echo $project_sub_group['project_sub_group_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='task_group_id' class='col-lg-2'>Task Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='task_group_id' name='task_group_id'>
													<option value=''>Select Task Group Id</option>
													<?php foreach($task_groups as $task_group) { ?>
														<?php if((isset($main_data) && $main_data != null && $main_data['task_group_id'] == $task_group['task_group_id']) or (isset($_GET['task_group_id']) and $_GET['task_group_id'] != null and $_GET['task_group_id'] == $task_group['task_group_id'])) { ?>
															<option value="<?php echo $task_group['task_group_id']; ?>" selected='selected'><?php echo $task_group['task_group_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $task_group['task_group_id']; ?>"><?php echo $task_group['task_group_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='task_id' class='col-lg-2'>Task Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input type="hidden" name="total_cost" id="total_cost" value="<?php if(isset($main_data) && $main_data != null) { echo $main_data['total_cost'];} ?>">
												<select class='form-control' id='task_id' name='task_id'>
													<option value=''>Select Task Id</option>
													<?php foreach($tasks as $task) { ?>
														<?php if((isset($main_data) && $main_data != null && $main_data['task_id'] == $task['task_id']) or (isset($_GET['task_id']) and $_GET['task_id'] != null and $_GET['task_id'] == $task['task_id'])) { ?>
															<option value="<?php echo $task['task_id']; ?>" selected='selected'><?php echo $task['task_name']; ?></option>
														<?php	} else { ?>
															<option value="<?php echo $task['task_id']; ?>"><?php echo $task['task_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<input class='form-control' id='task_name' name='task_name' type='hidden' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['task_name'];} ?>'>
										<div class='form-group col-md-12'>
											<label for='task_area' class='col-lg-2'>Task Area<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='task_area' name='task_area' type='number' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['task_area'];} ?>'>
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
$(document).on('change','#task_group_id',function(){
	var task_group_id=$(this).val();
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('project_task/get_tasks?token=').$_SESSION['token']; ?>'+'&task_group_id='+task_group_id,
		dataType: 'json',
		success: function(data){
			console.log(data.data[0]);
			if(data.success)
			{
				var html  = '<option value="none">Select Task Id</option>';
				for(x in data.data)
				{
					html+='<option total_cost="'+data.data[x].total_cost+'" value="'+data.data[x].task_id+'">'+data.data[x].task_name+'</option>';
				}
				console.log(html);
				$('#task_id').html(html);
			}
			else
			{
				var html  = '<option value="none">No Task to select</option>';
				$('#task_id').html(html);
			}
		}
	});
});
$(document).on('change','#task_id',function(){
	// alert($("#task_id option:selected").html());
	$('#task_name').val($("#task_id option:selected").html());

	var total_cost=$('option:selected', this).attr('total_cost');
	
	$('#total_cost').val(total_cost);
	
	
});
$(document).on('change','#project_group_id',function(){
	var project_group_id=$(this).val();
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('project_task/get_sub_groups?token=').$_SESSION['token']; ?>'+'&project_group_id='+project_group_id,
		dataType: 'json',
		success: function(data){
			console.log(data.data[0]);
			if(data.success)
			{
				var html  = '<option value="none">Select Project Sub Group Id</option>';
				for(x in data.data)
				{
					html+='<option  value="'+data.data[x].project_sub_group_id+'">'+data.data[x].project_sub_group_name+'</option>';
				}
				console.log(html);
				$('#project_sub_group_id').html(html);
			}
			else
			{
				var html  = '<option  value="none">No Project Sub Group to select</option>';
				$('#project_sub_group_id').html(html);
			}
		}
	});
});
!function($) {
	"use strict";
	var FormValidator = function() {
		this.$signupForm = $("#signupForm");
	};
	FormValidator.prototype.init = function() {
		this.$signupForm.validate({
			rules: {
				project_sub_group_id: "required",
				project_group_id: "required",
				project_id: "required",
				task_id: "required",
				task_name: "required",
				task_area: "required",
				task_cost: "required",
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