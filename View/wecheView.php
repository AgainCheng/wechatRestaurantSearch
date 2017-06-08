<?php
   
/* 
    这是一个显示类,用于填充格式反馈数据给微信服务器

    $GLOBALS['deveID'] 用户id

    $GLOBALS['deveID'] 开发者id
*/


class wecheView
{


    
    public function __construct () 
    {

    }
    
    /*
        返回图片信息结构
        Parameter:(发送者ID, 接受者ID, 图片ID)
        Return: 填充好的微信服务器接收数据
    */
    public function  sendImg ($MediaId) 
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
        echo sprintf($textTpl, $GLOBALS['deveID'], $GLOBALS['userID'], $CreateTime, $MsgType, $MediaId);
    }


    /*
        返回文本信息
        Parameter:(发送者ID, 接受者ID, 内容)    
        Return: 填充好的微信服务器接收数据
    */
    public function  sendText ($Content) 
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
        echo sprintf($textTpl, $GLOBALS['deveID'] , $GLOBALS['userID'],  $CreateTime, $MsgType, $Content);                 
    }


    /*
        发送图文消息
        Parameter:(发送者ID, 接受者ID, 数据内容)
        数据内容格式:
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





}

?>
