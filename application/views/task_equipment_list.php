<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Task Equipment List</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>admin/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li class='active'>Task Equipment List</li>
								</ol>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<div class='grid simple'>
									<div class='grid-title text-right'>
									<?php if(isset($_GET['type']) && $_GET['type'] == 'update') { ?>
										<div class='alert alert-success text-left'>Updation Successful.</div>
									<?php } else if(isset($_GET['type']) && $_GET['type'] == 'insert') { ?>
										<div class='alert alert-success text-left'>Insertion Successful.</div>
									<?php } ?>
										<a href='<?php echo base_url(); ?>task_equipment/insert<?php echo '?nav_menu=task_equipment&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' data-original-title='Add New' data-toggle='tooltip'> <i class='fa fa-plus'></i> </a>
										<a class='btn btn-icon waves-effect waves-light btn-danger m-b-5 delete_all' data-original-title='Delete' data-toggle='tooltip'> <i class='fa fa-trash-o'></i> </a>
									</div>
									<div class='grid-body '>
										<div class='col-md-12 col-sm-12 col-xs-12'>
											<table id='table' class='table table-hover table-condensed dataTable' style="width: 100% !important;">
												<thead>
													<tr>
														<th><input type='checkbox' name='check_all' class='check_all'></th>
														<th>Task Id</th>
														<th>Task Group Id</th>
														<th>Equipment Group Id</th>
														<th>Equipment Id</th>
														<th>Equipment Quantity</th>
														<th></th>
														<th></th>
														<th></th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<div id='quickview' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
			<div class='modal-header'>
				<h3>Details</h3>
			</div>
			<div class='modal-body details'>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>
	$(document).ready(function() {
		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'ajax': {
				'url': '<?php echo site_url('task_equipment/get_all?token=').$_SESSION['token']; ?>',
				'type': 'POST'
			},
			'columnDefs': [{
				'targets': 0,
				'visible': true,
				'orderable': false,
				'render': function ( data, type, row ) {
					return '<input type="checkbox" class="check_delete" name="check_delete" value="'+row[0]+'">';
				},
			},
			{
				'targets': 6,
				'visible': true,
				'orderable': false,
				'render': function ( data, type, row ) {
					return '<a task-equipment-id="'+row[0]+'" class="cursor view_detail" data-toggle="modal" data-target="#quickview" onclick="return view_detail('+row[0]+')"><i class="fa fa-eye"></i></a>';
				},
			},
			{
				'targets': 7,
				'visible': true,
				'orderable': false,
				'render': function ( data, type, row ) {
					return '<a task-equipment-id="'+row[0]+'" href="javascript:void(0);" class="edit"><i class="fa fa-pencil"></i></a>';
				},
			},
			{
				'targets': 8,
				'visible': true,
				'orderable': false,
				'render': function ( data, type, row ) {
					return '<a task-equipment-id="'+row[0]+'" href="javascript:void(0);" class="single_delete"><i class="fa fa-trash"></i></a>';
				},
			}],
		});
	});
	/*------------------------------------------On Click Edit--------------------------------------*/
		$(document).on('click', '.edit', function(){ 
			var task_equipment_id = $(this).attr('task-equipment-id');
			window.location.href='<?php echo site_url('task_equipment/update?token=').$_SESSION['token']; ?>'+'&task_equipment_id='+task_equipment_id; 
		});
	/*------------------------------------------On Click Single Delete Box--------------------------------------*/
		$(document).on('click', '.single_delete', function(){
			var task_equipment_id = $(this).attr('task-equipment-id');
			if (confirm('Are you sure, you want to delete?')) {
				$('.dataTables_processing').show(); 
				$.ajax({
					type: 'GET',
					url: '<?php echo site_url('task_equipment/delete?token=').$_SESSION['token']; ?>'+'&task_equipment_id='+task_equipment_id,
					dataType: 'json',
					success: function(data){
						console.log(JSON.stringify(data));
						if(data.success) {
							alert('Deleted successfully.');
							table.draw();
						} else {
							alert(data.message);
						}
						$('.dataTables_processing').hide();
					}
				});
			}
		});
	/*---------------------------------On Click Delete All----------------------------------------*/
		$(document).on('click', '.delete_all', function(){ 
			var task_equipment_id = [];
			var i = 0;
			$('.check_delete').each(function() {
				if(this.checked == true) {
					task_equipment_id[i] = $(this).val();
					i++;
				}
			});
			if(task_equipment_id.length == 0) {
				alert('Please select an item to delete.');
				return;
			}
			if (confirm('Are you sure, you want to delete?')) {
				$('.dataTables_processing').show();
					$.ajax({
						type: 'GET',
						url: '<?php echo site_url('task_equipment/delete_all?token=').$_SESSION['token']; ?>'+'&task_equipment_id='+task_equipment_id,
						dataType: 'json',
						success: function(data){
							console.log(JSON.stringify(data));
							if(data.success) {
								alert('Deleted successfully.');
								table.draw(); 
							} else {
							alert(data.message);
						}
						$('.dataTables_processing').hide();
					}
				});
			}
		});
	/*---------------------------------------------On click check all------------------------------------------*/
		$('.check_all').click(function(event) {
			if(this.checked) {
				$(':checkbox').each(function() {
					this.checked = true;
				});
			} else {
				$(':checkbox').each(function() {
					this.checked = false;
				});
			}
		});
	/*------------------------------------------On Click View Detail--------------------------------------*/
		function view_detail(task_equipment_id) {
				$.ajax({
					type: 'GET',
					url: '<?php echo site_url('task_equipment/get_by_id?token=').$_SESSION['token']; ?>'+'&task_equipment_id='+task_equipment_id,
					dataType: 'json',
					success: function(data){
						$('.details').html('');
						var optionhtml = '';
						if(data.success) {
						optionhtml = '<div class="text-center">'
							optionhtml += '<table class="table table-striped table-bordered">';
								optionhtml += '<thead>';
									optionhtml += '<tr>';
										optionhtml += '<th>Task Id</th>';
										optionhtml += '<td>'+data.data[0].task_id+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Task Group Id</th>';
										optionhtml += '<td>'+data.data[0].task_group_id+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Equipment Group Id</th>';
										optionhtml += '<td>'+data.data[0].equipment_group_id+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Equipment Id</th>';
										optionhtml += '<td>'+data.data[0].equipment_id+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Equipment Quantity</th>';
										optionhtml += '<td>'+data.data[0].equipment_quantity+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Task Equipment Cost</th>';
										optionhtml += '<td>'+data.data[0].task_equipment_cost+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Task Equipment Run Cost</th>';
										optionhtml += '<td>'+data.data[0].task_equipment_run_cost+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Task Equipment Total Cost</th>';
										optionhtml += '<td>'+data.data[0].task_equipment_total_cost+'</td>';
								optionhtml += '</thead>';
							optionhtml += '</table>';
						optionhtml += '</div>';
							$('.details').html(optionhtml);
						}
					}
				});
		};
</script>
<?php include('admin_footer.php'); ?>