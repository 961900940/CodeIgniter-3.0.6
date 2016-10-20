<?php
/**公共函数**/

/**
 * log 记录运行日志
 * filename string 文件名
 * msg      string 记录的日志消息
 **/
function writeLog($filename,$msg)
{
	$dir = './log/';
	$time		= time();
	$sLogPath 	= $dir.$filename.'_'.date("Y-m-d",$time).'.txt';
	$msgpath	= date("Y-m-d H:i:s",$time)." : ".$msg."\r\n";
	if(!file_exists($dir)){
		mkdir($dir); 		//mkdir('./xw/');	  创建名为'xw'的目录
		chmod($dir,0777);	//chmod('./xw/',0777);设置目录权限为0777
	}
	file_put_contents($sLogPath,$msgpath,FILE_APPEND);
}

/**
*  格式化输出
**/
function dump($data)
{
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
}
