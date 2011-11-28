<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_model extends CI_Model {
		
	var $prd_img_size			= array('s', 'v', 'l', 'o');
	var $prd_img_original_tag	= 'o';
	
	/**
	 * 
	 * @param	string
	 * @param	string
	 * 
	 */
	function product_image($product_icon, $size = NULL)
	{
		if (empty($product_icon))
		{
			return;
		}
		
		$url = NULL;
		
		if( ! empty($size))
		{
			$url = $this->_product_image_naming($product_icon, $size);
		}
		else
		{
			foreach($this->prd_img_size as $val)
			{
				$url[$val] = $this->_product_image_naming($product_icon, $val);
			}
		}
		
		return $url;
	}
	
	function _product_image_naming($product_icon, $size)
	{
		$filename	= basename($product_icon);
		$image_size	= $this->prd_img_size;
		
		if (in_array($size, $image_size))
		{
			if($size == $this->prd_img_original_tag)
			{
				return preg_replace('/\.(jpg|jpeg|png|gif)/', '.$1', $filename);
			}
			else
			{
				return preg_replace('/\.(jpg|jpeg|png|gif)/', '_' . $size . '.$1', $filename);
			}
		}
	}
}