<?php
   
  
class wecheController
{

    public $appid = "wxa678c4e0756b1969";
    public $appsecret  = "cd204ae4316019628ce439001280e579";

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
        返回accesss_token
    */
    public function  getAccessToken ()
    {

        session_start();
        if( $_SESSION['access_token'] && (time() - $_SESSION['expire_time']) < 7200 )
        {
            return $_SESSION['access_token'];
        }else{

            $appid = $this->appid;
            $secret = $this->appsecret;

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
            
            $token =  json_decode($this->curlSendDateGet($url) );

            $token = $token->access_token;

            $_SESSION['access_token'] =  $token;
            $_SESSION['expire_time'] = time();
            return $token;
        }
    }


    /*
        返回所有关注用户ID
    */
    public function getUserOpenId () 
    {
        $tokenId = $this->getAccessToken();

        $url =  "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$tokenId}";

        $arr = json_decode($this->curlSendDateGet($url), true);
  
        return $arr['data']['openid'] ;
    }


    /*
        返回授权连接
    */
    public function getEmpowerLink () 
    {
        $appid = $this->appid;
        $redirect_uri = 'http://119.23.204.96/weixin/loginTest.php';
        $scope = 'snsapi_userinfo';

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize";
        $url .= "?appid={$appid}";
        $url .= "&redirect_uri={$redirect_uri}";
        $url .= "&response_type=code";
        $url .= "&scope={$scope}";
        $url .= "&state=STATE#wechat_redirect";
        
        return $url;
    }


    /*
        返回授权用户信息
    */
    public function getUserInfo () 
    {
        //准备
        $code = $_GET['code'];
        $appid = $this->appid;
        $appsecret =  $this->appsecret;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=". $code ."&grant_type=authorization_code ";

        //发送code获取access_token;
        $access_token_arr = json_decode($this->curlSendDateGet($url),1);


        //准备参数
        $access_token = $access_token_arr['access_token'];
        $openid = $access_token_arr['openid'];
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        //发送access_token获取用户信息
        $userInfoArr = json_decode($this->curlSendDateGet($url), 1);


        return $userInfoArr;

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



}

?>
