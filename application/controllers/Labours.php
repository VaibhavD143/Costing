<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labours extends CI_Controller
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
			$load['title'] = 'Add Labours';
			$results=$this->db_query->table_select('labour_group');
			foreach($results as $result)
			{
				$load['labour_groups'][]=$result;
			}
			$results=$this->db_query->table_select('units');
			foreach($results as $result)
			{
				$load['units'][]=$result;
			}
			$this->load->view('add_labours',$load);
			return;
		}
		$data = array(
			'labour_group_id' => $_POST['labour_group_id'],
			'unit_id' => $_POST['unit_id'],
			'labour_name' => $_POST['labour_name'],
			'labour_rate' => $_POST['labour_rate']
		);

		$result['message'] = "Labours not saved";
		if($this->db_query->table_insert('labours',$data) == true)
		{
			redirect('labours/get_all?type=insert&success=true&nav_menu=labours&token='.$_SESSION['token']);
		} else {

			redirect('labours/insert?type=insert&success=false&nav_menu=labours&token='.$_SESSION['token']);
		}

	}

	public function get_by_id()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'labour_id' => $_GET['labour_id']
		);
		$result_data = $this->db_query->table_select_where('labours',$where);
		foreach($result_data as $data) {
			$result['data'][] = array(
				'labour_id' => $data['labour_id'],
				'labour_group_id' => $data['labour_group_id'],
				'unit_id' => $data['unit_id'],
				'labour_name' => $data['labour_name'],
				'labour_rate' => $data['labour_rate']
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
			$load['title'] = 'Labours List';
			$this->load->view('labours_list',$load);
			return;
		}
		$column = array('labours.labour_group_id','labours.unit_id','labours.labour_name','labours.labour_rate');
		$order = array('labour_id' => 'asc');
		$lists = $this->person->get_datatables2('labours',$column,$order);
		$data = array();
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = array();
			$row[] = $list->labour_id;
			$row[] = $list->labour_group_id;
			$row[] = $list->unit_id;
			$row[] = $list->labour_name;
			$row[] = $list->labour_rate;
			$data[] = $row;
		}
		$output = array(
						'draw' => $_POST['draw'],
						'recordsTotal' => $this->person->count_all('labours',$column,$order),
						'recordsFiltered' => $this->person->count_filtered('labours',$column,$order),
						'data' => $data,
				);
		echo json_encode($output);
	}
	
	public function update_task_labour_price($labour_id,$labour_rate)
	{
		$sql="SELECT task_id FROM task_labour WHERE labour_id='".$labour_id."'";
		$task_ids=$this->db_query->custom_query($sql);
		if($task_ids != NULL)
		{
			// echo "in";
			// echo $task_ids[0]['task_id'];
			$sql="UPDATE task_labour SET task_labour_total_cost=`labour_quantity`*'".(int)$labour_rate."' WHERE labour_id='".$labour_id."'";
			if($this->db_query->custom_boolean_query($sql))
			{
				foreach($task_ids as $task_id)
				{
					// echo $task_id['task_id'];
					$sql='SELECT sum(task_labour_total_cost) AS tot FROM task_labour WHERE task_id = "'.$task_id['task_id'].'"';
					$task_labour_total_cost=$this->db_query->get_scalar_value($sql,"tot");
					if($task_labour_total_cost == "")
					{
						$task_labour_total_cost=0;
					}
					$sql='UPDATE `tasks` SET `labour_cost` = "'.$task_labour_total_cost.'"  WHERE `tasks`.`task_id` = "'.$task_id['task_id'].'"';
					if($this->db_query->custom_boolean_query($sql))
					{
						$sql='UPDATE tasks SET `total_cost` = `material_cost`+`equipment_cost`+`labour_cost` WHERE task_id="'.$task_id['task_id'].'"';
						if($this->db_query->custom_boolean_query($sql))
						{
							$total_cost=$this->db_query->get_scalar_value('SELECT `total_cost` FROM tasks WHERE task_id="'.$task_id['task_id'].'"','total_cost');
							$sql='UPDATE project_task SET `task_cost` = `task_area`*'.(int)$total_cost.' WHERE task_id="'.$task_id['task_id'].'"';
								if(!$this->db_query->custom_boolean_query($sql))
								{
									return $res;
								}
						}
						else
						{
							return "Couldn't update total_cost in tasks where task_id=".$task_id['task_id'];
						}
					}
					else
					{
						return "couldn't update labour_cost in tasks where task_id=".$task_id['task_id'];
					}
				}
			
			}
			else
			{
				return "Coudn't update task_labour_total_cost";
			}
		}
		else
		{
			return "NULL task_ids";
		}
		
		
		
		
		
	}
	
	
	public function update()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		if(!isset($_POST['btnsubmit'])) {
			$load['title'] = 'Add Labours';
			$result_data = $this->db_query->table_select_where('labours',array('labour_id' => $_GET['labour_id']));
			foreach($result_data as $res) {
				$load['main_data'] = array(
					'labour_id' => $res['labour_id'],
					'labour_group_id' => $res['labour_group_id'],
					'unit_id' => $res['unit_id'],
					'labour_name' => $res['labour_name'],
					'labour_rate' => $res['labour_rate']
				);
			}
			$results=$this->db_query->table_select('labour_group');
			foreach($results as $result)
			{
				$load['labour_groups'][]=$result;
			}
			$results=$this->db_query->table_select('units');
			foreach($results as $result)
			{
				$load['units'][]=$result;
			}
			$this->load->view('add_labours',$load);
			return;
		}
		$where = array(
			'labour_id' => $_GET['labour_id']
		);
		$labour_rate=$_POST['labour_rate'];
		$labour_id=$_GET['labour_id'];
		$data = array(
			'labour_group_id' => $_POST['labour_group_id'],
			'unit_id' => $_POST['unit_id'],
			'labour_name' => $_POST['labour_name'],
			'labour_rate' => $_POST['labour_rate']
			); 

		$result['message'] = "Labours not update";
		if($this->db_query->table_update('labours',$data,$where) == true)
		{
			$result['message2']=$this->update_task_labour_price($labour_id,$labour_rate);
			redirect('labours/get_all?type=update&success=true&nav_menu=labours&token='.$_SESSION['token']);
		} else {
			redirect('labours/update?type=update&success=false&labour_id='.$_GET['labour_id'].'&nav_menu=labours&token='.$_SESSION['token']);
		}
	}

	public function delete()
	{
		if(!isset($_SESSION['token']) || !isset($_GET['token']) || $_GET['token'] != $_SESSION['token']) {
			redirect('admin');
			return;
		}
		$where = array(
			'labour_id' => $_GET['labour_id']
		);
		$result['message'] = "Labours not deleted";
		$result['success'] = false;
		if($this->db_query->table_delete('labours',$where) == true)
		{
			$result['success'] = true;
			$result['message'] = "Labours deleted";
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
		$where = $_GET['labour_id'];
		$result['message'] = 'Labours not deleted';
		$result['success'] = false;
		if($this->db_query->table_delete_all('labours','labour_id',$where) == true) {
			$result['message'] = 'Labours deleted';
			$result['success'] = true;
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}