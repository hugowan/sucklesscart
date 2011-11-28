<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stat extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$this->load->library('table');

		// $sql2 = "
				// SELECT COUNT(cust_id) AS count, inv_no, cust_id
				// FROM tbl_inv
				// WHERE inv_no IN ($t)
				// AND STATUS IN ( 100 )
				// AND create_date >= '2010-08-01 00:00:00'
				// AND create_date <= '2011-07-31 23:59:59'
				// AND subtotal > 0
				// GROUP BY cust_id
				// -- GROUP BY inv_no
				// HAVING count >= 9 AND count <= 12
				// ";

// SELECT i.*, d.supplier_id
// FROM tbl_inv i
// LEFT JOIN tbl_trans_detail d ON i.inv_no = d.inv_no
// WHERE i.cust_id = '1974'
// AND i.status IN ( 100 )
// AND i.create_date >= '2010-08-01 00:00:00'
// AND i.create_date <= '2011-07-31 23:59:59'
// AND d.supplier_id != '96'
// AND d.inv_no IS NOT NULL 
// AND d.create_date >= '2010-08-01 00:00:00'
// AND d.create_date <= '2011-07-31 23:59:59'
// AND i.subtotal > 0
// GROUP BY d.inv_no

		// $query = $this->db->query($sql2);
		// echo $this->table->generate($query);
		// echo '<pre>';
		// print_r($query->result_array());
		// echo '</pre>';

			
		$trans_sql = "SELECT inv_no
					FROM tbl_trans_detail
					WHERE supplier_id = '96'
					AND session_id IS NULL
					AND inv_no IS NOT NULL
					-- AND create_date >= '2010-08-01 00:00:00'
					-- AND create_date <= '2011-07-31 23:59:59'
					AND log_datetime >= '2010-08-01 00:00:00'
					AND log_datetime <= '2011-07-31 23:59:59'
					AND status = '100'
					GROUP BY inv_no";
		$trans = $this->db->query($trans_sql)->result_array();
		
		$t = '"' .implode('","', array_map('implode', array_fill(0, count($trans), ','), $trans)) . '"';
		
		
		
		$sql = "SELECT count AS times, COUNT(count) AS count, SUM(total) AS total
				FROM (
				SELECT cust_id, COUNT(cust_id) AS count, SUM(subtotal) AS total
				FROM tbl_inv
				WHERE inv_no IN ($t)
				-- AND STATUS IN ( 99 )
				-- AND create_date >= '2010-08-01 00:00:00'
				-- AND create_date <= '2011-07-31 23:59:59'
				AND subtotal > 0
				GROUP BY cust_id
				) AS tmp
				GROUP BY count";
		

		// exit();
		$data['result'] = $this->db->query($sql)->result_array();
		$this->load->view('tools/stat', $data);

	}
}

           
   


