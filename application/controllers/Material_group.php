<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Material_group extends CI_Controller
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
			$load['title'] = 'Add Material_group';
			$this->load->view('add_material_group',$load);
			return;
		}
		$data = array(
			'material_group_name' => $_POST['material_group_name']
		);

		$result['message'] = "Material_group not saved";
		if($this->db_query->table_insert('material_group',$data) == true)
		{
			redirect('material_group/get_all?type=insert&success=true&nav_menu=material_group&token='.$_SESSION['token']);
		} else {

			redirect('material_group/insert?type=insert&success=false&nav_menu=material_group&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'material_group_id' => $_GET['material_group_id']
		);
		$result_data = $this->db_query->table_select_where('material_group',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'material_group_id' => $data['material_group_id'],
				'material_group_name' => $data['material_group_name']
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
			$load['title'] = 'Material_group List';
			$this->load->view('material_group_list',$load);
			return;
		}
		$column = array('material_group.material_group_name');
		$order = array('material_group_id' => 'asc');
		$lists = $this->person->get_datatables2('material_group',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->material_group_id;
			$row[] = $list->material_group_name;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('material_group',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('material_group',$column,$order),
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
			$load['title'] = 'Add Material_group';
			$result_data = $this->db_query->table_select_where('material_group',array('material_group_id' => $_GET['material_group_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'material_group_id' => $res['material_group_id'],
					'material_group_name' => $res['material_group_name']
				);
			}
			$this->load->view('add_material_group',$load);
			return;
		}
		$where = array(
			'material_group_id' => $_GET['material_group_id']
		);
		$data = array(
			'material_group_name' => $_POST['material_group_name']
			); 

		$result['message'] = "Material_group not update";
		if($this->db_query->table_update('material_group',$data,$where) == true)
		{
			redirect('material_group/get_all?type=update&success=true&nav_menu=material_group&token='.$_SESSION['token']);
		} else {
			redirect('material_group/update?type=update&success=false&material_group_id='.$_GET['material_group_id'].'&nav_menu=material_group&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'material_group_id' => $_GET['material_group_id']
		);
		$result['message'] = "Material_group not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('material_group',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Material_group deleted";
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
		$where = $_GET['material_group_id'];
		$result['message'] = 'Material_group not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('material_group','material_group_id',$where) == true) {
			$result['message'] = 'Material_group deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}