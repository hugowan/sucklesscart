<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class report extends CI_Controller {
	
	const U_NAME = 'fbreport';
	const U_PASS = 'fb123report';
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		if($this->session->userdata('event_valid_user') != 1)
		{
			redirect('/20110906/report/login/', 'refresh');
		}
		
		$this->load->library('table');
		$this->load->library('pagination');
		$this->load->helper('form');
		
		$per_page = 30;
		// $page_start = (int)$this->uri->segment(4);
		$page_start = (int)$this->input->get('per_page');
		$uri = array();
		
		$question = $this->input->get('question');
		$to = ($this->input->get('to')) ? $this->input->get('to') . ' 23:59:59': date('Y-m-d 23:59:59');
		$from = ($this->input->get('from')) ? $this->input->get('from') . ' 00:00:00' : date('Y-m-d 00:00:00', strtotime($to . " 0 day"));
		
		// SELECT r.*, COUNT(s.fb_uid) AS COUNT, IF(notice = '1', 'Y', 'N') AS notice
		$sql = "SELECT r.fb_uid, r.real_name, r.email, r.question, IF(notice = '1', 'Y', 'N') AS notice, r.ip, r.datetime, COUNT(s.fb_uid) AS COUNT
				FROM event_20110906_register r 
				LEFT JOIN event_20110906_static s ON s.fb_uid = r.fb_uid 
				WHERE r.datetime BETWEEN '{$from}' AND '{$to}'
				AND s.datetime BETWEEN '{$from}' AND '{$to}' ";
		$sql.= (!empty($question)) ? "AND r.question = '{$question}' " : "";
		$sql.= "GROUP BY r.fb_uid ORDER BY COUNT DESC, r.datetime ";
		
		$query2 = $this->db->query($sql);
		
		$sql.= "LIMIT {$page_start}, {$per_page}";
		$query = $this->db->query($sql);

		// quick fix
		if($this->input->get('question'))
			$uri[] = 'question=' . $this->input->get('question');

		if($this->input->get('from'))
			$uri[] = 'from=' . $this->input->get('from');
			
		if($this->input->get('to'))
			$uri[] = 'to=' . $this->input->get('to');
		
		if(sizeof($uri) > 0)
			$uri_str = implode('&', $uri);
		// end quick fix
		
		$url = base_url() . '20110906/report/index/?';
		$url.= (!empty($uri_str)) ? $uri_str : NULL;
		
		// $_SERVER['QUERY_STRING']
		$config['base_url'] = $url;
		$config['total_rows'] = $query2->num_rows();
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$config['enable_query_strings'] = TRUE;
		$config['page_query_string'] = TRUE;
		
		// $config['query_string_segment'] = 'from';
		
		$this->pagination->initialize($config);
				
		$data['question_opt'] = array('' => '', 'a' => 'a. 網上購物商城', 'b' => 'b. 拍賣網站', 'c' => 'c. 視頻網站', 'd' => 'd. 社交網站');
		$data['to'] = $to;
		$data['from'] = $from;
		
		$this->table->set_heading('Facebook帳號', '用戶名稱', '電郵地址', '回答答案', '電郵通知', '登記IP', '登記日期', '點擊次數');
		$data['table'] = $this->table->generate($query);
		
		
		$query->free_result();
		
		$this->load->view('20110906/report', $data);
	}
	
	function login()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('login', 'login', 'trim|required');
		$this->form_validation->set_rules('pass', 'pass', 'trim|required|md5');
		
		if($this->form_validation->run())
		{
			$user = $this->input->post('login');
			$pass = $this->input->post('pass');
			
			if(self::U_NAME == $user AND md5(self::U_PASS) == $pass)
			{
				$this->session->set_userdata('event_valid_user', '1');
				redirect('/20110906/report/', 'refresh');
			}
		}
		
		$this->load->view('20110906/login');
	}
	
	function logout()
	{
		$this->session->unset_userdata('event_valid_user');
		redirect('/20110906/report/login/', 'refresh');
	}
}