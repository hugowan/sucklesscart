<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class fb extends CI_Controller {

	const PAGE_ID	= '131751680203194';
	const APP_ID	= '252237538147968';
	const APP_SECRET= '63da2993e0e5696855fed7a8e73e8b78';
	
    function __construct()
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		
		parent::__construct();
		$this->load->model('facebook_signed_model', 'facebook_signed');
        // $config = array('appId' => self::APP_ID, 'secret' => self::APP_SECRET);
        // $this->load->library('facebook/facebook', $config);
    }

	function start()
	{
		// $user_id = $this->facebook->getUser();
		if(!empty($_REQUEST['signed_request']))
		{
			$signed_request = $this->facebook_signed->parse_signed_request($_REQUEST['signed_request'], self::APP_SECRET);
			
			if(empty($signed_request['page']))
			{
				echo "<script>window.top.location.href = 'http://www.facebook.com/crvmore?v=app_252237538147968';</script>";
				exit();
			}
			
			$user_id = (!empty($signed_request['user_id'])) ? $signed_request['user_id'] : NULL;
		}
		else
		{
			echo "<script>window.top.location.href = 'http://www.facebook.com/crvmore?v=app_252237538147968';</script>";
			exit();
		}
		
		// permission
		if(!empty($user_id))
		{
			$is_registed = $this->db->query("SELECT * FROM event_20110906_register WHERE fb_uid = '{$user_id}'")->num_rows();
			
			if($is_registed)
			{
				$this->step3();
			}
			else
			{
				$this->step2();
			}			
		}
		else
		{
			$this->step1();
		}
	}
	
	function step1()
	{
		$signed_request = $this->facebook_signed->parse_signed_request($_REQUEST['signed_request'], self::APP_SECRET);
		$data['is_liked'] = ($signed_request['page']['liked'] == 1) ? TRUE : FALSE;
		
		$this->_template('20110906/step1', $data);
	}	
	
	function step2()
	{
		// $data['user_id'] = $this->facebook->getUser();
		$signed_request = $this->facebook_signed->parse_signed_request($_REQUEST['signed_request'], self::APP_SECRET);
		$data['user_id'] = (!empty($signed_request)) ? $signed_request['user_id'] : NULL;
		
		$this->_template('20110906/step2', $data);
	}
	
	function step3()
	{
		/*
		echo '<pre>';
		print_r($this->session->all_userdata());
		echo '</pre>';
		*/
		
		$this->_template('20110906/step3', NULL);
	}
	
	function tc()
	{
		$this->load->view('20110906/tc', NULL);
	}
	
	function _template($file_path, $data)
	{
		$data['page_id'] = self::PAGE_ID;
		$data['app_id'] = self::APP_ID;
		
		$this->load->view($file_path, $data);
	}

	function save_callback()
	{
		// $this->session->set_userdata('user_id', $this->input->post('user_id'));
		// $this->session->set_userdata('real_name', $this->input->post('real_name'));
		// $this->session->set_userdata('email', $this->input->post('email'));
		// $this->session->set_userdata('question', $this->input->post('question'));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_id', 'user_id', 'required|integer');
		$this->form_validation->set_rules('real_name', 'real_name', 'required');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		
		if($this->form_validation->run())
		{
			$data['fb_uid']		= $this->input->post('user_id');
			$data['real_name']	= $this->input->post('real_name');
			$data['email']		= $this->input->post('email');
			$data['question']	= $this->input->post('question');
			$data['datetime']	= date('Y-m-d H:i:s');
			$data['notice']		= $this->input->post('notice');
			$data['ip']			= $this->input->ip_address();
			
			$this->db->insert('event_20110906_register', $data);
		}
	}
	
}