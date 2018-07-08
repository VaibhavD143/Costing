<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Materials extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if(!isset($_SESSION)) {
			session_start();
		}
		ob_start();
		$this->load->model('database/datatables','person',true);
		$this->load->model('database/query','db_query',true);
	}

	public function insert()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['btnsubmit'])) {
			$load['title'] = 'Add Materials';
			$results=$this->db_query->table_select('categories');
			foreach($results as $result)
			{
				$load['categories'][]=$result;
			}
			$results=$this->db_query->table_select('units');
			foreach($results as $result)
			{
				$load['units'][]=$result;
			}
			$results=$this->db_query->table_select('material_group');
			foreach($results as $result)
			{
				$load['material_groups'][]=$result;
			}
			
			$this->load->view('add_materials',$load);
			return;
		}
		$data = array(
			'material_group_id' => $_POST['material_group_id'],
			'category_id' => $_POST['category_id'],
			'unit_id' => $_POST['unit_id'],
			'material_name' => $_POST['material_name'],
			'material_rate' => $_POST['material_rate'],
			'material_standard_credit' => $_POST['material_standard_credit'],
			'material_transport_cost' => $_POST['material_transport_cost']
		);

		$result['message'] = "Materials not saved";
		if($this->db_query->table_insert('materials',$data) == true)
		{
			redirect('materials/get_all?type=insert&success=true&nav_menu=materials&token='.$_SESSION['token']);
		} else {

			redirect('materials/insert?type=insert&success=false&nav_menu=materials&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'material_id' => $_GET['material_id']
		);
		$result_data = $this->db_query->table_select_where('materials',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'material_id' => $data['material_id'],
				'material_group_id' => $data['material_group_id'],
				'category_id' => $data['category_id'],
				'unit_id' => $data['unit_id'],
				'material_name' => $data['material_name'],
				'material_rate' => $data['material_rate'],
				'material_standard_credit' => $data['material_standard_credit'],
				'material_transport_cost' => $data['material_transport_cost']
			);
		}
		$result['success'] = true;
		if($result_data == null)
		{
			$result['data'] = 'Data does not exists';
			$result['success'] = false;
		}

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function get_all()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['draw'])) {
			$load['title'] = 'Materials List';
			$this->load->view('materials_list',$load);
			return;
		}
		$column = array('materials.material_group_id','materials.category_id','materials.unit_id','materials.material_name','materials.material_rate','materials.material_standard_credit','materials.material_transport_cost');
		$order = array('material_id' => 'asc');
		$lists = $this->person->get_datatables2('materials',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->material_id;
			$row[] = $list->material_group_id;
			$row[] = $list->category_id;
			$row[] = $list->unit_id;
			$row[] = $list->material_name;
			$row[] = $list->material_rate;
			$row[] = $list->material_standard_credit;
			$row[] = $list->material_transport_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('materials',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('materials',$column,$order),
						'data' => $data,
				);
		echo json_encode($output);
	}
	
	public function update_task_material_price($material_id,$material_rate,$material_transport_cost)
	{
		$sql="SELECT task_id FROM task_material WHERE material_id='".$material_id."'";
		$task_ids=$this->db_query->custom_query($sql);
		if($task_ids != NULL)
		{
			// echo "in";
			// echo $task_ids[0]['task_id'];
			$sql="UPDATE task_material SET task_material_cost=`material_quantity`*'".(int)$material_rate."',task_transport_cost=`material_quantity`*'".(int)$material_transport_cost."' WHERE material_id='".$material_id."'";
			if($this->db_query->custom_boolean_query($sql))
			{
				$sql="UPDATE task_material SET task_material_total_cost=`task_transport_cost`+`task_material_cost` WHERE material_id='".$material_id."'";
				if($this->db_query->custom_boolean_query($sql))
				{
					foreach($task_ids as $task_id)
					{
						echo $task_id['task_id'];
						$sql='SELECT sum(task_material_total_cost) AS tot FROM task_material WHERE task_id = "'.$task_id['task_id'].'"';
						$task_material_total_cost=$this->db_query->get_scalar_value($sql,"tot");
						if($task_material_total_cost == "")
						{
							$task_material_total_cost=0;
						}
						$sql='UPDATE `tasks` SET `material_cost` = "'.$task_material_total_cost.'"  WHERE `tasks`.`task_id` = "'.$task_id['task_id'].'"';
						if($this->db_query->custom_boolean_query($sql))
						{
							$sql='UPDATE tasks SET `total_cost` = `material_cost`+`equipment_cost`+`labour_cost` WHERE task_id="'.$task_id['task_id'].'"';
							if($this->db_query->custom_boolean_query($sql))
							{
								$total_cost=$this->db_query->get_scalar_value('SELECT `total_cost` FROM tasks WHERE task_id="'.$task_id['task_id'].'"','total_cost');
								$sql='UPDATE project_task SET `task_cost` = `task_area`*'.(int)$total_cost.' WHERE task_id="'.$task_id['task_id'].'"';
								if(!$this->db_query->custom_boolean_query($sql))
								{
									return $res;
								}
							}
							else
							{
								return "Couldn't update total cost in tasks where task_id=".$task_id['task_id'];
							}
						}
						else
						{
							return "couldn't update material_cost in tasks where task_id=".$task_id['task_id'];
						}
					}
				}
				else
				{
					return "coudn't update task_material_total_cost";
				}
			}
			else
			{
				return "Coudn't update task_material_cost and task_transport_cost";
			}
		}
		else
		{
			return "NULL task_ids";
		}
	}
	
	public function update()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['btnsubmit'])) {
			$load['title'] = 'Add Materials';
			$result_data = $this->db_query->table_select_where('materials',array('material_id' => $_GET['material_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'material_id' => $res['material_id'],
					'material_group_id' => $res['material_group_id'],
					'category_id' => $res['category_id'],
					'unit_id' => $res['unit_id'],
					'material_name' => $res['material_name'],
					'material_rate' => $res['material_rate'],
					'material_standard_credit' => $res['material_standard_credit'],
					'material_transport_cost' => $res['material_transport_cost']
				);
			}
			$results=$this->db_query->table_select('categories');
			foreach($results as $result)
			{
				$load['categories'][]=$result;
			}
			$results=$this->db_query->table_select('units');
			foreach($results as $result)
			{
				$load['units'][]=$result;
			}
			$results=$this->db_query->table_select('material_group');
			foreach($results as $result)
			{
				$load['material_groups'][]=$result;
			}
			
			$this->load->view('add_materials',$load);
			return;
		}
		$where = array(
			'material_id' => $_GET['material_id']
		);
		$material_id=$_GET['material_id'];
		$material_rate=$_POST['material_rate'];
		$material_transport_cost=$_POST['material_transport_cost'];
		$data = array(
			'material_group_id' => $_POST['material_group_id'],
			'category_id' => $_POST['category_id'],
			'unit_id' => $_POST['unit_id'],
			'material_name' => $_POST['material_name'],
			'material_rate' => $_POST['material_rate'],
			'material_standard_credit' => $_POST['material_standard_credit'],
			'material_transport_cost' => $_POST['material_transport_cost']
			); 

		$result['message'] = "Materials not update";
		if($this->db_query->table_update('materials',$data,$where) == true)
		{
			$result['message2']=$this->update_task_material_price($material_id,$material_rate,$material_transport_cost);
			redirect('materials/get_all?type=update&success=true&nav_menu=materials&token='.$_SESSION['token']);
		} else {
			redirect('materials/update?type=update&success=false&material_id='.$_GET['material_id'].'&nav_menu=materials&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'material_id' => $_GET['material_id']
		);
		$result['message'] = "Materials not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('materials',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Materials deleted";
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	public function delete_all()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = $_GET['material_id'];
		$result['message'] = 'Materials not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('materials','material_id',$where) == true) {
			$result['message'] = 'Materials deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}