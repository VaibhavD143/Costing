<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Add New Task Material</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>task_material/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li><a href='<?php echo base_url();?>task_material/get_all?nav_menu=task_material&token=<?php echo $_SESSION['token']; ?>'>Task Material List</a></li>
									<li class='active'>Add New</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<?php if(isset($main_data) && $main_data != null) { 
										$action = site_url('task_material/update?task_material_id='.$_GET['task_material_id'].'&token=').$_SESSION['token']; 
									} else {
										$action = site_url('task_material/insert?token=').$_SESSION['token']; 
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
										<button type='Submit' value='Submit' name='btnsubmit' href='<?php echo base_url(); ?>task_material/get_all<?php echo '?nav_menu=task_material&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Save' data-toggle='tooltip'> <i class='fa fa-save'></i> </button>
										<a href='<?php echo base_url(); ?>task_material/get_all<?php echo '?nav_menu=task_material&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' title='' data-original-title='Cancel' data-toggle='tooltip'> <i class='fa fa-reply'></i> </a>
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
											<label for='material_group_id' class='col-lg-2'>Material Group Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<select class='form-control' id='material_group_id' name='material_group_id'>
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
											<label for='material_id' class='col-lg-2'>Material Id<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input type="hidden" id="material_price" name="material_price" value="<?php if(isset($main_data) && $main_data != null) { echo $main_data['price'];} ?>"/>
												<input type="hidden" id="material_t_price" name="material_t_price" value="<?php if(isset($main_data) && $main_data != null) { echo $main_data['t_price'];} ?>"/>
												<select class='form-control' id='material_id' name='material_id'>
													<option value=''>Select material Id</option>
													<?php foreach($materials as $material) { ?>
														<?php if(isset($main_data) && $main_data != null && $main_data['material_id'] == $material['material_id']) { ?>
															<option price="<?php echo $material['material_rate']; ?>" t_price="<?php echo $material['material_transport_cost']; ?>" value="<?php echo $material['material_id']; ?>" selected='selected'><?php echo $material['material_name']; ?></option>
														<?php	} else { ?>
															<option price="<?php echo $material['material_rate']; ?>" t_price="<?php echo $material['material_transport_cost']; ?>" value="<?php echo $material['material_id']; ?>"><?php echo $material['material_name']; ?></option>
														<?php } 
													} ?>
												</select>
											</div>
										</div>
										<div class='form-group col-md-12'>
											<label for='material_quantity' class='col-lg-2'>Material Quantity<span class='danger'>*</span></label>
											<div class='col-lg-10'>
												<input class='form-control' id='material_quantity' name='material_quantity' type='number' value='<?php if(isset($main_data) && $main_data != null) { echo $main_data['material_quantity'];} ?>'>
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
$(document).ready(function(){
	
});
$(document).on('change','#task_group_id',function(){
	var task_group_id=$(this).val();
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('task_material/get_tasks?token=').$_SESSION['token']; ?>'+'&task_group_id='+task_group_id,
		dataType: 'json',
		success: function(data){
			console.log(data.data[0]);
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
$(document).on('change','#material_group_id',function(){
	var material_group_id=$(this).val();
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('task_material/get_materials?token=').$_SESSION['token']; ?>'+'&material_group_id='+material_group_id,
		dataType: 'json',
		success: function(data){
			console.log(data.data[0]);
			if(data.success)
			{
				var html  = '<option value="none">Select Material Id</option>';
				for(x in data.data)
				{
					html+='<option price="'+data.data[x].material_rate+'" t_price="'+data.data[x].material_transport_cost+'" value="'+data.data[x].material_id+'">'+data.data[x].material_name+'</option>';
				}
				console.log(html);
				$('#material_id').html(html);
			}
			else
			{
				var html  = '<option price="0" t_price="0" value="none">No Material to select</option>';
				$('#material_id').html(html);
			}
		}
	});
});
$(document).on('change','#material_id',function(){
	var price=$('option:selected', this).attr('price');
	var t_price=$('option:selected', this).attr('t_price');
	$('#material_price').val(price);
	$('#material_t_price').val(t_price);
	
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
				material_group_id: "required",
				material_id: "required",
				material_quantity: "required",
				task_material_cost: "required",
				task_transport_cost: "required",
				task_material_total_cost: "required",
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