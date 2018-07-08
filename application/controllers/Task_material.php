<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task_material extends CI_Controller
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
			$load['title'] = 'Add Task_material';
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select('material_group');
			foreach($results as $result)
			{
				$load['material_groups'][]=$result;
			}
			if(isset($_GET['task_group_id']) and $_GET['task_group_id'] != null)
			{
				$results=$this->db_query->table_select_where('tasks',array('task_group_id' =>$_GET['task_group_id']));
				foreach($results as $result)
				{
					$load['tasks'][]=$result;
				}
			}
			$this->load->view('add_task_material',$load);
			return;
		}
		$price=$_POST['material_price'];
		$t_price=$_POST['material_t_price'];
		$material_quantity=$_POST['material_quantity'];
		$task_material_cost=(int)$price*(int)$material_quantity;
		$task_transport_cost=(int)$t_price*(int)$material_quantity;
		$task_material_total_cost=$task_transport_cost+$task_material_cost;
		$task_id=$_POST['task_id'];
		$task_group_id=$_POST['task_group_id'];
		// if($this->db_query->custom_boolean_query('UPDATE `tasks` SET `material_cost` = `material_cost`+'.$task_material_total_cost.'  WHERE `tasks`.`task_id` = "'.$task_id.'"'))
		// {
			// $result['message2'] = "Tasks Material Price Updated";
		// }
		// else
		// {
			// $result['message2'] = "Tasks Material Price Not Updated";
		// }
	
		$data = array(
			'task_id' => $_POST['task_id'],
			'task_group_id' => $_POST['task_group_id'],
			'material_group_id' => $_POST['material_group_id'],
			'material_id' => $_POST['material_id'],
			'material_quantity' => $_POST['material_quantity'],
			'task_material_total_cost'=>$task_material_total_cost,
			'task_transport_cost' => $task_transport_cost,
			'task_material_cost' => $task_material_cost
		);
		
		
		$result['message'] = "Task_material not saved";
		if($this->db_query->table_insert('task_material',$data) == true)
		{
			$result['message2']=$this->update_task_material_price($task_id);
			redirect('task_material/insert?type=insert&success=true&nav_menu=task_material&task_group_id='.$task_group_id.'&task_id='.$task_id.'&token='.$_SESSION['token']);
			// redirect('task_material/get_all?type=insert&success=true&nav_menu=task_material&token='.$_SESSION['token']);
		} else {

			redirect('task_material/insert?type=insert&success=false&nav_menu=task_material&token='.$_SESSION['token']);
		}

	}
	public function get_materials(){
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'material_group_id' => $_GET['material_group_id']
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
	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_material_id' => $_GET['task_material_id']
		);
		$result_data = $this->db_query->table_select_where('task_material',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'task_material_id' => $data['task_material_id'],
				'task_id' => $data['task_id'],
				'task_group_id' => $data['task_group_id'],
				'material_group_id' => $data['material_group_id'],
				'material_id' => $data['material_id'],
				'material_quantity' => $data['material_quantity'],
				'task_material_cost' => $data['task_material_cost'],
				'task_transport_cost' => $data['task_transport_cost'],
				'task_material_total_cost' => $data['task_material_total_cost']
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
			$load['title'] = 'Task_material List';
			$this->load->view('task_material_list',$load);
			return;
		}
		$column = array('task_material.task_id','task_material.task_group_id','task_material.material_id','task_material.material_quantity','task_material.task_material_cost','task_material.task_transport_cost','task_material.task_material_total_cost');
		$order = array('task_material_id' => 'asc');
		$lists = $this->person->get_datatables2('task_material',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->task_material_id;
			$row[] = $list->task_id;
			$row[] = $list->task_group_id;
			$row[] = $list->material_group_id;
			$row[] = $list->material_id;
			$row[] = $list->material_quantity;
			$row[] = $list->task_material_cost;
			$row[] = $list->task_transport_cost;
			$row[] = $list->task_material_total_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('task_material',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('task_material',$column,$order),
						'data' => $data,
				);
		echo json_encode($output);
	}
	public function update_task_material_price($task_id)
	{
		// $task_id=2;
		$sql='SELECT sum(task_material_total_cost) AS tot FROM task_material WHERE task_id = "'.$task_id.'"';
		$task_material_total_cost=$this->db_query->get_scalar_value($sql,"tot");
		if($task_material_total_cost == "")
		{
			$task_material_total_cost=0;
		}
		$sql='UPDATE `tasks` SET `material_cost` = "'.$task_material_total_cost.'"  WHERE `tasks`.`task_id` = "'.$task_id.'"';
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
			$load['title'] = 'Add Task_material';
			$result_data = $this->db_query->table_select_where('task_material',array('task_material_id' => $_GET['task_material_id']));
			foreach($result_data as $res) {
				$task_group_id=$res['task_group_id'];
				$material_group_id=$res['material_group_id'];
				
				$material_quantity=$res['material_quantity'];
				$task_material_cost=$res['task_material_cost'];
				$task_transport_cost=$res['task_transport_cost'];
				if($material_quantity != '0')
				{
					$price=(int)$task_material_cost / (int)$material_quantity;
					$t_price=(int)$task_transport_cost/ (int)$material_quantity;
				}
				else
				{
					$price=0;
					$t_price=0;
				}
				$task_id=$res['task_id'];
				$task_material_total_cost=$res['task_material_total_cost'];
				
				$load['main_data'] = array(
					'task_material_id' => $res['task_material_id'],
					'task_id' => $res['task_id'],
					'task_group_id' => $res['task_group_id'],
					'material_group_id' => $res['material_group_id'],
					'material_id' => $res['material_id'],
					'material_quantity' => $res['material_quantity'],
					'task_material_cost' => $res['task_material_cost'],
					'task_transport_cost' => $res['task_transport_cost'],
					'task_material_total_cost' => $res['task_material_total_cost'],
					'price' => $price,
					't_price' => $t_price
					
				);
			}
			
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select('material_group');
			foreach($results as $result)
			{
				$load['material_groups'][]=$result;
			}
			
			$results=$this->db_query->table_select_where('materials',array('material_group_id' =>$material_group_id));
			foreach($results as $result)
			{
				$load['materials'][]=$result;
			}
			$results=$this->db_query->table_select_where('tasks',array('task_group_id' =>$task_group_id));
			foreach($results as $result)
			{
				$load['tasks'][]=$result;
			}
			$this->load->view('add_task_material',$load);
			return;
		}
		$where = array(
			'task_material_id' => $_GET['task_material_id']
		);
		$task_id_pre=$this->db_query->get_scalar_value('SELECT task_id FROM task_material WHERE task_material_id="'.$_GET['task_material_id'].'"',"task_id");
		
		// $result_data = $this->db_query->table_select_where('task_material',$where);
		// foreach($result_data as $data) {
				// $task_id=$data['task_id'];
				// $task_material_total_cost=$data['task_material_total_cost'];
		// }
		// $sql='UPDATE `tasks` SET `material_cost` = `material_cost`-'.$task_material_total_cost.'  WHERE `tasks`.`task_id` = "'.$task_id.'"';
		// if($this->db_query->custom_boolean_query($sql))
		// {
			// $load['message1'] = "Tasks Material Price Updated";
		// }
		// else
		// {
			// $load['message1'] = "Tasks Material Price Not Updated";
		// }
		
		$price=$_POST['material_price'];
		$t_price=$_POST['material_t_price'];
		$material_quantity=$_POST['material_quantity'];
		$task_material_cost=(int)$price*(int)$material_quantity;
		$task_transport_cost=(int)$t_price*(int)$material_quantity;
		$task_material_total_cost=$task_transport_cost+$task_material_cost;
		
		// if($this->db_query->custom_boolean_query('UPDATE `tasks` SET `material_cost` = `material_cost`+'.$task_material_total_cost.'  WHERE `tasks`.`task_id` = "'.$task_id.'"'))
		// {
			// $result['message2'] = "Tasks Material Price Updated";
		// }
		// else
		// {
			// $result['message2'] = "Tasks Material Price Not Updated";
		// }
		$data = array(
			'task_id' => $_POST['task_id'],
			'task_group_id' => $_POST['task_group_id'],
			'material_group_id' => $_POST['material_group_id'],
			'material_id' => $_POST['material_id'],
			'material_quantity' => $_POST['material_quantity'],
			'task_material_total_cost'=>$task_material_total_cost,
			'task_transport_cost' => $task_transport_cost,
			'task_material_cost' => $task_material_cost
		);
		$task_id=$_POST['task_id'];
		$result['message'] = "Task_material not update";
		if($this->db_query->table_update('task_material',$data,$where) == true)
		{
			$result['message2']=$this->update_task_material_price($task_id_pre);
			$result['message2']=$this->update_task_material_price($task_id);
			redirect('task_material/get_all?type=update&success=true&nav_menu=task_material&token='.$_SESSION['token']);
		} else {
			redirect('task_material/update?type=update&success=false&nav_menu=task_material&task_material_id='.$_GET['task_material_id'].'&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_material_id' => $_GET['task_material_id']
		);
		
		
		$task_id = $this->db_query->get_scalar_value('SELECT `task_id` FROM task_material where task_material_id = "'.$_GET['task_material_id'].'"','task_id');
		// $sql='UPDATE `tasks` SET `material_cost` = `material_cost`-'.$task_material_total_cost.'  WHERE `tasks`.`task_id` = "'.$task_id.'"';
		// if($this->db_query->custom_boolean_query($sql))
		// {
			// $load['message2'] = "Tasks Material Price Updated";
		// }
		// else
		// {
			// $load['message2'] = "Tasks Material Price Not Updated";
		// }
		$result['message'] = "Task_material not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('task_material',$where) == true)
		{
			$result['message2']=$this->update_task_material_price($task_id);
			$result['success'] = true;
			$result['message'] = "Task_material deleted";
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
		$where = $_GET['task_material_id'];
		$task_material_ids=explode(',',$where);
		$task_id = $this->db_query->get_scalar_value('SELECT `task_id` FROM task_material where task_material_id = "'.$task_material_ids[0].'"','task_id');
		// foreach($task_material_ids as $id)
		// {
			// $result_data = $this->db_query->table_select_where('task_material',array('task_material_id' => $id));
			// foreach($result_data as $data) {
					// $task_id=$data['task_id'];
					// $task_material_total_cost=$data['task_material_total_cost'];
			// }
			// $sql='UPDATE `tasks` SET `material_cost` = `material_cost`-'.$task_material_total_cost.'  WHERE `tasks`.`task_id` = "'.$task_id.'"';
			// if($this->db_query->custom_boolean_query($sql))
			// {
				// $result['message2'] = "Tasks Material Price Updated";
			// }
			// else
			// {
				// $result['message2'] = "Tasks Material Price Not Updated";
				// break;
			// }
		// }
		$result['message'] = 'Task_material not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('task_material','task_material_id',$where) == true) {
			$result['message2']=$this->update_task_material_price($task_id);
			$result['message'] = 'Task_material deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}