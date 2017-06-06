<?php
	
    echo"123";
	   session_start();
    include("./demo/token.php");
  	$aa = new token();
  	$token  = $aa->getAccessToken();

 	  $url  =  "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";

$data = '
  {
     "button":[
     {  
          "type":"click",
          "name":"获取",
          "key":"get-cuisine"
      },
      {
           "name":"难度",
           "sub_button":[
           {  
               "type":"click",
               "name":"4",
                "key":"4"
            },
            {
               "type":"click",
               "name":"3",
                "key":"3"
             },
              {
               "type":"click",
               "name":"2",
                "key":"2"
             },
            {
               "type":"click",
               "name":"1",
                "key":"1"
            }]
       },
       { 
          "type":"click",
          "name":"制作方法",
          "key":"get-makeFunc"
        },
      ]
 },

';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$tmpInfo = curl_exec($ch);

		curl_close($ch);
		var_dump($tmpInfo);







