<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collectionshop extends FE_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('layout');
		$this->load->model('cms_model');
		$this->load->model('collection_shop_model');
		
		$this->lang_id = $this->lang->lang_id();
	}
	
	function index($lang_id=0)
	{
		$this->web_id = 20; // It is a hardcode value
	
		if ($lang_id == 0)
			$lang_id = $this->lang_id;
		
		$s = $this->cms_model->load_cms_content($this->web_id);
		echo $s;
	}
	
	function loadcity($city_id)
	{
		echo $this->collection_shop_model->_prepare_city_select($city_id, "collection_point_city_id", null, array('prompt'=>'--- 請選擇 ---'));
	}
	
	function loadshopcity($shop_city_id)
	{
		echo $this->collection_shop_model->_prepare_shop_select($shop_city_id, "shop_selection_multi_layers_shop_dd", null, array('prompt'=>'--- 請選擇 ---'));
	}
	
	function loadaddress($shop_id)
	{
		$a = $this->collection_shop_model->load_shop_info($shop_id);
		echo $a->shop_address;
	}
}