<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * frontend controller
 */
class FE_Controller extends CI_Controller {
	
	// 1 en, 2 tc, 4 sc
	const DEFAULT_COUNTRY_ID	= '1';	// for cms only?
	const DEFAULT_COUNTRY_CODE 	= 'HK';
	
	function __construct()
	{
		parent::__construct();
		
		$http_host		= isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$http_host_arr	= explode('.', $http_host, 2);
		$subdomain		= $http_host_arr[0];
		$domain			= $http_host_arr[1];
		
		session_start();
		
		// global
		$this->session->set_userdata('site_host', $http_host);
		$this->session->set_userdata('site_subdomain', $subdomain);
		$this->session->set_userdata('site_domain', $domain);

		// cms
		$this->session->set_userdata('country_id', self::DEFAULT_COUNTRY_ID);
		$this->session->set_userdata('country_code', self::DEFAULT_COUNTRY_CODE);
		
		// identity subdoamin to choose template 
		if ($this->session->userdata('site_host') != $http_host || $this->session->userdata('site_id') == NULL)
		{
			$site = $this->db->get_where('tbl_site', array('site_domain' => $http_host))->row_array();
			
			if ( ! empty($site))
			{
				$this->session->set_userdata('site_id', $site['site_id']);
				$this->session->set_userdata('site_template', $site['site_template']);
			}
			else
			{
				redirect(site_url('main'));
			}
		}
		
		define('SITE_ID', $this->session->userdata('site_id'));
		define('CURR_TEMPLATE', $this->session->userdata('site_template'));
    }	
}

/**
 * 
 */
class BE_Controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
	}
}

	