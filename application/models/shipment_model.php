<?php

class Shipment_model extends CI_Model
{
	const STANDARD_POST	= 1;
	const AIR_POST		= 2;
	const SPEED_POST	= 3;
	const HK_POST		= 4;
	const SF_EXPRESS	= 5;
	const UPS			= 111;
	
	const STORE_PICKUP	= 0;
	const HOME_DELIVERY = 1;
	
	// var $country_code	= '';		// same as session country_code
	var $delivery_method	= '';		// 1 = home delivery, 0 = store pickup
	var $shipment_method	= '';		// if $delivery_method == 1 - shipment_type
	var $pickup_shop		= '';		// if $delivery_method == 0 - pickup shop id
	var $weight				= '';		
	
	function __construct()
	{
		$this->lang = $this->lang->lang();
		$this->shipment_method = array(
									'en' => array(
													self::STANDARD_POST => 'Oversea: HK Post V Air Parcel',
													self::AIR_POST => 'Air Post',
													self::SPEED_POST => 'Oversea: HK Post V Speed Post',
													self::HK_POST => 'HK SAR: HK Post / 3PL',
													self::SF_EXPRESS => 'China: S.F. Express',
													self::UPS => 'UPS'
													),
									'tc' => array(
													self::STANDARD_POST => '海外: 香港郵政(空郵包裹)',
													self::AIR_POST => '空郵',
													self::SPEED_POST => '海外: 香港郵政(特快專遞)',
													self::HK_POST => '本地: 香港郵政速遞 / 第三方物流公司',
													self::SF_EXPRESS => '國內: 順豐速運',
													self::UPS => 'UPS 速遞'
													),
									'sc' => array(
													self::STANDARD_POST => '海外: 香港邮政(空邮包裹)',
													self::AIR_POST => '空邮',
													self::SPEED_POST => '海外: 香港邮政(特快专递)',
													self::HK_POST => '本地: 香港邮政速递/ 第三方物流公司',
													self::SF_EXPRESS => '国内: 顺丰速运',
													self::UPS => 'UPS 速递'
													),													
									);
	}
	
	function load()
	{
		return;
	}
	
	function method($country_code)
	{
		if (isset($country_code))
		{
			$this->db->select('id, shipment_country, shipment_type, cat_id');
			$this->db->group_by('shipment_type');
			$shipment = $this->db->get_where('tbl_shipment', array('shipment_country' => $country_code))->result_array();
			
			if (count($shipment) > 0)
			{
				foreach ($shipment as $key => $val)
				{
					$shipment[$key]['display_name'] = $this->_shipment_name($val['shipment_type']);
				}
				return $shipment;
			}
		}
		return array();
	}
	
	function fee($country_code)
	{
		$fee = FALSE;
		if ($this->delivery_method == self::STORE_PICKUP)
		{
			$this->db->group_by('shop_id');
			$fee = $this->db->get_where('tbl_collection_shop', array('shop_id' => $this->pickup_shop))->row_object()->collection_extra_charge;
		}
		elseif ($this->delivery_method == self::HOME_DELIVERY)
		{
			$this->db->where('shipment_type', $this->shipment_method);
			$this->db->where('shipment_country', $country_code);
			$this->db->from('tbl_shipment');
			$t = $this->db->get()->result_array();
			
			echo '<pre>';
			print_r($t);
			echo '</pre>';
		}
		
		return $fee;
	}
	
	function country()
	{
		$this->db->select("id, country_code, country_$this->lang AS country_name");
		return $this->db->get_where('tbl_country', array('status' => '1'))->result_array();
	}
	
	function _shipment_name($id)
	{
		return $this->shipment_method[$this->lang][$id];
	}
}
