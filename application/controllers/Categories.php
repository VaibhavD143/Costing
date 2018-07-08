<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller
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
			$load['title'] = 'Add Categories';
			$this->load->view('add_categories',$load);
			return;
		}
		$data = array(
			'category_name' => $_POST['category_name']
		);

		$result['message'] = "Categories not saved";
		if($this->db_query->table_insert('categories',$data) == true)
		{
			redirect('categories/get_all?type=insert&nav_menu=categories&success=true&token='.$_SESSION['token']);
		} else {

			redirect('categories/insert?type=insert&nav_menu=categories&success=false&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'category_id' => $_GET['category_id']
		);
		$result_data = $this->db_query->table_select_where('categories',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'category_id' => $data['category_id'],
				'category_name' => $data['category_name']
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
			$load['title'] = 'Categories List';
			$this->load->view('categories_list',$load);
			return;
		}
		$column = array('categories.category_name');
		$order = array('category_id' => 'asc');
		$lists = $this->person->get_datatables2('categories',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->category_id;
			$row[] = $list->category_name;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('categories',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('categories',$column,$order),
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
			$load['title'] = 'Add Categories';
			$result_data = $this->db_query->table_select_where('categories',array('category_id' => $_GET['category_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'category_id' => $res['category_id'],
					'category_name' => $res['category_name']
				);
			}
			$this->load->view('add_categories',$load);
			return;
		}
		$where = array(
			'category_id' => $_GET['category_id']
		);
		$data = array(
			'category_name' => $_POST['category_name']
			); 

		$result['message'] = "Categories not update";
		if($this->db_query->table_update('categories',$data,$where) == true)
		{
			redirect('categories/get_all?type=update&success=true&nav_menu=categories&token='.$_SESSION['token']);
		} else {
			redirect('categories/update?type=update&success=false&category_id='.$_GET['category_id'].'&nav_menu=categories&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'category_id' => $_GET['category_id']
		);
		$result['message'] = "Categories not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('categories',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Categories deleted";
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
		$where = $_GET['category_id'];
		$result['message'] = 'Categories not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('categories','category_id',$where) == true) {
			$result['message'] = 'Categories deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}