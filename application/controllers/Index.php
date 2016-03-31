<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct(){
       parent::__construct();
       $this->load->model('Onethink_action');
       $this->load->helper('url_helper');			//视图页面  site_url('news/'.$news_item['slug']);
       $this->load->database();
    }

	public function index(){
	    //$this->output->cache(1);               //开启缓存,其中 $n 是缓存更新的时间（单位分钟）。
	    //$this->output->delete_cache();           //删除缓存
	    
	    
		$data['title'] = 'ckckckckckckck123456789';
		$this->load->view('templates/header',$data);
		$this->load->view('templates/footer');
		
		//1、原生sql
		$query = $this->db->query('select * from onethink_action');
		$res = $query->result_array();
		//var_dump($res);
		
		//2、框架sql
		/* $this->db->where('id',1);     
		$this->db->select('*'); */
		$res2=$this->db->get('action');       //action为onethink_action表名
		//var_dump($res2->result());
		
		
		//3、切换数据库
		$db2 = $this->load->database('default', TRUE);
		$res3 = $db2->query('select * from onethink_action where id=2');
		//var_dump($res3->row());
        
		
        //echo config_item('index_page');           //访问配置信息
		//$this->output->enable_profiler(TRUE);     //启用分析器

	}

	//读取数据(数组)
	public function test(){
		$data['news'] = $this->Onethink_action->get_actions();
		var_dump( $this->db->last_query());				//查询最后执行的sql语句
		$data['title'] = 'News archive';

	    $this->load->view('templates/header', $data);
	    $this->load->view('Index/test', $data);
	    $this->load->view('templates/footer');
	    
	}
	//读取单条数据
	public function test2($slug = NULL){
		$data['news'] = $this->Onethink_action->get_actions($slug);
		if (empty($data['news'])){
		        show_404();
		}
		$data['title'] = $data['news']['title'];

	    $this->load->view('templates/header', $data);
	    $this->load->view('Index/test2', $data);
	    $this->load->view('templates/footer');
	}

	//数据库插入数据
	public function create(){
	    $this->load->helper('form');							// 表单辅助函数 提供的，用于生成 form 元素
	    $this->load->library('form_validation');				

	    $data['title'] = 'Create a news item';

	    $this->form_validation->set_rules('title', 'Title', 'required');		//  检查表单是否被提交，以及提交的数据是否能通过验证规则
	    $this->form_validation->set_rules('text', 'Text', 'required');			//  set_rules() 方法有三个参数：表单中字段的名称，错误信息中使用的名称，以及验证规则

	    if ($this->form_validation->run() === FALSE){
	        $this->load->view('templates/header', $data);
	        $this->load->view('Index/create');
	        $this->load->view('templates/footer');

	    }else{
	    	//var_dump($this->input->post('title'));
	    	//var_dump($this->input->get_post('text'));
	        $this->Onethink_action->set_news();
	        $this->load->view('Index/success');
	    }
	}

	//重映射方法 ************************************
	/*public function _remap($method)
	{
	    if ($method === 'index'){
	        $this->$method();
	    }else{
	        $this->create();
	    }
	}*/
}
