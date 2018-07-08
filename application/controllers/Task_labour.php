<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task_labour extends CI_Controller
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
			$load['title'] = 'Add Task_labour';
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select('labour_group');
			foreach($results as $result)
			{
				$load['labour_groups'][]=$result;
			}
			if(isset($_GET['task_group_id']) and $_GET['task_group_id'] != null)
			{
				$results=$this->db_query->table_select_where('tasks',array('task_group_id' =>$_GET['task_group_id']));
				foreach($results as $result)
				{
					$load['tasks'][]=$result;
				}
			}
			$this->load->view('add_task_labour',$load);
			return;
		}
		$price=$_POST['labour_price'];
		$labour_quantity=$_POST['labour_quantity'];
		$task_labour_total_cost=(int)$price*(int)$labour_quantity;
		$data = array(
			'task_id' => $_POST['task_id'],
			'task_group_id' => $_POST['task_group_id'],
			'labour_group_id' => $_POST['labour_group_id'],
			'labour_id' => $_POST['labour_id'],
			'labour_quantity' => $_POST['labour_quantity'],
			'task_labour_total_cost' => $task_labour_total_cost
			
		);
		$task_id=$_POST['task_id'];
		$task_group_id=$_POST['task_group_id'];
		$result['message'] = "Task_labour not saved";
		if($this->db_query->table_insert('task_labour',$data) == true)
		{
			$result['message2']=$this->update_task_material_price($task_id);
			redirect('task_labour/insert?type=insert&success=true&nav_menu=task_labour&task_group_id='.$task_group_id.'&task_id='.$task_id.'&token='.$_SESSION['token']);
			// redirect('task_labour/get_all?type=insert&success=true&nav_menu=task_labour&token='.$_SESSION['token']);
		} else {

			redirect('task_labour/insert?type=insert&success=false&nav_menu=task_labour&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_labour_id' => $_GET['task_labour_id']
		);
		$result_data = $this->db_query->table_select_where('task_labour',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'task_labour_id' => $data['task_labour_id'],
				'task_id' => $data['task_id'],
				'task_group_id' => $data['task_group_id'],
				'labour_group_id' => $data['labour_group_id'],
				'labour_id' => $data['labour_id'],
				'labour_quantity' => $data['labour_quantity'],
				'task_labour_total_cost' => $data['task_labour_total_cost']
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
	public function get_labours(){
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'labour_group_id' => $_GET['labour_group_id']
		);
		$result_data = $this->db_query->table_select_where('labours',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'labour_id' => $data['labour_id'],
				'labour_group_id' => $data['labour_group_id'],
				'unit_id' => $data['unit_id'],
				'labour_name' => $data['labour_name'],
				'labour_rate' => $data['labour_rate']
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
	public function get_all()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['draw'])) {
			$load['title'] = 'Task_labour List';
			$this->load->view('task_labour_list',$load);
			return;
		}
		$column = array('task_labour.task_id','task_labour.task_group_id','task_labour.labour_group_id','task_labour.labour_id','task_labour.labour_quantity','task_labour.task_labour_total_cost');
		$order = array('task_labour_id' => 'asc');
		$lists = $this->person->get_datatables2('task_labour',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->task_labour_id;
			$row[] = $list->task_id;
			$row[] = $list->task_group_id;
			$row[] = $list->labour_group_id;
			$row[] = $list->labour_id;
			$row[] = $list->labour_quantity;
			$row[] = $list->task_labour_total_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('task_labour',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('task_labour',$column,$order),
						'data' => $data,
				);
		echo json_encode($output);
	}
	
	public function update_task_material_price($task_id)
	{
		// $task_id=2;
		$sql='SELECT sum(task_labour_total_cost) AS tot FROM task_labour WHERE task_id = "'.$task_id.'"';
		$task_labour_total_cost=$this->db_query->get_scalar_value($sql,"tot");
		if($task_labour_total_cost == "")
		{
			$task_labour_total_cost=0;
		}
		$sql='UPDATE `tasks` SET `labour_cost` = "'.$task_labour_total_cost.'"  WHERE `tasks`.`task_id` = "'.$task_id.'"';
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
			$load['title'] = 'Add Task_labour';
			$result_data = $this->db_query->table_select_where('task_labour',array('task_labour_id' => $_GET['task_labour_id']));
			foreach($result_data as $res) {
				$task_group_id=$res['task_group_id'];
				$labour_group_id=$res['labour_group_id'];
				
				$labour_quantity=$res['labour_quantity'];
				$task_labour_total_cost=$res['task_labour_total_cost'];
				
				$price=(int)$task_labour_total_cost / (int)$labour_quantity;
				$load['main_data'] = array(
					'task_labour_id' => $res['task_labour_id'],
					'task_id' => $res['task_id'],
					'task_group_id' => $res['task_group_id'],
					'labour_group_id' => $res['labour_group_id'],
					'labour_id' => $res['labour_id'],
					'labour_quantity' => $res['labour_quantity'],
					'task_labour_total_cost' => $res['task_labour_total_cost'],
					'price' => $price
				);
			}
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select('labour_group');
			foreach($results as $result)
			{
				$load['labour_groups'][]=$result;
			}
			$results=$this->db_query->table_select_where('labours',array('labour_group_id' => $labour_group_id) );
			foreach($results as $result)
			{
				$load['labours'][]=$result;
			}
			$results=$this->db_query->table_select_where('tasks',array('task_group_id' =>$task_group_id));
			foreach($results as $result)
			{
				$load['tasks'][]=$result;
			}
			
			$this->load->view('add_task_labour',$load);
			return;
		}
		$where = array(
			'task_labour_id' => $_GET['task_labour_id']
		);
		$task_id_pre=$this->db_query->get_scalar_value('SELECT task_id FROM task_labour WHERE task_labour_id="'.$_GET['task_labour_id'].'"',"task_id");
		$result['message2']=$this->update_task_material_price($task_id);
		$price=$_POST['labour_price'];
		$labour_quantity=$_POST['labour_quantity'];
		$task_labour_total_cost=(int)$price*(int)$labour_quantity;
		$data = array(
			'task_id' => $_POST['task_id'],
			'task_group_id' => $_POST['task_group_id'],
			'labour_group_id' => $_POST['labour_group_id'],
			'labour_id' => $_POST['labour_id'],
			'labour_quantity' => $_POST['labour_quantity'],
			'task_labour_total_cost' => $task_labour_total_cost
			
		);
		$task_id=$_POST['task_id'];
		$result['message'] = "Task_labour not update";
		if($this->db_query->table_update('task_labour',$data,$where) == true)
		{
			$result['message1']=$this->update_task_material_price($task_id_pre);
			$result['message2']=$this->update_task_material_price($task_id);
			redirect('task_labour/get_all?type=update&success=true&nav_menu=task_labour&token='.$_SESSION['token']);
		} else {
			redirect('task_labour/update?type=update&success=false&task_labour_id='.$_GET['task_labour_id'].'&nav_menu=task_labour&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_labour_id' => $_GET['task_labour_id']
		);
		
		$task_id = $this->db_query->get_scalar_value('SELECT `task_id` FROM task_labour where task_labour_id = "'.$_GET['task_labour_id'].'"','task_id');
		
		
		$result['message'] = "Task_labour not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('task_labour',$where) == true)
		{
			$result['message2']=$this->update_task_material_price($task_id);
			$result['success'] = true;
			$result['message'] = "Task_labour deleted";
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
		$where = $_GET['task_labour_id'];
		$task_labour_ids=explode(',',$where);
		$task_id = $this->db_query->get_scalar_value('SELECT `task_id` FROM task_labour where task_labour_id = "'.$task_labour_ids[0].'"','task_id');
		$result['message'] = 'Task_labour not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('task_labour','task_labour_id',$where) == true) {
			$result['message2']=$this->update_task_material_price($task_id);
			$result['message'] = 'Task_labour deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}