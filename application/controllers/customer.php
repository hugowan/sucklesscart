<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends FE_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function login()
	{
		$body_right	= $this->load->view(CURR_TEMPLATE . '/customer_login', NULL, TRUE);
		$this->layout->set_element('head', array('title' => ''));
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_frame('front');
		$this->layout->view();
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('home');
	}
	
	function register()
	{
	}
	
	/**
	 * login
	 * 
	 */
	function do_login()
	{
		$this->load->model('customer_model');
		
		$login = $this->input->post('login');
		$password = $this->input->post('password');
		
		$is_customer = $this->customer_model->check_customer($login, $password);
		
		if ($is_customer != FALSE)
		{
			$customer = array(
			   'cust_id' => $is_customer['cust_id'],
			   'cust_name' => $is_customer['login_name'],
			   'cust_group_id'	=> $is_customer['usergroup']
			);

			$this->session->set_userdata($customer);
			redirect('home');
		}
		else
		{
			// redirect($this->agent->referrer());
			redirect('customer/login');
		}
	}	
}