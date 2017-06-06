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

    public $appid = "wxa678c4e0756b1969";
    public $secret  = "cd204ae4316019628ce439001280e579";
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
    public function responseMsg()
    {
        //获取发送微信发送过来的xml数据
        //php < 5.6 使用 $GLOBLS
        //php > 7.0 使用 $postStr = file_get_contents('php://input');
        $postStr = file_get_contents('php://input');

        //extract post data
        if (!empty($postStr)){

                file_put_contents('getMsgUser.txt', $postStr);

                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);

                //接收微信服务器发送过来的xml数据
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                
                $ToUserName   =  $postObj->ToUserName;      //发送者ID,用户
                $FromUserName =  $postObj->FromUserName;    //接受者ID,开发者
                $MsgType      =  $postObj->MsgType;         //消息类型
                    


                $linka = '<a href="'.$this->getUserInfo().'">点我吧,你会感到意外</a>';
                $resStr = $this->sendText($FromUserName, $ToUserName, $linka);  



                 //事件
                if($MsgType == 'event')
                {
                    $event = $postObj->Event;
                    $eventKey = $postObj->EventKey;

                    //关注事件
                    if( $event == "subscribe" )
                    {
                        $resStr = $this->sendText($FromUserName, $ToUserName, '居然敢关注我,那就给你见识下我的厉害吧,点击下面获取按钮,你就知道你今天要吃什么了'); 
                    }

                    //菜单点击事件
                    if( $event == "CLICK" )
                    {

                         include('./demo/getMenuData.php');
                         $menu  = new getMenuData(); 

                        //获取按钮点击事件
                        if( $eventKey == "get-cuisine" ){
                                            
                            $text = $menu->getMenu();

                            //返回信息
                            $resStr = $this->sendText($FromUserName, $ToUserName,  $text);

                        }

                        //难度按钮点击事件
                        if( $eventKey == '4' || $eventKey == '3' ||$eventKey == '2' || $eventKey == '1'){
                            //准备值
                            $arr =  array( 'userid' => (string)$ToUserName, 'nd' => '1');
                            //设置函数
                            $menu->addUserInfo($ToUserName, $arr);
                            //返回信息
                            $resStr = $this->sendText($FromUserName, $ToUserName, '当前菜式等级为:'.$eventKey );
                        
                        }

                    }



                } 


                file_put_contents('sendMsgUser.txt', $resStr);
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

    /*
        随机返回数据库中的一条数据
    */
    public function getMenuData() 
    {

        include('./demo/db_example.php');       //导入对象
        $dataNum =  $database->count('menu');   //查询数据条数
        $list = array();
        do{
            $num  = rand(1, $dataNum);
            $list =  $database->select('menu', "cname", array("id[=]" => $num ));

        }while( empty($list[0]) );
        return  $list[0].$dataNum;                       
    }

    /*
        返回图片信息
        Parameter:(发送者ID, 接受者ID, 图片ID)
        Return: 填充好的微信服务器接收数据
    */
    public function  sendImg ($ToUserName, $FromUserName, $MediaId) 
    {
        $textTpl = "<xml>
                       <ToUserName><![CDATA[%s]]></ToUserName>
                       <FromUserName><![CDATA[%s]]></FromUserName>
                       <CreateTime>%s</CreateTime>
                       <MsgType><![CDATA[%s]]></MsgType>
                       <Image>
                           <MediaId><![CDATA[%s]]></MediaId>
                       </Image>
                       </xml>";
        $CreateTime = time();
        $MsgType = 'image';
        return sprintf($textTpl, $ToUserName, $FromUserName, $time, $MsgType, $MediaId);
    }


    /*
        返回文本信息
        Parameter:(发送者ID, 接受者ID, 内容)    
        Return: 填充好的微信服务器接收数据
    */
    public function  sendText ($ToUserName, $FromUserName, $Content) 
    {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                         <FuncFlag>0</FuncFlag>
                     </xml>"; 
        $CreateTime = time(); 
        $MsgType = 'text';        
        return sprintf($textTpl, $ToUserName, $FromUserName,  $CreateTime, $MsgType, $Content);                 
    }


    /*
        发送图文消息
        Parameter:(发送者ID, 接受者ID, 数据内容)
        $arr =  array(
                 array(
                    'title' => 'AA奇幻日记',  
                     'data' => '2017-7-2',
                    'description' => '123',
                    'url' => 'http://slide.news.sina.com.cn/w/slide_1_2841_153039.html#p=1',
                    'picUrl' => 'http://n.sinaimg.cn/news/1_img/upload/8de453bf/20170603/Pvok-fyfvnky4286753.jpg',
                )
          );
        Return: 填充好的微信服务器接收数据
    */
    public function sendImgText ($ToUserName, $FromUserName, $arr) 
    {
        $textTpl = "<xml>
             <ToUserName><![CDATA[%s]]></ToUserName>
             <FromUserName><![CDATA[%s]]></FromUserName>
             <CreateTime>%s</CreateTime>
             <MsgType><![CDATA[%s]]></MsgType>
             <ArticleCount>%s</ArticleCount>
            <Articles>
            %s
            </Articles>
            </xml>";

        $str = '';
        foreach($arr as $v)
        {
            $str .= "<item>";
            $str .= "<Title><![CDATA[".$v['title']."]]></Title>";
            $str .= "<Description><![CDATA[".$v['description']."]]></Description>";
            $str .= "<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>";
            $str .= "<Url><![CDATA[".$v['url']."]]></Url>";
            $str .= "</item>";
        }
                                                
         $CreateTime = time();
         $MsgType =  'news';
         $ArticleCount = count($arr);
         $contet  = $str;
         return   sprintf($textTpl, $ToUserName, $FromUserName, $CreateTime , $MsgType, $ArticleCount, $contet); 
    } 


    /*
        curl获取数据方法(访问地址)
    */
    public function  curlSendDateGet ( $url ) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // 3. 执行cURL请求
        $ret = curl_exec($ch);
        // 4. 关闭资源
        curl_close($ch);

        return $ret;
    }


    /*
        curlpost发送方式(访问地址)
    */
    public function curlSendDatePost ($url, $data) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $tmpInfo = curl_exec($ch);
        curl_close($ch);
        return $tmpInfo;
    }
    
    
    /*
        axxess_token缓存方法
    */
    public function  getAccessToken ()
    {

        session_start();
        if( $_SESSION['access_token'] && (time() - $_SESSION['expire_time']) < 7200 )
        {
            return $_SESSION['access_token'];
        }else{

            $appid = $this->appid;
            $secret = $this->secret;

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
            
            $token =  json_decode($this->curlSendDateGet($url) );

            $token = $token->access_token;

            $_SESSION['access_token'] =  $token;
            $_SESSION['expire_time'] = time();
            return $token;
        }
    }


    /*
        返回授权连接
    */
    public function getUserInfo () 
    {

        $appid = $this->appid;
        $redirect_uri = 'http://119.23.204.96/weixin/loginTest.php';
        $scope = 'snsapi_userinfo';

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize";
        $url .= "?appid={$appid}";
        $url .= "&redirect_uri={$redirect_uri}";
        $url .= "&response_type=code";
        $url .= "&scope={$scope}";
        $url .= "&state=STATE#wechat_redirect ";
        
        return $url;
    }


    /*
        获取关注用户ID
    */
    public function getUserOpenId () 
    {
        $tokenId = $this->getAccessToken();

        $url =  "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$tokenId}";

        $arr = json_decode($this->curlSendDateGet($url), true);
  
        return $arr['data']['openid'] ;
    }


    /*
        群发消息--文本模式(发送内容)
    */
    public function sendCrowdData ($content) 
    {
        //数据模板
        $textTpl  ='{
           "touser":[
                %s
           ],
            "msgtype": "text",
            "text": { "content": "%s"}
        }';

        $str = '"'.implode($this->getUserOpenId(), '","').'"';//获取合并用户列表字符串
        $data = sprintf($textTpl, $str, $content);//填充模板
        $token = $this->getAccessToken();       //获取token值
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$token}";
        return $this->curlSendDatePost($url, $data);//发送数据
    }


    /*
        获取天气数据(地区)
    */
    public function getWeather ($area) 
    {

        $key = '3b6382cf12c747c7dbc1520867b743c7';
        $dtype = '2';

        $url = "http://v.juhe.cn/weather/index";
        $url .="?cityname={$area}";
        $url .="&dtype={$dtype}";
        $url .="&format=";
        $url .="&key={$key}";
    }



}

?>
