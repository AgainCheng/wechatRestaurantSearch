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
          "key":"get"
      },
      {  
          "type":"click",
          "name":"搜索",
          "key":"pin"
      },

       { 
          "name": "更多", 
          "sub_button": [
              {
                  "type": "scancode_waitmsg", 
                  "name": "进入", 
                  "key": "rselfmenu_0_0", 
                  "sub_button": [ ]
              }, 
              {
                  "type": "scancode_push", 
                  "name": "APP下载", 
                  "key": "rselfmenu_0_1", 
                  "sub_button": [ ]
              },
              {
                  "type": "scancode_push", 
                  "name": "添加店铺", 
                  "key": "rselfmenu_0_1", 
                  "sub_button": [ ]
              },
              {
                  "type": "scancode_push", 
                  "name": "往期文章", 
                  "key": "rselfmenu_0_1", 
                  "sub_button": [ ]
              },
              {
                  "name": "发送位置", 
                  "type": "location_select", 
                  "key": "rselfmenu_2_0"
              },
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







