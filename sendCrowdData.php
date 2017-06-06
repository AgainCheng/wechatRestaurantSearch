<?php

class wxModel
{
 

    /*
        curl��ȡ���ݷ���
    */
    public function  getData ( $url ) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        // 3. ִ��cURL����
        $ret = curl_exec($ch);
        // 4. �ر���Դ
        curl_close($ch);

        return $ret;
    }

    
    /*
        axxess_token���淽��
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
        ��ȡ���й�ע�û���ID,��������
    */
    public function getUserOpenId () 
    {
        $tokenId = $this->getAccessToken();

        $url =  "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$tokenId}";

        $arr = json_decode($this->getData($url), true);
  
        return $arr['data']['openid'] ;

    }
    /*
        curlPost���ͷ�ʽ
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

    
    //Ⱥ����Ϣ
    public function sendCrowdData () 
    {
        //����ģ��
        $textTpl  ='{
           "touser":[
                %s
           ],
            "msgtype": "text",
            "text": { "content": "%s"}
        }';

        $str = '"'.implode($this->getUserOpenId(), '","').'"';//��ȡ�ϲ��û��б��ַ���
        $content = "���ȸ�����,����Է��ĸϿ������.";
        $data = sprintf($textTpl, $str, $content);//���ģ��
        echo $data;
        $token = $this->getAccessToken();       //��ȡtokenֵ
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$token}";

        return $this->curlSendDatePost($url, $data);//��������

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
//    "text": { "content": "���ȸ�����,����Է��ĸϿ������."} 
// }


// {
//    "touser":[
//     "OPENID1",
//     "OPENID2"
//    ],
//     "msgtype": "text",
//     "text": { "content": "hello from boxer."}
// }