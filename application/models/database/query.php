<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Model {

	public function index()
	{
		//$this->load->view('welcome_message');
	}
	public function table_insert($table_name,$data)
	{
		if($this->db->insert($table_name,$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function table_select($table_name,$limit = null,$start = null,$order_column_name = null,$order_by = null)
	{
		if($limit != null && $offset != null)
		{
			$this->db->limit($limit, $offset);
		}
		if($order_column_name != null && $order_by != null)
		{
			$this->db->order_by($order_column_name, $order_by);
		}
		$result = $this->db->get($table_name);
		return $result->result_array();
	}
	public function table_select_where($table_name,$where,$limit = null,$start = null,$order_column_name = null,$order_by = null)
	{
		if($limit != null && $offset != null)
		{
			$this->db->limit($limit, $offset);
		}
		if($order_column_name != null && $order_by != null)
		{
			$this->db->order_by($order_column_name, $order_by);
		}
		$result = $this->db->get_where($table_name,$where);
		return $result->result_array();
	}
	public function table_select_limit($table_name,$where,$limit = 10,$start = 1)
	{
		$this->db->limit($limit,$start);
		$result = $this->db->get_where($table_name,$where);
		return $result->result_array();
	}
	public function table_select_join($table_name1,$table_name2,$where,$field_name,$limit = null,$start = null,$order_column_name = null,$order_by = null,$select = null)
	{
		if($select == null) {
			$select = '*';
		}
		$this->db->select($select);
		$this->db->from($table_name1);
		$this->db->where($where);
		$this->db->join($table_name2,$table_name2.'.'.$field_name.'='.$table_name1.'.'.$field_name);
		if($limit != null && $offset != null)
		{
			$this->db->limit($limit, $offset);
		}
		if($order_column_name != null && $order_by != null)
		{
			$this->db->order_by($order_column_name, $order_by);
		}
		$result = $this->db->get();
		return $result->result_array();
		
	}
	public function table_update($table_name,$data,$where)
	{
		$this->db->where($where);
		if($this->db->update($table_name,$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function table_delete($table_name,$where)
	{
		if($this->db->delete($table_name,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function table_delete_all($table_name,$field,$ids)
	{
		if($this->db->query("delete from ".$table_name." where ".$field." IN(".$ids.")")) {
			return true;
		} else {
			return false;
		}
	}
	
	public function select_orderby($table_name,$columnname, $order)
	{
		$this->db->order_by($columnname, $order); 
		$result = $this->db->get($table_name);
		return $result->result_array();
	}
	public function select_where_orderby($table_name,$where,$columnname, $order)
	{
		$this->db->order_by($columnname, $order); 
		$result = $this->db->get_where($table_name,$where);
		return $result->result_array();
	}
	public function get_scalar_value($query,$column_name)
	{
		$result = $this->db->query($query);
		$result = $result->result_array();
		if(isset($result[0][$column_name]))
		{
			return $result[0][$column_name];
		}
		return false;
	}
	public function update_delete_field($table_name,$column_name,$ids)
	{
		$this->db->query("update ".$table_name." SET is_delete=1 where ".$column_name." IN(".$ids.")");
		return true;
	}
	public function update_employee_id($table_name,$column_name,$where_column_name,$ids)
	{
		$this->db->query("update ".$table_name." SET ".$column_name."=".$_SESSION['employee_id']." where ".$where_column_name." IN(".$ids.")");
		return true;
	}
	public function update_chalan_status_id($table_name,$column_name,$status,$where_column_name,$ids)
	{
		$this->db->query("update ".$table_name." SET ".$column_name."=".$status." where ".$where_column_name." IN(".$ids.")");
		return true;
	}
	public function custom_query($query)
	{
		$result = $this->db->query($query);
		$result = $result->result_array();
		return $result;
	}
	
	public function custom_boolean_query($query)
	{
		$result = $this->db->query($query);
		return $result;
	}
	public function count_total($column,$where,$table_name)
	{
		/*
			$this->db->like('title', 'match');
			$this->db->from('my_table');
			echo $this->db->count_all_results();
		*/
		$this->db->like($column, $where);
		$this->db->from($table_name);
		$total = $this->db->count_all_results();
		return $total;
	}
	public function time_ago( $date )
	{

		if( empty( $date ) )
		{
		return "No date provided";
		}

		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();

		$unix_date = strtotime( $date );

		// check validity of date

		if( empty( $unix_date ) )
		{
		return "Bad date";
		}

		// is it future date or past date

		if( $now > $unix_date )
		{
		$difference = $now - $unix_date;
		$tense = "ago";
		}
		else
		{
		$difference = $unix_date - $now;
		$tense = "from now";
		}

		for( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ )
		{
		$difference /= $lengths[$j];
		}

		$difference = round( $difference );

		if( $difference != 1 )
		{
		$periods[$j].= "s";
		}

		return "$difference $periods[$j] {$tense}";

	}
}
