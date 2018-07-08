<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_group extends CI_Controller
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
			$load['title'] = 'Add Project_group';
			
			$this->load->view('add_project_group',$load);
			return;
		}
		$data = array(
			'project_id' => $_SESSION['project_id'],
			'project_group_name' => $_POST['project_group_name'],
			// 'project_group_cost' => $_POST['project_group_cost']
		);

		$result['message'] = "Project_group not saved";
		if($this->db_query->table_insert('project_group',$data) == true)
		{
			redirect('project_group/get_all?type=insert&success=true&nav_menu=project_group&token='.$_SESSION['token']);
		} else {

			redirect('project_group/insert?type=insert&success=false&nav_menu=project_group&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_group_id' => $_GET['project_group_id']
		);
		$result_data = $this->db_query->table_select_where('project_group',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'project_group_id' => $data['project_group_id'],
				'project_id' => $data['project_id'],
				'project_group_name' => $data['project_group_name'],
				'project_group_cost' => $data['project_group_cost']
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
			$load['title'] = 'Project_group List';
			$this->load->view('project_group_list',$load);
			return;
		}
		$column = array('project_group.project_id','project_group.project_group_name','project_group.project_group_cost');
		$order = array('project_group_id' => 'asc');
		$where=array('project_id' => $_SESSION['project_id']);
		$lists = $this->person->get_datatables2('project_group',$column,$order,null ,null,null,null,null,null,$where);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->project_group_id;
			$row[] = $list->project_id;
			$row[] = $list->project_group_name;
			$row[] = $list->project_group_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('project_group',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('project_group',$column,$order),
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
			$load['title'] = 'Add Project_group';
			$result_data = $this->db_query->table_select_where('project_group',array('project_group_id' => $_GET['project_group_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'project_group_id' => $res['project_group_id'],
					'project_id' => $res['project_id'],
					'project_group_name' => $res['project_group_name'],
					'project_group_cost' => $res['project_group_cost']
				);
			}
			$this->load->view('add_project_group',$load);
			return;
		}
		$where = array(
			'project_group_id' => $_GET['project_group_id']
		);
		$data = array(
			'project_id' => $_SESSION['project_id'],
			'project_group_name' => $_POST['project_group_name'],
			// 'project_group_cost' => $_POST['project_group_cost']
			); 

		$result['message'] = "Project_group not update";
		if($this->db_query->table_update('project_group',$data,$where) == true)
		{
			redirect('project_group/get_all?type=update&success=true&nav_menu=project_group&token='.$_SESSION['token']);
		} else {
			redirect('project_group/update?type=update&success=false&project_group_id='.$_GET['project_group_id'].'&nav_menu=project_group&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_group_id' => $_GET['project_group_id']
		);
		$result['message'] = "Project_group not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('project_group',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Project_group deleted";
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
		$where = $_GET['project_group_id'];
		$result['message'] = 'Project_group not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('project_group','project_group_id',$where) == true) {
			$result['message'] = 'Project_group deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}