<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equipment_group extends CI_Controller
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
			$load['title'] = 'Add Equipment_group';
			$this->load->view('add_equipment_group',$load);
			return;
		}
		$data = array(
			'equipment_group_name' => $_POST['equipment_group_name']
		);

		$result['message'] = "Equipment_group not saved";
		if($this->db_query->table_insert('equipment_group',$data) == true)
		{
			redirect('equipment_group/get_all?type=insert&success=true&nav_menu=equipment_group&token='.$_SESSION['token']);
		} else {

			redirect('equipment_group/insert?type=insert&success=false&nav_menu=equipment_group&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'equipment_group_id' => $_GET['equipment_group_id']
		);
		$result_data = $this->db_query->table_select_where('equipment_group',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'equipment_group_id' => $data['equipment_group_id'],
				'equipment_group_name' => $data['equipment_group_name']
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
			$load['title'] = 'Equipment_group List';
			$this->load->view('equipment_group_list',$load);
			return;
		}
		$column = array('equipment_group.equipment_group_name');
		$order = array('equipment_group_id' => 'asc');
		$lists = $this->person->get_datatables2('equipment_group',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->equipment_group_id;
			$row[] = $list->equipment_group_name;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('equipment_group',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('equipment_group',$column,$order),
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
			$load['title'] = 'Add Equipment_group';
			$result_data = $this->db_query->table_select_where('equipment_group',array('equipment_group_id' => $_GET['equipment_group_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'equipment_group_id' => $res['equipment_group_id'],
					'equipment_group_name' => $res['equipment_group_name']
				);
			}
			$this->load->view('add_equipment_group',$load);
			return;
		}
		$where = array(
			'equipment_group_id' => $_GET['equipment_group_id']
		);
		$data = array(
			'equipment_group_name' => $_POST['equipment_group_name']
			); 

		$result['message'] = "Equipment_group not update";
		if($this->db_query->table_update('equipment_group',$data,$where) == true)
		{
			redirect('equipment_group/get_all?type=update&success=true&nav_menu=equipment_group&token='.$_SESSION['token']);
		} else {
			redirect('equipment_group/update?type=update&success=false&equipment_group_id='.$_GET['equipment_group_id'].'&nav_menu=equipment_group&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'equipment_group_id' => $_GET['equipment_group_id']
		);
		$result['message'] = "Equipment_group not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('equipment_group',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Equipment_group deleted";
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
		$where = $_GET['equipment_group_id'];
		$result['message'] = 'Equipment_group not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('equipment_group','equipment_group_id',$where) == true) {
			$result['message'] = 'Equipment_group deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}