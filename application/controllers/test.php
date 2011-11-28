<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends FE_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$session = $this->session->all_userdata();
		echo '<pre>';
		print_r($session);
		echo '</pre>';
		
		log_message('debug', 'For testing');
	}
}