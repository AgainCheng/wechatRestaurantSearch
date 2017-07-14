<?php

include('./Model/Medoo/medoo.php');
include ('./Controller/restDataController.php');
include ('./View/wecheView.php');

//文本信息处理类
class textCon
{

	public $resData;
	public $viewObj;

	public function __construct ()
	{	
        $this->viewObj  =  	new wecheView();
        $this->resData  =   new restDataController();

	}

	//数据路由
	public function textHandle ($Content) 
	{	
		
		    	
    	//关键字"我要吃"调用方法
    	$tem = 'a'.$Content;
	 	if ( strpos($tem, '我要吃') ) { $this->wycText($Content); }

	 	//数字信息调用方法
	 	$tem = (int)$Content;
	 	if ( $tem != 0 ) { $this->numText($tem); }

	 	// 默认信息处理
	 	$this->ptText();

    
	}


	//处理普通信息
	public function ptText ()
	{
	
		$resStr = '客官点击下面 [抽取] 按钮,随机抽取菜式奥!!';

		$this->viewObj->sendText($resStr);
	}


	// //处理关键字"我要吃"
	public function wycText ($Content)
	{
		
		$text = $this->resData->setUserSel($Content);
		var_dump($text);
		if($text){
			
			$resStr = '已设定条件: '.$text;

		} else {
			
			$resStr = '抱歉客官,没有您要的选项奥!';
		
		}
		var_dump($resStr);
		return  $this->viewObj->sendText($resStr);
	}


	//处理数字消息默认回复餐馆菜单
	public function numText ($content)
	{
		echo "123";

		$nameOrPic =  $this->resData->getRestMenu($content);

		var_dump($nameOrPic);

		if (!$nameOrPic) { 
			$resStr = '抱歉客官,没 有 该 店 铺 的信息奥!!!';
			$this->viewObj->sendText($resStr);
			exit; 
		}

		$name = explode(',', $nameOrPic['menu_name']);
		$pirce = explode(',', $nameOrPic['menu_price']);
		var_dump($name);
		var_dump($pirce);

		$resStr = $nameOrPic['address']."餐馆的菜单为:\n";

		foreach ($name as  $k => $v) {
			$resStr .= $v.' : '.$pirce[$k]."\n";
		}
		$resStr .= '<a href="http://119.23.204.96/cheng/public">到这里去</a>';
		echo $resStr;

		$this->viewObj->sendText($resStr);
	}


}

// $userID =  '123123';
// $deveID =  'cheng'; 

// echo "<pre>";
// $obj  = new textCon();
// $aa  =  $obj->textHandle('我要吃甜的');
// echo $aa ; 



