<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends FE_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
	}
	
	function index()
	{
		$this->front_page();
	}
		
	function front_page()
	{
		$data['feature_product'] = $this->product_model->feature_products('4');

		$body_right	= $this->load->view(CURR_TEMPLATE . '/home_index', $data, TRUE);

		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();
		
		// $body['body_right'] = $this->load->view(CURR_TEMPLATE . '/main_index', NULL, TRUE);
		// $data['header']	= $this->load->view(CURR_TEMPLATE . '/layout/frame_header', NULL, TRUE);
		// $data['body']	= $this->load->view(CURR_TEMPLATE . '/layout/frame_body', $body, TRUE);
		// $this->load->view(CURR_TEMPLATE . '/layout/frame', $data);
	}
}