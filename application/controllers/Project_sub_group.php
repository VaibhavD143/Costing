<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_sub_group extends CI_Controller
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
			$load['title'] = 'Add Project_sub_group';
			$results=$this->db_query->table_select_where('project_group',array('project_id' => $_SESSION['project_id']));
			foreach($results as $result)
			{
				$load['project_groups'][]=$result;
			}
			$this->load->view('add_project_sub_group',$load);
			return;
		}
		$data = array(
			'project_group_id' => $_POST['project_group_id'],
			'project_id' => $_SESSION['project_id'],
			'project_sub_group_name' => $_POST['project_sub_group_name'],
			// 'project_sub_group_cost' => $_POST['project_sub_group_cost']
		);

		$result['message'] = "Project_sub_group not saved";
		if($this->db_query->table_insert('project_sub_group',$data) == true)
		{
			redirect('project_sub_group/get_all?type=insert&success=true&nav_menu=project_sub_group&token='.$_SESSION['token']);
		} else {

			redirect('project_sub_group/insert?type=insert&success=false&nav_menu=project_sub_group&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_sub_group_id' => $_GET['project_sub_group_id']
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

	public function get_all()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['draw'])) {
			$load['title'] = 'Project_sub_group List';
			$this->load->view('project_sub_group_list',$load);
			return;
		}
		$column = array('project_sub_group.project_group_id','project_sub_group.project_id','project_sub_group.project_sub_group_name','project_sub_group.project_sub_group_cost');
		$order = array('project_sub_group_id' => 'asc');
		$lists = $this->person->get_datatables2('project_sub_group',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->project_sub_group_id;
			$row[] = $list->project_group_id;
			$row[] = $list->project_id;
			$row[] = $list->project_sub_group_name;
			$row[] = $list->project_sub_group_cost;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('project_sub_group',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('project_sub_group',$column,$order),
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
			$load['title'] = 'Add Project_sub_group';
			$result_data = $this->db_query->table_select_where('project_sub_group',array('project_sub_group_id' => $_GET['project_sub_group_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'project_sub_group_id' => $res['project_sub_group_id'],
					'project_group_id' => $res['project_group_id'],
					'project_id' => $res['project_id'],
					'project_sub_group_name' => $res['project_sub_group_name'],
					'project_sub_group_cost' => $res['project_sub_group_cost']
				);
			}
			$results=$this->db_query->table_select_where('project_group',array('project_id' => $_SESSION['project_id']));
			foreach($results as $result)
			{
				$load['project_groups'][]=$result;
			}
			$this->load->view('add_project_sub_group',$load);
			return;
		}
		$where = array(
			'project_sub_group_id' => $_GET['project_sub_group_id']
		);
		$data = array(
			'project_group_id' => $_POST['project_group_id'],
			'project_id' => $_SESSION['project_id'],
			'project_sub_group_name' => $_POST['project_sub_group_name'],
			// 'project_sub_group_cost' => $_POST['project_sub_group_cost']
			); 

		$result['message'] = "Project_sub_group not update";
		if($this->db_query->table_update('project_sub_group',$data,$where) == true)
		{
			redirect('project_sub_group/get_all?type=update&success=true&nav_menu=project_sub_group&token='.$_SESSION['token']);
		} else {
			redirect('project_sub_group/update?type=update&success=false&project_sub_group_id='.$_GET['project_sub_group_id'].'&nav_menu=project_sub_group&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'project_sub_group_id' => $_GET['project_sub_group_id']
		);
		$result['message'] = "Project_sub_group not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('project_sub_group',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Project_sub_group deleted";
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
		$where = $_GET['project_sub_group_id'];
		$result['message'] = 'Project_sub_group not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('project_sub_group','project_sub_group_id',$where) == true) {
			$result['message'] = 'Project_sub_group deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}