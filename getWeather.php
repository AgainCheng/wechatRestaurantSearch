<?php
	echo '123';

	// include('./proving.php');

	// $mode =  new wxModel();

	// $arr = $mode->getUserOpenId();

	// var_dump($arr);



	$area = '广州';
	$key = '';
	$dtype = '2';

	$url = "http://v.juhe.cn/weather/index";
	$url .="?cityname={$area}";
	$url .="&dtype={$dtype}";
	$url .="&format=";
	$url .="&key={$key}";

	echo "<pre>";
	$arr =  json_decode( curlSendDateGet($url), 1 );
	$num = 0 ;

	
	foreach( $arr as  $v){
		var_dump($v);
		if($num == 2){
			return false;
		}
		$num +=1 ;
		
	}
	

	 function  curlSendDateGet ( $url ) 
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

    