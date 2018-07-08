<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labour_group extends CI_Controller
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
			$load['title'] = 'Add Labour_group';
			$this->load->view('add_labour_group',$load);
			return;
		}
		$data = array(
			'labour_group_name' => $_POST['labour_group_name']
		);

		$result['message'] = "Labour_group not saved";
		if($this->db_query->table_insert('labour_group',$data) == true)
		{
			redirect('labour_group/get_all?type=insert&success=true&nav_menu=labour_group&token='.$_SESSION['token']);
		} else {

			redirect('labour_group/insert?type=insert&success=false&nav_menu=labour_group&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'labour_group_id' => $_GET['labour_group_id']
		);
		$result_data = $this->db_query->table_select_where('labour_group',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'labour_group_id' => $data['labour_group_id'],
				'labour_group_name' => $data['labour_group_name']
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
			$load['title'] = 'Labour_group List';
			$this->load->view('labour_group_list',$load);
			return;
		}
		$column = array('labour_group.labour_group_name');
		$order = array('labour_group_id' => 'asc');
		$lists = $this->person->get_datatables2('labour_group',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->labour_group_id;
			$row[] = $list->labour_group_name;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('labour_group',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('labour_group',$column,$order),
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
			$load['title'] = 'Add Labour_group';
			$result_data = $this->db_query->table_select_where('labour_group',array('labour_group_id' => $_GET['labour_group_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'labour_group_id' => $res['labour_group_id'],
					'labour_group_name' => $res['labour_group_name']
				);
			}
			$this->load->view('add_labour_group',$load);
			return;
		}
		$where = array(
			'labour_group_id' => $_GET['labour_group_id']
		);
		$data = array(
			'labour_group_name' => $_POST['labour_group_name']
			); 

		$result['message'] = "Labour_group not update";
		if($this->db_query->table_update('labour_group',$data,$where) == true)
		{
			redirect('labour_group/get_all?type=update&success=true&nav_menu=labour_group&token='.$_SESSION['token']);
		} else {
			redirect('labour_group/update?type=update&success=false&labour_group_id='.$_GET['labour_group_id'].'&nav_menu=labour_group&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'labour_group_id' => $_GET['labour_group_id']
		);
		$result['message'] = "Labour_group not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('labour_group',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Labour_group deleted";
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
		$where = $_GET['labour_group_id'];
		$result['message'] = 'Labour_group not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('labour_group','labour_group_id',$where) == true) {
			$result['message'] = 'Labour_group deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}