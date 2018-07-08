<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Units extends CI_Controller
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
			$load['title'] = 'Add Units';
			$this->load->view('add_units',$load);
			return;
		}
		$data = array(
			'unit_name' => $_POST['unit_name']
		);

		$result['message'] = "Units not saved";
		if($this->db_query->table_insert('units',$data) == true)
		{
			redirect('units/get_all?type=insert&success=true&nav_menu=units&token='.$_SESSION['token']);
		} else {

			redirect('units/insert?type=insert&success=false&nav_menu=units&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'unit_id' => $_GET['unit_id']
		);
		$result_data = $this->db_query->table_select_where('units',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'unit_id' => $data['unit_id'],
				'unit_name' => $data['unit_name']
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
			$load['title'] = 'Units List';
			$this->load->view('units_list',$load);
			return;
		}
		$column = array('units.unit_name');
		$order = array('unit_id' => 'asc');
		$lists = $this->person->get_datatables2('units',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->unit_id;
			$row[] = $list->unit_name;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('units',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('units',$column,$order),
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
			$load['title'] = 'Add Units';
			$result_data = $this->db_query->table_select_where('units',array('unit_id' => $_GET['unit_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'unit_id' => $res['unit_id'],
					'unit_name' => $res['unit_name']
				);
			}
			$this->load->view('add_units',$load);
			return;
		}
		$where = array(
			'unit_id' => $_GET['unit_id']
		);
		$data = array(
			'unit_name' => $_POST['unit_name']
			); 

		$result['message'] = "Units not update";
		if($this->db_query->table_update('units',$data,$where) == true)
		{
			redirect('units/get_all?type=update&success=true&nav_menu=units&token='.$_SESSION['token']);
		} else {
			redirect('units/update?type=update&success=false&unit_id='.$_GET['unit_id'].'&nav_menu=units&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'unit_id' => $_GET['unit_id']
		);
		$result['message'] = "Units not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('units',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Units deleted";
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
		$where = $_GET['unit_id'];
		$result['message'] = 'Units not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('units','unit_id',$where) == true) {
			$result['message'] = 'Units deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}