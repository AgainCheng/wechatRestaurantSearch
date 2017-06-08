<?php
	

	include('./demo/getMenuData.php');
	include('./demo/wecheMode.php');
	$wx = new wxModel();
	$arr =  $wx->getUserInfo();


	// 这个字段传回来是个数组,防止有值时报错,将其转为json,存入数据库
	$arr['privilege'] = json_encode($arr['privilege']);


	$userID = $arr['openid'];
	//引入数据库操作类
   	$menuObj  =  new cuisineApp($userID);
   	$menuObj->wirteUInfoDatabase($arr, 'userInfo', 'openid');






