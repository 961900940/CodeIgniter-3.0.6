<?php 
	/**
	* 
	*/
	class Onethink_action extends CI_Model 
	{
		
		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		//读取数据
		public function get_actions($slug = FALSE){
		    if ($slug === FALSE){
		        $query = $this->db->get('action');
		        return $query->result_array();		//查询所有数据
		    }

		    $query = $this->db->get_where('action', array('status' => '1'));
		    return $query->row_array();		//查询第一条数据
		}

		//插入数据
		public function set_news(){
		    $this->load->helper('url');

		    $slug = url_title($this->input->post('title'), 'dash', TRUE);	//url_title() ， 这个方法由 URL 辅助函数 提供，用于将字符串 中的所有空格替换成连接符（-），并将所有字符转换为小写。
		    $data = array(
		        'title' => $slug,
		        'remark' => $this->input->post('text')
		    );

		    return $this->db->insert('action', $data);
		}
	}