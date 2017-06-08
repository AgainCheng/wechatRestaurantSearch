<?php
	
    echo "修改菜单";

    include('wecheController.php');
    $weche = new wecheController();
    $token =  $weche->getAccessToken();



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
          "name":"搜索",
          "key":"pin"
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







