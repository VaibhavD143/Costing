<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller
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
			$load['title'] = 'Add Tasks';
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$this->load->view('add_tasks',$load);
			return;
		}
		$data = array(
			'task_group_id' => $_POST['task_group_id'],
			'task_name' => $_POST['task_name']
		);

		$result['message'] = "Tasks not saved";
		if($this->db_query->table_insert('tasks',$data) == true)
		{
			redirect('tasks/get_all?type=insert&success=true&nav_menu=tasks&token='.$_SESSION['token']);
		} else {

			redirect('tasks/insert?type=insert&success=false&nav_menu=tasks&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_id' => $_GET['task_id']
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
			$load['title'] = 'Tasks List';
			$this->load->view('tasks_list',$load);
			return;
		}
		$column = array('tasks.task_group_id','tasks.task_name','tasks.material_cost','tasks.labour_cost','tasks.equipment_cost','tasks.total_cost');
		$order = array('task_id' => 'asc');
		$lists = $this->person->get_datatables2('tasks',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->task_id;
			$row[] = $list->task_group_id;
			$row[] = $list->task_name;
			$row[] = $list->material_cost;
			$row[] = $list->labour_cost;
			$row[] = $list->equipment_cost;
			$row[] = $list->total_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('tasks',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('tasks',$column,$order),
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
			$load['title'] = 'Add Tasks';
			$result_data = $this->db_query->table_select_where('tasks',array('task_id' => $_GET['task_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'task_id' => $res['task_id'],
					'task_group_id' => $res['task_group_id'],
					'task_name' => $res['task_name'],
					'material_cost' => $res['material_cost'],
					'labour_cost' => $res['labour_cost'],
					'equipment_cost' => $res['equipment_cost'],
					'total_cost' => $res['total_cost']
				);
			}
			$results=$this->db_query->table_select('task_group');
			foreach($results as $result)
			{
				$load['task_groups'][]=$result;
			}
			$this->load->view('add_tasks',$load);
			return;
		}
		$where = array(
			'task_id' => $_GET['task_id']
		);
		$data = array(
			'task_group_id' => $_POST['task_group_id'],
			'task_name' => $_POST['task_name']
			); 

		$result['message'] = "Tasks not update";
		if($this->db_query->table_update('tasks',$data,$where) == true)
		{
			redirect('tasks/get_all?type=update&success=true&nav_menu=tasks&token='.$_SESSION['token']);
		} else {
			redirect('tasks/update?type=update&success=false&nav_menu=tasks&task_id='.$_GET['task_id'].'&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_id' => $_GET['task_id']
		);
		$result['message'] = "Tasks not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('tasks',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Tasks deleted";
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
		$where = $_GET['task_id'];
		$result['message'] = 'Tasks not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('tasks','task_id',$where) == true) {
			$result['message'] = 'Tasks deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}