<?php include('admin_header.php'); ?>
			<div class='content-page'>
				<div class='content'>
					<div class='container'>
						<div class='row'>
							<div class='col-sm-12'>
								<h4 class='pull-left page-title'>Project Sub Group List</h4>
								<ol class='breadcrumb pull-right'>
									<li><a href='<?php echo base_url();?>admin/dashboard?token=<?php echo $_SESSION['token']; ?>'>Dashboard</a></li>
									<li class='active'>Project Sub Group List</li>
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
										<a href='<?php echo base_url(); ?>project_sub_group/insert<?php echo '?nav_menu=project_sub_group&token='.$_SESSION['token']; ?>' class='btn btn-icon waves-effect waves-light btn-primary m-b-5' data-original-title='Add New' data-toggle='tooltip'> <i class='fa fa-plus'></i> </a>
										<a class='btn btn-icon waves-effect waves-light btn-danger m-b-5 delete_all' data-original-title='Delete' data-toggle='tooltip'> <i class='fa fa-trash-o'></i> </a>
									</div>
									<div class='grid-body '>
										<div class='col-md-12 col-sm-12 col-xs-12'>
											<table id='table' class='table table-hover table-condensed dataTable' style="width: 100% !important;">
												<thead>
													<tr>
														<th><input type='checkbox' name='check_all' class='check_all'></th>
														<th>Project Group Id</th>
														<th>Project Id</th>
														<th>Project Sub Group Name</th>
														<th>Project Sub Group Cost</th>
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
				'url': '<?php echo site_url('project_sub_group/get_all?token=').$_SESSION['token']; ?>',
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
				'targets': 5,
				'visible': true,
				'orderable': false,
				'render': function ( data, type, row ) {
					return '<a project-sub-group-id="'+row[0]+'" class="cursor view_detail" data-toggle="modal" data-target="#quickview" onclick="return view_detail('+row[0]+')"><i class="fa fa-eye"></i></a>';
				},
			},
			{
				'targets': 6,
				'visible': true,
				'orderable': false,
				'render': function ( data, type, row ) {
					return '<a project-sub-group-id="'+row[0]+'" href="javascript:void(0);" class="edit"><i class="fa fa-pencil"></i></a>';
				},
			},
			{
				'targets': 7,
				'visible': true,
				'orderable': false,
				'render': function ( data, type, row ) {
					return '<a project-sub-group-id="'+row[0]+'" href="javascript:void(0);" class="single_delete"><i class="fa fa-trash"></i></a>';
				},
			}],
		});
	});
	/*------------------------------------------On Click Edit--------------------------------------*/
		$(document).on('click', '.edit', function(){ 
			var project_sub_group_id = $(this).attr('project-sub-group-id');
			window.location.href='<?php echo site_url('project_sub_group/update?token=').$_SESSION['token']; ?>'+'&project_sub_group_id='+project_sub_group_id; 
		});
	/*------------------------------------------On Click Single Delete Box--------------------------------------*/
		$(document).on('click', '.single_delete', function(){
			var project_sub_group_id = $(this).attr('project-sub-group-id');
			if (confirm('Are you sure, you want to delete?')) {
				$('.dataTables_processing').show(); 
				$.ajax({
					type: 'GET',
					url: '<?php echo site_url('project_sub_group/delete?token=').$_SESSION['token']; ?>'+'&project_sub_group_id='+project_sub_group_id,
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
			var project_sub_group_id = [];
			var i = 0;
			$('.check_delete').each(function() {
				if(this.checked == true) {
					project_sub_group_id[i] = $(this).val();
					i++;
				}
			});
			if(project_sub_group_id.length == 0) {
				alert('Please select an item to delete.');
				return;
			}
			if (confirm('Are you sure, you want to delete?')) {
				$('.dataTables_processing').show();
					$.ajax({
						type: 'GET',
						url: '<?php echo site_url('project_sub_group/delete_all?token=').$_SESSION['token']; ?>'+'&project_sub_group_id='+project_sub_group_id,
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
		function view_detail(project_sub_group_id) {
				$.ajax({
					type: 'GET',
					url: '<?php echo site_url('project_sub_group/get_by_id?token=').$_SESSION['token']; ?>'+'&project_sub_group_id='+project_sub_group_id,
					dataType: 'json',
					success: function(data){
						$('.details').html('');
						var optionhtml = '';
						if(data.success) {
						optionhtml = '<div class="text-center">'
							optionhtml += '<table class="table table-striped table-bordered">';
								optionhtml += '<thead>';
									optionhtml += '<tr>';
										optionhtml += '<th>Project Group Id</th>';
										optionhtml += '<td>'+data.data[0].project_group_id+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Project Id</th>';
										optionhtml += '<td>'+data.data[0].project_id+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Project Sub Group Name</th>';
										optionhtml += '<td>'+data.data[0].project_sub_group_name+'</td>';
									optionhtml += '<tr>';
										optionhtml += '<th>Project Sub Group Cost</th>';
										optionhtml += '<td>'+data.data[0].project_sub_group_cost+'</td>';
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