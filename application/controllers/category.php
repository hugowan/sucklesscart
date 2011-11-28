<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends FE_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('product_model');
	}
	
	function index()
	{
		redirect('category/browse');
	}
	
	function browse()
	{
		$segments = $this->uri->uri_to_assoc(4);
		
		if ( ! empty($segments['cat_id']))
		{
		    $cat_id = (int)$segments['cat_id'];
		}
		else
		{
			redirect('home');
		}

		if ( ! empty($segments['page']) && (int)$segments['page'] > 0)
		{
            $page_offset = (int)$segments['page'];
        }
        else
        {
            $page_offset = 0;
        }
		
		// for sorting, searching...
		$options = array();
		
		// query data
		$category_ids		= $this->category_model->find_sub_cat_id($cat_id); // find related category ids
		$data['products']	= $this->product_model->find_products_by_cid($category_ids, $options, PRD_DISPLAY_LIMIT, $page_offset); // return related category products
		$data['total']		= $this->product_model->count_products_by_cid($category_ids, $options);
		$data['navi']		= $this->category_model->navigation($cat_id);
		$current_category	= end($data['navi']);
		$data['title']		= $current_category['cat_name'];
		
		// pagination config
		$this->config->set_item('enable_query_strings', FALSE); // make sure query string is disable
        $config					= $this->config->item('pagination');
        $config['base_url']		= site_url('category/browse/cat_id/' . $cat_id . '/page');
		$config['uri_segment']	= '6';
		$config['per_page']		= PRD_DISPLAY_LIMIT;
		$config['total_rows']	= $data['total'];
		$this->pagination->initialize($config); 
		$data['pagination'] = $this->pagination->create_links();
		
		// layout output
		$head['title'] = '';
		$body_right	= $this->load->view(CURR_TEMPLATE . '/product_browse', $data, TRUE);
		$this->layout->set_element('head', $head);
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();
	}
}