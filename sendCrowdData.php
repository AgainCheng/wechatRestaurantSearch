<?php

class wxModel
{
 

    /*
        curl获取数据方法
    */
    public function  getData ( $url ) 
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
        axxess_token缓存方法
    */
    public function  getAccessToken ()
    {

        session_start();
        if( $_SESSION['access_token'] && (time() - $_SESSION['expire_time']) < 7200 )
        {
            return $_SESSION['access_token'];
        }else{

            $appid = "wxa678c4e0756b1969";
            $secret = "cd204ae4316019628ce439001280e579";

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
            
            $token =  json_decode($this->getData($url) );

            $token = $token->access_token;

            $_SESSION['access_token'] =  $token;
            $_SESSION['expire_time'] = time();
            return $token;
        }
    }

    /*
        获取所有关注用户的ID,返回数组
    */
    public function getUserOpenId () 
    {
        $tokenId = $this->getAccessToken();

        $url =  "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$tokenId}";

        $arr = json_decode($this->getData($url), true);
  
        return $arr['data']['openid'] ;

    }
    /*
        curlPost发送方式
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

    
    //群发消息
    public function sendCrowdData () 
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
        $content = "菜肴更新啦,不想吃饭的赶快过来啦.";
        $data = sprintf($textTpl, $str, $content);//填充模板
        echo $data;
        $token = $this->getAccessToken();       //获取token值
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$token}";

        return $this->curlSendDatePost($url, $data);//发送数据

    }

}



$mode = new wxModel();
$arr = $mode->sendCrowdData();


echo $arr ;


// { "touser":[ 
//      "oI9N8w31__SXd4VzbXmVgcFRkmoI",
//      "oI9N8w58sN-8DKyWVpXUAPL2wjSo",
//      "oI9N8w2l4oG1P6RogEAF1afxZ7lc" 
//  ],
//   "msgtype": "text",
//    "text": { "content": "菜肴更新啦,不想吃饭的赶快过来啦."} 
// }


// {
//    "touser":[
//     "OPENID1",
//     "OPENID2"
//    ],
//     "msgtype": "text",
//     "text": { "content": "hello from boxer."}
// }