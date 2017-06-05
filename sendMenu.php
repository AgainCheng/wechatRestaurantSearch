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
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
                 "type":"view",
                 "name":"wxa",
                 "url":"http://mp.weixin.qq.com",
             },
            {
               "type":"click",
               "name":"点我",
               "key":"V1001_GOOD"
            }]
       },

       { 
          "type":"click",
          "name":"制作方法",
          "key":"get-makeFunc"
      }

      ]
 },

';

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
                "key":"getde4"
            },
            {
               "type":"click",
               "name":"3",
                "key":"getde3"
             },
              {
               "type":"click",
               "name":"2",
                "key":"getde2"
             },
            {
               "type":"click",
               "name":"1",
                "key":"getde1"
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







