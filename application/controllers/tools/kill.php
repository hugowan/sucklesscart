<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kill extends CI_Controller {

    function __construct()
    {
		parent::__construct();
		$this->load->library('table');
    }
	
	function index()
	{
		$pool = array(
					40046622,
					40076023,
					40062141,
					40432858,
					40087614,
					40419642,
					30073580,
					30062612,
					20077826,
					40060415,
					40070636,
					40432906,
					40167170,
					40340102,
					40076045,
					40161152,
					60315317,
					40117290,
					40124522,
					40240411,
					40050984,
					40240433,
					40332433,
					40060002,
					40454775,
					30293016,
					30387856,
					30465273,
					40445016,
					40063485,
					40135401,
					40062680,
					40454742,
				);
		
		$i = 0;

		
		foreach($pool as $v)
		{
			$output[$v]['excel_plu'] = $v;
			
			$result = $this->db->query("SELECT model_number, product_no, product_name, price, status FROM tbl_product WHERE model_number = '{$v}'");
		
			if($result->num_rows < 1)
			{
				$result = $this->db->query("SELECT model_number, product_no, product_name, price, status FROM tbl_product WHERE model_number LIKE '%{$v}'");
			}
			
			if($result->num_rows > 0)
			{
				$output[$v]['db_plu'] = $result->row_object()->model_number;
				$output[$v]['product_no'] = $result->row_object()->product_no;
				$output[$v]['product_name'] = $result->row_object()->product_name;
				$output[$v]['price'] = $result->row_object()->price;
			}
			else
			{
				// $output[$i] = '沒有相符的資料';
			}
			
			$i++;
		}

		// echo '<pre>';
		// print_r($output);
		// echo '</pre>';

		$this->table->set_heading('Excel PLU', 'DB PLU', 'Product No', 'Product Name', 'Price', 'Status');
		$data['table'] = $this->table->generate($output);
		$this->load->view('temp/price', $data);
	}
}