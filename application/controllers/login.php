<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
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

	public function index()
	{
		if(isset($_SESSION['token']) && isset($_GET['token']) && $_GET['token'] == $_SESSION['token']) {
			redirect('login/dashboard?token='.$_SESSION['token']);
		} else {
			$data['projects']=$this->db_query->table_select('projects');
			$this->load->view('login',$data);
			return;
			
			redirect('login/dashboard2?token='.$randomString);
		}
	}
	public function login()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 20; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$_SESSION['token'] = $randomString;
		$data=explode(',',$_POST['project']);
		$_SESSION['project_id'] = $data[0];
		$_SESSION['project_name'] = $data[1];
		//die('fgadf');
		$this->load->view('units_list');
		return;
	}
	
	public function dashboard2() {
		
		$this->load->view('units_list');
	}
	
	public function logout()
	{
		session_destroy();
		$this->index();
	}
}