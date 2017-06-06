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
          "name":"抽取",
          "key":"get-cuisine"
      },
      {  
          "type":"click",
          "name":"填充",
          "key":"padding"
      },

       { 
          "type":"click",
          "name":"定位",
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







