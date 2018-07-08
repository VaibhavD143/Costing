<?php include('/../admin_header.php'); ?>
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="pull-left page-title">Admin List</h4>
                                <ol class="breadcrumb pull-right">
                                    <li><a href="<?php echo base_url();?>admin/dashboard?token=<?php echo $_SESSION['token']; ?>">Dashboard</a></li>
                                    <li class="active">Admin List</li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading text-right">
										<a href="<?php echo base_url(); ?>admin/insert<?php echo "?token=".$_SESSION['token']; ?>" class="btn btn-icon waves-effect waves-light btn-primary m-b-5" data-original-title="Add New" data-toggle="tooltip"> <i class="fa fa-plus"></i> </a>
										<a class="btn btn-icon waves-effect waves-light btn-danger m-b-5 delete_all" data-original-title="Delete" data-toggle="tooltip"> <i class="fa fa-trash-o"></i> </a>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <table id="table" class="table table-striped">
                                                    <thead>
                                                        <tr>
															<th><input type="checkbox" name="check_all" class="check_all"></th>
                                                            <th>Name</th>
                                                            <th>Position</th>
                                                            <th>Office</th>
                                                            <th>Age</th>
                                                            <th>Start date</th>
															<th></th>
															<th></th>
                                                        </tr>
                                                    </thead>


                                                    <tbody>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- End Row -->

                    </div> <!-- container -->

                </div> <!-- content -->
<script type="text/javascript">
$(document).ready(function() {
  table = $('#table').DataTable({

	"processing": true,
	"serverSide": true,

	"ajax": {
		"url": "<?php echo site_url('admin/admin_list?token=').$_SESSION['token']; ?>",
		"type": "POST"
	},

	"columnDefs": [{
		"targets": 0,
		"visible": true,
		"orderable": false,
		"render": function ( data, type, row ) {
			return '<input type="checkbox" class="check_delete" name="check_delete" value="'+row[0]+'">';
		},
	},
	{
		"targets": 6,
		"visible": true,
		"orderable": false,
		"render": function ( data, type, row ) {
			return '<a admin-id="'+row[0]+'" href="javascript:void(0);" class="edit"><i class="fa fa-pencil"></i></a>';
		}
	},
	{
		"targets": 7,
		"visible": true,
		"orderable": false,
		"render": function ( data, type, row ) {
			return '<a admin-id="'+row[0]+'" href="javascript:void(0);" class="single_delete"><i class="fa fa-trash"></i></a>';
		}
	}],

  });
});
/*------------------------------------------On Click Edit--------------------------------------*/	
$(document).on('click', '.edit', function(){ 
	var admin_id = $(this).attr("admin-id");
	window.location.href="<?php echo site_url('admin/update?token=').$_SESSION['token']; ?>"+"&admin_id="+admin_id; 
});

/*------------------------------------------On Click Single Delete Box--------------------------------------*/	
$(document).on('click', '.single_delete', function(){ 
	var admin_id = $(this).attr("admin-id");
	
	if (confirm("Are you sure, you want to delete?")) {
		$(".dataTables_processing").show(); 
		$.ajax({
			type: "GET",
			url: "<?php echo site_url('admin_service/delete?token=').$_SESSION['token']; ?>"+"&admin_id="+admin_id,
			dataType: "json",
			success: function(data){
				console.log(JSON.stringify(data));
				if(data.success) {
				   alert("Deleted successfully.");
				   table.draw(); 
				} else {
					alert(data.message);
				}
				$(".dataTables_processing").hide();
			}
		});
	}
	
});

/*---------------------------------On Click Delete All----------------------------------------*/
$(document).on('click', '.delete_all', function(){ 
	var admin_id = [];
	var i = 0;
	$('.check_delete').each(function() {
		if(this.checked == true) {
			admin_id[i] = $(this).val();
			i++;
		}  
	});
	
	if(admin_id.length == 0) {
		alert("Please select an item to delete.");
		return;
	}
	
	if (confirm("Are you sure, you want to delete?")) {
		$(".dataTables_processing").show(); 
		$.ajax({
			type: "GET",
			url: "<?php echo site_url('admin_service/delete_all?token=').$_SESSION['token']; ?>"+"&admin_id="+admin_id,
			dataType: "json",
			success: function(data){
				console.log(JSON.stringify(data));
				if(data.success) {
				   alert("Deleted successfully.");
				   table.draw(); 
				} else {
					alert(data.message);
				}
				$(".dataTables_processing").hide();
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
</script>
<?php include('/../admin_footer.php'); ?>