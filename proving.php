<?php
/**
  * wechat php test
  */

//define your token
	define("", "");

	$wechatObj = new wxModel();

	if ($_GET['echostr'])
	{
	    $wechatObj->valid();
	}
	else
	{
	    $wechatObj->responseMsg();
	}

	$wechatObj->valid();

class wxModel
{

    /*
        接口信息
    */
    public function valid()
    {

        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    /*
        处理微信发送过来的xml消息   
    */
    public function responseMsg ()
    {
        //获取发送微信发送过来的xml数据
        //php < 5.6 使用 $GLOBLS
        //php > 7.0 使用 $postStr = file_get_contents('php://input');
        $postStr = file_get_contents('php://input');

        //extract post data
        if (!empty($postStr)) {
        	
        		file_put_contents('./userTextMsg/getMsgUser.txt', '这是用户发送过来的数据'.$postStr);	//写入接受信息
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);

                // 接收微信服务器发送过来的xml数据
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                
                //解析
                $ToUserName   =  $postObj->ToUserName;      //发送者ID,用户
                $FromUserName =  $postObj->FromUserName;    //接受者ID,开发者
                $MsgType      =  $postObj->MsgType;         //消息类型

                // 超全局变量存入用户ID和开发者ID方便使用
        		$GLOBALS['userID'] = (string)$ToUserName;
        		$GLOBALS['deveID'] = (string)$FromUserName;


    			// 文本处理
                if ( $MsgType == 'text' ) {

                    include ('textCon.php');
                    $text = new textCon();
                    
                    $Content  = (string)$postObj->Content; 
                    $resStr = $text->textHandle($Content); 

                }


                // 地址信息处理
                if ($MsgType == 'location') {

                	include ('locationCon.php');

                    $resStr =  (new locationCon())->locationHandle($postObj);

                }


                 // 事件
                if ($MsgType == 'event') {
         
                	include ('eventCon.php');

                	(new eventCon())->eventHaedel(  (string)$postObj->EventKey );

                } 



        }else {
            echo "";
            exit;
        }
    }


    /*
        验证接口的有效性
    */
    private function checkSignature()
    {
        /*
        1）将token、timestamp、nonce三个参数进行字典序排序
        2）将三个参数字符串拼接成一个字符串进行sha1加密
        3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
         */
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
  }
