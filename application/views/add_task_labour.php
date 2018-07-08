<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Add New Task Labour</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>task_labour/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li><a href='<?php echo base_url();?>task_labour/get_all?nav_menu=task_labour&token=<?php echo $_SESSION['token']; ?>'>Task Labour List</a></li>
									<li class='active'>Add New</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<?php if(isset($main_data) && $main_data != null) { 
										$action = site_url('task_labour/update?task_labour_id='.$_GET['task_labour_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('task_labour/insert?token=').$_SESSION['token']; 
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
										<button type='Submit' value='Submit' name='btnsubmit' href='<?php echo base_url(); ?>task_labour/get_all<?php echo '?nav_menu=task_labour&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Save' data-toggle='tooltip'> <i class='fa fa-save'></i> </button>
										<a href='<?php echo base_url(); ?>task_labour/get_all<?php echo '?nav_menu=task_labour&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Cancel' data-toggle='tooltip'> <i class='fa fa-reply'></i> </a>
									</div>
									<div class='grid-body'>
										<div class='form'>
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
										<div class='form-group col-md-12'>
											<label for='labour_group_id' class='col-lg-2'>Labour Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='labour_group_id' name='labour_group_id'>
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
											<label for='labour_id' class='col-lg-2'>Labour Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input type="hidden" id="labour_price" name="labour_price" value="<?php if(isset($main_data) && $main_data != null) { echo $main_data['price'];} ?>"/>
												<select class='form-control' id='labour_id' name='labour_id'>
													<option value=''>Select Labour Id</option>
													<?php foreach($labours as $labour) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['labour_id'] == $labour['labour_id']) { ?>
															<option  price="<?php echo $labour['labour_rate']; ?>" value="<?php echo $labour['labour_id']; ?>" selected='selected'><?php echo $labour['labour_name']; ?></option>
														<?php	} else { ?>
															<option price="<?php echo $labour['labour_rate']; ?>" value="<?php echo $labour['labour_id']; ?>"><?php echo $labour['labour_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='labour_quantity' class='col-lg-2'>Labour Quantity<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='labour_quantity' name='labour_quantity' type='number' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['labour_quantity'];} ?>'>
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
		url: '<?php echo site_url('task_labour/get_tasks?token=').$_SESSION['token']; ?>'+'&task_group_id='+task_group_id,
		dataType: 'json',
		success: function(data){
			console.log(data);
			if(data.success)
			{
				var html  = '<option value="none">Select Task Id</option>';
				for(x in data.data)
				{
					html+='<option value="'+data.data[x].task_id+'">'+data.data[x].task_name+'</option>';
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
$(document).on('change','#labour_group_id',function(){
	var labour_group_id=$(this).val();
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('task_labour/get_labours?token=').$_SESSION['token']; ?>'+'&labour_group_id='+labour_group_id,
		dataType: 'json',
		success: function(data){
			console.log(data);
			if(data.success)
			{
				var html  = '<option value="none">Select Labour Id</option>';
				for(x in data.data)
				{
					html+='<option price="'+data.data[x].labour_rate+'" value="'+data.data[x].labour_id+'">'+data.data[x].labour_name+'</option>';
				}
				console.log(html);
				$('#labour_id').html(html);
			}
			else
			{
				var html  = '<option price="0" t_price="0" value="none">No Labour to select</option>';
				$('#labour_id').html(html);
			}
		}
	});
});
$(document).on('change','#labour_id',function(){
	var price=$('option:selected', this).attr('price');
	$('#labour_price').val(price);
	
});
!function($) {
	"use strict";
	var FormValidator = function() {
		this.$signupForm = $("#signupForm");
	};
	FormValidator.prototype.init = function() {
		this.$signupForm.validate({
			rules: {
				task_id: "required",
				task_group_id: "required",
				labour_group_id: "required",
				labour_id: "required",
				labour_quantity: "required",
				task_labour_total_cost: "required",
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