<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
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

	public function report()
	{
		// echo "dlfhk";
		// die();
		$view['project_tasks']=$this->db_query->table_select_where('project_task',array('project_id' => $_SESSION['project_id']));
		$view['tasks']=array();
		$view['tasks_name']=array();
		$view['project_cost']=0;
		
		for($i=0;$i<(int)count($view['project_tasks']);$i++)
		{
			$view['project_cost']+=(int)$view['project_tasks'][$i]['task_cost'];
			$view['task_detail'][]=$view['project_tasks'][$i];
			$view['tasks'][$i]['task_material']=$this->db_query->table_select_join('task_material','materials',array('task_id' => $view['project_tasks'][$i]['task_id']),'material_id');
			$view['tasks'][$i]['task_labour']=$this->db_query->table_select_join('task_labour','labours',array('task_id' => $view['project_tasks'][$i]['task_id']),'labour_id');
			$view['tasks'][$i]['task_equipment']=$this->db_query->table_select_join('task_equipment','equipments',array('task_id' => $view['project_tasks'][$i]['task_id']),'equipment_id');
			// $view['total_materials']=$this->db_query->custom_query('SELECT * FROM project_task as p,task_material as x,materials as m WHERE p.task_id=x.task_id and p.project_id='.$_SESSION['project_id'].' and m.material_id = x.material_id');
			$view['total_materials']=$this->db_query->custom_query('SELECT tm.material_id,SUM(tm.task_material_cost*p.task_area) as task_material_cost,SUM(tm.material_quantity*p.task_area) as total_quantity,SUM(tm.task_transport_cost*p.task_area) as task_transport_cost,SUM(tm.task_material_total_cost*p.task_area) as task_material_total_cost,m.material_name,m.material_rate,m.material_transport_cost,m.unit_id,u.unit_name FROM project_task as p,task_material as tm,materials as m,units as u WHERE u.unit_id=m.unit_id and p.task_id=tm.task_id and p.project_id='.$_SESSION['project_id'].' and m.material_id=tm.material_id GROUP BY material_id');
			$view['total_labours']=$this->db_query->custom_query('SELECT tl.labour_id,SUM(tl.labour_quantity*p.task_area) as total_quantity,SUM(tl.task_labour_total_cost*p.task_area) as task_labour_total_cost,l.labour_name,l.labour_rate,l.unit_id,u.unit_name FROM project_task as p,task_labour as tl,labours as l,units as u WHERE u.unit_id=l.unit_id and p.task_id=tl.task_id and p.project_id='.$_SESSION['project_id'].' and l.labour_id=tl.labour_id GROUP BY labour_id');
			$view['total_equipments']=$this->db_query->custom_query('SELECT te.equipment_id,SUM(te.task_equipment_cost*p.task_area) as task_equipment_cost,SUM(te.equipment_quantity*p.task_area) as total_quantity,SUM(te.task_equipment_run_cost*p.task_area) as task_equipment_run_cost,SUM(te.task_equipment_total_cost*p.task_area) as task_equipment_total_cost,e.equipment_name,e.equipment_rate,e.equipment_run_cost,e.unit_id,u.unit_name FROM project_task as p,task_equipment as te,equipments as e,units as u WHERE u.unit_id=e.unit_id and p.task_id=te.task_id and p.project_id='.$_SESSION['project_id'].' and e.equipment_id=te.equipment_id GROUP BY equipment_id');
		}
		$this->load->view('reports',$view);
	}
}