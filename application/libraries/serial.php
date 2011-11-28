<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * rewrite from crv://sc_webcat/ecat_admin/crvmore_number_autogen_function.php
 * 
 */
class Serial {
		
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	function order($order_area)
	{
		$this->CI->db->from('tbl_inv');
		$this->CI->db->where('create_date >=', date('Y-m-01'));
		$nums_order = $this->CI->db->count_all_results();
		
		$this->CI->db->from('tbl_inv');
		$this->CI->db->where('create_date >=', date('Y-m-01'));
		$this->CI->db->order_by('inv_date', 'desc');
		$last_order = $this->CI->db->get()->row_object()->inv_no;
		
		// start of chaos
		$last_order = substr($last_order, 9, 2).substr($last_order, 12, 2).substr($last_order, 15, 2);
		
		$new_number = $nums_order + 1; // next order(?)
		
		if ($last_order >= $new_number)
		{
			$new_number = $last_order + 1;
		}
		
		$new_number = str_pad($new_number, 6, '0', STR_PAD_LEFT);
		
		$rand_num = rand(1000, 9999);
		
		$inv_no = "ORD" . $order_area . date("ym");
		$inv_no.= substr($new_number, 0, 2) . substr($rand_num, 0, 1);
		$inv_no.= substr($new_number, 2, -2) . substr($rand_num, 1, -2);
		$inv_no.= substr($new_number, -2) . date("d"). substr($rand_num, -2);
		
		return $inv_no;
	}

	function product()
	{
		
	}
	
	function member()
	{
		
	}
}