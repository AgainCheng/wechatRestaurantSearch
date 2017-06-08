<?php
 // $GLOBALS['deveID'] 用户id
 // $GLOBALS['deveID'] 开发者id
include ('./Controller/restDataController.php');
include ('./View/wecheView.php');

class locationCon
{
	


	public $viewObj;


	public function __construct ()
	{	
        $this->viewObj  =  new wecheView();

	}


	//写入数据库
	public function locationHandle ($postObj)
	{
		$data['userid']   = 	(string)$postObj->ToUserName;
		$data['x'] 		  = 	(string)$postObj->Location_X;
		$data['y'] 		  =		(string)$postObj->Location_Y;
		$data['location'] = 	(string)$postObj->Label;

		$restObj = new restDataController();
		$restObj->wirteUInfo($data, 'ulocation', 'userid', $data['userid']);


		$str = '已设定位置'.$data['location'].'('.$data['x'] .":".$data['y'].')';
		return $this->viewObj->sendText($str);
	}

}


// $postStr = <<< eot
// <xml><ToUserName><![CDATA[gh_8975a14402a2]]></ToUserName>
// <FromUserName><![CDATA[oI9N8w2l4oG1P6RogEAF1afxZ7lc]]></FromUserName>
// <CreateTime>1496906814</CreateTime>
// <MsgType><![CDATA[location]]></MsgType>
// <Location_X>23.113729</Location_X>
// <Location_Y>113.403107</Location_Y>
// <Scale>16</Scale>
// <Label><![CDATA[路20-3号]]></Label>
// <MsgId>6429165811759233814</MsgId>
// </xml>
// eot;


// $userID =  '123123';
// $deveID =  'cheng'; 

// $obj  = new locationCon($deveID, $deveID);

// $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

// $aa  =  $obj->locationHandle($postObj);

// var_dump($aa) ;
// file_put_contents('./userTextMsg/sendMsgUser.txt', $aa);


