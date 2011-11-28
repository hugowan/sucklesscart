<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends FE_Controller {
	
	private $cust_id;
	private $cust_group_id;
	
	// private static $delivery_method;
	
	function __construct()
	{
		parent::__construct();
		
		// $this->load->model('coupon_model');
		$this->load->model('order_model');
		$this->load->model('customer_model');
		$this->load->model('shipment_model');
		$this->load->model('collection_shop_model');
		$this->load->helper('array');
		$this->lang->load('order', $this->lang->lang());
		
		$this->country_id = $this->session->userdata('country_id');
		$this->country_code = $this->session->userdata('country_code');
		
		// logged in
		if ( ! $this->session->userdata('cust_id'))
		{
			$this->session->set_flashdata('message', '請先登入');
			redirect('customer/login');
			exit();
		}
		else
		{
			$this->cust_id = $this->session->userdata('cust_id');
			$this->cust_group_id = $this->session->userdata('cust_group_id');						
		}
		
		// cart not empty
		if ($this->cart->total_items() == 0)
		{
			redirect('cart');
			exit();
		}
	}	
	
	function index()
	{
		// display bill
		$data['cart_content']		= $this->cart->contents(); // cart content
		$data['total_items']		= $this->cart->total_items(); // qty
		$data['total_discount']		= $this->cart->total_discount(); // discount amount
		$data['cart_total_original']= $this->cart->total_original(); // original amount
		$data['cart_total']			= $this->cart->total(); // final amount
		
		// --------------------------------------------------------------------
		
		// customer detail
		$data['customer'] = $this->customer_model->load($this->cust_id);
		
		// shipment method
		$shipment_method = $this->shipment_model->method($this->country_code);
		if (count($shipment_method) > 0)
		{
			$data['shipment_method'] = array_swap('shipment_type', 'display_name', $shipment_method);
		}
		
		// country
		$country = $this->shipment_model->country();
		if (count($country) > 0)
		{
			$data['country'] = array_swap('country_code', 'country_name', $country);
		}
		
		// collection shop
		$pickup_city = $this->collection_shop_model->load_city();
		if (count($pickup_city) > 0) 
		{
			$data['pickup_city'] = array_swap('shop_city_id', 'shop_city', $pickup_city);
		}
		
		// --------------------------------------------------------------------
		
		$body_right = $this->load->view(CURR_TEMPLATE . '/order_delivery', $data, TRUE);
		$this->layout->set_element('head', NULL);
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();
	}
	
	function save_basic()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('last_name', NULL, 'trim|required');
		$this->form_validation->set_rules('first_name', NULL, 'trim|required');
		$this->form_validation->set_rules('tel_1', NULL, 'trim|required|integer');
		$this->form_validation->set_rules('email', NULL, 'trim|required|valid_email');
		$this->form_validation->set_rules('delivery_method', NULL, 'trim|required|is_natural');
		
		if ($this->form_validation->run())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function save_shipment()
	{
		$this->load->library('form_validation');
	
		$delivery_method = $this->input->post('delivery_method');
		
		if ($delivery_method == $this->order_model->store_pickup)
		{
			$this->form_validation->set_rules('receiver_credential', NULL, 'trim|required');
			$this->form_validation->set_rules('pickup_shop', NULL, 'trim|required');
		}
		elseif ($delivery_method == $this->order_model->home_delivery)
		{
			$this->form_validation->set_rules('address_1', NULL, 'trim|required');
			$this->form_validation->set_rules('city', NULL, 'trim|required');
			$this->form_validation->set_rules('state', NULL, 'trim|required');
			$this->form_validation->set_rules('zip_code', NULL, 'trim|required');
			$this->form_validation->set_rules('country', NULL, 'trim|required');
			$this->form_validation->set_rules('shipment_method', NULL, 'trim|required|integer');
		}
		else
		{
			return FALSE;
		}
		
		if ($this->form_validation->run())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function save()
	{
		if ($this->save_basic() === TRUE && $this->save_shipment() === TRUE)
		{
			$is_valid = TRUE;
		}
		else
		{
			$is_valid = FALSE;
		}
		
		if ($is_valid == FALSE)
		{
			return FALSE;
		}
		
		echo $is_valid;
		echo '<br />';
		
		// basic
		$last_name			= $this->input->post('last_name');
		$first_name			= $this->input->post('first_name');
		$tel_1				= $this->input->post('tel_1');
		$email				= $this->input->post('email');
		$remark				= $this->input->post('remark');
		$delivery_method	= $this->input->post('delivery_method'); // home delivery, store pickup

		// store pickup
		$pickup_shop			= $this->input->post('pickup_shop');
		$receiver_credential	= $this->input->post('receiver_credential'); // hkid, passport...

		// home delivery
		$shipment_method= $this->input->post('shipment_method');
		$address_1		= $this->input->post('address_1');
		$address_2		= $this->input->post('address_2');
		$address_3		= $this->input->post('address_3');
		$city			= $this->input->post('city');
		$state			= $this->input->post('state');
		$zip_code		= $this->input->post('zip_code');
		$country		= $this->input->post('country'); // country code
		
		// payment
		$payment_method = $this->input->post('payment_method');
		
		// init shipment_model
		$this->shipment_model->delivery_method	= $delivery_method;
		$this->shipment_model->shipment_method	= $shipment_method;
		$this->shipment_model->pickup_shop		= $pickup_shop;
		$this->shipment_model->weight			= $this->cart->total_weight();
		$shipment_fee = $this->shipment_model->fee($this->country_code);
		
		if ($shipment_fee == FALSE)
		{
			return FALSE;
		}
		
		// init order_model
		$this->order_model->delivery_method	= $delivery_method;
		$this->order_model->shipment_fee	= $shipment_fee;
		$this->order_model->original_total	= $this->cart->total_original();
		$this->order_model->order_total		= $this->cart->total() + $shipment_fee;
		$this->order_model->cust_id			= $this->cust_id;
		$this->order_model->cust_group_id	= $this->cust_group_id;
		$this->order_model->create();
	}
	
	function pickup_shop_ajax()
	{
		$city_id = (int)$this->input->post('city_id');
		
		if ( ! empty($city_id))
		{
			$pickup_shop = $this->collection_shop_model->load_shop($city_id);
			if (count($pickup_shop) > 0)
			{
				echo json_encode(array_swap('shop_id', 'shop_name', $pickup_shop));
			}
		}
	}
}