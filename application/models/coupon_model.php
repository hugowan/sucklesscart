<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon_model extends CI_Model {
	
	const PERCENT_DISCOUNT_ID	= 100;
	const AMOUNT_DISCOUNT_ID	= 1;
	
	var $cust_id;
	var $cust_group_id;
	
	var $_used_coupon_status 	= array('95', '98', '99'); // unknow status, pls ref tbl_trans_coupon
	var $_coupon_display_name	= array(
										'en' => 'coupon_display_name',
										'tc' => 'coupon_display_name_tc',
										'sc' => 'coupon_display_name_sc'
										);
		
	function __construct() {}
	
	/**
	 * 
	 * @param	array
	 * @return	array
	 */
	function load($options = array())
	{
		$coupons = $this->_query($options);
		foreach ($coupons as $key=> $val)
		{
			// discount type
			// (1) percent discount - if coupon_amount = 10, that mean 10% off
			// (2) amount discount - real decrease amount
			$discount_type = $this->_discount_type($coupons[$key]);
			$coupons[$key]['_percent']		= $discount_type['percent'];
			$coupons[$key]['_amount']		= $discount_type['amount'];
			
			$coupons[$key]['_display_name']	= $val[$this->_coupon_display_name[$this->lang->lang()]];
			$coupons[$key]['_is_expired']	= $this->_is_expired($val['expiry_days'], $val['create_date'], $val['end_date']);
			$coupons[$key]['_is_used']		= $this->_is_used($val['id']);
		}
		
		return $coupons; 
	}
	
	/**
	 * @param	integer
	 * @return	mixed
	 */
	function useable($coupon_id = NULL)
	{
		$coupon = $this->load(array('where' => array('id' => $coupon_id)));
		
		if (is_array($coupon) && count($coupon) > 0)
		{
			foreach($coupon as $key => $val)
			{
				if ($val['_is_expired'] == FALSE && $val['_is_used'] == FALSE)
				{
					return $coupon[$key];
				}				
			}
		}
		
		return FALSE; // coupon could not found
	}
	
	function apply($coupon_id = NULL)
	{
		$this->load->library('cart');
		$this->load->model('category_model');
		$this->load->helper('array');
		
		if (isset($coupon_id))
		{
			// check coupon is not expire, not used
			$coupon = $this->useable($coupon_id);
			
			// check coupon is really able to use on current cart status
			// reference by crv://sc_webcat/lib/checkout_function.php - func coupon_validation
			if ($coupon != FALSE && count($coupon) > 0)
			{
				$cart_contents = $this->cart->contents(); // cart items
				$is_applicable = FALSE; // default cannot apply
				
				// allow items
				if ( ! empty($coupon['product_unique_key']))
				{
					$is_applicable = $this->_apply_product($coupon, $cart_contents);
				}
				elseif ( ! empty($coupon['cat_id']))
				{
					// find all related sub category, and check cart inside products is under allowed category
					$is_applicable = $this->_apply_category($coupon, $cart_contents);
				}
				
				
				// allow country
				// codeing...
				// $coupon['country_group'];
				
				// use limit(?), only can apply one coupon, how come...
				// codeing...
				// $coupon['coupon_purchase_limit'];

				
				// if TRUE apply the coupon to cart
				if ($is_applicable === TRUE)
				{
					$return['id']			= $coupon['id'];
					$return['coupon_string']= $coupon['coupon_string'];
					$return['name']			= $coupon['coupon_name'];
					$return['display_name']	= $coupon['_display_name'];
					$return['percent']		= $coupon['_percent'];
					$return['amount']		= $coupon['_amount'];
					
					return array($coupon['id'] => $return);
				}
				
				return FALSE;
			}			
		}
	}
	
	function _apply_product($coupon, $cart_contents)
	{
		if ( ! empty($coupon['product_unique_key']))
		{
			foreach($cart_contents as $val)
			{
				if ($coupon['product_unique_key'] == $val['key'])
				{
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	function _apply_category($coupon, $cart_contents)
	{
		$related_category_ids = array();
		$active_coupon_amount = 0;
			
		if ( ! empty($coupon['cat_id']))
		{
			// coupon allow category
			$category_ids = explode("~", $coupon['cat_id']);
			
			// find all related sub category
			foreach ($category_ids as $val)
			{
				if ( ! empty($val))
				{
					$sub_cat_ids = $this->category_model->find_sub_cat_id($val);
					$related_category_ids = array_merge($related_category_ids, $sub_cat_ids);
				}
			}
			$related_category_ids = array_unique($related_category_ids);
			
			// check cart products is under allowed category
			if (count($cart_contents) > 0)
			{
				foreach ($cart_contents as $val)
				{
					// find product assigned category
					$this->db->select('cat_id');
					$query = $this->db->get_where('tbl_product_browse', array('product_id' => $val['id']));
					$assigned_category_ids = implode_assoc($query->result_array());
					
					// return matched
					$is_matched = array_intersect($related_category_ids, $assigned_category_ids);
					
					if (count($is_matched) > 0)
					{
						$active_coupon_amount += $val['price'] * $val['qty'];
					}
				}
				
				// matched product amount > min_invoice_amount
				if ($active_coupon_amount >= $coupon['min_invoice_amount'])
				{
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	function _discount_type($coupon)
	{
		if ($coupon['coupon_nature'] == self::PERCENT_DISCOUNT_ID)
		{
			$return['percent']	= $coupon['coupon_amount'];
			$return['amount']	= 0;
		}
		elseif ($coupon['coupon_nature'] == self::AMOUNT_DISCOUNT_ID)
		{
			$return['percent']	= 0;
			$return['amount']	= $coupon['coupon_amount'];
		}
		return $return;
	}
	
	function _query($options = array())
	{
		if ( ! empty($options['where']))
		{
			$this->db->where($options['where']);
		}

		$this->db->from('tbl_coupon');
		$this->db->where('cust_id', $this->cust_id);
		$this->db->order_by('id', 'desc');
		return $this->db->get()->result_array();
	}
	
	function _is_expired($expiry_days, $create_date, $end_date)
	{
		$now = strtotime("now");
		
		if ( ! empty($expiry_days))
		{
			if (strtotime($create_date) + ($expiry_days * 86400) <= $now)
			{
				return TRUE;
			}
		}
		else
		{
			if (strtotime($end_date) <= $now)
			{
				return TRUE;
			}
		}
		
		return 0; // FALSE
	}
	
	function _is_used($coupon_id)
	{
		$this->db->from('tbl_trans_coupon');
		$this->db->where('coupon_id', $coupon_id);
		$this->db->where_not_in('used_status', $this->_used_coupon_status);
		return $this->db->count_all_results();
	}
}