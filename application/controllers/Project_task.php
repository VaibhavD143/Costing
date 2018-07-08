<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_task extends CI_Controller
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
			$load['title'] = 'Add Project_task';
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select_where('project_group',array('project_id' => $_SESSION['project_id']));
			foreach($results as $result)
			{
				$load['project_groups'][]=$result;
			}
			if(isset($_GET['project_sub_group_id']) and $_GET['project_sub_group_id'] != null)
			{
				$results=$this->db_query->table_select_where('project_sub_group',array('project_group_id' =>$_GET['project_group_id']));
				foreach($results as $result)
				{
					$load['project_sub_groups'][]=$result;
				}
			}
			$this->load->view('add_project_task',$load);
			return;
		}
		$task_area=$_POST['task_area'];
		$total_cost=$_POST['total_cost'];
		$task_cost=(int)$task_area*(int)$total_cost;
		$data = array(
			'project_sub_group_id' => $_POST['project_sub_group_id'],
			'project_group_id' => $_POST['project_group_id'],
			'project_id' => $_SESSION['project_id'],
			'task_group_id' => $_POST['task_group_id'],
			'task_id' => $_POST['task_id'],
			'task_name' => $_POST['task_name'],
			'task_area' => $_POST['task_area'],
			'task_cost' => $task_cost
		);
		$project_group_id=$_POST['project_group_id'];
		$project_sub_group_id=$_POST['project_sub_group_id'];
		$result['message'] = "Project_task not saved";
		if($this->db_query->table_insert('project_task',$data) == true)
		{
			// redirect('task_material/insert?type=insert&success=true&nav_menu=task_material&task_group_id='.$task_group_id.'&task_id='.$task_id.'&token='.$_SESSION['token']);
			redirect('project_task/insert?type=insert&success=true&nav_menu=project_task&project_group_id='.$project_group_id.'&project_sub_group_id='.$project_sub_group_id.'&token='.$_SESSION['token']);
		} else {

			redirect('project_task/insert?type=insert&success=false&nav_menu=project_task&token='.$_SESSION['token']);
		}

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
	public function get_sub_groups(){
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_group_id' => $_GET['project_group_id']
		);
		$result_data = $this->db_query->table_select_where('project_sub_group',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'project_sub_group_id' => $data['project_sub_group_id'],
				'project_group_id' => $data['project_group_id'],
				'project_id' => $data['project_id'],
				'project_sub_group_name' => $data['project_sub_group_name'],
				'project_sub_group_cost' => $data['project_sub_group_cost']
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
			'project_task_id' => $_GET['project_task_id']
		);
		$result_data = $this->db_query->table_select_where('project_task',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'project_task_id' => $data['project_task_id'],
				'project_sub_group_id' => $data['project_sub_group_id'],
				'project_group_id' => $data['project_group_id'],
				'project_id' => $data['project_id'],
				'task_group_id' => $data['task_group_id'],
				'task_id' => $data['task_id'],
				'task_name' => $data['task_name'],
				'task_area' => $data['task_area'],
				'task_cost' => $data['task_cost']
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
			$load['title'] = 'Project_task List';
			$this->load->view('project_task_list',$load);
			return;
		}
		$column = array('project_task.project_sub_group_id','project_task.project_group_id','project_task.project_id','project_task.task_group_id','project_task.task_id','project_task.task_name','project_task.task_area','project_task.task_cost');
		$order = array('project_task_id' => 'asc');
		$lists = $this->person->get_datatables2('project_task',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->project_task_id;
			$row[] = $list->project_group_id;
			$row[] = $list->project_sub_group_id;
			$row[] = $list->task_id;
			$row[] = $list->task_area;
			$row[] = $list->task_cost;
			$row[] = $list->task_group_id;
			$row[] = $list->task_name;
			$row[] = $list->project_id;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('project_task',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('project_task',$column,$order),
						'data' => $data,
				);
		echo json_encode($output);
	}
	public function update()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['btnsubmit'])) {
			$load['title'] = 'Add Project_task';
			$result_data = $this->db_query->table_select_where('project_task',array('project_task_id' => $_GET['project_task_id']));
			foreach($result_data as $res) {
				$project_group_id=$res['project_group_id'];
				$task_area = $res['task_area'];
				$task_cost = $res['task_cost'];
				if($task_area != '0')
				{
					$total_cost=(int)$task_cost/(int)$task_area;
				}
				else
				{
					$total_cost=0;
				}
				$load['main_data'] = array(
					'project_task_id' => $res['project_task_id'],
					'project_sub_group_id' => $res['project_sub_group_id'],
					'project_group_id' => $res['project_group_id'],
					'project_id' => $res['project_id'],
					'task_group_id' => $res['task_group_id'],
					'task_id' => $res['task_id'],
					'task_name' => $res['task_name'],
					'task_area' => $res['task_area'],
					'task_cost' => $res['task_cost'],
					'total_cost' => $total_cost
				);
			}
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$results=$this->db_query->table_select('tasks');
			foreach($results as $result)
			{
				$load['tasks'][]=$result;
			}
			$results=$this->db_query->table_select_where('project_group',array('project_id' => $_SESSION['project_id']));
			foreach($results as $result)
			{
				$load['project_groups'][]=$result;
			}
			$results=$this->db_query->table_select_where('project_sub_group',array('project_group_id' => $project_group_id));
			foreach($results as $result)
			{
				$load['project_sub_groups'][]=$result;
			}
			$this->load->view('add_project_task',$load);
			return;
		}
		$where = array(
			'project_task_id' => $_GET['project_task_id']
		);
		// $data = array(
			// 'project_sub_group_id' => $_POST['project_sub_group_id'],
			// 'project_group_id' => $_POST['project_group_id'],
			// 'project_id' => $_POST['project_id'],
			// 'task_group_id' => $_POST['task_group_id'],
			// 'task_id' => $_POST['task_id'],
			// 'task_name' => $_POST['task_name'],
			// 'task_area' => $_POST['task_area'],
			// 'task_cost' => $_POST['task_cost']
			// ); 
		$task_area=$_POST['task_area'];
		$total_cost=$_POST['total_cost'];
		$task_cost=(int)$task_area*(int)$total_cost;
		$data = array(
			'project_sub_group_id' => $_POST['project_sub_group_id'],
			'project_group_id' => $_POST['project_group_id'],
			'project_id' => $_SESSION['project_id'],
			'task_group_id' => $_POST['task_group_id'],
			'task_id' => $_POST['task_id'],
			'task_name' => $_POST['task_name'],
			'task_area' => $_POST['task_area'],
			'task_cost' => $task_cost
		);
		$result['message'] = "Project_task not update";
		if($this->db_query->table_update('project_task',$data,$where) == true)
		{
			redirect('project_task/get_all?type=update&success=true&nav_menu=project_task&token='.$_SESSION['token']);
		} else {
			redirect('project_task/update?type=update&success=false&project_task_id='.$_GET['project_task_id'].'&nav_menu=project_task&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_task_id' => $_GET['project_task_id']
		);
		$result['message'] = "Project_task not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('project_task',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Project_task deleted";
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
		$where = $_GET['project_task_id'];
		$result['message'] = 'Project_task not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('project_task','project_task_id',$where) == true) {
			$result['message'] = 'Project_task deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}