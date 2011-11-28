<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends FE_Controller {
	
	private $search_type = array('quick', 'advance');
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('product_model');
		$this->load->model('log_model');
	}
	
	function index()
	{
		$keyword	= $this->input->post('keyword');
		$category	= $this->input->post('category');
		$brand		= $this->input->post('brand');
		$price		= $this->input->post('price');
		
		if( ! empty($keyword))
		{
			$category	= ( ! empty($category)) ? $category : 0;
			$brand		= ( ! empty($brand)) ? $brand : 0;
			$price		= ( ! empty($price)) ? $price : 0;
			redirect('search/result/' . $keyword . '/' . $category . '/' . $brand . '/' . $price);
		}
	}
	
	function advance()
	{
		$body_right	= $this->load->view(CURR_TEMPLATE . '/product_adv_srch', NULL, TRUE);
		$this->layout->set_element('head', array('title' => ''));
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();
	}
	
	function result($keyword = NULL, $category = 0, $brand = 0, $price = 0)
	{
		$keyword	= $this->_filter($keyword);
		$category	= (int)$category;
		$brand		= $this->_filter($brand);
		$price		= $this->_filter($price);
		$page_offset= $this->uri->segment(8);
		$options	= array();
		
		if ( empty($keyword))
		{
			redirect("search/advance/");
		}
		
		if ( ! empty($brand))
		{
			$options['advance']['brand_name'] = $brand;
		}
		
		if ( ! empty($price))
		{
			$price = explode('-', $price);
			
			if(is_array($price))
			{
				$options['advance']['price']['price_min'] = (int)$price[0];
				$options['advance']['price']['price_max'] = (int)$price[1];
			}
		}
		
		$product_ids = $this->product_model->find_products_by_pname($keyword);
		
		if ( ! empty($category_id))
		{
			$related_cat_ids = $this->category_model->find_sub_cat_id($cat_id);
			$related_prds = $this->product_model->find_products_by_cid($related_cat_ids);
			
			foreach($related_prds as $key => $val)
			{
				$related_prd_ids[] = $val['id'];
			}
			
			$product_ids = array_intersect($product_ids, $related_prd_ids);
		}
		
		$data['products']	= $this->product_model->find_products_by_pid($product_ids, $options, PRD_DISPLAY_LIMIT, $page_offset);
		$data['total']		= $this->product_model->count_products_by_pid($product_ids, $options);
		$data['title']		= $keyword;

		// pagination config
		$this->config->set_item('enable_query_strings', FALSE);
        $config					= $this->config->item('pagination');
        $config['base_url']		= site_url('search/result/' . $keyword . '/' . $category . '/' . $brand . '/' . $price . '/page');
		$config['uri_segment']	= '8';
		$config['per_page']		= PRD_DISPLAY_LIMIT;
		$config['total_rows']	= $data['total'];
		$this->pagination->initialize($config); 
		$data['pagination'] = $this->pagination->create_links();
		
		// layout output
		$head['title'] = '';
		$body_right	= $this->load->view(CURR_TEMPLATE . '/product_browse', $data, TRUE);
		$this->layout->set_element('head', array('title' => ''));
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();
	}
	
	function _filter($string)
	{
		return urldecode(strip_tags($string));
	}
}