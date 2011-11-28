<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_dump extends CI_Controller {

	function __constructor()
	{
		parent::__construct();
	}
	
	function index($cate_id = NULL)
	{
		$this->load->model('Category_model', 'category');
		$this->load->helper('download');
		
		$category = $this->db->query("SELECT * FROM tbl_category")->result_array();
		$child_cate = $this->category->categorySort($category, 1375);
		$child_cate_id = $this->category->catchChildId($child_cate);
		$child_cate_id = implode(',', $child_cate_id);		
	
		
		$product_id = $this->db->query("SELECT product_id FROM tbl_product_browse WHERE cat_id IN ($child_cate_id) GROUP BY product_id")->result_array();
		$product_id = implode(',' ,array_map('implode', array_fill(0, count($product_id), ','), $product_id));

		$product_info = $this->db->query("SELECT id, cat_id, product_unique_key, product_name, model_number FROM tbl_product WHERE status = '1' AND id IN ($product_id)")->result_array();
		
		$this->export($product_info, 'export');
	}
	
	function export($data, $filename)
	{
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/iofactory');
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("title")
		            ->setDescription("description");
		
		// Set properties
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
							               
		// Assign cell values		
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()
					->setCellValue('A1', 'id')
					->setCellValue('B1', 'cat_id')
					->setCellValue('C1', 'product_unique_key')
					->setCellValue('D1', 'product_name')
					->setCellValue('E1', 'model_number')
					->setCellValue('F1', 'original_model_number');
		$row = 2;
		foreach($data as $v)
		{
			$product_name = str_replace($v['model_number'], '', $v['product_name']);
			$model_number = preg_replace('/crv_/', '', $v['model_number']);
				
			$objPHPExcel->getActiveSheet()
						->setCellValue('A'.$row, $v['id'])		
						->setCellValue('B'.$row, $v['cat_id'])
						->setCellValue('C'.$row, $v['product_unique_key'])
						->setCellValue('D'.$row, $product_name)
						->setCellValue('E'.$row, $model_number)
						->setCellValue('F'.$row, $v['model_number']);
			$row++;			
		}
		
		// Save it as an excel 2003 file
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="export.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		// $objWriter->save('nameoffile.xls'); 
	}
}

/* End of file category.php */
/* Location: ./application/controllers/category.php */