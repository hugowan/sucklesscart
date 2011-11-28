<?php

/**
 * tbl_inv
 * tbl_trans_detail
 * tbl_temp_trans_header
 * 
 */

class Order_model extends CI_Model {
	
	const STORE_PICKUP	= 0;
	const HOME_DELIVERY = 1;
	
	var $store_pickup		= self::STORE_PICKUP;
	var $home_delivery		= self::HOME_DELIVERY;

	var $_order_area		= '';		// HK...
	var $_lang_id			= '';
	var $_payment_gateway	= array('asiapay', 'worldpay');
	
	// customer's personal information
	var $last_name          = '';
	var $first_name         = '';
	var $tel_1              = '';
	var $email              = '';
	var $remark             = '';
	var $delivery_method    = '';		// home delivery, store pickup
	var $cust_id			= '';		// customer id
	var $cust_group_id		= '';		// customer group

	// shipment - store pickup
	var $pickup_shop        = '';		// pickup shop id
	var $pickip_shop_address= '';		// pickup shop address
	var $receiver_credential= '';		// hkid, passport (first four characters... e.g. A123) 
	
	// shipment - home delivery
	var $shipment_method	= '';		// SF, DHL...	
	var $address_1          = '';
	var $address_2          = '';
	var $address_3          = '';
	var $city               = '';
	var $state              = '';
	var $zip_code           = '';
	var $country            = '';		// country code (HK, US...)
	var $country_id			= '';		// country id
	
	// payment
	var $payment_mehtod		= '';		// credit card, bank transfer...
	var $payment_status		= 0;		// dont why is 0
	
	// bill
	var $shipment_fee		= '';		
	var $original_total		= '';		// item(s) total amount (widthout any discount, shipment fee...)
	var $order_total		= '';		// final customer's paid amount
	var $inv_on_off			= 1;		// 0 = needed paid, 1 = no needed paid
	var $inv_status			= 1;		// dont why always 1
	
	// unless
	var $create_by			= 'Member';
	
	function __construct()
	{
		if ($this->delivery_method == $this->home_delivery)
		{
			$this->_order_area = strtoupper($this->country);
		}
		else
		{
			$this->_order_area = 'HK';
		}
		
		$this->_lang_id = $this->lang->lang_id();
	}
	
	function create()
	{
		$this->load->library('serial');
		
		$now = date('Y-m-d H:i:s');
		$inv_no = $this->serial->order($this->_order_area); // create inv_no
		
		if ($this->order_total > 0 && in_array($this->payment_mehtod, $this->_payment_gateway))
		{
			$this->inv_on_off = 0;
		}
		
		$this->country_id = $this->db->get_where('tbl_country', array('country_code' => $this->country_code))->row_object()->id;
		
		$this->db->set('inv_no', $inv_no);
		$this->db->set('inv_lang', $this->_lang_id);
		$this->db->set('inv_on_off', $this->inv_on_off);
		$this->db->set('status', $this->inv_status);
		$this->db->set('cust_id', $this->cust_id);
		$this->db->set('usergroup', $this->cust_group_id);
		$this->db->set('inv_date', $now);
		$this->db->set('receiver_first_name', $this->first_name);
		$this->db->set('receiver_last_name', $this->last_name);
		$this->db->set('receiver_email', $this->email);
		$this->db->set('receiver_credential', $this->receiver_credential);
		$this->db->set('tel_1', $this->tel_1);

		$this->db->set('delivery_method', $this->delivery_method);
		$this->db->set('order_made_destination', $this->country_id);
		
		$this->db->set('shipment_method', $this->shipment_method);
		$this->db->set('shipment_address_1', $this->address_1);
		$this->db->set('shipment_address_2', $this->address_2);
		$this->db->set('shipment_address_3', $this->address_3);
		$this->db->set('shipment_city', $this->city);
		$this->db->set('shipment_state', $this->state);
		$this->db->set('shipment_country', $this->country);
		$this->db->set('shipment_zip_code', $this->zip_code);

		$this->db->set('inv_shop_id', $this->pickup_shop);
		$this->db->set('inv_shop_address', $this->pickip_shop_address);
		
		$this->db->set('shipment_charge', $this->shipment_fee);
		$this->db->set('subtotal', $this->original_total);
		$this->db->set('nettotal', $this->order_total);
		
		$this->db->set('payment_method', $this->payment_mehtod);
		$this->db->set('payment_status', $this->payment_status);
		
		// unless
		$this->db->set('create_date', $now);
		$this->db->set('create_by', $this->create_by);
	}
}
