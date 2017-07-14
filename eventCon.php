<?php

include ('./Controller/restDataController.php');
include ('./Model/Medoo/medoo.php');
include ('./View/wecheView.php');

class eventCon
{

	

	public $dataObj;
	public $viewObj;


	public function __construct ()
	{	

		$this->dataObj  =  new restDataController();
        $this->viewObj  =  new wecheView();

// 
	}

	
	//路由
	public function eventHaedel ($envetKey)
	{
		
		if ($envetKey == "pin") { $this->searchButt(); }   //搜索按钮事件
		if ($envetKey == "get") { $this->getMunu(); }   //搜索按钮事件

	}	


	//抽取按钮
	public function getMunu () 
	{
		$arr = $this->dataObj->getMenu();
		$this->viewObj->sendText($arr[0]);
	}


	/*
		处理搜索按钮
	*/
	public function searchButt () 
	{
	
		//查询用户位置
		$address =  $this->dataObj->getUserLoaction($GLOBALS['userID']);
		var_dump($address);
		//如果没有数据
		if ($uLocation) {
			$str = '抱歉客官,我还不知道你在那奥,点击左下角: "更多" -> "发送位置"';
			echo  $this->viewObj->sendText($str);
			exit ;
		}

		//查询用户附近店铺
		$uRest = $this->dataObj->getUserPosiRest($GLOBALS['userID']);

		//拼接字符
		$str .= "\n".'位置: '.$address['address']."  \n附近的餐馆:"."\n\n";
		foreach ($uRest as $v) {
			$str .= '['.$v['id'].']  '.$v['res_name']."\n";
		}
		
		$str .= "\n\n".'输入店铺编号查看菜单奥亲!';
		$str .= "\n\n".'<a href="http://restaddpage.html">发现新餐馆?提交获取奖励</a>';

		//显示数据
		$this->viewObj->sendText($str);
	}

	public function gg ()
	{
		$aa = $this->dataObj->getRestMenu(1);
		var_dump($aa);
	}

}

	// $GLOBALS['userID'] =  'gh_8975a14402a2';
	// $aa =  new  eventCon();

	// $aa->getMunu();

	

