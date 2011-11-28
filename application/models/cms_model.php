<?php

class Cms_model extends CI_Model {
	
	function __construct()
	{
		$this->lang_id = $this->lang->lang_id();
		$this->country_id = $this->session->userdata('country_id');
		$this->status = 1;
		$this->preview_date = date('Y-m-d H:i:s');
	}
	
	/**
		* Prepare CMS content by web_id
		* @param int $web_id This number is not cms_id
		* @return array|false array of content and title or false.
	*/
	

	function load_cms_content($web_id)
	{
		$sql = "SELECT * FROM tbl_content c
				LEFT JOIN tbl_content_desc cd ON c.cms_id=cd.cms_id AND cd.language= '".$this->lang_id."'
				WHERE c.web_id = '".$web_id."'
					AND c.approve_status = 1
					AND c.status = 1
					AND c.content_online_date <= '".$this->preview_date."'
					AND c.content_offline_date >= '".$this->preview_date."'
					AND c.country_group LIKE '%~".$this->country_id."~%'
				LIMIT 1";
				
		$cms_content = $this->db->query($sql)->result_array();
		
		if (count($cms_content) <= 0)
			return FALSE;
		
		$cms_content = $cms_content[0];
		
		// Load image for the template.
		$sql = "SELECT * FROM `tbl_image`
			WHERE `cms_id` = '".$cms_content['cms_id']."'
			AND `language` = '".$this->lang_id."'
			ORDER BY `image_id` ASC";
		$image_content = $this->db->query($sql)->result_array();
		
		if ($cms_content['content_template'])
		{
			// debug use...
			$cms_content['content_template'] = str_replace(".htm",".php",$cms_content['content_template']);
			
			// Prepare template data
			$data = array();
			
			for ($c = 1; $c<=30; $c++)
			{
				$data['Content'.$c] = $cms_content['c'.$c];
			}
			
			foreach ($image_content as $key=>$image_content_arr)
			{
				$data['image'.$image_content_arr[$image_id]] = "<img src=\"".$image_content_arr['image_path']."\" alt=\"".$image_content_arr['alt_text']."\">";
			}
			
			return array($this->load->view(CURR_TEMPLATE . '/v6/'. $cms_content['content_template'], $data, TRUE), $cms_content['content_name']);
		}
		
		return FALSE;
	}
}