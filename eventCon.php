<?php

include ('./Controller/restDataController.php');
include ('./View/wecheView.php');

class eventCon
{

	

	public $dataObj;
	public $viewObj;


	public function __construct ()
	{	

		$this->dataObj  =  new restDataController();
        $this->viewObj  =  new wecheView();


	}

	
	//路由
	public function eventHaedel ($envetKey)
	{
		
		if ($envetKey == "pin") { $this->searchButt(); }   //搜索按钮事件


	}	


	/*
		这个类用来处理搜索按钮
	*/
	public function searchButt () 
	{

		//查询用户附近店铺
		$uRest = $this->dataObj->getUserPosiRest($GLOBALS['userID']);
		//查询用户位置
		$uLocation =  $this->dataObj->getUserLoaction('gh_8975a14402a2');

		//如果没有数据
		if (!$uRest) {
			$str = '抱歉客官,我还不知道你在那奥,切换回文字输入,点+号发送坐标,即可查询附近餐馆奥!';
			echo  $this->viewObj->sendText($str);
			exit ;
		}

		//拼接字符
		$str ;
		foreach ($uRest as $v) {
			$str .= '['.$v['id'].']  '.$v['resname']."\n";
		}
		$str .= "\n".'位置: '.$uLocation['location']."附近的餐馆!";
		$str .= "\n\n".'输入店铺编号查看菜单奥亲!';
		$str .= "\n\n".'<a href="http://restaddpage.html">发现新餐馆?提交获取奖励</a>';

		//返回数据
		$this->viewObj->sendText($str);
	}


}

	$aa =  new  eventCon();

	$aa->eventHaedel('pin');

