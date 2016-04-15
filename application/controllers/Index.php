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

	    if ($this->input->get_post('debug') == '1') {		//	http://localhost/CodeIgniter-3.0.6/index.php/Index/test?debug=1  查看当前执行的sql语句
            print_R($this->db->last_query());
            exit;
        }

        if ($this->input->get_post('debug') == '2') {		//	http://localhost/CodeIgniter-3.0.6/index.php/Index/test?debug=2   查看等前的结果集
            print_R($data['news']);
            exit;
        }
	    
	    $this->config->load('common',TRUE);						//加载自己的配置config文件
	    $author = $this->config->item('author','common');
	    var_dump($author);exit;
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


	//同一个IP在30s内,不超过2次记录时的写入信息
	function writefile(){
		header("Content-Type:text/html;   charset=utf-8");

		$num =2 ;		//最多写入3次
		$timeout = 5;	//规定时间内
		$ip = $_SERVER['REMOTE_ADDR'];
	    $file =dirname( dirname(__FILE__) ).'\\public\\'.$ip.'.txt';
	    if(!file_exists($file)){
	        $array['num'] =1;
	        $array['time']=time();
	        $data = json_encode($array);

	        //var_dump($file);
	        $myfile = fopen($file, "a");
	        fwrite( $myfile,$data);
	        fclose($myfile);
	    }else{
	        $data =  file_get_contents($file);
	        $datas = json_decode($data,true);
	        //var_dump($datas);
	        if( ( $datas['num'] != $num ) && ( time()- $datas['time'] < $timeout) ){	//过期时间内,可写
	        	$array['num'] =$datas['num'] + 1;
	        }elseif(time()- $datas['time'] >= $timeout){								//过期重置
	        	$array['num'] = 1;
	        }else{
	            exit('同一个IP在'.$timeout.'s内,已超过'.$num.'次'); 
	        }

	        $array['time']=time();
        	$data = json_encode($array);
            file_put_contents($file, $data);
	    }
	}


	//转义查询、查询绑定
	public function parameterband(){
	    //1、转义查询
        //******************select*********************
	    //$this->db->escape() 这个函数会检测数据类型，仅转义字符串类型的数据。 它会自动用单引号将你的数据括起来，你不用手动添加
        /*$id = $this->input->get_post('id',TRUE);
	    $sql ="select * from onethink_channel where id =".$this->db->escape($id) ;
		$res = $this->db->query($sql);
		var_dump($this->db->last_query());
        var_dump($res->result_array()); */
		
	    //$this->db->escape_str() 这个函数忽略数据类型，对传入的数据进行转义， 这个方法并不常用，一般情况都是使用上面的那个方法
        /*$id = $this->input->get_post('id',TRUE);
	    $sql ="select * from onethink_channel where id ='".$this->db->escape_str($id)."' " ;
		$res = $this->db->query($sql);
		var_dump($this->db->last_query());
        var_dump($res->row()); */
	    
	    //用于处理 LIKE 语句中的字符串，  LIKE 通配符（'%', '_'）可以被正确的转义。
	    /* $title=$this->input->get_post('title',TRUE);
	    $sql = "SELECT * FROM onethink_channel WHERE title LIKE '%" .$this->db->escape_like_str($title)."%'";
	    $res = $this->db->query($sql);
	    var_dump($this->db->last_query());
	    var_dump($res->result_array()); */
	    
	    
	    //***************insert***********************
	    /* $title = $this->input->get_post('title',TRUE);
	    $sql = "INSERT INTO onethink_channel (title) VALUES(".$this->db->escape($title).")";
	    $res = $this->db->query($sql);
	    var_dump($this->db->last_query());
	    var_dump($res); */
	    
	    /* $title = $this->input->get_post('title',TRUE);
	    $sql = "INSERT INTO onethink_channel (title) VALUES('".$this->db->escape_str($title)."')";
	    $res = $this->db->query($sql);
	    var_dump($this->db->last_query());
	    var_dump($res); */
	    
	    /* $time =time();
	    $data = array('title' => $this->input->get_post('title',true),'create_time'=>$time); 
	    $sql  = $this->db->insert_string('channel', $data);       //$this->db->insert_string(),所有的值自动被转义，生成安全的查询语句
	    $res = $this->db->query($sql);
	    var_dump($this->db->last_query());
	    var_dump($res); */
	    
	    /* $time =time();
	    $data = array('title' => $this->input->get_post('title',true),'create_time'=>$time);
	    $res  = $this->db->insert('channel', $data);       //$this->db->insert(),  数据插入到数据库
	    var_dump($this->db->last_query());
	    var_dump($res); */

	    /* $title = $this->input->get_post('title',TRUE);
	    $sql = "INSERT INTO onethink_channel (title) VALUES('".mysqli_real_escape_string($title)."')";
	    $res = $this->db->query($sql);
	    var_dump($this->db->last_query());
	    var_dump($res); */
	    
	    
	    
		//2、查询绑定
        /*$sql ='select * from onethink_channel where id = ? ';
        $res = $this->db->query($sql,array($this->input->get_post('id',TRUE)));
		var_dump($this->db->last_query());
        var_dump($res->result_array()); */

	    /* $sql = "update onethink_channel set title = ? , create_time = ? where id = ?";
	    $res = $this->db->query($sql,array($this->input->get_post('title',TRUE),time(),$this->input->get_post('id',TRUE)));
	    var_dump($this->db->last_query());
	    var_dump($res); */
	    
	    
	    
	    //3、 连贯操作
	    /* $res = $this->db->select('*')
	                ->from('channel')
                    ->where('id >= 3 and update_time != 0') //注意id 后边有空格
	                ->limit(3,2)   //和正常的limit参数相反的
	                ->order_by('id desc')
                    ->get();
	    var_dump($res->result_array());
	    var_dump($this->db->last_query()); */

	}
}
