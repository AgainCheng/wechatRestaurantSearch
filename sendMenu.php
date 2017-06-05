<?php
	
   	echo"123";
	session_start();

$arr = array(
	array(
		  "type"=>"click",
          "name"=>"今日歌曲",
          "key"=>"V1001_TODAY_MUSIC"
		),
	array(
		'name'=>'下拉菜单',
		'sub_button'=>array(
				array(
				  "type"=>"click",
		          "name"=>"今日歌曲",
		          "key"=>"V1001_TODAY_MUSIC"
				),
				array(
				  "type"=>"click",
		          "name"=>"今日歌曲",
		          "key"=>"V1001_TODAY_MUSIC"
				),
				array(
				  "type"=>"click",
		          "name"=>"今日歌曲",
		          "key"=>"V1001_TODAY_MUSIC"
				),
		),
	),	

	array(
	  	 "type"=>"click",
         "name"=>"今日歌曲",
         "key"=>"V1001_TODAY_MUSIC"
	),

);



$data = '
	{
     "button":[
     {	
          "type":"click",
          "name":"今日歌曲",
          "key":"V1001_TODAY_MUSIC"
      },
      {
           "name":"菜单",
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
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       }]
 }
';


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


}

	$aa = new wxModel();

	$token  = $aa->getAccessToken();

	$jsonObj = json_encode( $arr );

	// var_dump($jsonObj);

 	$url  =  "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=LAkzRJOaLLKPBcbUlcCNlhWZrq-awOhk9MM2cUzBbLF2E2uDjUKwO4tq31KhKuRDw9NRVcp5wNfvzpHVwKE5ROZpF4u7oDs21SwvGDloMo_SOq4-dd-QG_gjm1oCoajaXKYcACADQE";
 	echo "<br>";

 	echo $url ; 
 	echo "<br>";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	echo "<br>";
	curl_close($ch);

	var_dump($tmpInfo);



// 　　 $url = "http://localhost/web_services.php";
// 　　$post_data = array ("username" => "bob","key" => "12345");

// 　　$ch = curl_init();

// 　　curl_setopt($ch, CURLOPT_URL, $url);
// 　　curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 　　// post数据
// 　　curl_setopt($ch, CURLOPT_POST, 1);
// 　　// post的变量
// 　　curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

// 　　$output = curl_exec($ch);
// 　　curl_close($ch);

// 　　//打印获得的数据
// 　　print_r($output);




