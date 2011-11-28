<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
	
	const CATEGORY_INDEX = 1375;
	
	function __construct()
	{
		$this->lang_id = $this->lang->lang_id();
		$this->load->helper('array');
	}	
	
	/**
	 * sub category id
	 * @param	string
	 * @return	array
	 */
	function find_sub_cat_id($cat_id = self::CATEGORY_INDEX)
	{
		static $ids = array();

		if (count($cat_id) < 1)
		{
			return;
		}

		if ( ! is_array($cat_id))
		{
			$cat_id = array($cat_id);
		}
		
		$this->db->select('cat_id');
		$this->db->from('tbl_category');
		$this->db->where('status', 1);
		$this->db->where_in('cat_parent_id', $cat_id);
		$cat_ids = $this->db->get()->result_array();
		$cat_ids = implode_assoc($cat_ids);

		$ids = array_merge($ids, $cat_id);
		
		if (count($cat_ids) > 0)
		{
			$this->find_sub_cat_id($cat_ids);
		}
		
		return array_unique($ids);
	}
	
	function navigation($cat_id = self::CATEGORY_INDEX)
	{
		static $names = array();
			
		$cat_id = (int)$cat_id;
		
		if (empty($cat_id))
		{
			return;
		}
		
		$this->db->select('c.cat_id, c.cat_parent_id, cd.cat_name');
		$this->db->from('tbl_category c');
		$this->db->join('tbl_category_desc cd', 'cd.cat_id = c.cat_id', 'left');
		$this->db->where('status', 1);
		$this->db->where('c.cat_id', $cat_id);
		$this->db->where('cd.language', $this->lang_id);
		$this->db->limit(1);
		$child_cat = $this->db->get()->row_array();
		
		$names[] = $child_cat;
		
		if ($child_cat['cat_id'] != self::CATEGORY_INDEX)
		{
			$this->navigation($child_cat['cat_parent_id']);
		}
		
		return array_reverse($names);
	}
}