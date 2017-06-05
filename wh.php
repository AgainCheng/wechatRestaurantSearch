<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "againliang");

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
        微信发送过来的xml消息处理   
    */
    public function responseMsg()
    {
		//获取发送微信发送过来的xml数据
        //php < 5.6 使用 $GLOBLS
        //php > 7.0 使用 $postStr = file_get_contents('php://input');
		$postStr = file_get_contents('php://input');

        //使用mode类中的
		include('./demo/db_example.php');
		$arr = array(
			'id' => null,
			'uname'=>'cheng',
			'meg' => time()
			);

		$database->insert('umeg', $arr);
      	
      	

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */

                libxml_disable_entity_loader(true);

                //接收微信服务器发送过来的xml数据
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                
                $ToUserName   =  $postObj->ToUserName;      //开发者ID
                $FromUserName =  $postObj->FromUserName;    //用户ID
                $MsgType      =  $postObj->MsgType;         //消息类型
                $userContent  =  $postObj->Content;         //用户消息

                $time = time();
                $content = '来点我';

                //判断类型为text
                if( $MsgType == 'text' )
                {

                    if('a')
                    {
                        $content = '成';
                    }

 
                    //返回消息用的模板
                    $textTpl = "<xml>
        							<ToUserName><![CDATA[%s]]></ToUserName>
        							<FromUserName><![CDATA[%s]]></FromUserName>
        							<CreateTime>%s</CreateTime>
        							<MsgType><![CDATA[%s]]></MsgType>
        							<Content><![CDATA[%s]]></Content>
        							<FuncFlag>0</FuncFlag>
    							</xml>"; 

                   
                    $type = 'text';  //返回消息类型
                    
            		$resStr  = sprintf($textTpl, $ToUserName,  $FromUserName, $time,  $type, $content);
                    file_put_contents('data.txt', $resStr, FILE_APPEND);
                }

                if( $userContent == '图文' ){
                     $arr =  array(
                            array(
                                'title' => 'AA奇幻日记',
                                'data' => '2017-7-2',
                                'description' => 'aaaaaaaaaaaaaaaaaaaaa',
                                'url' => 'http://slide.news.sina.com.cn/w/slide_1_2841_153039.html#p=1',
                                'picurl' => 'http://n.sinaimg.cn/news/1_img/upload/8de453bf/20170603/Pvok-fyfvnky4286753.jpg',
                            ),
                            array(
                                'title' => 'BB奇幻日记',
                                'data' => '2017-7-2',
                                'description' => 'vvvvvvvvvvvvvvvvv',
                                'url' => 'http://slide.sports.sina.com.cn/k/slide_2_786_131756.html#p=1',
                                'picurl' => 'http://n.sinaimg.cn/sports/2_img/sipaphoto/cf0d0fdd/20170604/jQ8w-fyfvnky4418688.jpg',
                            ),
                            array(
                                'title' => 'BB奇幻日记',
                                'data' => '2017-7-2',
                                'description' => 'vvvvvvvvvvvvvvvvv',
                                'url' => 'http://slide.sports.sina.com.cn/k/slide_2_786_131756.html#p=1',
                                'picurl' => 'http://n.sinaimg.cn/sports/2_img/sipaphoto/cf0d0fdd/20170604/jQ8w-fyfvnky4418688.jpg',
                            ),

                    );
                    $textTpl = <<< EOT
                        <xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>
                        %s
                        </Articles>
                        </xml>
EOT;

                    $str = '';
                    foreach(  $arr as $v)
                    {
                        $str .= "<item>
                            <Title><![CDATA[".$v['title']."]]></Title>
                            <Description><![CDATA[".$v['description']."]></Description>
                            <PicUrl><![CDATA[".$v['picurl']."]]></PicUrl>
                            <Url><![CDATA[".$v['url']."]]></Url>
                            </item>";
                    }
                    
                    $ToUserName   =   $postObj->FromUserName;
                    $FromUserName =   $postObj->ToUserName;
                    $CreateTime = time();
                    $MsgType =  'naws';
                    $ArticleCount = count($arr);
                    $contet  = $str;

                    $resStr =  sprintf($textTpl, $ToUserName, $FromUserName, $CreateTime , $MsgType, $ArticleCount, $contet);


                }
                echo $resStr;



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

?>