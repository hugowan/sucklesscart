<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {
	
	const PRD_DESCRIPTION = 'd2';
	const PRD_BUY_NOTICE = 'd7';
	
	var $total_rows;
	
	function __construct()
	{
		$this->lang_id = $this->lang->lang_id();
		$this->load->helper('array');
	}
	
	/**
	 * 精選貨品
	 * @param	array
	 * @return	array
	 */
	function feature_products($display_limit)
	{
		$where['p.feature_flag'] = 1;
		$where['p.feature_online <='] = date('Y-m-d H:i:s');
		$where['p.feature_offline >='] = date('Y-m-d H:i:s');

		$this->db->select('p.id, p.cat_id, p.product_unique_key, p.product_icon, pc.price, pc.original_price');
		$this->db->limit((int)$display_limit);
		$products = $this->_query_products($where)->result_array();
		return $this->_fetch_products($products);
	}
	
	/**
	 * @param	string	keyword
	 * @return	array 	product id
	 */
	function find_products_by_pname($keyword)
	{
		$keyword = urldecode($keyword);
		
		if (empty($keyword))
		{
			return array();
		}
		else
		{
			// keyword length less than 4 or chinese character
			if (strlen($keyword) < 4 || preg_match("/[\x{4e00}-\x{9fa5}]+.*/u", $keyword) > 0)
			{
				$this->db->select('p.id');
				$this->db->from('tbl_product p');
				$this->db->join('tbl_product_desc pd', 'pd.product_id = p.id', 'inner');
				$this->db->where('pd.language', $this->lang_id);
				$this->db->where('p.status', 1);
				$this->db->where('p.searchable', 1);
				$this->db->where('(p.product_name LIKE "%' . $keyword . '%" OR pd.product_name LIKE "%' . $keyword . '%" OR p.keyword_search LIKE "%' . $keyword . '%")');
				$ids = $this->db->get()->result_array();
			}
			else
			{
				$this->db->select('id');
				$this->db->or_where('product_no', $keyword);
				$this->db->or_where('product_unique_key', $keyword);
				$ids_1 = $this->db->get_where('tbl_product', array('status' => '1', 'searchable' => '1'))->result_array();
				
				$this->db->select('id');
				$this->db->where('MATCH (product_name) AGAINST ("' . $keyword . '" IN BOOLEAN MODE)', NULL, FALSE);
				$ids_2 = $this->db->get_where('tbl_product', array('status' => '1', 'searchable' => '1'))->result_array();
				
				$this->db->select('id');
				$this->db->or_like('keyword_search', $keyword);
				$this->db->where('MATCH (keyword_search) AGAINST ("' . $keyword . '" IN BOOLEAN MODE)', NULL, FALSE);
				$ids_3 = $this->db->get_where('tbl_product', array('status' => '1', 'searchable' => '1'))->result_array();
	
				$this->db->select('p.id');
				$this->db->from('tbl_product p');
				$this->db->join('tbl_product_desc pd', 'pd.product_id = p.id', 'inner');
				$this->db->where('pd.language', $this->lang_id);
				$this->db->where('MATCH (pd.product_name) AGAINST ("' . $keyword . '" IN BOOLEAN MODE)', NULL, FALSE);
				$ids_4 = $this->db->where(array('p.status' => '1', 'p.searchable' => '1'))->get()->result_array();
	
				$ids = array_merge($ids_1, $ids_2, $ids_3, $ids_4);				
			}

			if (count($ids) > 0)
			{
				return array_unique(implode_assoc($ids));
			}
			else
			{
				return array();
			}
		}
	}

	/**
	 * find products by product id
	 * @param	array
	 * @param	array
	 * @param	string
	 * @param	string
	 * @return	array 	product info
	 */	
	function find_products_by_pid($product_ids = array(), $options = array(), $count = PRD_DISPLAY_LIMIT, $offset = 0)
	{
		if ( ! is_array($product_ids))
		{
			$product_ids = array((int)$product_ids);
		}

		if ( is_array($product_ids) && count($product_ids) == 0)
		{
			return array();
		}
		else
		{
			$this->db->select('p.id, p.cat_id, p.product_unique_key, p.product_icon, pc.price, pc.original_price, pc.actual_weight');
			$this->db->where_in('p.id', $product_ids);
			$this->db->group_by('p.id');
			$this->db->limit((int)$count, (int)$offset);
			$products = $this->_query_products($options)->result_array();
			return $this->_fetch_products($products);
		}
	}
	
	function count_products_by_pid($product_ids = array(), $options = array())
	{
		if ( count($product_ids) == 0)
		{
			return 0;
		}
		else
		{
			$this->db->select('COUNT(DISTINCT(p.id)) AS total');
			$this->db->where_in('p.id', $product_ids);
			return $this->_query_products($options)->row_object()->total;			
		}
	}
	
	/**
	 * find products by category id
	 * @param	array
	 * @param	array
	 * @param	string
	 * @param	string
	 * @return	array 	product info
	 */
	function find_products_by_cid($category_ids = array(), $options = array(), $count = PRD_DISPLAY_LIMIT, $offset = 0)
	{
		if ( ! is_array($category_ids) && count($category_ids) == 0)
		{
			return array();
		}
		else
		{
			$this->db->select('p.id, p.cat_id, p.product_unique_key, p.product_icon, pc.price, pc.original_price, pc.actual_weight');
			$this->db->where_in('pb.cat_id', $category_ids);
			$this->db->group_by('p.id');
			$this->db->limit((int)$count, (int)$offset);
			$products = $this->_query_products($options)->result_array();
			return $this->_fetch_products($products);
		}
	}
	
	/**
	 * count number of result
	 * @param	array
	 * @param	array
	 * @return	integer
	 */
	function count_products_by_cid($category_ids = array(), $options = array())
	{
		$this->db->select('COUNT(DISTINCT(p.id)) AS total');
		$this->db->where_in('pb.cat_id', $category_ids);
		return $this->_query_products($options)->row_object()->total;
	}
	
	/**
	 * rebuild output pattern
	 * @param	array
	 * @return	array
	 */
	function _fetch_products($products = array())
	{
		$this->load->model('image_model');
			
		if (count($products) > 0)
		{
			$i = 0;
			foreach ($products as $key => $val)
			{
				$products[$i]['price']				= $this->_currency_exchange($val['price']);
				$products[$i]['original_price']		= $this->_currency_exchange($val['original_price']);
				$products[$i]['product_image']		= $this->image_model->product_image($val['product_icon']);
				$products[$i]['product_name']		= $this->_product_name($val['id'], $this->lang_id);
				$products[$i]['product_unique_key']	= $val['product_unique_key'];
				$products[$i]['currency_symbol']	= $this->_currency_symbol();
				
				unset($products[$i]['product_icon']);
				
				$i++;
			}
			return $products;
		}
	}
	
	/**
	 * @param	array
	 * @return	object
	 */
	function _query_products($options = NULL)
	{
		$this->db->from('tbl_product p');
		$this->db->join('tbl_product_combination pc', 'pc.product_unique_key = p.product_unique_key', 'left');
		$this->db->join('tbl_product_browse pb', 'pb.product_id = p.id', 'left');
		$this->db->where('p.status', 1);
		// $this->db->where('p.product_status', 1);
		$this->db->where('p.hide_browse_flag', 0);
		$this->db->where('p.product_unique_key IS NOT NULL');
		$this->db->where('pc.online <=', date('Y-m-d H:i:s'));
		$this->db->where('pc.offline >=', date('Y-m-d H:i:s'));

		// --------------------------------------------------------------------
		
		if ( ! empty($options['where']))
		{
			$this->db->where($options['where']);
		}

		if ( ! empty($options['advance']['brand_name']))
		{
			$brand_ids = $this->_brand_id($options['advance']['brand_name']);
			$this->db->where_in('brand_id', $brand_ids);
		}

		if ( ! empty($options['advance']['price']))
		{
			$this->db->where('pc.price BETWEEN '. $options['advance']['price']['price_min'] .' AND ' . $options['advance']['price']['price_max']);
		}
		
		return $this->db->get();
	}
	
	/**
	 * return product name by language
	 * @param	string
	 * @return	string
	 */
	function _product_name($product_id, $lang_id = NULL)
	{
		$this->db->from('tbl_product_desc');
		$this->db->where(array('product_id' => $product_id, 'language' => $lang_id));
		$this->db->limit(1);
		return $this->db->get()->row_object()->product_name;
	}
	
	function _brand_id($brand_name)
	{
		$ids = $this->db->query("SELECT id FROM tbl_brand WHERE brand_name LIKE '%$brand_name%'")->result_array();
		return implode_assoc($ids);
	}
	
	/**
	 * return currency price
	 * @param	integer
	 * @return	string
	 */
	function _currency_exchange($price, $currency_code = NULL)
	{
		return number_format($price, 2);
	}
	
	function _currency_symbol()
	{
		return 'HK$';
	}
}