<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equipments extends CI_Controller
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
			$load['title'] = 'Add Equipments';
			$results=$this->db_query->table_select('equipment_group');
			foreach($results as $result)
			{
				$load['equipment_groups'][]=$result;
			}
			$results=$this->db_query->table_select('units');
			foreach($results as $result)
			{
				$load['units'][]=$result;
			}
			$this->load->view('add_equipments',$load);
			return;
		}
		$data = array(
			'equipment_group_id' => $_POST['equipment_group_id'],
			'unit_id' => $_POST['unit_id'],
			'equipment_name' => $_POST['equipment_name'],
			'equipment_rate' => $_POST['equipment_rate'],
			'equipment_run_cost' => $_POST['equipment_run_cost']
		);

		$result['message'] = "Equipments not saved";
		if($this->db_query->table_insert('equipments',$data) == true)
		{
			redirect('equipments/get_all?type=insert&success=true&nav_menu=equipments&token='.$_SESSION['token']);
		} else {

			redirect('equipments/insert?type=insert&success=false&nav_menu=equipments&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'equipment_id' => $_GET['equipment_id']
		);
		$result_data = $this->db_query->table_select_where('equipments',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'equipment_id' => $data['equipment_id'],
				'equipment_group_id' => $data['equipment_group_id'],
				'unit_id' => $data['unit_id'],
				'equipment_name' => $data['equipment_name'],
				'equipment_rate' => $data['equipment_rate'],
				'equipment_run_cost' => $data['equipment_run_cost']
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
			$load['title'] = 'Equipments List';
			$this->load->view('equipments_list',$load);
			return;
		}
		$column = array('equipments.equipment_group_id','equipments.unit_id','equipments.equipment_name','equipments.equipment_rate','equipments.equipment_run_cost');
		$order = array('equipment_id' => 'asc');
		$lists = $this->person->get_datatables2('equipments',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->equipment_id;
			$row[] = $list->equipment_group_id;
			$row[] = $list->unit_id;
			$row[] = $list->equipment_name;
			$row[] = $list->equipment_rate;
			$row[] = $list->equipment_run_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('equipments',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('equipments',$column,$order),
						'data' => $data,
				);
		echo json_encode($output);
	}
	
	public function update_task_equipment_price($equipment_id,$equipment_rate,$equipment_run_cost)
	{
		$sql="SELECT task_id FROM task_equipment WHERE equipment_id='".$equipment_id."'";
		$task_ids=$this->db_query->custom_query($sql);
		if($task_ids != NULL)
		{
			// echo "in";
			// echo $task_ids[0]['task_id'];
			$sql="UPDATE task_equipment SET task_equipment_cost=`equipment_quantity`*'".(int)$equipment_rate."',task_equipment_run_cost=`equipment_quantity`*'".(int)$equipment_run_cost."' WHERE equipment_id='".$equipment_id."'";
			if($this->db_query->custom_boolean_query($sql))
			{
				$sql="UPDATE task_equipment SET task_equipment_total_cost=`task_equipment_run_cost`+`task_equipment_cost` WHERE equipment_id='".$equipment_id."'";
				if($this->db_query->custom_boolean_query($sql))
				{
					foreach($task_ids as $task_id)
					{
						echo $task_id['task_id'];
						$sql='SELECT sum(task_equipment_total_cost) AS tot FROM task_equipment WHERE task_id = "'.$task_id['task_id'].'"';
						$task_equipment_total_cost=$this->db_query->get_scalar_value($sql,"tot");
						if($task_equipment_total_cost == "")
						{
							$task_equipment_total_cost=0;
						}
						$sql='UPDATE `tasks` SET `equipment_cost` = "'.$task_equipment_total_cost.'"  WHERE `tasks`.`task_id` = "'.$task_id['task_id'].'"';
						if($this->db_query->custom_boolean_query($sql))
						{
							$sql='UPDATE tasks SET `total_cost` = `material_cost`+`equipment_cost`+`labour_cost` WHERE task_id="'.$task_id['task_id'].'"';
							if($this->db_query->custom_boolean_query($sql))
							{
								$total_cost=$this->db_query->get_scalar_value('SELECT `total_cost` FROM tasks WHERE task_id="'.$task_id['task_id'].'"','total_cost');
								$sql='UPDATE project_task SET `task_cost` = `task_area`*'.(int)$total_cost.' WHERE task_id="'.$task_id['task_id'].'"';
								$res=$this->db_query->custom_boolean_query($sql);
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
			$load['title'] = 'Add Equipments';
			$result_data = $this->db_query->table_select_where('equipments',array('equipment_id' => $_GET['equipment_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'equipment_id' => $res['equipment_id'],
					'equipment_group_id' => $res['equipment_group_id'],
					'unit_id' => $res['unit_id'],
					'equipment_name' => $res['equipment_name'],
					'equipment_rate' => $res['equipment_rate'],
					'equipment_run_cost' => $res['equipment_run_cost']
				);
			}
			$results=$this->db_query->table_select('equipment_group');
			foreach($results as $result)
			{
				$load['equipment_groups'][]=$result;
			}
			$results=$this->db_query->table_select('units');
			foreach($results as $result)
			{
				$load['units'][]=$result;
			}
			$this->load->view('add_equipments',$load);
			return;
		}
		$where = array(
			'equipment_id' => $_GET['equipment_id']
		);
		$equipment_id=$_GET['equipment_id'];
		$equipment_rate=$_POST['equipment_rate'];
		$equipment_run_cost=$_POST['equipment_run_cost'];
		$data = array(
			'equipment_group_id' => $_POST['equipment_group_id'],
			'unit_id' => $_POST['unit_id'],
			'equipment_name' => $_POST['equipment_name'],
			'equipment_rate' => $_POST['equipment_rate'],
			'equipment_run_cost' => $_POST['equipment_run_cost']
			); 

		$result['message'] = "Equipments not update";
		if($this->db_query->table_update('equipments',$data,$where) == true)
		{
			$result['message2']=$this->update_task_equipment_price($equipment_id,$equipment_rate,$equipment_run_cost);
			redirect('equipments/get_all?type=update&success=true&nav_menu=equipments&token='.$_SESSION['token']);
		} else {
			redirect('equipments/update?type=update&success=false&equipment_id='.$_GET['equipment_id'].'&nav_menu=equipments&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'equipment_id' => $_GET['equipment_id']
		);
		$result['message'] = "Equipments not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('equipments',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Equipments deleted";
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
		$where = $_GET['equipment_id'];
		$result['message'] = 'Equipments not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('equipments','equipment_id',$where) == true) {
			$result['message'] = 'Equipments deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}