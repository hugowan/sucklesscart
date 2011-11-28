<?php

class Collection_shop_model extends CI_Model {
	
	function __construct()
	{
		$this->lang_id = $this->lang->lang_id();
		$this->country_id = $this->session->userdata('country_id');
		$this->status = 1;
		$this->preview_date = date('Y-m-d H:i:s');
	}
	
	/**
		* Load Collection Shop
		* @param int $shop_city_id Shop City ID
		* @return array array of city information
	*/

	function load_city($shop_city_id = "0", $lang_id = "0")
	{
		if ($lang_id == 0)
			$lang_id = $this->lang_id;
		
		if ($shop_city_id == 0)
		{
			$sql = "SELECT `shop_city_id`, `shop_city` FROM tbl_collection_shop_city WHERE lang_id = ? ORDER BY `shop_city`";
			return $this->db->query($sql, array($lang_id))->result_array();
		}
		else
		{
			$sql = "SELECT `shop_city_id`, `shop_city` FROM tbl_collection_shop_city WHERE shop_city_id = ? AND lang_id = ? ORDER BY `shop_city`";
			return $this->db->query($sql, array(intval($shop_city_id), $lang_id))->row();
		}
	}
	
	function load_shop($shop_city_id, $lang_id="0")
	{
		if ($lang_id == 0)
			$lang_id = $this->lang_id;
	
		$sql = "SELECT shop_id, shop_name, shop_address, shop_opening_hr, shop_tel, shop_email, shop_admin, collection_extra_charge
				FROM tbl_collection_shop
				WHERE shop_city=? AND shop_state=1 AND shop_address_lang = ?";
		return  $this->db->query($sql, array($shop_city_id, $lang_id))->result_array();
	}
	
	function load_shop_info($shop_id, $lang_id="0")
	{
		if ($lang_id == 0)
			$lang_id = $this->lang_id;
		
		$sql = "SELECT shop_id, shop_name, shop_address, shop_opening_hr, shop_tel, shop_email, shop_admin, collection_extra_charge
				FROM tbl_collection_shop
				WHERE shop_id=? AND shop_state=1 AND shop_address_lang = ? LIMIT 1";
		return $this->db->query($sql, array($shop_id, $lang_id))->row();
	}
	
	function _prepare_city_select($shop_city_id, $name, $select=null, $htmlOptions=array())
	{
		if (isset($htmlOptions['prompt']))
			$s = "<option value=\"\">".$htmlOptions['prompt']."</option>\n";
		
		// For Hong Kong Only
		if ($shop_city_id != 1)
			return;
		
		$aCityData = $this->load_city();
		
		// Option Value
		foreach ($aCityData as $key=>$aCityDataDetail)
		{
			if ($aCityDataDetail['shop_city_id'] == $select)
				$s .= "<option value=\"".$aCityDataDetail['shop_city_id']."\" selected=\"selected\">".$aCityDataDetail['shop_city']."</option>\n";
			else
				$s .= "<option value=\"".$aCityDataDetail['shop_city_id']."\">".$aCityDataDetail['shop_city']."</option>\n";
		}

		return $s;
	}
	
	function _prepare_shop_select($shop_city_id, $name, $select=null, $htmlOptions=array())
	{
		if (isset($htmlOptions['prompt']))
			$s = "<option value=\"\">".$htmlOptions['prompt']."</option>\n";
		
		$aShopData = $this->load_shop($shop_city_id);
		
		// Option Value
		foreach ($aShopData as $key=>$aShopDataDetail)
		{
			if ($aShopDataDetail['shop_id'] == $select)
				$s .= "<option value=\"".$aShopDataDetail['shop_id']."\" selected=\"selected\">".$aShopDataDetail['shop_name']."</option>\n";
			else
				$s .= "<option value=\"".$aShopDataDetail['shop_id']."\">".$aShopDataDetail['shop_name']."</option>\n";
		}
		return $s;
	}
}