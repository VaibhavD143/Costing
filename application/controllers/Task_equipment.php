<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task_equipment extends CI_Controller
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
			$load['title'] = 'Add Task_equipment';
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select('equipment_group');
			foreach($results as $result)
			{
				$load['equipment_groups'][]=$result;
			}
			if(isset($_GET['task_group_id']) and $_GET['task_group_id'] != null)
			{
				$results=$this->db_query->table_select_where('tasks',array('task_group_id' =>$_GET['task_group_id']));
				foreach($results as $result)
				{
					$load['tasks'][]=$result;
				}
			}
			$this->load->view('add_task_equipment',$load);
			return;
		}
		$price=$_POST['equipment_price'];
		$r_price=$_POST['equipment_r_price'];
		$equipment_quantity=$_POST['equipment_quantity'];
		$task_equipment_cost=(int)$price*(int)$equipment_quantity;
		$task_equipment_run_cost=(int)$r_price*(int)$equipment_quantity;
		$task_equipment_total_cost=$task_equipment_run_cost+$task_equipment_cost;
		$task_id=$_POST['task_id'];
		$task_group_id=$_POST['task_group_id'];
		$data = array(
			'task_id' => $_POST['task_id'],
			'task_group_id' => $_POST['task_group_id'],
			'equipment_group_id' => $_POST['equipment_group_id'],
			'equipment_id' => $_POST['equipment_id'],
			'equipment_quantity' => $_POST['equipment_quantity'],
			'task_equipment_cost' => $task_equipment_cost,
			'task_equipment_run_cost' => $task_equipment_run_cost,
			'task_equipment_total_cost' => $task_equipment_total_cost
		);

		$result['message'] = "Task_equipment not saved";
		if($this->db_query->table_insert('task_equipment',$data) == true)
		{
			$result['message2']=$this->update_task_equipment_price($task_id);
			redirect('task_equipment/insert?type=insert&success=true&nav_menu=task_equipment&task_group_id='.$task_group_id.'&task_id='.$task_id.'&token='.$_SESSION['token']);
			// redirect('task_equipment/get_all?type=insert&success=true&nav_menu=task_equipment&token='.$_SESSION['token']);
		} else {
			redirect('task_equipment/insert?type=insert&success=false&nav_menu=task_equipment&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_equipment_id' => $_GET['task_equipment_id']
		);
		$result_data = $this->db_query->table_select_where('task_equipment',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'task_equipment_id' => $data['task_equipment_id'],
				'task_id' => $data['task_id'],
				'task_group_id' => $data['task_group_id'],
				'equipment_group_id' => $data['equipment_group_id'],
				'equipment_id' => $data['equipment_id'],
				'equipment_quantity' => $data['equipment_quantity'],
				'task_equipment_cost' => $data['task_equipment_cost'],
				'task_equipment_run_cost' => $data['task_equipment_run_cost'],
				'task_equipment_total_cost' => $data['task_equipment_total_cost']
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

	public function get_tasks(){
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_group_id' => $_GET['task_group_id']
		);
		$result_data = $this->db_query->table_select_where('tasks',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'task_id' => $data['task_id'],
				'task_group_id' => $data['task_group_id'],
				'task_name' => $data['task_name'],
				'material_cost' => $data['material_cost'],
				'labour_cost' => $data['labour_cost'],
				'equipment_cost' => $data['equipment_cost'],
				'total_cost' => $data['total_cost']
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
	public function get_equipments()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'equipment_group_id' => $_GET['equipment_group_id']
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
			$load['title'] = 'Task_equipment List';
			$this->load->view('task_equipment_list',$load);
			return;
		}
		$column = array('task_equipment.task_id','task_equipment.task_group_id','task_equipment.equipment_group_id','task_equipment.equipment_id','task_equipment.equipment_quantity','task_equipment.task_equipment_cost','task_equipment.task_equipment_run_cost','task_equipment.task_equipment_total_cost');
		$order = array('task_equipment_id' => 'asc');
		$lists = $this->person->get_datatables2('task_equipment',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->task_equipment_id;
			$row[] = $list->task_id;
			$row[] = $list->task_group_id;
			$row[] = $list->equipment_group_id;
			$row[] = $list->equipment_id;
			$row[] = $list->equipment_quantity;
			$row[] = $list->task_equipment_cost;
			$row[] = $list->task_equipment_run_cost;
			$row[] = $list->task_equipment_total_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('task_equipment',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('task_equipment',$column,$order),
						'data' => $data,
				);
		echo json_encode($output);
	}
	
	public function update_task_equipment_price($task_id)
	{
		// $task_id=2;
		$sql='SELECT sum(task_equipment_total_cost) AS tot FROM task_equipment WHERE task_id = "'.$task_id.'"';
		$task_equipment_total_cost=$this->db_query->get_scalar_value($sql,"tot");
		if($task_equipment_total_cost == "")
		{
			$task_equipment_total_cost=0;
		}
		$sql='UPDATE `tasks` SET `equipment_cost` = "'.$task_equipment_total_cost.'"  WHERE `tasks`.`task_id` = "'.$task_id.'"';
		if($this->db_query->custom_boolean_query($sql))
		{
			$sql='UPDATE tasks SET `total_cost` = `material_cost`+`equipment_cost`+`labour_cost` WHERE task_id="'.$task_id.'"';
			if($this->db_query->custom_boolean_query($sql))
			{
				$total_cost=$this->db_query->get_scalar_value('SELECT `total_cost` FROM tasks WHERE task_id="'.$task_id.'"','total_cost');
				$sql='UPDATE project_task SET `task_cost` = `task_area`*'.(int)$total_cost.' WHERE task_id="'.$task_id.'"';
				$res=$this->db_query->custom_boolean_query($sql);
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function update()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['btnsubmit'])) {
			$load['title'] = 'Add Task_equipment';
			$result_data = $this->db_query->table_select_where('task_equipment',array('task_equipment_id' => $_GET['task_equipment_id']));
			foreach($result_data as $res) {
				$task_group_id=$res['task_group_id'];
				$equipment_group_id=$res['equipment_group_id'];
				
				$equipment_quantity=$res['equipment_quantity'];
				$task_equipment_cost=$res['task_equipment_cost'];
				$task_equipment_run_cost=$res['task_equipment_run_cost'];
				if($equipment_quantity != '0')
				{
					$price=(int)$task_equipment_cost / (int)$equipment_quantity;
					$r_price=(int)$task_equipment_run_cost/ (int)$equipment_quantity;
				}
				else
				{
					$price=0;
					$r_price=0;
				}
				$task_id=$res['task_id'];
				$task_equipment_total_cost=$res['task_equipment_total_cost'];
				
				
				
				$load['main_data'] = array(
					'task_equipment_id' => $res['task_equipment_id'],
					'task_id' => $res['task_id'],
					'task_group_id' => $res['task_group_id'],
					'equipment_group_id' => $res['equipment_group_id'],
					'equipment_id' => $res['equipment_id'],
					'equipment_quantity' => $res['equipment_quantity'],
					'task_equipment_cost' => $res['task_equipment_cost'],
					'task_equipment_run_cost' => $res['task_equipment_run_cost'],
					'task_equipment_total_cost' => $res['task_equipment_total_cost'],
					'price' => $price,
					'r_price' => $r_price
				);
			}
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select('equipment_group');
			foreach($results as $result)
			{
				$load['equipment_groups'][]=$result;
			}
			$results=$this->db_query->table_select_where('equipments',array('equipment_group_id' =>$equipment_group_id));
			foreach($results as $result)
			{
				$load['equipments'][]=$result;
			}
			$results=$this->db_query->table_select_where('tasks',array('task_group_id' =>$task_group_id));
			foreach($results as $result)
			{
				$load['tasks'][]=$result;
			}
			$this->load->view('add_task_equipment',$load);
			return;
		}
		$where = array(
			'task_equipment_id' => $_GET['task_equipment_id']
		);
		$task_id_pre=$this->db_query->get_scalar_value('SELECT task_id FROM task_equipment WHERE task_equipment_id="'.$_GET['task_equipment_id'].'"',"task_id");
		$price=$_POST['equipment_price'];
		$r_price=$_POST['equipment_r_price'];
		$equipment_quantity=$_POST['equipment_quantity'];
		$task_equipment_cost=(int)$price*(int)$equipment_quantity;
		$task_equipment_run_cost=(int)$r_price*(int)$equipment_quantity;
		$task_equipment_total_cost=$task_equipment_run_cost+$task_equipment_cost;
		$task_id=$_POST['task_id'];
		
		$data = array(
			'task_id' => $_POST['task_id'],
			'task_group_id' => $_POST['task_group_id'],
			'equipment_group_id' => $_POST['equipment_group_id'],
			'equipment_id' => $_POST['equipment_id'],
			'equipment_quantity' => $_POST['equipment_quantity'],
			'task_equipment_cost' => $task_equipment_cost,
			'task_equipment_run_cost' => $task_equipment_run_cost,
			'task_equipment_total_cost' => $task_equipment_total_cost
			); 

		$result['message'] = "Task_equipment not update";
		if($this->db_query->table_update('task_equipment',$data,$where) == true)
		{
			$result['message2']=$this->update_task_equipment_price($task_id_pre);
			$result['message2']=$this->update_task_equipment_price($task_id);
			redirect('task_equipment/get_all?type=update&success=true&nav_menu=task_equipment&token='.$_SESSION['token']);
		} else {
			redirect('task_equipment/update?type=update&success=false&task_equipment_id='.$_GET['task_equipment_id'].'&nav_menu=task_equipment&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_equipment_id' => $_GET['task_equipment_id']
		);
		$task_id = $this->db_query->get_scalar_value('SELECT `task_id` FROM task_equipment where task_equipment_id = "'.$_GET['task_equipment_id'].'"','task_id');
		$result['message'] = "Task_equipment not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('task_equipment',$where) == true)
		{
			$result['message2']=$this->update_task_equipment_price($task_id);
			$result['success'] = true;
			$result['message'] = "Task_equipment deleted";
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
		$where = $_GET['task_equipment_id'];
		$task_equipment_ids=explode(',',$where);
		$task_id = $this->db_query->get_scalar_value('SELECT `task_id` FROM task_equipment where task_equipment_id = "'.$task_equipment_ids[0].'"','task_id');
		$result['message'] = 'Task_equipment not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('task_equipment','task_equipment_id',$where) == true) {
			$result['message2']=$this->update_task_equipment_price($task_id);
			$result['message'] = 'Task_equipment deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}