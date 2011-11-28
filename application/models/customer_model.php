<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model {
	
	function __construct()
	{
		$this->load->library('encrypt');
	}
	
	function load($cust_id)
	{
		if ( ! $cust_id)
		{
			return array();
		}
		
		$query = $this->db->get_where('tbl_customer', array('cust_id' => $cust_id));
		
		if ($row = $query->row_array())
		{
			return $row;
		}
		
		return array();
	}
	
	function check_customer($login, $password)
	{
		$customer = $this->db->get_where('tbl_customer', array('login_name' => $login, 'status' => '1'))->row_array();
		
		if (count($customer) > 0)
		{
			if($customer['cust_password'] == $this->encrypt->customer_encrypt($password))
			{
				return $customer;
			}
		}
		
		return FALSE;
	}
}