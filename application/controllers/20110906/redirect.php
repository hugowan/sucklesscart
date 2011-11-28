<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class redirect extends CI_Controller {
	
	const PAGE_URL = 'http://www.facebook.com/crvmore?sk=app_252237538147968';
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$data['fb_uid'] = $this->input->get('fbuid');
		
		if(!empty($data['fb_uid']))
		{
			$data['ip']			= $this->input->ip_address();
			$data['datetime']	= date('Y-m-d H:i:s');

			$is_registed = $this->db->query("SELECT * FROM event_20110906_register WHERE fb_uid = '{$data['fb_uid']}'")->num_rows();
			$is_visited	 = $this->db->query("SELECT * FROM event_20110906_static WHERE fb_uid = '{$data['fb_uid']}' AND ip = '{$data['ip']}'")->num_rows();
			
			if($is_registed && empty($is_visited))
			{
				$this->db->insert('event_20110906_static', $data);
			}
			
			$this->bye();
		}
		else
		{
			$this->bye();
		}
	}
	
	function bye()
	{
		redirect(self::PAGE_URL, 'location', 301);
	}
}