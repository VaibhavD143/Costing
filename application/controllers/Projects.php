<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller
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
			$load['title'] = 'Add Projects';
			$this->load->view('add_projects',$load);
			return;
		}
		$data = array(
			'project_name' => $_POST['project_name']
		);

		$result['message'] = "Projects not saved";
		if($this->db_query->table_insert('projects',$data) == true)
		{
			redirect('projects/get_all?type=insert&success=true&nav_menu=projects&token='.$_SESSION['token']);
		} else {

			redirect('projects/insert?type=insert&success=false&nav_menu=projects&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_id' => $_GET['project_id']
		);
		$result_data = $this->db_query->table_select_where('projects',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'project_id' => $data['project_id'],
				'project_name' => $data['project_name']
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
			$load['title'] = 'Projects List';
			$this->load->view('projects_list',$load);
			return;
		}
		$column = array('projects.project_name');
		$order = array('project_id' => 'asc');
		$lists = $this->person->get_datatables2('projects',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->project_id;
			$row[] = $list->project_name;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('projects',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('projects',$column,$order),
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
			$load['title'] = 'Add Projects';
			$result_data = $this->db_query->table_select_where('projects',array('project_id' => $_GET['project_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'project_id' => $res['project_id'],
					'project_name' => $res['project_name']
				);
			}
			$this->load->view('add_projects',$load);
			return;
		}
		$where = array(
			'project_id' => $_GET['project_id']
		);
		$data = array(
			'project_name' => $_POST['project_name']
			); 

		$result['message'] = "Projects not update";
		if($this->db_query->table_update('projects',$data,$where) == true)
		{
			redirect('projects/get_all?type=update&success=true&nav_menu=projects&token='.$_SESSION['token']);
		} else {
			redirect('projects/update?type=update&success=false&project_id='.$_GET['project_id'].'&nav_menu=projects&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_id' => $_GET['project_id']
		);
		$result['message'] = "Projects not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('projects',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Projects deleted";
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
		$where = $_GET['project_id'];
		$result['message'] = 'Projects not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('projects','project_id',$where) == true) {
			$result['message'] = 'Projects deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}