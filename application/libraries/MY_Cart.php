<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Cart extends CI_Cart {

	var $cust_id;
	var $cust_group_id;
	
	function __construct($params = array())
	{
		// Set the super object to a local variable for use later
		$this->CI =& get_instance();

		// Are any config settings being passed manually?  If so, set them
		$config = array();
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				$config[$key] = $val;
			}
		}

		// Load the Sessions class
		$this->CI->load->library('session', $config);

		// Grab the shopping cart array from the session table, if it exists
		if ($this->CI->session->userdata('cart_contents') !== FALSE)
		{
			$this->_cart_contents = $this->CI->session->userdata('cart_contents');
		}
		else
		{
			// No cart exists so we'll set some base values
			$this->_cart_contents['cart_total'] = 0;
			$this->_cart_contents['total_items'] = 0;
		}
		
		// Customer info
		$this->cust_id = $this->CI->session->userdata('cust_id');
		$this->cust_group_id = $this->CI->session->userdata('cust_group_id');
		
		log_message('debug', "Cart Class Initialized");
	}
	
	function _insert($items = array())
	{
		$this->CI->load->model('product_model');
			
		if ( ! is_array($items) || count($items) == 0)
		{
			return FALSE;
		}
		
		if ( ! isset($items['id']) || ! isset($items['qty']))
		{
			return FALSE;
		}
		else
		{
			// make sure quantity correct
			$items['qty'] = trim(preg_replace('/([^0-9])/i', '', $items['qty']));
			$items['qty'] = trim(preg_replace('/(^[0]+)/i', '', $items['qty']));
			
			if ( ! is_numeric($items['qty']) OR $items['qty'] == 0)
			{
				return FALSE;
			}
								
			$product = $this->CI->product_model->find_products_by_pid($items['id'], NULL, 1);
			$product = $product[0];
			
			if (isset($product) && count($product) > 0)
			{
				// create a unique identifier for the item being inserted into the cart
				if ( ! empty($items['options']) && count($items['options']) > 0)
				{
					$rowid = md5($items['id'] . implode('', $items['options']));
				}
				else
				{
					$rowid = md5($items['id']);
				}
				
				// increase quantity
				if (isset($this->_cart_contents[$rowid]))
				{
					$qty = $this->_cart_contents[$rowid]['qty'] += $items['qty'];
				}
				else
				{
					$qty = $items['qty'];
				}
				
				unset($this->_cart_contents[$rowid]);
				
				// add the new items to the cart array
				$this->_cart_contents[$rowid] = array(
					'rowid'	=> $rowid,
					'id'	=> $product['id'],
					'name'	=> $product['product_name'],
					'price'	=> $product['price'],
					'weight'=> $product['actual_weight'],
					'image'	=> $product['product_image']['s'],
					'key'	=> $product['product_unique_key'],
					'qty'	=> $qty,
					'currency_symbol' => $product['currency_symbol']
				);
				
				return TRUE;
			}
		}
	}

	function _save_cart()
	{
		// Unset these so our total can be calculated correctly below
		unset($this->_cart_contents['total_items']); // total qty
		unset($this->_cart_contents['total_discount']); // discount total
		unset($this->_cart_contents['cart_total_original']); // total sub total (not include discount)
		unset($this->_cart_contents['cart_total']); // final total (included discount)
		unset($this->_cart_contents['total_weight']);
		
		// Lets add up the individual prices and set the cart sub-total
		$discount_total = 0;
		$weight = 0;
		$total = 0;
		$items = 0;
		foreach ($this->_cart_contents as $key => $val)
		{
			// We make sure the array contains the proper indexes
			if ( ! is_array($val) OR ! isset($val['price']) OR ! isset($val['qty']))
			{
				continue;
			}
			
			// Total weight
			$weight += ($val['qty'] * $val['weight']);  
			
			// Set the subtotal
			$total += ($val['qty'] * $val['price']);
			$items += $val['qty'];
		}
		
		// Set the cart total and total items.
		$this->_cart_contents['total_items']		= $items;
		$this->_cart_contents['total_discount']		= $discount_total;
		$this->_cart_contents['cart_total_original']= $total;
		$this->_cart_contents['cart_total']			= $total;
		$this->_cart_contents['total_weight']		= $weight;
		$this->_cart_contents['currency_symbol']	= $this->currency_symbol();

		// Is our cart empty?  If so we delete it from the session
		if (count($this->_cart_contents) <= 2)
		{
			$this->CI->session->unset_userdata('cart_contents');
			
			return FALSE;
		}
		
		// If we made it this far it means that our cart has data.
		// Let's pass it to the Session class so it can be stored
		$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		
		// --------------------------------------------------------------------
		
		// Recheck coupon is able to use
		if (count($this->coupons()) > 0)
		{
			$tmp_coupons = $this->coupons(); // coupons buffer 
			
			foreach ($tmp_coupons as $key => $val)
			{
				$is_applicable = $this->apply_coupon($val['id']);
				
				if ($is_applicable === FALSE)
				{
					unset($this->_cart_contents['coupons']);
				}
				else
				{
					$discount_total += $val['amount'];
					$discount_total += $total * ($val['percent']) / 100;
				}
			}
			
			$this->_cart_contents['total_discount'] = $discount_total;
			$this->_cart_contents['cart_total'] = $total - $discount_total;
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));			
		}
		
		return TRUE;
	}
	
	function currency_symbol()
	{
		return 'HK$';
	}
	
	function total_original()
	{
		return $this->_cart_contents['cart_total_original'];
	} 
	
	function total_discount()
	{
		return $this->_cart_contents['total_discount'];
	}
	
	function total_weight()
	{
		return $this->_cart_contents['total_weight'];
	}
	
	function contents()
	{
		$cart = $this->_cart_contents;

		unset($cart['total_items']);
		unset($cart['total_discount']);
		unset($cart['cart_total_original']);
		unset($cart['cart_total']);
		unset($cart['total_weight']);
		unset($cart['currency_symbol']);
		unset($cart['coupons']);
		
		if ( count($cart) > 0)
		{
			return $cart;
		}
		
		return NULL;
	}
	
	function coupons()
	{
		$cart = $this->_cart_contents;
		
		if (isset($cart['coupons']))
		{
			return $cart['coupons'];
		}
		
		return NULL;
	}
	
	/**
	 * Apply coupon
	 * 
	 * @access	public
	 * @param	integer
	 * @param	bool		save cart switch
	 * @return	bool
	 */
	function apply_coupon($coupon_id, $save_cart = FALSE)
	{
		$this->CI->load->model('coupon_model');
		
		// init coupon model
		$this->CI->coupon_model->cust_id = $this->cust_id;
		$this->CI->coupon_model->cust_group_id = $this->cust_group_id;
		$coupon = $this->CI->coupon_model->apply($coupon_id);
		
		if ($coupon != FALSE)
		{
			if ($save_cart == TRUE)
			{
				$this->_cart_contents['coupons'] = $coupon;
				$this->_save_cart();
			}
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Destory the cart and rebuild again
	 * 
	 * 
	 */
	function reload()
	{
		$cart = $this->contents();
		$coupons = $this->coupons();
		
		if ( isset($cart) && count($cart) > 0)
		{
			// take the item(s) out
			foreach ($cart as $val)
			{
				$items[] = array('id' => $val['id'], 'qty' => $val['qty']);
			}

			// take the coupon(s) id out
			if (count($coupons) > 0)
			{
				$coupons = array_keys($coupons);
			}
			
			// --------------------------------------------------------------------
			
			// destory and create the cart
			$this->destroy();
			$this->_cart_contents = $this->CI->session->userdata('cart');
			
			// --------------------------------------------------------------------
			
			// insert again, that's it
			$this->insert($items);
			
			if (count($coupons) > 0)
			{
				foreach ($coupons as $val)
				{
					$this->apply_coupon($val, TRUE);
				}
			}
			
			return TRUE;
		}
		return FALSE;
	}
}
