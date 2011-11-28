<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout {
	
	var $CI;
	var $config;
	var $element;
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	function set_element($key, $data)
	{
		$this->element[$key] = $data;
	}
	
	function set_frame($frame)
	{
		// $this->CI->load->model('element_model', 'element');
		$config['element'] = array();
		$body = array('body_left' => NULL, 'body_right' => NULL, 'message' => NULL);
		
		$message = $this->CI->session->flashdata('message');
		
		// for dev only
		if ( ! empty($message))
		{
			$body['message'] = '<script >alert("' . $message . '")</script>'; 
		}
		
		switch ($frame)
		{
			case 'front':
				// frame head
				$head = ( ! empty($this->element['head'])) ? $this->element['head'] : NULL;
				
				// frame header
				$header = $this->CI->load->view(CURR_TEMPLATE . '/layout/frame_header', NULL, TRUE);
				
				// frame body
				$body['body_left']	= $this->CI->load->view(CURR_TEMPLATE . '/layout/frame_body_left', NULL, TRUE);
				$body['body_right'] = $this->element['body_right'];
				
				// config
				$config['element'][] = array('key' => 'head', 'filepath' => CURR_TEMPLATE . '/layout/frame_head', 'data' => $head);
				$config['element'][] = array('key' => 'header', 'filepath' => CURR_TEMPLATE . '/layout/frame_header', 'data' => $header);
				$config['element'][] = array('key' => 'body', 'filepath' => CURR_TEMPLATE . '/layout/frame_body', 'data' => $body);
				$config['element'][] = array('key' => 'footer', 'filepath' => CURR_TEMPLATE . '/layout/frame_footer', 'data' => NULL);
				$config['core'] = array('filepath' => CURR_TEMPLATE . '/layout/frame');
				break;
			default:
		}
		
		$this->config = $config;
	}
	
	function view($file = NULL, $data = NULL)
	{
		if ( ! is_null($this->config['element']))
		{
			foreach ($this->config['element'] as $key => $val)
			{
				$frame[$val['key']] = $this->CI->load->view($val['filepath'], $val['data'], TRUE);	
			}
		}
		
		$this->CI->load->view($this->config['core']['filepath'], $frame);
	}
}