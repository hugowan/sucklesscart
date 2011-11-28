<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends FE_Controller {
	
	private $cust_id;
	private $cust_group_id;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('coupon_model');
		$this->lang->load('cart', $this->lang->lang());

		// logged in
		if ($this->session->userdata('cust_id'))
		{
			$this->cust_id = $this->session->userdata('cust_id');
			$this->cust_group_id = $this->session->userdata('cust_group_id');
		}
	}
	
	/**
	 * display cart details
	 * 
	 * 
	 */
	function index()
	{
		if ( ! empty($this->cust_id))
		{
			// customer owned coupon(s)
			$this->coupon_model->cust_id = $this->cust_id;
			$this->coupon_model->cust_group_id = $this->cust_group_id;
			$data['coupons'] = $this->coupon_model->load();
			
			// added coupon
			$data['cart_coupons'] = $this->cart->coupons();
		}
		
		if ($this->cart->total_items() > 0)
		{
			$this->cart->reload();
			$data['cart_content']		= $this->cart->contents(); // cart content
			$data['total_items']		= $this->cart->total_items(); // qty
			$data['total_discount']		= $this->cart->total_discount(); // discount amount
			$data['cart_total_original']= $this->cart->total_original(); // original amount
			$data['cart_total']			= $this->cart->total(); // final amount
		}
		else
		{
			$data = array();
		}
		
		$body_right = $this->load->view(CURR_TEMPLATE . '/cart_detail', $data, TRUE);
		$this->layout->set_element('head', NULL);
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();
	}
	
	/**
	 * add product to cart
	 * @access	public 
	 * @return 	string
	 */
	function insert()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('product_id', NULL, 'trim|required|integer');
		$this->form_validation->set_rules('qty', NULL, 'trim|required|integer');
			
		if ($this->form_validation->run())
		{
			$item['id']		= $this->input->post('product_id');
			$item['qty']	= $this->input->post('qty');
			$item['options']= $this->input->post('options');
			
			$result = $this->cart->insert($item);
			
			if ($result == TRUE)
			{
				// echo json_encode($this->cart->contents()); // if ajax
				$this->session->set_flashdata('message', $this->lang->line('cart_product_added'));
				redirect($this->agent->referrer());
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	function apply_coupon_ajax()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('coupon_id', NULL, 'trim|required|integer');
		
		if ($this->form_validation->run())
		{
			$coupon_id = $this->input->post('coupon_id');
			// $this->cart->apply_coupon('6785', TRUE); // for test
			$result = $this->cart->apply_coupon($coupon_id, TRUE);
		}
	}
	
	function update_qty_ajax()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('rowid', NULL, 'trim|required');
		$this->form_validation->set_rules('qty', NULL, 'trim|required|integer');
		
		if ($this->form_validation->run())
		{
			$item['rowid']	= $this->input->post('rowid');
			$item['qty']	= $this->input->post('qty');
			
			$result = $this->cart->update($item);
			
			if ($result == TRUE)
			{
				echo json_encode($this->cart->contents());
			}
		}
		else
		{
			return FALSE;
		}
	}
}