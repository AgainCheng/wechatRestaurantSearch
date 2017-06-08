<?php

include ('./Controller/dataController.php');
include ('./View/wecheView.php');

//文本信息处理类
class textCon
{


	public $dataObj;
	public $viewObj;

	public function __construct ()
	{	
		$this->dataObj  =  	new dataController($GLOBALS['userID']);   
        $this->viewObj  =  new wecheView();

	}

	//数据路由
	public function textHandle ($Content) 
	{	
		
		//默认信息处理
		$resStr = $this->ptText($Content);
    	
    	//添加前置符号防止判断为0
    	$Content2 = 'a'.$Content;
	 	if ( strpos($Content2, '我要吃') ) { $resStr  =  $this->wycText($Content); }



    	return  $resStr;
	}


	//处理普通信息
	public function ptText ($Content)
	{
	
		$resStr = '这条是普通消息';

		return  $this->viewObj->sendText($resStr);
	}


	//处理关键字"我要吃"
	public function wycText ($Content)
	{
		
		$text = $this->dataObj->setUserSel($Content);
	    $num = $this->dataObj->getMenuData(); //根据喜好查询数据库,如果没有则返回提示消息

	    //判断是否找到数据
    	if( !empty($text ) ){
    		if(empty($num)){
    			$resStr = '抱歉,客官库中没有 '.$text." 数据奥奥!!!";
    		}else{
    			$resStr = '已设置条件:'.$text;
    		}
    	}else{
    		$resStr = '抱歉客官,没有找到你要的类型,满足不了你的特殊爱好奥!!!';
    	}

		return  $this->viewObj->sendText($resStr);
	}


}

$userID =  '123123';
$deveID =  'cheng'; 

$obj  = new textCon($deveID, $deveID);
$aa  =  $obj->textHandle('我要吃');
echo $aa ; 
file_put_contents('./userTextMsg/sendMsgUser.txt', $aa);


