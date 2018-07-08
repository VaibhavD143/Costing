<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task_group extends CI_Controller
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
			$load['title'] = 'Add Task_group';
			$this->load->view('add_task_group',$load);
			return;
		}
		$data = array(
			'task_group_name' => $_POST['task_group_name']
		);

		$result['message'] = "Task_group not saved";
		if($this->db_query->table_insert('task_group',$data) == true)
		{
			redirect('task_group/get_all?type=insert&success=true&nav_menu=task_group&token='.$_SESSION['token']);
		} else {

			redirect('task_group/insert?type=insert&success=false&nav_menu=task_group&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_group_id' => $_GET['task_group_id']
		);
		$result_data = $this->db_query->table_select_where('task_group',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'task_group_id' => $data['task_group_id'],
				'task_group_name' => $data['task_group_name']
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
			$load['title'] = 'Task_group List';
			$this->load->view('task_group_list',$load);
			return;
		}
		$column = array('task_group.task_group_name');
		$order = array('task_group_id' => 'asc');
		$lists = $this->person->get_datatables2('task_group',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->task_group_id;
			$row[] = $list->task_group_name;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('task_group',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('task_group',$column,$order),
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
			$load['title'] = 'Add Task_group';
			$result_data = $this->db_query->table_select_where('task_group',array('task_group_id' => $_GET['task_group_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'task_group_id' => $res['task_group_id'],
					'task_group_name' => $res['task_group_name']
				);
			}
			$this->load->view('add_task_group',$load);
			return;
		}
		$where = array(
			'task_group_id' => $_GET['task_group_id']
		);
		$data = array(
			'task_group_name' => $_POST['task_group_name']
			); 

		$result['message'] = "Task_group not update";
		if($this->db_query->table_update('task_group',$data,$where) == true)
		{
			redirect('task_group/get_all?type=update&success=true&nav_menu=task_group&token='.$_SESSION['token']);
		} else {
			redirect('task_group/update?type=update&success=false&task_group_id='.$_GET['task_group_id'].'&nav_menu=task_group&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'task_group_id' => $_GET['task_group_id']
		);
		$result['message'] = "Task_group not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('task_group',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Task_group deleted";
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
		$where = $_GET['task_group_id'];
		$result['message'] = 'Task_group not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('task_group','task_group_id',$where) == true) {
			$result['message'] = 'Task_group deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}