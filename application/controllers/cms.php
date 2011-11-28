<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS extends FE_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('cms_model');
	}
	/*
	function _remap($method) 
	{
		if (method_exists($this, $method))
		{
			$this->$method($this->uri->segment(3));
		}
		else
		{
			$this->index($method);
		}
	}
	*/
	
	function index($web_id)
	{
		$cms_content = $this->cms_model->load_cms_content($web_id);
		
		if ($cms_content === false)
		{
			// no cms, show error
			die('error');
		}
		
		$body_right	= $cms_content[0];
		$head['title']	= $cms_content[1];
		
		$this->layout->set_element('body_right', $body_right);
		$this->layout->set_element('head', $head);
	
		$this->layout->set_frame('front');
		$this->layout->view();
	}
}