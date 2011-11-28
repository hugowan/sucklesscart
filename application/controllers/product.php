<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends FE_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('product_model');
	}
	
	function index()
	{
		
	}
	
	function detail($prd_id)
	{
		$prd_id = (int)$prd_id;
			
		if (empty($prd_id))
		{
		    redirect('home');
			eixt();
		}
		
		$product = $this->product_model->find_products_by_pid($prd_id, NULL, 1);
		
		if (count($product) < 1)
		{
		    redirect('home');
			eixt();
		}
		
		$data['product']	= $product[0];
		$data['navi']		= $this->category_model->navigation($data['product']['cat_id']);
		$current_category	= end($data['navi']);
		$data['title']		= $current_category['cat_name'];
		
		// layout output
		$head['title'] = $data['product']['product_name'];
		$body_right = $this->load->view(CURR_TEMPLATE . '/product_detail', $data, TRUE);
		$this->layout->set_element('head', $head);
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();		
	}
}