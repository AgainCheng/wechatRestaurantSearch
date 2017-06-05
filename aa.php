<?php

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


var_dump( json_encode( $arr ) );